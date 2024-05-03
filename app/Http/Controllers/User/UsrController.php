<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Models\Notification;
use App\Models\User;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\UserCampaignHistoryModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmailJob;
use App\Models\Feedback;
use App\Models\MailTemplate;
use App\Models\ratings;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\TaskProgression;
use App\Models\taskProgressionUserHistory;
use App\Services\TwilioService;
use Illuminate\Support\Facades\URL;

class UsrController extends Controller
{


    function index()
    {
        $getdomain = Helper::getdomain();

        if (!empty($getdomain) && $getdomain == config('app.pr_name')) {
            return redirect()->route('company.signup');
        }
        if (!empty(auth()->user()) && auth()->user()->user_type == 1) {
            return redirect()->route('admin.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == 2) {
            return redirect()->route('company.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == 4) {
            return redirect()->route('user.dashboard');
        } else {
            $siteSetting = Helper::getSiteSetting();
            return view('user.userlogin', compact('siteSetting'));
        }
    }


    public function sendSMS(Request $request, TwilioService $twilioService)
    {
        $to = '+18777804236';
        $message = 'hello123';

        try {
            $twilioService->sendSMS($to, $message);
            echo "SMS sent successfully";
        } catch (Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
            echo "Failed to send SMS: " . $e->getMessage();
        }
    }

    public function dashboard()
    {
        try {
            $campaignList = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('user_id', Auth::user()->id)->take(10)->get();
            $totalJoinedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '1')->where('user_id', Auth::user()->id)->get();
            $totalCompletedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '3')->where('user_id', Auth::user()->id)->get();
            $totalReferralUser = User::where('referral_user_id', Auth::user()->id)->get();
            $totalReward = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('user_id', Auth::user()->id)->where('status', '3')->sum('reward');
            $chartReward = UserCampaignHistoryModel::where('user_id', Auth::user()->id)->select(DB::raw('DATE(created_at) AS day'), DB::raw('SUM(reward) AS total_day_reward'))->whereDate('created_at', '>=', Carbon::now()->subDays(10)->format("Y-m-d"))->where('status', '3')->groupBy('day')->get()->toArray();

            $dateandtime = Carbon::now();
            $start_date = $dateandtime->subDays(7);
            $start_time = strtotime($start_date);
            $end_time = strtotime("+1 week", $start_time);

            for ($i = $start_time; $i < $end_time; $i += 86400) {
                $chartRevenueData[(int)date('d', $i)] = 0;
            }
            foreach ($chartReward as $values) {
                $chartRevenueData[(int) date("d", strtotime($values['day']))] = $values['total_day_reward'];
            }
            $chartRevenueData = (['day' => array_keys($chartRevenueData), 'revenue' => array_values($chartRevenueData)]);

            $userData = User::get();
            $data = [];
            $data['total_comapny'] = 0;
            $data['total_user'] = 0;
            $data['total_campaign'] = 0;
            $data['total_package'] = 0;
            return view('user.dashboard', compact('userData', 'data', 'campaignList', 'totalJoinedCampaign', 'totalCompletedCampaign', 'totalReward', 'chartReward', 'totalReferralUser', 'chartRevenueData'));
        } catch (Exception $e) {
            Log::error('UsrController::Dashboard => ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $host = $request->getHost();
            $domain = explode('.', $host);
            $CompanyModel = new CompanyModel();
            $exitDomain = $CompanyModel->checkDmain($domain['0']);
            $companyId = $exitDomain->user_id;
            $companyActive = User::where('id', $companyId)->where('user_type', '2')->where('status', '1')->first();
            if (empty($companyActive)) {
                return redirect()->back()->with('error', 'Please contact to Company administrator.');
            }
            $userActive = User::where('email', $request->email)->where('user_type', '4')->where('status', '1')->first();
            if (empty($userActive)) {
                return redirect()->back()->with('error', 'Please contact to Company administrator.');
            }

            $input = $request->all();
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'company_id' => $companyId, 'status' => '1', 'user_type' => '4'))) {
                if (Session('referral_link') != null) {
                    $referral_link = Session('referral_link');
                    $lastSegment = Str::of($referral_link)->afterLast('/'); //referral_link
                    $user_plan = UserCampaignHistoryModel::where('referral_link', $lastSegment->value)->first();
                    $id = base64_encode($user_plan->campaign_id);
                    return redirect()->route('user.campaign.view', $id);
                }
                if (Session('join_link') != null) {
                    $join_link = Session('join_link');
                    return redirect()->route('user.campaign.view', $join_link);
                }
                if (Session('questions_create') != null) {
                    return redirect()->route('community.questions.create');
                }
                return redirect()->route('user.dashboard');
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }
        } catch (Exception $e) {
            Log::error('UsrController::login => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    function campaign()
    {
        try {
            return view('user.campaign.list');
        } catch (Exception $e) {
            Log::error('UsrController::Campaign => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function campaignview()
    {
        try {

            return view('user.campaign.view');
        } catch (Exception $e) {
            Log::error('UsrController::campaignview => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function changePasswordStore(Request $request)
    {

        try {
            $currentPasswordStatus = Hash::check($request->current_password, Auth::user()->password);
            if ($currentPasswordStatus) {

                User::findOrFail(Auth::user()->id)->update([
                    'password' => Hash::make($request->password),
                    'view_password' => $request->password,
                ]);

                return redirect()->back()->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Old Password does not match');
            }
        } catch (Exception $e) {
            Log::error('UsrController::ChangePasswordStore => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function editProfile()
    {

        try {
            $userData = Auth::user();
            $country_data = CountryModel::all();
            $state_data = StateModel::all();
            $city_data = CityModel::all();

            return view('user.editprofile', compact('userData', 'country_data', 'state_data', 'city_data'));
        } catch (Exception $e) {
            Log::error('UsrController::EditProfile => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function editProfileStore(Request $request)
    {
        try {

            $profileId = Auth::user()->id;

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'contact_number' => 'required|numeric|digits:10',
                // 'profile_image' => 'file|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $userEmail = User::where('company_id', Auth::user()->company_id)->where('email', $request->email)->where('email', '!=', $request->hidden_email)->first();

            if (isset($userEmail) && $userEmail != null) {
                return redirect()->back()->withErrors($validator)->with('error', 'User email id already exists.')->withInput();
            }
            $userNumber = User::where('company_id', Auth::user()->company_id)->where('contact_number', $request->contact_number)->where('contact_number', '!=', $request->hidden_contact_number)->first();

            if (isset($userNumber) && $userNumber != null) {
                return redirect()->back()->withErrors($validator)->with('error', 'User Mobile Number already exists.')->withInput();
            }

            $profileEdit = User::where('id', $profileId)->first();

            $profileEdit->first_name = $request->first_name;
            $profileEdit->last_name = $request->last_name;
            $profileEdit->email = $request->email;
            $profileEdit->contact_number = $request->contact_number;
            $profileEdit->country_id = $request->country;
            $profileEdit->state_id = $request->state;
            $profileEdit->city_id = $request->city;


            if ($request->hasFile('profile_image')) {

                if (\File::exists(base_path() . '/uploads/user/user-profile/' . $profileEdit->profile_image)) {
                    \File::delete(base_path() . '/uploads/user/user-profile/' . $profileEdit->profile_image);
                }

                $extension = $request->file('profile_image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('profile_image')->move(base_path() . '/uploads/user/user-profile', $image);
                $profileEdit->profile_image = $image;
            }

            $profileEdit->save();


            return redirect()->route('user.edit_profile')->with('success', "Profile Updated Successfully!");
        } catch (Exception $e) {
            Log::error('UsrController::EditProfileStore => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function socialAccount(Request $request)
    {
        try {
            $profileId = Auth::user()->id;
            $socialAccount = User::where('id', $profileId)->first();
            $socialAccount->facebook_link = $request->facebook_link;
            $socialAccount->instagram_link = $request->instagram_link;
            $socialAccount->twitter_link = $request->twitter_link;
            $socialAccount->youtube_link = $request->youtube_link;

            $socialAccount->save();
            return redirect()->route('user.edit_profile')->with('success', "Social account link updated successfully!");
        } catch (Exception $e) {
            Log::error('UsrController::SocialAccount => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function bankDetail(Request $request)
    {
        try {
            $profileId = Auth::user()->id;
            $bankDetail = User::where('id', $profileId)->first();
            $bankDetail->bank_name = $request->bank_name;
            $bankDetail->ac_holder = $request->ac_holder;
            $bankDetail->ifsc_code = $request->ifsc_code;
            $bankDetail->paypal_id = $request->paypal_id;
            $bankDetail->stripe_id = $request->stripe_id;
            $bankDetail->ac_no = $request->ac_no;

            $bankDetail->save();
            return redirect()->route('user.edit_profile')->with('success', "Bank detail updated successfully!");
        } catch (Exception $e) {
            Log::error('UsrController::BankDetail => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function Profile()
    {
        try {
            $userData = Auth::user()->load('country', 'state', 'city');
            $referralUser = User::orderBy('id', 'DESC')->where('referral_user_id', Auth::user()->id)->get();

            $progressions = taskProgressionUserHistory::with('taskProgressionHistory')
                ->where('user_id', Auth::user()->id)
                ->where('company_id', Auth::user()->company_id)
                ->orderBy('id', 'desc')
                ->get();

            return view('user.profile', compact('userData', 'referralUser', 'progressions'));
        } catch (Exception $e) {
            Log::error('UsrController::Profile => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function myreward(Request $request)
    {
        try {
            $filter = UserCampaignHistoryModel::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->whereIn('status', [3, 4]);
            if ($request->filled('from_date')) {
                $from_date = date("Y-m-d", strtotime($request->from_date));
                $filter->whereDate('created_at', '>=', $from_date);
            }

            if ($request->filled('two_date')) {
                $two_date = date("Y-m-d", strtotime($request->two_date));
                $filter->whereDate('created_at', '<=', $two_date);
            }

            if ($request->filled('type')) {
                $type = $request->type;
                $filter->whereHas('getCampaign', function ($query) use ($type) {
                    return $query->where('type', '=', $type);
                });
            }

            if ($request->filled('status')) {
                $status = $request->status;
                $filter->where('status', '=', $status);
            }

            $filterResults = $filter->get();
            return view('user.reward.myReward', compact('filterResults'));
        } catch (Exception $e) {
            Log::error('UsrController::Myreward => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function progressreward(Request $request)
    {
        try {
            $filter = UserCampaignHistoryModel::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->whereIn('status', [1, 2, 5]);

            if ($request->filled('from_date')) {
                $from_date = date("Y-m-d", strtotime($request->from_date));
                $filter->whereDate('created_at', '>=', $from_date);
            }

            if ($request->filled('two_date')) {
                $two_date = date("Y-m-d", strtotime($request->two_date));
                $filter->whereDate('created_at', '<=', $two_date);
            }

            if ($request->filled('type')) {
                $type = $request->type;
                $filter->whereHas('getCampaign', function ($query) use ($type) {
                    return $query->where('type', '=', $type);
                });
            }

            if ($request->filled('status')) {
                $status = $request->status;
                $filter->where('status', '=', $status);
            }

            $filterResults = $filter->get();

            return view('user.reward.progressreward', compact('filterResults'));
        } catch (Exception $e) {
            Log::error('UsrController::Progressreward => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function reopen(UserCampaignHistoryModel $reopen)
    {
        try {
            $reopen->status = '5';

            $reopen->save();
            if (isset($reopen)) {
                $Notification = new Notification();
                $Notification->user_id =  $reopen->user_id;
                $Notification->company_id =  $reopen->getCampaign->company_id;
                $Notification->type =  '2';
                $Notification->title =  "Reopen request";
                $Notification->message =  $reopen->getCampaign->title . " Reopen request by " . $reopen->getuser->FullName;
                $Notification->save();
            }

            return redirect()->back()->with('success', "Reopen requested successfully!");
        } catch (Exception $e) {
            Log::error('UsrController::Reopen => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function claimReward($id)
    {
        try {
            $claimReward = UserCampaignHistoryModel::where('id', $id)->first();


            $claimReward->status = '2';

            $claimReward->save();

            if (isset($claimReward)) {
                $Notification = new Notification();
                $Notification->user_id =  $claimReward->user_id;
                $Notification->company_id =  $claimReward->getCampaign->company_id;
                $Notification->type =  '2';
                $Notification->title =  " Campaign approval request";
                $Notification->message =  $claimReward->getCampaign->title . " approval request by " . $claimReward->getuser->FullName;

                $Notification->save();
            }

            return redirect()->back()->with('success', "Claim Reward requested successfully!");
        } catch (Exception $e) {
            Log::error('UsrController::ClaimReward => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function analytics(Request $request)
    {
        try {
            $year = request('year') ?: date("Y");

            $topFromDate = request('top_from_date');
            $topToDate = request('top_to_date');

            $monthlyReferralsData = User::select(DB::raw('COUNT(*) as user_count'), DB::raw('DATE_FORMAT(created_at, "%b") as month'))
                ->where('referral_user_id', Auth::user()->id)
                ->whereYear('created_at', '=', $year)
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b")'))
                ->get()
                ->toArray();
            $monthlyReferrals  = ['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0];

            foreach ($monthlyReferralsData as $result) {
                $monthlyReferrals[$result['month']] = $result['user_count'];
            }
            $monthlyReferrals = ['months' => array_keys($monthlyReferrals), 'data' => array_values($monthlyReferrals)];
            $topUserReferral = UserCampaignHistoryModel::whereExists(function ($query) {
                $query->from('users')
                    ->whereRaw('user_campaign_history.user_id = users.id')
                    ->where('users.referral_user_id', Auth::user()->id)
                    ->where('user_campaign_history.status', '3')
                    ->whereNotNull('users.referral_user_id');
            })


                ->when($topFromDate, function ($query) use ($topFromDate) {
                    return $query->where('created_at', '>=', $topFromDate);
                })
                ->when($topToDate, function ($query) use ($topToDate) {
                    return $query->where('created_at', '<=', $topToDate);
                })
                ->groupBy('user_campaign_history.user_id')
                ->with(['getuser' => function ($query) {
                    $query->select('id', 'first_name');
                }])
                ->selectRaw('user_campaign_history.user_id,Sum(reward) as sum')
                ->orderBy('sum', 'DESC')->take(5)
                ->get()->toArray();

            if ($request->ajax()) {
                return [
                    "monthlyReferrals" => $monthlyReferrals,
                    "topUserReferral" => $topUserReferral,
                ];
            }

            return view('user.analytics', compact('monthlyReferrals', 'topUserReferral'));
        } catch (Exception $e) {
            Log::error('UsrController::Analytics => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    function notification()
    {
        try {
            $user = Auth::user();
            $notifications = Notification::orderBy('id', 'DESC')->where('user_id', $user->id)->where('type', '1')->get();
            foreach ($notifications as $notification) {
                $notification->is_read = '1';
                $notification->save();
            }
            return view('user.notification', compact('notifications'));
        } catch (Exception $e) {
            Log::error('UsrController::Notification => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function signup()
    {

        try {
            $companyId = Helper::getCompanyId();

            $companyActive = User::where('id', $companyId)->where('user_type', '2')->where('status', '1')->first();

            $country_data = CountryModel::all();


            if (empty($companyActive)) {
                return redirect()->back()->with('error', 'Please contact to Company administrator.');
            }
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'Please  inform you company administer')->withInput();
            }
            $siteSetting = Helper::getSiteSetting();

            return view('user.signup', compact('siteSetting', 'country_data'));
        } catch (Exception $e) {
            Log::error('UsrController::Signup => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function get_states(Request $request)
    {
        $country_id = $request->input('country_id');

        $states = StateModel::where('country_id', $country_id)->get();

        $options = '';
        $options .= "<option value=''>Select state</option>";
        foreach ($states as $state) {
            $options .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        // Return the options as JSON response
        return response()->json($options);
    }


    public function get_city(Request $request)
    {
        $state_id = $request->input('state_id');

        $citys = CityModel::where('state_id', $state_id)->get();
        $options = '';
        $options .= "<option value=''>Select City</option>";
        foreach ($citys as $city) {
            $options .= "<option value='" . $city->id . "'>" . $city->name . "</option>";
        }
        return response()->json($options);
    }



    public function store(Request $request)
    {
        try {
            $host = $request->getHost();
            $domain = explode('.', $host);
            $CompanyModel = new CompanyModel();
            $exitDomain = $CompanyModel->checkDmain($domain['0']);
            $companyId = $exitDomain->user_id;

            $userEmail = User::where('user_type', 4)->where('email', $request->email)->where('company_id', $companyId)->first();
            if (!empty($userEmail)) {
                return redirect()->back()->with('error', 'This email already exists');
            }
            $usercontactnumber = User::where('user_type', 4)->where('contact_number', $request->contact_number)->where('company_id', $companyId)->first();
            if (!empty($usercontactnumber)) {
                return redirect()->back()->with('error', 'This contact number already exists');
            }
            if (isset($request->referral_code)) {
                $referrer_user = User::where('referral_code', $request->referral_code)->where('referral_code', '!=', null)->where('company_id', $companyId)->first();
            }
            // Get domain
            $host = $request->getHost();
            $domain = explode('.', $host);
            $CompanyModel = new CompanyModel();
            $exitDomain = $CompanyModel->checkDmain($domain['0']);
            $companyId = $exitDomain->user_id;
            $ActivePackageData = Helper::GetActivePackageData($companyId);

            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['USER'])->count();
            if ($userCount >= $ActivePackageData->no_of_user) {
                return redirect()->back()->with('error', 'The user registration limit is over. please contact to administrator.');
            }
            $user_id = null;
            if (Session('referral_link') != null) {
                $referral_link = Session('referral_link');
                $lastSegment = Str::of($referral_link)->afterLast('/'); //referral_link
                $user = UserCampaignHistoryModel::where('referral_link', $lastSegment->value)->first();
                $user_id =  $user->user_id;
            } else {
                !empty($referrer_user) ? $user_id = $referrer_user->id : null;
            }


            $userRegister = new User();
            $userRegister->first_name = $request->first_name;
            $userRegister->last_name = $request->last_name;
            $userRegister->email = $request->email;
            $userRegister->country_id = $request->country;
            $userRegister->state_id = $request->state;
            $userRegister->city_id = $request->city;
            $userRegister->user_type = '4';
            $userRegister->company_id = $companyId;
            $userRegister->referral_code = Str::random(6);
            $userRegister->password = Hash::make($request->password);
            $userRegister->view_password = $request->password;
            $userRegister->contact_number = $request->contact_number;
            $userRegister->referral_user_id = !empty($user_id) ? $user_id : null;
            $userRegister->package_id = $ActivePackageData->id;
            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }

            try {

                $SettingValue = SettingModel::where('id', $companyId)->first();
                $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'welcome')->first();
                $userName  = $request->fname . ' ' . $request->lname;
                $to = $request->email;

                $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : '';
                $settingTitle = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : env('APP_NAME');
                $subject = !empty($mailTemplateSubject) ? $mailTemplateSubject : 'Welcome To ' . $settingTitle;

                $message = '';
                $type =  "user";
                $html =  $mailTemplate->template_html;

                $data =  ['user' => $userRegister, 'first_name' => $request->first_name, 'company_id' => $companyId, 'template' => $html, 'webUrl' => $webUrl];
                if ((config('app.sendmail') == 'true' && config('app.mailSystem') == 'local') || (config('app.mailSystem') == 'server')) {
                    SendEmailJob::dispatch($to, $subject, $message, $userName, $data, $type, $html);
                }
            } catch (Exception $e) {
                Log::error('UsrController::Store => ' . $e->getMessage());
            }
            // End mail
            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'welcome')->first();
            if (!empty($smsTemplate)) {
                $SettingModel = SettingModel::first();
                if (!empty($companyId)) {
                    $SettingModel = SettingModel::find($companyId);
                }
                if (!empty($SettingModel) && !empty($SettingModel->sms_account_sid) && !empty($SettingModel->sms_account_token) && !empty($SettingModel->sms_account_number)) {
                    $name = $request->first_name;
                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                    $company_link = $webUrl ? $webUrl : '';
                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]"], [$name, $company_title, $company_link], $smsTemplate->template_html_sms);

                    // Remove HTML tags and decode HTML entities
                    $message = htmlspecialchars_decode(strip_tags($html));

                    // Remove unwanted '&nbsp;' text
                    $message = str_replace('&nbsp;', ' ', $message);

                    $to = $SettingModel->type == "2" ? $request->contact_number : $SettingModel->sms_account_to_number;
                    $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                    try {
                        $twilioService->sendSMS($to, $message);
                    } catch (Exception $e) {
                        Log::error('Failed to send SMS: ' . $e->getMessage());
                        echo "Failed to send SMS: " . $e->getMessage();
                    }
                }
            }

            // End sms


            // try {
            //     Mail::send('user.email.welcome', ['user' => $userRegister, 'first_name' => $request->first_name], function ($message) use ($request) {
            //         $message->to($request->email);
            //         $message->subject('Welcome Mail');
            //     });
            // } catch (Exception $e) {
            //     Log::error('UsrController::Store => ' . $e->getMessage());
            // }

            $userRegister->save();
            $message = "Registration Successfully!";

            return redirect()->route('user.login')->with('success', $message);
        } catch (Exception $e) {
            Log::error('UsrController::Store => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function forget(Request $request)
    {
        try {
            $getdomain = Helper::getdomain();

            if (!empty($getdomain) && $getdomain == config('app.pr_name')) {
                return redirect(env('ASSET_URL') . '/company/signup');
            }

            $siteSetting = Helper::getSiteSetting();

            return view('user.forgetPassword', compact('siteSetting'));
        } catch (Exception $e) {
            Log::error('UsrController::Forget => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function submitForgetPassword(Request $request)
    {


        try {
            $companyId = Helper::getCompanyId();

            $userEmail = User::where('company_id', $companyId)->where('email', $request->email)->where('user_type', '4')->first();

            if (empty($userEmail)) {
                return redirect()->back()->with('error', 'Something went wrong.')->withInput();
            }

            $token = Str::random(64);
            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'forgot_password')->first();
            $html = "";
            $webUrl = "";
            $submit = route('user.confirmPassword', $token);
            $currentUrl = URL::current();
            $webUrlGetHost = $request->getHost();
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }
            if (!empty($mailTemplate)) {
                $html = $mailTemplate->template_html;
            }
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            try {
                $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Reset Password';
                Mail::send('user.email.forgetPassword', [
                    'token' => $token,
                    'name' => $userEmail->FullName,
                    'company_id' => $companyId,
                    'template' => $html,
                    'webUrl' => $webUrl
                ], function ($message) use ($request, $mailTemplateSubject) {
                    $message->to($request->email);
                    $message->subject($mailTemplateSubject);
                });
            } catch (Exception $e) {
                Log::error('UsrController:: => ' . $e->getMessage());
                return redirect()->back()->with('error', "Something went wrong!");
            }
            Log::error('UsrController:: mail send');
            //Start sms
            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'forgot_password')->first();

            if (!empty($smsTemplate)) {
                Log::error('UsrController:: check for sms');

                $SettingModel = SettingModel::first();
                if (!empty($companyId)) {
                    $SettingModel = SettingModel::find($companyId);
                }
                if (!empty($SettingModel) && !empty($SettingModel->sms_account_sid) && !empty($SettingModel->sms_account_token) && !empty($SettingModel->sms_account_number)) {
                    $name = $userEmail->first_name;
                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                    $company_link = $webUrl ? $webUrl : '';
                    $submit = route('user.confirmPassword', $token);
                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[change_password_link]"], [$name, $company_title, $company_link, $submit], $smsTemplate->template_html_sms);
                    $message = htmlspecialchars_decode(strip_tags($html));

                    // Remove unwanted '&nbsp;' text
                    $message = str_replace('&nbsp;', ' ', $message);


                    try {
                        $to = $SettingModel->type == "2" ? $userEmail->contact_number : $SettingModel->sms_account_to_number;
                        $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                        Log::error('UsrController:: going to send sms');
                        $twilioService->sendSMS($to, $message);
                        Log::error('UsrController:: sms send');
                    } catch (Exception $e) {
                        Log::error('Failed to send SMS: ' . $e->getMessage());
                        echo "Failed to send SMS: " . $e->getMessage();
                    }
                }
            }
            //End sms


            return back()->with('message', 'We have e-mailed your password reset link!');
        } catch (Exception $e) {
            Log::error('UsrController::SubmitForgetPassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function confirmPassword($token)
    {
        try {

            $user = DB::table('password_resets')->where('token', $token)->first();
            $siteSetting = Helper::getSiteSetting();

            return view('user.confirmPassword', compact('user', 'siteSetting'), ['token' => $token]);
        } catch (Exception $e) {
            Log::error('UsrController::ConfirmPassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function submitResetPassword(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();

            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token
                ])
                ->first();

            if (!$updatePassword) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            $user = User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password), 'view_password' => $request->password]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();
            $currentUrl = URL::current();
            $webUrlGetHost = $request->getHost();
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }
            if (!empty($mailTemplate)) {
                $html = $mailTemplate->template_html;
            }
            try {
                $user = User::where('email', $request->email)->where('company_id', $companyId)->first();

                $SettingValue = SettingModel::where('id', $companyId)->first();
                $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'change_pass')->first();

                $userName  = $user->first_name . ' ' . $user->last_name;
                $to = $request->email;
                // $subject = 'Welcome To '. !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : env('APP_NAME');

                $message = '';
                $type =  "user";
                $html =  $mailTemplate->template_html;

                Mail::send('user.email.passwordChange', ['user' => $user, 'first_name' => $userName, 'company_id' => $companyId, 'template' => $html, 'webUrl' => $webUrl], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject(!empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Your New Password Is Set');
                });
            } catch (Exception $e) {
                Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
            }
            //Start sms
            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'change_pass')->first();

            if (!empty($smsTemplate)) {
                $SettingModel = SettingModel::first();
                if (!empty($companyId)) {
                    $SettingModel = SettingModel::find($companyId);
                }
                if (!empty($SettingModel) && !empty($SettingModel->sms_account_sid) && !empty($SettingModel->sms_account_token) && !empty($SettingModel->sms_account_number)) {
                    $name = $user->first_name;
                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                    $company_link = $webUrl ? $webUrl : '';

                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]"], [$name, $company_title, $company_link,], $smsTemplate->template_html_sms);
                    $message = htmlspecialchars_decode(strip_tags($html));

                    // Remove unwanted '&nbsp;' text
                    $message = str_replace('&nbsp;', ' ', $message);

                    $to = $SettingModel->type == "2" ? $user->contact_number : $SettingModel->sms_account_to_number;
                    $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                    try {
                        $twilioService->sendSMS($to, $message);
                    } catch (Exception $e) {
                        Log::error('Failed to send SMS: ' . $e->getMessage());
                        echo "Failed to send SMS: " . $e->getMessage();
                    }
                }
            }
            //End sms
            return redirect()->route('user.login')->with('success', 'Your password has been changed!');
        } catch (Exception $e) {
            Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function addTaskRating(Request $request)
    {
        try {
            $user = Auth::user();
            $companyId = null; // Initialize companyId to null

            // Get the domain and company ID
            $host = $request->getHost();
            $domain = explode('.', $host);
            $CompanyModel = new CompanyModel();
            $exitDomain = $CompanyModel->checkDmain($domain['0']);

            if ($exitDomain) {
                $companyId = $exitDomain->user_id;
            } else {
                throw new \Exception("Domain not found");
            }

            // Check if the user has already rated the campaign
            $existingRating = ratings::where('user_id', $user->id)
                ->where('company_id', $companyId)
                ->where('campaign_id', $request->campaign_id)
                ->first();

            if (!empty($existingRating)) {
                // Update the existing rating
                $existingRating->no_of_rating = $request->emoji;
                $existingRating->comments = $request->comments;
                $existingRating->save();
            } else {
                // Create a new rating
                $ratings = new ratings();
                $ratings->user_id = $user->id;
                $ratings->company_id = $companyId;
                $ratings->campaign_id = $request->campaign_id;
                $ratings->no_of_rating = $request->emoji;
                $ratings->comments = $request->comments;
                $ratings->save();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('UsrController::addTaskRating => ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => "Error: " . $e->getMessage()]);
        }
    }

    public function addTaskFeedback(Request $request)
    {

        $user = Auth::user();
        $companyId = null; // Initialize companyId to null

        // Get the domain and company ID
        $host = $request->getHost();
        $domain = explode('.', $host);
        $CompanyModel = new CompanyModel();
        $exitDomain = $CompanyModel->checkDmain($domain['0']);

        if ($exitDomain) {
            $companyId = $exitDomain->user_id;
        } else {
            throw new \Exception("Domain not found");
        }

        // Check if the user has already rated the campaign
        $existingRating = Feedback::where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->where('campaign_id', $request->campaign_id)
            ->first();

        if (!empty($existingRating)) {
            // Update the existing rating
            $existingRating->no_of_rating = $request->no_of_rating;
            $existingRating->comments = $request->comments;
            $existingRating->save();
        } else {

            // Create a new rating
            $ratings = new Feedback();
            $ratings->user_id = $user->id;
            $ratings->company_id = $companyId;
            $ratings->campaign_id = $request->campaign_id;
            $ratings->no_of_rating = $request->no_of_rating;
            $ratings->comments = $request->comments;
            $ratings->save();
        }

        return response()->json(['success' => true]);

        // } catch (\Exception $e) {
        //     Log::error('UsrController::addTaskRating => ' . $e->getMessage());
        //     return response()->json(['success' => false, 'error' => "Error: " . $e->getMessage()]);
        // }

    }

    public function Logout()
    {
        try {
            Session::flush();

            Auth::logout();

            return redirect()->route('user.login');
        } catch (Exception $e) {
            Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
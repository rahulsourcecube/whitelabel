<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Models\Notification;
use App\Models\User;
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

class UsrController extends Controller
{


    function index()
    {
        if (!empty(auth()->user()) && auth()->user()->user_type == env('ADMIN_ROLE')) {
            return redirect()->route('admin.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == env('COMPANY_ROLE')) {
            return redirect()->route('company.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == env('USER_ROLE')) {
            return redirect()->route('user.dashboard');
        } else {
            return view('user.userlogin');
        }
    }

    public function dashboard()
    {
        try {

            $campaignList = UserCampaignHistoryModel::orderBy('campaign_id', 'DESC')->where('user_id', Auth::user()->id)->take(10)->get();
            $totalJoinedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '1')->where('user_id', Auth::user()->id)->get();
            $totalCompletedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '3')->where('user_id', Auth::user()->id)->get();
            $totalReferralUser = User::where('referral_user_id', Auth::user()->id)->get();
            $totalReward = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('user_id', Auth::user()->id)->sum('reward');
            $chartReward = UserCampaignHistoryModel::where('user_id', Auth::user()->id)->select(DB::raw('DATE(created_at) AS day'), DB::raw('SUM(reward) AS total_day_reward'))->whereDate('created_at', '>=', Carbon::now()->subDays(10)->format("Y-m-d"))->groupBy('day')->get()->toArray();

            $userData = User::get();
            $data = [];
            $data['total_comapny'] = 0;
            $data['total_user'] = 0;
            $data['total_campaign'] = 0;
            $data['total_package'] = 0;
            return view('user.dashboard', compact('userData', 'data', 'campaignList', 'totalJoinedCampaign', 'totalCompletedCampaign', 'totalReward', 'chartReward', 'totalReferralUser'));
        } catch (Exception $exception) {

            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function login(Request $request)
    {
        try {
            $input = $request->all();
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'status' => '1'))) {

                if (!empty(auth()->user()) &&  auth()->user()->user_type == env('USER_ROLE')) {

                    if(Session('referral_link') != null){
                        return redirect(Session('referral_link'));
                    }

                    return redirect()->route('user.dashboard');
                } else {
                    return redirect()->back()->with('error', 'These credentials do not match our records.');
                }
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    function campaign()
    {

        return view('user.campaign.list');
    }
    function campaignview()
    {

        return view('user.campaign.view');
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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function editProfile()
    {
        try {
            $userData = Auth::user();
            return view('user.editprofile', compact('userData'));
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
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
                'profile_image' => 'file|mimes:jpeg,png,jpg|max:2048',
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


            if ($request->hasFile('profile_image')) {

                if (\File::exists('uploads/user/user-profile/' . $profileEdit->profile_image)) {
                    \File::delete('uploads/user/user-profile/' . $profileEdit->profile_image);
                }

                $extension = $request->file('profile_image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('profile_image')->move('uploads/user/user-profile', $image);
                $profileEdit->profile_image = $image;
            }

            $profileEdit->save();


            return redirect()->route('user.edit_profile')->with('success', "Profile Updated Successfully!");
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
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
            $bankDetail->ac_no = $request->ac_no;

            $bankDetail->save();
            return redirect()->route('user.edit_profile')->with('success', "Bank detail updated successfully!");
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function Profile()
    {
        $userData = Auth::user();
        $referralUser = User::orderBy('id', 'DESC')->where('referral_user_id', Auth::user()->id)->get();
        return view('user.profile', compact('userData', 'referralUser'));
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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function progressreward(Request $request)
    {
        try {
            $filter = UserCampaignHistoryModel::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->whereIn('status', [1, 2]);

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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function claimReward($id)
    {
        try {
            $claimReward = UserCampaignHistoryModel::where('id', $id)->first();


            $user = Auth::user();

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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong");
        }
    }
    function analytics(Request $request)
    {
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $topFromDate = request('top_from_date');
        $topToDate = request('top_to_date');

        $monthlyReferrals = User::select(DB::raw('COUNT(*) as user_count'), DB::raw('MONTH(created_at) as month'))
            ->where('referral_user_id', Auth::user()->id)
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->where('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->where('created_at', '<=', $toDate);
            })
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()->toArray();

        $topUserReferral = UserCampaignHistoryModel::whereExists(function ($query) {
            $query->from('users')
                ->whereRaw('user_campaign_history.user_id = users.id')
                ->where('users.referral_user_id', Auth::user()->id)
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
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }
    public function signup()
    {
        return view('user.signup');
    }

    public function store(Request $request)
    {
        try {
            $userEmail = User::where('user_type', env('USER_ROLE'))->where('email', $request->email)->first();
            if (!empty($userEmail)) {
                return redirect()->back()->with('error', 'This email already exists');
            }
            $usercontactnumber = User::where('user_type', env('USER_ROLE'))->where('contact_number', $request->contact_number)->first();
            if (!empty($usercontactnumber)) {
                return redirect()->back()->with('error', 'This contact number already exists');
            }
            if (isset($request->referral_code)) {
                $referrer_user = User::where('referral_code', $request->referral_code)->where('referral_code', '!=', null)->first();
            }
            $companyId = User::where('user_type', '2')->where('status', '1')->orderBy('id', 'desc')->first();
            $userRegister = new User();
            $userRegister->first_name = $request->first_name;
            $userRegister->last_name = $request->last_name;
            $userRegister->email = $request->email;
            $userRegister->user_type = '4';
            $userRegister->company_id = $companyId->id;
            $userRegister->referral_code = Str::random(6);
            $userRegister->password = Hash::make($request->password);
            $userRegister->view_password = $request->password;
            $userRegister->contact_number = $request->contact_number;
            $userRegister->referral_user_id = !empty($referrer_user) ? $referrer_user->id : null;

            Mail::send('user.email.welcome', ['user' => $userRegister, 'first_name' => $request->first_name], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Welcome Mail');
            });

            $userRegister->save();

            return redirect()->route('user.login')->with('success', "Registration Successfully!");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function forget(Request $request)
    {

        return view('user.forgetPassword');
    }

    public function submitForgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);


            Mail::send('user.email.forgetPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('message', 'We have e-mailed your password reset link!');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function confirmPassword($token)
    {
        try {
            $user = DB::table('password_resets')->where('token', $token)->first();
            return view('user.confirmPassword', compact('user'), ['token' => $token]);
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function submitResetPassword(Request $request)
    {
        try {
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

            return redirect()->route('user.login')->with('success', 'Your password has been changed!');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function Logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('user.login');
    }
}

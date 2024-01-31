<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\company\forgetpass;
use App\Models\CampaignModel;
use App\Models\SettingModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Mail\company\CompanyWelcomeMail;

class CompanyLoginController extends Controller
{
    public function index()
    {
        //  Check domain
        $getdomain = Helper::getdomain();

        if (!empty($getdomain) && $getdomain == env('pr_name')) {
            return redirect(env('ASSET_URL') . '/company/signup');
        }
        //end     
        if (!empty(auth()->user()) && auth()->user()->user_type == '1') {
            return redirect()->route('admin.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == '2') {
            return redirect()->route('company.dashboard');
        } else {
            $siteSetting = Helper::getSiteSetting();
            return view('company.companylogin', compact('siteSetting'));
        }
    }
    function dashboard()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $companyId = Helper::getCompanyId();
        $data = [];
        $data['total_campaign'] = 0;
        $data['total_user'] = 0;
        $data['new_user'] = 0;
        $data['old_user'] = 0;
        $data['total_campaign'] = CampaignModel::where('company_id', $companyId)->where('status', '1')->count();
        $data['old_user'] = User::where('company_id', $companyId)->where('user_type', '4')->where(function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', '<>', $currentMonth)->orWhereYear('created_at', '<>', $currentYear);
        })->count();
        $data['new_user'] = User::where('company_id', $companyId)->where('user_type', '4')->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();
        $data['total_user'] = User::where('company_id', $companyId)->where('user_type', '4')->count();
        $data['total_campaignReq'] = $user_campaign_history = DB::table('users as u')
            ->where('u.company_id', $companyId)
            ->where('uch.status', '2')
            ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
            ->count();
        $data['referral_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '1')->orderBy("id", "DESC")->take(5)->get();
        $data['social_share_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '2')->orderBy("id", "DESC")->take(5)->get();
        $data['custom_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '3')->orderBy("id", "DESC")->take(5)->get();

        $start_time = strtotime('first day of this month');
        $end_time = strtotime(date("Y-m-d"));
        $chart_title = 'Day of the current month';
        if ($start_time == $end_time) {
            $start_time = strtotime('first day of last month');
            $end_time = strtotime('last day of last month');
            $chart_title = 'Day of the previous month';
        }
        // DB::enableQueryLog();
        $user_campaign_history = DB::table('users as u')
            ->where('u.company_id', $companyId)
            ->where('uch.status', '3')
            ->whereMonth('uch.updated_at', '=', date("m", $start_time))
            ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
            ->select(DB::raw('SUM(uch.reward) as total_reward , DAYOFMONTH(uch.updated_at) as day'))
            ->groupBy('day')
            ->get();     

        $list = [];
        for ($i = $start_time; $i <= $end_time; $i += 86400) {
            $list[(int)date('d', $i)] = 0;
        }
        foreach ($user_campaign_history as $values) {
            $list[$values->day] = $values->total_reward;
        }
        $user_reward_and_days = json_encode(['day' => array_keys($list), 'reward' => array_values($list)]);
        return view('company.dashboard', $data, compact('user_reward_and_days', 'chart_title'));
    }
    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'user_type' => '2'))) {
            return redirect()->route('company.dashboard');
        } elseif (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'user_type' => '3'))) {
            return redirect()->route('company.dashboard');
        } else {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }
    }
    public function loginWithToken(Request $request)
    {
        try {
            $user = User::where('token', $request->token)->first();
            if (auth()->attempt(array('email' => $user->email, 'password' => $user->view_password, 'user_type' => '2'))) {
                $user->update([
                    'token' =>  ""
                ]);
                return redirect()->route('company.dashboard');
            } else {
                return redirect()->route('company.login')->with('error', 'These credentials do not match our records.');
            }
        } catch (\Exception $e) {
            return redirect()->route('company.login');
        }
    }
    public function signup(Request $request)
    {
        $getdomain = Helper::getdomain();
        if (!empty($getdomain) && $getdomain != env('pr_name')) {
            return redirect(env('ASSET_URL') . '/company/signup');
        }
        //Helper::createCompanySubDomain('aa111');
        $siteSetting = Helper::getSiteSetting();
        return view('company.signup', compact('siteSetting'));
    }
    public function signupStore(Request $request)
    {

        try {
            $input = $request->all();
            $input['dname'] = strtolower($input['dname']);

            $useremail = User::where('email', $request->email)->where('user_type', '2')->first();
            if (!empty($useremail)) {
                return redirect()->back()->with('error', 'Email is already registered!!')->withInput();
            }
            $usercontact = User::where('contact_number', $request->ccontact)->where('user_type', '2')->first();
            if (!empty($usercontact)) {
                return redirect()->back()->with('error', 'Contact number is already registered!!')->withInput();
            }
            $subdomain = CompanyModel::where('subdomain',  $input['dname'])->first();
            if (!empty($subdomain)) {
                return redirect()->back()->with('error', 'Subdomain is already registered!!')->withInput();
            }

            $user = new User();
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->email = $request->email;
            $user->password = hash::make($request->password);
            $user->view_password = $request->password;
            $user->contact_number = $request->ccontact;
            $user->user_type = '2';
            $user->save();
            //
            $userName  = $request->fname . ' ' . $request->lname;
            \Mail::to($request->email)->send(new CompanyWelcomeMail('Welcome Mail', $userName));

            if (isset($user)) {
                $compnay = new CompanyModel();
                $compnay->user_id = $user->id;
                $compnay->company_name = $request->cname;
                $compnay->contact_email = $request->email;
                $compnay->subdomain = $input['dname'];
                $compnay->contact_number = $request->ccontact;
                $compnay->save();
                $token =  Hash::make($user->id);
                $user->update([
                    'company_id' => $compnay->id,
                    'token' => $token
                ]);
                $role = Role::where('name', 'Company')->first();
                $user->assignRole([$role->id]);
                $settingModel = new SettingModel();
                $settingModel->user_id = $user->id;
                $settingModel->title = $request->cname;               
                $settingModel->save();
            }
            return redirect()->to($request->getScheme() . '://' . $request->dname . '.' . $request->getHost() . '/company/companyLoginWithToken/?token=' . $token);
            /*if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {
                if (!empty(auth()->user()) &&  auth()->user()->user_type == '2') {
                    //$domain =  Helper::get_domaininfo($_SERVER['ASSET_URL']); 
                    return redirect($request->getScheme() .'://' . $request->dname .'.'. $request->getHost().'/company/dashboard');
                } else {
                    return redirect()->back()->with('error', 'These credentials do not match our records.');
                }
                Helper::createCompanySubDomain($input['dname']);
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }*/
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function forget()
    {
        $siteSetting = Helper::getSiteSetting();
        return view('company.forgetPassword', compact('siteSetting'));
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


            Mail::send('company.email.forgetPassword', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('success', 'We have e-mailed your password reset link!');
        } catch (Exception $exception) {
            
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }
    public function forgetPassSendmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $user = User::where('email', $request->email)->first();
            if (!empty($user)) {
                $mailData = [
                    "email" => $request->email,
                    "_token" => $request->_token
                ];
                $details = $user;
                Mail::to($request->email)->send(new forgetpass($details));

                return redirect()->back()->with('success', 'Mail Send Successfully');
            } else {
                return redirect()->back()->with('error', 'email not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function confirmPassword($token)
    {
        try {
            $user = DB::table('password_resets')->where('token', $token)->first();
            $siteSetting = Helper::getSiteSetting();
            return view('company.confirmPassword', compact('user', 'siteSetting'), ['token' => $token]);
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

            return redirect()->route('company.signin')->with('success', 'Your password has been changed!');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function changePassword(Request $request, $id)
    {

        try {
            $userCheck = User::where('id', $id)->first();
            if (empty($userCheck)) {
                return redirect()->back()->with('error', 'User not found!');
            }
            $userCheck->password = Hash::make($request->new_password);
            $userCheck->view_password = $request->password;

            $userCheck->update();

            return redirect()->route('company.signin')->with('error', 'These credentials do not match our records.');
        } catch (Exception $e) {
            Log::info("change password in profile LogError" . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
    public function editProfile()
    {
        $editprofiledetail = User::where('id', Auth::user()->id)->first();
        return view('company.editprofile', compact('editprofiledetail'));
    }
    public function updateprofile(Request $request)
    {
        try {
            $updateprofiledetail = User::where('id', Auth::user()->id)->first();
            $updateprofiledetail['first_name'] = isset($request->first_name) ? $request->first_name : '';
            $updateprofiledetail['last_name'] = isset($request->last_name) ? $request->last_name : '';
            $updateprofiledetail['email'] = isset($request->email) ? $request->email : '';
            $updateprofiledetail['contact_number'] = isset($request->contact_number) ? $request->contact_number : '';
            if ($request->hasFile('profile_image')) {
                if ($updateprofiledetail->profile_image && file_exists(base_path().'/uploads/user-profile/') . $updateprofiledetail->profile_image) {
                    unlink(base_path().'/uploads/user-profile/' . $updateprofiledetail->profile_image);
                }
                $filename = rand(111111, 999999) . '.' . $request->profile_image->extension();
                $request->file('profile_image')->move(base_path().'/uploads/user-profile/', $filename);
                $updateprofiledetail['profile_image'] = isset($filename) ? $filename : '';
            }
            $updateprofiledetail->save();
            return redirect()->route('company.edit_profile')->with('success', 'Profile Update Successfully!');
        } catch (Exception $e) {
            Log::info(['message', 'Update Profile error']);
            return redirect()->back()->with($e->getMessage());
        }
    }
    public function Profile()
    {
        $companyId = Helper::getCompanyId();
        $profiledetail = User::where('id', Auth::user()->id)->first();
        $companydetail = SettingModel::where('user_id', $companyId)->first();
        $companyname = CompanyModel::where('user_id', $companyId)->first();
        return view('company.profile', compact('profiledetail', 'companydetail', 'companyname'));
    }
    public function updatepassword(Request $request)
    {
        try {
            $userCheck = User::where('id', Auth::user()->id)->first();
            if (empty($userCheck)) {
                return redirect()->back()->with('error', 'User not found!');
            }
            $userCheck->password = Hash::make($request->newPassword);
            $userCheck->view_password = $request->newPassword;
            $userCheck->update();
            return redirect()->route('company.edit_profile')->with('success', 'Password Update Successfully!');
        } catch (Exception $e) {
            Log::info("change password in profile error" . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
    public function verifyemail(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $email_check = User::where('id', '!=', $id)->where('email', $request->email)->where('user_type', '2')->first();
        if (!empty($email_check)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function verifycontact(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $contact_check = User::where('id', '!=', $id)->where('contact_number', $request->contact_number)->where('user_type', '2')->first();
        if (!empty($contact_check)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function logout(Request $request)
    {

        Auth::logout();
        return redirect()->route('company.signin');
    }
}

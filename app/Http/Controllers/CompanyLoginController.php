<?php

namespace App\Http\Controllers;

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
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Mail;
use Spatie\Permission\Models\Role;

class CompanyLoginController extends Controller
{

    public function index()
    {
        if (!empty(auth()->user()) && auth()->user()->user_type == env('ADMIN_ROLE')) {
            return redirect()->route('admin.dashboard');
        } elseif (!empty(auth()->user()) && auth()->user()->user_type == env('COMPANY_ROLE')) {
            return redirect()->route('company.dashboard');
        } else {
            return view('company.companylogin');
        }
    }
    function dashboard()
    {
        $companyId = Auth::user()->id;
        $data = [];
        $data['total_campaign'] = 0;
        $data['total_user'] = 0;
        $data['total_campaign'] = CampaignModel::where('company_id', $companyId)->where('status', '1')->count();
        $data['total_user'] = User::where('company_id', $companyId)->where('user_type', '4')->count();
        $data['total_campaignReq'] = 0;
        $data['referral_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '1')->orderBy("id", "DESC")->take(10)->get();
        $data['social_share_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '2')->orderBy("id", "DESC")->take(10)->get();
        $data['custom_tasks'] = CampaignModel::where('company_id', $companyId)->where('type', '3')->orderBy("id", "DESC")->take(10)->get();

        // DB::enableQueryLog();
        $user_campaign_history = DB::table('users as u')
            ->where('u.company_id', $companyId)
            ->where('uch.status', '3')
            ->whereMonth('uch.updated_at', '=', Carbon::now()->month)
            ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
            ->select(DB::raw('SUM(uch.reward) as total_reward , DAYOFMONTH(uch.updated_at) as day'))
            ->groupBy('day')
            ->get();

        // dd(DB::getQueryLog());
        $dateandtime = Carbon::now();
        $start_date = "01-" . $dateandtime->month . "-" . $dateandtime->year;
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 month", $start_time);
        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $list[date('d', $i)] = 0;
        }
        foreach ($user_campaign_history as $values) {
            $list[$values->day] = $values->total_reward;
        }
        $user_reward_and_days = json_encode(['day' => array_keys($list), 'reward' => array_values($list)]);
        return view('company.dashboard', $data, compact('user_reward_and_days'));
    }
    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {
            if (!empty(auth()->user()) &&  (auth()->user()->user_type == env('COMPANY_ROLE') || auth()->user()->user_type == env('STAFF_ROLE'))) {

                return redirect()->route('company.dashboard');
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }
        } else {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }
    }

    public function signup()
    {
        return view('company.signup');
    }
    public function signupStore(Request $request)
    {
        try {
            $useremail = User::where('email', $request->email)->where('user_type', env('COMPANY_ROLE'))->first();
            if (!empty($useremail)) {
                return redirect()->back()->with('error', 'Email is already registered!!')->withInput();
            }
            $usercontact = User::where('contact_number', $request->ccontact)->where('user_type', env('COMPANY_ROLE'))->first();
            if (!empty($usercontact)) {
                return redirect()->back()->with('error', 'Contact number is already registered!!')->withInput();
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
            if (isset($user)) {
                $compnay = new CompanyModel();
                $compnay->user_id = $user->id;
                $compnay->company_name = $request->cname;
                $compnay->contact_email = $request->email;
                $compnay->subdomain = $request->dname;
                $compnay->contact_number = $request->ccontact;
                $compnay->save();
            }
            if (isset($user)) {
                $role = Role::where('name', 'Company')->first();
                $user->assignRole([$role->id]);
                $settingModel = new SettingModel();
                $settingModel->user_id = $user->id;
                $settingModel->save();
                $input = $request->all();
            }
            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {


                if (!empty(auth()->user()) &&  auth()->user()->user_type == env('COMPANY_ROLE')) {

                    return redirect()->route('company.dashboard');
                } else {
                    return redirect()->back()->with('error', 'These credentials do not match our records.');
                }
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function forget()
    {
        return view('company.forgetPassword');
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
                \Mail::to($request->email)->send(new forgetpass($details));

                return redirect()->back()->with('success', 'Mail Send Successfully');
            } else {
                return redirect()->back()->with('error', 'email not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function confirmPassword($id)
    {
        return view('company.confirmPassword', compact('id'));
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
                if ($updateprofiledetail->profile_image && file_exists('uploads/user-profile/') . $updateprofiledetail->profile_image) {
                    unlink('uploads/user-profile/' . $updateprofiledetail->profile_image);
                }
                $filename = rand(111111, 999999) . '.' . $request->profile_image->extension();
                $request->file('profile_image')->move('uploads/user-profile/', $filename);
                $updateprofiledetail['profile_image'] = isset($filename) ? $filename : '';
            }
            $updateprofiledetail->save();
            return redirect()->route('company.dashboard');
        } catch (Exception $e) {
            Log::info(['message', 'Update Profile error']);
            return redirect()->back()->with($e->getMessage());
        }
    }
    public function Profile()
    {
        $profiledetail = User::where('id', Auth::user()->id)->first();
        $companydetail = SettingModel::where('user_id', Auth::user()->id)->first();
        $companyname = CompanyModel::where('user_id', Auth::user()->id)->first();
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
            return redirect()->route('company.dashboard')->with('message', 'Password Update Successfully!');
        } catch (Exception $e) {
            Log::info("change password in profile error" . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
    public function verifyemail(Request $request)
    {
        $userId = Auth::user()->id;
        $email_check = User::where('id', '!=', $userId)->where('email', $request->email)->where('user_type', env('COMPANY_ROLE'))->first();
        if (!empty($email_check)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function verifycontact(Request $request)
    {
        $userId = Auth::user()->id;
        $contact_check = User::where('id', '!=', $userId)->where('contact_number', $request->contact_number)->where('user_type', env('COMPANY_ROLE'))->first();
        if (!empty($contact_check)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}

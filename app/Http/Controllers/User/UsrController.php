<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

            $campaignList = UserCampaignHistoryModel::orderBy('user_id', 'DESC')->where('user_id', Auth::user()->id)->take(10)->get();
            $totalJoinedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '1')->where('user_id', Auth::user()->id)->get();
            $totalCompletedCampaign = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('status', '3')->where('user_id', Auth::user()->id)->get();
            $totalReward = UserCampaignHistoryModel::orderBy('id', 'DESC')->where('user_id', Auth::user()->id)->get();

            $userData = User::get();
            $data = [];
            $data['total_comapny'] = 0;
            $data['total_user'] = 0;
            $data['total_campaign'] = 0;
            $data['total_package'] = 0;
            return view('user.dashboard', compact('userData', 'data', 'campaignList', 'totalJoinedCampaign', 'totalCompletedCampaign', 'totalReward'));
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

            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {


                if (!empty(auth()->user()) &&  auth()->user()->user_type == env('USER_ROLE')) {

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
    public function editProfile()
    {
        try {
            $userData = Auth::user();
            return view('user.editprofile', compact('userData'));
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function changePasswordStore(Request $request)
    {

        try {

            $currentPasswordStatus = Hash::check($request->current_password, Auth::user()->password);
            if ($currentPasswordStatus) {

                User::findOrFail(Auth::user()->id)->update([
                    'password' => Hash::make($request->password),
                ]);

                return redirect()->back()->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Old Password does not match');
            }
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

            $userEmail = User::where('company_id', $profileId)->where('email', $request->email)->first();

            if (!empty($userEmail)) {
                return redirect()->back()->withErrors($validator)->with('error', 'User email id already exit.')->withInput();
            }
            $userNumber = User::where('company_id', $profileId)->where('contact_number', $request->contact_number)->first();

            if (!empty($userNumber)) {
                return redirect()->back()->withErrors($validator)->with('error', 'User Mobile Number already exit.')->withInput();
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


            return redirect()->route('user.edit_profile')->with('success', "Edit Profile Successfully!");
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
    function myreward()
    {

        return view('user.reward.myReward');
    }
    function progressreward()
    {

        return view('user.reward.progressReward');
    }
    function analytics()
    {
        return view('user.analytics');
    }
    function notification()
    {
        return view('user.notification');
    }
    public function signup()
    {
        return view('user.signup');
    }

    public function store(Request $request)
    {
        try {

            $userEmail = User::where('user_type', '4')->where('status', '1')->where('email', $request->email)->first();

            if (!empty($userEmail)) {
                return redirect()->back()->with('error', 'This email already exists');
            }


            if (isset($request->referral_code)) {
                $referrer_user = User::where('referral_code', $request->referral_code)->where('referral_code', '!=', null)->first();
            }
            // dd($referrer_user);

            $companyId = User::where('user_type', '2')->where('status', '1')->first();
            $userRegister = new User();
            $userRegister->first_name = $request->first_name;
            $userRegister->last_name = $request->last_name;
            $userRegister->email = $request->email;
            $userRegister->user_type = '4';
            $userRegister->company_id = $companyId->id;
            $userRegister->referral_code = Str::random(6);
            $userRegister->password = Hash::make($request->password);
            $userRegister->referral_user_id = !empty($referrer_user) ? $referrer_user->id : null;

            // $baseUrl = config('app.url');
            // $affiliateLink = $baseUrl . '/user/signup?referral_code=' . $userRegister->referral_code;
            // dd($affiliateLink);

            $userRegister->save();

            return redirect()->route('user.login')->with('success', "Registration Successfully!");
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
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
                ->update(['password' => Hash::make($request->password)]);

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

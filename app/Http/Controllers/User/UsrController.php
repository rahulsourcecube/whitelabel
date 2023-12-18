<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $data = [];
        $data['total_comapny'] = 0;
        $data['total_user'] = 0;
        $data['total_campaign'] = 0;
        $data['total_package'] = 0;
        return view('user.dashboard', $data);
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
        return view('user.editprofile');
    }
    public function Profile()
    {
        return view('user.profile');
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
            $userRegister->referral_user_id = isset($referrer_user) ? $referrer_user->id : null;

            // $baseUrl = config('app.url');
            // $affiliateLink = $baseUrl . '/user/signup?referral_code=' . $userRegister->referral_code;
            // dd($affiliateLink);

            $userRegister->save();

            return redirect()->route('user.login')->with('success', "Registration Successfully!");
        } catch (Exception $exception) {
            dd($exception);
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function forget()
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

            return redirect('/user')->with('success', 'Your password has been changed!');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', "Something Went Wrong!");
        }
    }

    public function Logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('user');
    }
}

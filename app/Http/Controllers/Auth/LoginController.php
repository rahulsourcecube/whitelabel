<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ((auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'user_type' => '1'))) && (url()->current() == url('login'))) {

            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()
                ->with('error', 'Email-Address And Password Are Wrong.');
        }

        // if ((auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'user_type' => '2'))) && (url()->current() == url('/company/login'))) {
        //     return redirect()->route('company.dashboard');
        // } else {
        //     return redirect()->back()
        //         ->with('error', 'Email-Address And Password Are Wrong.');
        // }

        // if ((auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'user_type' => '4'))) && (url()->current() == url('/user/login'))) {
        //     return redirect()->route('user.dashboard');
        // } else {
        //     return redirect()->back()
        //         ->with('error', 'Email-Address And Password Are Wrong.');
        // }
    }
}

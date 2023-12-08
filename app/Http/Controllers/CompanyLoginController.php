<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $data = [];
        $data['total_campaign'] = 0;
        $data['total_user'] = 0;
        $data['total_campaignReq'] = 0;
        return view('company.dashboard', $data);
    }
    public function login(Request $request)
    {

        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {


            if (!empty(auth()->user()) &&  auth()->user()->user_type == env('COMPANY_ROLE')) {

                return redirect()->route('company.dashboard');
            } else {
                return redirect()->back()->with('error', 'These credentials do not match our records.');
            }
        } else {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }
    }
    public function editProfile(){
        return view('company.editprofile');
    }
    public function Profile(){
        return view('company.profile');
    }
    public function signup(){
        return view('company.signup');
    }
}

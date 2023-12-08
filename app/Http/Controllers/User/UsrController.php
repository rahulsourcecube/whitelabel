<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function signup()
    {
        return view('user.signup');
    }
    public function forget()
    {
        return view('user.forgetPassword');
    }
    public function confirmPassword()
    {
        return view('user.confirmPassword');
    }
}

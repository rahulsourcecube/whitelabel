<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->user_type == env('ADMIN_ROLE')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->user_type == env('COMPANY_ROLE')) {
            return redirect()->route('company.dashboard');
        } else {
            return view('auth.login');
        }
    }
    function dashboard()
    {

        $data = [];
        $data['total_comapny'] = 0;
        $data['total_user'] = 0;
        $data['total_campaign'] = 0;
        $data['total_package'] = 0;
        return view('admin.dashboard', $data);
    }
}

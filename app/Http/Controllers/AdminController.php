<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        } elseif (auth()->user()->user_type == env('USER_ROLE')) {
            return redirect()->route('user.dashboard');
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

    public function change_password()
    {
        $user = User::where('id', Auth::user()->id)->first();
        // return view('auth.change_password', compact('user'));
    }

    public function update_change_password(Request $request)
    {
        // Match The Old Password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }


        //Update the new Password
        user::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);

        return redirect()->route('admin.change_password')->with('success', __('Change Password successfully updated.'));
    }
    public function logout(Request $request) {  
     
        Auth::logout();
        return redirect()->route('admin.login');
      }
}

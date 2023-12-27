<?php

namespace App\Http\Controllers;

use App\Models\CompanyModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $data = [];
        $data['total_comapny'] = 0;
        $data['old_user'] = User::where('user_type', env('COMPANY_ROLE'))->where(function ($query) use ($currentMonth, $currentYear) {$query->whereMonth('created_at', '<>', $currentMonth)->orWhereYear('created_at', '<>', $currentYear);})->count();
        $data['total_user'] = 0;
        $data['total_campaign'] = 0;
        $data['total_package'] = 0;
        $data['company'] = CompanyModel::get(['id', 'company_name', 'user_id']);
        $data['old_user'] = User::where('user_type', env('COMPANY_ROLE'))->where(function ($query) use ($currentMonth, $currentYear) {$query->whereMonth('created_at', '<>', $currentMonth)->orWhereYear('created_at', '<>', $currentYear);})->count();
        $data['new_user'] = User::where('user_type', env('COMPANY_ROLE'))->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();
        $data['total_user'] = User::where('user_type', env('COMPANY_ROLE'))->count();


        return view('admin.dashboard', $data);
    }

    function CompanyRevenue(Request $request)
    {
        // dd($request->all());
        $currentMonth = now()->format('Y-m');
        $userCounts = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('company_id', $request->company)
            ->where(DB::raw('DATE_FORMAT(created_at, "%m/%Y")'), $request->month)
            ->groupBy('date')
            ->get()->toArray();
        // dd($userCounts);

        $data = [];

        foreach ($userCounts as $item) {
            $data[] = [
                "label" => date('d', strtotime($item['date'])), // Format the day of the month
                "value" => $item['count']
            ];
        }

        return response()->json($data);
    }

    //web admin Function Use change Password Page
    public function ChengPassword()
    {
        try {
            $user = Auth::User();
            return view(
                'admin.change-password',
                compact('user')
            );
        } catch (Exception $exception) {
            Log::error('error : ' . $exception->getMessage());
            return redirect()->route('admin.dashboard')->with('error', "Something went wrong");
        }
    }

    //web admin Function Use admin change Password

    public function UpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'min:8',
            'new_password' => 'min:8',
            'confirm_password' => 'required_with:new_password|same:new_password|min:6'
        ]);
        try {

            $userId = Auth::User();
            $user = User::find($userId->id);
            $password = $request->input("current_password");
            if (Hash::check($request->current_password, auth()->user()->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                Auth::logout();
                return redirect()->route('Login')->with('message', 'Password Update successfully');
            } else {
                return redirect()->back()->with('error', 'Current Password do not match your Password.');
            }
        } catch (Exception $exception) {
            Log::error('error : ' . $exception->getMessage());
            return redirect()->back()->with('error', "Something went wrong");
        }
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

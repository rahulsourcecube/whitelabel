<?php

namespace App\Http\Controllers;

use App\Models\CampaignModel;
use App\Models\CompanyModel;
use App\Models\PackageModel;
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
        //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        // if(!empty($getdomain) && $getdomain != env('pr_name')  ){
        //     return redirect()->back();
        // }    

        if (auth()->user() && auth()->user()->user_type == 1) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user() && auth()->user()->user_type == 2) {
            return redirect()->route('company.dashboard');
        } elseif (auth()->user() && auth()->user()->user_type == 4) {
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
        $data['total_comapny'] = User::where('user_type', 2)->where('status', '1')->count();
        $data['total_campaign'] = CampaignModel::where('status', '1')->count();
        $data['total_package'] =  PackageModel::where('status', '1')->count();
        $data['total_user'] = User::where('user_type', 4)->where('status', '1')->count();

        $data['company'] = CompanyModel::get(['id', 'company_name', 'user_id']);

        $data['old_company'] = User::where('user_type', 2)->where('status', '1')->where(function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', '<>', $currentMonth)->orWhereYear('created_at', '<>', $currentYear);
        })->count();
        $data['new_company'] = User::where('user_type', 2)->where('status', '1')->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();

        return view('admin.dashboard', $data);
    }

    function CompanyRevenue(Request $request)
    {

        $userId = $request->company; // Replace with the actual user ID
        $month = $request->month; // Replace with the actual month you want to filter

        $results = DB::table('user_campaign_history')
            ->join('campaign', 'user_campaign_history.campaign_id', '=', 'campaign.id')
            ->select(DB::raw('DATE_FORMAT(user_campaign_history.created_at, "%Y-%m-%d") as date'), DB::raw('SUM(user_campaign_history.reward) as total_reward'))
            ->where('campaign.company_id', $userId)
            ->where('user_campaign_history.status', 3)
            ->where(DB::raw('DATE_FORMAT(user_campaign_history.created_at, "%m/%Y")'), $month)
            ->groupBy(DB::raw('DATE_FORMAT(user_campaign_history.created_at, "%Y-%m-%d")'))
            ->get()
            ->toArray();


        $data = [];

        foreach ($results as $item) {
            $data[] = [
                "label" => date('d', strtotime($item->date)), // Format the day of the month
                "value" => $item->total_reward
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
                // Auth::logout();
                return redirect()->route('admin.ChengPassword')->with('success', 'Password Update successfully');
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
    public function logout(Request $request)
    {

        Auth::logout();
        // return view('auth.login');

        return redirect()->to('login');
    }
}

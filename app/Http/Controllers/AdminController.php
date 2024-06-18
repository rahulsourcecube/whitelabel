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
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {

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
        try {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $data = [];
            $data['total_comapny'] = User::where('user_type', 2)->where('status', '1')->count();
            $data['total_campaign'] = CampaignModel::where('status', '1')->count();
            $data['total_package'] =  PackageModel::where('status', '1')->count();
            $data['total_user'] = User::where('user_type', 4)->where('status', '1')->count();

            $data['company'] = CompanyModel::leftJoin('users', 'company.user_id', '=', 'users.id')->where('users.status', '1')->get();

            $data['old_company'] = User::where('user_type', 2)->where('status', '1')->where(function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', '<>', $currentMonth)->orWhereYear('created_at', '<>', $currentYear);
            })->count();
            $data['new_company'] = User::where('user_type', 2)->where('status', '1')->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();

            return view('admin.dashboard', $data);
        } catch (Exception $e) {
            Log::error('AdminController::Dashboard => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    function CompanyRevenue(Request $request)
    {
        try {
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
                    "label" => date('Y-m-d', strtotime($item->date)), // Format the day of the month
                    "value" => $item->total_reward
                ];
            }

            return response()->json($data);
        } catch (Exception $e) {
            Log::error('AdminController::Dashboard => ' . $e->getMessage());
            return response()->json(["data" => [], 'message' => "error : " . $e->getMessage()]);
        }
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
        } catch (Exception $e) {
            Log::error('AdminController::ChengPassword => ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', "Error : " . $e->getMessage());
        }
    }

    //web admin Function Use admin change Password

    public function UpdatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'min:8',
                'new_password' => 'min:8',
                'confirm_password' => 'required_with:new_password|same:new_password|min:6'
            ]);

            $userId = Auth::User();
            $user = User::find($userId->id);
            if (Hash::check($request->current_password, auth()->user()->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->route('admin.ChengPassword')->with('success', 'Password Update Successfully');
            } else {
                return redirect()->back()->with('error', 'Current Password do not match your Password.');
            }
        } catch (Exception $e) {
            Log::error('AdminController::UpdatePassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function update_change_password(Request $request)
    {
        try {
            // Match The Old Password
            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->with("error", "Old Password Doesn't match!");
            }

            //Update the new Password
            user::whereId(Auth::user()->id)->update([
                'password' => Hash::make($request->new_password)

            ]);

            return redirect()->route('admin.change_password')->with('success', __('Change Password successfully updated.'));
        } catch (Exception $e) {
            Log::error('AdminController::Updatechangepassword => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return redirect()->to('login');
        } catch (Exception $e) {
            Log::error('AdminController::Logout => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
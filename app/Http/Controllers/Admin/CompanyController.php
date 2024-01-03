<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CompanyModel;
use App\Models\CompanyPackage;
use App\Models\SettingModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    //
    function index()
    {
        return view('admin.company.list');
    }

    public function dtList(Request $request)
    {
        $columns = ['id', 'company_name']; // Add more columns as needed
        $totalData = CompanyModel::count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = CompanyModel::orderBy($columns[$order], $dir)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {
            $list[] = [
                $result->id,
                $result->user->first_name  . ' ' . $result->user->last_name,
                $result->user->email,
                $result->user->contact_number,
                $result['company_name'],
                $result['subdomain'],
                $result->user->status == '1' ? '<button class="btn btn-success btn-sm">Active</button>' : '<button class="btn btn-danger btn-sm">Deactive</button>',
                $result['email'],
                $result['email'],
                $result['email'],
                $result['is_indivisual'],
            ];
        }
        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $list
        ]);
    }
    public function view(Request $request)
    {
        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        $data = [];
        $data['user_company'] = CompanyModel::where('id', $request->id)->first();
        $data['user_company_setting'] = SettingModel::where('user_id', $data['user_company']->user_id)->first();
        $data['ActivePackageData'] = CompanyPackage::where('company_id', $data['user_company']->user_id)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
        $data['CampaignModelCount'] = CampaignModel::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->count();
        $data['staffCount'] = User::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->where('user_type',  User::USER_TYPE['STAFF'])->count();
        $data['userCount'] = User::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->where('user_type',  User::USER_TYPE['USER'])->count();


        return view('admin.company.view', $data);
    }
    function edit(Request $request)
    {
        $data = [];
        
        $data['user_company'] = CompanyModel::where('id', $request->id)->first();
        $data['setting'] = SettingModel::where('user_id', $data['user_company']->user_id)->first();
        $data['editprofiledetail'] = User::where('id',$data['user_company']->user_id)->first();
        return view('admin.company.edit', $data);
    }
    public function updatepassword(Request $request, $id)
    {
        try {
            $userCheck = User::where('id', $id)->first();
            if (empty($userCheck)) {
                return redirect()->back()->with('error', 'User not found!');
            }
            $userCheck->password = Hash::make($request->newPassword);
            $userCheck->view_password = $request->newPassword;
            $userCheck->update();
            return redirect()->back()->with('success', 'Password Update Successfully!');
        } catch (Exception $e) {
            Log::info("change password in profile error" . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
    public function updateprofile(Request $request,$id)
    {
        try {
            $updateprofiledetail = User::where('id', $id)->first();
            $updateprofiledetail['first_name'] = isset($request->first_name) ? $request->first_name : '';
            $updateprofiledetail['last_name'] = isset($request->last_name) ? $request->last_name : '';
            $updateprofiledetail['email'] = isset($request->email) ? $request->email : '';
            $updateprofiledetail['contact_number'] = isset($request->contact_number) ? $request->contact_number : '';
            if ($request->hasFile('profile_image')) {
                if ($updateprofiledetail->profile_image && file_exists('uploads/user-profile/') . $updateprofiledetail->profile_image) {
                    unlink('uploads/user-profile/' . $updateprofiledetail->profile_image);
                }
                $filename = rand(111111, 999999) . '.' . $request->profile_image->extension();
                $request->file('profile_image')->move('uploads/user-profile/', $filename);
                $updateprofiledetail['profile_image'] = isset($filename) ? $filename : '';
            }
            $updateprofiledetail->save();
            return redirect()->back()->with('success', 'Profile Update Successfully!');
        } catch (Exception $e) {
            Log::info(['message', 'Update Profile error']);
            return redirect()->back()->with($e->getMessage());
        }
    }
    function store(Request $request,$id)
    {
        try {

            //code...
            $SettingModel = SettingModel::where('user_id', $id)->first();
            if ($SettingModel->user_id) {
                if (empty($SettingModel)) {
                    $SettingModel = new SettingModel;
                }
                //Update Favicon
                if ($request->hasFile('logo')) {
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    // Generate a random number as a prefix
                    $randomNumber = rand(111111, 999999);
                    // Generate a timestamp (e.g., current Unix timestamp)
                    $timestamp = time();
                    // Combine the timestamp, random number, an underscore, and the original extension
                    $logo = $timestamp . '_' . $randomNumber . '.' . $extension;
                    // Move the file to the storage directory with the new filename+
                    $request->file('logo')->move('uploads/setting', $logo);
                    if (!empty($SettingModel->logo)) {
                        $oldlogo = 'uploads/setting/' . $SettingModel->logo;
                        // Delete the old favicon if it exists
                        if (file_exists($oldlogo)) {
                            unlink($oldlogo);
                        }
                    }
                    // Save the logo path to the database
                    $SettingModel->logo = $logo;
                }
                //Update Favicon
                if ($request->hasFile('favicon')) {
                    if (!empty($SettingModel->favicon)) {
                        $oldFaviconPath = 'uploads/setting/' . $SettingModel->favicon;
                        // Delete the old favicon if it exists
                        if (file_exists($oldFaviconPath)) {
                            unlink($oldFaviconPath);
                        }
                    }
                    $extension = $request->file('favicon')->getClientOriginalExtension();
                    // Generate a random number as a prefix
                    $randomNumber = rand(1000, 9999);
                    // Generate a timestamp (e.g., current Unix timestamp)
                    $timestamp = time();
                    // Combine the timestamp, random number, an underscore, and the original extension
                    $favicon_img = $timestamp . '_' . $randomNumber . '.' . $extension;
                    // Move the file to the storage directory with the new filename+
                    $request->file('favicon')->move('uploads/setting', $favicon_img);
                    // Save the favicon_img path to the database
                    $SettingModel->favicon = $favicon_img;
                }
                $SettingModel->title = $request->title;
                $SettingModel->email = $request->email;
                $SettingModel->contact_number = $request->contact_number;
                $SettingModel->description = $request->description;
                $SettingModel->facebook_link = $request->facebook_link;
                $SettingModel->twitter_link = $request->twitter_link;
                $SettingModel->linkedin_link = $request->linkedin_link;
                $SettingModel->save();
                return redirect()->back()->with('success', 'Setting Update successfully');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}

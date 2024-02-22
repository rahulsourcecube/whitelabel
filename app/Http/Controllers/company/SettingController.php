<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Models\SettingModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

   function __construct()
   {
      // check user permission
      $this->middleware('permission:general-setting-list', ['only' => ['index']]);
      $this->middleware('permission:general-setting-create', ['only' => ['store']]);;
   }

   function index()
   {
      try {
         $companyId = Helper::getCompanyId();
         $data['setting'] = SettingModel::where('user_id', $companyId)->first();
         $data['companyname'] = CompanyModel::where('user_id', $companyId)->first();
         return view('company.setting.setting', $data);
      } catch (Exception $e) {
         Log::error('SettingController::Index => ' . $e->getMessage());
         return redirect()->back()->with('error', "Error : " . $e->getMessage());
      }
   }
   function store(Request $request)
   {
      try {
         $companyId = Helper::getCompanyId();

         $SettingModel = SettingModel::where('user_id', $companyId)->first();
         if ($SettingModel->user_id) {
            if (empty($SettingModel)) {
               $SettingModel = new SettingModel;
            }
            //Update Favicon
            if ($request->hasFile('logo')) {
               $extension = $request->file('logo')->getClientOriginalExtension();
               $randomNumber = rand(111111, 999999);
               $timestamp = time();
               $logo = $timestamp . '_' . $randomNumber . '.' . $extension;
               $request->file('logo')->move(base_path('uploads/setting'), $logo);
               if (!empty($SettingModel->logo)) {
                  $oldlogo = 'uploads/setting/' . $SettingModel->logo;
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
               $randomNumber = rand(1000, 9999);
               $timestamp = time();
               $favicon_img = $timestamp . '_' . $randomNumber . '.' . $extension;
               $request->file('favicon')->move(base_path('uploads/setting'), $favicon_img);
               $SettingModel->favicon = $favicon_img;
            }
            $SettingModel->title = $request->title;
            $SettingModel->email = $request->email;
            $SettingModel->contact_number = $request->contact_number;
            $SettingModel->description = $request->description;
            $SettingModel->facebook_link = $request->facebook_link;
            $SettingModel->twitter_link = $request->twitter_link;
            $SettingModel->linkedin_link = $request->linkedin_link;
            $SettingModel->logo_link = $request->logo_link;
            $SettingModel->save();
            return redirect()->route('company.setting.index')->with('success', 'Setting Update successfully');
         }
      } catch (Exception $e) {
         Log::error('SettingController::Store => ' . $e->getMessage());
         return redirect()->back()->with('error', "Error : " . $e->getMessage());
      }
   }
}

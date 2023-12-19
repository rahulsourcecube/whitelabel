<?php
namespace App\Http\Controllers\Company;
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
   function index()
   {
      $data['setting'] = SettingModel::where('user_id', Auth::user()->id)->first();
      $data['companyname'] = CompanyModel::where('user_id', Auth::user()->id)->first();
      return view('company.setting.setting', $data);
   }
   function store(Request $request)
   {
      try {
         //code...
         $SettingModel = SettingModel::where('user_id', Auth::user()->id)->first();
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
            return redirect()->route('company.setting.index')->with('success', 'Setting Update successfully');
         }
      } catch (\Throwable $th) {
         return redirect()->route('company.setting.index')->with('error', $th->getMessage());
      }
   }
}
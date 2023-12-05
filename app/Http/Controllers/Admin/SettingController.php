<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //
    function index()
    {
        // $setting=[];
        $setting=SettingModel::first();        
        return view('admin.setting.setting',compact('setting'));    }
    function store(request $request)
    {
        $SettingModel=SettingModel::where('id',"1")->first();
       
        if ($request->hasFile('logo')) {
            $originalFilename = $request->file('logo')->getClientOriginalName();
            $extension = $request->file('logo')->getClientOriginalExtension();
            
            // Generate a random number as a prefix
            $randomNumber = rand(1000, 9999);
            
            // Generate a timestamp (e.g., current Unix timestamp)
            $timestamp = time();
            
            // Combine the timestamp, random number, an underscore, and the original extension
            $logo = $timestamp . '_' . $randomNumber . '.' . $extension;
            
            // Move the file to the storage directory with the new filename+
            $request->file('logo')->move('uploads/setting', $logo);

            // Save the logo path to the database
            $SettingModel->logo = $logo;
        } else {
            $SettingModel->logo =$SettingModel->logo; // or whatever default value you want
        }
        if ($request->hasFile('favicon_img')) {
            $originalFilename = $request->file('favicon_img')->getClientOriginalName();
            $extension = $request->file('favicon_img')->getClientOriginalExtension();
            
            // Generate a random number as a prefix
            $randomNumber = rand(1000, 9999);
            
            // Generate a timestamp (e.g., current Unix timestamp)
            $timestamp = time();
            
            // Combine the timestamp, random number, an underscore, and the original extension
            $favicon_img = $timestamp . '_' . $randomNumber . '.' . $extension;
            
            // Move the file to the storage directory with the new filename+
            $request->file('favicon_img')->move('uploads/setting', $favicon_img);

            // Save the favicon_img path to the database
            $SettingModel->favicon = $favicon_img;
        } else {
            $SettingModel->favicon =$SettingModel->favicon;// or whatever default value you want
        }
        // dd($request->description);
        $SettingModel->title = $request->title;
        $SettingModel->email = $request->email; // Fix typo in 'description' discription
        $SettingModel->contact_number = $request->contact_no;
        $SettingModel->facebook_link = $request->flink;
        $SettingModel->twitter_link = $request->t_link;
        $SettingModel->linkedin_link = $request->l_link;
        // $SettingModel->Logo = $request->Logo;
        // $SettingModel->Favicon = $request->Favicon;
        $SettingModel->save(); 
            

        // return redirect()->route('');
        return redirect()->route('admin.setting.index')->with('success', 'Setting Update successfully');
    
    }
}

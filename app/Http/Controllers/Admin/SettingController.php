<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    //
    function index()
    {
        $data = [];
        $data['setting'] = SettingModel::first();
        return view('admin.setting.setting', $data);
    }
    function store(request $request)
    {
        try {
            //code...
            $SettingModel = SettingModel::first();

            if (empty($SettingModel)) {
                $SettingModel = new SettingModel;
            }

            if ($request->hasFile('logo')) {
                $extension = $request->file('logo')->getClientOriginalExtension();

                // Generate a random number as a prefix
                $randomNumber = rand(1000, 9999);

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
            } else {
                if (!empty($SettingModel->logo)) {
                    $oldlogo = 'uploads/setting/' . $SettingModel->logo;
                    // Delete the old favicon if it exists
                    if (file_exists($oldlogo)) {
                        unlink($oldlogo);
                    }
                }
                $SettingModel->logo = ''; // or whatever default value you want

            }
            if ($request->hasFile('favicon_img')) {
                if (!empty($SettingModel->favicon)) {
                    $oldFaviconPath = 'uploads/setting/' . $SettingModel->favicon;
                    // Delete the old favicon if it exists
                    if (file_exists($oldFaviconPath)) {
                        unlink($oldFaviconPath);
                    }
                }
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
                if (!empty($SettingModel->favicon)) {
                    $oldFaviconPath = 'uploads/setting/' . $SettingModel->favicon;
                    // Delete the old favicon if it exists
                    if (file_exists($oldFaviconPath)) {
                        unlink($oldFaviconPath);
                    }
                }

                $SettingModel->favicon = '';
            }
            $SettingModel->title = $request->title;
            $SettingModel->email = $request->email;
            $SettingModel->contact_number = $request->contact_no;
            $SettingModel->facebook_link = $request->flink;
            $SettingModel->twitter_link = $request->t_link;
            $SettingModel->linkedin_link = $request->l_link;

            $SettingModel->save();
            return redirect()->route('admin.setting.index')->with('success', 'Setting Update successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.setting.index')->with('error', $th->getMessage());
        }
    }
}

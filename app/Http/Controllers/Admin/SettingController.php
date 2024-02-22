<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    //
    function index()
    {
        try {
            $data = [];
            $data['setting'] = SettingModel::where('user_id', Auth::user()->id)->first();
            return view('admin.setting.setting', $data);
        } catch (Exception $e) {
            Log::error('SettingController::Index ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    function store(request $request)
    {
        try {
            $SettingModel = SettingModel::where('user_id', Auth::user()->id)->first();
            if (empty($SettingModel)) {
                $SettingModel = new SettingModel;
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
                }
                $SettingModel->title = $request->title;
                $SettingModel->email = $request->email;
                $SettingModel->contact_number = $request->contact_no;
                $SettingModel->facebook_link = $request->flink;
                $SettingModel->twitter_link = $request->t_link;
                $SettingModel->linkedin_link = $request->l_link;
//Mail Credentials
                $SettingModel->mail_mailer = $request->mail_mailer;
                $SettingModel->mail_host = $request->mail_host;
                $SettingModel->mail_port = $request->mail_port;
                $SettingModel->mail_username = $request->mail_username;
                $SettingModel->mail_password = $request->mail_password;
                $SettingModel->mail_encryption = $request->mail_encryption;
                $SettingModel->mail_address = $request->mail_address;
 //Stripe Credentials
                $SettingModel->stripe_key = $request->stripe_key;
                $SettingModel->stripe_secret = $request->stripe_secret;

                $SettingModel->user_id = Auth::user()->id;
                $SettingModel->save();
            } else {

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
                }
                $SettingModel->title = $request->title;
                $SettingModel->email = $request->email;
                $SettingModel->contact_number = $request->contact_no;
                $SettingModel->facebook_link = $request->flink;
                $SettingModel->twitter_link = $request->t_link;
                $SettingModel->linkedin_link = $request->l_link;
                //Mail Credentials
                $SettingModel->mail_mailer = $request->mail_mailer;
                $SettingModel->mail_host = $request->mail_host;
                $SettingModel->mail_port = $request->mail_port;
                $SettingModel->mail_username = $request->mail_username;
                $SettingModel->mail_password = $request->mail_password;
                $SettingModel->mail_encryption = $request->mail_encryption;
                $SettingModel->mail_address = $request->mail_address;
 //Stripe Credentials
                $SettingModel->stripe_key = $request->stripe_key;
                $SettingModel->stripe_secret = $request->stripe_secret;
                $SettingModel->save();
            }
            return redirect()->route('admin.setting.index')->with('success', 'Setting Update successfully');
        } catch (\Throwable $e) {
            Log::error('SettingController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }
}

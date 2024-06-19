<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CityModel;
use App\Models\SettingModel;
use App\Models\StateModel;
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
                //Sms tipe
                $SettingModel->sms_type = $request->sms_type == 'true' ? '2' : '1';

                //Sms twilio
                $SettingModel->sms_account_sid = $request->sms_account_sid;
                $SettingModel->sms_account_token = $request->sms_account_token;
                $SettingModel->sms_account_number = $request->sms_account_number;
                $SettingModel->sms_account_to_number = $request->sms_account_to_number;
                $SettingModel->sms_mode = $request->sms_mode;

                //Sms plivo

                $SettingModel->plivo_auth_id = $request->plivo_auth_id;
                $SettingModel->plivo_auth_token = $request->plivo_auth_token;
                $SettingModel->plivo_phone_number = $request->plivo_phone_number;
                $SettingModel->plivo_test_phone_number = $request->plivo_test_phone_number;

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
                //Sms tipe
                $SettingModel->sms_type = $request->sms_type == 'true' ? '2' : '1';

                //Sms twilio
                $SettingModel->sms_account_sid = $request->sms_account_sid;
                $SettingModel->sms_account_token = $request->sms_account_token;
                $SettingModel->sms_account_number = $request->sms_account_number;
                $SettingModel->sms_account_to_number = $request->sms_account_to_number;
                $SettingModel->sms_mode = $request->sms_mode;

                //Sms plivo

                $SettingModel->plivo_auth_id = $request->plivo_auth_id;
                $SettingModel->plivo_auth_token = $request->plivo_auth_token;
                $SettingModel->plivo_phone_number = $request->plivo_phone_number;
                $SettingModel->plivo_test_phone_number = $request->plivo_test_phone_number;
                $SettingModel->plivo_mode = $request->plivo_mode;

                $SettingModel->save();
            }
            return redirect()->route('admin.setting.index')->with('success', 'Setting Update Successfully');
        } catch (\Throwable $e) {
            Log::error('SettingController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }
    public function get_states(Request $request)
    {
        $country_id = $request->input('country_id');

        $states = StateModel::where('country_id', $country_id)->get();

        $options = '';
        $options .= "<option value=''>Select state</option>";
        foreach ($states as $state) {
            $options .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        // Return the options as JSON response
        return response()->json($options);
    }


    public function get_city(Request $request)
    {
        $state_id = $request->input('state_id');

        $citys = CityModel::where('state_id', $state_id)->get();
        $options = '';
        $options .= "<option value=''>Select City</option>";
        foreach ($citys as $city) {
            $options .= "<option value='" . $city->id . "'>" . $city->name . "</option>";
        }
        return response()->json($options);
    }
}
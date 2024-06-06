<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\CampaignModel;
use App\Models\MailTemplate;
use App\Models\NotificationsQue;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use App\Services\PlivoService;
use App\Services\TwilioService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SmstemplateController extends Controller
{
    function index()
    {
        try {
            $ActivePackageData = Helper::GetActivePackageData();

            if ($ActivePackageData->sms_temp_status != "1") {
                return redirect()->route('company.dashboard')->with('error', "You don't hav epermission.");
            }


            return view('company.smsTemplate.list');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function list(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id'];
            $totalData = SmsTemplate::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['template_html'];
            $query = SmsTemplate::orderBy($columns[0], $dir);
            $query->where('company_id', $companyId);
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        if ($column == 'full_name') {
                            $query->orWhere(DB::raw('concat(title)'), 'like', "%{$search}%");
                        } else {
                            $query->orWhere("$column", 'like', "%{$search}%");
                        }
                    }
                });
            }

            $results = $query->skip($start)
                ->take($length)
                ->get();

            foreach ($results as $result) {
                if ($result->template_type == 'welcome') {
                    $type = 'Welcome';
                } elseif ($result->template_type == 'forgot_password') {
                    $type = 'Forgot Password';
                } elseif ($result->template_type == 'change_pass') {
                    $type = 'Change Password';
                } elseif ($result->template_type == 'new_task') {
                    $type = 'New Task';
                } elseif ($result->template_type == 'earn_reward') {
                    $type = 'Earn Reward';
                } elseif ($result->template_type == 'custom') {
                    $type = 'custom';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,
                    $result->template_type ?? "",


                ];
            }
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('SettingController::Elist  => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }
    function create()
    {
        try {
            $ActivePackageData = Helper::GetActivePackageData();

            if ($ActivePackageData->sms_temp_status != "1") {
                return redirect()->route('company.dashboard')->with('error', "You don't hav epermission.");
            }
            return view('company.smsTemplate.create');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function edit($id)
    {
        try {
            $ActivePackageData = Helper::GetActivePackageData();

            if ($ActivePackageData->sms_temp_status != "1") {
                return redirect()->route('company.dashboard')->with('error', "You don't hav epermission.");
            }
            $companyId = Helper::getCompanyId();
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            if (empty($SmsTemplate)) {
                return redirect()->back()->with('error', 'No Found SMS Template ')->withInput();
            }
            return view('company.smsTemplate.create', compact('SmsTemplate'));
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function store(Request $request)
    {

        try {
            $companyId = Helper::getCompanyId();
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();


            if (empty($SmsTemplate) || empty($request->id)) {

                $existingTemplate = SmsTemplate::where('company_id', $companyId)
                    ->where('template_type', $request->type)
                    ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->with('error', 'Template already exit ' . $request->type);
                }

                $SmsTemplate = new SmsTemplate;
                $SmsTemplate->template_type = $request->type;
            }

            $SmsTemplate->company_id = $companyId;

            $SmsTemplate->template_html_sms = $request->tempHtml;

            $SmsTemplate->save();
            return redirect()->route('company.sms.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sendSms(Request $request)
    {

        try {
            $companyId = Helper::getCompanyId();

            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            $webUrl = "";
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {

                $webUrl =  'https://' . $webUrlGetHost;
            } else {

                $webUrl =  'http://' . $webUrlGetHost;
            }


            $SettingModel = SettingModel::where('user_id', $companyId)->first();


            if (empty($SettingModel) &&  (Helper::activeTwilioSetting() == false  && $SettingModel->sms_type != '2') || (Helper::activePlivoSetting() == false  && $SettingModel->sms_type != '1')) {
                return redirect()->route('company.sms.index')->with(['error' => "Please enter SMS Credential"]);
            }

            $notFoundNumber = [];
            if ($request->template_type == 'welcome') {
                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {
                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();

                        if (!empty($user)) {
                            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'welcome')->first();
                            if (!empty($smsTemplate)) {
                                $SettingModel = SettingModel::first();
                                if (!empty($companyId)) {
                                    $SettingModel = SettingModel::where('user_id', $companyId)->first();
                                }
                                if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {
                                    $name = $user->first_name;
                                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                                    $company_link = $webUrl ? $webUrl : '';
                                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]"], [$name, $company_title, $company_link], $smsTemplate->template_html_sms);

                                    // Remove HTML tags and decode HTML entities
                                    $message = htmlspecialchars_decode(strip_tags($html));

                                    // Remove unwanted '&nbsp;' text
                                    $message = str_replace('&nbsp;', ' ', $message);
                                    $contact_number = Helper::getReqestPhoneCode($user->contact_number, $user->country_id);


                                    try {
                                        if (Helper::activeTwilioSetting()) {
                                            $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;

                                            $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                            $twilioService->sendSMS($to, $message);
                                        } else {
                                            $to = $SettingModel->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;

                                            $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                            $PlivoService->sendSMS($to, $message);
                                        }
                                    } catch (Exception $e) {
                                        Log::error('Failed to send SMS: ' . $e->getMessage());
                                        echo "Failed to send SMS: " . $e->getMessage();
                                    }
                                }
                            }
                        } else {
                            $notFoundNumber[] = $number;
                        }
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'forgot_password') {

                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {
                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();
                        if (!empty($user)) {
                            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'forgot_password')->first();
                            $token = Str::random(64);
                            if (!empty($smsTemplate)) {
                                Log::error('UsrController:: check for sms');

                                $SettingModel = SettingModel::first();
                                if (!empty($companyId)) {
                                    $SettingModel = SettingModel::where('user_id', $companyId)->first();
                                }
                                if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {

                                    $name = $user->first_name;
                                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                                    $company_link = $webUrl ? $webUrl : '';
                                    $submit = route('user.confirmPassword', $token);
                                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[change_password_link]"], [$name, $company_title, $company_link, $submit], $smsTemplate->template_html_sms);
                                    $message = htmlspecialchars_decode(strip_tags($html));

                                    // Remove unwanted '&nbsp;' text
                                    $message = str_replace('&nbsp;', ' ', $message);


                                    $contact_number = Helper::getReqestPhoneCode($user->contact_number, $user->country_id);
                                    try {
                                        if (Helper::activeTwilioSetting()) {
                                            $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;
                                            $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                            Log::error('UsrController:: going to send sms ' . " user contect Number::" . $contact_number);
                                            $twilioService->sendSMS($to, $message);
                                            Log::error('UsrController:: sms send');
                                        } else {
                                            $to = $SettingModel->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;

                                            $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                            $PlivoService->sendSMS($to, $message);
                                        }
                                    } catch (Exception $e) {
                                        Log::error('Failed to send SMS: ' . $e->getMessage());
                                        echo "Failed to send SMS: " . $e->getMessage();
                                    }
                                }
                            }
                        } else {
                            $notFoundNumber[] = $number;
                        }
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'change_pass') {
                $notFoundEmails = [];

                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {

                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();
                        if (!empty($user)) {
                            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'change_pass')->first();

                            if (!empty($smsTemplate)) {
                                $SettingModel = SettingModel::first();
                                if (!empty($companyId)) {
                                    $SettingModel = SettingModel::where('user_id', $companyId)->first();
                                }
                                if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {

                                    $name = $user->first_name;
                                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                                    $company_link = $webUrl ? $webUrl : '';

                                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]"], [$name, $company_title, $company_link,], $smsTemplate->template_html_sms);
                                    $message = htmlspecialchars_decode(strip_tags($html));

                                    // Remove unwanted '&nbsp;' text
                                    $message = str_replace('&nbsp;', ' ', $message);
                                    $contact_number = Helper::getReqestPhoneCode($user->contact_number, $user->country_id);
                                    try {
                                        if (Helper::activeTwilioSetting()) {
                                            $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;
                                            $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                            $twilioService->sendSMS($to, $message);
                                        } else {
                                            $to = $SettingModel->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;

                                            $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                            $PlivoService->sendSMS($to, $message);
                                        }
                                    } catch (Exception $e) {
                                        Log::error('Failed to send SMS: ' . $e->getMessage());
                                        echo "Failed to send SMS: " . $e->getMessage();
                                    }
                                }
                            }
                            //End sms
                        } else {
                            $notFoundNumber[] = $number;
                        }
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'new_task') {


                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {

                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();
                        if (!empty($user)) {
                            if ($user->sms_new_task_notification != '1') {
                                $SettingValue = SettingModel::where('user_id', $companyId)->first();
                                $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'new_task')->first();



                                $companyData =  CampaignModel::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                                // foreach ($companyDatas as $companyData) {

                                if (!empty($smsTemplate)) {
                                    if (!empty($SettingValue) && !empty($SettingValue->sms_account_sid) && !empty($SettingValue->sms_account_token) && !empty($SettingValue->sms_account_number)) {
                                        $name =  $user->FullName;
                                        $contact_number =  $user->contact_number;
                                        $company_title = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : 'Referdio';
                                        $company_link = $webUrl ? $webUrl : '';
                                        $campaign_title = $companyData->title;
                                        $campaign_join_link = route('front.campaign.Join', base64_encode($companyData->id));
                                        $campaign_price = !empty($companyData->text_reward) ? $companyData->text_reward : $companyData->reward;
                                        $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]", "[campaign_join_link]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price, $campaign_join_link], $smsTemplate->template_html_sms);

                                        // Remove HTML tags and decode HTML entities
                                        $message = htmlspecialchars_decode(strip_tags($html));

                                        // Remove unwanted '&nbsp;' text
                                        $message = str_replace('&nbsp;', ' ', $message);

                                        try {
                                            if (Helper::activeTwilioSetting()) {
                                                $to = $SettingValue->sms_mode == "2" ? $contact_number : $SettingValue->sms_account_to_number;
                                                $twilioService = new TwilioService($SettingValue->sms_account_sid, $SettingValue->sms_account_token, $SettingValue->sms_account_number);
                                                $twilioService->sendSMS($to, $message);
                                            } else {
                                                $to = $SettingValue->plivo_mode == "2" ? $contact_number : $SettingValue->plivo_test_phone_number;

                                                $PlivoService = new PlivoService($SettingValue->plivo_auth_id, $SettingValue->plivo_auth_token, $SettingValue->plivo_phone_number);
                                                $PlivoService->sendSMS($to, $message);
                                            }
                                        } catch (Exception $e) {
                                            Log::error('Notifications >> Que SMS Fail => ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        } else {
                            $notFoundNumber[] = $number;
                        }
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'earn_reward') {
                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {

                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();
                        if (!empty($user)) {
                            $reward = UserCampaignHistoryModel::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'earn_reward')->first();

                            if (!empty($smsTemplate)) {
                                $SettingModel = SettingModel::first();
                                if (!empty($companyId)) {
                                    $SettingModel = SettingModel::where('user_id', $companyId)->first();
                                }
                                if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {

                                    $name =  $user->FullName;
                                    $contact_number =  $user->contact_number;
                                    $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                                    $company_link = $webUrl ? $webUrl : '';
                                    $campaign_title = $reward->getCampaign->title;
                                    $campaign_price = !empty($reward->text_reward) ? $reward->text_reward : $reward->reward;
                                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price], $smsTemplate->template_html_sms);

                                    // Remove HTML tags and decode HTML entities
                                    $message = htmlspecialchars_decode(strip_tags($html));

                                    // Remove unwanted '&nbsp;' text
                                    $message = str_replace('&nbsp;', ' ', $message);

                                    try {
                                        if (Helper::activeTwilioSetting()) {
                                            $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;
                                            $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                            $twilioService->sendSMS($to, $message);
                                        } else {
                                            $to = $SettingModel->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;

                                            $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                            $PlivoService->sendSMS($to, $message);
                                        }
                                    } catch (Exception $e) {
                                        Log::error('Failed to send SMS: ' . $e->getMessage());
                                        echo "Failed to send SMS: " . $e->getMessage();
                                    }
                                }
                            }
                        } else {
                            $notFoundNumber[] = $number;
                        }
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'custom') {
                foreach ($request->contact_number as $number) {
                    if (!empty($number)) {

                        $user = User::where('contact_number', $number)->where('company_id', $companyId)->where('user_type', '4')->first();
                        if (!empty($user)) {
                            if ($user->sms_custom_notification != '1') {
                                $SettingValue = SettingModel::where('user_id', $companyId)->first();
                                $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'custom')->first();



                                $companyData =  CampaignModel::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                                // foreach ($companyDatas as $companyData) {

                                if (!empty($smsTemplate)) {
                                    if (!empty($SettingValue) && !empty($SettingValue->sms_account_sid) && !empty($SettingValue->sms_account_token) && !empty($SettingValue->sms_account_number)) {
                                        $name =  $user->FullName;
                                        $contact_number =  $user->contact_number;
                                        $company_title = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : 'Referdio';
                                        $company_link = $webUrl ? $webUrl : '';
                                        $campaign_title = $companyData->title;
                                        $campaign_price = !empty($companyData->text_reward) ? $companyData->text_reward : $companyData->reward;

                                        //set survey shortcut
                                        $campaign_join_link = route('front.campaign.Join', base64_encode($companyData->id));
                                        $template = $smsTemplate->template_html_sms;

                                        $pattern = '/\[survey\[(.*?)\]\]/';
                                        preg_match_all($pattern, $template, $matches);
                                        if (!empty($matches[1])) {
                                            foreach ($matches[1] as $surveyValue) {
                                                $surveyFrom = Helper::getSurveyFrom($surveyValue);
                                                $survey_link = $company_link . '/survey' . '/' . $surveyFrom->slug;

                                                $template = str_replace('[survey[' . $surveyValue . ']]', $survey_link, $template);
                                            }
                                        }
                                        $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]", "[campaign_join_link]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price, $campaign_join_link], $template);

                                        // Remove HTML tags and decode HTML entities
                                        $message = htmlspecialchars_decode(strip_tags($html));

                                        // Remove unwanted '&nbsp;' text
                                        $message = str_replace('&nbsp;', ' ', $message);

                                        try {
                                            if (Helper::activeTwilioSetting()) {
                                                $to = $SettingValue->sms_mode == "2" ? $contact_number : $SettingValue->sms_account_to_number;
                                                $twilioService = new TwilioService($SettingValue->sms_account_sid, $SettingValue->sms_account_token, $SettingValue->sms_account_number);
                                                $twilioService->sendSMS($to, $message);
                                            } else {
                                                $to = $SettingValue->plivo_mode == "2" ? $contact_number : $SettingValue->plivo_test_phone_number;

                                                $PlivoService = new PlivoService($SettingValue->plivo_auth_id, $SettingValue->plivo_auth_token, $SettingValue->plivo_phone_number);
                                                $PlivoService->sendSMS($to, $message);
                                            }
                                        } catch (Exception $e) {
                                            Log::error('Notifications >> Que SMS Fail => ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        } else {

                            $SettingValue = SettingModel::where('user_id', $companyId)->first();
                            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'custom')->first();



                            $companyData =  CampaignModel::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                            // foreach ($companyDatas as $companyData) {

                            if (!empty($smsTemplate)) {

                                if (!empty($SettingValue) && !empty($SettingValue->sms_account_sid) && !empty($SettingValue->sms_account_token) && !empty($SettingValue->sms_account_number)) {
                                    $name =  "";
                                    $contact_number =  $number;
                                    $company_title = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : 'Referdio';
                                    $company_link = $webUrl ? $webUrl : '';
                                    $campaign_title = $companyData->title;
                                    $campaign_price = !empty($companyData->text_reward) ? $companyData->text_reward : $companyData->reward;

                                    //set survey shortcut
                                    $campaign_join_link = route('front.campaign.Join', base64_encode($companyData->id));
                                    $template = $smsTemplate->template_html_sms;

                                    $pattern = '/\[survey\[(.*?)\]\]/';
                                    preg_match_all($pattern, $template, $matches);
                                    if (!empty($matches[1])) {
                                        foreach ($matches[1] as $surveyValue) {
                                            $surveyFrom = Helper::getSurveyFrom($surveyValue);
                                            $survey_link = $company_link . '/survey' . '/' . $surveyFrom->slug;

                                            $template = str_replace('[survey[' . $surveyValue . ']]', $survey_link, $template);
                                        }
                                    }
                                    $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]", "[campaign_join_link]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price, $campaign_join_link], $template);

                                    // Remove HTML tags and decode HTML entities
                                    $message = htmlspecialchars_decode(strip_tags($html));

                                    // Remove unwanted '&nbsp;' text
                                    $message = str_replace('&nbsp;', ' ', $message);

                                    try {
                                        if (Helper::activeTwilioSetting()) {
                                            $to = $SettingValue->sms_mode == "2" ? $contact_number : $SettingValue->sms_account_to_number;
                                            $twilioService = new TwilioService($SettingValue->sms_account_sid, $SettingValue->sms_account_token, $SettingValue->sms_account_number);
                                            $twilioService->sendSMS($to, $message);
                                        } else {
                                            $to = $SettingValue->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;


                                            $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                            $PlivoService->sendSMS($to, $message);
                                        }
                                    } catch (Exception $e) {
                                        Log::error('Notifications >> Que SMS Fail => ' . $e->getMessage());
                                    }
                                }
                            }
                            // $notFoundNumber[] = $number;
                        }
                    }
                }
                // $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('company.sms.index')->with([
                    'success' => 'SMS sent successfully'

                ]);
            }
            return redirect()->route('company.sms.index')
                ->with('success', 'SMS send successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::SendSms  => ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'no found ' . $request->mail);
        }
    }
    public function sendAllSms(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $userDatas = User::where('user_type', User::USER_TYPE['USER'])
                ->where('status', '1')
                ->where('company_id', $companyId)
                ->get();
            $SettingModel = SettingModel::where('user_id', $companyId)->first();

            if (empty($SettingModel) && empty($SettingModel->sms_account_sid) && empty($SettingModel->sms_account_token) && empty($SettingModel->sms_account_number)) {
                return response()->json(['success' => false, 'message' => "Error: Please enter SMS Credential"]);
            }

            if (!$userDatas->isEmpty()) {
                $notificationsQueBatch = [];

                foreach ($userDatas as $userData) {
                    $notificationsQueBatch[] = [
                        'company_id' => $companyId,
                        'user_id' => $userData->id,

                        'notifications_type' => "2",
                        'type' => $request->type,
                        'created_at' => now(),
                    ];

                    if (count($notificationsQueBatch) >= 1000) {
                        NotificationsQue::insert($notificationsQueBatch);
                        $notificationsQueBatch = []; // Reset the batch array
                    }
                }

                // Insert any remaining records
                if (!empty($notificationsQueBatch)) {
                    NotificationsQue::insert($notificationsQueBatch);
                }
            }
            return response()->json(['success' => true, 'message' => 'Send SMS successfully']);
        } catch (Exception $e) {
            Log::error('SmstemplateController::sendAllSms  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\MailTemplate;
use App\Models\NotificationsQue;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Services\PlivoService;
use App\Services\TwilioService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class TemplateController extends Controller
{
    function index()
    {
        try {
            return view('admin.mailTemplate.list');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function list(Request $request)
    {
        try {
            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;

            $columns = ['id'];
            $totalData = MailTemplate::where('company_id', $companyId)->count();

            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['template_html'];
            $query = MailTemplate::orderBy($columns[0], $dir);

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
                } elseif ($result->template_type == 'custom') {
                    $type = 'Custom';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,
                    $result->subject,
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
            return view('admin.mailTemplate.create');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function edit($id)
    {
        try {
            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;
            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            if (empty($mailTemplate)) {
                return redirect()->back()->with('error', 'Mail Template Not Found')->withInput();
            }
            return view('admin.mailTemplate.create', compact('mailTemplate'));
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'required',
                'tempHtml' => 'required',
            ]);

            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;
            $mailTemplate = MailTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();

            if (empty($mailTemplate) || empty($request->id)) {

                $existingTemplate = MailTemplate::where('company_id', $companyId)
                    ->where('template_type', $request->type)
                    ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->withInput()->with('error', 'Template already exit');
                }

                $mailTemplate = new MailTemplate;
                $mailTemplate->template_type = $request->type;
            }

            $mailTemplate->company_id = $companyId;

            $mailTemplate->template_html = $request->tempHtml;
            $mailTemplate->subject = $request->subject;

            $mailTemplate->save();
            return redirect()->route('admin.mail.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('MailtemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    function smsIndex()
    {
        try {
            return view('admin.smsTemplate.list');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function smsList(Request $request)
    {
        try {
            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;
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
                $query->where('company_id', $companyId);
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
                } elseif ($result->template_type == 'custom') {
                    $type = 'Custom';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,
                    $result->template_type ?? "-",

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
    function smsCreate()
    {
        try {
            return view('admin.smsTemplate.create');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function smsEdit($id)
    {
        try {
            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            if (empty($SmsTemplate)) {
                return redirect()->back()->with('error', 'SMS Template Not Found')->withInput();
            }
            return view('admin.smsTemplate.create', compact('SmsTemplate'));
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function smsStore(Request $request)
    {

        try {
            $companyId = (auth()->user()->user_type == "1") ?? auth()->user()->id;
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();

            if (empty($SmsTemplate) || empty($request->id)) {

                $existingTemplate = SmsTemplate::where('company_id', $companyId)
                    ->where('template_type', $request->type)
                    ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->with('error', 'Template already exit ');
                }

                $SmsTemplate = new SmsTemplate;
                $SmsTemplate->template_type = $request->type;
            }

            $SmsTemplate->company_id = $companyId;

            $SmsTemplate->template_html_sms = $request->tempHtml;

            $SmsTemplate->save();
            return redirect()->route('admin.sms.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sendMail(Request $request)
    {
        try {
            $companyId = auth::user()->id;

            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            $webUrl = "";
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }
            $SettingModel = SettingModel::where('user_id', $companyId)->first();

            if (
                empty($SettingModel) && empty($SettingModel->mail_username) && empty($SettingModel->mail_host) && empty($SettingModel->mail_password)
            ) {
                return redirect()->route('admin.mail.index')->with(['error' => "Please enter mail credentials"]);
            }


            $notFoundEmails = [];
            if ($request->template_type == 'welcome') {
                foreach ($request->mail as $mail) {
                    $user = User::where('email', $mail)->where('user_type', '2')->first();
                    if (!empty($user)) {

                        try {
                            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'welcome')->first();

                            $userName  = $request->fname . ' ' . $request->lname;
                            $to = $request->email;

                            $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : '';
                            $settingTitle = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : env('APP_NAME');
                            $subject = !empty($mailTemplateSubject) ? $mailTemplateSubject : 'Welcome To ' . $settingTitle;

                            $message = '';
                            $type =  "";
                            $html =  $mailTemplate->template_html ? $mailTemplate->template_html : null;

                            $data =  ['first_name' => $request->fname, 'company_id' => $companyId, 'template' => $html, 'webUrl' => $webUrl];


                            SendEmailJob::dispatch($to, $subject, $message, $userName, $data, $type);
                        } catch (Exception $e) {
                            Log::error('UsrController::Store => ' . $e->getMessage());
                        }
                    } else {
                        $notFoundEmails[] = $mail;
                    }
                }
                $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

                return redirect()->route('admin.mail.index')->with([
                    'success' => 'Mail sent successfully',
                    'error_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'forgot_password') {

                foreach ($request->mail as $mail) {
                    $user = User::where('email', $mail)->where('user_type', '2')->first();
                    if (!empty($user)) {
                        $token = Str::random(64);
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'forgot_password')->first();
                        $html = "";

                        $submit = route('user.confirmPassword', $token);

                        if (!empty($mailTemplate)) {
                            $html = $mailTemplate->template_html;
                        }
                        DB::table('password_resets')->insert([
                            'email' => $user->email,
                            'token' => $token,
                            'created_at' => Carbon::now()
                        ]);
                        try {
                            $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Reset Password';

                            Mail::send(
                                'company.email.forgetPassword',
                                [
                                    'token' => $token,
                                    'email' => $user->email,
                                    'name' => $user->FullName,
                                    'webUrl' => $webUrl,
                                    'template' => $html
                                ],
                                function ($message) use ($user, $mailTemplateSubject) {
                                    $message->to($user->email);
                                    $message->subject($mailTemplateSubject);
                                }
                            );
                        } catch (Exception $e) {

                            Log::error('CompanyLoginController::submitForgetPassword => ' . $e->getMessage());
                            return redirect()->back()->with('error', "Something went wrong");
                        }
                    } else {
                        $notFoundEmails[] = $mail;
                    }
                }
                $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

                return redirect()->route('admin.mail.index')->with([
                    'success' => 'Mail sent successfully',
                    'error_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'change_pass') {
                $notFoundEmails = [];
                foreach ($request->mail as $mail) {
                    $user = User::where('email', $mail)->where('user_type', '2')->first();
                    if (!empty($user)) {

                        try {
                            $SettingValue = SettingModel::first();
                            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'change_pass')->first();

                            $userName  = $user->first_name . ' ' . $user->last_name;
                            $to = $request->email;
                            $message = '';
                            $type =  "user";
                            $html =  $mailTemplate->template_html;

                            Mail::send('company.email.passwordChange', ['user' => $user, 'first_name' => $userName, 'company_id' => 1, 'template' => $html, 'webUrl' => $webUrl], function ($message) use ($user) {
                                $message->to($user->email);
                                $message->subject(!empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Your New Password Updated Successfully.');
                            });
                        } catch (Exception $e) {
                            Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
                        }
                    } else {
                        $notFoundEmails[] = $mail;
                    }
                }
                $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

                return redirect()->route('admin.mail.index')->with([
                    'success' => 'Mail sent successfully',
                    'error_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'custom') {
                foreach ($request->mail as $mail) {
                    $user = User::where('email', $mail)->where('user_type', '2')->first();
                    if (!empty($user)) {
                        try {
                            $SettingValue = SettingModel::first();
                            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'custom')->first();

                            $userName  = $user->first_name . ' ' . $user->last_name;
                            $to = $request->email;
                            $message = '';
                            $type =  "user";
                            $html =  $mailTemplate->template_html;

                            Mail::send('company.email.custom', ['user' => $user, 'first_name' => $userName, 'company_id' => 1, 'template' => $html, 'webUrl' => $webUrl], function ($message) use ($user) {
                                $message->to($user->email);
                                $message->subject(!empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Referdio');
                            });
                        } catch (Exception $e) {
                            Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
                        }
                    } else {
                        $notFoundEmails[] = $mail;
                    }
                }

                return redirect()->route('admin.mail.index')->with([
                    'success' => 'Mail sent successfully',

                ]);
            }
            return redirect()->route('admin.mail.index')
                ->with('success', 'Mail send successfully');
        } catch (Exception $e) {
            Log::error('TemplateController::sendMail  => ' . $e->getMessage());
            return redirect()->route('admin.mail.index')
                ->with('error', 'Something went wrong.');
        }
    }

    public function sendAllMail(Request $request)
    {
        try {
            $companyId = auth::user()->id;

            $userDatas = User::where('user_type', User::USER_TYPE['COMPANY'])->where('status', '1')->get();

            $SettingModel = SettingModel::where('user_id', $companyId)->first();

            if (
                empty($SettingModel) && empty($SettingModel->mail_username) && empty($SettingModel->mail_host) && empty($SettingModel->mail_password)
            ) {
                return response()->json(['success' => false, 'message' => "Error: Please enter mail credentials "]);
            }

            if (!$userDatas->isEmpty()) {
                $notificationsQueBatch = [];

                foreach ($userDatas as $userData) {
                    $notificationsQueBatch[] = [
                        'company_id' => $companyId,
                        'user_id' => $userData->id,
                        'notifications_type' => "1",
                        'type' => $request->type,
                        'created_at' => now(),
                    ];

                    // Check if the batch size exceeds a certain limit (e.g., 1000 records)
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
            return response()->json(['success' => true, 'message' => 'Send mail successfully']);
        } catch (Exception $e) {
            Log::error('TemplateController::sendAllMail  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }
    //Sms
    public function sendSms(Request $request)
    {

        try {
            $adminId = auth::user()->id;

            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            $webUrl = "";
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }

            $SettingModel = SettingModel::where('user_id', $adminId)->first();

            if (empty($SettingModel) || (Helper::activeTwilioSetting() == false  && $SettingModel->sms_type != '2') || (Helper::activePlivoSetting() == false  && $SettingModel->sms_type != '1')) {
                return redirect()->route('admin.sms.index')->with(['error' => "Please enter SMS Credential "]);
            }
            $notFoundNumber = [];
            if ($request->template_type == 'welcome') {
                foreach ($request->contact_number as $number) {
                    $user = User::where('contact_number', $number)->where('user_type', '2')->first();

                    if (!empty($user)) {
                        $smsTemplate = SmsTemplate::where('company_id', $adminId)->where('template_type', 'welcome')->first();
                        if (!empty($smsTemplate)) {
                            // $SettingModel = SettingModel::first();

                            $SettingModel = SettingModel::where('user_id', $adminId)->first();

                            if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {
                                $name = $user->fname;
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
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('admin.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'forgot_password') {

                foreach ($request->contact_number as $number) {
                    $user = User::where('contact_number', $number)->where('user_type', '2')->first();
                    if (!empty($user)) {
                        $token = Str::random(64);
                        $smsTemplate = SmsTemplate::where('company_id', $adminId)->where('template_type', 'forgot_password')->first();

                        if (!empty($smsTemplate)) {

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
                                    $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;
                                    $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                    if (Helper::activeTwilioSetting()) {
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
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('admin.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'change_pass') {
                $notFoundEmails = [];
                foreach ($request->contact_number as $number) {
                    $user = User::where('contact_number', $number)->where('user_type', '2')->first();
                    if (!empty($user)) {
                        $smsTemplate = SmsTemplate::where('company_id', $adminId)->where('template_type', 'change_pass')->first();

                        if (!empty($smsTemplate)) {

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
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('admin.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            } elseif ($request->template_type == 'custom') {

                foreach ($request->contact_number as $number) {
                    $user = User::where('contact_number', $number)->where('user_type', '2')->first();

                    if (!empty($user)) {
                        $smsTemplate = SmsTemplate::where('company_id', $adminId)->where('template_type', 'custom')->first();
                        if (!empty($smsTemplate)) {

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
                    } else {
                        $notFoundNumber[] = $number;
                    }
                }
                $errorMessage = count($notFoundNumber) > 0 ? '<strong>SMS not sent this Number</strong> :' . implode(', ', $notFoundNumber) : '';

                return redirect()->route('admin.sms.index')->with([
                    'success' => 'SMS sent successfully',
                    'error_sms_hold' => $errorMessage
                ]);
            }
            return redirect()->route('admin.sms.index')
                ->with('success', 'SMS send successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::sendSms  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }

    public function sendAllSms(Request $request)
    {
        try {
            $adminId = auth::user()->id;
            $userDatas = User::where('user_type', User::USER_TYPE['COMPANY'])->where('status', '1')->get();

            $SettingModel = SettingModel::where('user_id', $adminId)->first();
            if (empty($SettingModel) || (Helper::activeTwilioSetting() == false  && $SettingModel->sms_type != '2') || (Helper::activePlivoSetting() == false  && $SettingModel->sms_type != '1')) {
                return response()->json(['success' => false, 'message' => "Error: Please Enter SMS Credential"]);
            }

            if (!$userDatas->isEmpty()) {
                $notificationsQueBatch = [];

                foreach ($userDatas as $userData) {
                    $notificationsQueBatch[] = [
                        'company_id' => $adminId,
                        'user_id' => $userData->id,

                        'notifications_type' => "2",
                        'type' => $request->type,
                        'created_at' => now(),
                    ];

                    // Check if the batch size exceeds a certain limit (e.g., 1000 records)
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
            return response()->json(['success' => true, 'message' => 'Send SMS Successfully']);
        } catch (Exception $e) {
            Log::error('SmstemplateController::sendAllSms  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }
}
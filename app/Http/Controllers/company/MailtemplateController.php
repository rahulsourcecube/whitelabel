<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\CampaignModel;
use App\Models\MailTemplate;
use App\Models\NotificationsQue;
use App\Models\SettingModel;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class MailtemplateController extends Controller
{
    function index()
    {
        try {
            $companyId = Helper::getCompanyId();

            return view('company.mailTemplate.list');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function list(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
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
                } elseif ($result->template_type == 'new_task') {
                    $type = 'New Task';
                } elseif ($result->template_type == 'earn_reward') {
                    $type = 'Earn Reward';
                } elseif ($result->template_type == 'custom') {
                    $type = 'Custom Mail ';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,
                    $result->subject,
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
    function create()
    {
        try {
            return view('company.mailTemplate.create');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function edit($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            if (empty($mailTemplate)) {
                return redirect()->back()->with('error', 'No Found Mail Template ')->withInput();
            }
            return view('company.mailTemplate.create', compact('mailTemplate'));
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tempHtml' => 'required',

            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $companyId = Helper::getCompanyId();
            $mailTemplate = MailTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();


            if (empty($mailTemplate) || empty($request->id)) {

                $existingTemplate = MailTemplate::where('company_id', $companyId)
                    ->where('template_type', $request->type)
                    ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->withInput()->with('error', 'Template already exit ' . $request->type);
                }

                $mailTemplate = new MailTemplate;
                $mailTemplate->template_type = $request->type;
            }

            $mailTemplate->company_id = $companyId;

            $mailTemplate->template_html = $request->tempHtml;
            $mailTemplate->subject = $request->subject;

            $mailTemplate->save();
            return redirect()->route('company.mail.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('MailtemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sendMail(Request $request)
    {
        // try {
        $companyId = Helper::getCompanyId();

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
        $SettingModel = SettingModel::find($companyId);

        if (
            empty($SettingModel) &&
            empty($SettingModel->mail_username) &&
            empty($SettingModel->mail_host) &&
            empty($SettingModel->mail_password)
        ) {
            return redirect()->route('company.mail.index')->with(['error' => "Please enter mail Cridntioal"]);
        }


        $notFoundEmails = [];
        if ($request->template_type == 'welcome') {
            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    try {
                        $SettingValue = SettingModel::where('id', $companyId)->first();
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'welcome')->first();
                        $userName  = $user->fname . ' ' . $user->lname;
                        $to = $user->email;

                        $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : '';
                        $settingTitle = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : env('APP_NAME');
                        $subject = !empty($mailTemplateSubject) ? $mailTemplateSubject : 'Welcome To ' . $settingTitle;

                        $message = '';
                        $type =  "user";
                        $html =  !empty($mailTemplate) && !empty($mailTemplate->template_html) ? $mailTemplate->template_html : "";

                        $data =  ['first_name' => $user->first_name, 'company_id' => $companyId, 'template' => $html, 'webUrl' => $webUrl];
                        if ((config('app.sendmail') == 'true' && config('app.mailSystem') == 'local') || (config('app.mailSystem') == 'server')) {
                            SendEmailJob::dispatch($to, $subject, $message, $userName, $data, $type, $html);
                        }
                    } catch (Exception $e) {
                        Log::error('MailtemplateController::Store => ' . $e->getMessage());
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        } elseif ($request->template_type == 'forgot_password') {

            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    $token = Str::random(64);
                    $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'forgot_password')->first();
                    $html = "";
                    $webUrl = "";
                    $submit = route('user.confirmPassword', $token);
                    $currentUrl = URL::current();
                    $webUrlGetHost = $request->getHost();
                    if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                        // URL is under HTTPS
                        $webUrl =  'https://' . $webUrlGetHost;
                    } else {
                        // URL is under HTTP
                        $webUrl =  'http://' . $webUrlGetHost;
                    }
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
                        Mail::send('user.email.forgetPassword', [
                            'token' => $token,
                            'name' => $user->FullName,
                            'company_id' => $companyId,
                            'template' => $html,
                            'webUrl' => $webUrl
                        ], function ($message) use ($user, $mailTemplateSubject) {
                            $message->to($user->email);
                            $message->subject($mailTemplateSubject);
                        });
                    } catch (Exception $e) {
                        Log::error('UsrController:: => ' . $e->getMessage());
                        return redirect()->back()->with('error', "Something went wrong!");
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        } elseif ($request->template_type == 'change_pass') {
            $notFoundEmails = [];
            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    try {
                        // $user = User::where('email', $request->email)->where('company_id', $companyId)->first();

                        $SettingValue = SettingModel::where('id', $companyId)->first();
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'change_pass')->first();

                        $userName  = $user->first_name . ' ' . $user->last_name;
                        $to = $user->email;
                        // $subject = 'Welcome To '. !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : env('APP_NAME');

                        $message = '';
                        $html = "";
                        $type =  "user";
                        $html =  $mailTemplate->template_html;

                        Mail::send('user.email.passwordChange', ['user' => $user, 'first_name' => $userName, 'company_id' => $companyId, 'template' => $html, 'webUrl' => $webUrl], function ($message) use ($user) {
                            $message->to($user->email);
                            $message->subject(!empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'Your New Password Is Set');
                        });
                    } catch (Exception $e) {
                        Log::error('UsrController::SubmitResetPassword => ' . $e->getMessage());
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        } elseif ($request->template_type == 'new_task') {

            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    if ($user->mail_new_task_notification != '1') {
                        $SettingValue = SettingModel::where('user_id', $companyId)->first();
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'new_task')->first();


                        $companyData =  CampaignModel::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                        // foreach ($companyDatas as $companyData) {
                        $userDetails = User::where('email', $request->mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                        try {

                            if (!empty($userDetails) && !empty($mailTemplate) && !empty($mailTemplate->template_html)) {

                                $userName  = $userDetails->FullName;
                                $campaign_title  = $companyData->title;
                                $campaign_price = $companyData->text_reward ? $companyData->text_reward : $companyData->reward;
                                $to = $userDetails->email;
                                $campaign_join_link = route('user.campaign.getusercampaign', base64_encode($companyData->id));

                                $message = '';

                                $html =  $mailTemplate->template_html;

                                $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'new_task';

                                Mail::send('user.email.creatednewTask', [
                                    'name' => $userName,
                                    'company_id' => $companyData->company_id,
                                    'template' => $html,
                                    'webUrl' => $webUrl,
                                    'campaign_title' => $campaign_title,
                                    'campaign_price' => $campaign_price,
                                    'campaign_join_link' => $campaign_join_link
                                ], function ($message) use ($to, $mailTemplateSubject) {
                                    $message->to($to);
                                    $message->subject($mailTemplateSubject);
                                });
                            }
                            // }
                        } catch (Exception $e) {
                            Log::error('Notifications >> Que MAIL Fail => ' . $e->getMessage());
                        }
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        } elseif ($request->template_type == 'earn_reward') {
            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    try {
                        $reward = UserCampaignHistoryModel::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                        $SettingValue = SettingModel::where('id', $companyId)->first();
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'earn_reward')->first();
                        $userDetails = User::where('id', $reward->user_id)->where('company_id', $companyId)->first();
                        if (!empty($userDetails) && !empty($mailTemplate) && !empty($mailTemplate->template_html)) {
                            $userName  = $userDetails->FullName;
                            $campaign_title  = $reward->getCampaign->title;
                            $campaign_price = $reward->text_reward ? 'text_reward' : $reward->reward;
                            $to = $userDetails->email;
                            $message = '';

                            $html =  $mailTemplate->template_html;

                            $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'earn_reward';
                            Mail::send('user.email.earnReward', [
                                'name' => $userName,
                                'company_id' => $companyId,
                                'template' => $html,
                                'webUrl' => $webUrl,
                                'campaign_title' => $campaign_title,
                                'campaign_price' => $campaign_price,
                            ], function ($message) use ($to, $mailTemplateSubject) {
                                $message->to($to);
                                $message->subject($mailTemplateSubject);
                            });
                        }
                    } catch (Exception $e) {
                        Log::error('CampaignController::Action => ' . $e->getMessage());
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ?  implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        } elseif ($request->template_type == 'custom') {
            foreach ($request->mail as $mail) {
                $user = User::where('email', $mail)->where('company_id', $companyId)->where('user_type', '4')->first();
                if (!empty($user)) {
                    if ($user->mail_custom_notification != '1') {

                        try {
                            $cmpaign =  CampaignModel::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                            $SettingValue = SettingModel::where('id', $companyId)->first();
                            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'custom')->first();
                            $userDetails = User::where('id', $user->id)->where('company_id', $companyId)->first();
                            if (!empty($userDetails) && !empty($mailTemplate) && !empty($mailTemplate->template_html)) {
                                $userName  = $userDetails->FullName;
                                $campaign_title  = $cmpaign->title;
                                $campaign_price = $cmpaign->text_reward ? 'text_reward' : $cmpaign->reward;
                                $campaign_join_link = route('user.campaign.getusercampaign', base64_encode($cmpaign->id));
                                $to = $userDetails->email;
                                $message = '';

                                $html =  $mailTemplate->template_html;

                                $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'earn_reward';
                                Mail::send('user.email.custom', [
                                    'name' => $userName,
                                    'company_id' => $companyId,
                                    'template' => $html,
                                    'webUrl' => $webUrl,
                                    'campaign_title' => $campaign_title,
                                    'campaign_price' => $campaign_price,
                                    'campaign_price' => $campaign_price,
                                    'campaign_join_link' => $campaign_join_link
                                ], function ($message) use ($to, $mailTemplateSubject) {
                                    $message->to($to);
                                    $message->subject($mailTemplateSubject);
                                });
                            }
                        } catch (Exception $e) {
                            Log::error('CampaignController::Action => ' . $e->getMessage());
                        }
                    }
                } else {
                    $notFoundEmails[] = $mail;
                }
            }
            $errorMessage = count($notFoundEmails) > 0 ? implode(', ', $notFoundEmails) : '';

            return redirect()->route('company.mail.index')->with([
                'success' => 'Mail sent successfully',
                'error_hold' => $errorMessage
            ]);
        }
        return redirect()->route('company.mail.index')
            ->with('success', 'Mail send successfully');
        // } else {
        //     Log::error('MailtemplateController::Delete  => ' . $e->getMessage());
        //     return redirect()->back()->withInput()->with('error', 'no found ' . $request->mail);
        // }
    }
    public function sendAllMail(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();


            // $notificationsQue=new NotificationsQue()
            $userDatas = User::where('user_type', User::USER_TYPE['USER'])
                ->where('status', '1')
                ->where('company_id', $companyId)
                ->get();

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
            // return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        } catch (Exception $e) {
            Log::error('MailtemplateController::sendAllMail  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }
}

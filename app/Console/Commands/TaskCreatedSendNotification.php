<?php

namespace App\Console\Commands;

use App\Models\CompanyModel;
use App\Models\MailTemplate;
use App\Models\NotificationsQue;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;




class TaskCreatedSendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newtask:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {

        $notificationsQues = NotificationsQue::select('company_id')->where('status', '0')->groupBy('company_id')->get();
        if (!empty($notificationsQues)) {

            foreach ($notificationsQues as $key => $notificationsQue) {

                $SettingValue = SettingModel::where('user_id', $notificationsQue->company_id)->first();
                $mailTemplate = MailTemplate::where('company_id', $notificationsQue->company_id)->where('template_type', 'new_task')->first();
                $smsTemplate = SmsTemplate::where('company_id', $notificationsQue->company_id)->where('template_type', 'new_task')->first();

                $companyDatas = NotificationsQue::where('company_id', $notificationsQue->company_id)->where('status', '0')->take(10)->get();
                foreach ($companyDatas as $companyData) {
                    $userDetails = User::where('user_type', "4")->where('status', "1")->where('company_id', $companyData->company_id)->first();

                    $webUrl =  'https://' . $notificationsQue->getCompany->subdomain . config('app.domain');

                    //Start Mail
                    try {
                        if ($companyData->notifications_type == '1' || $companyData->notifications_type == '3') {
                            if (!empty($userDetails) && !empty($mailTemplate) && !empty($mailTemplate->template_html)) {

                                $userName  = $userDetails->FullName;
                                $campaign_title  = $companyData->getCampaign->title;
                                $campaign_price = $companyData->getCampaign->text_reward ? $companyData->getCampaign->text_reward : $companyData->getCampaign->reward;
                                $to = $userDetails->email;
                                $campaign_join_link = route('front.campaign.Join', base64_encode($companyData->getCampaign->id));

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
                        }
                    } catch (Exception $e) {
                        Log::error('Notifications >> Que MAIL Fail => ' . $e->getMessage());
                    }
                    // End mail
                    if ($companyData->notifications_type == '2' || $companyData->notifications_type == '3') {
                        if (!empty($smsTemplate)) {

                            if (!empty($SettingValue) && !empty($SettingValue->sms_account_sid) && !empty($SettingValue->sms_account_token) && !empty($SettingValue->sms_account_number)) {
                                $name =  $userDetails->FullName;
                                $contact_number =  $userDetails->contact_number;
                                $company_title = !empty($SettingValue) && !empty($SettingValue->title) ? $SettingValue->title : 'Referdio';
                                $company_link = $webUrl ? $webUrl : '';
                                $campaign_title = $companyData->getCampaign->title ?? "";
                                $campaign_price = !empty($companyData->getCampaign->text_reward) ? $companyData->getCampaign->text_reward : $companyData->getCampaign->reward ?? "";
                                $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price], $smsTemplate->template_html_sms);

                                // Remove HTML tags and decode HTML entities
                                $message = htmlspecialchars_decode(strip_tags($html));

                                // Remove unwanted '&nbsp;' text
                                $message = str_replace('&nbsp;', ' ', $message);

                                $to = $SettingValue->type == "2" ? $contact_number : $SettingValue->sms_account_to_number;
                                $twilioService = new TwilioService($SettingValue->sms_account_sid, $SettingValue->sms_account_token, $SettingValue->sms_account_number);
                                try {
                                    $twilioService->sendSMS($to, $message);
                                } catch (Exception $e) {
                                    Log::error('Notifications >> Que SMS Fail => ' . $e->getMessage());
                                }
                            }
                        }
                    }
                    // End sms

                    if (!empty($userDetails)) {

                        $companyData->status = '1';
                        $companyData->save();
                    }
                }
            }
        }
        $this->info('Command executed successfully.');
    }
}

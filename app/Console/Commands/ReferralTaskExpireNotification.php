<?php

namespace App\Console\Commands;

use App\Models\CampaignModel;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ReferralTaskExpireNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Referral Task Expire Notification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $yesterday = $yesterday->format('Y-m-d');

        $type = CampaignModel::TYPE[strtoupper('REFERRAL')];
        $CampaignData = CampaignModel::where('type', $type)->where('expiry_date', $yesterday)->get();
        foreach ($CampaignData as $list) {
            $campaign_id = base64_encode($list->id);
            $list->status = '0';
            $list->save();
            if (isset($list->PendingCampaignUSerHistory)) {
                foreach ($list->PendingCampaignUSerHistory as $userData) {
                    $userData->referral_link = '';
                    $userData->save();
                    $Url = route('user.campaign.view', $campaign_id);
                    if (isset($userData->user_id)) {
                        $Notification = new Notification();
                        $Notification->user_id =  $userData->user_id ?? '';
                        $Notification->type =  '1';
                        $Notification->title =  "Referral task is expired";
                        $Notification->message =  "<a href='" . $Url . "' title='View' >" . Str::limit($list->title, 30) . " </a> task is expired and you can submit it for approval. ";
                        $Notification->save();
                    }
                }
            }
        }
        Log::channel('cron_logs')->info('Referral task is expired');
        return Command::SUCCESS;
    }
}

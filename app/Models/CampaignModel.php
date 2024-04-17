<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignModel extends Model
{
    use HasFactory, SoftDeletes;
    const TYPE = [
        'REFERRAL' => 1,
        'SOCIAL' => 2,
        'CUSTOM' => 3,
    ];
    protected $table = 'campaign';
    protected $fillable = [
        'company_id',
        'title',
        'reward',
        'text_reward',
        'description',
        'priority',
        'public',
        'expiry_date',
        'type',
        'image',
        'status',
        'feedback_type',
        'referral_url_segment',
        'country_id',
        'state_id',
        'city_id',
    ];

    protected $append = ["task_type", "social_task_user_count", "task_expired"];

    public function getTaskTypeAttribute()
    {
        $typeConst = array_flip(CampaignModel::TYPE);
        $type = $this->type;
        return ucfirst(strtolower($typeConst[$type]));
    }
    public function getTaskExpiredAttribute()
    {
        $expiryDate = Carbon::parse($this->expiry_date);
        $string = 'Active';
        // Check if the task's expiry_date is in the past (expired)
        if (Carbon::now()->greaterThanOrEqualTo($expiryDate)) {
            // Task is expired
            $string = 'Expired';
        } else {
            // Task is not expired
            $string = 'Active';
        }
        return $string;
    }

    public function getTaskStatusAttribute()
    {
        $status = $this->status;
        $string = 'Active';
        if ($status == 0) {
            $string = 'Deactive';
        }
        return $string;
    }


    public function campaign()
    {
        return $this->belongsTo(CampaignModel::class)->where('campaign_id', '!=', 'id');
    }

    public function social()
    {
        return $this->belongsTo(CampaignModel::class)->where('campaign_id', '!=', 'id');
    }
    public function campaignUSerHistoryData()
    {
        return $this->hasMany(UserCampaignHistoryModel::class, 'campaign_id', 'id');
    }
    public function campaignUSerHistory()
    {
        return $this->hasMany(UserCampaignHistoryModel::class, 'campaign_id', 'id')->where('status', '3');
    }
    public function PendingCampaignUSerHistory()
    {
        return $this->hasMany(UserCampaignHistoryModel::class, 'campaign_id', 'id')->where('status', '1');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignModel extends Model
{
    use HasFactory;
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
        'description',
        'expiry_date',
        'type',
        'image',
        'status',
    ];

    protected $append = ["task_type", "social_task_user_count"];

    public function getTaskTypeAttribute()
    {
        $typeConst = array_flip(CampaignModel::TYPE);
        $type = $this->type;
        return ucfirst(strtolower($typeConst[$type]));
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
  
    // function getcompany(){
    //     return $this->belongsTo(CompanyModel::class)->where('id','company_id');
    // }  
    public function campaignUSerHistory() {
        return $this->hasMany(UserCampaignHistoryModel::class, 'campaign_id','id')->where('status','3');
    }
}

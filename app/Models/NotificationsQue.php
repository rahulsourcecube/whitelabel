<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationsQue extends Model
{
    use HasFactory;
    protected $table = 'notifications_que';

    function getCampaign()
    {
        return $this->hasOne(CampaignModel::class, 'id', 'campaign_id');
    }
    function getCompany()
    {
        return $this->hasOne(CompanyModel::class, 'id', 'company_id');
    }
}

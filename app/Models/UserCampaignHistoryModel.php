<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

const TASK_STATUS = [
    'Pending ' => 1,
    'Claim Request' => 2,
    'Completed' => 3,
    'Rejected' => 4,
];
class UserCampaignHistoryModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="user_campaign_history";
    protected $fillable = [
        'id',
        'campaign_id',
        'reward',
        'user_id',
        'password',        
        'status',
        'verified_by',        
    ];
    
    function getuser(){
        return $this->hasOne(User::class,'id','user_id');
    }
    
    function getCampaign(){
        return $this->belongsTo(CampaignModel::class, 'campaign_id');
    }
    
    public function getTaskStatusAttribute() {
        $status = $this->status;
        $string = 'Pending';
        if($status == 1){
            $string = 'Pending';
        }elseif($status == 2){
            $string='Claim Request';
        }
        elseif($status == 3){
            $string='Completed';
        }
        elseif($status == 4){
            $string='Rejected';
        } elseif ($status == 5) {
            $string = 'Reopen';
        }
        return $string;
    }
}


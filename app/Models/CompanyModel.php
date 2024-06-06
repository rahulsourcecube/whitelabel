<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'company';

    /*protected $fillable = [
        'user_id',~
        'company_name',
        'company_description',
        'contact_email',
        'contact_number',
        'company_logo',
        'subdomain',
        'is_indivisual',
        // Add more fields as needed
    ];*/
    public function campaign()
    {
        return $this->belongsTo(CampaignModel::class)->where('company_id', 'user_id')->where('public', '1')->exite();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function checkDomain($subdomain)
    {
        return   $this->where('subdomain', $subdomain)->first();
    }
    public function setting()
    {
        return $this->hasOne(SettingModel::class, 'user_id');
    }

    public  function checkUserLogin($userId, $companyId)
    {

        $userCount = User::where('company_id', $companyId)->where('id', $userId)->first();
        if (empty($userCount)) {
            return false; // not login
        } else {
            return true; //logged in
        }
    }
}

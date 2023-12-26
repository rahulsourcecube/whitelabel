<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PackageModel extends Model
{
    use HasFactory;
    protected $table="package";
    const STATUS = [
        'ACTIVE' => '1',
        'INACTIVE' => '0',
    ];
    const TYPE = [
        'FREE' => 1,
        'MONTHLY' => 2,
        'YEARLY' => 3,
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function getPackageStringAttribute()
    {
        if($this->type == PackageModel::TYPE['FREE']){
            return 'Free Trial';
        }else if($this->type == PackageModel::TYPE['MONTHLY']){
            return 'Monthly';
        }else {
            return 'Yearly';
        }
    }

    public function getPlanPriceAttribute()
    {
        if($this->type == PackageModel::TYPE['FREE']){
            return 'Free';
        }else {
            return $this->price;
        }
    }

    public function getEndDateAttribute()
    {
        $duration = $this->duration;
        $currentDate = Carbon::now();
        $newDate = $currentDate->addDays(5);
        return $newDate->format('Y-m-d');
    }

    public function getStartDateAttribute()
    {
        $currentDate = Carbon::now();
        return $currentDate->format('Y-m-d');
    }

    public function getUserBoughtAttribute()
    {
        if(Auth::user()){
            $checkPackageBought = CompanyPackage::where('package_id', $this->id)->where('company_id', Auth::user()->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->exists();
            return $checkPackageBought;
        }
        return false;
    }
}

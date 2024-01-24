<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PackageModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "package";
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
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPackageStringAttribute()
    {
        if ($this->type == PackageModel::TYPE['FREE']) {
            return 'Free Trial';
        } else if ($this->type == PackageModel::TYPE['MONTHLY']) {
            return 'Monthly';
        } else {
            return 'Yearly';
        }
    }

    public function getPlanPriceAttribute()
    {
        if ($this->type == PackageModel::TYPE['FREE']) {
            return 'Free';
        } else {
            return $this->price;
        }
    }

    public function getEndDateAttribute()
    {
        $GetActivePackageData = Helper::GetLastPackageData();
        $currentDate = '';
        if ($GetActivePackageData == null) {
            $currentDate = Carbon::now();
        } else {
            $currentDate = Carbon::parse($GetActivePackageData->end_date);
            $currentDate = $currentDate->addDays(1);
        }
        $duration = $this->duration;
        if ($this->type == PackageModel::TYPE['MONTHLY']) {
            $newDate = $currentDate->addMonths($duration);
        } elseif ($this->type == PackageModel::TYPE['YEARLY']) {
            $newDate = $currentDate->addYears($duration);
        } else {
            $newDate = $currentDate->addDays($duration);
        }
        return $newDate->format('Y-m-d');
    }

    public function getStartDateAttribute()
    {
        $GetActivePackageData = Helper::GetLastPackageData();
        $currentDate = '';
        if ($GetActivePackageData == null) {
            $currentDate = Carbon::now();
        } else {
            $currentDate = Carbon::parse($GetActivePackageData->end_date);
            $currentDate = $currentDate->addDays(1);
        }
        return $currentDate->format('Y-m-d');
    }

    public function getUserBoughtAttribute()
    {
        if (Auth::user()) {
            $checkPackageBought = CompanyPackage::where('package_id', $this->id)->where('company_id', Auth::user()->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->exists();
            return $checkPackageBought;
        }
        return false;
    }
}

<?php

namespace App\Helpers;

use App\Models\CampaignModel;
use App\Models\CompanyPackage;
use App\Models\SettingModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function getSiteSetting()
    {
      
        try {
            $generalSetting = SettingModel::where('user_id', Auth::user()->id)->first();           
            return $generalSetting;
        } catch (\Exception $exception) {
            Log::info('site setting error : ' . $exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
    public static function getcurrency()
    {
        return '$';
    }

    public static function taskType($type)
    {
        $types = array_flip(CampaignModel::TYPE);
        return ucfirst(strtolower($types[$type])); 
    }

    public static function isActivePackage()
    {
        $user = Auth::user();
        $checkPackage = CompanyPackage::where('company_id', $user->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->exists();
        return $checkPackage;
    }
    public static function isInactivePackage()
    {
        $user = Auth::user();
        $checkPackage = CompanyPackage::where('company_id', $user->id)->where('status', '1')->count();
        return (int)$checkPackage > 0 ? true : false;
    }

    // get Active Package Data
    public static function GetActivePackageData()
    {

        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        $user = Auth::user();
        $packageData = CompanyPackage::where('company_id', $user->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '>=', $currentDate)->first();
        
        return $packageData;
    }

    // get Active Package Data
    public static function GetNextExpiryPackage()
    {

        $currentDate = Carbon::now()->addDays(7);
        $currentDate = $currentDate->format('Y-m-d');
        $user = Auth::user();
        $packageData = CompanyPackage::where('company_id', $user->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '>=', $currentDate)->where('end_date', '<=', $currentDate)->first();
        return $packageData;
    }

}

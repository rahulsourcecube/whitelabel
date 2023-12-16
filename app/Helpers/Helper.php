<?php

namespace App\Helpers;

use App\Models\CampaignModel;
use App\Models\CompanyPackage;
use App\Models\SettingModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function getSiteSetting()
    {
        try {
            $generalSetting = SettingModel::first();
            return $generalSetting;
        } catch (\Exception $exception) {
            Log::info('site setting error : ' . $exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
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
}

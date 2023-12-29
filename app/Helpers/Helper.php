<?php

namespace App\Helpers;

use App\Models\CampaignModel;
use App\Models\CompanyPackage;
use App\Models\SettingModel;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function getSiteSetting()
    {

        try {
            $companyId = Helper::getCompanyId();
            $generalSetting = SettingModel::where('user_id', $companyId)->first();
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
        $companyId = Helper::getCompanyId();
        $checkPackage = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->orderBy('id', 'desc')->exists();
        return $checkPackage;
    }
    public static function getCompanyId()
    {
        if (auth()->user()->user_type == env('COMPANY_ROLE') || auth()->user()->user_type == env('ADMIN_ROLE')) {
            $companyId = Auth::user()->id;
        } else {
            $companyId = Auth::user()->company_id;
        }

        return $companyId;
    }
    public static function isInactivePackage()
    {
        $companyId = Helper::getCompanyId();
        $checkPackage = CompanyPackage::where('company_id', $companyId)->where('status', '1')->count();
        return (int)$checkPackage > 0 ? true : false;
    }

    // get Active Package Data
    public static function GetActivePackageData()
    {

        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        $companyId = Helper::getCompanyId();

        // and then you can get query log
        $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();

        return $packageData;
    }

    // get Remaining Days
    public static function getRemainingDays()
    {
        try {
            $packageExpiringIN = 7;
            $Date = Carbon::now()->addDays($packageExpiringIN);
            $currentDate = $Date->format('Y-m-d');
            $companyId = Helper::getCompanyId();
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '<=', $currentDate)->first();

            if ($packageData != null && new DateTime($packageData->end_date) > Carbon::now()) {
                // Assuming $packageData->end_date is a string representing a date
                $end_date = new DateTime($packageData->end_date);
                // Add 24 hours to the end date
                $end_date->add(new DateInterval('PT24H'));
                $timeDifference = $end_date->diff(Carbon::now());
                $days = "";
                $hours = "";
                if ($timeDifference->format('%a') != 0) {
                    $days = $timeDifference->format('%a') . ' days';
                }
                if ($timeDifference->format('%a') == 0) {
                    $hours = $timeDifference->format('%h') . ' hours';
                }
                $remainingDays = $days . " " . $hours;
                return $remainingDays;
            } else {
                $remainingDays = null;
            }
            return $remainingDays;
        } catch (Exception $e) {
            Log::info("helper function get Remaining Days Error" . $e->getMessage());
            return null;
        }
    }

    // get Remaining Days
    public static function FreePackagePurchased()
    {
        $companyId = Helper::getCompanyId();

        $packageData = CompanyPackage::where('company_id', $companyId)->where('price', 0.00)->first();
        return $packageData;
    }
}

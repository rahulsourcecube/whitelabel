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

    // get Remaining Days
    public static function getRemainingDays()
    {
        try {
            $packageExpiringIN = 7;
            $Date = Carbon::now()->addDays($packageExpiringIN);
            $currentDate = $Date->format('Y-m-d');
            $user = Auth::user();
            $packageData = CompanyPackage::where('company_id', $user->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '<=', $currentDate)->first();

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

}

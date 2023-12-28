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
use GuzzleHttp\Psr7\Request;
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
        $checkPackage = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->orderBy('id','desc')->exists();
        return $checkPackage;
    }
    public static function getCompanyId()
    {
        if(auth()->user()->user_type == env('COMPANY_ROLE') || auth()->user()->user_type == env('ADMIN_ROLE')){
            $companyId = Auth::user()->id;
        }else{
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
        $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
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

    //Create host in local
    public static function createCompanySubDomain($subdomain) {

        $subdomain = $subdomain.'.'.$_SERVER['SERVER_NAME'];
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );

        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){

            $documentRoot = $_SERVER["DOCUMENT_ROOT"];
            $documentRoot = str_replace('/', '\\', $documentRoot );

            $virtualHostConfig = <<<EOL
                \n<VirtualHost *:80>
                        DocumentRoot "{$documentRoot}"
                        ServerName {$subdomain}
                        <Directory "{$documentRoot}">
                        </Directory>
                    </VirtualHost>
                EOL;
            $dirArr = explode('\\',$documentRoot);
            $vertualHostPath = $dirArr[0].DIRECTORY_SEPARATOR.$dirArr[1].DIRECTORY_SEPARATOR;//.$dirArr[2].DIRECTORY_SEPARATOR;

            exec($vertualHostPath . 'apache/bin/httpd.exe -k restart');
            // dd($dirArr);

            // Path to Apache's httpd-vhosts.conf file
            $vhostsFilePath = $vertualHostPath .'apache/conf/extra/httpd-vhosts.conf'; // 'C:/xampp8.2/apache/conf/extra/httpd-vhosts.conf';

            // Add the virtual host configuration to httpd-vhosts.conf
            file_put_contents($vhostsFilePath, $virtualHostConfig, FILE_APPEND);

            // Update the system's hosts file
            $hostsFilePath = 'C:\Windows\System32\drivers\etc\hosts';
            $hostsEntry = "\n127.0.0.1\t{$subdomain}\n";
            file_put_contents($hostsFilePath, $hostsEntry, FILE_APPEND);

            // Restart Apache to apply changes
            exec($vertualHostPath . 'apache/bin/httpd.exe -k restart');
        }else{
            // echo  "live";
        }
       return;
    }

}

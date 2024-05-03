<?php

namespace App\Helpers;

use App\Models\CampaignModel;
use App\Models\Channels;
use App\Models\Community;
use App\Models\CompanyModel;
use App\Models\CompanyPackage;
use App\Models\SettingModel;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


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

    public static function getdomain()
    {
        $host = request()->getHttpHost();
        $domain = explode('.', $host);
        $domainName = $domain['0'] ? $domain['0'] : null;
        return $domainName;
    }
    public static function mainDomain()
    {
        $url = request()->getHttpHost();

        // Parse the URL
        $parsedUrl = $url;
        // Check if 'host' key exists in the parsed URL array
        if (isset($parsedUrl)) {
            // Extract the host/domain
            $host = $parsedUrl; // Your host/domain

            // If the host is an IP address, return it directly
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                return $host;
            }

            // If the host is in the format "subdomain.domain.tld",
            // return the last two parts as the main domain
            $parts = explode('.', $host);
            if (count($parts) >= 2) {
                return $parts[count($parts) - 2] . '.' . $parts[count($parts) - 1];
            }


            // If the host doesn't match any of the above conditions,
            // return it as is (assuming it's already the main domain)
            return $host;
        }

        // Return a default value or handle the error as needed
        return 'Unknown';
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
        $checkPackage = CompanyPackage::where('company_id', $companyId)->orderBy('id', 'desc')->exists();
        return $checkPackage;
    }
    public static function isActivePackageAccess()
    {
        $companyId = Helper::getCompanyId();
        $checkPackage = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->orderBy('id', 'desc')->exists();
        return $checkPackage;
    }

    public static function getCompanyId()
    {
        $getdomain = Helper::getdomain();
        Log::info('Domain : ' . $getdomain);
        if (!empty($getdomain) && $getdomain != config('app.pr_name')) { //Company Domain logic
            $CompanyModel = new CompanyModel();
            $exitDomain = $CompanyModel->checkDmain($getdomain);
            $companyId = $exitDomain ? $exitDomain->user_id : false;
        } else {
            Log::info('Is Any Company/Admin Login? : ' . json_encode(auth()->user()));
            if (!empty(auth()->user())) {
                if (auth()->user()->user_type == '2') { // Company
                    $companyId = Auth::user()->id;
                } elseif (auth()->user()->user_type == '3' || auth()->user()->user_type == '4') { //User and Staff
                    $companyId = Auth::user()->company_id;
                } else {  // admin
                    $companyId = null;
                }
            } else {
                $companyId = null;
            }
        }
        Log::info('Company ID : ' . $companyId);
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
        if (Auth::user() != null) {
            $companyId = Helper::getCompanyId();
            // and then you can get query log
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
        } else {
            $companyId = Helper::getCompanyId();
            // $companyId = User::where('user_type', '2')->where('status', '1')->orderBy('id', 'desc')->first();
            // and then you can get query log
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
        }

        return $packageData;
    }
    // get Last Package Data
    public static function GetLastPackageData()
    {
        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        if (Auth::user() != null) {
            $companyId = Helper::getCompanyId();
            // and then you can get query log
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->orderBy('id', 'desc')->first();
        } else {
            $companyId = User::where('user_type', '2')->where('status', '1')->orderBy('id', 'desc')->first();
            // and then you can get query log
            $packageData = CompanyPackage::where('company_id', $companyId->id)->where('status', CompanyPackage::STATUS['ACTIVE'])->orderBy('id', 'desc')->first();
        }

        return $packageData;
    }
    // Change Date format
    public static function Dateformat($date)
    {
        if (gettype($date) == 'string') {
            $date = Carbon::parse($date);
        }
        $formattedDate = $date->format('Y-M-d');

        return $formattedDate;
    }

    // get Remaining Days
    public static function getRemainingDays()
    {
        $sevenDaysLater = now()->addDays(6)->toDateString();
        $companyId = Helper::getCompanyId();

        $packageData = CompanyPackage::where('company_id', $companyId)
            ->where('status', CompanyPackage::STATUS['ACTIVE'])
            ->where('end_date', '>=', now()->toDateString())
            ->where('end_date', '<=', $sevenDaysLater)
            ->first();

        $changeStatusStart = CompanyPackage::where('company_id', $companyId)
            //  ->where('id', '!=',  $packageData->id)
            ->where('start_date', '>',  now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->first();

        $changeStatusEnd = CompanyPackage::where('company_id', $companyId)
            ->where('status', CompanyPackage::STATUS['ACTIVE'])
            ->where('end_date', '<',  now()->toDateString())
            ->orderBy('end_date', 'desc')
            ->first();

        $remainingDays = null;

        if ($packageData != null) {

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
            if (empty($changeStatusStart)) {

                $remainingDays = "Your package going to be expires in " . $days . " " . $hours;

                if ($packageData->end_date  ==  now()->toDateString()) {
                    $remainingDays  =   "Today " . $remainingDays;
                }
            }
        } else {

            if (!empty($changeStatusEnd)) {
                $changeStatusEnd->status = '0';
                $changeStatusEnd->save();
            }

            if (!empty($changeStatusStart)) {
                $changeStatusStart->status = '1';
                $changeStatusStart->save();
            }
        }

        return $remainingDays;
    }
    public static function oldgetRemainingDays()
    {
        try {
            $packageExpiringIN = 7;
            $Date = Carbon::now()->addDays($packageExpiringIN);
            $currentDate = $Date->format('Y-m-d');
            $companyId = Helper::getCompanyId();
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('end_date', '<=', $currentDate)->first();
            if ($packageData != null && new DateTime($packageData->end_date) >= Carbon::now()) {
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
    public static function createCompanySubDomain($subdomain)
    {
        return;
        try {
            $domain = $_SERVER['SERVER_NAME'];
            $subdomain = $subdomain . '.' . $_SERVER['SERVER_NAME'];
            $whitelist = array(
                '127.0.0.1',
                '::1'
            );
            if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $documentRoot = $_SERVER["DOCUMENT_ROOT"];
                $documentRoot = str_replace('/', '\\', $documentRoot);

                $virtualHostConfig = <<<EOL
                    \n<VirtualHost *:80>
                            DocumentRoot "{$documentRoot}"
                            ServerName {$subdomain}
                            <Directory "{$documentRoot}">
                            </Directory>
                        </VirtualHost>
                    EOL;
                $dirArr = explode('\\', $documentRoot);
                $vertualHostPath = $dirArr[0] . DIRECTORY_SEPARATOR . $dirArr[1] . DIRECTORY_SEPARATOR; //.$dirArr[2].DIRECTORY_SEPARATOR;

                exec($vertualHostPath . 'apache/bin/httpd.exe -k restart');

                // Path to Apache's httpd-vhosts.conf file
                $vhostsFilePath = $vertualHostPath . 'apache/conf/extra/httpd-vhosts.conf'; // 'C:/xampp8.2/apache/conf/extra/httpd-vhosts.conf';

                // Add the virtual host configuration to httpd-vhosts.conf
                file_put_contents($vhostsFilePath, $virtualHostConfig, FILE_APPEND);

                // Update the system's hosts file
                $hostsFilePath = 'C:\Windows\System32\drivers\etc\hosts';
                $hostsEntry = "\n127.0.0.1\t{$subdomain}\n";
                file_put_contents($hostsFilePath, $hostsEntry, FILE_APPEND);

                // Restart Apache to apply changes
                exec($vertualHostPath . 'apache/bin/httpd.exe -k restart');
            } else {
                $cpanelUsername = 'rkinfosolution';
                $cpanelPassword = 'Tell@5050';
                $cpanelDomain = $domain;

                // Subdomain details
                $subdomainName = 'newsubdomain';
                $subdomainDocumentRoot = '/public_html/' . $subdomain; // Adjust the path as needed

                // Build the API URL
                $apiUrl = "https://{$cpanelUsername}:{$cpanelPassword}@{$cpanelDomain}:2083/cpsess_randomstring/execute/API2";

                // API request data
                $data = array(
                    'cpanel_jsonapi_user' => $cpanelUsername,
                    'cpanel_jsonapi_apiversion' => 2,
                    'cpanel_jsonapi_module' => 'SubDomain',
                    'cpanel_jsonapi_func' => 'addsubdomain',
                    'subdomain' => $subdomainName,
                    'dir' => $subdomainDocumentRoot,
                    'domain' => $cpanelDomain,
                );

                // Make the API request
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                $response = curl_exec($ch);
                curl_close($ch);

                // Decode the JSON response
                $responseData = json_decode($response, true);

                // Check if the subdomain was created successfully
                if ($responseData['cpanelresult']['error'] == null) {
                    Log::error("Subdomain '{$subdomainName}' created successfully!");
                } else {
                    Log::error("Error creating subdomain: {" . $responseData['cpanelresult']['error'] . "}");
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Error creating subdomain: " . $th->getMessage());
        }
        return;
    }

    // get Remaining Days
    public static function FreePackagePurchased()
    {
        $companyId = Helper::getCompanyId();
        $packageData = CompanyPackage::where('company_id', $companyId)->first();
        return $packageData;
    }
    public static function stripeKey()
    {
        $admin = User::where('user_type', '1')->first();; // Fetching the first mail configuration
        $mailConfig = SettingModel::where('user_id', $admin->id)
            ->select('stripe_key', 'stripe_secret')
            ->first();
        return $mailConfig;
    }
    public static function smsMessage()
    {
        $admin = User::where('user_type', '1')->first();; // Fetching the first mail configuration
        $mailConfig = SettingModel::where('user_id', $admin->id)
            ->select('stripe_key', 'stripe_secret')
            ->first();
        return $mailConfig;
    }
    public  static function getChannels()
    {
        $companyId = Helper::getCompanyId();
        $channels = Channels::where('company_id', $companyId)->get();

        return $channels;
    }
}
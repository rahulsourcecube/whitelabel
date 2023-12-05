<?php

namespace App\Helpers;


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
   
}
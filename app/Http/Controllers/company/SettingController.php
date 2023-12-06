<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
   function index(){
      // $setting=User::where()->where('role','2')->first();
        return view('company.setting.setting');
   }
}

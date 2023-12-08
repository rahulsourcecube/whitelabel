<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    function index(){
          return view('company.package.list');
     }


     function billing(){
        return view('company.billing.list');
   }
}

<?php

namespace App\Http\Controllers\Fornt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function success()
    {

        return view('front.error.thankyou');
    }
    public function error()
    {
        return view('front.error.error');
    }
}

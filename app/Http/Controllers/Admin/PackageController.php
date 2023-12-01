<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    //
    function index()  {

        return view('admin.package.list');
       }
       function create() {
           return view('admin.package.create');
       }
       function view() {
           return view('admin.package.view');
       }

}

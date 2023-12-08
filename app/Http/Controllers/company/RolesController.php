<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SebastianBergmann\CodeUnit\FunctionUnit;

class RolesController extends Controller
{
    function rolelist()
    {
        return view('company.roles.rolelist');
    }
    function rolecreate()
    {
        return view('company.roles.rolecreate');
    }
    function roleview()
    {
        return view('company.roles.roleview');
    }
    
}

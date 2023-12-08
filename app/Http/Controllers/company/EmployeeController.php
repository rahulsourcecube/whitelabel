<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    function index()
    {
        return view('company.employee.list');
    }
    function create()
    {
        return view('company.employee.create');
    }
    function roleview()
    {
        return view('company.roles.roleview');
    }
}

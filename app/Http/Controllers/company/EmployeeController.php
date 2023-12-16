<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    function index(Request $request)
    {

            return view('company.employee.list');

    }


    function create()
    {
        return view('company.employee.create');
    }
    function store(Request $request)
    {

        try {
            $companyId = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cpassword' => 'required|string|min:8',
            ]);
            if ($validator->fails()) {
               return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = new User();
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->email = $request->email;
            $user->password = hash::make($request->password);
            $user->view_password = $request->password;
            $user->user_type = User::USER_TYPE['STAFF'];
            $user->company_id = $companyId;
            $user->save();
            return redirect()->route('company.employee.list')->with('success', 'Employee added successfuly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return view('company.employee.create');
    }
    function roleview()
    {
        return view('company.roles.roleview');
    }
}

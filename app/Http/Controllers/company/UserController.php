<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {
        } else {
            return view('company.user.list');
        }
    }

    public function dtList(Request $request)
    {
        $columns = ['id', 'title'];
        $totalData = User::where('user_type', '2')->count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = User::orderBy($columns[$order], $dir)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {
            $profileImgUrl = "";
            if(!empty($result->profile_image) && file_exists('uploads/company/user-profile/' . $result->profile_image)){
                $profileImgUrl = asset('uploads/company/user-profile/' . $result->profile_image);
            }
            $list[] = [
                base64_encode($result->id),
                $result->full_name ?? "-",
                $result->email ?? "-",
                $result->contact_number ?? "-",
                $profileImgUrl,
                $result->user_status,
            ];
        }
        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $list
        ]);
    }

    function create()
    {
        return view('company.user.create');
    }

    function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'number' => 'required|numeric|digits:10',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = new User();
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move('uploads/company/user-profile', $image);
                $user->profile_image = $image;
            } else {
                $user->profile_image = null;
            }
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->contact_number = $request->number;
            $user->email = $request->email;
            $user->password = hash::make($request->password);
            $user->view_password = $request->password;
            $user->user_type = '2';
            $user->save();
            return redirect()->route('company.user.list')->with('success', 'User added successfuly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function View($id)
    { 
        $user_id = base64_decode($id);
        $user = User::where('id', $user_id)->first();
        return view('company.user.view',compact('user'));
    }

    function edit($id)
    {
        dd(123);
        $user_id = base64_decode($id);
        $user=[];
        $user = User::where('id', $user_id)->first();
        return view('company.user.edit',compact('user'));
    }
}

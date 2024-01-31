<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // check user permission
        $this->middleware('permission:user-list', ['only' => ['index', 'view']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }


    function index(Request $request)
    {
        if ($request->ajax()) {
        } else {
            return view('company.user.list');
        }
    }

    public function dtList(Request $request)
    {
        $companyId = Helper::getCompanyId();
        $columns = ['id', 'first_name',  'email', 'contact_number', 'profile_image','status', 'full_name'];
        $totalData = User::where('user_type', User::USER_TYPE['USER'])
            ->where('company_id', $companyId)->count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $query = User::orderBy($columns[$order], $dir)
        ->where('user_type', User::USER_TYPE['USER'])
        ->where('company_id', $companyId);

        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    if ($column == 'full_name') {
                        $query->orWhere(DB::raw('concat(first_name, " ", last_name)'), 'like', "%{$search}%");
                    } else {
                        $query->orWhere("$column", 'like', "%{$search}%");
                    }
                    // $query->orWhere("$column", 'like', "%{$search}%");
                }
            });
        }

        $results = $query->skip($start)
        ->take($length)
        ->get();
        foreach ($results as $result) {
            $profileImgUrl = "";
            if (!empty($result->profile_image) && file_exists(base_path().'/uploads/company/user-profile/' . $result->profile_image)) {
                $profileImgUrl = asset(base_path().'/uploads/company/user-profile/' . $result->profile_image);
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
           "recordsFiltered" => $totalData,
            "data" => $list
        ]);
    }

    function create()
    {
        return view('company.user.create');
    }
    function checkEmail(Request $request)
    {
        $companyId = Helper::getCompanyId();
        $useremail = User::where('company_id', $companyId)->where('email', $request->email);
        if(!empty($request->id)){
            $useremail->where('id','!=', $request->id);
        }
        $exist = $useremail->first();
        if (!empty($exist)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    function checkContactNumber(Request $request)
    {

        $companyId = Helper::getCompanyId();

        if(!empty($request->id)){
            $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->where('id','!=', $request->id)->first();;
        }else{
            $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->first();
        }
        if (!empty($usernumber)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    function store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'number' => 'required|numeric|digits:10',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }


            $ActivePackageData = Helper::GetActivePackageData();
            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['USER'])->count();
            if($userCount >= $ActivePackageData->no_of_user){
                return redirect()->back()->with('error', 'You can create only '. $ActivePackageData->no_of_user.' users');
            }

            $useremail =User::where('company_id',$companyId)->where('email',$request->email)->where('company_id', $companyId)->first();

            if(!empty($useremail)){
                return redirect()->back()->withErrors($validator)->with('error', 'User email id already exit.')->withInput();
            }
            $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->where('company_id', $companyId)->first();
            if (!empty($usernumber)) {
                return redirect()->back()->withErrors($validator)->with('error', 'User Mobile Number already exit.')->withInput();
            }
            $user = new User();
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path().'/uploads/company/user-profile', $image);
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
            $user->user_type = User::USER_TYPE['USER'];
            $user->company_id = $companyId;
            $user->status = !empty($request->status) ? '1' : '0';
            $user->facebook_link = $request->facebook_link;
            $user->instagram_link = $request->instagram_link;
            $user->twitter_link = $request->twitter_link;
            $user->referral_code = Str::random(6);
            $user->youtube_link = $request->youtube_link;
            $user->bank_name = $request->bank_name;
            $user->ac_holder = $request->ac_holder;
            $user->ifsc_code = $request->ifsc_code;
            $user->paypal_id = $request->paypal_id;
            $user->stripe_id = $request->stripe_id;
            $user->ac_no = $request->ac_no;
            $user->package_id = $ActivePackageData->id;
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
        if (empty($user)) {
            return redirect()->back()->with('error','User not found');
        }
        return view('company.user.view', compact('user'));
    }

    function edit($id)
    {
        $user_id = base64_decode($id);
        $user = User::where('id', $user_id)->first();
        if (empty($user)) {
            return redirect()->back()->with('error','User not found');
        }
        return view('company.user.edit', compact('user'));
    }

    public function update($id, Request $request)
    {
        try {
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->first();

            if (empty($user)) {

                return redirect()->back()->with('error', 'Something went wrong');
            }

            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'number' => 'required|numeric|digits:10|unique:users,contact_number,' . $user->id,
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->hasFile('image')) {
                $oldImage = $user->profile_image;
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path().'/uploads/company/user-profile', $image);
                $user['profile_image'] = $image;
                if (!empty($oldImage)) {
                    $oldImagePath = base_path().'/uploads/company/user-profile/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->contact_number = $request->number;
            $user->email = $request->email;
            $user->password = !empty($request->password) ? hash::make($request->password) : hash::make($user->view_password);
            $user->view_password = !empty($request->password) ? $request->password : $user->view_password;
            $user->status = !empty($request->status) ? '1' : '0';
            $user->facebook_link = $request->facebook_link;
            $user->instagram_link = $request->instagram_link;
            $user->twitter_link = $request->twitter_link;
            $user->youtube_link = $request->youtube_link;
            $user->bank_name = $request->bank_name;
            $user->ac_holder = $request->ac_holder;
            $user->ifsc_code = $request->ifsc_code;
            $user->paypal_id = $request->paypal_id;
            $user->stripe_id = $request->stripe_id;
            $user->ac_no = $request->ac_no;
            $user->save();
            return redirect()->route('company.user.list')->with('success', 'User updated successfully');
        } catch (Exception $e) {
            Log::error('Company user update error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        try {
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->first();
            if (!empty($user->profile_image)) {
                $oldImagePath = base_path().'/uploads/company/user-profile/' . $user->profile_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $user = User::where('id', $user_id)->delete();
            return response()->json(['success' => 'error', 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            Log::error('Company user delete error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}

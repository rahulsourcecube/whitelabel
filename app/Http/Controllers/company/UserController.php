<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Imports\UsersImport;
use App\Models\TaskProgression;
use App\Models\taskProgressionUserHistory;
use App\Models\User;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\TextUI\Help;
use SplFileObject;

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
            $companyId = Helper::getCompanyId();

            $totalUsers = User::where('company_id', $companyId)->where('user_type', User::USER_TYPE['USER'])->get();

            return view('company.user.list', compact('totalUsers'));
        }
    }

    public function dtList(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id', 'first_name',  'email', 'contact_number', 'profile_image', 'status', 'full_name'];
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
                    }
                });
            }

            $results = $query->skip($start)
                ->take($length)
                ->get();
            foreach ($results as $result) {
                $profileImgUrl = "";
                if (!empty($result->profile_image) && file_exists(base_path() . '/uploads/company/user-profile/' . $result->profile_image)) {
                    $profileImgUrl = asset(base_path() . '/uploads/company/user-profile/' . $result->profile_image);
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
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('UserController::dtList ' . $e->getMessage());
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
    }

    function create()
    {
        // dd('dfgdg');
        //Check  ActivePackageAccess

        $country_data = CountryModel::all();
        $isActivePackageAccess = Helper::isActivePackageAccess();

        if (!$isActivePackageAccess) {
            return redirect()->back()->with('error', 'Your package expired. Please buy the package.');
        }

        return view('company.user.create', compact('country_data'));
    }

    public function phoneCode(Request $request)
    {
        $country_id = $request->input('country_id');

        $country = CountryModel::where('id', $country_id)->first();


        $code = '';
        $code = !empty($country) && !empty($country->phonecode) ?  $country->phonecode : "";

        // Return the options as JSON response
        return response()->json($code);
    }

    public function get_states(Request $request)
    {
        $country_id = $request->input('country_id');

        $states = StateModel::where('country_id', $country_id)->get();

        $options = '';
        $options .= "<option value=''>Select state</option>";
        foreach ($states as $state) {
            $options .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        // Return the options as JSON response
        return response()->json($options);
    }


    public function get_city(Request $request)
    {
        $state_id = $request->input('state_id');

        $citys = CityModel::where('state_id', $state_id)->get();
        $options = '';
        $options .= "<option value=''>Select City</option>";
        foreach ($citys as $city) {
            $options .= "<option value='" . $city->id . "'>" . $city->name . "</option>";
        }
        return response()->json($options);
    }

    function checkEmail(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();

            $useremail = User::where('company_id', $companyId)->where('email', $request->email)->where('user_type', 4);
            if (!empty($request->id)) {
                $useremail->where('id', '!=', $request->id);
            }
            $exist = $useremail->first();
            if (!empty($exist)) {
                echo 'false';
            } else {
                echo 'true';
            }
        } catch (Exception $e) {
            Log::error('UserController::checkEmail ' . $e->getMessage());
            echo 'true';
        }
    }

    function checkContactNumber(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();

            if (!empty($request->id)) {
                $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->where('id', '!=', $request->id)->first();;
            } else {
                $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->where('user_type', 4)->first();
            }
            if (!empty($usernumber)) {
                echo 'false';
            } else {
                echo 'true';
            }
        } catch (Exception $e) {
            Log::error('UserController::checkContactNumber ' . $e->getMessage());
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
                // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'number' => 'required|numeric|digits:10',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $useremail = User::where('company_id', $companyId)->where('email', $request->email)->where('user_type', 4)->first();
            if (!empty($request->id)) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $ActivePackageData = Helper::GetActivePackageData();
            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['USER'])->count();
            if ($userCount >= $ActivePackageData->no_of_user) {
                return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_user . ' users');
            }


            $usernumber = User::where('company_id', $companyId)->where('contact_number', $request->number)->where('user_type', 4)->first();
            if (!empty($usernumber)) {
                return redirect()->back()->withErrors($validator)->with('error', 'User Mobile Number already exit.')->withInput();
            }

            $user = new User();
            // dd($user);
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path() . '/uploads/company/user-profile', $image);
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
            $user->country_id = $request->country;
            $user->state_id = $request->state;
            $user->city_id = $request->city;
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
            Log::error('UserController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    function View($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->where('company_id', $companyId)->first();
            if (empty($user)) {
                return redirect()->back()->with('error', 'User not found');
            }
            $progressions = taskProgressionUserHistory::with('taskProgressionHistory')
                ->where('user_id', $user_id)
                ->where('company_id', $companyId)
                ->orderBy('id', 'desc')
                ->get();
            return view('company.user.view', compact('user', 'progressions'));
        } catch (\Exception $e) {
            Log::error('UserController::View ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    function edit($id)
    {
        try {
            $isActivePackageAccess = Helper::isActivePackageAccess();
            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }
            $user_id = base64_decode($id);
            $companyId = Helper::getCompanyId();



            $user = User::where('id', $user_id)->where('company_id', $companyId)->first();
            $country_data = CountryModel::all();
            $state_data = StateModel::where('country_id', $user->country_id)->get();
            $city_data = CityModel::where('state_id', $user->state_id)->get();
            if (empty($user)) {
                return redirect()->back()->with('error', 'User not found');
            }
            return view('company.user.edit', compact('user', 'country_data', 'state_data', 'city_data'));
        } catch (\Exception $e) {
            Log::error('UserController::edit ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->where('company_id', $companyId)->first();

            if (empty($user)) {

                return redirect()->back()->with('error', 'Something went wrong');
            }


            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'number' => 'required|numeric|digits:10',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $usernumber = User::where('id', '!=', $user->id)
                ->where('company_id', $companyId)
                ->where('contact_number', $request->number)
                ->where('user_type', 4)
                ->first();
            if (!empty($usernumber)) {
                return redirect()->back()->withErrors($validator)->with('error', 'User Mobile Number already exit.')->withInput();
            }

            if ($request->hasFile('image')) {
                $oldImage = $user->profile_image;
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path() . '/uploads/company/user-profile', $image);
                $user['profile_image'] = $image;
                if (!empty($oldImage)) {
                    $oldImagePath = base_path() . '/uploads/company/user-profile/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->contact_number = $request->number;
            // $user->email = $request->email;
            $user->password = !empty($request->password) ? hash::make($request->password) : hash::make($user->view_password);
            $user->country_id = $request->country;
            $user->state_id = $request->state;
            $user->city_id = $request->city;
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
            Log::error('UserController::update ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->first();
            if (!empty($user->profile_image)) {
                $oldImagePath = base_path() . '/uploads/company/user-profile/' . $user->profile_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $user = User::where('id', $user_id)->delete();
            return response()->json(['success' => 'success', 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            Log::error('UserController::delete ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
    public function export()
    {
        try {
            $date = Carbon::now()->toDateString();
            return Excel::download(new UsersExport($date), ('user' . '_' . $date . '.xlsx'));
        } catch (Exception $e) {
            Log::error('UserController::Export => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();
            $request->validate([
                'import_file' => 'required|file|mimes:xlsx,csv',
            ]);
            $ActivePackageData = Helper::GetActivePackageData();
            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['USER'])->count();

            if ($userCount >= $ActivePackageData->no_of_user) {

                return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_user . ' users');
            }

            if ($request->hasFile('import_file')) {
                $file = $request->file('import_file');


                (Excel::import(new UserImport(), $file));

                return redirect()->back()->with('success', 'File uploaded successfully.');
            } else {
                // No file uploaded
                return redirect()->back()->with('error', 'No file uploaded.');
            }
        } catch (Exception $e) {
            Log::error('UserController::Import => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ModelHasRoles;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // check user permission
        $this->middleware('permission:employee-management-list', ['only' => ['index']]);
        $this->middleware('permission:employee-management-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:employee-management-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:employee-management-delete', ['only' => ['delete']]);
    }


    function index(Request $request)
    {

        if ($request->ajax()) {
        } else {return view('company.employee.list');
        }
    }
    public function elist(Request $request)
    {
        $companyId = Helper::getCompanyId();
        $columns = ['id'];
        $totalData = User::where('user_type', User::USER_TYPE['STAFF'])
            ->where('company_id', Auth::user()->id)->count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $searchColumn = ['first_name', 'last_name','email'];
        $query = User::orderBy($columns[0], $dir)
            ->where('user_type', User::USER_TYPE['STAFF'])
            ->where('company_id', $companyId);

        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $searchColumn) {
                foreach ($searchColumn as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $results = $query->skip($start)
        ->take($length)
        ->get();

        foreach ($results as $result) {

            $list[] = [
                base64_encode($result->id),
                $result->full_name ?? "-",
                $result->email ?? "-",
                $result->roles->pluck('name', 'name')->first() ?? "-",
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
        $roles = Role::pluck('name', 'name')->all();
        return view('company.employee.create', compact('roles'));
    }
    function store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cpassword' => 'required|string|min:8',
            ]);
            $ActivePackageData = Helper::GetActivePackageData();
            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['STAFF'])->count();
            if ($userCount >= $ActivePackageData->no_of_employee ) {
                return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_employee . ' employees');
            }
            $useremail =User::where('company_id',$companyId)->where('email',$request->email)->first();

            if(!empty($useremail)){
                return redirect()->back()->withErrors($validator)->with('error', 'User email id already exit.')->withInput();
            }
            $user = new User();
            $user->first_name = $request->fname;
            $user->last_name = $request->lname;
            $user->email = $request->email;
            $user->password = hash::make($request->password);
            $user->view_password = $request->password;
            $user->user_type = User::USER_TYPE['STAFF'];
            $user->company_id = $companyId;
            $user->package_id = $ActivePackageData->id;
            $user->save();
            $user->assignRole($request->input('role'));
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

    function edit($id)
    {
        $user_id = base64_decode($id);
        $user = User::where('id', $user_id)->first();
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->first();
        if (empty($user)) {
            return redirect()->back('User not found');
        }
        return view('company.employee.edit', compact('user', 'roles', 'userRole'));
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
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $userDetails = [
                'first_name' => $request->fname,
                'last_name' => $request->lname,
                'email' => $request->email,
            ];

            $user->update($userDetails);
            $roles = ModelHasRoles::where("model_id", $user->id)->delete();
            $user->assignRole($request->input('role'));
            return redirect()->route('company.employee.list')->with('success', 'User updated successfully');
        } catch (Exception $e) {
            Log::error('Company user update error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function delete($id)
    {
        try {
            $user_id = base64_decode($id);
            $user=User::where('id', $user_id)->delete();
            return response()->json(['success' => 'error', 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            Log::error('Company user delete error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}

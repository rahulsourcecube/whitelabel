<?php
namespace App\Http\Controllers\Company;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ModelHasRoles;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        try {
            if ($request->ajax()) {
            } else {
                return view('company.employee.list');
            }
        } catch (Exception $e) {
            Log::error('EmployeeController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function elist(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id'];
            $totalData = User::where('user_type', User::USER_TYPE['STAFF'])
            ->where('company_id', Auth::user()->id)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['first_name', 'last_name', 'email', 'full_name'];
            $query = User::orderBy($columns[0], $dir)
                ->where('user_type', User::USER_TYPE['STAFF'])
                ->where('company_id', $companyId);

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
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

                $list[] = [
                    base64_encode($result->id),
                    $result->full_name ?? "-",
                    $result->email ?? "-",
                    $result->roles->pluck('role_name', 'role_name')->first() ?? "-",
                ];
            }
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('EmployeeController::Elist  => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }

    function create()
    {
        try {
            $isActivePackageAccess= Helper::isActivePackageAccess();

            if(!$isActivePackageAccess){
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();   
            }
            $companyId = Helper::getCompanyId();

            $roles = Role::where('company_id', $companyId)->get();

            return view('company.employee.create', compact('roles'));
        } catch (Exception $e) {
            Log::error('EmployeeController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                // 'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cpassword' => 'required|string|min:8',
            ]);
            $ActivePackageData = Helper::GetActivePackageData();
            $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['STAFF'])->count();
            if ($userCount >= $ActivePackageData->no_of_employee) {
                return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_employee . ' employees');
            }
            $useremails = User::where('company_id', $companyId)->where('email', $request->email)->where('user_type' , '3')->first();           
            $useremail = User::where('id', $companyId)->where('email', $request->email)->where('user_type' , '2')->first();           

            if (!empty($useremail) || !empty($useremails) ) {
                return redirect()->back()->with('error', 'Employee email id already exit.')->withInput();
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
        } catch (Exception $e) {
            Log::error('EmployeeController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return view('company.employee.create');
    }
    function roleview()
    {
        return view('company.roles.roleview');
    }

    function edit($id)
    {
        try {
            $isActivePackageAccess= Helper::isActivePackageAccess();

            if(!$isActivePackageAccess){
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();   
            }
            
            $companyId = Helper::getCompanyId();
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->where('company_id', $companyId)->first();         
            $roles = Role::where('company_id', $companyId)->get();
            $userRole = $user->roles->pluck('name', 'name')->first();
          
            if (empty($user)) {
                return redirect()->back()->with('error', 'User not found');
            }
            return view('company.employee.edit', compact('user', 'roles', 'userRole'));
        } catch (Exception $e) {
            Log::error('EmployeeController::Edit => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
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
                // 'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $useremails = User::where('company_id', $companyId)->where('id', '!=', $user_id)->where('email', $request->email)->where('user_type' , '3')->first();           
            $useremail = User::where('id', $companyId)->where('email', $request->email)->where('user_type' , '2')->first();           

            if (!empty($useremail) || !empty($useremails) ) {
                return redirect()->back()->with('error', 'Employee email id already exit.')->withInput();
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
            Log::error('EmployeeController::Update => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $user_id = base64_decode($id);
            $user = User::where('id', $user_id)->where('company_id', $companyId)->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            Log::error('EmployeeController::Delete  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => "Error: "  . $e->getMessage()]);
        }
    }

}

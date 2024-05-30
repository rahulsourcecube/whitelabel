<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Module;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeUnit\FunctionUnit;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // check user permission
        $this->middleware('permission:role-list', ['only' => ['rolelist', 'roleview']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    // this function yous for role list
    function rolelist()
    {
        try {
            $companyId = Helper::getCompanyId();

            $roles = Role::where('name', '!=', 'Company')->where('company_id', $companyId)->orderBy('id', 'DESC')->get();
            return view('company.roles.rolelist', compact('roles'));
        } catch (Exception $e) {
            Log::error('RolesController::Rolelist => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    // this function yous for role create
    function rolecreate()
    {
        try {
            $ModelPermission = Module::all();
            $permission = Permission::get();
            return view('company.roles.rolecreate', compact('permission', 'ModelPermission'));
        } catch (Exception $e) {
            Log::error('RolesController::Rolecreate => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'permission' => 'required',
            ]);
            $companyId = Helper::getCompanyId();
            $role = Role::where('name', $request->input('name'))->where('company_id', $companyId)->first();
            if (!empty($role)) {
                return redirect()->back()->with('error', $request->input('name') . ' Role already Exit!!')->withInput();
            }
            $role = Role::create(['role_name' => $request->input('name'), 'name' => $request->input('name') . $companyId, 'company_id' => $companyId]);

            $role->syncPermissions(array_map('intval', $request->input('permission')));
            return redirect()->route('company.role.rolelist')
                ->with('success', 'Role created successfully');
        } catch (Exception $e) {
            Log::error('RolesController::Store => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    // this function yous for role edit
    public function edit($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $role = Role::where('id', $id)->where('company_id', $companyId)->first();
            if (empty($role)) {
                return redirect()->back()->with('error', 'Role not found!');
            }
            $permission = Permission::get();
            $ModelPermission = Module::all();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();

            return view('company.roles.edit', compact('role', 'permission', 'rolePermissions', 'ModelPermission'));
        } catch (Exception $e) {
            Log::error('RolesController::Edit => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    // this function yous for role update
    public function update(Request $request, $id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $this->validate($request, [
                'permission' => 'required',
            ]);
            $role = Role::where('id', $id)->where('company_id', $companyId)->first();
            $roles = Role::where('name', $request->input('name'))->where('company_id', $companyId)->first();
            if (!empty($roles)) {
                return redirect()->back()->with('error', $request->input('name') . ' Rloe already Exit!!')->withInput();
            }
            $role->syncPermissions(array_map('intval', $request->input('permission')));
            return redirect()->back()->with('success', 'Role updated successfully');
        } catch (Exception $e) {
            Log::error('RolesController::Update => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    // this function yous for role view
    function roleview($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $role = Role::where('id', $id)->where('company_id', $companyId)->first();
            $companyId = Helper::getCompanyId();
            if (empty($role)) {
                return redirect()->back()->with('error', 'Role not found!');
            }
            $ModelPermission = Module::all();
            $rolePermission = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)
                ->get();
            return view('company.roles.roleview', compact('role', 'rolePermissions', 'ModelPermission', 'rolePermission'));
        } catch (Exception $e) {
            Log::error('RolesController::Roleview => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    // this function yous for role destroy
    public function destroy($id)
    {
        try {
            Role::where('id', $id)->delete();
            return redirect()->route('company.role.rolelist')
                ->with('success', 'Role deleted successfully');
        } catch (Exception $e) {
            Log::error('RolesController::Destroy => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}

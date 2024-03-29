<?php

namespace App\Http\Controllers\Company;

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
        $roles = Role::where('name', '!=', 'Company')->orderBy('id', 'DESC')->get();
        return view('company.roles.rolelist', compact('roles'));
    }
    // this function yous for role create
    function rolecreate()
    {
        try {
            $ModelPermission = Module::all();
            $permission = Permission::get();
            return view('company.roles.rolecreate', compact('permission', 'ModelPermission'));
        } catch (Exception $e) {

            Log::error('list role Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong please try again');
        }
    }
    // this function yous for role store
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ]);
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions(array_map('intval', $request->input('permission')));
            return redirect()->route('company.role.rolelist')
                ->with('success', 'Role created successfully');
        } catch (Exception $e) {

            Log::error('store role Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong please try again');
        }
    }
    // this function yous for role edit
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $ModelPermission = Module::all();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('company.roles.edit', compact('role', 'permission', 'rolePermissions', 'ModelPermission'));
    }
    // this function yous for role update
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'permission' => 'required',
        ]);
        try {

            $role = Role::find($id);

            $role->syncPermissions(array_map('intval', $request->input('permission')));

            return redirect()->back()->with('success', 'Role updated successfully');

        } catch (Exception $e) {

            Log::error('Update role Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong please try again');
        }
    }
    // this function yous for role view
    function roleview($id)
    {
        try {

            $role = Role::find($id);
            $ModelPermission = Module::all();
            $rolePermission = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)
                ->get();
            return view('company.roles.roleview', compact('role', 'rolePermissions', 'ModelPermission', 'rolePermission'));
        } catch (Exception $e) {

            Log::error('roleview role Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong please try again');
        }
    }
    // this function yous for role destroy
    public function destroy($id)
    {
        Role::where('id', $id)->delete();
        return redirect()->route('company.role.rolelist')
            ->with('success', 'Role deleted successfully');
    }
}

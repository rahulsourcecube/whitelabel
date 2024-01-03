<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $Module = Module::create(['module_name' => 'role']);
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }


        $Module = Module::create(['module_name' => 'user']);
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }

        $Module = Module::create(['module_name' => 'task']);
        $permissions = [
            'task-list',
            'task-create',
            'task-edit',
            'task-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }

        $Module = Module::create(['module_name' => 'Employee Management']);
        $permissions = [
            'employee-management-list',
            'employee-management-create',
            'employee-management-edit',
            'employee-management-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }


        $Module = Module::create(['module_name' => 'Billing and Payment']);
        $permissions = [
            'billing-and-payment-list',
            'billing-and-payment-create',
            'billing-and-payment-edit',
            'billing-and-payment-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }

        $Module = Module::create(['module_name' => 'package']);
        $permissions = [
            'package-list',
            'package-create',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }


        $Module = Module::create(['module_name' => 'General Setting']);
        $permissions = [
            'general-setting-list',
            'general-setting-create',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }


        $Module = Module::create(['module_name' => 'task-analytics']);
        $permissions = [
            'task-analytics-list',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }

        $Module = Module::create(['module_name' => 'notification']);
        $permissions = [
            'notification-list',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'modules_id' => $Module->id]);
        }

        $role = Role::create(['name' => 'Staff']);

        $permissions = Permission::pluck('id', 'id')->all();

        $rolePermissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        $role->revokePermissionTo($rolePermissions);

        $role->syncPermissions($permissions);

    }
}

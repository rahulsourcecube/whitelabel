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


        $Module = Module::create(['module_name' => 'setting']);
        $permissions = [
            'setting-list',
            'setting-create',
            'setting-edit',
            'setting-delete',
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

        $Module = Module::create(['module_name' => 'task-analytics']);
        $permissions = [
            'task-analytics-list',
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

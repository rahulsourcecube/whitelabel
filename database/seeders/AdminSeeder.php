<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@mailinator.com',
                'contact_number' => '1234567890',
                'password' => Hash::make('Admin@2023'),
                'view_password' => 'Admin@2023',
                'user_type' => '1',
            ],
            [
                'first_name' => 'Demo',
                'last_name' => 'User',
                'email' => 'user@mailinator.com',
                'contact_number' => '1234567890',
                'user_type' => 'User',
                'password' => Hash::make('User@2023'),
                'view_password' => 'User@2023',
                'user_type' => '4',
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }

        $user = User::create([
            'first_name' => 'Demo',
            'last_name' => 'Company',
            'email' => 'company@mailinator.com',
            'contact_number' => '1234567890',
            'user_type' => 'Company',
            'password' => Hash::make('Company@2023'),
            'view_password' => 'Company@2023',
            'user_type' => '2',
        ]);


        $role = Role::create(['name' => 'Company']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}

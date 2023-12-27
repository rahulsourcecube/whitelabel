<?php

namespace Database\Seeders;

use App\Models\CompanyModel;
use App\Models\PackageModel;
use App\Models\SettingModel;
use App\Models\User;
use Carbon\Carbon;
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
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@mailinator.com',
            'contact_number' => '1234567890',
            'password' => Hash::make('Admin@2023'),
            'view_password' => 'Admin@2023',
            'user_type' => '1',
        ]);

        $setting = SettingModel::create([
            'title' => 'WhiteLabel',
            'email' => 'admin@mailinator.com',
            'contact_number' => '1235435535',
            'description' => '<p>WhiteLabel</p>',
            'facebook_link' => 'https://www.facebook.com',
            'twitter_link' => 'https://www.tweeter.com',
            'linkedin_link' => 'https://www.linkedin.com',
            'user_id' => $admin->id,
        ]);
        $package = PackageModel::create([
            'title' => 'Company',
            'description' => '<p>Company</p>',
            'no_of_campaign' => 1,
            'duration' => 5,
            'price' => 0.00,
            'type' => '1',
            'status' => '1',
            'created_by' => $admin->id,
            'no_of_user' => 1,
            'no_of_employee' => 1,
        ]);



        $company = User::create([
            'first_name' => 'Demo',
            'last_name' => 'Company',
            'email' => 'company@mailinator.com',
            'contact_number' => '1234567890',
            'password' => Hash::make('Company@2023'),
            'view_password' => 'Company@2023',
            'user_type' => '2',
        ]);

        CompanyModel::create([
            'company_name' => 'Demo Company',
            'contact_email' => 'company@mailinator.com',
            'contact_number' => '1234567890',
            'subdomain' => 'demo',
            'user_id' => $company->id,
        ]);

        SettingModel::create([
            'title' => 'WhiteLabel',
            'email' => 'company@mailinator.com',
            'contact_number' => '1235435535',
            'description' => '<p>WhiteLabel</p>',
            'facebook_link' => 'https://www.facebook.com',
            'twitter_link' => 'https://www.tweeter.com',
            'linkedin_link' => 'https://www.linkedin.com',
            'user_id' => $company->id,
        ]);

        $user = User::create([
            'first_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'user@mailinator.com',
            'contact_number' => '1234567890',
            'user_type' => 'User',
            'password' => Hash::make('User@2023'),
            'view_password' => 'User@2023',
            'user_type' => '4',
            'company_id' => $company->id,
        ]);



        $role = Role::create(['name' => 'Company']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $rolePermissions = [
            'role-delete',
        ];

        $role->revokePermissionTo($rolePermissions);

        $user->assignRole([$role->id]);
    }
}

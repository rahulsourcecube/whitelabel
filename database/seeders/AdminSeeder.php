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
            'title' => 'Referdio',
            'email' => 'admin@mailinator.com',
            'contact_number' => '1235435535',
            'description' => '<p>Referdio</p>',
            'facebook_link' => 'https://www.facebook.com',
            'twitter_link' => 'https://www.Twitter.com',
            'linkedin_link' => 'https://www.linkedin.com',
            'user_id' => $admin->id,
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail_username' => 'rahul@sourcecubeindia.com',
            'mail_password' => 'qdkiattyimybxtuc',
            'mail_encryption' => 'ssl',
            'mail_address' => 'rahul@sourcecubeindia.com',
            'stripe_key' => 'pk_test_51Mo53GSF7jse029jEgWM9ZxB9dCsBccGMzSykWfF2QDVI3mg2mhSMO3eBiYoXUiNFycNxLh0rAODKPQbX46WvpVq00g9xdcNPf',
            'stripe_secret' => 'sk_test_51Mo53GSF7jse029jHMjdSJqH60MGgJZTO056vmY690KRkjdA2AtniAV9qJH4zcMaZTuVg8flAjGWVbTsSu7z1qrD00tKIJTDPd',
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



        // $company = User::create([
        //     'first_name' => 'Demo',
        //     'last_name' => 'Company',
        //     'email' => 'company@mailinator.com',
        //     'contact_number' => '1234567890',
        //     'password' => Hash::make('Company@2023'),
        //     'view_password' => 'Company@2023',
        //     'user_type' => '2',
        // ]);

        // CompanyModel::create([
        //     'company_name' => 'Demo Company',
        //     'contact_email' => 'company@mailinator.com',
        //     'contact_number' => '1234567890',
        //     'subdomain' => 'demo',
        //     'user_id' => $company->id,
        // ]);

        // SettingModel::create([
        //     'title' => 'referdio',
        //     'email' => 'company@mailinator.com',
        //     'contact_number' => '1235435535',
        //     'description' => '<p>referdio</p>',
        //     'facebook_link' => 'https://www.facebook.com',
        //     'twitter_link' => 'https://www.Twitter.com',
        //     'linkedin_link' => 'https://www.linkedin.com',
        //     'user_id' => $company->id,
        // ]);

        // $user = User::create([
        //     'first_name' => 'Demo',
        //     'last_name' => 'User',
        //     'email' => 'user@mailinator.com',
        //     'contact_number' => '1234567890',
        //     'user_type' => 'User',
        //     'password' => Hash::make('User@2023'),
        //     'view_password' => 'User@2023',
        //     'user_type' => '4',
        //     'company_id' => $company->id,
        // ]);

        $role = Role::create(['name' => 'Company']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $rolePermissions = [
            'role-delete',
        ];

        $role->revokePermissionTo($rolePermissions);

        // $company->assignRole([$role->id]);
    }
}
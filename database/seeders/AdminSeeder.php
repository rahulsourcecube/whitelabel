<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            
            
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}

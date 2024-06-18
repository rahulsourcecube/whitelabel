<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $rowsCount = count($rows) - 1; // Counting total rows in the collection (excluding header)
        $companyId = Helper::getCompanyId(); // Get company ID from Helper function
        $ActivePackageData = Helper::GetActivePackageData(); // Get active package data from Helper function

        // Count existing users based on company ID, package ID, and user type
        $userCount = User::where('company_id', $companyId)
            ->where('package_id', $ActivePackageData->id)
            ->where('user_type', User::USER_TYPE['USER'])
            ->count();

        // Check if importing exceeds user limit defined in active package
        if ($userCount >= $ActivePackageData->no_of_user || $rowsCount >= $ActivePackageData->no_of_user || $ActivePackageData->no_of_user < ($rowsCount + $userCount)) {
            return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_user . ' Users');
        }

        // Iterate through each row in the collection (skipping the header row)
        foreach ($rows as $key => $row) {
            if ($key == 0) continue; // Skip header row

            // Check if user with the same email or contact number exists
            $existingUser = User::where('company_id', $companyId)
                ->where('package_id', $ActivePackageData->id)
                ->where('user_type', User::USER_TYPE['USER'])
                ->where(function ($query) use ($row) {
                    $query->where('email', $row[2])
                        ->orWhere('contact_number', $row[3]);
                })
                ->first();

            // If no existing user found, create a new user

            if (empty($existingUser)) {
                User::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'contact_number' => $row[3],
                    'password' => Hash::make($row[4]),
                    'user_type' => User::USER_TYPE['USER'],
                    'company_id' => $companyId,
                    'status' => $row[5] == '1' ? "1" : "0",
                    'package_id' => $ActivePackageData->id
                ]);
            }
        }
    }
}

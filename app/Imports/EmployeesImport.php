<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Spatie\Permission\Models\Role;

class EmployeesImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection  $rows)
    {
        // Start check package


        $rowsCount = count($rows) - 1;


        $companyId = Helper::getCompanyId();
        $ActivePackageData = Helper::GetActivePackageData();

        $userCount = User::where('company_id', $companyId)
            ->where('package_id', $ActivePackageData->id)
            ->where('user_type',  User::USER_TYPE['USER'])
            ->count();


        if ($userCount >= $ActivePackageData->no_of_employee || $rowsCount >= $ActivePackageData->no_of_employee || $ActivePackageData->no_of_employee < ($rowsCount + $userCount)) {
            return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_employee . ' employees');
        }
        // @end

        foreach ($rows as $key => $row) {

            if ($key == 0) continue;

            $existingUser = User::where('company_id', $companyId)
                ->where('user_type', User::USER_TYPE['STAFF'])
                ->where('email', $row[2])
                ->orWhere('contact_number', $row[3])
                ->first();

            if ($existingUser) continue;
            if ($row[4]) {
                $role = Role::where('name', $row[4] . $companyId)
                    ->where('company_id', $companyId)
                    ->first();

                if (empty($role)) {
                    $role = Role::create([
                        'role_name' => $row[4],
                        'name' => $row[4] . $companyId,
                        'company_id' => $companyId
                    ]);
                }

                $user =  User::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'password' => Hash::make($row[3]),
                    'user_type' => User::USER_TYPE['STAFF'],
                    'company_id' => $companyId,
                    'package_id' => $ActivePackageData->id,
                ]);

                $user->assignRole($role->id);
            }
        }

        return "Import completed successfully";
    }
}

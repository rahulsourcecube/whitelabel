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

    public function collection(Collection  $rows)
    {
        $rowsCount = count($rows) - 1;

        $companyId = Helper::getCompanyId();
        $ActivePackageData = Helper::GetActivePackageData();
        $userCount = User::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->where('user_type',  User::USER_TYPE['STAFF'])->count();

        if ($userCount >= $ActivePackageData->no_of_user || $rowsCount >= $ActivePackageData->no_of_user || $ActivePackageData->no_of_user < ($rowsCount + $userCount)) {

            return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_user . ' Users');
        }

        foreach ($rows as $key => $row) {
            if ($key == 0) continue;
            $existingUser = User::where('company_id', $companyId)->where('user_type', User::USER_TYPE['USER'])->where('email', $row[2])->orWhere('contact_number', $row[3])->first();

            if ($existingUser) continue;

            User::create(
                [
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'contact_number' => $row[3],
                    'password' => Hash::make($row[4]),
                    'user_type' => User::USER_TYPE['USER'],
                    'company_id' => $companyId,
                    'status' => $row[5] ?? "0",
                    'package_id' => $ActivePackageData->id
                ],

            );
        }
    }
}

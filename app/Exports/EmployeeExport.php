<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $companyId = Helper::getCompanyId();
        $exports = User::where('company_id', $companyId)->where('user_type', User::USER_TYPE['STAFF'])
            ->get();

        $export = $exports->map(function ($export) {
            return [
                "first_name" => $export->first_name ?? "-",
                "last_name" => $export->last_name ?? "-",
                "email" => $export->email ?? "-",
                "role" => $export->roles->pluck('role_name', 'role_name')->first() ?? "-",
            ];
        });


        return $export;
    }
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Contact Number',
            'Role',
        ];
    }
    // Set style for heading
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
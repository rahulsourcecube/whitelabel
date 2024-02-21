<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\CampaignModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Export implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $type;
    public function __construct($type)
    {
        $this->type = $type;
    }
    public function collection()
    {
        $companyId = Helper::getCompanyId();
        $exports = CampaignModel::where('company_id', $companyId)
            ->select('title', 'description', 'reward', 'expiry_date')
            ->where('type', $this->type)
            ->get();
    
        $export = $exports->map(function ($export) {
            return [
                "title" => $export->title,
                "description" => html_entity_decode(strip_tags($export->description)),
                "reward" => $export->text_reward ? $export->text_reward : ($export->reward?:0),
                "expiry_date" => $export->expiry_date,
            ];
        });
       
    
        return $export;
    }
    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Reward',
            'Expiry date',
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

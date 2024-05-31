<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'company_package';
    const STATUS = [
        'ACTIVE' => '1',
        'INACTIVE' => '0',
    ];
    protected $fillable = [
        'payment_id',
        'no_of_employee',
        'no_of_user',
        'status',
    ];

    public function GetPackageData()
    {
        return $this->hasOne(PackageModel::class, 'id', 'package_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPackage extends Model
{
    use HasFactory;
    protected $table = 'company_package';
    const STATUS = [
        'ACTIVE' => '1',
        'INACTIVE' => '0',
    ];
    protected $fillable = [
        'payment_id',
    ];
}

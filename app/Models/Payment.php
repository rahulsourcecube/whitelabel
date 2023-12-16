<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'company_package_id',
        'amount',
        'name_on_card',
        'card_number',
        'card_expiry_month',
        'card_expiry_year',
        'card_cvv',
        'zipcode',
        'status'
    ];
}

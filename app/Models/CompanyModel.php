<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'company';

    /*protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'contact_email',
        'contact_number',
        'company_logo',
        'subdomain',
        'is_indivisual',
        // Add more fields as needed
    ];*/
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
  
}

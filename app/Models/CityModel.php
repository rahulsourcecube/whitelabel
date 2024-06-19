<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CityModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'city';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'state_id',
        'country_id',
        'name',
        'zipcode',
        'created_at',
        'updated_at'
    ];

    public function state()
    {
        return $this->belongsTo(StateModel::class);
    }
}

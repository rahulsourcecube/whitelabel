<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'state';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'country_id',
        'name',
        'created_at',
        'updated_at'
    ];

    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }


    public function city()
    {
        return $this->hasMany(CityModel::class);
    }
}

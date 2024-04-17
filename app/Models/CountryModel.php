<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'country';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'short_name',
        'phonecode',
        'created_at',
        'updated_at'
    ];

    public function state()
    {
        return $this->hasMany(StateModel::class);
    }
}

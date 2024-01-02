<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    function getuser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

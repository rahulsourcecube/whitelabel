<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskEvidence extends Model
{
    use HasFactory;

    function getCompanySetting()
    {
        return $this->hasOne(SettingModel::class, 'id', 'company_id');
    }
    function getuser()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}

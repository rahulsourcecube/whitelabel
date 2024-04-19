<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class taskProgressionUserHistory extends Model
{
    use HasFactory;
    protected $table="task_progression_user_history";
    
    public function taskProgressionHistory(): HasMany
    {
        return $this->hasMany(taskProgression::class,'id','progression_id');
    }
    public function taskProgression()
    {
        return $this->hasOne(taskProgression::class,'id','progression_id');
    }
}

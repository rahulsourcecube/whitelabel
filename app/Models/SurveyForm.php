<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyForm extends Model
{
    const PUBLIC = [
        'YES' => '1',
        'NO' => '0',

    ];
    use SoftDeletes;
    use HasFactory;
    protected $dates = ['deleted_at'];
    protected $table = "survey_form";
}

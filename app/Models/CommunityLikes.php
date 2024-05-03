<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityLikes extends Model
{
    use HasFactory;
    protected  $table = "community_likes";
    protected $fillable = [
        'user_id',
        'company_id',
        'reply_id',
    ];
}

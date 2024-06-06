<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected  $table = "community";
    protected $fillable = [
        'user_id',
        'company_id',
        'channel_id',
        'title',
        'slug',
        'content',
        'image',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(Channels::class, 'channel_id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function reply()
    {
        return $this->hasMany(Reply::class, 'community_id')->where('status', '0');;
    }
}

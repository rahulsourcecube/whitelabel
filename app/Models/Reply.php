<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reply extends Model
{
    const STATUS = [
        'ACTIVE' => '0',
        'INACTIVE' => '1',

    ];
    use HasFactory;
    protected  $table = "replies";
    protected $fillable = [

        'content',
        'community_id',
        'company_id',
        'user_id',
        'status',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // public function likes()
    // {
    //     return $this->hasMany('App\Like');
    // }

    public function is_liked_by_auth_user()
    {

        $id = Auth::id();

        $likers = array();
        if (!empty($this->likes)) {
            foreach ($this->likes as $like) :

                array_push($likers, $like->user_id);

            endforeach;

            if (in_array($id, $likers)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}

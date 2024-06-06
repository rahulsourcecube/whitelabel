<?php

namespace App\Models;

use App\Helpers\Helper;
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

    public function likes()
    {
        $companyId = Helper::getCompanyId();
        // $likers = $this->hasOne(CommunityLikes::class, 'id', 'reply_id')->where('user_id', auth::id())->where('company_id', $companyId)->first();

        return $this->belongsTo(CommunityLikes::class, 'id', 'reply_id')->where('user_id', auth::id())->where('company_id', $companyId);
    }
    public function getunlikes()
    {

        $companyId = Helper::getCompanyId();
        $getunlikes = CommunityLikes::where('reply_id', $this->id)->where('type', "2")
            ->where('company_id', $companyId)->first();
        return $getunlikes;
    }

    public function getlikeCountReplyAttribute()
    {

        $likeCount = "0";
        $companyId = Helper::getCompanyId();
        $likeCount = CommunityLikes::where('reply_id', $this->id)->where('type', "1")
            ->where('company_id', $companyId)
            ->count();

        return $likeCount;
    }
    public function getunlikeCountReplyAttribute()
    {

        $likeCount = "0";
        $companyId = Helper::getCompanyId();
        $likeCount = CommunityLikes::where('reply_id', $this->id)->where('type', "2")
            ->where('company_id', $companyId)
            ->count();

        return $likeCount;
    }
}

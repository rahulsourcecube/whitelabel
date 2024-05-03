<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CommunityLikes;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    // public function like($id)
    // {
    //     $reply = Reply::find($id);

    //     $like = CommunityLikes::create([
    //         'reply_id' => $id,
    //         'user_id' => Auth::id()
    //     ]);

    //     Session::flash('success', 'you liked the reply');

    //     return redirect()->back();
    // }

    // public function unlike($id)
    // {
    //     $like = Like::where('reply_id', $id)->where('user_id', Auth::id())->first();

    //     $like->delete();

    //     Session::flash('success', 'you unliked this reply');

    //     return redirect()->back();
    // }
}
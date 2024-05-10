<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Channels;
use App\Models\Community;
use App\Models\Reply;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class CommunityController extends Controller
{
    public function community($type = null)
    {

        $companyId = Helper::getCompanyId();
        // $discussions = Community::orderby('created_at', 'desc')->paginate(2);
        $questions = Community::where('company_id', $companyId)->orderBy('created_at', 'desc');
        $companyAdmin = Helper::companyAdmin();
        if ($companyAdmin == false) {
            $questions->where('status', Reply::STATUS['ACTIVE']);
        }

        if (!empty($type) && $type == 'my' && !empty(Auth::user())) {
            $questions->where('user_id', auth()->user()->id);
        }
        if (!empty($type) && $type != 'my') {

            $questions->where('channel_id', base64_decode($type));
        }

        $questions = $questions->paginate(5);


        $channels = Channels::where('company_id', $companyId)->get();
        return view('front.community.index', compact('channels', 'questions', 'companyAdmin'));
    }

    public function index()
    {

        $discussions = Community::orderby('created_at', 'desc')->paginate(2);
        return view('front.community.forum')->with('discussions', $discussions);
    }
    public function discuss()
    {

        $companyId = Helper::getCompanyId();
        $channels = Channels::where('company_id', $companyId)->get();

        return view('front.community.discuss', compact('channels'));
    }

    public function channel($id)
    {
        $check = Community::where('channel_id', $id)->first();
        $channel = Channels::find($id);
        return view('layouts.channel')->with('discussions', $channel->discussions()->paginate(3))
            ->with('check', $check);
    }
    public function create()
    {
        if (!(Auth::user())) {
            session()->put('questions_create', 'questions_create');

            return redirect()->route('user.login');
        }

        $companyId = Helper::getCompanyId();
        $channels = Channels::where('company_id', $companyId)->get();
        return view('front.community.create', compact('channels'));
    }

    public function store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'content' => 'required',

            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->validate($request, [
                'title' => 'required',
                'channel_id' => 'required',
                'content' => 'required'
            ]);
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path('uploads/community'), $image);
            } else {
                $image = null;
            }

            $questions = Community::create([
                'title' => $request->title,
                'channel_id' => $request->channel_id,
                'company_id' => $companyId,
                'content' => $request->content,
                'user_id' => Auth::user()->id,
                'image' =>  $image,
            ]);

            $questions->save();


            return redirect()->route('community')->with('success', 'Thank you for reply  successfully');
        } catch (Exception $e) {
            Log::error('CommunityController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $companyId = Helper::getCompanyId();
            $companyAdmin = Helper::companyAdmin();

            $questions = Community::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            if (empty($questions)) {
                return redirect()->back()->with('error', 'Questions not found');
            }
            $questionsReply = Reply::where('company_id', $companyId)->where('community_id', $questions->id)->orderBy('created_at', 'desc');
            if ($companyAdmin == false) {

                $questionsReply->where('status', Reply::STATUS['ACTIVE']);
            }
            $questionsReplys = $questionsReply->paginate(5);

            return view('front.community.show', compact('questions', 'questionsReplys', 'companyAdmin'));
        } catch (Exception $e) {
            Log::error('CommunityController::show => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function reply(Request $request, $id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'content' => 'required',

            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $companyId = Helper::getCompanyId();
            $discussions = Community::where('company_id', $companyId)->where('id', $id)->first();
            if ($discussions) {
                $reply = Reply::create([
                    'user_id' => Auth::user()->id,
                    'company_id' => $companyId,
                    'content' => request()->content,
                    'community_id' => $id
                ]);
            }

            return redirect()->back()->with('success', 'Thank you for reply  successfully');
        } catch (Exception $e) {
            Log::error('CommunityController::reply => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function Delete($id)
    {
        try {


            $companyId = Helper::getCompanyId();
            $questions = Community::where('company_id', $companyId)->where('id', base64_decode($id));
            $questions->delete();
            return response()->json(["status" => 200, "message" => "Questions Deleted"]);
        } catch (\Exception $e) {
            Log::error('CommunityController::delete ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
    public function replyDelete($id)
    {
        try {


            $companyId = Helper::getCompanyId();
            $reply = Reply::where('company_id', $companyId)->where('id', base64_decode($id));
            $reply->delete();
            return response()->json(["status" => 200, "message" => "Your Reply Deleted"]);
        } catch (\Exception $e) {
            Log::error('CommunityController::delete ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
    public function status(Request $request)
    {
        try {



            $companyId = Helper::getCompanyId();
            // Ensure that the community belongs to the current company
            $community = Community::where('company_id', $companyId)->findOrFail(base64_decode($request->id));
            $community->update([
                'status' => $request->status,
            ]);


            return response()->json(["status" => 200, "message" => "Updated successfully"]);
        } catch (\Exception $e) {
            Log::error('CommunityController::status ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
    public function replyStatus(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();
            $reply = Reply::where('company_id', $companyId)->findOrFail(base64_decode($request->id));

            $reply->update([
                'status' => $request->status,
            ]);

            return response()->json(["status" => 200, "message" => "Updated successfully"]);
        } catch (\Exception $e) {
            Log::error('CommunityController::status ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
}

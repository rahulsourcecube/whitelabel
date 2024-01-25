<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\Referral;
use App\Models\TaskEvidence;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    function campaign()
    {
        return view('user.campaign.list');
    }
    function dtlist(Request $request)
    {
        $columns = ['id', 'title'];
        $totalData = CampaignModel::where('company_id', Auth::user()->company_id)->count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];

        $searchColumn = ['title', 'reward', 'description'];

        $query = CampaignModel::orderBy($columns[$order], $dir)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', '1')
            ->whereNotExists(function ($query) {
                $query->from('user_campaign_history')
                    ->whereRaw('campaign.id = user_campaign_history.campaign_id')
                    ->where('user_campaign_history.user_id', Auth::user()->id);
            })
            ->whereDate('expiry_date', '>=', now());

        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $searchColumn) {
                foreach ($searchColumn as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        $totalData = $query->count();
        $results = $query->skip($start)
        ->take($length)
        ->select('campaign.*')
        ->get();
        // dd($results->toSql(), $query->getBindings());

        foreach ($results as $result) {
            $list[] = [
                base64_encode($result->id),
                $result->title ?? "-",
                Helper::getcurrency() . $result->reward ?? "-",
                Str::limit($result->description, 60) ?? "-",
                $result->task_type ?? "_",
                $result->status ?? "_",
            ];
        }
        // $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => isset($totalData) ? $totalData : '',
            "recordsFiltered" => isset($totalData) ? $totalData : '',
            "data" => $list
        ]);
    }

    function campaignview(Request $request)
    {
        $campagin_id = base64_decode($request->id);
        $data = [];
        $data['chats'] = null;
        $data['user'] = null;
        $data['ReferralCount'] = 0;
        $data['campagin_detail'] = CampaignModel::where('id', $campagin_id)->first();
        $data['user_detail'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)
            ->whereExists(function ($query) {
                $query->from('users')
                    ->whereRaw('user_campaign_history.user_id = users.id')
                    ->where('users.referral_user_id', Auth::user()->id)
                    ->whereNotNull('users.referral_user_id');
            })
            ->orderBy('user_id', 'desc')->get();

        $data['user_Campaign'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)->where('user_id', Auth::user()->id)->first();
        if ($data['user_Campaign'] != null) {
            $data['chats'] = TaskEvidence::where('campaign_id', $data['user_Campaign']->id)->get();
            $data['user'] = User::where('id', $data['user_Campaign']->user_id)->first();
            $query = Referral::where('campagin_id', $data['user_Campaign']->id)->where('referral_user_id', Auth::user()->id);
            $data['ReferralCount'] = $query->count();
        }

        $data['referral_user_detail'] = Referral::where('campagin_id', $campagin_id)->where('referral_user_id', Auth::user()->id)->get();
        return view('user.campaign.view', $data);
    }
    public function GetReferralUserDetail(Request $request)
    {
        $companyId = Helper::getCompanyId();
        if ($request->ajax() && $request->campagin_id != null) {
            $draw = $request->input('draw');


            $query = Referral::where('campagin_id', $request->campagin_id)->where('referral_user_id', Auth::user()->id);
            $recordsTotal = $query->count();

            $userCounts = $query->get();
            $data = [];

            foreach ($userCounts as $item) {
                $data[] = [
                    "User" => isset($item->getuser->first_name) ? $item->getuser->first_name : '',
                    "Reward" => Helper::getcurrency() . $item->reward ?? '',
                    "Date" => Helper::Dateformat($item->created_at) ?? ''
                ];
            }
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'data' => $data,
            ]);
        }
    }
    function referral($referral_link)
    {
        if (!(Auth::user())) {
            session()->put('referral_link', route('campaign.referral', $referral_link));
            return redirect()->route('user.login');
        }
        session()->put('referral_link', route('campaign.referral', $referral_link));
        $UserCampaign = UserCampaignHistoryModel::where('referral_link', $referral_link)->first();
        $ReferralCount = Referral::where('campagin_id', $UserCampaign->campagin_id)->where('referral_user_id', Auth::user()->id)->count();
        if (isset($UserCampaign) && $UserCampaign != null) {
            $campagin_id = base64_encode($UserCampaign->campaign_id);
            $ReferralUser = Referral::where('campagin_id', $UserCampaign->campaign_id)->where('user_id', Auth::user()->id)->exists();
        }
        if (isset($UserCampaign) && $UserCampaign != null  && $ReferralUser == false && Auth::user()->company_id == $UserCampaign->getCampaign->company_id && $ReferralCount >= $UserCampaign->no_of_referral_users) {
            return redirect()->route('user.campaign.view', $campagin_id);
        } else {
            return redirect()->route('user.campaign.view', $campagin_id)->with('error', 'Referral link is expired.');
        }

        return redirect()->route('user.campaign.view', $campagin_id)->with('error', 'Referral link is expired.');
    }
    function getusercampaign(Request $request)
    {
        $token = Str::random(10);
        $campagin_id = base64_decode($request->id);


        $getcampaign = CampaignModel::where('id', $campagin_id)->first();
        $input = new UserCampaignHistoryModel;
        $input->campaign_id = isset($getcampaign->id) ? $getcampaign->id : '';
        $input->user_id = Auth::user()->id;
        $input->reward = $getcampaign->reward;

        if ($getcampaign->type == 1) {
            $input->reward = 0;
            $input->referral_link = $token;
            $input->no_of_referral_users = $getcampaign->no_of_referral_users;
        }
        $input->status = 1;
        $input->verified_by = 0;
        $input->save();
        if (Session('referral_link') != null) {
            $referral_link = Session('referral_link');
            $lastSegment = Str::of($referral_link)->afterLast('/'); //referral_link
            $UserCampaign = UserCampaignHistoryModel::where('referral_link', $lastSegment->value)->first();
            if ($UserCampaign->campaign_id == $getcampaign->id) {
                if (isset($UserCampaign) && $UserCampaign != null) {
                    $campagin_id = base64_encode($UserCampaign->campaign_id);
                    $ReferralUser = Referral::where('campagin_id', $UserCampaign->campaign_id)->where('user_id', Auth::user()->id)->exists();
                }
                if (isset($UserCampaign) && $UserCampaign != null && $ReferralUser == false && Auth::user()->company_id == $UserCampaign->getCampaign->company_id) {
                    $Referral = new Referral;
                    $Referral->referral_user_id = $UserCampaign->user_id;
                    $Referral->user_id = Auth::user()->id;
                    $Referral->campagin_id = $UserCampaign->campaign_id;
                    $Referral->reward = isset($UserCampaign->getCampaign->reward) ? $UserCampaign->getCampaign->reward : '0';
                    $Referral->ip = IpRequest::ip();
                    $Referral->save();
                    $UserCampaign->reward = $UserCampaign->reward + $UserCampaign->getCampaign->reward;
                    $UserCampaign->save();
                    return redirect()->route('user.campaign.view', $campagin_id);
                }
            }
            session()->forget('referral_link');
        }
        return $input;
    }
}

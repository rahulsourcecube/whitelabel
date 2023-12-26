<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\UserCampaignHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $results = CampaignModel::orderBy($columns[$order], $dir)
            ->where('company_id', Auth::user()->company_id)
            ->where('status','1')
            ->whereNotExists(function ($query) {
                $query->from('user_campaign_history')
                    ->whereRaw('campaign.id = user_campaign_history.campaign_id')
                    ->where('user_campaign_history.user_id', Auth::user()->id);
            })
            ->whereDate('expiry_date', '>', now())
            ->skip($start)
            ->take($length)
            ->select('campaign.*')
            ->get();
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
        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => isset($totalData) ? $totalData : '',
            "recordsFiltered" => isset($totalFiltered) ? $totalFiltered : '',
            "data" => $list
        ]);
    }

    function campaignview(Request $request)
    {
        $campagin_id = base64_decode($request->id);
        $data=[];
        $data['campagin_detail'] = CampaignModel::where('id', $campagin_id)->first();
        $data['user_detail'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)
            ->whereExists(function ($query) {
                $query->from('users')
                    ->whereRaw('user_campaign_history.user_id = users.id')
                    ->where('users.referral_user_id', Auth::user()->id)
                    ->whereNotNull('users.referral_user_id');
            })
            ->orderBy('user_id', 'desc')->get();

            $data['user_plan'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)->where('user_id', Auth::user()->id)->first();



            return view('user.campaign.view',$data);

    }
    function getusercampaign(Request $request)
    {
        $campagin_id = base64_decode($request->id);

        $getcampaign = CampaignModel::where('id', $campagin_id)->first();
        $input = new UserCampaignHistoryModel;
        $input->campaign_id = isset($getcampaign->id) ? $getcampaign->id : '';
        $input->user_id = Auth::user()->id;
        $input->reward = isset($getcampaign->reward) ? $getcampaign->reward : '';
        $input->status = 1;
        $input->verified_by = 0;
        $input->save();
        return $input;
    }
}

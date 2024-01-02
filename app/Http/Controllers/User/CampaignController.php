<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\Referral;
use App\Models\UserCampaignHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IpRequest;
use Illuminate\Support\Facades\Auth;
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
        $results = CampaignModel::orderBy($columns[$order], $dir)
            ->where('company_id', Auth::user()->company_id)
            ->where('status', '1')
            ->whereNotExists(function ($query) {
                $query->from('user_campaign_history')
                    ->whereRaw('campaign.id = user_campaign_history.campaign_id')
                    ->where('user_campaign_history.user_id', Auth::user()->id);
            })
            ->whereDate('expiry_date', '>=', now())
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
        $data = [];
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
                    "User" =>isset($item->getuser->first_name) ? $item->getuser->first_name : '',
                    "Reward" => $item->reward ?? '',
                    "Date" => date_format($item->created_at, "Y-m-d  h:ia") ?? ''
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
        $user_plan = UserCampaignHistoryModel::where('referral_link', $referral_link)->first();
        if (isset($user_plan) && $user_plan != null) {
            $campagin_id = base64_encode($user_plan->campaign_id);
            $ReferralUser = Referral::where('campagin_id', $user_plan->campaign_id)->where('user_id', Auth::user()->id)->exists();
            $ReferralIp = Referral::where('campagin_id', $user_plan->campaign_id)->where('ip', IpRequest::ip())->exists();
        }
        if (isset($user_plan) && $user_plan != null && $ReferralIp == false && $ReferralUser == false && Auth::user()->company_id == $user_plan->getCampaign->company_id) {
            $Referral = new Referral;
            $Referral->referral_user_id = $user_plan->user_id;
            $Referral->user_id = Auth::user()->id;
            $Referral->campagin_id = $user_plan->campaign_id;
            $Referral->reward = isset($user_plan->getCampaign->reward) ? $user_plan->getCampaign->reward : '0';
            $Referral->ip = IpRequest::ip();
            $Referral->save();
            $user_plan->reward = $user_plan->reward + $user_plan->getCampaign->reward;
            $user_plan->save();
            return redirect()->route('user.campaign.view', $campagin_id);
        } else {
            return redirect()->back()->with('error', 'Referral link is expired.');
        }
    }
    function getusercampaign(Request $request)
    {
        $token = Str::random(10);
        $campagin_id = base64_decode($request->id);
        $getcampaign = CampaignModel::where('id', $campagin_id)->first();
        $input = new UserCampaignHistoryModel;
        $input->campaign_id = isset($getcampaign->id) ? $getcampaign->id : '';
        $input->user_id = Auth::user()->id;
        $input->reward = 0;
        $input->referral_link = $token;
        $input->status = 1;
        $input->verified_by = 0;
        $input->save();
        return $input;
    }
}

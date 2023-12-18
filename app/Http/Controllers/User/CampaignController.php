<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\UserCampaignHistoryModel;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    function campaign()
    {
        return view('user.campaign.list');
    }
    function dtlist(Request $request)
    {
        $columns = ['id', 'title'];
        $totalData = CampaignModel::where('company_id', 33)->count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = CampaignModel::orderBy($columns[$order], $dir)
            ->where('company_id', 33)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {
            $list[] = [
                base64_encode($result->id),
                $result->title ?? "-",
                $result->reward ?? "-",
                $result->description ?? "-",
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
    // function userlist(Request $request)
    // {
    //     $columns = ['id', 'title'];
    //     $totalData = UserCampaignHistoryModel::where('user_id', 34)->count();
    //     $start = $request->input('start');
    //     $length = $request->input('length');
    //     $order = $request->input('order.0.column');
    //     $dir = $request->input('order.0.dir');
    //     $list = [];
    //     $results = UserCampaignHistoryModel::orderBy($columns[$order], $dir)
    //         ->where('user_id', 34)
    //         ->skip($start)
    //         ->take($length)
    //         ->get();
    //     foreach ($results as $result) {
    //         $list[] = [
    //             base64_encode($result->id),
    //             $result->title ?? "-",
    //             $result->reward ?? "-",
    //             $result->description ?? "-",
    //             $result->task_type ?? "_",
    //             $result->status ?? "_",
    //         ];
    //     }
    //     $totalFiltered = $results->count();
    //     return response()->json([
    //         "draw" => intval($request->input('draw')),
    //         "recordsTotal" => isset($totalData) ? $totalData : '',
    //         "recordsFiltered" => isset($totalFiltered) ? $totalFiltered : '',
    //         "data" => $list
    //     ]);
    // }
    function campaignview(Request $request)
    {
        $campagin_id = base64_decode($request->id);
        $campagin_detail = CampaignModel::where('id', $campagin_id)->first();
        $user_detail = UserCampaignHistoryModel::whereNot('user_id',33)->orderBy('user_id','desc')->get();
        if (isset($campagin_detail)) {
            return view('user.campaign.view', compact('campagin_detail','user_detail'));
        }
    }
    function getusercampaign(Request $request)
    {
        $campagin_id = base64_decode($request->id);
        $getcampaign = CampaignModel::where('id', $campagin_id)->first();
        $input = new UserCampaignHistoryModel;
        $input->campaign_id = isset($getcampaign->id) ? $getcampaign->id : '';
        $input->user_id = 35;
        $input->reward = isset($getcampaign->reward) ? $getcampaign->reward : '';
        $input->status = 1;
        $input->verified_by = 1;
        $input->save();
        return $input;
    }
}

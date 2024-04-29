<?php

namespace App\Http\Controllers\Fornt;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function publicCampaign()
    {

        $task_data = CampaignModel::where('public', 1)->paginate(6);

        return view('front.campaign.public_campaign', compact('task_data'));
    }
}

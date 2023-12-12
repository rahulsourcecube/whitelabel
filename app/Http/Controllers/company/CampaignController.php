<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use Illuminate\Http\Request;

class CampaignController extends Controller
{

    function index()
    {
        return view('company.campaign.list');
    }
    function referralTasks()
    {
        return view('company.campaign.referralTasks');
    }
    function socialShare()
    {
        return view('company.campaign.socialShare');
    }
    function customTasks()
    {
        return view('company.campaign.customTasks');
    }
    function create()
    {
        return view('company.campaign.create');
    }
    function referralStore(Request $request)
    {
        $campaignModel = new CampaignModel();
        $campaignModel->company_id=auth()->user()->id;
        $campaignModel->title= $request->title;
        $campaignModel->description= $request->description;
        $campaignModel->reward=$request->reaward;
        $campaignModel->expiry_date=$request->edate;
        $campaignModel->type=$request->tasktype;
         $campaignModel->save();
       
        return redirect()->route('company.campaign.list')->with('error', 'These credentials do not match our records.');
    }
    function analytics()
    {
        return view('company.campaign.analytics');
    }

    function view()
    {
        return view('company.campaign.view');
    }

}

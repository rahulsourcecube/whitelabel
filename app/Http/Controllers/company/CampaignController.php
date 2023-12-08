<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
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
    function analytics()
    {
        return view('company.campaign.analytics');
    }

    function view()
    {
        return view('company.campaign.view');
    }

}

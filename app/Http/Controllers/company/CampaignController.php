<?php

namespace App\Http\Controllers\company;

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
    function view()
    {
        return view('company.campaign.view');
    }
    
}

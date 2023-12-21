<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 

class CampaignController extends Controller
{

    function index($type)
    {
        $taskType = $type;
        $type = CampaignModel::TYPE[strtoupper($type)];
        return view('company.campaign.list', compact('type', 'taskType'));
    }

    public function tdlist($type, Request $request)
    {
        try {
            $companyId = Auth::user()->id;
            $columns = ['id', 'title'];
            $totalData = CampaignModel::where('company_id', $companyId)->where('type', $type)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $results = CampaignModel::where('company_id', $companyId)->where('type', $type)
                ->skip($start)
                ->take($length)
                ->get();
            foreach ($results as $result) {
                $imgUrl = "";
                if (!empty($result->image) && file_exists('uploads/campaign/' . $result->image)) {
                    $imgUrl = asset('uploads/campaign/' . $result->image);
                }
                $list[] = [
                    base64_encode($result->id),
                    $result->title ?? "-",
                    $result->reward ?? "-",
                    Str::limit($result->description, 60) ?? "-",
                    $result->task_type,
                    $result->task_status,
                    $result->task_status,
                    // $imgUrl,
                ];
            }
            $totalFiltered = $results->count();
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('Task list error : ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
    }
    public function statuswiselist(Request $request){
        $columns = ['id', 'title'];
       
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = UserCampaignHistoryModel::orderBy($columns[$order], $dir)
        // ->where('company_id', Auth::user()->id)
        ->where('campaign_id', $request->input('id'))
        ->where('status', $request->input('status'))
            ->skip($start)
            ->take($length)
            ->get();
           
        foreach ($results as $result) {
          
            $list[] = [
                base64_encode($result->id),
                $result->getuser->full_name ?? "-",
                $result->getuser->email ?? "-",
                $result->getuser->contact_number ?? "-",              
                $result->reward ?? "-",              
                date('Y-m-d H:i:s', strtotime( str_replace('/', '-', $result->created_at ) ) )  ?? "-", 
                $result->TaskStatus?? "-",                     
                base64_encode($result->user_id)?? "-",                     
            
            ];
        }
        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => count($results),
            "recordsFiltered" => $totalFiltered,
            "data" => $list
        ]);
    }
   


    function create($type)
    {
        $type = CampaignModel::TYPE[strtoupper($type)];
        return view('company.campaign.create', compact('type'));
    }

    public function store(Request $request)
    {
        try {
            $companyId = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'reward' => 'required|numeric',
                'description' => 'required',
                'expiry_date' => 'required|date',
                'type' => 'required',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move('uploads/company/campaign/', $image);
            } else {
                $image = null;
            }
            $request->merge(['image' => $image, 'company_id' => $companyId]);

            $Campaign = new CampaignModel();
            $Campaign->title = $request->title; 
            $Campaign->reward = $request->reward; 
            $Campaign->description = $request->description; 
            $Campaign->expiry_date = $request->expiry_date; 
            $Campaign->type = $request->type; 
            $Campaign->image = $image; 
            $Campaign->company_id = $companyId; 
            $Campaign->status = !empty($request->status)?'0':"1";         

            $Campaign->save();
            // CampaignModel::create($request->all());
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task added successfuly.');
        } catch (Exception $e) {
            Log::error('Campaign store error : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request,CampaignModel $Campaign)
    {
        try {
            $companyId = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'reward' => 'required|numeric',
                'description' => 'required',
                'expiry_date' => 'required|date',
                'type' => 'required',
                'image' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move('uploads/company/campaign/', $image);
                if (!empty($Campaign->image)) {
                    $oldImagePath = 'uploads/company/campaign/' . $Campaign->image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            } else {
                $image = $Campaign->image;
            }
            $request->merge(['image' => $image, 'company_id' => $companyId]);

            $Campaign->title = $request->title; 
            $Campaign->reward = $request->reward; 
            $Campaign->description = $request->description; 
            $Campaign->expiry_date = $request->expiry_date; 
            $Campaign->type = $request->type; 
            $Campaign->image = $image; 
            $Campaign->company_id = $companyId; 
            $Campaign->status = !empty($request->status)?'0':'1'; 
            $Campaign->save();
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task update successfuly.');
        } catch (Exception $e) {
            Log::error('Campaign store error : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    function analytics()
    {
        return view('company.campaign.analytics');
    }

    public function view($type, $id)
    {
        $type = CampaignModel::TYPE[strtoupper($type)];
        $taskId = base64_decode($id);
        $task = CampaignModel::where('id', $taskId)->where('type', $type)->first();
        if (empty($task)) {
            return back()->with('error', 'Task not found');
        }
        return view('company.campaign.view', compact('type', 'taskId', 'task'));
    }

    public function edit($type, $id)
    {
        $type = CampaignModel::TYPE[strtoupper($type)];
        $taskId = base64_decode($id);
        $task = CampaignModel::where('id', $taskId)->where('type', $type)->first();
        if (empty($task)) {
            return back()->with('error', 'Task not found');
        }
        return view('company.campaign.edit', compact('type', 'taskId', 'task'));
    }
    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            $campaignModel=CampaignModel::where('id', $id)->first();
            if (!empty($campaignModel->image)) {
                $oldImagePath = 'uploads/company/campaign/' . $campaignModel->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $campaignModel=CampaignModel::where('id', $id)->delete();
            return response()->json(['success' => 'error', 'message' => 'Task deleted successfully']);
        } catch (Exception $e) {
            Log::error('Campaign delete error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function action(Request $request)
    {      
      
        try {
            $id = base64_decode($request->id);
            $action = UserCampaignHistoryModel::where('id',$id)->first();
            
          
            if($request->action=='3'){
                $action->status = '3';
                $action->save();
                return response()->json(['success' => 'error', 'message' => 'Task Accept  Approval Requset successfully']);             
            }else{
                $action->status = '4';
                $action->save();
                return response()->json(['success' => 'error', 'message' => 'Task Reject  Approval Requset successfully']); 
            }
           
        } catch (Exception $e) {
            Log::error('ation error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function userDetails(Request $request)
    {      
      
        try {
            $user_id = base64_decode($request->id);
            $user = User::where('id', $user_id)->first();
            if (empty($user)) {
               return response()->json(['success' => 'error', 'message' => 'Task Accept Approval Requset successfully']);  
            }
            $html="";
            $html='<div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="d-md-flex align-items-center">
                                            <div class="text-center text-sm-left ">
                                                <div class="avatar avatar-image" style="width: 150px; height:150px">
                                                    <img id="image" src="assets/images/avatars/thumb-3.jpg" alt="">
                                                </div>
                                            </div>
                                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                                <h2 class="m-b-5 name" >'. $user->first_name.' '.$user->last.'</h2>
                                                <p class="text-opacity font-size-13"></p>
                                                s
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="d-md-block d-none border-left col-1"></div>
                                            <div class="col">
                                                <ul class="list-unstyled m-t-10">
                                                    <li class="row">
                                                        <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                            <span>Email: </span> 
                                                        </p>
                                                        <p class="col font-weight-semibold">'. $user->email .'</p>
                                                    </li>
                                                    <li class="row">
                                                        <p class="col-sm-4 col-4 font-weight-semibold text-dark m-b-5">
                                                            <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                            <span>Phone: </span> 
                                                        </p>
                                                        <p class="col font-weight-semibold"> '.$user->contact_number.'</p>
                                                    </li>
                                                   
                                                </ul>
                                                <div class="d-flex font-size-22 m-t-15">
                                                    <a href="#" class="text-gray p-r-20">
                                                        <i class="anticon anticon-facebook"></i>
                                                    </a>        
                                                    <a href="#" class="text-gray p-r-20">    
                                                        <i class="anticon anticon-twitter"></i>
                                                    </a>
                                                    <a href="#" class="text-gray p-r-20">
                                                        <i class="anticon anticon-behance"></i>
                                                    </a> 
                                                    <a href="#" class="text-gray p-r-20">   
                                                        <i class="anticon anticon-dribbble"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            return response()->json(['success' => 'error', 'message' => $html]);  
           
        } catch (Exception $e) {
            Log::error('ation error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    
}

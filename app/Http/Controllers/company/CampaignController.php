<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
                    $result->description ?? "-",
                    $result->task_type,
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
                $request->file('image')->move('uploads/company/campaign', $image);
            } else {
                $image = null;
            }
            $request->merge(['image' => $image, 'company_id' => $companyId]);
            CampaignModel::create($request->all());
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task added successfuly.');
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
        return view('company.campaign.view', compact('type', 'taskId', 'task'));
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Exports\Export;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CampaignController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // check user permission
        $this->middleware('permission:task-list', ['only' => ['index', 'view']]);
        $this->middleware('permission:task-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:task-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:task-delete', ['only' => ['delete']]);
    }



    function index($type)
    {
        $taskType = $type;
        $type = CampaignModel::TYPE[strtoupper($type)];
        $totalData = CampaignModel::where('company_id', Auth::user()->id)->where('type', $type)->get();
        return view('company.campaign.list', compact('type', 'taskType', 'totalData'));
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
                    Helper::getcurrency() . $result->reward ?? "-",
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
    public function statuswiselist(Request $request)
    {
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
        // dd($results);

        foreach ($results as $result) {

            $list[] = [
                base64_encode($result->id),
                $result->getuser->full_name ?? "-",
                $result->getuser->email ?? "-",
                $result->getuser->contact_number ?? "-",
                $result->reward ?? "-",
                date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $result->created_at)))  ?? "-",
                $result->TaskStatus ?? "-",
                base64_encode($result->user_id) ?? "-",

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
            $userCount = User::where('company_id', $companyId)->where('user_type',  User::USER_TYPE['USER'])->count();
            $ActivePackageData = Helper::GetActivePackageData();
            if ($userCount >= $ActivePackageData->GetPackageData->no_of_campaign) {
                return redirect()->back()->with('error', 'you can create only ' . $ActivePackageData->GetPackageData->no_of_campaign . ' campaigns');
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
            $Campaign->status = !empty($request->status) ? '0' : "1";

            $Campaign->save();
            // CampaignModel::create($request->all());
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task added successfuly.');
        } catch (Exception $e) {
            Log::error('Campaign store error : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request, CampaignModel $Campaign)
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
            $Campaign->status = !empty($request->status) ? '0' : '1';
            $Campaign->save();
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task update successfuly.');
        } catch (Exception $e) {
            Log::error('Campaign store error : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    function analytics(Request $request)
    {
        $companyId = Auth::user()->id;
        $date = Carbon::today()->subDays(7);
        $total_join_users = DB::table('users as u')
            ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
            ->join('campaign as c', 'c.id', '=', 'uch.campaign_id')
            ->where('u.company_id', $companyId)
            ->where('u.user_type', env('USER_ROLE'))
            ->where('u.status', '0')
            ->where('c.status', '0')
            ->where('uch.status', '3')
            ->where('c.type', '1')
            ->whereDate('uch.created_at', '>=', $date)
            ->select(DB::raw('COUNT(uch.user_id) as total_user , DAYNAME(uch.created_at) as day'))
            ->groupBy('day')
            ->get();

        $dateandtime = Carbon::now();
        $start_date = $dateandtime->subDays(7);
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 week", $start_time);
        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $list[date('l', $i)] = 0;
        }
        foreach ($total_join_users as $values) {
            $list[$values->day] = $values->total_user;
        }
        $user_total = json_encode(['day' => array_keys($list), 'total_user' => array_values($list)]);
        return view('company.campaign.analytics', compact('user_total'));
    }
    function fetch_data(Request $request)
    {
        // dd($request->from_date);
        if ($request->ajax()) {
            // if ($request->from_date != '' && $request->to_date != '') {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            dd($from_date);
            $to_date = date('Y-m-d', strtotime($request->to_date));

            DB::enableQueryLog();
            $companyId = Auth::user()->id;
            $total_join_users = DB::table('users as u')
                ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
                ->join('campaign as c', 'c.id', '=', 'uch.campaign_id')
                ->where('u.company_id', $companyId)
                ->where('u.user_type', env('USER_ROLE'))
                ->where('u.status', '0')
                ->where('c.status', '0')
                ->where('uch.status', '3')
                ->where('c.type', '1')
                ->select(DB::raw('COUNT(uch.user_id) as total_user , DAYNAME(uch.created_at) as day'))
                ->whereBetween('uch.created_at', [$from_date, $to_date])
                ->groupBy('day')
                ->get();
            //  dd(DB::getQueryLog());
            // dd($total_join_users);
            $dateandtime = Carbon::now();
            $start_date = $dateandtime->subDays(7);
            $start_time = strtotime($start_date);
            $end_time = strtotime("+1 week", $start_time);
            for ($i = $start_time; $i < $end_time; $i += 86400) {
                $list[date('l', $i)] = 0;
            }
            foreach ($total_join_users as $values) {
                $list[$values->day] = $values->total_user;
            }
            $user_total = json_encode(['day' => array_keys($list), 'total_user' => array_values($list)]);
            dd($user_total);
            return $user_total;
        }
        // }
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
            $campaignModel = CampaignModel::where('id', $id)->first();
            if (!empty($campaignModel->image)) {
                $oldImagePath = 'uploads/company/campaign/' . $campaignModel->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $campaignModel = CampaignModel::where('id', $id)->delete();
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

            $action = UserCampaignHistoryModel::where('id', $id)->first();
            $Notification = new Notification();

            if ($request->action == '3') {
                $action->status = '3';
                $action->save();
                if(isset($action)){
                    $Notification->user_id=  $action->user_id;
                    $Notification->company_id=  $action->campaign_id;
                    $Notification->title=  " Campaign approved ";
                    $Notification->message=  $action->getCampaign->title." Approved.";
                    $Notification->type=  "1";
                    $Notification->save();
                }
                return response()->json(['success' => 'success', 'messages' => ' Task Approved successfully']);
            } else {
                $action->status = '4';
                $action->save();
                if(isset($action)){
                    $Notification->user_id=  $action->user_id;
                    $Notification->company_id=  $action->campaign_id;
                    $Notification->title=  " Campaign rejected";
                    $Notification->message=  $action->getCampaign->title ." Rejected.";
                    $Notification->type=  "1";

                    $Notification->save();
                }
                return response()->json(['success' => 'success', 'messages' => ' Task Rejectedz successfully']);
            }
        } catch (Exception $e) {
            Log::error('ation error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function export($type)
    {
        $date = Carbon::now()->toDateString();
        $tasktype = CampaignModel::TYPE[strtoupper($type)];
        return Excel::download(new Export($tasktype), ($type . '_' . $date . '.xlsx'));
    }
    public function userDetails(Request $request)
    {

        try {
            $id = base64_decode($request->id);
            $companyId = Auth::user()->id;
            $camphistory = UserCampaignHistoryModel::where('id', $id)->first();

            $user = User::where('id', $camphistory->user_id)->first();
            if (empty($user)) {
                return response()->json(['success' => 'error', 'message' => 'Task Accept Approval Requset successfully']);
            }
            $html = "";
            $html .= '<div class="modal-header ">
                        <h5 class="modal-title h4">View</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="anticon anticon-close"></i>
                        </button>
                    </div>  

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row align-items-center">
                            <div class="text-center text-sm-left col-md-2">
                                <div class="avatar avatar-image" style="width: 150px; height:150px">';

            if (isset($user) && !empty($user->profile_image) && file_exists('uploads/company/user-profile/' . $user->profile_image)) {
                $html .= '<img src="' . asset('uploads/company/user-profile/' . $user->profile_image) . '" alt="">';
            } else {
                $html .= '<img src="' . asset('assets/images/default-user.jpg') . '" alt="">';
            };
            $html .= ' </div>
                            </div>
                            <div class="text-center text-sm-left m-v-15 p-l-30">
                                <h2 class="m-b-5"></h2>
                                <div class="row">
                                    <div class="d-md-block d-none border-left col-1"></div>
                                    <div class="col-md-12">
                                        <ul class="list-unstyled m-t-10">
                                            <li class="row">
                                                <p class="col-sm-6 col-6 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-mail"></i>
                                                    <span>Email: </span>
                                                </p>
                                                <p class="col font-weight-semibold">' . $user->email ?? $user->email;
            $html .= '</p>
                                            </li>
                                            <li class="row">
                                                <p class="col-sm-6 col-6 font-weight-semibold text-dark m-b-5">
                                                    <i class="m-r-10 text-primary anticon anticon-phone"></i>
                                                    <span>Phone: </span>
                                                </p>
                                                <p class="col font-weight-semibold"> ' . $user->contact_number ?? $user->contact_number;
            $html .= '</p>
                                            </li>
                                        
                                        </ul>
                                        <div class="d-flex font-size-22 m-t-15">
                                        ';
            if (!empty($user->facebook_link)) {
                $html .= '
                                            <a href="' . $user->facebook_link ?? $user->facebook_link;
                $html .= '"
                                            target="blank" class="text-gray p-r-20">
                                            <i class="anticon anticon-facebook"></i>
                                        </a>';
            };
            if (!empty($user->instagram_link)) {
                $html .= '
                                            
                                            <a href="' . $user->instagram_link ?? $user->instagram_link;
                $html .= '"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-instagram"></i>
                                            </a>
                                            ';
            };
            if (!empty($user->twitter_link)) {
                $html .= '
                                            <a href="' . $user->twitter_link ?? $user->twitter_link;
                $html .= '"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-twitter"></i>
                                            </a>
                                            ';
            };
            if (!empty($user->youtube_link)) {
                $html .= '
                                            <a href="' . $user->youtube_link ?? $user->youtube_link;
                $html .= '"
                                                target="blank" class="text-gray p-r-20">
                                                <i class="anticon anticon-youtube"></i>
                                            </a>
                                            ';
            }
            $html .= '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Bank Detail:</h2>
                <div class="table-responsive">
                    <table class="product-info-table m-t-20">
                        <tbody>
                            <tr>
                                <td>Bank Name:</td>
                                <td> ' . $user->twitter_link ?? $user->twitter_link;
            $html .= '</td>
                            </tr>
                            <tr>
                                <td>Bank Holder : </td>
                                <td>' . $user->ac_holder     ?? $user->ac_holder;
            $html .= '</td>
                            </tr>
                            <tr>
                                <td>IFSC Code :</td>
                                <td>' . $user->ifsc_code ?? $user->ifsc_code;
            $html .= '</td>
                            </tr>
                            <tr>
                                <td>Account No :</td>
                                <td> ' . $user->ac_no ?? $user->ac_no;
            $html .= '</td>
                            </tr>
                            <tr>                               
                                <td> <button class="btn btn-success  btn-sm action" data-action="3"   data-id="' . base64_encode($id) . '" data-url="' . route('company.campaign.action') . '"  >Accept</button>
                                <button class="btn btn-danger  btn-sm action" data-action="4"   data-id="' . base64_encode($id) . '" data-url="' . route('company.campaign.action') . '"data-action="Reject" >Reject</button></td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>  ';
            return response()->json(['success' => 'error', 'message' => $html]);
        } catch (Exception $e) {
            Log::error('ation error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}

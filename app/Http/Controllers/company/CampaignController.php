<?php

namespace App\Http\Controllers\Company;

use App\Exports\Export;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\SettingModel;
use App\Models\TaskEvidence;
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
use PhpParser\Node\Stmt\TryCatch;

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
        try {
            $taskType = $type;
            $type = CampaignModel::TYPE[strtoupper($type)];
            $companyId = Helper::getCompanyId();
            $totalData = CampaignModel::where('company_id', $companyId)->where('type', $type)->get();
            return view('company.campaign.list', compact('type', 'taskType', 'totalData'));
        } catch (Exception $e) {
            Log::error('CampaignController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function tdlist($type, Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $totalData = CampaignModel::where('company_id', $companyId)->where('type', $type)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $list = [];

            $searchColumn = ['title', 'reward', 'description', 'no_of_referral_users'];

            $query = CampaignModel::where('company_id', $companyId)->where('type', $type);

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            }

            $results = $query->skip($start)
                ->take($length)
                ->get();

            foreach ($results as $result) {
                $list[] = [
                    base64_encode($result->id),
                    $result->title ?? "-",
                    Helper::getcurrency() . $result->reward ?? "-",
                    Str::limit($result->description, 60) ?? "-",
                    $result->task_type,
                    $result->no_of_referral_users,
                    $result->task_status,
                    $result->campaignUSerHistoryData->count() == 0
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('CampaignController::Tdlist  => ' . $e->getMessage());
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
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id', 'title'];
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];

            $searchColumn = ['user_campaign_history.created_at', 'users.email', 'users.contact_number', 'users.first_name', 'users.last_name'];

            $query = UserCampaignHistoryModel::leftJoin('users', 'user_campaign_history.user_id', '=', 'users.id')
                ->select("user_campaign_history.*")
                ->orderBy("user_campaign_history." . $columns[$order], $dir)
                ->where('user_campaign_history.campaign_id', $request->input('id'))
                ->where('users.company_id', $companyId)
                ->where('user_campaign_history.status', $request->input('status'));

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            }

            $results = $query->skip($start)
                ->take($length)
                ->get();

            $list = [];
            foreach ($results as $result) {

                $list[] = [
                    base64_encode($result->id),
                    $result->getuser->full_name ?? "-",
                    $result->getuser->email ?? "-",
                    $result->getuser->contact_number ?? "-",
                    '$' . $result->reward ?? "0",
                    Helper::Dateformat($result->created_at)  ?? "-",
                    $result->TaskStatus ?? "-",
                    base64_encode($result->user_id) ?? "-",

                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => count($results),
                "recordsFiltered" => count($results),
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('CampaignController::Statuswiselist  => ' . $e->getMessage());
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
        try {
            //Check  Active Package Access 
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }

            $typeInText = $type;
            $type = CampaignModel::TYPE[strtoupper($type)];
            return view('company.campaign.create', compact('type', 'typeInText'));
        } catch (Exception $e) {
            Log::error('CampaignController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
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
            $ActivePackageData = Helper::GetActivePackageData();
            $CampaignModelCount = CampaignModel::where('company_id', $companyId)->where('package_id', $ActivePackageData->id)->count();
            if ($CampaignModelCount >= $ActivePackageData->no_of_campaign) {
                return redirect()->back()->with('error', 'You can create only ' . $ActivePackageData->no_of_campaign . ' tasks');
            }
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                $request->file('image')->move(base_path('uploads/company/campaign/'), $image);
            } else {
                $image = null;
            }
            $request->merge(['image' => $image, 'company_id' => $companyId]);

            $Campaign = new CampaignModel();
            $Campaign->title = $request->title;
            $Campaign->reward = $request->reward;
            $Campaign->no_of_referral_users = $request->no_of_referral_users;
            $Campaign->description = $request->description;
            $Campaign->expiry_date = $request->expiry_date;
            $Campaign->type = $request->type;
            $Campaign->image = $image;
            $Campaign->company_id = $companyId;
            $Campaign->status = !empty($request->status) ? '1' : "0";
            $Campaign->package_id = $ActivePackageData->id;

            $Campaign->save();
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task added successfuly.');
        } catch (Exception $e) {
            Log::error('CampaignController::Store => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function update(Request $request, CampaignModel $Campaign)
    {
        try {
            $companyId = Helper::getCompanyId();
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
                $request->file('image')->move(base_path('uploads/company/campaign/'), $image);
                if (!empty($Campaign->image)) {
                    $oldImagePath = base_path() . '/uploads/company/campaign/' . $Campaign->image;
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
            $Campaign->no_of_referral_users = $request->no_of_referral_users;
            $Campaign->description = $request->description;
            $Campaign->expiry_date = $request->expiry_date;
            $Campaign->type = $request->type;
            $Campaign->image = $image;
            $Campaign->company_id = $companyId;
            $Campaign->status = !empty($request->status) ? '1' : '0';
            $Campaign->save();
            $taskType = Helper::taskType($request->type);
            return redirect()->route('company.campaign.list', $taskType)->with('success', 'Task update successfuly.');
        } catch (Exception $e) {
            Log::error('CampaignController::Update => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    function analytics(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $date = Carbon::today()->subDays(7);
            $total_join_users  = DB::table('users as u')
                ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
                ->join('campaign as c', 'c.id', '=', 'uch.campaign_id')
                ->where('u.company_id', $companyId)
                ->where('u.user_type', 4)
                ->where('u.status', '1')
                ->where('c.status', '1')
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
            $list = [];
            for ($i = $start_time; $i < $end_time; $i += 86400) {
                $list[date('D', $i)] = 0;
            }
            foreach ($total_join_users as $values) {
                $list[$values->day] = $values->total_user;
            }
            $user_total = json_encode(['day' => array_keys($list), 'total_user' => array_values($list)]);           

            $customTasks = CampaignModel::where('company_id', $companyId)->where('type', 3)->get();

            return view('company.campaign.analytics', compact('user_total', 'customTasks'));
        } catch (Exception $e) {
            Log::error('CampaignController::Analytics => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function fetch_data(Request $request)
    {
        try {

            if ($request->ajax()) {
                if ($request->date_range_filter != null) {
                    $date = explode('-', $request->date_range_filter);
                    $from_date = date('Y-m-d', strtotime($date[0]));
                    $to_date = date('Y-m-d', strtotime($date[1]));
                    $companyId = Auth::user()->id;
                    $total_join_users = DB::table('users as u')
                        ->join('user_campaign_history as uch', 'u.id', '=', 'uch.user_id')
                        ->join('campaign as c', 'c.id', '=', 'uch.campaign_id')
                        ->where('u.company_id', $companyId)
                        ->where('u.user_type', 4)
                        ->where('u.status', '1')
                        ->where('c.status', '1')
                        ->where('uch.status', '3')
                        ->where('c.type', '1')
                        ->whereDate('uch.created_at', '>=', $from_date)
                        ->whereDate('uch.created_at', '<=', $to_date)
                        ->select(DB::raw('COUNT(uch.user_id) as total_user , DAYNAME(uch.created_at) as day'))
                        ->groupBy('day')
                        ->get();
                    $start_date = $from_date;
                    $start_time = strtotime($start_date);
                    $end_time = strtotime($to_date);
                    $list = [];
                    for ($i = $start_time; $i <= $end_time; $i += 86400) {
                        $list[date('D', $i)] = 0;
                    }

                    foreach ($total_join_users as $values) {
                        $abbreviatedDay = date('D', strtotime($values->day));
                        $list[$abbreviatedDay] = $values->total_user;
                    }

                    $user_total = [];
                    if (isset($list)) {
                        $user_total = ['day' => array_keys($list), 'total_user' => array_values($list)];
                    }
                    return $user_total;
                }
            }
        } catch (Exception $e) {
            Log::error('CampaignController::Fetchdata => ' . $e->getMessage());
            return  ['day' => [], 'total_user' => []];
        }
    }

    public function view($type, $id)
    {
        try {
            $companyId = Helper::getCompanyId();

            $type = CampaignModel::TYPE[strtoupper($type)];
            $taskId = base64_decode($id);
            $task = CampaignModel::where('id', $taskId)->where('company_id', $companyId)->where('type', $type)->first();
            if (empty($task)) {
                return back()->with('error', 'Task not found');
            }
            return view('company.campaign.view', compact('type', 'taskId', 'task'));
        } catch (Exception $e) {
            Log::error('CampaignController::View => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function edit($type, $id)
    {
        try {
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }

            $companyId = Helper::getCompanyId();
            $type = CampaignModel::TYPE[strtoupper($type)];
            $taskId = base64_decode($id);
            $task = CampaignModel::where('id', $taskId)->where('company_id', $companyId)->where('type', $type)->first();
            if (empty($task)) {
                return back()->with('error', 'Task not found');
            }
            return view('company.campaign.edit', compact('type', 'taskId', 'task'));
        } catch (Exception $e) {
            Log::error('CampaignController::Edit => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $id = base64_decode($id);
            $campaignModel = CampaignModel::where('id', $id)->where('company_id', $companyId)->first();
            if (!empty($campaignModel->image)) {
                $oldImagePath = base_path() . '/uploads/company/campaign/' . $campaignModel->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $campaignModel = CampaignModel::where('id', $id)->where('company_id', $companyId)->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        } catch (Exception $e) {
            Log::error('CampaignController::Delete  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error : ' . $e->getMessage()]);
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
                if (isset($action)) {
                    $Notification->user_id =  $action->user_id;
                    $Notification->company_id =  $action->campaign_id;
                    $Notification->title =  " Campaign approved ";
                    $Notification->message =  $action->getCampaign->title . " Approved.";
                    $Notification->type =  "1";
                    $Notification->save();
                }

                return response()->json(['success' => 'success', 'messages' => ' Task Approved successfully']);
            } else {
                $action->status = '4';
                $action->save();
                if (isset($action)) {
                    $Notification->user_id =  $action->user_id;
                    $Notification->company_id =  $action->campaign_id;
                    $Notification->title =  " Campaign rejected";
                    $Notification->message =  $action->getCampaign->title . " Rejected.";
                    $Notification->type =  "1";

                    $Notification->save();
                }
                return response()->json(['success' => 'success', 'messages' => ' Task Rejected successfully']);
            }
        } catch (Exception $e) {
            Log::error('CampaignController::Action  => ' . $e->getMessage());
            return response()->json(['success' => 'error', 'messages' => 'Error : ' . $e->getMessage()]);
        }
    }
    public function export($type)
    {
        try {
            $date = Carbon::now()->toDateString();
            $tasktype = CampaignModel::TYPE[strtoupper($type)];
            return Excel::download(new Export($tasktype), ($type . '_' . $date . '.xlsx'));
        } catch (Exception $e) {
            Log::error('CampaignController::Export => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function userDetails(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $companyId = Helper::getCompanyId();
            $setting = SettingModel::where('user_id', $companyId)->first();
            $camphistory = UserCampaignHistoryModel::where('id', $id)->first();
            $referral_user_detail = Referral::where('campagin_id', $camphistory->campaign_id)->where('referral_user_id', $camphistory->user_id)->get();
            $user = User::where('id', $camphistory->user_id)->where('company_id', $companyId)->first();

            if (empty($user)) {
                return redirect()->back()->with('error', 'User not found');
            }
            $chats = TaskEvidence::where('campaign_id', $id)->where('company_id', $companyId)->get();
            return view('company.campaign.user-details', compact('chats', 'setting', 'user', 'camphistory', 'referral_user_detail', 'id'));
        } catch (Exception $e) {
            Log::error('CampaignController::UserDetails => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function storeChat(UserCampaignHistoryModel $id, Request $request)
    {
        try {
            if ($request->hasFile('image') || $request->chat_input != null) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = rand(111111, 999999) . time() . '.' . $image->getClientOriginalExtension();
                    // Save the image to the public directory
                    $image->move(public_path('uploads/Chats'), $imageName);
                    $imageName = 'uploads/Chats/' . $imageName;
                }
                $chats = TaskEvidence::where('campaign_id', $id->id)->where('user_id', $id->user_id)->where('company_id', $id->getCampaign->company_id)->get();
                if ($chats->count() == 0) {

                    $id->status = '2';
                    $id->save();
                    if (isset($id)) {
                        $Notification = new Notification();
                        $Notification->user_id =  $id->user_id;
                        $Notification->company_id =  $id->getCampaign->company_id;
                        $Notification->type =  '2';
                        $Notification->title =  " Campaign approval request";
                        $Notification->message =  $id->getCampaign->title . " approval request by " . $id->getuser->FullName;
                        $Notification->save();
                    }
                }
                if ($id->status == '4' && Auth::user()->user_type == 4) {
                    $id->status = '5';
                    $id->save();
                }
                $TaskEvidence = new TaskEvidence();
                $TaskEvidence->user_id = $id->user_id;
                $TaskEvidence->company_id = $id->getCampaign->company_id;
                $TaskEvidence->campaign_id = $id->id;
                $TaskEvidence->sender_id = Auth::user()->id;
                $TaskEvidence->message = $request->chat_input;
                if ($request->hasFile('image')) {
                    $TaskEvidence->document = $imageName;
                }
                $TaskEvidence->save();
            }
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('CampaignController::StoreChat => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function CompanyCustom(Request $request)
    {
        try {
            $data = [];
            if (empty($request->title)) {
                $data = [];
            } else {
                $results = UserCampaignHistoryModel::selectRaw('MONTH(updated_at) as month')
                    ->selectRaw('(SELECT COUNT(id) FROM user_campaign_history WHERE campaign_id = ' . $request->title . ' AND status = 3 AND MONTH(updated_at) = MONTH(updated_at)) as total_completed')
                    ->selectRaw('(SELECT COUNT(id) FROM user_campaign_history WHERE campaign_id = ' . $request->title . ' AND status = 1 AND MONTH(updated_at) = MONTH(updated_at)) as total_joined')
                    ->whereYear('updated_at', $request->year)
                    ->groupBy(DB::raw('MONTH(updated_at)'))
                    ->get();
                foreach ($results as $item) {
                    $data[] = [
                        "label" => Carbon::create()->month($item['month'])->format('F'), // Format the day of the month
                        "total_completed" => $item['total_completed'],
                        "total_joined" => $item['total_joined']

                    ];
                }
            }
            return response()->json($data);
        } catch (Exception $e) {
            Log::error('CampaignController::CompanyCustom => ' . $e->getMessage());
            return response()->json(['Error : ' . $e->getMessage()]);
        }
    }

    public function getSocialAnalytics(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();

            if ($request->ajax()) {
                $columns = ['id', 'title', 'social_task_user_count'];
                $draw = $request->input('draw');
                $start = $request->input('start');
                $length = $request->input('length');
                $order = $request->input('order.0.column');
                $dir = $request->input('order.0.dir');
                $searchValue = $request->input('search.value');

                // CampaignModel::where('company_id', $companyId)->where('type', $type)->count();
                // (SELECT count(*) as total FROM campaign where campaign.id=user_campaign_history.campaign_id) as total

                // $query = CampaignModel::select(['id', 'title'])->where('type', '2')->where('company_id', $companyId)->whereDate('created_at', '>=' , date('Y-m-d', strtotime($request->from_date)))->whereDate('created_at', '<=' , date('Y-m-d', strtotime($request->to_date)));
                $query = UserCampaignHistoryModel::join('campaign', 'user_campaign_history.campaign_id', '=', 'campaign.id')
                    ->select(
                        'campaign.id',
                        'campaign.title',
                        DB::raw('(SELECT COUNT(*) FROM campaign WHERE campaign.id = user_campaign_history.campaign_id) as total')
                    )->where('campaign.type', '2')->where('user_campaign_history.status', 3)->where('campaign.company_id', $companyId)->whereDate('user_campaign_history.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))->whereDate('user_campaign_history.created_at', '<=', date('Y-m-d', strtotime($request->to_date)));


                if (!empty($searchValue)) {
                    $query->where(function ($query) use ($searchValue) {
                        $query->where('campaign.id', 'like', '%' . $searchValue . '%')
                            ->orWhere('campaign.title', 'like', '%' . $searchValue . '%');
                    });
                }
                $recordsTotal = $query->count();

                $query->orderBy($columns[$order], $dir)->skip($start)->take($length);

                $userCounts = $query->get();
                $data = [];
                foreach ($userCounts as $item) {
                    $data[] = [
                        $item->id, // Format the day of the month
                        $item->title, // Format the day of the month
                        $item->total,
                    ];
                }
                return response()->json([
                    'draw' => (int)$draw,
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => $recordsTotal,
                    'data' => $data,
                ]);
            }
        } catch (Exception $e) {
            Log::error('CampaignController::Statuswiselist  => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }
}

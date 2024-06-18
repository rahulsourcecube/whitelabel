<?php

namespace App\Http\Controllers\Company;

use App\Exports\Export;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\Feedback;
use App\Models\MailTemplate;
use App\Models\Notification;
use App\Models\NotificationsQue;
use App\Models\ratings;
use App\Models\Referral;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\StateModel;
use App\Models\TaskEvidence;
use App\Models\TaskProgression;
use App\Models\taskProgressionUserHistory;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use App\Services\PlivoService;
use App\Services\TwilioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Mail;


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

            $searchColumn = ['title', 'priority', 'public', 'reward', 'description', 'no_of_referral_users'];

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
                $priority = "-";
                switch ($result->priority) {
                    case 1:
                        $priority = "<span class=' text-danger'>High</span>";
                        break;
                    case 2:
                        $priority = "<span class=' text-info'>Medium</span>";
                        break;
                    case 3:
                        $priority = "<span class=' text-success'>Low</span>";
                        break;
                }

                $public = $result->public ? "Yes" : "No";

                $list[] = [
                    base64_encode($result->id),
                    $result->title ?? "-",
                    $result->text_reward ? Str::limit($result->text_reward, 15) : Helper::getcurrency() . ($result->reward ?? "0"),
                    Str::limit($result->description, 40) ?? "-",
                    $result->task_type,
                    $result->no_of_referral_users,
                    $result->task_status,
                    $result->campaignUSerHistoryData->count() == 0,
                    $priority, // Priority value
                    $public, // Public value
                    $result->Notifications ?? "-",

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
                    $result->text_reward ? Str::limit($result->text_reward, 15) : Helper::getcurrency() . ($result->reward ?? "0"),
                    Helper::Dateformat($result->created_at)  ?? "-",
                    $result->TaskStatus ?? "-",
                    base64_encode($result->user_id) ?? "-",
                    $result->notifications_type ?? "-",

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
            $companyId = Helper::getCompanyId();
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }
            $country_data = CountryModel::all();
            $typeInText = $type;
            $type = CampaignModel::TYPE[strtoupper($type)];
            $mail = SettingModel::where('user_id', $companyId)->first();
            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'new_task')->first();
            $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'earn_reward')->first();
            return view('company.campaign.create', compact('type', 'typeInText', 'country_data', 'mail', 'mailTemplate', 'smsTemplate'));
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
                'reward' => 'required_without:text_reward',
                'text_reward' => 'required_without:reward',
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
            $Campaign->reward = $request->reward ?? 0;
            $Campaign->text_reward = $request->text_reward;
            $Campaign->no_of_referral_users = $request->no_of_referral_users;
            $Campaign->description = $request->description;
            $Campaign->priority = $request->priority;
            $Campaign->public = $request->has('public') ? 1 : 0;
            // $Campaign->public = $request->public;
            $Campaign->expiry_date = $request->expiry_date;
            $Campaign->type = $request->type;
            $Campaign->image = $image;
            $Campaign->company_id = $companyId;
            $Campaign->status = !empty($request->status) ? '1' : "0";
            $Campaign->package_id = $ActivePackageData->id;
            $Campaign->feedback_type = $request->feedback_type;
            $Campaign->referral_url_segment = $request->referral_url;

            $Campaign->country_id = $request->country;
            $Campaign->state_id = $request->state;
            $Campaign->city_id = $request->city;
            // Notifications type
            $Campaign->notifications_type = $request->notifications_type;

            $Campaign->save();
            if (!empty($Campaign) && !empty($Campaign->notifications_type)) {
                $userDatas = User::where('user_type', User::USER_TYPE['USER'])
                    ->where('status', '1')
                    ->where('company_id', $companyId)
                    ->cursor();

                if (!$userDatas->isEmpty()) {
                    $notificationsQueBatch = [];

                    foreach ($userDatas as $userData) {
                        $notificationsQueBatch[] = [
                            'company_id' => $companyId,
                            'user_id' => $userData->id,
                            'campaign_id' => $Campaign->id,
                            'notifications_type' => $Campaign->notifications_type,
                            'created_at' => now(),
                        ];

                        // Check if the batch size exceeds a certain limit (e.g., 1000 records)
                        if (count($notificationsQueBatch) >= 1000) {
                            NotificationsQue::insert($notificationsQueBatch);
                            $notificationsQueBatch = []; // Reset the batch array
                        }
                    }

                    // Insert any remaining records
                    if (!empty($notificationsQueBatch)) {
                        NotificationsQue::insert($notificationsQueBatch);
                    }
                }
            }


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
                'reward' => 'required_without:text_reward',
                'text_reward' => 'required_without:reward',
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
            $Campaign->reward = $request->reward ?? 0;
            $Campaign->text_reward = $request->text_reward;
            $Campaign->no_of_referral_users = $request->no_of_referral_users;
            $Campaign->description = $request->description;
            $Campaign->priority = $request->priority;
            $Campaign->public = $request->has('public') ? 1 : 0;
            $Campaign->expiry_date = $request->expiry_date;
            $Campaign->type = $request->type;
            $Campaign->image = $image;
            $Campaign->company_id = $companyId;
            $Campaign->status = !empty($request->status) ? '1' : '0';
            $Campaign->feedback_type = $request->feedback_type;
            $Campaign->referral_url_segment = $request->referral_url;

            $Campaign->country_id = $request->country;
            $Campaign->state_id = $request->state;
            $Campaign->city_id = $request->city;
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
                ->select(DB::raw('COUNT(uch.user_id) as total_user , DATE_FORMAT(uch.created_at, "%a") as day'))
                ->groupBy('day')
                ->get();
            $start_time = strtotime(date("Y-m-d", strtotime("-6 day")));
            $end_time = strtotime(date("Y-m-d"));

            $list = [];
            for ($i = $start_time; $i <= $end_time; $i += 86400) {
                $list[date('D', $i)] = 0;
            }

            foreach ($total_join_users as $values) {
                $list[$values->day] = $values->total_user;
            }
            $user_total = json_encode(['day' => array_keys($list), 'total_user' => array_values($list)]);
            $customTasks = CampaignModel::where('company_id', $companyId)->where('type', 3)->get();

            $startDate =  date("m/d/Y", $start_time);
            $endDate =  date("m/d/Y", $end_time);

            return view('company.campaign.analytics', compact('user_total', 'customTasks', 'startDate', 'endDate'));
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

                    // dd($from_date, $to_date);
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
                        ->select(DB::raw('COUNT(uch.user_id) as total_user , DATE_FORMAT(uch.created_at, "%a") as day'))
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

            $state_data = "";
            $city_data = "";

            $companyId = Helper::getCompanyId();
            $type = CampaignModel::TYPE[strtoupper($type)];
            $taskId = base64_decode($id);
            $task = CampaignModel::where('id', $taskId)->where('company_id', $companyId)->where('type', $type)->first();
            $country_data = CountryModel::all();
            $state_data = StateModel::where('country_id', $task->country_id)->get();
            $city_data = CityModel::where('state_id', $task->state_id)->get();
            if (empty($task)) {
                return back()->with('error', 'Task not found');
            }
            return view('company.campaign.edit', compact('type', 'taskId', 'task', 'country_data', 'state_data', 'city_data'));
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
            // $Notifications=NotificationsQue::insert($notificationsQueBatch);
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
            $companyId = Helper::getCompanyId();
            $action = UserCampaignHistoryModel::where('id', $id)->first();
            $Notification = new Notification();
            $ActivePackageData = Helper::GetActivePackageData();
            if ($request->action == '3') {


                $action->status = '3';
                $action->save();
                if (!empty($action)) {
                    $batch = UserCampaignHistoryModel::where('user_id', $action->user_id)->where('status', 3)->get()->count();

                    $progression = [];
                    if (!empty($batch)) {
                        $progression = TaskProgression::where('company_id', $companyId)->where('no_of_task', $batch)->first();
                        $taskProgressionUserHistory = taskProgressionUserHistory::where('company_id', $companyId)->where('user_id', $action->user_id)->where('no_of_task', $batch)->first();
                        if (!empty($progression) && empty($taskProgressionUserHistory)) {
                            $taskProgressionUserHistoryStore = new taskProgressionUserHistory();
                            $taskProgressionUserHistoryStore->company_id = $companyId;
                            $taskProgressionUserHistoryStore->user_id = $action->user_id;
                            $taskProgressionUserHistoryStore->no_of_task = $progression->no_of_task;
                            $taskProgressionUserHistoryStore->progression_id = $progression->id;
                            $taskProgressionUserHistoryStore->save();
                        }
                    }
                }
                if (isset($action)) {

                    $Notification->user_id =  $action->user_id;
                    $Notification->company_id =  $action->campaign_id;
                    $Notification->title =  " Campaign approved ";
                    $Notification->message =  $action->getCampaign->title . " approved.";
                    $Notification->type =  "1";
                    $Notification->save();

                    $webUrlGetHost = $request->getHost();
                    $currentUrl = URL::current();
                    if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                        // URL is under HTTPS
                        $webUrl =  'https://' . $webUrlGetHost;
                    } else {
                        // URL is under HTTP
                        $webUrl =  'http://' . $webUrlGetHost;
                    }

                    //Start Mail
                    try {
                        $mailTemplate = MailTemplate::where('company_id', $companyId)->where('template_type', 'earn_reward')->first();
                        $userDetails = User::where('id', $action->user_id)->where('company_id', $companyId)->first();
                        if (!empty($userDetails) && !empty($mailTemplate) && !empty($mailTemplate->template_html) && $ActivePackageData->mail_temp_status == "1") {
                            $userName  = $userDetails->FullName;
                            $campaign_title  = $action->getCampaign->title;
                            $campaign_price = $action->text_reward ? 'text_reward' : $action->reward;
                            $to = $userDetails->email;
                            $message = '';

                            $html =  $mailTemplate->template_html;

                            $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'earn_reward';
                            Mail::send('user.email.earnReward', [
                                'name' => $userName,
                                'company_id' => $companyId,
                                'template' => $html,
                                'webUrl' => $webUrl,
                                'campaign_title' => $campaign_title,
                                'campaign_price' => $campaign_price,
                            ], function ($message) use ($to, $mailTemplateSubject) {
                                $message->to($to);
                                $message->subject($mailTemplateSubject);
                            });
                        }
                    } catch (Exception $e) {
                        Log::error('CampaignController::Action => ' . $e->getMessage());
                    }
                    // // End mail


                    $smsTemplate = SmsTemplate::where('company_id', $companyId)->where('template_type', 'earn_reward')->first();

                    if (!empty($smsTemplate) && $ActivePackageData->sms_temp_status == "1") {
                        // $SettingModel = SettingModel::first();
                        if (!empty($companyId)) {
                            $SettingModel = SettingModel::where('user_id', $companyId)->first();
                        }
                        if (!empty($SettingModel) && (Helper::activeTwilioSetting() == true || Helper::activePlivoSetting() == true)) {
                            $name =  $userDetails->FullName;
                            $contact_number =  $userDetails->contact_number;
                            $company_title = !empty($SettingModel) && !empty($SettingModel->title) ? $SettingModel->title : 'Referdio';
                            $company_link = $webUrl ? $webUrl : '';
                            $campaign_title = $action->getCampaign->title;
                            $campaign_price = !empty($action->text_reward) ? $action->text_reward : $action->reward;
                            $html = str_replace(["[user_name]", "[company_title]", "[company_web_link]", "[campaign_title]", "[campaign_price]"], [$name, $company_title, $company_link, $campaign_title, $campaign_price], $smsTemplate->template_html_sms);

                            // Remove HTML tags and decode HTML entities
                            $message = htmlspecialchars_decode(strip_tags($html));

                            // Remove unwanted '&nbsp;' text
                            $message = str_replace('&nbsp;', ' ', $message);

                            try {
                                if (Helper::activeTwilioSetting()) {
                                    $to = $SettingModel->sms_mode == "2" ? $contact_number : $SettingModel->sms_account_to_number;
                                    $twilioService = new TwilioService($SettingModel->sms_account_sid, $SettingModel->sms_account_token, $SettingModel->sms_account_number);
                                    $twilioService->sendSMS($to, $message);
                                } else {
                                    $to = $SettingModel->plivo_mode == "2" ? $contact_number : $SettingModel->plivo_test_phone_number;

                                    $PlivoService = new PlivoService($SettingModel->plivo_auth_id, $SettingModel->plivo_auth_token, $SettingModel->plivo_phone_number);
                                    $PlivoService->sendSMS($to, $message);
                                }
                            } catch (Exception $e) {
                                Log::error('Failed to send SMS: ' . $e->getMessage());
                                echo "Failed to send SMS: " . $e->getMessage();
                            }
                        }
                    }
                    // End sms
                }

                return response()->json(['success' => 'success', 'messages' => ' Task approved successfully']);
            } else {
                $action->status = '4';
                $action->save();
                if (isset($action)) {
                    $Notification->user_id =  $action->user_id;
                    $Notification->company_id =  $action->campaign_id;
                    $Notification->title =  " Campaign rejected";
                    $Notification->message =  $action->getCampaign->title . " rejected.";
                    $Notification->type =  "1";

                    $Notification->save();
                }
                return response()->json(['success' => 'success', 'messages' => ' Task rejected successfully']);
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
            $ratings = ratings::where('campaign_id', $camphistory->campaign_id)->where('user_id', $camphistory->user_id)->first();
            $feedback = Feedback::where('campaign_id', $camphistory->campaign_id)->where('user_id', $camphistory->user_id)->first();


            if (empty($user)) {
                return redirect()->back()->with('error', 'User not found');
            }
            $chats = TaskEvidence::where('campaign_id', $id)->where('company_id', $companyId)->get();
            return view('company.campaign.user-details', compact('chats', 'setting', 'user', 'camphistory', 'referral_user_detail', 'id', 'ratings', 'feedback'));
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
                TaskEvidence::where('campaign_id', $id->id)->where('user_id', $id->user_id)->where('company_id', $id->getCampaign->company_id)->get();

                if ($request->hasFile('image')) {
                    $sentMessage = ' sent file...';
                } else {
                    $sentMessage = ' sent a message ' . ' ' .  Str::limit($request->chat_input, 10) ?? "-";
                }

                $Notification = new Notification();
                if (auth()->user()->user_type == '4') {
                    $Notification->user_id =  $id->user_id;
                    $Notification->company_id =  $id->getCampaign->company_id;
                    $Notification->type =  '2';
                    $Notification->title =  "User send message";
                    $Notification->message =  $id->getuser->FullName . ' ' . $sentMessage;
                    $Notification->save();
                } else {
                    $Notification->user_id =  $id->user_id;
                    $Notification->company_id =  $id->campaign_id;
                    $Notification->title =  "Company send message";
                    $Notification->message = "New message for the task " . $id->getCampaign->title ?? "-";;
                    $Notification->type =  "1";
                    $Notification->save();
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

                $query = UserCampaignHistoryModel::join('campaign', 'user_campaign_history.campaign_id', '=', 'campaign.id')
                    ->select(
                        'campaign.id',
                        'campaign.title',
                        DB::raw('COUNT(user_campaign_history.id) as total')
                    )->where('campaign.type', '2')->where('user_campaign_history.status', 3)->where('campaign.company_id', $companyId)->whereDate('user_campaign_history.created_at', '>=', date('Y-m-d', strtotime($request->from_date)))->whereDate('user_campaign_history.created_at', '<=', date('Y-m-d', strtotime($request->to_date)));


                if (!empty($searchValue)) {
                    $query->where(function ($query) use ($searchValue) {
                        $query->where('campaign.id', 'like', '%' . $searchValue . '%')
                            ->orWhere('campaign.title', 'like', '%' . $searchValue . '%');
                    });
                }
                $query->groupBy('campaign.id', 'campaign.title');
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
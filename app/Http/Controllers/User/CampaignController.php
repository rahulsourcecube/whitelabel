<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\ratings;
use App\Models\Referral;
use App\Models\StateModel;
use App\Models\TaskEvidence;
use App\Models\User;
use App\Models\UserCampaignHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class CampaignController extends Controller
{
    function campaign()
    {
        $compact['countrys'] = CountryModel::all();
        $compact['states'] = StateModel::all();
        $compact['citys'] = CityModel::all();
        return view('user.campaign.list',$compact);
    }
    function dtlist(Request $request)
    {
        
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id', 'title'];
            $totalData = CampaignModel::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];

            $searchColumn = ['title', 'reward', 'description'];
        
            $query = CampaignModel::orderByRaw('CASE WHEN priority = 1 THEN 1 WHEN priority = 2 THEN 2 ELSE 3 END ASC')
            ->orderBy($columns[$order], $dir)            
            ->where('company_id', $companyId)
            ->where('status', '1')
            ->whereNotExists(function ($query) {
                $query->from('user_campaign_history')
                    ->whereRaw('campaign.id = user_campaign_history.campaign_id')
                    ->where('user_campaign_history.user_id', Auth::user()->id);
            })
            ->whereDate('expiry_date', '>=', now());
        
        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $searchColumn) {
                foreach ($searchColumn as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        if (!empty($request->country)) {
            $query->where('country_id', $request->country);
        }
        if (!empty($request->state)) {
            $query->where('state_id', $request->state);
        }
        if (!empty($request->city)) {
            $query->where('city_id', $request->city);
        }
        $totalData = $query->count();
        $results = $query->skip($start)
            ->take($length)
            ->select('campaign.*')
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
                    $result->task_type ?? "_",
                    $result->status ?? "_",
                    $priority, // Priority value
                    $public // Public value,
                ];
            }
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => isset($totalData) ? $totalData : '',
                "recordsFiltered" => isset($totalData) ? $totalData : '',
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('CampaignController::Dtlist => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" =>  [],
            ]);
        }
    }

    function campaignview(Request $request)
    {
        try {         
          
            $campagin_id = base64_decode($request->id);
            $companyId = Helper::getCompanyId();
            $data = [];
            $data['chats'] = null;
            $data['user'] = null;
            $data['ReferralCount'] = 0;
            $data['user_Campaign'] = null;
            $data['campagin_detail'] = CampaignModel::where('id', $campagin_id)->where('company_id', $companyId)->first();

            if (empty($data['campagin_detail'])) {
                return redirect()->back()->with('error', "Campaign not found.");
            }

            $data['user_detail'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)
                ->whereExists(function ($query) {
                    $query->from('users')
                        ->whereRaw('user_campaign_history.user_id = users.id')
                        ->where('users.referral_user_id', Auth::user()->id)
                        ->whereNotNull('users.referral_user_id');
                })
                ->orderBy('user_id', 'desc')->get();


            $data['user_Campaign'] = UserCampaignHistoryModel::where('campaign_id', $campagin_id)->where('user_id', Auth::user()->id)->first();
            $data['ratings'] = ratings::where('campaign_id', $campagin_id)->where('user_id', Auth::user()->id)->first();
            $data['feedback'] = Feedback::where('campaign_id', $campagin_id)->where('user_id', Auth::user()->id)->first();
           
          


            if ($data['user_Campaign'] != null) {
                $data['chats'] = TaskEvidence::where('campaign_id', $data['user_Campaign']->id)->where('company_id', $companyId)->get();
                $data['user'] = User::where('id', $data['user_Campaign']->user_id)->where('company_id', $companyId)->first();
                $query = Referral::where('campagin_id', $data['user_Campaign']->campaign_id)->where('referral_user_id', Auth::user()->id);
                $data['ReferralCount'] = $query->count();
            }


            $data['referral_user_detail'] = Referral::where('campagin_id', $campagin_id)->where('referral_user_id', Auth::user()->id)->get();
            return view('user.campaign.view', $data);
        } catch (Exception $e) {
            Log::error('CampaignController::Campaignview => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function GetReferralUserDetail(Request $request)
    {
        try {
            if ($request->ajax() && $request->campagin_id != null) {
                $draw = $request->input('draw');


                $query = Referral::where('campagin_id', $request->campagin_id)->where('referral_user_id', Auth::user()->id);
                $recordsTotal = $query->count();

                $userCounts = $query->get();
                $data = [];

                foreach ($userCounts as $item) {
                    $data[] = [
                        "User" => isset($item->getuser->first_name) ? $item->getuser->first_name : '',
                        "Reward" => Str::limit($item->text_reward, 15) ? $item->text_reward : Helper::getcurrency() . ($item->reward ?? '0'),
                        "Date" => Helper::Dateformat($item->created_at) ?? ''
                    ];
                }
                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => $recordsTotal,
                    'data' => $data,
                ]);
            }
        } catch (Exception $e) {
            Log::error('CampaignController::ReferralUserDetail => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" =>  [],
            ]);
        }
    }
    function referral($referral_link)
    {
        try {
            if (!(Auth::user())) {
                session()->put('referral_link', route('campaign.referral', $referral_link));
                return redirect()->route('user.login');
            }
            session()->put('referral_link', route('campaign.referral', $referral_link));
            $UserCampaign = UserCampaignHistoryModel::where('referral_link', $referral_link)->first();
            $ReferralCount = Referral::where('campagin_id', $UserCampaign->campagin_id)->where('referral_user_id', Auth::user()->id)->count();
            if (isset($UserCampaign) && $UserCampaign != null) {
                $campagin_id = base64_encode($UserCampaign->campaign_id);
                $ReferralUser = Referral::where('campagin_id', $UserCampaign->campaign_id)->where('user_id', Auth::user()->id)->exists();
            }
            if (isset($UserCampaign) && $UserCampaign != null  && $ReferralUser == false && Auth::user()->company_id == $UserCampaign->getCampaign->company_id && $ReferralCount >= $UserCampaign->no_of_referral_users) {
                return redirect()->route('user.campaign.view', $campagin_id);
            } else {
                return redirect()->route('user.campaign.view', $campagin_id)->with('error', 'Referral link is expired.');
            }
            return redirect()->route('user.campaign.view', $campagin_id)->with('error', 'Referral link is expired.');
        } catch (Exception $e) {
            Log::error('CampaignController::Referral => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function getusercampaign(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $token = Str::random(10);
            $campagin_id = base64_decode($request->id);

            $getcampaign = CampaignModel::where('id', $campagin_id)->where('company_id', $companyId)->first();

            $input = new UserCampaignHistoryModel;
            $input->campaign_id = isset($getcampaign->id) ? $getcampaign->id : '';
            $input->user_id = Auth::user()->id;
            $input->reward = ($getcampaign->reward ?: 0);
            $input->text_reward = $getcampaign->text_reward;

            if ($getcampaign->type == 1) {
                $input->reward = 0;
                $input->text_reward = '';
                $input->referral_link = $token;
                $input->no_of_referral_users = $getcampaign->no_of_referral_users;
            }

            $input->status = 1;
            $input->verified_by = 0;

            $input->save();

            if (Session('referral_link') != null) {
                $referral_link = Session('referral_link');
                $lastSegment = Str::of($referral_link)->afterLast('/'); //referral_link
                $UserCampaign = UserCampaignHistoryModel::where('referral_link', $lastSegment->value)->first();
                if ($UserCampaign->campaign_id == $getcampaign->id) {
                    if (isset($UserCampaign) && $UserCampaign != null) {
                        $campagin_id = base64_encode($UserCampaign->campaign_id);
                        $ReferralUser = Referral::where('campagin_id', $UserCampaign->campaign_id)->where('user_id', Auth::user()->id)->exists();
                    }
                    if (isset($UserCampaign) && $UserCampaign != null && $ReferralUser == false && Auth::user()->company_id == $UserCampaign->getCampaign->company_id) {
                        $Referral = new Referral;
                        $Referral->referral_user_id = $UserCampaign->user_id;
                        $Referral->user_id = Auth::user()->id;
                        $Referral->campagin_id = $UserCampaign->campaign_id;
                        $Referral->reward = isset($UserCampaign->getCampaign->reward) ? $UserCampaign->getCampaign->reward : '0';
                        $Referral->text_reward = isset($UserCampaign->getCampaign->text_reward) ? $UserCampaign->getCampaign->text_reward : '';
                        $Referral->ip = IpRequest::ip();
                        $Referral->save();
                        // $UserCampaign->reward = $UserCampaign->reward + $UserCampaign->getCampaign->reward;
                        // $UserCampaign->save();
                        return redirect()->route('user.campaign.view', $campagin_id);
                    }
                }
                session()->forget('referral_link');
            }
            return $input;
        } catch (Exception $e) {
            Log::error('CampaignController::Usercampaign => ' . $e->getMessage());
            return $input = "";
        }
    }
    function requestSend(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $id = base64_decode($request->id);

            $input = new UserCampaignHistoryModel;
            $input = UserCampaignHistoryModel::where('id', $id)->first();

            $CampaignModel = CampaignModel::where('id', $input['campaign_id'])->where('company_id', $companyId)->first();

            $input->status = 2;
            $input->verified_by = 0;
            $input->reward = $CampaignModel->reward ?? 0;
            $input->text_reward = $CampaignModel->text_reward;

            if (isset($input)) {
                $Notification = new Notification();
                $Notification->user_id =  $CampaignModel->user_id;
                $Notification->company_id =  $companyId;
                $Notification->type =  '2';
                $Notification->title =  " Campaign approval request";
                $Notification->message =  $CampaignModel->title . " approval request by " . $input->getuser->FullName;
                $Notification->save();
                //store in chat
                $TaskEvidence = new TaskEvidence();
                $TaskEvidence->user_id = Auth::user()->id;
                $TaskEvidence->company_id =  $companyId;
                $TaskEvidence->campaign_id = $id;
                $TaskEvidence->sender_id = Auth::user()->id;
                $TaskEvidence->message = "Task completed";
                $TaskEvidence->save();
            }

            // $UserCampaign->reward = $UserCampaign->reward + $UserCampaign->getCampaign->reward;
            // $UserCampaign->save();
            $input->save();

            return $input;
        } catch (Exception $e) {
            Log::error('CampaignController::requestSend => ' . $e->getMessage());
            return $input = "";
        }
    }
    function reopenSend(Request $request)
    {


        $companyId = Helper::getCompanyId();
        $id = base64_decode($request->id);



        $input = new UserCampaignHistoryModel;
        $input = UserCampaignHistoryModel::where('id', $id)->first();

        $CampaignModel = CampaignModel::where('id', $input['campaign_id'])->where('company_id', $companyId)->first();


        $input->status = 5;
        $input->verified_by = 0;
        $input->reward = $CampaignModel->reward ?? 0;
        $input->text_reward = $CampaignModel->text_reward;

        if (isset($input)) {
            $Notification = new Notification();
            $Notification->user_id =  $CampaignModel->user_id;
            $Notification->company_id =  $companyId;
            $Notification->type =  '2';
            $Notification->title =  " Campaign Reopen request";
            $Notification->message =  $CampaignModel->title . " approval Reopen by " . $input->getuser->FullName;
            $Notification->save();
        }

        // $UserCampaign->reward = $UserCampaign->reward + $UserCampaign->getCampaign->reward;
        // $UserCampaign->save();
        $input->save();


        return $input;
    }
}

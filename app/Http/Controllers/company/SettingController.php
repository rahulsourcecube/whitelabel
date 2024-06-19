<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use App\Models\SettingModel;
use App\Models\TaskProgression;
use App\Models\taskProgressionUserHistory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDO;
use Spatie\Permission\Models\Role;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        // check user permission
        $this->middleware('permission:general-setting-list', ['only' => ['index']]);
        $this->middleware('permission:general-setting-create', ['only' => ['store']]);;
    }

    function index()
    {
        try {
            $companyId = Helper::getCompanyId();
            $data['setting'] = SettingModel::where('user_id', $companyId)->first();
            $data['companyname'] = CompanyModel::where('user_id', $companyId)->first();
            return view('company.setting.setting', $data);
        } catch (Exception $e) {
            Log::error('SettingController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function store(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();

            $SettingModel = SettingModel::where('user_id', $companyId)->first();
            if ($SettingModel->user_id) {
                if (empty($SettingModel)) {
                    $SettingModel = new SettingModel;
                }
                //Update Favicon
                if ($request->hasFile('logo')) {
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    $randomNumber = rand(111111, 999999);
                    $timestamp = time();
                    $logo = $timestamp . '_' . $randomNumber . '.' . $extension;
                    $request->file('logo')->move(base_path('uploads/setting'), $logo);
                    if (!empty($SettingModel->logo)) {
                        $oldlogo = 'uploads/setting/' . $SettingModel->logo;
                        if (file_exists($oldlogo)) {
                            unlink($oldlogo);
                        }
                    }
                    // Save the logo path to the database
                    $SettingModel->logo = $logo;
                }
                //Update Favicon
                if ($request->hasFile('favicon')) {
                    if (!empty($SettingModel->favicon)) {
                        $oldFaviconPath = 'uploads/setting/' . $SettingModel->favicon;
                        // Delete the old favicon if it exists
                        if (file_exists($oldFaviconPath)) {
                            unlink($oldFaviconPath);
                        }
                    }
                    $extension = $request->file('favicon')->getClientOriginalExtension();
                    $randomNumber = rand(1000, 9999);
                    $timestamp = time();
                    $favicon_img = $timestamp . '_' . $randomNumber . '.' . $extension;
                    $request->file('favicon')->move(base_path('uploads/setting'), $favicon_img);
                    $SettingModel->favicon = $favicon_img;
                }
                $SettingModel->title = $request->title;
                $SettingModel->email = $request->email;
                $SettingModel->contact_number = $request->contact_number;
                $SettingModel->description = $request->description;
                $SettingModel->facebook_link = $request->facebook_link;
                $SettingModel->twitter_link = $request->twitter_link;
                $SettingModel->linkedin_link = $request->linkedin_link;
                $SettingModel->logo_link = $request->logo_link;
                //Mail Credentials
                $SettingModel->mail_mailer = $request->mail_mailer;
                $SettingModel->mail_host = $request->mail_host;
                $SettingModel->mail_port = $request->mail_port;
                $SettingModel->mail_username = $request->mail_username;
                $SettingModel->mail_password = $request->mail_password;
                $SettingModel->mail_encryption = $request->mail_encryption;
                $SettingModel->mail_address = $request->mail_address;
                //Sms tipe
                $SettingModel->sms_type = $request->sms_type == 'true' ? '2' : '1';

                //Sms twilio
                $SettingModel->sms_account_sid = $request->sms_account_sid;
                $SettingModel->sms_account_token = $request->sms_account_token;
                $SettingModel->sms_account_number = $request->sms_account_number;
                $SettingModel->sms_account_to_number = $request->sms_account_to_number;
                $SettingModel->sms_mode = $request->sms_mode;

                //Sms plivo

                $SettingModel->plivo_auth_id = $request->plivo_auth_id;
                $SettingModel->plivo_auth_token = $request->plivo_auth_token;
                $SettingModel->plivo_phone_number = $request->plivo_phone_number;
                $SettingModel->plivo_test_phone_number = $request->plivo_test_phone_number;
                $SettingModel->plivo_mode = $request->plivo_mode;


                $SettingModel->save();
                return redirect()->route('company.setting.index')->with('success', 'Setting Update successfully');
            }
        } catch (Exception $e) {
            Log::error('SettingController::Store => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function progressionIndex()
    {
        try {
            $companyId = Helper::getCompanyId();
            return view('company.setting.progressionIndex');
        } catch (Exception $e) {
            Log::error('SettingController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function progressionList(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id'];
            $totalData = TaskProgression::where('company_id', Auth::user()->id)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['first_name', 'last_name', 'email', 'full_name'];
            $query = TaskProgression::where('company_id', Auth::user()->id)->orderBy($columns[0], $dir);

            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        if ($column == 'full_name') {
                            $query->orWhere(DB::raw('concat(title)'), 'like', "%{$search}%");
                        } else {
                            $query->orWhere("$column", 'like', "%{$search}%");
                        }
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
                    $result->no_of_task ?? "-",
                    $result->image ?? "-",

                ];
            }
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('SettingController::Elist  => ' . $e->getMessage());
            return response()->json([
                "draw" => 0,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }
    function progressionCreate()
    {
        try {
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }
            $companyId = Helper::getCompanyId();

            return view('company.setting.progressionCreate');
        } catch (Exception $e) {
            Log::error('SettingController::progressionCreate => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function progressionStore(Request $request)
    {

        try {

            $companyId = Helper::getCompanyId();
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'no_of_task' => 'required|string|max:255'

            ]);
            $progressionNoOfTask = TaskProgression::where('company_id', $companyId)->where('id', '!=', base64_decode($request->id))->where('no_of_task', $request->no_of_task)->first();
            $progression = TaskProgression::where('id', base64_decode($request->id))->first();
            if ($progressionNoOfTask) {

                return redirect()->back()->with('error', 'No of task is already added')->withInput();
            }

            if (!empty($progression)) {

                if ($request->hasFile('image')) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $randomNumber = rand(1000, 9999);
                    $timestamp = time();
                    $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                    $request->file('image')->move(base_path('uploads/company/progression/'), $image);
                    if (!empty($progression->image)) {
                        $oldImagePath = base_path() . '/uploads/company/progression/' . $progression->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                } else {
                    $image = $progression->image;
                }
                //  $progression = new TaskProgression();
                $progression->title = $request->title;
                $progression->company_id = $companyId;
                $progression->no_of_task = $request->no_of_task;
                $progression->image = $image;

                $progression->save();
                $message = "Task Progression Update successfuly.";
            } else {


                if ($request->hasFile('image')) {
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $randomNumber = rand(1000, 9999);
                    $timestamp = time();
                    $image = $timestamp . '_' . $randomNumber . '.' . $extension;
                    $request->file('image')->move(base_path('uploads/company/progression/'), $image);
                }
                $progression = new TaskProgression();
                $progression->title = $request->title;
                $progression->company_id = $companyId;
                $progression->no_of_task = $request->no_of_task;
                $progression->image = $image;

                $progression->save();

                $message = "Task Progression added successfuly.";
            }
            return redirect()->route('company.progression.index')->with('success', $message);
        } catch (Exception $e) {
            Log::error('SettingController::progressionStore => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function progressionedit($id)
    {
        try {
            $isActivePackageAccess = Helper::isActivePackageAccess();

            if (!$isActivePackageAccess) {
                return redirect()->back()->with('error', 'your package expired. Please buy the package.')->withInput();
            }
            $companyId = Helper::getCompanyId();
            $progression = TaskProgression::where('id', base64_decode($id))->where('company_id', $companyId)->first();
            if (empty($progression)) {
                return redirect()->back()->with('error', 'No Found progression ')->withInput();
            }

            return view('company.setting.progressionCreate', compact('progression'));
        } catch (Exception $e) {
            Log::error('SettingController::progressionCreate => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function progressionDelete($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $id = base64_decode($id);
            $taskProgressionUserHistory = taskProgressionUserHistory::where('progression_id', $id)->first();
            if (!empty($taskProgressionUserHistory)) {
                return response()->json(['error' => true, 'message' => 'You cannot delete these record']);
            }
            $taskProgression = TaskProgression::where('id', $id)->where('company_id', $companyId)->first();
            if (!empty($taskProgression->image)) {
                $oldImagePath = base_path() . '/uploads/company/progression/' . $taskProgression->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $taskProgression = TaskProgression::where('id', $id)->where('company_id', $companyId)->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        } catch (Exception $e) {
            Log::error('SettingController::Delete  => ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error : ' . $e->getMessage()]);
        }
    }
    public function sendMail()
    {
    }
}

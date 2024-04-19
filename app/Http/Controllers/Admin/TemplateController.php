<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use App\Models\SmsTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    function index()
    {
        try {
           

            return view('admin.mailTemplate.list');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function list(Request $request)
    {
        try {
           
            $companyId = auth()->user()->id;
            
            $columns = ['id'];
            $totalData = MailTemplate::where('company_id', $companyId)->count();
            
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['template_html'];
            $query = MailTemplate::orderBy($columns[0], $dir);

            $query->where('company_id', $companyId);
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
                if ($result->template_type == 'welcome') {
                    $type = 'Welcome';
                } elseif ($result->template_type == 'forgot_password') {
                    $type = 'Forgot Password';
                } elseif ($result->template_type == 'change_pass') {
                    $type = 'Change Password';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,

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
    function create()
    {
        try {
            return view('admin.mailTemplate.create');
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function edit($id)
    {
        try {
            $companyId = auth()->user()->id;
            $mailTemplate = MailTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            return view('admin.mailTemplate.create', compact('mailTemplate'));
        } catch (Exception $e) {
            Log::error('MailtemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $companyId = auth()->user()->id;
            $mailTemplate = MailTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();
              
            
            if (empty($mailTemplate) || empty($request->id)) {
               
                $existingTemplate = MailTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->with('error', 'Template already exit ' . $request->type );
                }
                
                $mailTemplate = new MailTemplate;
                $mailTemplate->template_type = $request->type;
            }

            $mailTemplate->company_id = $companyId;

            $mailTemplate->template_html = $request->tempHtml;
            $mailTemplate->subject = $request->subject;

            $mailTemplate->save();
            return redirect()->route('admin.mail.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('MailtemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    function smsIndex()
    {
        try {
            $companyId = Helper::getCompanyId();

            return view('admin.smsTemplate.list');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function smsList(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id'];
            $totalData = SmsTemplate::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['template_html'];
            $query = SmsTemplate::orderBy($columns[0], $dir);
            $query->where('company_id', $companyId);
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where('company_id', $companyId);
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
                if ($result->template_type == 'welcome') {
                    $type = 'Welcome';
                } elseif ($result->template_type == 'forgot_password') {
                    $type = 'Forgot Password';
                } elseif ($result->template_type == 'change_pass') {
                    $type = 'Change Password';
                } else {
                    $type = '';
                }

                $list[] = [
                    base64_encode($result->id),
                    $type,

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
    function smsCreate()
    {
        try {
            return view('admin.smsTemplate.create');
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    function smsEdit($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)->where('id', base64_decode($id))->first();
            return view('admin.smsTemplate.create', compact('SmsTemplate'));
        } catch (Exception $e) {
            Log::error('SmstemplateController::Create => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function smsStore(Request $request)
    {

        try {
            $companyId = Helper::getCompanyId();
            $SmsTemplate = SmsTemplate::where('company_id', $companyId)
                ->where('template_type', $request->type)
                ->first();


            if (empty($SmsTemplate) || empty($request->id)) {

                $existingTemplate = SmsTemplate::where('company_id', $companyId)
                    ->where('template_type', $request->type)
                    ->first();
                if (!empty($existingTemplate)) {
                    return redirect()->back()->with('error', 'Template already exit ' . $request->type);
                }

                $SmsTemplate = new SmsTemplate;
                $SmsTemplate->template_type = $request->type;
            }

            $SmsTemplate->company_id = $companyId;

            $SmsTemplate->template_html_sms = $request->tempHtml;

            $SmsTemplate->save();
            return redirect()->route('admin.sms.index')
                ->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

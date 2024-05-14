<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\SettingModel;
use App\Models\SmsTemplate;
use App\Models\Survey;
use App\Models\SurveyForm;
use App\Models\User;
use App\Services\TwilioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SurveyController extends Controller
{
    function formIndex(Request $request)
    {

        return view('company.survey.form.list');
    }

    public function formList(Request $request)
    {

        try {
            $companyId = Helper::getCompanyId(); // Assuming Helper is properly defined

            $columns = ['id', 'title'];
            $totalData = SurveyForm::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumns = ['title'];
            $query = SurveyForm::orderBy($columns[$order], $dir)->where('company_id', $companyId);

            // Server-side search
            if ($request->has('search') && $request->input('search.value') !== '') {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumns) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
                // Count total records after applying search criteria
                $totalData = $query->count();
            }

            $results = $query
                ->skip($start)
                ->take($length)
                ->get();


            foreach ($results as $result) {
                $surveyDatas = "0";
                $surveyDatas = Survey::where('form_id', $result->id)->count();
                $list[] = [
                    base64_encode($result->id),
                    $result->slug,
                    $result->title,
                    $surveyDatas
                    // Add more fields as needed
                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalData,
                'data' => $list
            ]);
        } catch (\Exception $e) {
            Log::error('SurveyController::formList ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }


    public function formCreate()
    {
        try {
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::where('company_id', $companyId)->first();
            $survey_dtt = SurveyForm::where('company_id', $companyId)->count();
            // dd($survey_dtt);
            return view('company.survey.form.create', compact('surveyFiled', 'survey_dtt'));
        } catch (Exception $e) {
            Log::error('SurveyController::formCreate => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function formView(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $surveyFiled = SurveyForm::find($id);
            $fields = json_decode($surveyFiled->fields, true);
            $surveyDatas = Survey::where('form_id', $id)->paginate(5);

            return view('company.survey.form.view', compact('surveyFiled', 'fields', 'surveyDatas'));
        } catch (Exception $e) {
            Log::error('SurveyController::formView => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }


    public function formEdit(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::find($id);

            // $survey_dtt = SurveyForm::where('company_id', $companyId)->count();

            return view('company.survey.form.edit ', compact('surveyFiled'));
        } catch (Exception $e) {
            Log::error('SurveyController::formEdit => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }


    public function getAdditionalFields(Request $request)
    {
        $type = $request->input('type');
        $addCount = $request->input('addCount');
        $add = $request->input('addrequest');

        // Define the additional fields HTML based on the selected type
        $additionalFields = '';

        switch ($type) {
            case 'select':
                $additionalFields = ' <div class="form-group row">
                <div class="col-sm-2">
                    <label for="label"  class="col-form-label">Value</label>
                        <input type="text" class="form-control" name="select[' . $addCount . '][]" id="label" placeholder="Enter Value" required>
                    </div>';
                if ($add == 'addrequest') {
                    $additionalFields .= '
                    <div class="col-sm-1 mt-4 float-right">
                        <span class="btn btn-primary  btn-sm" onclick="addFiledType(' . $addCount . ', \'select\')" data-typeCount="' . $addCount . '">Add</span>
                    </div>';
                } else {
                    $additionalFields .= '
                    <div class="col-sm-1 mt-4 float-right">
                        <span class="btn btn-danger  btn-sm" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                    </div>';
                }
                $additionalFields .= '</div>';
                break;
            case 'radio':
                $additionalFields = '
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="label"  class="col-form-label">Value</label>
                            <input type="text" class="form-control" name="radio[' . $addCount . '][]" id="label" placeholder="Enter Value" required>
                        </div>';
                if (!empty($add) && $add == 'addrequest') {
                    $additionalFields .= '
                            <div class="col-sm-1 mt-4 float-right">
                                <span class="btn btn-primary  btn-sm" onclick="addFiledType(' . $addCount . ', \'radio\')" data-typeCount="' . $addCount . '">Add</span>
                            </div>';
                } else {
                    $additionalFields .= '
                            <div class="col-sm-1 mt-4 float-right">
                                <span class="btn btn-danger  btn-sm" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                            </div>';
                }
                $additionalFields .= '</div>';

                // Add HTML for radio additional fields
                break;
            case 'checkbox':
                $additionalFields = ' <div class="form-group row">
                <div class="col-sm-2">
                    <label for="label"  class="col-form-label">Value</label>
                        <input type="text" class="form-control"  name="checkbox[' . $addCount . '][]" id="label" placeholder="Enter Value" required >
                    </div>';
                if ($add == 'addrequest') {
                    $additionalFields .= '
                                <div class="col-sm-1 mt-4 float-right">
                                    <span class="btn btn-primary  btn-sm" onclick="addFiledType(' . $addCount . ', \'checkbox\')" data-typeCount="' . $addCount . '">Add</span>
                                </div>';
                } else {
                    $additionalFields .= '
                                <div class="col-sm-1 mt-4 float-right">
                                    <span class="btn btn-danger  btn-sm" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                                </div>';
                }
                $additionalFields .= '</div>';
                // Add HTML for checkbox additional fields
                break;
            case 'addMore':
                $additionalFields = '<hr class="border hr-' . $addCount . '">
                <span class="btn btn-danger float-right addFiledRemove btn-sm" onclick="addFiledRemove(this)" data-removeCount="' . $addCount . '"><i class="fa fa-trash"></i></span>
                <div class="form-group row ">
                    <div class="col-md-6">
                    <label for="type_' . $addCount . '" class=" col-form-label">Type</label>
                <select data-count="' . $addCount . '" onchange="onchangeType(this,' . $addCount . ')" name="type[]" class="form-control templateType type" id="type_' . $addCount . '" required>
               <option value="">Select Type</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="select">Select</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="question" class="col-form-label">Question</label>
                    <input type="text" class="form-control" name="question[]" id="label" placeholder="Enter Question" required>
                </div>
                <div class="col-md-6">
                    <label for="required" class="col-form-label">Required</label>
                        <select id="required" name="required[]" class="form-control ">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                </div>

                <!-- Add more fields as needed -->
            </div>
            <div id="additionalFieldsContainer' . $addCount . '">

            </div>
            <div id="addFiledMore' . $addCount . '">
            </div>';
                // Add HTML for checkbox additional fields
                break;
                // Add cases for other types if needed
            default:
                // No additional fields needed
                break;
        }

        // Return the additional fields HTML
        return response()->json(['additionalFields' => $additionalFields]);
    }
    public function formStore(Request $request)
    {
        try {
            $inputFields = $request->all();
            $companyId = Helper::getCompanyId();
            $SurveyForm = new SurveyForm;
            $SurveyForm->company_id = $companyId;
            $SurveyForm->title = $request->input('survey_title');
            $SurveyForm->slug = $request->input('slug');
            $SurveyForm->description = $request->input('description');

            $fields = [];
            $types = $request->input('type');
            $question = $request->input('question');

            $required = $request->input('required');

            // Loop through each field and create an array for each field
            foreach ($types as $key => $type) {
                $inputNames = 'input_' . $key . '_' . rand(10000, 200000);
                $fields[] = [
                    'type' => $type,
                    'inputName' => $inputNames,
                    'label' => $question[$key],
                    'idname' => $inputNames,
                    'class' => $inputNames,
                    'required' => $required[$key],
                    $type => !empty($inputFields[$type]) && !empty($inputFields[$type][$key]) ? $inputFields[$type][$key] : null, // Assuming 'position' is common for all fields
                ];
            }

            // Convert fields array to JSON and save it in the SurveyForm model
            $SurveyForm->fields = json_encode($fields);

            // Save the SurveyForm instance
            $SurveyForm->save();

            return redirect()->route('company.survey.form.index', ['survey' => $SurveyForm->id])
                ->with('success', 'Survey added successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function formUpdate(Request $request, $id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $SurveyForm = SurveyForm::find($id);
            $inputFields = $request->all();

            $SurveyForm->company_id = $companyId;
            $SurveyForm->title = $request->input('survey_title');
            $SurveyForm->slug = $request->input('slug');
            $SurveyForm->description = $request->input('description');

            $fields = [];
            $types = $request->input('type');
            $question = $request->input('question');

            $required = $request->input('required');

            // Loop through each field and create an array for each field
            foreach ($types as $key => $type) {
                $inputNames = 'input_' . $key . '_' . rand(10000, 200000);
                $fields[] = [
                    'type' => $type,
                    'inputName' => $inputNames,
                    'label' => $question[$key],
                    'idname' => $inputNames,
                    'class' => $inputNames,

                    'required' => $required[$key],
                    $type => !empty($inputFields[$type]) && !empty($inputFields[$type][$key]) ? $inputFields[$type][$key] : null, // Assuming 'position' is common for all fields
                ];
            }


            // Convert fields array to JSON and save it in the SurveyForm model
            $SurveyForm->fields = json_encode($fields);

            // Save the SurveyForm instance
            $SurveyForm->save();

            return redirect()->route('company.survey.form.index', ['survey' => $SurveyForm->id])
                ->with('success', 'Survey updated successfully');
        } catch (Exception $e) {
            Log::error('SurveyController::formUpdate => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function formDelete($id)
    {

        try {
            $id = base64_decode($id);
            $form_id = $id;
            $form_id = SurveyForm::where('id', $form_id)->delete();
            return response()->json(['success' => 'success', 'message' => 'Form deleted successfully']);
        } catch (Exception $e) {
            Log::error('SurveyController::formDelete ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
    function checkSlug(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $checkSlug = SurveyForm::where('company_id', $companyId)->where('slug', '=', $request->slug);

            if (!empty($request->id)) {
                $checkSlug->where('id', '!=', $request->id);
            }

            $exist = $checkSlug->first();

            if ($exist) {
                return 'false';
            } else {
                return 'true';
            }
        } catch (Exception $e) {
            Log::error('SurveyController::checkSlug ' . $e->getMessage());
            // Return true in case of any error
            return 'true';
        }
    }
    function sendSms(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();

            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            $webUrl = "";
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }

            // $SettingModel = SettingModel::first();
            // if (!empty($companyId)) {
            // }
            $SettingModel = SettingModel::where('user_id', $companyId)->first();

            if (empty($SettingModel) && empty($SettingModel->sms_account_sid) && empty($SettingModel->sms_account_token) && empty($SettingModel->sms_account_number)) {
                return redirect()->route('company.survey.form.index')->with(['error' => "Please enter SMS Credential "]);
            }

            if ($request->contact_number != '') {

                foreach ($request->contact_number as $number) {


                    $SettingValue = SettingModel::where('user_id', $companyId)->first();

                    if (!empty($request->smsHtml)) {
                        if (!empty($SettingValue) && !empty($SettingValue->sms_account_sid) && !empty($SettingValue->sms_account_token) && !empty($SettingValue->sms_account_number)) {


                            //set survey shortcut
                            $template = $request->smsHtml;

                            $pattern = '/\[survey\[(.*?)\]\]/';
                            preg_match_all($pattern, $template, $matches);
                            if (!empty($matches[1])) {
                                foreach ($matches[1] as $surveyValue) {
                                    $surveyFrom = Helper::getSurveyFrom($surveyValue);
                                    $survey_link = $webUrl . '/survey' . '/' . $surveyFrom->slug;

                                    $template = str_replace('[survey[' . $surveyValue . ']]', $survey_link, $template);
                                }
                            }
                            $html = $template;

                            // Remove HTML tags and decode HTML entities
                            $message = htmlspecialchars_decode(strip_tags($html));

                            // Remove unwanted '&nbsp;' text
                            $message = str_replace('&nbsp;', ' ', $message);

                            $to = $SettingValue->type == "2" ? $number : $SettingValue->sms_account_to_number;
                            $twilioService = new TwilioService($SettingValue->sms_account_sid, $SettingValue->sms_account_token, $SettingValue->sms_account_number);
                            try {
                                $twilioService->sendSMS($to, $message);
                            } catch (Exception $e) {
                                Log::error('Notifications >> Que SMS Fail => ' . $e->getMessage());
                            }
                        }
                    }
                }

                return redirect()->route('company.survey.form.index')->with([
                    'success' => 'SMS sent successfully',

                ]);
            }
        } catch (Exception $e) {
            Log::error('SurveyController::sendSms => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sendMail(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId();

            $webUrlGetHost = $request->getHost();
            $currentUrl = URL::current();
            $webUrl = "";
            if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
                // URL is under HTTPS
                $webUrl =  'https://' . $webUrlGetHost;
            } else {
                // URL is under HTTP
                $webUrl =  'http://' . $webUrlGetHost;
            }

            // $SettingModel = SettingModel::first();
            // if (!empty($companyId)) {
            // }

            $SettingModel = SettingModel::where('user_id', $companyId)->first();

            if (empty($SettingModel) && empty($SettingModel->mail_address) && empty($SettingModel->mail_address)) {
                return redirect()->route('company.survey.form.index')->with(['error' => "Please enter mail credential "]);
            }
            foreach ($request->mail as $mail) {
                try {
                    $to = $mail;
                    $message = '';

                    $html =  $request->tempHtml;

                    $mailTemplateSubject = !empty($mailTemplate) && !empty($mailTemplate->subject) ? $mailTemplate->subject : 'custom';
                    Mail::send('user.email.surveyEmail', [
                        'name' => "",
                        'company_id' => "",
                        'template' => $html,
                        'webUrl' => "$webUrl",
                        'campaign_title' => "",
                        'campaign_price' => "",
                        'campaign_price' => "",
                        'campaign_join_link' => ""
                    ], function ($message) use ($to, $mailTemplateSubject) {
                        $message->to($to);
                        $message->subject($mailTemplateSubject);
                    });
                } catch (Exception $e) {
                    Log::error('CampaignController::Action => ' . $e->getMessage());
                }
            }


            return redirect()->route('company.survey.form.index')->with([
                'success' => 'Mail sent successfully',
            ]);
        } catch (Exception $e) {
            Log::error('SurveyController::sendMail => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SurveyForm;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class SurveyController extends Controller
{
    function formIndex(Request $request)
    {

        return view('company.survey.form.list');
    }

    public function formList(Request $request)
    {

        // try {
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
            $list[] = [
                $result->id,
                $result->title
                // Add more fields as needed
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $list
        ]);
        // } catch (\Exception $e) {
        //     Log::error('SurveyController::formList ' . $e->getMessage());
        //     dd($e->getMessage());
        //     return response()->json([
        //         'draw' => intval($request->input('draw')),
        //         'recordsTotal' => 0,
        //         'recordsFiltered' => 0,
        //         'data' => []
        //     ]);
        // }
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

            $surveyFiled = SurveyForm::find($id);
            // dd($surveyFiled);



            // Decode the JSON string containing the fields and sort them based on position
            $fields = json_decode($surveyFiled->fields, true);
            // dd($fields);


            // if (!empty($fields)) {
            //     usort($fields, function ($a, $b) {
            //         return $a['position'] - $b['position'];
            //     });
            // }

            // Pass the sorted fields to the view
            // return view('company.survey.form.view', compact('surveyFiled', 'fields'));
            return view('company.survey.form.view', compact('surveyFiled', 'fields'));
        } catch (Exception $e) {
            Log::error('SurveyController::formView => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }


        // try {
        //     $companyId = Helper::getCompanyId();
        //     $surveyFiled = SurveyForm::find($id);
        //     dd($surveyFiled);
        //     // $survey_dtt = SurveyForm::where('company_id', $companyId)->count();

        //     return view('company.survey.form.view ', compact('surveyFiled'));
        // } catch (Exception $e) {
        //     Log::error('SurveyController::formView => ' . $e->getMessage());
        //     return redirect()->back()->with('error', "Error : " . $e->getMessage());
        // }
    }


    public function formEdit(Request $request, $id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::find($id);
            // $survey_dtt = SurveyForm::where('company_id', $companyId)->count();

            return view('company.survey.form.edit ', compact('surveyFiled'));
        } catch (Exception $e) {
            Log::error('SurveyController::formEdit => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function formEditFrom(Request $request, $id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::find($id);
            $survey_dtt = SurveyForm::where('company_id', $companyId)->count();

            return view('company.survey.form.edit_form ', compact('surveyFiled', 'survey_dtt'));
        } catch (Exception $e) {
            Log::error('SurveyController::formEditFrom => ' . $e->getMessage());
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
                    <label for="label"  class="col-form-label">Option Name</label>
                        <input type="text" class="form-control" name="select[]" id="label" placeholder="Enter Name">
                    </div>';
                if ($add == 'addrequest') {
                    $additionalFields .= '
                    <div class="col-sm-1 mt-4 float-right">
                        <span class="btn btn-primary" onclick="addFiledType(' . $addCount . ', \'select\')" data-typeCount="' . $addCount . '">Add</span>
                    </div>';
                } else {
                    $additionalFields .= '
                    <div class="col-sm-1 mt-4 float-right">
                        <span class="btn btn-danger" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                    </div>';
                }
                $additionalFields .= '</div>';
                break;
            case 'radio':
                $additionalFields = '
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="label"  class="col-form-label">Radio Name</label>
                            <input type="text" class="form-control" name="radio[]" id="label" placeholder="Enter Name">
                        </div>';
                if (!empty($add) && $add == 'addrequest') {
                    $additionalFields .= '
                            <div class="col-sm-1 mt-4 float-right">
                                <span class="btn btn-primary" onclick="addFiledType(' . $addCount . ', \'radio\')" data-typeCount="' . $addCount . '">Add</span>
                            </div>';
                } else {
                    $additionalFields .= '
                            <div class="col-sm-1 mt-4 float-right">
                                <span class="btn btn-danger" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                            </div>';
                }
                $additionalFields .= '</div>';

                // Add HTML for radio additional fields
                break;
            case 'checkbox':
                $additionalFields = ' <div class="form-group row">
                <div class="col-sm-2">
                    <label for="label"  class="col-form-label">Checkbox Name</label>
                        <input type="text" class="form-control" name="checkbox[]" id="label" placeholder="Enter Name">
                    </div>';
                if ($add == 'addrequest') {
                    $additionalFields .= '
                                <div class="col-sm-1 mt-4 float-right">
                                    <span class="btn btn-primary" onclick="addFiledType(' . $addCount . ', \'checkbox\')" data-typeCount="' . $addCount . '">Add</span>
                                </div>';
                } else {
                    $additionalFields .= '
                                <div class="col-sm-1 mt-4 float-right">
                                    <span class="btn btn-danger" onclick="removeFiledType(' . $addCount . ')"><i class="fa fa-trash"></i></span>
                                </div>';
                }
                $additionalFields .= '</div>';
                // Add HTML for checkbox additional fields
                break;
            case 'addMore':
                $additionalFields = '<hr class="border">
                <span class="btn btn-danger float-right addFiledRemove btn-sm" onclick="addFiledRemove(this)" data-removeCount="' . $addCount . '"><i class="fa fa-trash"></i></span>
                <div class="form-group row ">
                    <div class="col-md-6">
                    <label for="type" class=" col-form-label">Type</label>
                <select id="type" data-count="' . $addCount . '" onchange="onchangeType(this,' . $addCount . ')" name="type[]" class="form-control templateType type" required>
               <option value="">Select Type</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="textarea">Textarea</option>
                        <option value="select">Select</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="label" class="col-form-label">Label</label>
                    <input type="text" class="form-control" name="label[]" id="label" placeholder="Enter Label" required>
                </div>
                <div class="col-sm-6">
                    <label for="placeholder" class=" col-form-label">Placeholder</label>
                    <input type="text" class="form-control" name="placeholder[]" id="placeholder" placeholder="Enter Placeholder">
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




    public function formUpdateForm(Request $request)
    {
        // try {
        $companyId = Helper::getCompanyId();

        $surveyField = SurveyForm::where('id', $request->id)->where('company_id', $companyId)->first();

        if (!empty($surveyField)) {
            $fields = json_decode($surveyField->fields, true);

            // Append new data from the request to the existing fields
            $newField = array(
                'type' => $request->input('type'),
                'label' => $request->input('label'), // corrected 'lable' to 'label'
                'inputName' => $request->input('inputName'),
                'idname' => $request->input('idname'),
                'class' => $request->input('class'),
                'placeholder' => $request->input('placeholder'),
                'position' => $request->input('position')
            );

            $fields[] = $newField;

            // Convert the merged fields array back to JSON and save it to the database
            $surveyField->title = $request->input('survey_title');
            $surveyField->fields = json_encode($fields);
            $surveyField->save();

            return redirect()->route('company.survey.form.view', ['survey' => $request->id])
                ->with('success', 'Form updated successfully');
        } else {
            return redirect()->back()->with('error', 'Survey form not found');
        }
        // } catch (Exception $e) {
        //     Log::error('SmstemplateController::store => ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }

    public function formStore(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $SurveyForm = new SurveyForm;
            $SurveyForm->company_id = $companyId;
            $SurveyForm->title = $request->input('survey_title');

            $fields = [];
            $types = $request->input('type');
            $labels = $request->input('label');
            $placeholders = $request->input('placeholder');

            // Loop through each field and create an array for each field
            foreach ($types as $key => $type) {
                $inputNames = 'input_' . $key . '_' . rand(10000, 200000);

                $fields[] = [
                    'type' => $type,
                    'inputName' => $inputNames,
                    'label' => $labels[$key],
                    'idname' => $inputNames,
                    'class' => $inputNames,
                    'placeholder' => $placeholders[$key],
                    $type => $request->input($type), // Assuming 'position' is common for all fields
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


            $SurveyForm->company_id = $companyId;
            $SurveyForm->title = $request->input('survey_title');

            $fields = [];
            $types = $request->input('type');
            $labels = $request->input('label');
            $placeholders = $request->input('placeholder');

            // Loop through each field and create an array for each field
            foreach ($types as $key => $type) {
                $inputNames = 'input_' . $key . '_' . rand(10000, 200000);

                $fields[] = [
                    'type' => $type,
                    'inputName' => $inputNames,
                    'label' => $labels[$key],
                    'idname' => $inputNames,
                    'class' => $inputNames,
                    'placeholder' => $placeholders[$key],
                    $type => $request->input($type), // Assuming 'position' is common for all fields
                ];
            }


            // Convert fields array to JSON and save it in the SurveyForm model
            $SurveyForm->fields = json_encode($fields);

            // Save the SurveyForm instance
            $SurveyForm->save();

            return redirect()->route('company.survey.form.index', ['survey' => $SurveyForm->id])
                ->with('success', 'Survey updated successfully');
        } catch (Exception $e) {
            Log::error('SmstemplateController::formUpdate => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function formDelete($id)
    {

        try {
            $form_id = $id;
            $form_id = SurveyForm::where('id', $form_id)->delete();
            return response()->json(['success' => 'success', 'message' => 'Form deleted successfully']);
        } catch (Exception $e) {
            Log::error('SurveyController::formDelete ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
}

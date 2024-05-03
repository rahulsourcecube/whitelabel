<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyForm;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function survey(Request $request, $slug)
    {

        $companyId = Helper::getCompanyId();
        $surveyFiled = SurveyForm::where('slug', $slug)->where('company_id', $companyId)->first();

        $fields = json_decode($surveyFiled->fields, true);

        return view('front.surveyForm', compact('surveyFiled', 'fields'));
    }

    public function Store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::where('id', $request->form_id)->where('company_id', $companyId)->first();
            $inputData = $request->except('_token', 'url');
            $fields = json_decode($surveyFiled->fields, true);

            $fieldData = []; // Initialize an empty array to hold field data

            foreach ($inputData as $key => $row) {
                // Iterate over each key-value pair in $inputDatas
                if (!empty($fields)) {
                    foreach ($fields as $field) {
                        if ($field['inputName'] == $key) {
                            $fieldData[] = [$field['label'] => (is_array($row) ? implode(", ", $row) : $row)];
                        }
                    }
                }
            }

            $Survey = new Survey();
            $companyId = Helper::getCompanyId();
            $Survey->company_id = $companyId;
            $Survey->form_id = $request->form_id;
            $Survey->data = json_encode($fieldData);

            // Save the Survey instance
            $Survey->save();
            return redirect()->route('front.success.page');
        } catch (Exception $e) {
            Log::error('SmstemplateController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
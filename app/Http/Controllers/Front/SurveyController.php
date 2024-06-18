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

    public function survey(Request $request, SurveyForm $surveyForm)
    {

        $ActivePackageData = Helper::GetActivePackageData();
        if ($ActivePackageData->survey_status != "1" || empty($ActivePackageData->no_of_survey)) {

            return redirect()->route('home')->with('error', 'Please contact to Company administrator.');
        }
        $companyId = Helper::getCompanyId();

        if ($surveyForm->company_id !=  $companyId) {
            return redirect()->back()->with('error', "Not Found Campaign ");
        }

        $fields = json_decode($surveyForm->fields, true);

        return view('front.surveyForm', compact('surveyForm', 'fields'));
    }

    public function Store(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();

            $surveyFiled = SurveyForm::find($request->form_id);
            $inputData = $request->except('_token', 'url');

            $fields = json_decode($surveyFiled->fields, true);

            $fieldData = []; // Initialize an empty array to hold field data
            // dd()
            foreach ($inputData as $key => $row) {
                // Iterate over each key-value pair in $inputDatas
                if (!empty($fields)) {

                    if ($key == 'user_username') {
                        $fieldData[] = ['Username' => (is_array($row) ? (auth()->user() && !empty(auth()->user()->Fullname)  ? auth()->user()->Fullname  :  implode(", ", $row)) : $row)];
                    }
                    if ($key == 'user_email') {
                        $fieldData[] = ['Email Address' => (is_array($row) ? (auth()->user()  && !empty(auth()->user()->Fullname)  ? auth()->user()->email  :  implode(", ", $row)) : $row)];
                    }
                    foreach ($fields as $field) {


                        if ($field['inputName'] == $key) {
                            $fieldData[] = [$field['label'] => (is_array($row) ? implode(", ", $row) : $row)];
                        } else {
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
            Log::error('SurveyController::store => ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
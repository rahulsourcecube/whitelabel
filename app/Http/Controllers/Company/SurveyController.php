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
        if ($request->ajax()) {
        } else {
            return view('company.survey.form.list');
        }
    }

    public function formList(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId();
            $columns = ['id'];
            $totalData = SurveyForm::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['template_html'];
            $query = SurveyForm::orderBy($columns[0], $dir);
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
                

                $list[] = [
                    base64_encode($result->id),
                    $result->title??"-",

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
    function formCreate()
    {
        try {
            $companyId = Helper::getCompanyId();
            $surveyFiled = SurveyForm::where('company_id',$companyId)->first();
            return view('company.survey.form.create',compact('surveyFiled'));
        } catch (Exception $e) {
            Log::error('SurveyController::formCreate => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function formStore(Request $request)
    { 

        // try {
            $companyId = Helper::getCompanyId();
            $SurveyFiled = SurveyForm::where('company_id',$companyId)->first();
            if(empty($SurveyFiled) && !empty($request->input('survey_title'))){   
                      
                $Surveytitle = new SurveyForm();
                $Surveytitle->company_id=$companyId;
                $Surveytitle->title=$request->input('survey_title');
                $Surveytitle->save();
            }else{
                  
            if(!empty($SurveyFiled)){

                $fields = json_decode($SurveyFiled->fields, true);
    
                // Append new data from the request to the existing fields
                $newField = array(
                    'type' => $request->input('type'),
                    'lable' => $request->input('lable'),
                    'inputName' => $request->input('inputName'),
                    'idname' => $request->input('idname'),
                    'class' => $request->input('class'),
                    'placeholder' => $request->input('placeholder'),
                    'position' => $request->input('position')
                );
                
                $fields[] = $newField;
            
                // Convert the merged fields array back to JSON and save it to the database
                $SurveyFiled->fields = json_encode($fields);
                $SurveyFiled->save();

            }else{
                $fields[] = array(
                    'type' => $request->input('type'),
                    'lable' => $request->input('lable'),
                    'inputName' => $request->input('inputName'),
                    'idname' => $request->input('idname'),
                    'class' => $request->input('class'),
                    'placeholder' => $request->input('placeholder'),
                    'position' => $request->input('position')
                );
                
                // Creating a new SurveyForm instance
                $SurveyForm = new SurveyForm;                
                $SurveyForm->fields = json_encode($fields);
                
                // Saving the SurveyForm instance to the database
                $SurveyForm->save();
            }
        }

            
            return redirect()->route('company.survey.form.create');
            //     ->with('success', 'Setting updated successfully');
        // } catch (Exception $e) {
        //     Log::error('SmstemplateController::store => ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }
}

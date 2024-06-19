<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Helper;
use Illuminate\Pagination\Paginator;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function list(Request $request)
    {
        if (request()->getHttpHost() == config('app.domain')) {
            return redirect()->route('front.company.profiles');
        }

        $countrys = CountryModel::all();
        $states = StateModel::where('country_id', $request->input('country'))->get();
        $citys = CityModel::where('state_id', $request->input('state'))->get();

        $companyId = Helper::getCompanyId();
        $task_data = CampaignModel::where('public', 1)->where('status', '1')->where('company_id', $companyId);


        if (!empty($request->country) && $request->has('country')) {
            $task_data->where('country_id', $request->country);
        }

        if (!empty($request->state) &&  $request->has('state')) {
            $task_data->where('state_id', $request->state);
        }

        if (!empty($request->city) &&  $request->has('city')) {
            $task_data->where('city_id', $request->city);
        }
        $task_data->whereDate('expiry_date', '>=', now());
        $task_data = $task_data->paginate(6);

        // Pass selected country, state, and city IDs to the view
        $selectedCountry = $request->input('country');
        $selectedState = $request->input('state');
        $selectedCity = $request->input('city');

        return view('front.campaign.list', compact('task_data', 'countrys', 'states', 'citys', 'selectedCountry', 'selectedState', 'selectedCity'));
    }

    public function detail($id)
    {
        try {
            $companyId = Helper::getCompanyId();
            $campagin_detail = CampaignModel::Where('company_id', $companyId)->where('status', '1')->Where('id', base64_decode($id))->first();
            if (!$campagin_detail) {
                return redirect()->back()->with('error', "Not Found Campaign ");
            }
            return view('front.campaign.detail', compact('campagin_detail'));
        } catch (Exception $e) {
            Log::error('Front CampaignController::Referral => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function getStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $states = StateModel::where('country_id', $country_id)->get();
        $options = '';
        $options .= "<option value=''>Select state</option>";
        foreach ($states as $state) {
            $options .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        // Return the options as JSON response
        return response()->json($options);
    }

    public function getCity(Request $request)
    {
        $state_id = $request->input('state_id');

        $citys = CityModel::where('state_id', $state_id)->get();
        $options = '';
        $options .= "<option value=''>Select City</option>";
        foreach ($citys as $city) {
            $options .= "<option value='" . $city->id . "'>" . $city->name . "</option>";
        }
        return response()->json($options);
    }
    //create session join now
    function joinNow($join_link)
    {
        try {
            if (!(Auth::user())) {
                session()->put('join_link', $join_link);
                return redirect()->route('user.login');
            }
            return redirect()->route('user.campaign.view', $join_link);

            return redirect()->route('user.login');
        } catch (Exception $e) {
            Log::error('CampaignController::Referral => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
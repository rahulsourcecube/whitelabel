<?php

namespace App\Http\Controllers\Front;

use Illuminate\Pagination\Paginator;

use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function list(Request $request)
    {
        $country = CountryModel::all();
        $state = StateModel::all();
        $city = CityModel::all();

        $task_data = CampaignModel::where('public', 1);
        // $task_data = CampaignModel::where('public', 1)->where('status', 1);

        if (!empty($request->country) && $request->has('country')) {
            $task_data->where('country_id', $request->country);
        }

        if (!empty($request->state) &&  $request->has('state')) {
            $task_data->where('state_id', $request->state);
        }

        if (!empty($request->city) &&  $request->has('city')) {
            $task_data->where('city_id', $request->city);
        }

        $task_data = $task_data->paginate(6);

        // Pass selected country, state, and city IDs to the view
        $selectedCountry = $request->input('country');
        $selectedState = $request->input('state');
        $selectedCity = $request->input('city');

        return view('front.campaign.list', compact('task_data', 'country', 'state', 'city', 'selectedCountry', 'selectedState', 'selectedCity'));
    }



    public function detail($id)
    {
        $campagin_detail = CampaignModel::find($id);
        return view('front.campaign.detail', compact('campagin_detail'));
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
}

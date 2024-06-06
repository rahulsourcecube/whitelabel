<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CityModel;
use App\Models\CompanyModel;
use App\Models\CompanyPackage;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function success()
    {

        return view('front.error.thankyou');
    }
    public function error()
    {
        return view('front.error.error');
    }
    public function companyProfiles(Request $request)
    {

        $currentUrl = URL::current();
        if (URL::isValidUrl($currentUrl) && strpos($currentUrl, 'https://') === 0) {
            // URL is under HTTPS
            $webUrl =  'https://';
        } else {
            // URL is under HTTP
            $webUrl =  'http://';
        }
        if (request()->getHttpHost() != config('app.domain')) {
            $url = $webUrl . config('app.domain');
            return redirect()->away($url);
        }


        $data['countrys'] = CountryModel::all();
        $data['states'] = StateModel::where('country_id', $request->input('country'))->get();
        $data['citys'] = CityModel::where('state_id', $request->input('state'))->get();
        $company_data = User::where('user_type', User::USER_TYPE['COMPANY'])
            ->where('status', '1')
            ->where('public', '1')
            ->whereHas('campaigns', function ($query) {
                $query->where('public', '1')
                    ->whereDate('expiry_date', '>=', now());
            })->whereHas('companyActivePackage')
            ->orderBy('created_at', 'desc');



        if (!empty($request->country) && $request->has('country')) {
            $company_data->where('country_id', $request->country);
        }

        if (!empty($request->state) &&  $request->has('state')) {
            $company_data->where('state_id', $request->state);
        }

        if (!empty($request->city) &&  $request->has('city')) {
            $company_data->where('city_id', $request->city);
        }
        $data['companyProfiles'] = $company_data->paginate(12);


        $data['webUrl'] =  $webUrl;


        $data['selectedCountry'] = $request->input('country');
        $data['selectedState'] = $request->input('state');
        $data['selectedCity'] = $request->input('city');

        return view('front.company.company_profiles', $data);
    }
}

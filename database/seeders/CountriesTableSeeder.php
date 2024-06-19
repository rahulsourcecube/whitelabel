<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\StateModel;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        set_time_limit(1000);
        $countriesData = json_decode(file_get_contents(storage_path("countries.json")), true);

        foreach ($countriesData as $countryData) {

            $country = CountryModel::updateOrCreate(
                ['name' => $countryData['name']],
                [
                    'phonecode' => "+" . str_replace("+", "",  $countryData['phone_code']),
                    'short_name' => $countryData['iso2'], //iso2 - ISO 3166-1 alpha-2 code for country

                ]
            );
        }



        //Add/Update States
        $statesData = json_decode(file_get_contents(storage_path("states.json")), true);

        foreach ($statesData as $stateData) {
            $countryName = $stateData['country_name'];

            $country = CountryModel::where('name', $countryName)->first();

            if ($country) {
                StateModel::updateOrCreate(
                    [
                        "name" => $stateData["name"],
                        'country_id' => $country->id,
                        // "status" => 1  //active,
                    ],
                    [
                        "name" => $stateData["name"],
                    ]
                );
            }
            // $this->info("Sates '{$stateData["name"]}' in Country : '{$countryName}' updated/created successfully.");
        }



        //Add/Update Cities
        $citiesData = json_decode(file_get_contents(storage_path("cities.json")), true);

        foreach ($citiesData as $cityData) {
            $stateName = $cityData['state_name'];
            $countryName = $cityData['country_name'];

            // Find the country

            // Find the state

            $state = StateModel::where('name', $stateName)->first();
            $country = CountryModel::where('name', $countryName)->first();

            if ($country && $state) {

                CityModel::updateOrCreate(
                    [

                        'country_id' => $country->id,
                        'state_id' => $state->id,
                        "name" => $cityData["name"],

                    ],
                    [
                        "name" => $cityData["name"],
                    ]
                );

                //  $this->info("City '{$cityData["name"]}' in Country : '{$countryName}' and State : {$countryName} updated/created successfully.");
            }
        }
    }
}

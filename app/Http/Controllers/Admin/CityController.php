<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CityController extends Controller
{

    public function index()
    {
        return view('admin.location.city.list');
    }


    public function dtList(Request $request)
    {
        try {
            $columns = ['id', 'country_id', 'state_id', 'name', 'zipcode'];
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumns = ['country.name', 'state.name', 'city.name']; // Adjust these column names as per your database structure

            $query = CityModel::select('city.id', 'country.name as country_name', 'state.name as state_name', 'city.name as city_name', 'city.zipcode')
                ->join('state', 'city.state_id', '=', 'state.id')
                ->join('country', 'state.country_id', '=', 'country.id')
                ->orderBy($columns[$order], $dir);

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumns) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
                // Count total records after applying search criteria
                $totalData = $query->count();
            } else {
                // Count total records without search criteria
                $totalData = CityModel::count();
            }

            $results = $query->skip($start)->take($length)->get();

            foreach ($results as $result) {
                $list[] = [
                    $result->id,
                    $result->country_name,
                    $result->state_name,
                    $result->city_name,

                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (\Exception $e) {
            Log::error('CityController::dtList ' . $e->getMessage());
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
    }


    public function create()
    {
        $data['state'] = StateModel::all();
        return view('admin.location.city.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $StateCheckName = CityModel::where(function ($query) use ($request) {
                $query->where('name', $request->name)
                    ->Where('state_id', $request->state);
            })
                ->first();

            if (!empty($StateCheckName)) {
                $errorFields = [];
                if ($StateCheckName->name === $request->name) {
                    $errorFields[] = 'City name';
                }
                if ($StateCheckName->country__id === $request->state) {
                    $errorFields[] = 'State name';
                }

                return redirect()->back()->with('error', implode(', ', $errorFields) . ' already exists ')->withInput();
            }
            $city = new CityModel();
            $city->state_id = $request->state;
            $city->name = $request->name;
            // $city->zipcode = $request->zipcode;
            $city->save();

            return redirect()->route('admin.location.city.list')->with('success', 'City Added successfully');
        } catch (\Exception $e) {
            Log::error('CityController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }



    public function edit(Request $request)
    {

        try {
            $data = [];
            $data['city'] = CityModel::where('id', $request->city)->first();
            $data['state'] = StateModel::all();
            return view('admin.location.city.edit', $data);
        } catch (Exception $e) {
            Log::error('CityController::edit ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }


    function update(Request $request, $id)
    {
        try {
            $StateCheckName = CityModel::where('id', '!=', $id)
                ->where(function ($query) use ($request) {
                    $query->where('name', $request->name)
                        ->Where('state_id', $request->state)
                        ->Where('zipcode', $request->zipcode);
                })
                ->first();

            if (!empty($StateCheckName)) {
                $errorFields = [];
                if ($StateCheckName->name === $request->name) {
                    $errorFields[] = 'City name';
                }
                if ($StateCheckName->state_id === $request->state_id) {
                    $errorFields[] = 'State name';
                }


                return redirect()->back()->with('error', implode(', ', $errorFields) . ' already exists ')->withInput();
            }

            $city =   CityModel::find($id);

            $city->state_id = $request->state;
            $city->name = $request->name;
            // $city->zipcode = $request->zipcode;
            $city->save();

            return redirect()->route('admin.location.city.list')->with('success', 'City Update successfully');
        } catch (\Exception $e) {
            Log::error('CityController::update ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $package = CityModel::findOrFail($id);
            $package->delete();
            return response()->json(["status" => 200, "message" => "City Deleted"]);
        } catch (\Exception $e) {
            Log::error('CityController::delete ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
}

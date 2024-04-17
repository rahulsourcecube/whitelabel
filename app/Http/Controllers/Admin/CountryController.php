<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CountryModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{

    public function index()
    {
        return view('admin.location.country.list');
    }

    public function dtList(Request $request)
    {
        // dd('tergh');
        try {
            $columns = [ 'name'];
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['name'];


            $query = CountryModel::orderBy($columns[$order], $dir);

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        if ($column == 'name') {
                            $query->orWhere("$column", 'like', "%{$search}%");
                        }
                    }
                });
             $totalData = $query->count();
            } else {
                // Count total records without search criteria
                $totalData = CountryModel::count();
            }

            $results = $query->skip($start)->take($length)->get();
          
            $list = [];
            foreach ($results as $result) {
                $list[] = [
                    $result->id,
                    $result->name,
                    $result->short_name,
                    $result->phonecode,
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('CountryController::dtList ' . $e->getMessage());
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
        return view('admin.location.country.create');
    }

    public function store(Request $request)
    {
         try {
            $countryCheckName = CountryModel::where(function($query) use ($request) {
                $query->where('name', $request->name);
                
                    if (!empty($request->short_name)) {
                         
                        $query->orWhere('short_name', $request->short_name);
                    }
                    if (!empty($request->code)) {
                        $query->orWhere('phonecode', $request->code);
                    }
            })->first();
            
            if (!empty($countryCheckName)) {
                $errorFields = [];
                if ($countryCheckName->name == $request->name) {
                    $errorFields[] = 'Country name';
                }
                if (!empty($request->short_name) &&$countryCheckName->short_name == $request->short_name) {
                    $errorFields[] = 'Short name';
                }
                if (!empty($request->code)&& $countryCheckName->phonecode == $request->code) {
                    $errorFields[] = 'Phone code';
                }
            
                return redirect()->back()->with('error', implode(', ', $errorFields) .' already exists ')->withInput();
            }
            $country = new CountryModel();
            $country->name = $request->name;
            $country->short_name = $request->short_name;
            $country->phonecode = $request->code;
            $country->save();

            return redirect()->route('admin.location.country.list')->with('success', 'Country Added successfully');
        } catch (\Exception $e) {
            Log::error('CountryController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        // dd($request->country);
        try {
            $data = [];
            $data['country'] = CountryModel::where('id', $request->country)->first();
            return view('admin.location.country.edit', $data);
        } catch (Exception $e) {
            Log::error('CountryController::edit ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }


    function update(Request $request, $id)
    {
        try {
          
            $countryCheckName = CountryModel::where('id', '!=', $id)
    ->where(function($query) use ($request) {
        $query->where('name', $request->name);
        
        if (!empty($request->short_name)) {
            $query->orWhere('short_name', $request->short_name);
        }
        if (!empty($request->code)) {
            $query->orWhere('phonecode', $request->code);
        }
        
    })
    ->first();
            
            if (!empty($countryCheckName)) {
                $errorFields = [];
                if ($countryCheckName->name == $request->name) {
                    $errorFields[] = 'Name';
                }
                if ($countryCheckName->short_name == $request->short_name) {
                    $errorFields[] = 'Short name';
                }
                if (!empty($request->code) && $countryCheckName->phonecode == $request->code) { 
                    $errorFields[] = 'Phone code';
                }
            
                return redirect()->back()->with('error', implode(', ', $errorFields) .' already exists ')->withInput();
            }

            $country =   CountryModel::find($id);
            $country->name = $request->name;
            $country->short_name = $request->short_name;
            $country->phonecode = $request->code;

            $country->save();
            return redirect()->route('admin.location.country.list')->with('success', 'Country Update successfully');
        } catch (\Exception $e) {
            Log::error('CountryController::update ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $package = CountryModel::findOrFail($id);
            $package->delete(); // Soft delete

            return response()->json(["status" => 200, "message" => "Country Deleted"]);
        } catch (\Exception $e) {
            Log::error('CountryController::delete ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
}

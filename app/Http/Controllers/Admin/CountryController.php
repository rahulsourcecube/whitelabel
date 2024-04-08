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
            $columns = ['id', 'country'];
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumn = ['country'];


            $query = CountryModel::orderBy($columns[$order], $dir);

            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        if ($column == 'country') {
                            $query->orWhere("$column", 'like', "%{$search}%");
                        }
                    }
                });
            }

            $results = $query->skip($start)->take($length)->get();
            $totalData = CountryModel::count();
            $list = [];
            foreach ($results as $result) {
                $list[] = [
                    $result->id,
                    $result->name,
                    $result->short_name,
                    $result->code,
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
            $country = new CountryModel();
            $country->name = $request->name;
            $country->short_name = $request->short_name;
            $country->code = $request->code;
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

            $country =   CountryModel::find($id);
            $country->name = $request->name;
            $country->short_name = $request->short_name;
            $country->code = $request->code;

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

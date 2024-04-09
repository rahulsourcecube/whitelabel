<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CountryModel;
use App\Models\StateModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StateController extends Controller
{

    public function index()
    {
        return view('admin.location.state.list');
    }

    function dtList(Request $request)
    {
        try {
            $columns = ['id', 'country_id', 'name'];
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumns = ['country.name', 'state.name']; // Adjust these column names as per your database structure

            $query = StateModel::select('state.id', 'state.name as state_name', 'country.name as country_name')
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
            }

            $results = $query->skip($start)->take($length)->get();
            $totalData = StateModel::count();

            foreach ($results as $result) {
                $list[] = [
                    $result->id,
                    $result->country_name,
                    $result->state_name,
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (\Exception $e) {
            Log::error('StateController::dtList ' . $e->getMessage());
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
        $data['country'] = CountryModel::all();
        // dd($data['country']);
        return view('admin.location.state.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $state = new StateModel();
            $state->country_id = $request->country;
            $state->name = $request->name;
            $state->save();

            return redirect()->route('admin.location.state.list')->with('success', 'State Added successfully');
        } catch (\Exception $e) {
            Log::error('StateController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {

        try {
            $data = [];
            $data['state'] = StateModel::where('id', $request->state)->first();
            $data['country'] = CountryModel::all();
            return view('admin.location.state.edit', $data);
        } catch (Exception $e) {
            Log::error('StateController::edit ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }


    function update(Request $request, $id)
    {
        try {

            $state =   StateModel::find($id);
            $state->country_id = $request->country;
            $state->name = $request->name;
            $state->save();
            return redirect()->route('admin.location.state.list')->with('success', 'State Update successfully');
        } catch (\Exception $e) {
            Log::error('StateController::update ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $package = StateModel::findOrFail($id);
            $package->delete();
            return response()->json(["status" => 200, "message" => "State Deleted"]);
        } catch (\Exception $e) {
            Log::error('StateController::delete ' . $e->getMessage());
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
}

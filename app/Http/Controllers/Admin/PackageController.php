<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyPackage;
use App\Models\PackageModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    //
    function index()
    {
        return view('admin.package.list');
    }

    public function dtList(Request $request)
    {
        try {
            $columns = ['id', 'title', 'type', 'duration', 'price', 'no_of_campaign']; // Add more columns as needed

            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];

            $searchColumn = ['title', 'duration', 'price', 'type', 'no_of_campaign'];

            $query = PackageModel::orderBy($columns[$order], $dir);

            $totalData = PackageModel::count();
            // Server-side search
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumn) {
                    foreach ($searchColumn as $column) {
                        if ($column == 'type') {
                            $query->orWhere(DB::raw("(CASE WHEN type = 1 THEN 'Free Trial' WHEN type = 2 THEN 'Monthly' WHEN type = 3 THEN 'Yearly' END)"), 'like', "%{$search}%");
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
                    $result->id,
                    $result->title,
                    $result->package_string,
                    $result->duration,
                    Helper::getcurrency() . $result->price,
                    $result->no_of_campaign,
                    $result->image,
                    ($result->status == '1') ? '<button class="btn btn-success btn-sm">Active</button>' : '<button class="btn btn-danger btn-sm">Deactive</button>',

                ];
            }
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalData,
                "data" => $list
            ]);
        } catch (Exception $e) {
            Log::error('PackageController::dtList ' . $e->getMessage());
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
    }

    function create()
    {
        return view('admin.package.create');
    }

    function view(packageModel $package)
    {
        try {
            return view('admin.package.view', compact('package'));
        } catch (Exception $e) {
            Log::error('PackageController::view ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    function store(Request $request)
    {
        try {
            $package = new PackageModel();

            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();

                // Generate a random number as a prefix
                $randomNumber = rand(1000, 9999);

                // Generate a timestamp (e.g., current Unix timestamp)
                $timestamp = time();

                // Combine the timestamp, random number, an underscore, and the original extension
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;

                // Move the file to the storage directory with the new filename
                $request->file('image')->move(base_path() . '/uploads/package', $image);

                // Save the image path to the database
                $package->image = $image;
            } else {
                $package->image = ""; // or whatever default value you want
            }

            // dd($request->description);
            $package->title = $request->title;
            $package->description = $request->description; // Fix typo in 'description' discription
            $package->no_of_campaign = $request->campaign;
            $package->no_of_user = $request->user;
            $package->no_of_employee = $request->employee;
            $package->duration = $request->day;

            $package->price = $request->price ?? '0';
            $package->type = $request->type;
            $package->status = $request->status ? '1' : '0';

            $package->survey_status = $request->survey_status ? '1' : '0';
            $package->no_of_survey = $request->survey_status ? $request->no_of_survey : "";
            $package->mail_temp_status = $request->mail_temp_status ? '1' : '0';
            $package->sms_temp_status = $request->sms_temp_status ? '1' : '0';
            $package->community_status = $request->community_status ? '1' : '0';
            // $Packages->status=$request->discription;
            $package->created_by = auth()->user()->id;


            $package->save();

            return redirect()->route('admin.package.list')->with('success', 'Package submitted successfully');
        } catch (\Exception $e) {
            Log::error('PackageController::store ' . $e->getMessage());
            return redirect()->back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function edit(PackageModel $package)
    {
        try {
            return view('admin.package.edit', compact('package'));
        } catch (\Exception $e) {
            Log::error('PackageController::edit - Error loading package with ID: ' . $package->id . ' - ' . $e->getMessage());
            return redirect()->back()->with('error', 'ackage not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $package = PackageModel::findOrFail($id);

            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomNumber = rand(1000, 9999);
                $timestamp = time();
                $imageName = $timestamp . '_' . $randomNumber . '.' . $extension;

                $request->file('image')->move(base_path() . '/uploads/package', $imageName);
                $package->image = $imageName;
            }

            // Update package details
            $package->title = $request->title;
            $package->description = $request->description;
            $package->no_of_campaign = $request->campaign;
            $package->no_of_user = $request->user;
            $package->no_of_employee = $request->employee;
            $package->duration = $request->day;
            $package->price = $request->price ?? '0';
            $package->type = $request->type;
            $package->status = $request->status ? '1' : '0';
            $package->survey_status = $request->survey_status ? '1' : '0';
            $package->no_of_survey = $request->survey_status ? $request->no_of_survey : '';
            $package->mail_temp_status = $request->mail_temp_status ? '1' : '0';
            $package->sms_temp_status = $request->sms_temp_status ? '1' : '0';
            $package->community_status = $request->community_status ? '1' : '0';
            $package->created_by = auth()->user()->id;

            $package->save();

            // Batch update company packages
            $companyPackages = CompanyPackage::where('package_id', $package->id)->get();
            foreach ($companyPackages as $companyPackage) {
                $companyPackage->no_of_campaign = $package->no_of_campaign;
                $companyPackage->no_of_user = $package->no_of_user;
                $companyPackage->no_of_employee = $package->no_of_employee;
                $companyPackage->survey_status = $package->survey_status;
                $companyPackage->no_of_survey = $package->no_of_survey;
                $companyPackage->mail_temp_status = $package->mail_temp_status;
                $companyPackage->sms_temp_status = $package->sms_temp_status;
                $companyPackage->community_status = $package->community_status;
                $companyPackage->save();
            }

            return redirect()->route('admin.package.list')->with('success', 'Package updated successfully');
        } catch (\Exception $e) {

            Log::error('PackageController::update - Error updating package with ID: ' . $id . ' - ' . $e->getMessage());


            return redirect()->back()->with('error', 'An error updating the package: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $package = PackageModel::findOrFail($id);
            $package->delete();
            return response()->json(["status" => 200, "message" => "Package Deleted"]);
        } catch (\Exception $e) {
            Log::error('PackageController::delete ' . $e->getMessage());
            // Handle the case where the record with the specified $id does not exist
            return response()->json(["status" => 400, "message" => "Error: " . $e->getMessage()]);
        }
    }
}

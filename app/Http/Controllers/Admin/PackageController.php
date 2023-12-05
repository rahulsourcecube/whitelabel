<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageModel;
use Illuminate\Http\Request;


class PackageController extends Controller
{
    //
    function index()
    {
        return view('admin.package.list');
    }
    public function dtList(Request $request)
    {
        $columns = ['id', 'title']; // Add more columns as needed
        $totalData = PackageModel::count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = PackageModel::orderBy($columns[$order], $dir)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {
            // dd($result);
            // dd('admin/uploads/packageImage/' . $result->image);
            if ($result->type == '1') {
                $type = 'Free';
            } elseif ($result->type == '2') {
                $type = 'Monthly';
            } elseif ($result->type == '3') {
                $type = 'Yearly';
            }

            $list[] = [
                $result->id,
                $result->title,
                $type,
                $result->duration,
                $result->price,
                $result->no_of_campaign,
                $result->image,
                ($result->status == '1') ? 'Active' : 'Deactive',

            ];
        }
        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $list
        ]);
    }
    function create()
    {
        return view('admin.package.create');
    }
    function view(packageModel $package)
    {
        return view('admin.package.view', compact('package'));
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
                $request->file('image')->move('uploads/package', $image);

                // Save the image path to the database
                $package->image = $image;
            } else {
                $package->image = ""; // or whatever default value you want
            }
            // dd($request->description);
            $package->title = $request->title;
            $package->description = $request->description; // Fix typo in 'description' discription
            $package->no_of_campaign = $request->campaign;
            $package->duration = $request->day;
            $package->price = $request->price;
            $package->type = $request->type;
            // $Packages->status=$request->discription;
            $package->created_by = auth()->user()->id;

            $package->save();

            return redirect()->route('admin.package.list')->with('success', 'Package submitted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    function edit(PackageModel $package)
    {
        return view('admin.package.edit', compact('package'));
    }
    function update(Request $request, $id)
    {
        try {
            $package = new PackageModel();
            $package =   PackageModel::where('id', $id)->first();

            if ($request->hasFile('image')) {
                $originalFilename = $request->file('image')->getClientOriginalName();
                $extension = $request->file('image')->getClientOriginalExtension();

                // Generate a random number as a prefix
                $randomNumber = rand(1000, 9999);

                // Generate a timestamp (e.g., current Unix timestamp)
                $timestamp = time();

                // Combine the timestamp, random number, an underscore, and the original extension
                $image = $timestamp . '_' . $randomNumber . '.' . $extension;

                // Move the file to the storage directory with the new filename+
                $request->file('image')->move('uploads/package', $image);

                // Save the image path to the database
                $package->image = $image;
            } else {
                $package->image = $package->image; // or whatever default value you want
            }
            // dd($request->description);
            $package->title = $request->title;
            $package->description = $request->description; // Fix typo in 'description' discription
            $package->no_of_campaign = $request->campaign;
            $package->duration = $request->day;
            $package->price = $request->price;
            $package->type = $request->type;
            // $Packages->status=$request->discription;
            $package->created_by = auth()->user()->id;

            $package->save();

            return redirect()->route('admin.package.list')->with('success', 'Package Update successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $package = PackageModel::findOrFail($id);
            $package->delete();
            return response()->json(["status" => 200, "message" => "Package Deleted"]);
        } catch (\Exception $e) {
            // Handle the case where the record with the specified $id does not exist
            return response()->json(["status" => 400, "message" => "Package not found or could not be deleted"]);
        }
    }
}

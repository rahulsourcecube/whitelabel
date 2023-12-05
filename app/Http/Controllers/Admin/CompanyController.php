<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //
    function index()
    {
        return view('admin.company.list');
    }
    public function dtList(Request $request)
    {
        $columns = ['id', 'company_name']; // Add more columns as needed
        $totalData = CompanyModel::count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = CompanyModel::orderBy($columns[$order], $dir)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {
            $list[] = [
                $result->id,
                $result->user->first_name  . ' ' . $result->user->last_name,
                $result->user->email,
                $result->user->contact_number,
                $result['company_name'],
                $result['subdomain'],
                $result->user->status == '1' ? 'Active' : 'Deactive',
                $result['email'],
                $result['email'],
                $result['email'],
                $result['is_indivisual'],
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
    public function view(Request $request)
    {
        $data = [];
        $data['user_company'] = CompanyModel::where('id', $request->id)->first();
        // dd( $data['user_company'][]);
        return view('admin.company.view', $data);
    }
}

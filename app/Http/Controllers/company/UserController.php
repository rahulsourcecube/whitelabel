<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index()
    {

        return view('company.user.list');
    }
    public function dtList(Request $request)
    {
        $columns = ['id', 'title']; // Add more columns as needed
        $totalData = User::count();
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        $results = User::orderBy($columns[$order], $dir)
            ->skip($start)
            ->take($length)
            ->get();
        foreach ($results as $result) {

            $list[] = [



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
    function create() {
        return view('company.user.create');
    }
    function View() {
        return view('company.user.view');
    }
    function edit() {
        return view('company.user.edit');
    }
}

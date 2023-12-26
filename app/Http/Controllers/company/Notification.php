<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use App\Models\Notification as ModelsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Notification extends Controller
{
    function index(Request $request)
    {      
        $user = Auth::user();
        $notifications = ModelsNotification::where('company_id', $user->id)->where('type', '2')->where('is_read', '0')->get();
       
        
        foreach ($notifications as $notification) {
            $notification->is_read = '1';
            $notification->save();
        }
            return view('company.notification.list');        
    }
    public function dtList(Request $request)
    {

        $columns = ['id', 'title'];
        $user = Auth::user();


        $totalData = ModelsNotification::where('company_id', $user->id)->where('type', '2')->count();

        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $list = [];
        
        $results = ModelsNotification::orderBy($columns[$order], $dir)
            ->where('company_id', Auth::user()->id)
            ->where('type', '2')
            ->skip($start)
            ->take($length)
            ->get();

        foreach ($results as $result) {


            $list[] = [

                $result->title ?? "-",
                $result->message ?? "-",

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
}


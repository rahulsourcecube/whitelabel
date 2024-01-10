<?php

namespace App\Http\Controllers\company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Notification as ModelsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Notification extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   function __construct()
   {
      // check user permission
      $this->middleware('permission:notification-list', ['only' => ['index']]);
   }

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

        $searchColumn = ['created_at', 'message'];

        $query = ModelsNotification::orderBy($columns[$order], $dir)
            ->where('company_id', Auth::user()->id)
            ->where('type', '2');

        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $searchColumn) {
                foreach ($searchColumn as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $results = $query->skip($start)
        ->take($length)
        ->get();

        foreach ($results as $result) {


            $list[] = [

                $result->title ?? "-",
                $result->message ?? "-",
                Helper::Dateformat($result->created_at) ?? "-",

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

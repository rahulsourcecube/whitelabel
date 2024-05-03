<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Channels;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChannelsController extends Controller
{
    public function index()
    {
        $channels = Channels::all();

        return view('company.channels.list')->with('channels', $channels);
    }
    public function list(Request $request)
    {
        try {
            $companyId = Helper::getCompanyId(); // Assuming Helper is properly defined

            $columns = ['id', 'title'];
            $totalData = Channels::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumns = ['title'];
            $query = Channels::orderBy($columns[$order], $dir)->where('company_id', $companyId);

            // Server-side search
            if ($request->has('search') && $request->input('search.value') !== '') {
                $search = $request->input('search.value');
                $query->where(function ($query) use ($search, $searchColumns) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
                // Count total records after applying search criteria
                $totalData = $query->count();
            }

            $results = $query
                ->skip($start)
                ->take($length)
                ->get();


            foreach ($results as $result) {

                $list[] = [
                    base64_encode($result->id),
                    $result->title,

                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalData,
                'data' => $list
            ]);
        } catch (\Exception $e) {
            Log::error('SurveyController::formList ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    public function create()
    {
        return view('company.channels.create');
    }
    public function store(Request $request)
    {
        try {
            $title = $request->title;
            $companyId = Helper::getCompanyId();

            // Check if title already exists
            $existingChannel = Channels::where('title', $title)->where('company_id', $companyId)->first();

            if (!empty($existingChannel)) {
                // If ID provided and the existing channel ID is not the same, then it's a conflict

                if (!empty($request->id) && $existingChannel->id != base64_decode($request->id)) {
                    return redirect()->back()->with('error', 'Channel title already exists');
                }
                if (empty($request->id)) {
                    return redirect()->back()->with('error', 'Channel title already exists');
                }
            }

            // If ID provided, update channel
            if (!empty($request->id)) {
                $channel = Channels::find(base64_decode($request->id));
                if (!$channel) {
                    return redirect()->back()->with('error', 'Channel not found');
                }
                $channel->title = $title;
                $channel->save();
                return redirect()->route('company.channel.index')->with('success', 'Channel updated successfully');
            }

            // Create new channel
            $channel = Channels::create([
                'title' => $title,
                'company_id' => $companyId,
            ]);

            return redirect()->route('company.channel.index')->with('success', 'Channel added successfully');
        } catch (Exception $e) {
            Log::error('ChannelController::store ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
    public function edit($id)

    {
        try {
            $companyId = Helper::getCompanyId();
            $channels = Channels::where('id', base64_decode($id))->where('company_id', $companyId)->first();

            return view('company.channels.create', compact('channels'));
        } catch (Exception $e) {
            Log::error('ChannelController::edit ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
    public function delete($id)
    {
        try {

            $channels = Channels::find(base64_decode($id));

            $channels->delete();

            return response()->json(['success' => 'success', 'message' => 'Channel deleted successfully']);
        } catch (Exception $e) {
            Log::error('ChannelController::delete ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
}
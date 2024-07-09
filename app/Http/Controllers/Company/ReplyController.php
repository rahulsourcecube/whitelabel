<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Channels;
use App\Models\Community;
use App\Models\Reply;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ReplyController extends Controller
{
    public function index()
    {
        $ActivePackageData = Helper::GetActivePackageData();
        $companySetting = Helper::companySetting();
        if ($ActivePackageData->community_status != "1"  ($companySetting->community_status =='2')) {
            return redirect()->route('company.dashboard')->with('error', "You don't have permission.");
        }

        return view('company.reply.list');
    }
    public function list(Request $request)
    {
        try {

            $companyId = Helper::getCompanyId(); // Assuming Helper is properly defined

            $columns = ['id', 'content'];
            $totalData = Reply::where('company_id', $companyId)->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $request->input('order.0.column');
            $dir = $request->input('order.0.dir');
            $list = [];
            $searchColumns = ['content'];
            $query = Reply::orderBy($columns[$order], $dir)->where('company_id', $companyId);

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
                    $result->community->category->title ?? "-",
                    Str::limit($result->content, 40),
                    $result->status ?? "-",
                    base64_encode($result->community_id)

                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalData,
                'data' => $list
            ]);
        } catch (Exception $e) {
            Log::error('SurveyController::formList ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    public function view($id)
    {
        try {

            $ActivePackageData = Helper::GetActivePackageData();
            $companySetting = Helper::companySetting();
            if ($ActivePackageData->community_status != "1"  ($companySetting->community_status =='2')) {
                return redirect()->route('company.dashboard')->with('error', "You don't have permission.");
            }
            $companyId = Helper::getCompanyId();
            $companyAdmin = Helper::companyAdmin();
            $questions = Community::where('company_id', $companyId)->where('id', base64_decode($id))->first();


            if (empty($questions)) {
                return redirect()->back()->with('error', 'Questions not found');
            }
            $questionsReplys = Reply::where('company_id', $companyId)->where('community_id', $questions->id)->orderBy('created_at', 'desc')->paginate(5);



            return view('company.reply.view', compact('questions', 'questionsReplys', 'companyAdmin'));
        } catch (Exception $e) {
            Log::error('CommunityController::show => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {

            $Reply = Reply::find(base64_decode($id));
            $Reply->delete();

            return response()->json(['success' => 'success', 'message' => 'Reply deleted successfully']);
        } catch (Exception $e) {
            Log::error('ChannelController::delete ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => "Error: " . $e->getMessage()]);
        }
    }
}

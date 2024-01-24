<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CampaignModel;
use App\Models\CompanyModel;
use App\Models\CompanyPackage;
use App\Models\PackageModel;
use App\Models\Payment;
use App\Models\SettingModel;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    //
    function index()
    {
        $packages = PackageModel::where('status', PackageModel::STATUS['ACTIVE'])->get();

        return view('admin.company.list', compact('packages'));
    }

    function AddPackages($id)
    {

        try {
            $id =   CompanyModel::where('user_id', $id)->first();
            $currentDate = Carbon::now();
            $currentDate = $currentDate->format('Y-m-d');
            $companyId = $id->user_id;

            // and then you can get query log
            $packageData = CompanyPackage::where('company_id', $companyId)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
            $FreePackagePurchased = CompanyPackage::where('company_id', $companyId)->where('price', 0.00)->first();
            $packages = PackageModel::where('status', PackageModel::STATUS['ACTIVE'])->get();
            $html = '<div class="row">';
            if (isset($packages) && count($packages) > 0) {
                foreach ($packages as $list) {
                    $html .= '  <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between p-b-20 border-bottom">
                                            <div class="media align-items-center">
                                                <div class="avatar avatar-blue avatar-icon"
                                                    style="height: 55px; width: 55px;">
                                                    <i class="anticon anticon-dollar font-size-25"
                                                        style="line-height: 55px"></i>
                                                </div>
                                                <div class="m-l-15">';
if ($packageData && $packageData->package_id && $packageData->package_id == $list->id){
                    $html .= '<span class="badge badge-primary"
                                                            style="margin-left: 180px">Active</span>';}else{
                        $html .= '<span class="badge badge-primary"
                                                            style="margin-left: 180px;background-color: white;"> </span>';
                                                            }
                                                    $html .= '<h2 class="font-weight-bold font-size-30 m-b-0">';
                    if ($list->type != "1") {
                        $html .= '' . Helper::getcurrency() . '';
                    }
                    $html .= '' . $list->plan_price . '
                                                    </h2>
                                                    <h4 class="m-b-0">' . $list->title . '</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled m-v-30">
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between"> <span
                                                        class="text-dark font-weight-semibold">' . $list->duration . '';
                    if ($list->type == '1') {
                        $html .= ' Days';
                    } elseif ($list->type == '2') {
                        $html .= ' Month';
                    } elseif ($list->type == '3') {
                        $html .= ' Year';
                    }
                    $html .= ' Plan
                                                    </span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between"> <span
                                                        class="text-dark font-weight-semibold">Total campaign
                                                        ' . $list->no_of_campaign . '</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-dark font-weight-semibold">Total Employee
                                                        ' . $list->no_of_employee . '</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                            <li class="m-b-20">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-dark font-weight-semibold">Total User
                                                        ' . $list->no_of_user . '</span>
                                                    <div class="text-success font-size-16"> <i
                                                            class="anticon anticon-check"></i> </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="package-description">
                                         '. $list->description .'</div>
                                        <form action="' . route("admin.company.buy") . '" method="POST"
                                            id="package-payment-form">
                                            '.csrf_field().'
                                            <input type="hidden" name="package_id" value="' . $list->id . '">
                                            <input type="hidden" name="company_id" value="' . $id->user_id . '">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success ' . $list->user_bought . '"';
                    if ($list->type == '1' && !empty($FreePackagePurchased) && $FreePackagePurchased->id != null) {
                        $html .= 'disabled >';
                    } else {
                        $html .= ' >';
                    }
                    if ($list->user_bought == true) {
                        $html .= 'Purchased';
                    } else {
                        $html .= 'Add Package ';
                    }
                    $html .= '</button> </div>
                                        </form>
                                    </div>
                                </div>
                            </div>';
                }
            } else {
                $html .= '<h4>No packages found</h4>';
            }
            $html .= '</div>';
            return response()->json(['success' => 'error', 'html' => $html]);
        } catch (Exception $e) {
            Log::error('ation error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
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

        $searchColumn = ['company_name', 'company_description', 'users.first_name', 'full_name', 'company.subdomain', 'users.last_name', 'users.email', 'users.contact_number'];

        $query = CompanyModel::leftJoin('users', 'company.user_id', '=', 'users.id') // Assuming 'user_id' is the foreign key in CompanyModel
            ->orderBy('company.' . $columns[$order], $dir);

        // Server-side search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $searchColumn) {
                foreach ($searchColumn as $column) {
                    if ($column == 'full_name') {
                        $query->orWhere(DB::raw('concat(users.first_name, " ", users.last_name)'), 'like', "%{$search}%");
                    } else {
                        $query->orWhere("$column", 'like', "%{$search}%");
                    }
                }
            });
        }

        $results = $query->skip($start)
            ->take($length)
            ->get();

        $list = [];
        foreach ($results as $result) {
            $list[] = [
                $result->id,
                $result->user->first_name  . ' ' . $result->user->last_name,
                $result->user->email,
                $result->user->contact_number,
                $result['company_name'],
                $result['subdomain'],
                $result->user->status == '1' ? '<button class="btn btn-success btn-sm">Active</button>' : '<button class="btn btn-danger btn-sm">Deactive</button>',
                $result['email'],
                $result['email'],
                $result['email'],
                $result['is_individual'],
            ];
        }

        $totalFiltered = $results->count();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalData,
            "data" => $list
        ]);
    }
    public function view(Request $request)
    {
        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        $data = [];
        $data['user_company'] = CompanyModel::where('user_id', $request->id)->first();
        $data['user_company_setting'] = SettingModel::where('user_id', $data['user_company']->user_id)->first();
        $data['ActivePackageData'] = CompanyPackage::where('company_id', $data['user_company']->user_id)->where('status', CompanyPackage::STATUS['ACTIVE'])->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->orderBy('id', 'desc')->first();
        $data['CampaignModelCount'] = !empty($data['ActivePackageData'])?CampaignModel::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->count():0;
        $data['staffCount'] =  !empty($data['ActivePackageData'])?User::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->where('user_type',  User::USER_TYPE['STAFF'])->count():0;
        $data['userCount'] =  !empty($data['ActivePackageData'])?User::where('company_id', $data['user_company']->user_id)->where('package_id', $data['ActivePackageData']->id)->where('user_type',  User::USER_TYPE['USER'])->count():0;

        return view('admin.company.view', $data);
    }
    function edit(Request $request)
    {
        $data = [];

        $data['user_company'] = CompanyModel::where('user_id', $request->id)->first();
        $data['setting'] = SettingModel::where('user_id', $data['user_company']->user_id)->first();
        $data['editprofiledetail'] = User::where('id', $data['user_company']->user_id)->first();
        return view('admin.company.edit', $data);
    }
    public function updatepassword(Request $request, $id)
    {
        try {
            $userCheck = User::where('id', $id)->first();
            if (empty($userCheck)) {
                return redirect()->back()->with('error', 'User not found!');
            }
            $userCheck->password = Hash::make($request->newPassword);
            $userCheck->view_password = $request->newPassword;
            $userCheck->update();
            return redirect()->back()->with('success', 'Password Update Successfully!');
        } catch (Exception $e) {
            Log::info("change password in profile error" . $e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
    public function updateprofile(Request $request, $id)
    {
        try {
            $updateprofiledetail = User::where('id', $id)->first();
            $updateprofiledetail['first_name'] = isset($request->first_name) ? $request->first_name : '';
            $updateprofiledetail['last_name'] = isset($request->last_name) ? $request->last_name : '';
            $updateprofiledetail['email'] = isset($request->email) ? $request->email : '';
            $updateprofiledetail['contact_number'] = isset($request->contact_number) ? $request->contact_number : '';
            $updateprofiledetail['status'] = !empty($request->status) ? '1' : '0';
            if ($request->hasFile('profile_image')) {
                if ($updateprofiledetail->profile_image && file_exists('uploads/user-profile/') . $updateprofiledetail->profile_image) {
                    unlink('uploads/user-profile/' . $updateprofiledetail->profile_image);
                }
                $filename = rand(111111, 999999) . '.' . $request->profile_image->extension();
                $request->file('profile_image')->move('uploads/user-profile/', $filename);
                $updateprofiledetail['profile_image'] = isset($filename) ? $filename : '';
            }
            $updateprofiledetail->save();
            return redirect()->back()->with('success', 'Profile Update Successfully!');
        } catch (Exception $e) {
            Log::info(['message', 'Update Profile error']);
            return redirect()->back()->with($e->getMessage());
        }
    }
    function store(Request $request, $id)
    {
        try {

            //code...
            $SettingModel = SettingModel::where('user_id', $id)->first();
            if ($SettingModel->user_id) {
                if (empty($SettingModel)) {
                    $SettingModel = new SettingModel;
                }
                //Update Favicon
                if ($request->hasFile('logo')) {
                    $extension = $request->file('logo')->getClientOriginalExtension();
                    // Generate a random number as a prefix
                    $randomNumber = rand(111111, 999999);
                    // Generate a timestamp (e.g., current Unix timestamp)
                    $timestamp = time();
                    // Combine the timestamp, random number, an underscore, and the original extension
                    $logo = $timestamp . '_' . $randomNumber . '.' . $extension;
                    // Move the file to the storage directory with the new filename+
                    $request->file('logo')->move('uploads/setting', $logo);
                    if (!empty($SettingModel->logo)) {
                        $oldlogo = 'uploads/setting/' . $SettingModel->logo;
                        // Delete the old favicon if it exists
                        if (file_exists($oldlogo)) {
                            unlink($oldlogo);
                        }
                    }
                    // Save the logo path to the database
                    $SettingModel->logo = $logo;
                }
                //Update Favicon
                if ($request->hasFile('favicon')) {
                    if (!empty($SettingModel->favicon)) {
                        $oldFaviconPath = 'uploads/setting/' . $SettingModel->favicon;
                        // Delete the old favicon if it exists
                        if (file_exists($oldFaviconPath)) {
                            unlink($oldFaviconPath);
                        }
                    }
                    $extension = $request->file('favicon')->getClientOriginalExtension();
                    // Generate a random number as a prefix
                    $randomNumber = rand(1000, 9999);
                    // Generate a timestamp (e.g., current Unix timestamp)
                    $timestamp = time();
                    // Combine the timestamp, random number, an underscore, and the original extension
                    $favicon_img = $timestamp . '_' . $randomNumber . '.' . $extension;
                    // Move the file to the storage directory with the new filename+
                    $request->file('favicon')->move('uploads/setting', $favicon_img);
                    // Save the favicon_img path to the database
                    $SettingModel->favicon = $favicon_img;
                }
                $SettingModel->title = $request->title;
                $SettingModel->email = $request->email;
                $SettingModel->contact_number = $request->contact_number;
                $SettingModel->description = $request->description;
                $SettingModel->facebook_link = $request->facebook_link;
                $SettingModel->twitter_link = $request->twitter_link;
                $SettingModel->linkedin_link = $request->linkedin_link;
                $SettingModel->save();
                return redirect()->back()->with('success', 'Setting Update successfully');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function buy(Request $request)
    {
        try {
            $package = PackageModel::where('id', $request->package_id)->first();
            if (empty($package)) {
                return redirect()->back()->with('error', 'Package not found');
            }

            $companyId = $request->company_id;

            $package = PackageModel::where('id', $request->package_id)->first();
            if (empty($package)) {
                // return response()->json(['error' => true, 'message' => 'Package not found']);
                return redirect()->back()->with('error', 'Package not found');
            }

            // dd($package->start_date, $package, $package->end_date);
            $addPackage = new CompanyPackage();
            $addPackage->company_id = $companyId;
            $addPackage->package_id = $package->id;
            $addPackage->start_date = $package->start_date;
            $addPackage->end_date = $package->end_date;
            $addPackage->no_of_campaign = $package->no_of_campaign;
            $addPackage->no_of_user = $package->no_of_user;
            $addPackage->no_of_employee = $package->no_of_employee;
            $addPackage->price = $package->price;
            $addPackage->paymnet_method = 'card';
            $addPackage->status = '1';
            $addPackage->paymnet_response = null;
            $addPackage->save();
            if ($addPackage) {
                $makePayment = new Payment();
                $makePayment->user_id = Auth::user()->id;
                $makePayment->company_package_id = $addPackage->id;
                $makePayment->amount = $addPackage->price;
                $makePayment->name_on_card = $request->name_on_card ?? '';
                $makePayment->card_number = $request->card_number ?? '';
                $expiryDate = explode('/', $request->expiry_date) ?? '';
                $makePayment->card_expiry_month = $request->card_expiry_month ?? '';
                $makePayment->card_expiry_year = $request->card_expiry_year ?? '';
                $makePayment->card_cvv = $request->card_cvv ?? '';
                $makePayment->zipcode = $request->zipcode ?? '';
                $makePayment->save();

                $addPackage->update(['paymnet_id' => $makePayment->id]);
                return redirect()->back()->with('success', 'Package activated successfully!');
                // return response()->json(['success' => true, 'message' => 'Package activated successfully!']);
            } else {
                // return response()->json(['error' => true, 'message' => 'Something went wrong, please try again later!']);
                return redirect()->back()->with('error', 'Something went wrong, please try again later!');
            }
        } catch (Exception $e) {
            Log::error('Buy package error : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again later!');
        }
    }
}

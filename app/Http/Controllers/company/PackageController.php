<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\User;
use App\Models\CompanyPackage;
use App\Models\PackageModel;
use App\Models\Payment;
use App\Models\User as ModelsUser;
// use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use Stripe\Exception\CardException;
// use Stripe\PaymentIntent;
use Stripe\Stripe as StripeStripe;
use Stripe;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // check user permission
        $this->middleware('permission:package-list', ['only' => ['index']]);
        $this->middleware('permission:package-create', ['only' => ['buy']]);
    }
    public function index($type)
    {
        try {
            $type = PackageModel::TYPE[strtoupper($type)];
            $packages = PackageModel::where('status', PackageModel::STATUS['ACTIVE'])->where('type', $type)->get();
            return view('company.package.list', compact('packages', 'type'));
        } catch (Exception $e) {
            Log::error('PackageController::Index => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    function billing()
    {
        try {
            $companyId = Helper::getCompanyId();
            $bills = CompanyPackage::where('company_id', $companyId)->get();
            return view('company.billing.list', compact('bills'));
        } catch (Exception $e) {
            Log::error('PackageController::Billing => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function buy(Request $request)
    {
        $jsonResponse = [];
        $jsonResponse['success'] = false;
        $jsonResponse['message'] = 'Something went wrong.';
        try {
            $stripeData = Helper::stripeKey();
            $package = PackageModel::where('id', $request->package_id)->first();
            if (empty($package)) {
                return redirect()->back()->with('error', 'Package not found');
            }
            if ($package->price != 0) {
                try {
                    Stripe\Stripe::setApiKey($stripeData->stripe_secret);

                    $user = ModelsUser::find(Auth::user()->id);
                    if ($user->stripe_client_id == '' || $user->stripe_client_id == NULL) {
                        $customer = Stripe\Customer::create(array(
                            'name' => $user->first_name . ' ' .  $user->last_name,
                            'email' => $user->email,
                            "source" => $request->stripeToken
                        ));
                        if (isset($customer) && isset($customer->id)) {
                            $user->stripe_client_id = $customer->id;
                            $user->save();
                        }
                    }
                    Stripe\Charge::create([
                        "amount" => $package->price * 100,
                        "currency" => "usd",
                        "customer" => $user->stripe_client_id,
                        "description" => "Test payment.",
                        "shipping" => [
                            "name" => $user->first_name . ' ' .  $user->last_name,
                            "address" => [
                                "line1" => rand(123,45654) . ' ' . Str::random(10),
                                "postal_code" => rand(123,45654),
                                "city" => Str::random(10),
                                "state" => "CA",
                                "country" => "US",
                            ],
                        ]
                    ]);
                } catch (Exception $e) {
                    Log::error('PackageController::Buy => ' . $e->getMessage());
                    if (str_contains($e->getMessage(), 'No such destination:')) {
                        $jsonResponse['message'] =  "Stripe Account Not Activeted !" ;
                        return response()->json($jsonResponse);
                    }
                    Log::info("Buy package action API Error: " . $e->getMessage());
                    $jsonResponse['message'] =  "Error : " . $e->getMessage();
                    return response()->json($jsonResponse);
                }
            }

            $companyId = Helper::getCompanyId();

            $package = PackageModel::where('id', $request->package_id)->first();
            if (empty($package)) {
                return redirect()->back()->with('error', 'Package not found');
            }
            $activePackage = CompanyPackage::where('company_id', $companyId) ->where('status', CompanyPackage::STATUS['ACTIVE'])->first();
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
            $addPackage->status = !empty($activePackage) ?'0':'1';
            $addPackage->paymnet_response = null;
            $addPackage->save();
            if ($addPackage) {
                $makePayment = new Payment();
                $makePayment->user_id = Auth::user()->id;
                $makePayment->company_package_id = $addPackage->id;
                $makePayment->amount = $addPackage->price;
                $makePayment->name_on_card = $request->name_on_card ?? '';
                $makePayment->card_number = $request->card_number ?? '';
                $makePayment->client_secret = !empty($jsonResponse['client_secret']) ? $jsonResponse['client_secret']: '';
                $makePayment->payment_intente = !empty($jsonResponse['payment_intente']) ? $jsonResponse['payment_intente']: '';
                $expiryDate = explode('/', $request->expiry_date) ?? '';
                // $makePayment->card_expiry_month = $request->card_expiry_month ?? '';
                // $makePayment->card_expiry_year = $request->card_expiry_year ?? '';
                // $makePayment->card_cvv = $request->card_cvv ?? '';
                $makePayment->zipcode = $request->zipcode ?? '';
                $makePayment->save();

                $addPackage->update(['paymnet_id' => $makePayment->id]);

                $jsonResponse['success'] = true;
                $jsonResponse['message'] =  "Package purchased successfully!" ;
            }
            if ($package->price != 0) {
                return response()->json($jsonResponse);
            }else{
                return redirect()->back()->with('success', 'Package activated successfully!');
            }
        } catch (Exception $e) {
            Log::error('PackageController::Buy => ' . $e->getMessage());
            $jsonResponse['message'] =  "Error : " . $e->getMessage();
            if ($package->price != 0) {
                return response()->json($jsonResponse);
            }else{
                return redirect()->back()->with('error', "Error : " . $e->getMessage());
            }
        }
    }

    public function stripePost(Request $request)
    {
        try {
            $stripe = Helper::stripeKey();
            StripeStripe::setApiKey($stripe->stripe_secret);
            $stripe = new \Stripe\StripeClient($stripe->stripe_secret);
            $stripe->paymentIntents->create([
                'amount' => 50 * 100,
                'currency' => 'usd',
                'description' => 'Test payment.',
                'payment_method_types' => ['card'],
            ]);
            Session::flash('success-message', 'Payment done successfully!');
            return view('cardForm');
        } catch (Exception $e) {
            Log::error('PackageController::StripePost => ' . $e->getMessage());
            return redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}

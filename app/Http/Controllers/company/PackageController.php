<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CompanyPackage;
use App\Models\PackageModel;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe as StripeStripe;

use Stripe;
use Session;

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
      $type = PackageModel::TYPE[strtoupper($type)];
      $packages = PackageModel::where('status', PackageModel::STATUS['ACTIVE'])->where('type', $type)->get();
      return view('company.package.list', compact('packages', 'type'));
   }

   function billing()
   {
      // get CompanyPackage bills
      $companyId = Helper::getCompanyId();
      $bills = CompanyPackage::where('company_id', $companyId)->get();

      return view('company.billing.list', compact('bills'));
   }

   public function buy(Request $request)
   {
      try {
         $package = PackageModel::where('id', $request->package_id)->first();
         if (empty($package)) {
            return redirect()->back()->with('error','Package not found');
         }
         if ($package->price != 0.0) {
            StripeStripe::setApiKey(env('STRIPE_SECRET'));


            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $stripe->paymentIntents->create([
               'amount' => $package->price * 100,
               'currency' => 'usd',
               'description' => 'Test payment.',
               'payment_method_types' => ['card'],
            ]);
         }

         $companyId = Helper::getCompanyId();

         $package = PackageModel::where('id', $request->package_id)->first();
         if (empty($package)) {
            return redirect()->back()->with('error', 'Package not found');
         }

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
            $makePayment->card_number = $request-> card_number ?? '';
            $expiryDate = explode('/', $request->expiry_date) ?? '';
            $makePayment->card_expiry_month = $request-> card_expiry_month ?? '';
            $makePayment->card_expiry_year = $request-> card_expiry_year ?? '';
            $makePayment->card_cvv = $request-> card_cvv ?? '';
            $makePayment->zipcode = $request-> zipcode ?? '';
            $makePayment->save();

            $addPackage->update(['paymnet_id' => $makePayment->id]);
            return redirect()->back()->with('success', 'Package activated successfully!');
         } else {
            return redirect()->back()->with('error', 'Something went wrong, please try again later!');
         }
      } catch (Exception $e) {
         Log::error('Buy package error : ' . $e->getMessage());
         return redirect()->back()->with('error', 'Something went wrong, please try again later!');
      }
   }

   public function stripePost(Request $request)
   {

      try {
         StripeStripe::setApiKey(env('STRIPE_SECRET'));


         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

         $stripe->paymentIntents->create([
            'amount' => 50 * 100,
            'currency' => 'usd',
            'description' => 'Test payment.',
            'payment_method_types' => ['card'],
         ]);
         Session::flash('success-message', 'Payment done successfully!');
         return view('cardForm');
      } catch (CardException $e) {

         Session::flash('fail-message', $e->getMessage());
         return view('cardForm');
      }
   }
}

<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
use App\Models\Banner;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\Products;
use App\Models\Settings;
use App\Models\Page;
use App\Models\VariantProduct;
use App\Models\VariantProductAttribute;
use App\Models\ProductCategory;
use App\Models\TmpOrders;
use App\Models\TmpOrdersDetails;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Wishlist;
use App\Models\UserMain;
use App\Models\Services;
use App\Models\ServiceCategory;
use App\Models\SellerPersonalPage;

use App\Http\AdyenClient;

use DB;
use Auth;
use Validator;
use Session;
use Flash;
use Mail;
use Log;
use File;
use PDF;

class SwishController extends Controller
{
  protected $checkout;

  function __construct(AdyenClient $checkout) {
      $this->checkout = $checkout->service;
  }

  public function index(){
      return view('pages.index');
  }

  public function preview(Request $request){
      $type = $request->type;
      return view('pages.preview')->with('type', $type);
  }

  public function checkout(Request $request){
      $data = array(
          'type' => $request->type,
          'clientKey' => env('CLIENT_KEY')
      );

      //return view('front.checkout_swish')->with($data);
      return view('Front/checkout_swish', $data);
  }

  // Result pages
  public function result(Request $request){
      $type = $request->type;
      return view('pages.result')->with('type', $type);
  }

  /* ################# API ENDPOINTS ###################### */
  // The API routes are exempted from app/Http/Middleware/VerifyCsrfToken.php

  public function getPaymentMethods(Request $request){
      error_log("Request for getPaymentMethods $request");

      $params = array(
          "merchantAccount" => env('MERCHANT_ACCOUNT'),
          "channel" => "Web"
      );

      $response = $this->checkout->paymentMethods($params);
      foreach($response as $key => $r)
      {
          if($key > 0)
          {
            unset($response[$key]);
          }
      }
      return $response;
  }

  public function initiatePayment(Request $request){
      error_log("Request for initiatePayment $request");

      $orderRef = uniqid();
      $params = array(
          "merchantAccount" => env('MERCHANT_ACCOUNT'),
          "channel" => "Web", // required
          "amount" => array(
              "currency" => $this->findCurrency(($request->paymentMethod)["type"]),
              "value" => 1000 // value is 10€ in minor units
          ),
          "reference" => $orderRef, // required
          // required for 3ds2 native flow
          "additionalData" => array(
              "allow3DS2" => "true"
          ),
          "origin" => "https://dev.tijara.se", // required for 3ds2 native flow
          "shopperIP" => $request->ip(),// required by some issuers for 3ds2
          // we pass the orderRef in return URL to get paymentData during redirects
          // required for 3ds2 redirect flow
          "returnUrl" => "https://dev.tijara.se/api/handleShopperRedirect?orderRef=${orderRef}",
          "paymentMethod" => $request->paymentMethod,
          "browserInfo" => $request->browserInfo // required for 3ds2
          );

      $response = $this->checkout->payments($params);

      return $response;
  }


  public function submitAdditionalDetails(Request $request){
      error_log("Request for submitAdditionalDetails $request");

      $payload = array("details" => $request->details, "paymentData" => $request->paymentData);

      $response = $this->checkout->paymentsDetails($payload);

      return $response;
  }

  public function handleShopperRedirect(Request $request){
      error_log("Request for handleShopperRedirect $request");

      $redirect = $request->all();

      $details = array();
      if (isset($redirect["redirectResult"])) {
        $details["redirectResult"] = $redirect["redirectResult"];
      } else if (isset($redirect["payload"])) {
        $details["payload"] = $redirect["payload"];
      }
      $orderRef = $request->orderRef;

      $payload = array("details" => $details);

      $response = $this->checkout->paymentsDetails($payload);

      switch ($response["resultCode"]) {
          case "Authorised":
              return redirect()->route('result', ['type' => 'success']);
          case "Pending":
          case "Received":
              return redirect()->route('result', ['type' => 'pending']);
          case "Refused":
              return redirect()->route('result', ['type' => 'failed']);
          default:
              return redirect()->route('result', ['type' => 'error']);
      }
  }

  /* ################# end API ENDPOINTS ###################### */

  // Util functions
  public function findCurrency($type){

      switch ($type) {
      case "ach":
          return "USD";
      case "wechatpayqr":
      case "alipay":
          return "CNY";
      case "dotpay":
          return "PLN";
      case "boletobancario":
      case "boletobancario_santander":
          return "BRL";
      default:
          return "SEK";
      }
  }
}

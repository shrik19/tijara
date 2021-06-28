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

use App\Models\UserMain;

use DB;
use Auth;
use Validator;
use Session;
use Flash;
use Mail;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user_id = Auth::guard('user')->id();
        $is_added = 1;
        $txt_msg = 'added';
        if(session('role_id') == 1)
        {
          $productVariant = $request->product_variant;
          $productQuntity = $request->product_quantity;
          if(!empty($productVariant))
          {
              $Products = VariantProduct::join('products', 'variant_product.product_id', '=', 'products.id')
        							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
        							  ->select(['products.*','variant_product.price','variant_product.id as variant_id','variant_product_attribute.id as variant_attribute_id'])
                        ->where('variant_product.id','=', $productVariant)
        							  ->where('products.status','=','active')
        							  ->get()->toArray();

              $checkExisting = TmpOrders::where('user_id','=',$user_id)->get()->toArray();

              $subTotal = 0;
              $shippingTotal = 0;
              $total = 0;
              $created_at = date('Y-m-d H:i:s');
              //Create Temp order
              $arrOrderInsert = [
                  'user_id'             => $user_id,
                  'sub_total'           => $subTotal,
                  'shipping_total'      => $shippingTotal,
                  'total'               => $total,
                  'payment_details'     => NULL,
                  'payment_status'      => NULL,
                  'order_status'        => 'PENDING',
                  'created_at'          => $created_at,
              ];

              $OrderId = TmpOrders::create($arrOrderInsert)->id;

              //Create Temp Order Details
              $arrOrderDetailsInsert = [
                  'order_id'             => $OrderId,
                  'user_id'              => $user_id,
                  'product_id'           => $Products[0]['id'],
                  'variant_id'           => $productVariant,
                  'variant_attribute_id' => $Products[0]['variant_attribute_id'],
                  'price'                => $Products[0]['price'],
                  'quantity'             => $productQuntity,
                  'shipping_type'        => NULL,
                  'shipping_amount'      => '0.00',
                  'status'               => 'PENDING',
                  'created_at'           => $created_at,
              ];

              $OrderDetailsId = TmpOrdersDetails::create($arrOrderDetailsInsert)->id;
            }
        }
        else
        {
          $is_added = 0;
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg));
        exit;
    }
}

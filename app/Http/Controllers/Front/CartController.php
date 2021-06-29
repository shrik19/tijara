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
        $txt_msg = trans('lang.shopping_cart_added');
        if($user_id && session('role_id') == 1)
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

              $subTotal = 0;
              $shippingTotal = 0;
              $total = 0;
              $created_at = date('Y-m-d H:i:s');
              //Create Temp order
              $checkExisting = TmpOrders::where('user_id','=',$user_id)->get()->toArray();
              if(!empty($checkExisting))
              {
                  $OrderId = $checkExisting[0]['id'];
              }
              else
              {
                //$subTotal = $Products[0]['price'];
                //$total = $subTotal+$shippingTotal;

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
              }

              //Create Temp Order Details
              $checkExistingProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->where('product_id','=',$Products[0]['id'])->where('variant_id','=',$productVariant)->where('variant_attribute_id','=',$Products[0]['variant_attribute_id'])->get()->toArray();
              if(!empty($checkExistingProduct))
              {
                  $OrderDetailsId = $checkExistingProduct[0]['id'];
                  $quantity  = ($checkExistingProduct[0]['quantity'] + $productQuntity);
                  $price     = $Products[0]['price'];
                  $arrOrderDetailsUpdate = [
                      'price'                => $price,
                      'quantity'             => $quantity,
                      'updated_at'           => $created_at,
                  ];

                  TmpOrdersDetails::where('id',$OrderDetailsId)->update($arrOrderDetailsUpdate);
              }
              else
              {
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

              //Update Order Totals
              $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
              if(!empty($checkExistingOrderProduct))
              {
                  foreach($checkExistingOrderProduct as $details)
                  {
                      $subTotal += $details['price'] * $details['quantity'];
                  }
              }

              $total = $subTotal+$shippingTotal;

              $arrOrderUpdate = [
                  'user_id'             => $user_id,
                  'sub_total'           => $subTotal,
                  'shipping_total'      => $shippingTotal,
                  'total'               => $total,
                  'payment_details'     => NULL,
                  'payment_status'      => NULL,
                  'order_status'        => 'PENDING',
                  'updated_at'          => $created_at,
              ];

              TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
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

    public function showCart()
    {
      $data = [];
      $orderDetails = [];
      $user_id = Auth::guard('user')->id();
      if($user_id)
      {
        $checkExisting = TmpOrders::where('user_id','=',$user_id)->get()->toArray();
        if(!empty($checkExisting))
        {
          $data['subTotal'] = $checkExisting[0]['sub_total'];
          $data['Total'] = $checkExisting[0]['total'];

            $OrderId = $checkExisting[0]['id'];
            $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
            if(!empty($checkExistingOrderProduct))
            {
                foreach($checkExistingOrderProduct as $details)
                {
                    //$orderDetails[] = $details;

                    $TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
                							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
                							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
                							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
                							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                                //->join('attributes',  'attributes.id', '=', 'variant_product_attribute.attribute_value_id')
                							  ->select(['products.*','categories.category_name', 'variant_product.image','variant_product.price','variant_product.id as variant_id'])
                							  ->where('products.status','=','active')
                							  ->where('categories.status','=','active')
                							  ->where('subcategories.status','=','active')
                                ->where('products.id','=',$details['product_id'])
                                ->where('variant_product.id','=',$details['variant_id'])
                							  ->orderBy('products.id', 'DESC')
                							  ->orderBy('variant_product.id', 'ASC')
                							  ->groupBy('products.id')
                							  ->offset(0)->limit(config('constants.Products_limits'))->get();
                                //dd(DB::getQueryLog());

                                //dd(count($TrendingProducts));
                		if(count($TrendingProducts)>0) {
                			foreach($TrendingProducts as $Product)
                      {
                        $productCategories = $this->getProductCategories($Product->id);
                        //dd($productCategories);

                				$product_link	=	url('/').'/product';

                				$product_link	.=	'/'.$productCategories[0]['category_slug'];
                        $product_link	.=	'/'.$productCategories[0]['subcategory_slug'];

                        $product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

                				$Product->product_link	=	$product_link;

                        $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                        $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
                        $Product->quantity = $details['quantity'];

                        $details['product'] = $Product;
                        $orderDetails[] = $details;
                			}
                		}
                }
            }
        }
        else {
          $data['subTotal'] = 0.00;
          $data['Total'] = 0.00;

        }
        //dd($orderDetails);
        $data['details'] = $orderDetails;


        return view('Front/shopping_cart', $data);
      }
      else {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

    function getProductCategories($productId)
    {
      $productCategories = [];
      if(!empty($productId))
      {
        $productCategories = ProductCategory::join('categories', 'categories.id', '=', 'category_products.category_id')
                           ->join('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')
                           ->select(['category_products.*','categories.category_name', 'categories.category_slug', 'subcategories.subcategory_name', 'subcategories.subcategory_slug'])
                           ->where('category_products.product_id','=',$productId)
                           ->get()->toArray();
      }
      return $productCategories;
    }

    public function removeCartProduct(Request $request)
    {
        $user_id = Auth::guard('user')->id();
        $is_removed = 1;
        $txt_msg =  trans('lang.shopping_cart_removed');;
        if($user_id && session('role_id') == 1)
        {
            $OrderDetailsId = $request->OrderDetailsId;
            $ExistingOrder = TmpOrdersDetails::where('id',$OrderDetailsId)->get()->toArray();
            if(empty($ExistingOrder))
            {
              $is_removed = 0;
              $txt_msg = trans('lang.shopping_cart_already_removed');
            }
            else
            {
              foreach($ExistingOrder as $orderDetails)
              {
                $OrderId = $orderDetails['order_id'];
                TmpOrdersDetails::where('id',$OrderDetailsId)->delete();

                //Update Order Totals
                $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
                if(!empty($checkExistingOrderProduct))
                {
                    $subTotal = 0;
                    $total = 0;
                    foreach($checkExistingOrderProduct as $details)
                    {
                        $subTotal += $details['price'] * $details['quantity'];
                    }

                    $total = $subTotal;
                    $created_at = date('Y-m-d H:i:s');
                    $arrOrderUpdate = [
                        'user_id'             => $user_id,
                        'sub_total'           => $subTotal,
                        'shipping_total'      => 0.00,
                        'total'               => $total,
                        'payment_details'     => NULL,
                        'payment_status'      => NULL,
                        'order_status'        => 'PENDING',
                        'updated_at'          => $created_at,
                    ];

                    TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
                }
                else
                {
                  TmpOrders::where('id',$OrderId)->delete();
                }
              }
            }
        }
        else
        {
          $is_removed = 0;
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_removed,'msg'=>$txt_msg));
        exit;
    }

    function updateCartProduct(Request $request)
    {
      $user_id = Auth::guard('user')->id();
      $is_updated = 1;
      $txt_msg =  trans('lang.shopping_cart_updated');;
      if($user_id && session('role_id') == 1)
      {
          $OrderDetailsId = $request->OrderDetailsId;
          $Quantity = $request->quantity;
          $ExistingOrder = TmpOrdersDetails::where('id',$OrderDetailsId)->get()->toArray();

          foreach($ExistingOrder as $orderDetails)
          {
            $created_at = date('Y-m-d H:i:s');
            $OrderId = $orderDetails['order_id'];
            $Products = VariantProduct::join('products', 'variant_product.product_id', '=', 'products.id')
                      ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                      ->select(['products.*','variant_product.price','variant_product.id as variant_id','variant_product_attribute.id as variant_attribute_id'])
                      ->where('variant_product.id','=', $orderDetails['variant_id'])
                      ->where('products.status','=','active')
                      ->get()->toArray();

            $price     = $Products[0]['price'];
            $arrOrderDetailsUpdate = [
                'price'                => $price,
                'quantity'             => $Quantity,
                'updated_at'           => $created_at,
            ];

            TmpOrdersDetails::where('id',$OrderDetailsId)->update($arrOrderDetailsUpdate);

            //Update Order Totals
            $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
            if(!empty($checkExistingOrderProduct))
            {
                $subTotal = 0;
                $total = 0;
                foreach($checkExistingOrderProduct as $details)
                {
                    $subTotal += $details['price'] * $details['quantity'];
                }

                $total = $subTotal;

                $arrOrderUpdate = [
                    'user_id'             => $user_id,
                    'sub_total'           => $subTotal,
                    'shipping_total'      => 0.00,
                    'total'               => $total,
                    'payment_details'     => NULL,
                    'payment_status'      => NULL,
                    'order_status'        => 'PENDING',
                    'updated_at'          => $created_at,
                ];

                TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
            }
            else
            {
              TmpOrders::where('id',$OrderId)->delete();
            }
          }

      }
      else
      {
        $is_updated = 0;
        $txt_msg = trans('errors.login_buyer_required');
      }
      echo json_encode(array('status'=>$is_updated,'msg'=>$txt_msg));
      exit;
    }
}

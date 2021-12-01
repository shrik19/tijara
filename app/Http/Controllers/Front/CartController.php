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
use App\Models\AdminOrders;
use App\Models\TmpAdminOrders;

use App\Http\AdyenClient;
use Stripe;


use DB;
use Auth;
use Validator;
use Session;
use Flash;
use Mail;
use Log;
use File;
use PDF;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $is_other_seller = 0;
        $txt_msg = trans('lang.shopping_cart_added');
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
          $productVariant = $request->product_variant;
          $productQuntity = $request->product_quantity;
         
          if(!empty($request->from_wishlist) && $request->from_wishlist == 1)
          { 
            $wishlistId = Wishlist::where([['user_id','=',$user_id],['variant_id','=',$productVariant]])->first();
            if(!empty($wishlistId))
            {
              $tmpWishlist = $wishlistId->toArray();
              $tmpWishlistDetails = Wishlist::find($tmpWishlist['id']);
              $tmpWishlistDetails->delete();
            }
            
          }

          if(!empty($productVariant))
          { 
              $Products = VariantProduct::join('products', 'variant_product.product_id', '=', 'products.id')
                        ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                        ->select(['products.*','variant_product.price','variant_product.id as variant_id','variant_product.quantity','variant_product_attribute.id as variant_attribute_id'])
                        ->where('variant_product.id','=', $productVariant)
                        ->where('products.status','=','active')
                        ->get()->toArray();
              
          
              if($user_id == $Products[0]['user_id'])
              {                
                $is_added = 0;
                $txt_msg = trans('errors.same_buyer_product_err');
                echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                exit;
              } 

            
              $existingOrder = 0;
              $checkExistingOrderDetails = TmpOrdersDetails::
              join('temp_orders', 'temp_orders_details.order_id', '=', 'temp_orders.id')
              ->join('products', 'temp_orders_details.product_id', '=', 'products.id')
              ->select(['products.user_id','temp_orders_details.order_id'])
              ->where('temp_orders_details.user_id','=',$user_id)
              ->where('temp_orders.show_in_cart','=',1)->get()->toArray();

               
              if(!empty($checkExistingOrderDetails))
              {
                  foreach($checkExistingOrderDetails as $details)
                  {
                      if($Products[0]['user_id'] == $details['user_id'])
                      {
                        // $is_added = 0;
                        // $txt_msg = trans('errors.same_seller_product_err');
                        // echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                        // exit;
                        $existingOrder = $details['order_id'];
                        break;
                      }
                  }
              }

              $subTotal = 0;
              $shippingTotal = 0;
              $total = 0;
              $created_at = date('Y-m-d H:i:s');
              //Create Temp order
              $checkExisting = TmpOrders::where('user_id','=',$user_id)->get()->toArray();

              //if(!empty($checkExisting) && $is_other_seller == 0)
              if(!empty($existingOrder))
              {
                  $OrderId = $existingOrder;
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

              $product_shipping_type = '';
              $product_shipping_amount = 0;
              
              //Create Temp Order Details
              $checkExistingProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->where('product_id','=',$Products[0]['id'])->where('variant_id','=',$productVariant)->get()->toArray();

              if(!empty($checkExistingProduct))
              {
                  $OrderDetailsId = $checkExistingProduct[0]['id'];
                  $quantity  = ($checkExistingProduct[0]['quantity'] + $productQuntity);
                  $price     = $Products[0]['price'];

                  if(!empty($Products[0]['discount']))
                  {
                    $discount = number_format((($Products[0]['price'] * $Products[0]['discount']) / 100),2,'.','');
                    $discount_price = $Products[0]['price'] - $discount;
                  }
                  else
                  {
                    $discount_price = $price;
                  }   
                  
                  if($quantity > $Products[0]['quantity'])
                  {
                    $is_added = 0;
                    $txt_msg = trans('errors.quantity_err').$Products[0]['quantity'].')';
                    echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                    exit;
                  } 
                  $arrOrderDetailsUpdate = [
                      'price'                => $discount_price,
                      'quantity'             => $quantity,
                      'shipping_type'        => $product_shipping_type,
                      'shipping_amount'      => $product_shipping_amount,
                      'updated_at'           => $created_at,
                  ];

                  TmpOrdersDetails::where('id',$OrderDetailsId)->update($arrOrderDetailsUpdate);
              }
              else
              {

                if($productQuntity > $Products[0]['quantity'])
                  {
                    $is_added = 0;
                    $txt_msg = trans('errors.quantity_err').$Products[0]['quantity'].')';
                    echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                    exit;
                  } 
                $variantAttrs = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
                       ->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
                       ->where([['variant_id','=',$productVariant],['product_id','=',$Products[0]['id']]])->get();
                
                $attrIds = '';  
                foreach($variantAttrs as $variantAttr)
                {
                  if($attrIds == '')
                  {
                    if (strpos($attrIds, $variantAttr->name.' : '.$variantAttr->attribute_values) !== false) 
                    {}
                    else $attrIds = $variantAttr->name.' : '.$variantAttr->attribute_values;
                  }
                  else
                  {
                    if (strpos($attrIds, $variantAttr->name.' : '.$variantAttr->attribute_values) !== false) 
                    {}
                    else $attrIds.= ', '.$variantAttr->name.' : '.$variantAttr->attribute_values;
                  }
                }  
                
                  if(!empty($Products[0]['discount']))
                  {
                    $discount = number_format((($Products[0]['price'] * $Products[0]['discount']) / 100),2,'.','');
                    $discount_price = $Products[0]['price'] - $discount;
                  }
                  else
                  {
                    $discount_price = $Products[0]['price'];
                  } 
                       
                $arrOrderDetailsInsert = [
                    'order_id'             => $OrderId,
                    'user_id'              => $user_id,
                    'product_id'           => $Products[0]['id'],
                    'variant_id'           => $productVariant,
                    'variant_attribute_id' => '['.$attrIds.']',
                    'price'                => $discount_price,
                    'quantity'             => $productQuntity,
                    'shipping_type'        => $product_shipping_type,
                    'shipping_amount'      => $product_shipping_amount,
                    'status'               => 'PENDING',
                    'created_at'           => $created_at,
                ];

                $OrderDetailsId = TmpOrdersDetails::create($arrOrderDetailsInsert)->id;
              }

              //Update Order Totals
              $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();

              if(!empty($checkExistingOrderProduct))
              {
                  foreach($checkExistingOrderProduct as $details)
                  {
                     $product_shipping_amount = 0;
                      //Get Seller Shipping Informations
                      $SellerShippingData = UserMain::select('users.id','users.free_shipping','users.shipping_method','users.shipping_charges')->where('users.id','=',$details['product_user'])->first()->toArray();
         
                       // where products.id = $details['product_id']
                      if(!empty($details['shipping_method']) && !empty($details['shipping_charges']))
                      {
                        if($details['shipping_method'] == trans('users.flat_shipping_charges'))
                        {
                          $product_shipping_type = 'flat';
                          $product_shipping_amount = (float)$details['shipping_charges'];
                        }
                        else if($details['shipping_method'] == trans('users.prcentage_shipping_charges'))
                        {
                          $product_shipping_type = 'percentage';
                          $product_shipping_amount =((float)$details['price'] * $details['shipping_charges']) / 100;
                        }
                      }
                      else if(empty($SellerShippingData['free_shipping']))
                      {
                          if(!empty($SellerShippingData['shipping_method']) && !empty($SellerShippingData['shipping_charges']))
                          {
                              if($SellerShippingData['shipping_method'] == trans('users.flat_shipping_charges'))
                              {
                                $product_shipping_type = 'flat';
                                $product_shipping_amount = (float)$SellerShippingData['shipping_charges'];
                              }
                              elseif($SellerShippingData['shipping_method'] == trans('users.prcentage_shipping_charges'))
                              {
                                $product_shipping_type = 'percentage';
                                $product_shipping_amount = ((float)$details['price'] * $SellerShippingData['shipping_charges']) / 100;
                              }
                          }else{
                             $product_shipping_type = 'free'; 
                          }
                      }
                      else
                      {
                        $product_shipping_type = 'free';
                      }

                      $arrOrderDetailsUpdate = [
                        'shipping_type'        => $product_shipping_type,
                        'shipping_amount'      => $product_shipping_amount,
                      ];

                      TmpOrdersDetails::where('id',$details['id'])->update($arrOrderDetailsUpdate);

                      $subTotal += $details['price'] * $details['quantity'];
                      $shippingTotal += $product_shipping_amount;
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
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
    }

    public function showCart()
    {
      $data = [];
      $orderDetails = [];
      $user_id = Auth::guard('user')->id();
      $is_buyer_product = 0;
      if($user_id)
      {
        $checkExisting = TmpOrders::where('user_id','=',$user_id)->where('show_in_cart','=',1)->get()->toArray();

        if(!empty($checkExisting))
        {
          $subTotal       = 0;
          $shippingTotal  = 0;
          $total          = 0;
          $product_shipping_type = '';
          $product_shipping_amount = 0;

          foreach($checkExisting as $tmpOrder)
          { 

            $OrderId = $tmpOrder['id'];
           // DB::enableQueryLog();
            //Update Order Totals

            /* $checkExistingOrderProduct = TmpOrdersDetails::
            join('products', 'temp_orders_details.product_id', '=', 'products.id')->join('variant_product', 'variant_product.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->where('variant_product.quantity','>',0)->groupBy('temp_orders_details.variant_id')->get()->toArray();*/

             $checkExistingOrderProduct = TmpOrdersDetails::
            join('products', 'temp_orders_details.product_id', '=', 'products.id')->join("variant_product",function($join){
            $join->on("variant_product.product_id","=","products.id")
                ->on("variant_product.id","=","temp_orders_details.variant_id");
            })->select(['products.user_id as product_user','products.shipping_method','products.is_pick_from_store','products.store_pick_address','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->where('variant_product.quantity','>',0)->groupBy('temp_orders_details.variant_id')->get()->toArray();


           // print_r(DB::getQueryLog());exit;
            //  echo "<pre>";print_r($checkExistingOrderProduct);exit;
            if(!empty($checkExistingOrderProduct))
            {
                $subTotal       = 0;
                $shippingTotal  = 0;
                $total          = 0;

                foreach($checkExistingOrderProduct as $details)
                {
                  
                    $product_shipping_amount = 0;
                    $discount_price = 0;
                    $checkVariant = VariantProduct::where('id','=',$details['variant_id'])->get()->toArray();
                    if(empty($checkVariant))
                    {
                      $tmpOrderDetails = TmpOrdersDetails::find($details['id']);
                      $tmpOrderDetails->delete();
                      continue;
                    }

                    if(!empty($details['discount']))
                    {
                      $discount = number_format((($details['product_price'] * $details['discount']) / 100),2,'.','');
                      $discount_price = $details['product_price'] - $discount;
                    }
                    else
                    {
                      $discount_price = $details['product_price'];
                    } 

                    //Get Seller Shipping Informations
                    $SellerShippingData = UserMain::select('users.id','users.free_shipping','users.shipping_method','users.shipping_charges','users.is_pick_from_store')->where('users.id','=',$details['product_user'])->first();
                    
                    if(!empty($details['shipping_method']) && !empty($details['shipping_charges'])){
                      if($details['shipping_method'] == "Platta fraktkostnader")
                      {
                 
                        $product_shipping_type = 'flat';
                        $product_shipping_amount = $details['shipping_charges'];
                      }
                      else if($details['shipping_method'] ==  'Andel fraktkostnader')
                      {
                    
                        $product_shipping_type = 'percentage';
                        $product_shipping_amount = ((float)$discount_price * $details['shipping_charges']) / 100;
                      }
                  
                    }
                    else if(empty($SellerShippingData['free_shipping']))
                    { 
                        if(!empty($SellerShippingData['shipping_method']) && !empty($SellerShippingData['shipping_charges']))
                        {
                          //echo $SellerShippingData['shipping_method'];exit;
                            /*if($SellerShippingData['shipping_method'] == trans('users.flat_shipping_charges'))*/
                            if($SellerShippingData['shipping_method'] == "Platta fraktkostnader")
                            
                            {

                              $product_shipping_type = 'flat';
                              $product_shipping_amount = $SellerShippingData['shipping_charges'];
                            }
                           /* elseif($SellerShippingData['shipping_method'] == trans('users.prcentage_shipping_charges'))*/
                            elseif($SellerShippingData['shipping_method'] == 'Andel fraktkostnader')
                            {
                              $product_shipping_type = 'percentage';
                              $product_shipping_amount = ((float)$discount_price * $SellerShippingData['shipping_charges']) / 100;
                            }
                        }else{
                          $product_shipping_type='free';
                        }

                    }  
                    else
                    {
                      $product_shipping_type = 'free';
                    }

                    //echo "<pre>";print_r($details['is_pick_from_store']);exit;
                    if(!empty($details['is_pick_from_store']) && ($details['is_pick_from_store'] ==1))
                    {
                      $product_shipping_amount = 0;
                      $product_shipping_type='free';
                    }elseif(!empty($SellerShippingData['is_pick_from_store']) && ($SellerShippingData['is_pick_from_store'] ==1))
                    {
                      $product_shipping_amount=0;
                      $product_shipping_type='free';
                    }
                 

                    $arrOrderDetailsUpdate = [
                      'price'                => $discount_price,
                      'shipping_type'        => $product_shipping_type,
                      'shipping_amount'      => $product_shipping_amount,
                    ];
                    //echo "<pre>";print_r($arrOrderDetailsUpdate);exit;
                    TmpOrdersDetails::where('id',$details['id'])->update($arrOrderDetailsUpdate);

                    $subTotal += $discount_price * $details['quantity'];
                    $shippingTotal += $product_shipping_amount;
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
                ];
      
                TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
          }
          

          $updatedOrder = TmpOrders::where('id','=',$tmpOrder['id'])->first()->toArray();

          //$data['subTotal'] = $checkExisting[0]['sub_total'];
          //$data['Total'] = $checkExisting[0]['total'];
          //$data['shippingTotal'] = $checkExisting[0]['shipping_total'];

          $orderDetails[$OrderId] = ['subTotal' => $updatedOrder['sub_total'], 'Total' => $updatedOrder['total'], 'shippingTotal' => $updatedOrder['shipping_total']];

          //$OrderId = $checkExisting[0]['id'];
          $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
          if(!empty($checkExistingOrderProduct))
          {
              foreach($checkExistingOrderProduct as $details)
              {

                  //$orderDetails[] = $details;

                  $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                              ->get();
                              //dd(DB::getQueryLog());

                              //dd(count($TrendingProducts));
                  if(count($TrendingProducts)>0) {
                    foreach($TrendingProducts as $Product)
                    {
                      $productCategories = $this->getProductCategories($Product->id);
                      //dd($productCategories);

                      $product_link = url('/').'/product';

                      $product_link .=  '/'.$productCategories[0]['category_slug'];
                      $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                      $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                      $Product->product_link  = $product_link;

                      $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.role_id','store_name','is_pick_from_store','store_pick_address','store_pick_address')->where('users.id','=',$Product->user_id)->first();
                      if($Product['is_pick_from_store'] != 1){
                      // echo "<pre>";print_r($Product['is_pick_from_store']);exit;
                        $Product->is_pick_from_store = $SellerData['is_pick_from_store'];
                        $Product->store_pick_address = $SellerData['store_pick_address'];
                      }
                      if($SellerData['role_id'] == 1)
                      {
                        $is_buyer_product = 1;
                        $orderDetails[$OrderId]['is_buyer_product'] = 1;
                      }
                      else
                      {
                        $orderDetails[$OrderId]['is_buyer_product'] = 0;
                      }
                      $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                      if(!empty($SellerData['store_name'])){
                         $Product->store_name  = $SellerData['store_name'];
                       }else{
                          $Product->store_name  = $Product->seller;
                       }
                     
                      $Product->quantity = $details['quantity'];
                      $Product->image    = explode(',',$Product->image)[0];
                   
                      $sellerLogoImage = SellerPersonalPage::where('user_id',$Product->user_id)->first();

                      $seller_name = $SellerData['fname'].' '.$SellerData['lname'];

                      $seller_name = str_replace( array( '\'', '"', 
                      ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                      $seller_name = str_replace(" ", '-', $seller_name);
                      $seller_name = strtolower($seller_name);
                      
                      $seller_link = url('/').'/seller/'.$seller_name."/". base64_encode($Product->user_id)."/products"; 
                      $Product->seller_link  = $seller_link;
                      if(!empty($sellerLogoImage['logo'])){
                          $details['sellerLogo'] = $sellerLogoImage['logo'];
                      }
                      $details['product'] = $Product;
                      $orderDetails[$OrderId]['details'][] = $details;
                      
                    }
                  }
              }
          }
          else
          {
            unset($orderDetails[$OrderId]);
            $tmpOrder = TmpOrders::find($OrderId);
            $tmpOrder->delete();
            
          }
        }
        }
        
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

    function getServiceCategories($serviceId)
    {
      $serviceCategories = [];
      if(!empty($serviceId))
      {
        $serviceCategories = ServiceCategory::join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
                           ->join('serviceSubcategories', 'serviceSubcategories.id', '=', 'category_services.subcategory_id')
                           ->select(['category_services.*','servicecategories.category_name', 'servicecategories.category_slug', 'serviceSubcategories.subcategory_name', 'serviceSubcategories.subcategory_slug'])
                           ->where('category_services.service_id','=',$serviceId)
                           ->get()->toArray();

      }
      return $serviceCategories;
    }

    public function removeCartProduct(Request $request)
    {
        $user_id = Auth::guard('user')->id();
        $is_removed = 1;
        $txt_msg =  trans('lang.shopping_cart_removed');;
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
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
      $txt_msg =  trans('lang.shopping_cart_updated');
      $is_login_err = 0;
      if($user_id && Auth::guard('user')->getUser()->role_id == 1)
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
                      ->select(['products.*','variant_product.price','variant_product.quantity','variant_product.id as variant_id','variant_product_attribute.id as variant_attribute_id'])
                      ->where('variant_product.id','=', $orderDetails['variant_id'])
                      ->where('products.status','=','active')
                      ->get()->toArray();

            if($Quantity > $Products[0]['quantity'])
            {
              $is_updated = 0;
              $is_login_err = 0;
              $txt_msg = trans('errors.quantity_err').$Products[0]['quantity'].')';
              echo json_encode(array('status'=>$is_updated,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
              exit;
            }           

            $price     = $Products[0]['price'];
            if(!empty($Products[0]['discount']))
            {
              $discount = number_format((($Products[0]['price'] * $Products[0]['discount']) / 100),2,'.','');
              $discount_price = $Products[0]['price'] - $discount;
            }
            else
            {
              $discount_price = $price;
            } 
            $arrOrderDetailsUpdate = [
                'price'                => $discount_price,
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
        $is_login_err = 1;
        $txt_msg = trans('errors.login_buyer_required');
      }
      echo json_encode(array('status'=>$is_updated,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
      exit;
    }

    public function showPaymentOptions($orderId) {
      $user_id = Auth::guard('user')->id();
      if($user_id && Auth::guard('user')->getUser()->role_id == 1)
      {
        $order_id = base64_decode($orderId);
       // echo $order_id;exit;
        $checkExisting = TmpOrders::join('temp_orders_details', 'temp_orders.id', '=', 'temp_orders_details.order_id')
                        ->join('products', 'products.id', '=', 'temp_orders_details.product_id')
                        ->join('users', 'users.id', '=', 'products.user_id')
                        ->select(['users.*'])                          
                        ->where('temp_orders.id','=',$order_id)->first();

        if(empty($checkExisting))
        {
          return redirect(route('frontShowCart'));
        }
        Session::put('current_checkout_order_id', $order_id);
        $paymentOptionAvailable = array(); $isPaymentOptionAvailable= 0;

        if($checkExisting->klarna_username!='' && $checkExisting->klarna_password!='') {
            $paymentOptionAvailable[]='klarna';
            $isPaymentOptionAvailable = 1;
        }
        if($checkExisting->swish_api_key!='' && $checkExisting->swish_merchant_account!='' && $checkExisting->swish_client_key!='') {
          $paymentOptionAvailable[]='swish';
          $isPaymentOptionAvailable = 1;
        }
        if($checkExisting->strip_api_key!='' && $checkExisting->strip_secret!='' ) {
          $paymentOptionAvailable[]='strip';
          $isPaymentOptionAvailable = 1;
        }
        if($isPaymentOptionAvailable==0) {
          $blade_data['error_messages']= trans('errors.seller_credentials_err');
          return view('Front/payment_error',$blade_data); 
        }

        //if(count($paymentOptionAvailable)>1) 
        $customerDetails=UserMain::where('id',$user_id)->first();
        {
          
         // DB::enableQueryLog();
           $checkExistingOrder = TmpOrders::where('user_id','=',$customerDetails->id)->where('show_in_cart','=',1)->get()->toArray();
            if(!empty($checkExistingOrder))
          {
            $subTotal       = 0;
            $shippingTotal  = 0;
            $total          = 0;
            $product_shipping_type = '';
            $product_shipping_amount = 0;

            foreach($checkExistingOrder as $tmpOrder)
            { 

            //  $OrderId = $tmpOrder['id'];

               $checkExistingOrderProduct = TmpOrdersDetails::
              join('products', 'temp_orders_details.product_id', '=', 'products.id')
              ->join('temp_orders', 'temp_orders.id', '=', 'temp_orders_details.order_id')
              ->join("variant_product",function($join){
              $join->on("variant_product.product_id","=","products.id")
                  ->on("variant_product.id","=","temp_orders_details.variant_id");
              })->select(['products.user_id as product_user','products.shipping_method','products.is_pick_from_store','products.store_pick_address','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*','variant_product.image','products.title','temp_orders.sub_total','temp_orders.shipping_total','temp_orders.total'])->where('order_id','=',$order_id)->where('temp_orders_details.user_id','=',$user_id)->where('variant_product.quantity','>',0)->orderBy('temp_orders_details.id','ASC')->groupBy('temp_orders_details.variant_id')->get()->toArray();

              if(!empty($checkExistingOrderProduct))
              {
                $subTotal       = 0;
                $shippingTotal  = 0;
                $total          = 0; 
                foreach($checkExistingOrderProduct as $details)
                { 
                  $product_shipping_amount = 0;
                  $discount_price = 0;
                  $checkVariant = VariantProduct::where('id','=',$details['variant_id'])->get()->toArray();
                  if(empty($checkVariant))
                  {
                    $tmpOrderDetails = TmpOrdersDetails::find($details['id']);
                    $tmpOrderDetails->delete();
                    continue;
                  }
                  if(!empty($details['discount']))
                  {
                    $discount = number_format((($details['product_price'] * $details['discount']) / 100),2,'.','');
                    $discount_price = $details['product_price'] - $discount;
                  }
                  else
                  {
                    $discount_price = $details['product_price'];
                  } 

                  //Get Seller Shipping Informations
                  $SellerShippingData = UserMain::select('users.id','users.free_shipping','users.shipping_method','users.shipping_charges','users.is_pick_from_store')->where('users.id','=',$details['product_user'])->first()->toArray();
                   //echo "<pre>";print_r($SellerShippingData);exit;
                   if(!empty($details['shipping_method']) && !empty($details['shipping_charges'])){
                      if($details['shipping_method'] ==  "Platta fraktkostnader")
                      {
                        $product_shipping_type = 'flat';
                        $product_shipping_amount = $details['shipping_charges'];
                      }
                      else if($details['shipping_method'] ==  'Andel fraktkostnader')
                      {
                  
                        $product_shipping_type = 'percentage';
                        $product_shipping_amount = ((float)$discount_price * $details['shipping_charges']) / 100;
                      }
                  
                    }else if(empty($SellerShippingData['free_shipping']))
                    { 
                        if(!empty($SellerShippingData['shipping_method']) && !empty($SellerShippingData['shipping_charges']))
                        {
                          //echo $SellerShippingData['shipping_method'];exit;
                            /*if($SellerShippingData['shipping_method'] == trans('users.flat_shipping_charges'))*/
                            if($SellerShippingData['shipping_method'] == "Platta fraktkostnader")
                            
                            {

                              $product_shipping_type = 'flat';
                              $product_shipping_amount = $SellerShippingData['shipping_charges'];
                            }
                           /* elseif($SellerShippingData['shipping_method'] == trans('users.prcentage_shipping_charges'))*/
                            elseif($SellerShippingData['shipping_method'] == 'Andel fraktkostnader')
                            {
                              $product_shipping_type = 'percentage';
                              $product_shipping_amount = ((float)$discount_price * $SellerShippingData['shipping_charges']) / 100;
                            }
                        }else{
                          $product_shipping_type='free';
                        }

                    } else
                    {
                      $product_shipping_type = 'free';
                    }

                    if(!empty($details['is_pick_from_store']) && ($details['is_pick_from_store'] ==1))
                    {
                      $product_shipping_amount = 0;
                      $product_shipping_type='free';
                    }elseif(!empty($SellerShippingData['is_pick_from_store']) && ($SellerShippingData['is_pick_from_store'] ==1))
                    {
                      $product_shipping_amount=0;
                      $product_shipping_type='free';
                    }

                    $arrOrderDetailsUpdate = [
                      'price'                => $discount_price,
                      'shipping_type'        => $product_shipping_type,
                      'shipping_amount'      => $product_shipping_amount,
                    ];
                    //echo "<pre>";print_r($arrOrderDetailsUpdate);exit;
                    TmpOrdersDetails::where('id',$details['id'])->update($arrOrderDetailsUpdate);

                    $subTotal += $discount_price * $details['quantity'];
                    $shippingTotal += $product_shipping_amount;
                }//foreach end

                $total = $subTotal+$shippingTotal;

                $arrOrderUpdate = [
                    'user_id'             => $user_id,
                    'sub_total'           => $subTotal,
                    'shipping_total'      => $shippingTotal,
                    'total'               => $total,
                    'payment_details'     => NULL,
                    'payment_status'      => NULL,
                    'order_status'        => 'PENDING',
                ];
      
                TmpOrders::where('id',$order_id)->update($arrOrderUpdate);
              }//if end
               $updatedOrder = TmpOrders::where('id','=',$order_id)->first()->toArray();
               $orderDetails[$order_id] = ['subTotal' => $updatedOrder['sub_total'], 'Total' => $updatedOrder['total'], 'shippingTotal' => $updatedOrder['shipping_total']];
              // DB::enableQueryLog();
              // $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$order_id)->where('user_id','=',$user_id)->get()->toArray();
               //echo "<pre>";print_r($checkExistingOrderProduct);exit;
              // dd(DB::getQueryLog());
              if(!empty($checkExistingOrderProduct)){
                foreach($checkExistingOrderProduct as $details){
                  //echo "<pre>";print_r($details);
                 
                  $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                              ->where('products.user_id','=',$checkExisting->id)
                              ->orderBy('products.id', 'DESC')
                              ->orderBy('variant_product.id', 'ASC')
                              ->groupBy('products.id')
                              ->get();
                
                  if(count($TrendingProducts)>0) {
                    foreach($TrendingProducts as $Product)
                    {
                      $productCategories = $this->getProductCategories($Product->id);
                      //dd($productCategories);

                      $product_link = url('/').'/product';

                      $product_link .=  '/'.$productCategories[0]['category_slug'];
                      $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                      $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                      $Product->product_link  = $product_link;

                      $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.role_id','store_name','is_pick_from_store','store_pick_address','store_pick_address')->where('users.id','=',$Product->user_id)->first()->toArray();
                      if($Product['is_pick_from_store'] != 1){
                      // echo "<pre>";print_r($Product['is_pick_from_store']);exit;
                        $Product->is_pick_from_store = $SellerData['is_pick_from_store'];
                        $Product->store_pick_address = $SellerData['store_pick_address'];
                      }
                      if($SellerData['role_id'] == 1)
                      {
                        $is_buyer_product = 1;
                        $orderDetails[$order_id]['is_buyer_product'] = 1;
                      }
                      else
                      {
                        $orderDetails[$order_id]['is_buyer_product'] = 0;
                      }
                      $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                      if(!empty($SellerData['store_name'])){
                         $Product->store_name  = $SellerData['store_name'];
                       }else{
                          $Product->store_name  = $Product->seller;
                       }
                     
                      $Product->quantity = $details['quantity'];
                      $Product->image    = explode(',',$Product->image)[0];
                   
                      $sellerLogoImage = SellerPersonalPage::where('user_id',$Product->user_id)->first();

                      $seller_name = $SellerData['fname'].' '.$SellerData['lname'];

                      $seller_name = str_replace( array( '\'', '"', 
                      ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                      $seller_name = str_replace(" ", '-', $seller_name);
                      $seller_name = strtolower($seller_name);
                      
                      $seller_link = url('/').'/seller/'.$seller_name."/". base64_encode($Product->user_id)."/products"; 
                      $Product->seller_link  = $seller_link;
                      if(!empty($sellerLogoImage['logo'])){
                          $details['sellerLogo'] = $sellerLogoImage['logo'];
                      }
                      $details['product'] = $Product;
                      $orderDetails[$order_id]['details'][] = $details;
                      
                    }
                  }
                }//endforeach 
               
              }///endif
              else
              {
                unset($orderDetails[$order_id]);
                $tmpOrder = TmpOrders::find($order_id);
                $tmpOrder->delete();
                
              }
            }//foreach end
          } 

          $data['orderId']=$order_id;
          $data['payment_options']  = $paymentOptionAvailable;
          $data['details']= $customerDetails;
          $data['seller_data'] = $checkExisting;
          $data['orderDetails'] = $orderDetails;
          return view('Front/shopping_payment_options',$data);
        }
       // else 
        {
         // return redirect(route('frontShowCheckout',['id' => base64_encode($orderId),'paymentoption'=>$paymentOptionAvailable[0]]));
            //return redirect(route('frontShowCheckout',['id'=>base64_encode($orderId),'paymetoption'=>$paymentOptionAvailable[0]]));
            
        }

          
      }
      else
      {
        return redirect(route('frontShowCart'));
      }
    }
    

    public function showCheckout($orderId,$paymetOption,Request $request)
    {
      $data = [];
      
      $user_id = Auth::guard('user')->id();
      if($user_id && Auth::guard('user')->getUser()->role_id == 1)
      {
        $orderId = base64_decode($orderId);
        Session::put('current_checkout_order_id', $orderId);
        $checkExisting = TmpOrders::select('id')->where('id','=',$orderId)->get()->toArray();
        if(empty($checkExisting))
        {
          return redirect(route('frontShowCart'));
        }
        else
        {
          $OrderId = $checkExisting[0]['id'];
          if($request->billing_given_name) {
            $billing_address= [];
            $billing_address['given_name'] = $request->billing_given_name;
            $billing_address['family_name'] = $request->billing_family_name;
            $billing_address['email'] = $request->billing_email;
            $billing_address['street_address'] = $request->billing_address;
            $billing_address['postal_code'] = $request->billing_postcode;
            $billing_address['city'] = $request->billing_city;
            $billing_address['phone'] = $request->billing_phone_number;

            $shipping_address= [];
            $shipping_address['given_name'] = $request->shipping_given_name;
            $shipping_address['family_name'] = $request->shipping_family_name;
            $shipping_address['email'] = $request->shipping_email;
            $shipping_address['street_address'] = $request->shipping_address;
            $shipping_address['postal_code'] = $request->shipping_postcode;
            $shipping_address['city'] = $request->shipping_city;
            $shipping_address['phone'] = $request->shipping_phone_number;
            
            $address = ['billing' => json_encode($billing_address), 'shipping' => json_encode($shipping_address)];

            
            $arrOrderUpdate = [
            
              'address'  => json_encode($address),
              
            ];
    
            TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
          }
          $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
          if(empty($checkExistingOrderProduct))
          {
            return redirect(route('frontShowCart'));
          }
          else
          {
            $subTotal       = 0;
            $shippingTotal  = 0;
            $total          = 0;
            $product_shipping_type = '';
            $product_shipping_amount = 0;

            $OrderId = $checkExisting[0]['id'];

            //Update Order Totals
            // $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();


           /* $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->join('variant_product', 'variant_product.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->groupBy('temp_orders_details.variant_id')->get()->toArray();*/


          $checkExistingOrderProduct = TmpOrdersDetails::
            join('products', 'temp_orders_details.product_id', '=', 'products.id')->join("variant_product",function($join){
            $join->on("variant_product.product_id","=","products.id")
                ->on("variant_product.id","=","temp_orders_details.variant_id");
            })->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->where('variant_product.quantity','>',0)->groupBy('temp_orders_details.variant_id')->get()->toArray();


            if(!empty($checkExistingOrderProduct))
            {
                foreach($checkExistingOrderProduct as $details)
                {
                    $checkVariant = VariantProduct::where('id','=',$details['variant_id'])->get()->toArray();
                    if(empty($checkVariant))
                    {
                      $tmpOrderDetails = TmpOrdersDetails::find($details['id']);
                      $tmpOrderDetails->delete();
                      continue;
                    }
                    if(!empty($details['discount']))
                    {
                      $discount = number_format((($details['product_price'] * $details['discount']) / 100),2,'.','');
                      $discount_price = $details['product_price'] - $discount;
                    }
                    else
                    {
                      $discount_price = $details['product_price'];
                    }

                    //Get Seller Shipping Informations
                    $SellerShippingData = UserMain::select('users.id','users.free_shipping','users.shipping_method','users.shipping_charges')->where('users.id','=',$details['product_user'])->first()->toArray();

                    if(!empty($details['shipping_method']) && !empty($details['shipping_charges']))
                    {
                      if($details['shipping_method'] == trans('users.flat_shipping_charges'))
                      {
                        $product_shipping_type = 'flat';
                        $product_shipping_amount = $details['shipping_charges'];
                      }
                      else if($details['shipping_method'] == trans('users.prcentage_shipping_charges'))
                      {
                        $product_shipping_type = 'percentage';
                        $product_shipping_amount = ((float)$discount_price * $details['shipping_charges']) / 100;
                      }
                    }
                    else if(empty($SellerShippingData['free_shipping']))
                    {
                        if(!empty($SellerShippingData['shipping_method']) && !empty($SellerShippingData['shipping_charges']))
                        {
                            if($SellerShippingData['shipping_method'] == trans('users.flat_shipping_charges'))
                            {
                              $product_shipping_type = 'flat';
                              $product_shipping_amount = $SellerShippingData['shipping_charges'];
                            }
                            elseif($SellerShippingData['shipping_method'] == trans('users.prcentage_shipping_charges'))
                            {
                              $product_shipping_type = 'percentage';
                              $product_shipping_amount = ((float)$discount_price * $SellerShippingData['shipping_charges']) / 100;
                            }
                        }
                    }
                    else
                    {
                      $product_shipping_type = 'free';
                    }

                      

                    $arrOrderDetailsUpdate = [
                      'price'                => $discount_price,
                      'shipping_type'        => $product_shipping_type,
                      'shipping_amount'      => $product_shipping_amount,
                    ];
                    
                    TmpOrdersDetails::where('id',$details['id'])->update($arrOrderDetailsUpdate);

                    $subTotal += $discount_price * $details['quantity'];
                    $shippingTotal += $product_shipping_amount;
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
                ];
      
                TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
            }
          }            
        }

        //Get Orders Details to Show.
        $username = '';
        $password = '';

        $checkExisting = TmpOrders::where('id','=',$orderId)->get()->toArray();
        $param['Total'] = $checkExisting[0]['total'];
        
        $OrderId = $checkExisting[0]['id'];
        
        
        $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$OrderId)->where('user_id','=',$user_id)->get()->toArray();
        if(!empty($checkExistingOrderProduct))
        {
          foreach($checkExistingOrderProduct as $details)
          {
              //$orderDetails[] = $details;

              $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
                          ->join('categories', 'categories.id', '=', 'category_products.category_id')
                          ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
                          ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
                          ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                          ->join('users', 'users.id', '=', 'products.user_id')
                          //->join('attributes',  'attributes.id', '=', 'variant_product_attribute.attribute_value_id')
                          ->select(['products.*','categories.category_name', 'variant_product.image',
                          'variant_product.price','variant_product.id as variant_id',
                          'users.klarna_username','users.klarna_password',
                          'users.swish_api_key','users.swish_merchant_account','users.swish_client_key'
                          ,'users.strip_api_key','users.strip_secret'])
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

                          //dd(($TrendingProducts));
              //echo'<pre>';print_r($TrendingProducts);exit;
              $seller_id=0;                   
              if(count($TrendingProducts)>0) {
                foreach($TrendingProducts as $Product)
                {
                  $seller_id  = $Product->user_id;
                  $swish_client_key = $Product->swish_client_key;
                  $strip_api_key = $Product->strip_api_key;
                  $strip_secret = $Product->strip_secret;

                  
                  if(empty($username) && empty($password))
                  {
                    $username = $Product->klarna_username;
                    $password = base64_decode($Product->klarna_password);
                  }
                  $productCategories = $this->getProductCategories($Product->id);
                  //dd($productCategories);

                  $product_link = url('/').'/product';

                  $product_link .=  '/'.$productCategories[0]['category_slug'];
                  $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                  $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                  $Product->product_link  = $product_link;

                  $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                  $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                  $Product->quantity = $details['quantity'];
                  $Product->image    = explode(',',$Product->image)[0];
                  $details['product'] = $Product;
                  $orderDetails[] = $details;
                }
              }
          }
        }
        else
        {
          return redirect(route('frontShowCart'));
        }
        $param['details'] = $orderDetails;
      
        if($paymetOption=='klarna') {
          $responseFromFun=  $this->showCheckoutKlarna($user_id,$checkExisting,$orderDetails,$OrderId,$username,$password);
          if(isset($responseFromFun['error']) && $responseFromFun['error']==1) {
            return view('Front/payment_error',$responseFromFun); 
          }
          else
          return view('Front/checkout_klarna', $responseFromFun);
        }
        if($paymetOption=='swish') {
          $responseFromFun=  $this->showCheckoutSwish($seller_id,$checkExisting);         
          
          return view('Front/checkout_swish', $responseFromFun);
        }
        if($paymetOption=='strip') {
         // $responseFromFun=  $this->showCheckoutStrip($seller_id,$checkExisting);         
          $data['strip_secret'] = $strip_secret;
          $data['strip_api_key']= $strip_api_key;
          $data['order_id']= $OrderId;
          $data['Total']  = $param['Total'];
          return view('Front/checkout_strip', $data);
        }
        if($paymetOption=='swish_number') {
           $amount  = $param['Total'];
           $message = "test";
           $number = trim($request->swish_number);
           $this->createPaymentRequest($amount,$message,$number,$OrderId);
          /*$responseFromFun=  $this->showCheckoutSwish($seller_id,$checkExisting);         
          
          return view('Front/checkout_swish', $responseFromFun);*/
        }
      }
    }
    public function checkoutStripProcess(Request $request) {

      $orderRef = session('current_checkout_order_id');
      //echo $orderRef;exit;
      if($orderRef!=$request->order_id) 
      return redirect(route('frontShowCart'));
      $UserData = TmpOrders::join('temp_orders_details', 'temp_orders.id', '=', 'temp_orders_details.order_id')
                        ->join('products', 'products.id', '=', 'temp_orders_details.product_id')
                        ->join('users', 'users.id', '=', 'products.user_id')
                        ->select(['users.*'])                          
                        ->where('temp_orders.id','=',$orderRef)->first()->toArray();

      //echo'<pre>';print_r($UserData);exit;
      $checkExisting = TmpOrders::where('id','=',$orderRef)->first()->toArray();
      $orderTotal = (int)ceil($checkExisting['total']) * 100;
      Stripe\Stripe::setApiKey($UserData['strip_secret']);
      
        $response = Stripe\Charge::create ([
                "amount" => $orderTotal,
                "currency" => "INR",
                "source" => $request->stripeToken,
                "description" => "Tijara payment for order #".$orderRef ,
                
        ]);
        $arrOrderUpdate = [
          
          'klarna_order_reference'  => $response->id,
          
        ];

        TmpOrders::where('id',$orderRef)->update($arrOrderUpdate);
        //echo'<pre>';print_r($response);exit;
        $NewOrderId = $this->checkoutProcessedFunction($checkExisting,$orderRef,'checkout_complete','','') ;
        //Session::flash('success', 'Payment successful!');
        $newOrderDetails = Orders::where('id','=',$NewOrderId)->first()->toArray();
      
        $this->sendMailAboutOrder($newOrderDetails);
        return redirect(route('frontCheckoutSuccess',['id'=>base64_encode($NewOrderId)]));
    }
    function showCheckoutSwish($seller_id,$checkExisting) {

      $UserData = UserMain::select('users.*')->where('users.id','=',$seller_id)->first()->toArray();
      
        $client = new \Adyen\Client();
        $client->setXApiKey($UserData['swish_api_key']);
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);

        $orderTotal = (int)ceil($checkExisting[0]['total']) * 100;
        $data = array(
          'type' => 'swish',
          'clientKey' => $UserData['swish_client_key'],
          'orderId'=>$checkExisting[0]['id'],
          'paymentAmount'=>$orderTotal,
          'seller_id'=>$seller_id
        );
        return $data;
    }
    public function swishInitiatePayment(Request $request){
      error_log("Request for initiatePayment $request");
  
      $orderRef = session('current_checkout_order_id');
      $UserData = TmpOrders::join('temp_orders_details', 'temp_orders.id', '=', 'temp_orders_details.order_id')
                        ->join('products', 'products.id', '=', 'temp_orders_details.product_id')
                        ->join('users', 'users.id', '=', 'products.user_id')
                        ->select(['users.*'])                          
                        ->where('temp_orders.id','=',$orderRef)->first()->toArray();
      
        //echo'<pre>';print_r($UserData);exit;
        $client = new \Adyen\Client();
        $client->setXApiKey($UserData['swish_api_key']);
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);
        
      
      $checkExisting = TmpOrders::where('id','=',$orderRef)->first()->toArray();
      $orderTotal = (int)ceil($checkExisting['total']) * 100;
      $params = array(
          "merchantAccount" => $UserData['swish_merchant_account'],
          "channel" => "Web", // required
          "amount" => array(
              "currency" => 'SEK',
              "value" => $orderTotal // value is 10€ in minor units
          ),
          "reference" => $orderRef, // required
          // required for 3ds2 native flow
          "additionalData" => array(
              "allow3DS2" => "true"
          ),
          "origin" => url('/'), // required for 3ds2 native flow
          "shopperIP" => $request->ip(),// required by some issuers for 3ds2
          // we pass the orderRef in return URL to get paymentData during redirects
          // required for 3ds2 redirect flow
          "returnUrl" => url('/')."/checkouthandleShopperRedirect?orderRef=${orderRef}&seller_id=".$UserData['id'],
          "paymentMethod" => $request->paymentMethod,
          "browserInfo" => $request->browserInfo // required for 3ds2
          );
     // echo'<pre>';print_r($params);exit;
      $response = $this->service->payments($params);
  
      return $response;
  }
  public function checkouthandleShopperRedirect(Request $request){
    error_log("Request for handleShopperRedirect $request");

    $redirect = $request->all();

    $UserData = UserMain::select('users.*')->where('users.id','=',$request->seller_id)->first()->toArray();
      
        $client = new \Adyen\Client();
        $client->setXApiKey($UserData['swish_api_key']);
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);
      $details = array();
      if (isset($redirect["redirectResult"])) {
        $details["redirectResult"] = $redirect["redirectResult"];
      } else if (isset($redirect["payload"])) {
        $details["payload"] = $redirect["payload"];
      }
      $orderRef = $request->orderRef;

      $payload = array("details" => $details);

      $response = $this->service->paymentsDetails($payload);
      echo'<pre>';print_r($response);exit;
  }
    public function checkoutSubmitAdditionalDetails(Request $request){
      error_log("Request for submitAdditionalDetails $request");
  
      $orderRef = session('current_checkout_order_id');
      $UserData = TmpOrders::join('temp_orders_details', 'temp_orders.id', '=', 'temp_orders_details.order_id')
                        ->join('products', 'products.id', '=', 'temp_orders_details.product_id')
                        ->join('users', 'users.id', '=', 'products.user_id')
                        ->select(['users.*'])                          
                        ->where('temp_orders.id','=',$orderRef)->first()->toArray();
      
        $client = new \Adyen\Client();
        $client->setXApiKey($UserData['swish_api_key']);
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);
        
      
      $payload = array("details" => $request->details, "paymentData" => $request->paymentData);
  
      $response = $this->service->paymentsDetails($payload);
  
      //echo'<pre>';print_r($response);exit;
      return $response;
  }
    public function getSwishPaymentMethods(Request $request){
      
      $orderRef = session('current_checkout_order_id');
      $UserData = TmpOrders::join('temp_orders_details', 'temp_orders.id', '=', 'temp_orders_details.order_id')
                        ->join('products', 'products.id', '=', 'temp_orders_details.product_id')
                        ->join('users', 'users.id', '=', 'products.user_id')
                        ->select(['users.*'])                          
                        ->where('temp_orders.id','=',$orderRef)->first()->toArray();

      
      $client = new \Adyen\Client();
      $client->setXApiKey($UserData['swish_api_key']);
      $client->setEnvironment(\Adyen\Environment::TEST);

      $this->service = new \Adyen\Service\Checkout($client);

  
      $params = array(
          "merchantAccount" => $UserData['swish_merchant_account'],
          "channel" => "Web"
      );
  
      $response = $this->service->paymentMethods($params);
      foreach($response as $key => $r)
      {
          if($key > 0)
          {
            unset($response[$key]);
          }
      }
      return $response;
  }
    function showCheckoutKlarna($user_id,$checkExisting,$orderDetails,$OrderId,$username,$password)
    {
        $data = [];
        
        $UserData = UserMain::select('users.*')->where('users.id','=',$user_id)->first()->toArray();
        $orderAddress = json_decode($checkExisting[0]['address']);
        $orderBillingAddress  = json_decode($orderAddress->billing);
        $orderShippingAddress  = json_decode($orderAddress->shipping);
        //echo'<pre>';print_r($orderBillingAddress);exit;
        $billing_address= [];
        $billing_address['given_name'] = $orderBillingAddress->given_name;
        $billing_address['family_name'] = $orderBillingAddress->family_name;
        $billing_address['email'] = $orderBillingAddress->email;
        $billing_address['street_address'] = $orderBillingAddress->street_address;
        $billing_address['postal_code'] = $orderBillingAddress->postal_code;
        $billing_address['city'] = $orderBillingAddress->city;
        $billing_address['phone'] = $orderBillingAddress->phone;
        /*klarna api to create order*/
        $url = env('BASE_API_URL');
        
        $orderTotal = (int)ceil($checkExisting[0]['total']) * 100;
        //$url = "https://api.playground.klarna.com/checkout/v3/orders";
        $data = array("purchase_country"=> "SE",
          "purchase_currency"=> "SEK",
          "locale"=> "en-SE",
          "order_amount"=> $orderTotal,
          "order_tax_amount"=> 0,
          "billing_address" => $billing_address,
        );
        
        $arrOrderDetails = [];

        foreach($orderDetails as $orderProduct)
        {
          $productPrice = (int)ceil($orderProduct['price']) * 100;
          $arrOrderDetails[] = array(
            "type"=> "physical",
             "reference"=> $orderProduct['product_id'],
             "name"=> $orderProduct['product']->title.' '.$orderProduct['variant_attribute_id'],
             "quantity"=>$orderProduct['quantity'],
             "quantity_unit"=> "pcs",
             "unit_price"=> $productPrice,
             "tax_rate"=> 0,
             "total_amount"=> (int)ceil($productPrice * $orderProduct['quantity']),
             "total_discount_amount"=> 0,
             "total_tax_amount"=> 0,
             "product_url"=> $orderProduct['product']->product_link,
             "image_url"=> url('/').'/uploads/ProductImages/resized/'.$orderProduct['product']->image,
          );
        }
        
        if($checkExisting[0]['shipping_total'])
        {
          $shippingAmount = (int)ceil($checkExisting[0]['shipping_total']) * 100;
          $arrOrderDetails[] = array(
            "type"=> "shipping_fee",
             "name"=> 'Shipping Amount',
             "quantity"=>1,
             "quantity_unit"=> "pcs",
             "unit_price"=> $shippingAmount,
             "tax_rate"=> 0,
             "total_amount"=> $shippingAmount,
             "total_discount_amount"=> 0,
             "total_tax_amount"=> 0,
          );
        }
        
        $data['order_lines'] = $arrOrderDetails;
       
        $data['merchant_urls'] =array(
                 "terms"=>  url("/"),
                 "checkout"=> url("/")."/klarna_checkout_callback?order_id={checkout.order.id}",
                 "confirmation"=> url("/")."/klarna_checkout_callback?order_id={checkout.order.id}",
                 "push"=> url("/")."/checkout_push_notification?order_id={checkout.order.id}",
               
        );

        $data['merchant_data'] = $OrderId;
        $data['options']= ['allow_separate_shipping_address' => true,'color_button' => '#03989e','color_button_text' => '#ffffff'];

        $data = json_encode($data);
        $data =str_replace("\/\/", "//", $data);
        $data =str_replace("\/", "/", $data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        
        //dd($password);
         
        if (curl_errno($ch)) {
           $error_msg = curl_error($ch);
        }
        curl_close($ch);
        
        $response = json_decode($result);
        $responseToFun['error']  = 0;
        if(empty($response))
        { 
          $responseToFun['error']  = 1;
          $responseToFun['error_messages']= 'Något gick fel, kontakta admin.';
          return $responseToFun;
           //return view('Front/payment_error',$blade_data); 
        }
        if(!empty($response->error_messages))
        {
          $cnt_err = count($response->error_messages);
        }
        
        if (isset($error_msg) || @$cnt_err ) {
          $responseToFun['error']  = 1;
           $responseToFun['error_messages']= trans('errors.payment_failed_err');
           return $responseToFun;
          // return view('Front/payment_error',$blade_data); 
        }
        
        
        $order_id = $response->order_id;
        
        $order_status = $response->status;
        $currentDate = date('Y-m-d H:i:s');
        
        $arrOrderUpdate = [
          'klarna_order_reference'  => $order_id,
          'order_status'        => $order_status,
        ];

        TmpOrders::where('id',$OrderId)->update($arrOrderUpdate);
        
        $param["html_snippet"] = $response->html_snippet;
        //return view('Front/Packages/payment_integration',$html_data); 
        //return view('Front/checkout', $param);
        return $param;
      
    }
    
    function checkoutProcessedFunction($checkExisting,$TmpOrderId,$order_status,$address='',$orderLines='') {
      $currentDate = date('Y-m-d H:i:s');
      //START : Create Order
      $arrOrderInsert = [
                          'user_id'     => $checkExisting['user_id'],
                          'address'     => $checkExisting['address'],
                          'order_lines' => json_encode($orderLines),
                          'sub_total'   => $checkExisting['sub_total'],
                          'shipping_total' => $checkExisting['shipping_total'],
                          'total' => $checkExisting['total'],
                          'payment_details' => '',
                          'payment_status' => $order_status,
                          'order_status' => 'PENDING',
                          'order_complete_at' => '',
                          'created_at' => $currentDate,
                          'updated_at' => $currentDate,
                          'klarna_order_reference' => $checkExisting['klarna_order_reference'],
                        ];
      $NewOrderId = Orders::create($arrOrderInsert)->id;
      $temp_orders = TmpOrders::find($checkExisting['id']);
      $temp_orders->delete();
      //END : Create Order
      //START : Create Order Details.
      $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$TmpOrderId)->where('user_id','=',$checkExisting['user_id'])->get()->toArray();
      if(!empty($checkExistingOrderProduct))
      {
        foreach($checkExistingOrderProduct as $details)
        {
          $arrOrderDetailsInsert = [
            'order_id'     => $NewOrderId,
            'user_id'     => $checkExisting['user_id'],
            'product_id' => $details['product_id'],
            'variant_id'   => $details['variant_id'],
            'variant_attribute_id' => $details['variant_attribute_id'],
            'price' => $details['price'],
            'quantity' => $details['quantity'],
            'shipping_type' => $details['shipping_type'],
            'shipping_amount' => $details['shipping_amount'],
            'status' => $details['status'],
            'created_at' => $currentDate,
            'updated_at' => $currentDate

          ];

          OrdersDetails::create($arrOrderDetailsInsert)->id;

          $temp_orders_details = TmpOrdersDetails::find($details['id']);
          $temp_orders_details->delete();
        }
      }
      //END : Create Order Details.
      return $NewOrderId;
    }
    public function checkoutSwishIpn(){
      if(isset($_REQUEST['success']) && $_REQUEST['success']==true) {
          $order_id = $_REQUEST['merchantReference'];
              
          $currentDate = date('Y-m-d H:i:s');
              
              $username = '';
              $password = '';
             $checkOrderExisting = Orders::where('klarna_order_reference','=',$_REQUEST['pspReference'])->first();
              if(!empty($checkOrderExisting))
               {
                   return '[accepted]';
               }
              $checkExisting = TmpOrders::where('id','=',$order_id)->first()->toArray();
              if(!empty($checkExisting)) {
                  //$ProductData = json_decode($checkExisting['product_details'],true);
                  $NewOrderId=  $this->checkoutProcessedFunction($checkExisting,$order_id,'checkout_complete','','') ;
                  $newOrderDetails = Orders::where('id','=',$NewOrderId)->first()->toArray();
      
                  $this->sendMailAboutOrder($newOrderDetails);
                   $temp_orders = TmpOrders::find($order_id);
                    $temp_orders->delete();
              }
       }
            return '[accepted]';
      }

      public function swishIpnUrl(Request $request){
        $checkAdminOrderExisting = TmpAdminOrders::where('id','=',$_REQUEST['merchantReference'])->first();
        $checkOrderExisting = TmpOrders::where('id','=',$_REQUEST['merchantReference'])->first();
        
        if(!empty($checkAdminOrderExisting)) 
        {
          $formAction = url('/').'/swish-ipn-callback';
          app('App\Http\Controllers\Front\ProductController')->swishIpnCallback();
        }
        else if(!empty($checkOrderExisting)) { 
          $formAction = url('/').'/checkout-swish-ipn';
          $this->checkoutSwishIpn();
        }
        else
        return '[accepted]';
/*
        $parameters='1=1';
      foreach($request->all() as $key=>$value)
          $parameters .='&'.$key.'='.$value;
        header('Location: '.$formAction.'?'.$parameters);
        $html ='<form id="ipnform" action="'.$formAction.'">';
        foreach($request->all() as $key=>$value)
        $html .=  '<input type="hidden" name="'.$key.'" value="'.$value.'">';
        $html .=  '</form>';
        $html .=  '<script>document.getElementById("ipnform").submit();</script>';
        echo $html;*/
    }

    /* function for klarna payment callback*/
    public function checkoutKlarnaCallback(Request $request)
    {
      $order_id = $request->order_id;
      //$username = env('KLORNA_USERNAME');
      //$password = env('KLORNA_PASSWORD');
      $username = '';
      $password = '';
      $getTmpOrder = TmpOrders::where('klarna_order_reference','=',$order_id)->first()->toArray();
      if(!empty($getTmpOrder))
      {
        $getExistingOrderSeller = TmpOrdersDetails::join('products','products.id','=','temp_orders_details.product_id')->join('users', 'users.id', '=', 'products.user_id')->select(['users.klarna_username','users.klarna_password'])->where('order_id','=',$getTmpOrder['id'])->where('temp_orders_details.user_id','=',$getTmpOrder['user_id'])->offset(0)->limit(1)->get()->toArray();
        if(!empty($getExistingOrderSeller))
        {
          foreach($getExistingOrderSeller as $sellerDetails)
          {
            $username = $sellerDetails['klarna_username'];
            $password = base64_decode($sellerDetails['klarna_password']);
          }
        }
      }
      
      /*klarna api call to read order*/
    
      $url = env('BASE_API_URL')."/".$order_id;
      /*  $url ="https://api.playground.klarna.com/checkout/v3/orders/d1d90381-3cda-6a89-a22c-33f1ed95eb9e";*/
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
   
      $result = curl_exec($ch);

      if (curl_errno($ch)) 
      {
        $error_msg = curl_error($ch);
      }
      curl_close($ch);

      if (isset($error_msg)) 
      {
        $data['error_messages']=trans('errors.payment_failed_err');
        return view('Front/payment_error',$data); 
      }
    
      $response = json_decode($result);
      
      $order_id     =  $response->order_id;
      $order_status = $response->status;
      
      if($order_status == 'checkout_complete')
      {
        $orderLines = $response->order_lines;
        $billing_address = $response->billing_address;
        $shipping_address = $response->shipping_address;

        $address = ['billing' => json_encode($billing_address), 'shipping' => json_encode($shipping_address)];

        $order_amount = (float)($response->order_amount / 100);
        $TmpOrderId   = $response->merchant_data;
        //$orderCompleteAt = $response->complete_at;

        $checkExisting = TmpOrders::where('id','=',$TmpOrderId)->where('klarna_order_reference','=',$order_id)->first()->toArray();
        $Total = (float)ceil($checkExisting['total']);
        $NewOrderId = 0;
        if($order_amount != $Total)
        {
          $data['error_messages']=trans('errors.order_amount_mismatched');
          return view('Front/payment_error',$data);
        }
        
        else
        {
          $NewOrderId = $this->checkoutProcessedFunction($checkExisting,$TmpOrderId,$order_status,$address,$orderLines);
        }

        return redirect(route('frontCheckoutSuccess',['id' => base64_encode($NewOrderId)]));
      }
  }

  public function CheckoutswishCallback(Request $request) {
    
    $current_checkout_order_id  = session('current_checkout_order_id');
    Session::put('current_checkout_order_id', '');
    if($request->status=='success' || $request->status=='pending') {

      $arrOrderUpdate = [
        'show_in_cart'  => 0,
        
      ];
  
      TmpOrders::where('id',$current_checkout_order_id)->update($arrOrderUpdate);

      $data['swish_message'] = 'Din betalning behandlas, du kommer att få information inom en tid';
      $data['OrderId']=0;
      return view('Front/order_success', $data);
    }
    else {
      $blade_data['error_messages']= trans('lang.swish_payment_not_proceed');
          return view('Front/payment_error',$blade_data); 
    }
    
  }
  public function showCheckoutSuccess($id)
  {
    $data = [];
    Session::put('current_checkout_order_id', '');
    $user_id = Auth::guard('user')->id();
    if($user_id && Auth::guard('user')->getUser()->role_id == 1)
    {
      $OrderId = base64_decode($id);
      $checkOrder = Orders::where('id','=',$OrderId)->first()->toArray();

      if($checkOrder['user_id'] != $user_id)
      {
        Session::flash('error', trans('errors.not_authorize_order'));
        return redirect(route('frontHome'));
      }
      else
      {
          $data['OrderId'] = $OrderId;
          return view('Front/order_success', $data);
      }
    }
    else
    {
      Session::flash('error', trans('errors.login_buyer_required'));
      return redirect(route('frontLogin'));
    }
  }

  /*push notification request from Klarna*/
  public function pushNotification(Request $request)
  {
    /*get order from klarm by order id*/
    $order_id = $request->order_id;
    $currentDate = date('Y-m-d H:i:s');

    $username = '';
    $password = '';
    $checkExisting = Orders::where('klarna_order_reference','=',$order_id)->first()->toArray();
    if(!empty($checkExisting))
    {
      $getExistingOrderSeller = OrdersDetails::join('products','products.id','=','orders_details.product_id')->join('users', 'users.id', '=', 'products.user_id')->select(['users.klarna_username','users.klarna_password'])->where('order_id','=',$checkExisting['id'])->where('orders_details.user_id','=',$checkExisting['user_id'])->offset(0)->limit(1)->get()->toArray();
      if(!empty($getExistingOrderSeller))
      {
        foreach($getExistingOrderSeller as $sellerDetails)
        {
          $username = $sellerDetails['klarna_username'];
          $password = base64_decode($sellerDetails['klarna_password']);
        }
      }
    }

     //$username = env('KLORNA_USERNAME');
     //$password = env('KLORNA_PASSWORD');
     
     $Total = (float)ceil($checkExisting['total']) * 100;

     /*capture order after push request recieved from klarna*/
     $capture_url  = env('ORDER_MANAGEMENT_URL').$order_id."/captures";

     $data = <<<DATA
             {
                 "captured_amount" : $Total
             }
DATA;

     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL,$capture_url);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($curl, CURLOPT_POST, true);
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
     curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

     $response = curl_exec($curl);

     if (curl_errno($curl)) {
         $error_msg = curl_error($curl);
     }
     curl_close($curl);

     if (isset($error_msg)) {
       //echo $error_msg;
        $arrOrderUpdate = [
          'payment_details' => json_encode($response),
          'payment_status' => 'DECLINED',
          'order_status' => 'PENDING',
          'order_complete_at' => '',
          'updated_at' => $currentDate,
        ];

        Orders::where('id',$checkExisting['id'])->update($arrOrderUpdate);
        exit;
    }

     /* api call to get order details*/
     $url = env('ORDER_MANAGEMENT_URL').$order_id;        
 
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL,$url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
     curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
     
     $res = curl_exec($ch);

     if (curl_errno($ch)) {
         $error_msg = curl_error($ch);
     }
     curl_close($ch);

     if (isset($error_msg)) {
      //echo $error_msg;
       $arrOrderUpdate = [
         'payment_details' => json_encode($response),
         'payment_status' => 'FAILED',
         'order_status' => 'PENDING',
         'order_complete_at' => '',
         'updated_at' => $currentDate,
       ];

       Orders::where('id',$checkExisting['id'])->update($arrOrderUpdate);
       exit;
   }

     $response = json_decode($res);
     $order_status = $response->status;
     
     /*create file to check push request recieved or not*/
     
     if($order_status == 'CAPTURED')
     {
        $Total = (float)ceil($checkExisting['total']);
        $paymentDetails = ['captures' => $response->captures, 'klarna_reference' => $response->klarna_reference];
        
        //START : Create Order
        $arrOrderUpdate = [
                            'payment_details' => json_encode($paymentDetails),
                            'payment_status' => $order_status,
                            'order_status' => 'PENDING',
                            'order_complete_at' => '',
                            'updated_at' => $currentDate,
                          ];
        Orders::where('id',$checkExisting['id'])->update($arrOrderUpdate);

        $this->sendMailAboutOrder($checkExisting);

      }
      else
      {
        $arrOrderUpdate = [
          'payment_details' => json_encode($response),
          'payment_status' => $order_status,
          'order_status' => 'PENDING',
          'order_complete_at' => '',
          'updated_at' => $currentDate,
        ];

        Orders::where('id',$checkExisting['id'])->update($arrOrderUpdate);
      }

      exit;

 }
 function sendMailAboutOrder($checkExisting) {
      $GetOrder = Orders::join('users', 'users.id', '=', 'orders.user_id')->select('users.fname','users.lname','users.email','orders.*')->where('orders.id','=',$checkExisting['id'])->get()->toArray();

      //START : Send success email to User.
        $email = trim($GetOrder[0]['email']);
        $name  = trim($GetOrder[0]['fname']).' '.trim($GetOrder[0]['lname']);

      

        $mailOrderDetails = array(); $mail_order_details  = '<table width="800">
        <tbody>
        <tr>
                      <td style="width: 40%; text-align: left;">
                          Produkt
                      </td>
                      <td style="width: 15%; text-align: right;">
                        Kvantitet
                    </td>
                    <td style="width: 15%; text-align: right;">
                        Pris
                    </td>
                    <td style="width: 15%; text-align: right;">
                        Frakt
                    </td>
                    <td style="width: 15%; text-align: right;">
                        Total
                    </td>
                  </tr>
                  ';
        $checkExistingOrderProduct = OrdersDetails::where('order_id','=',$checkExisting['id'])->get()->toArray();
            if(!empty($checkExistingOrderProduct))
            {
                foreach($checkExistingOrderProduct as $details)
                {
                    $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                                ->get();
                      
                    if(count($TrendingProducts)>0) 
                    {
                      foreach($TrendingProducts as $Product)
                      {
                      
                        $productCategories = $this->getProductCategories($Product->id);
                        //dd($productCategories);

                        $product_link = url('/').'/product';

                        $product_link .=  '/'.$productCategories[0]['category_slug'];
                        $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                        $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                        $Product->product_link  = $product_link;

                        $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                        $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                        
                        $Product->quantity = $details['quantity'];
                        $Product->image    = explode(',',$Product->image)[0];
                        $details['product'] = $Product;
                        $mailOrderDetails[] = $details;

                        
                      }
                    }
                }
            }
            foreach($mailOrderDetails as $orderProduct) {
              $mail_order_details  .= '<tr>
                      <td style="width: 40%; text-align: left;">
                          <h4 style="margin:5px 0;">'.$orderProduct['product']->title.'</h4>
                          <br/>'.$orderProduct['variant_attribute_id'].'
                      </td>
                      <td  style="width: 15%; text-align: right;">
                        <h4 style="margin:5px 0;"> '.$orderProduct['quantity'].'</h4>
                    </td>
                    <td  style="width: 15%; text-align: right;">
                        <h4 style="margin:5px 0;"> '.number_format($orderProduct['price'],2).' kr</h4>
                    </td>
                    <td  style="width: 15%; text-align: right;">
                        <h4 style="margin:5px 0;"> '.number_format($orderProduct['shipping_amount'],2).' kr</h4>
                    </td>
                    <td  style="width: 15%; text-align: right;">
                        <h4 style="margin:5px 0;">'.number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2).' kr</h4>
                    </td>
                  </tr>';
            }

            
        $billingAddress  = json_decode($checkExisting['address'],true);
        $billingAddress           = json_decode($billingAddress['billing'],true);
        $billingAdd = '<p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$billingAddress['given_name'].' '.$billingAddress['family_name'].'</p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$billingAddress['email'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$billingAddress['street_address'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$billingAddress['city'].', '.$billingAddress['postal_code'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$billingAddress['phone'].' </p>';
        
        $shippingAddress  = json_decode($checkExisting['address'],true);
        $shippingAddress           = json_decode($shippingAddress['shipping'],true);
        $shippingAdd = '<p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$shippingAddress['given_name'].' '.$shippingAddress['family_name'].'</p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$shippingAddress['email'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$shippingAddress['street_address'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$shippingAddress['city'].', '.$shippingAddress['postal_code'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">'.$shippingAddress['phone'].' </p>';
        
        $mail_order_details .=  '<tr>                     
                <td colspan="5" style="text-align: right; padding-top: 20px;">
                    <h4 style="margin:5px 0; font-weight: 600; font-size: 20px;">TotalSumma</h4>
                    <h4 style="margin:5px 0; font-weight: 300; font-size: 18px;">'.$checkExisting['total'].' kr</h4>
                </td>
              </tr>
            </tbody>
        </table>';
        $OrderProducts = OrdersDetails::join('products','products.id', '=', 'orders_details.product_id')->select('products.user_id as product_user','orders_details.*')->where('order_id','=',$GetOrder[0]['id'])->offset(0)->limit(1)->get()->toArray();

              $GetSeller = UserMain::select('users.fname','users.lname','users.email','users.store_name')->where('id','=',$OrderProducts[0]['product_user'])->first()->toArray();

        $overview  = '<p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; 
        ">Butik: '.$GetSeller['store_name'].'</p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">Ordernummer: #'.$checkExisting['id'].' </p>
        <p style="font-size: 20px; font-weight: 400; text-align: left;margin:10px 0; ">Beställningsdatunm: '.$checkExisting['created_at'].' </p>';

        $GetEmailContents = getEmailContents('Order Success');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];
        $url = url('/').'/order-details/'.base64_encode($GetOrder[0]['id']);
        $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##','##ORDER_DETAILS##',
        '##ORDER_TOTAL##','##OVERVIEW##','##SHIPPING_ADDRESS##'],
        [$name,$email,url('/'),$url,$mail_order_details,$checkExisting['total'],$overview,$shippingAdd
      ],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$name,$subject) {
            $message->to($email, $name)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });

      //END : Send success email to User.

      
      //START : Send success email to Seller.
        $emailSeller = trim($GetSeller['email']);
        $nameSeller  = trim($GetSeller['fname']).' '.trim($GetSeller['lname']);

        $admin_email = env('ADMIN_EMAIL');
        $admin_name  = 'Tijara Admin';
        
        
        $GetEmailContents = getEmailContents('Seller Order Success');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];
        $url = url('/').'/order-details/'.base64_encode($GetOrder[0]['id']);
        $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##','##ORDER_DETAILS##',
        '##TOTAL##','##OVERVIEW##','##SHIPPING_ADDRESS##'],
        [$nameSeller,$emailSeller,url('/'),$url,$mail_order_details,$checkExisting['total'],$overview,$shippingAdd],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($emailSeller,$nameSeller,$admin_email,$admin_name,$subject) {
            $message->to($emailSeller, $nameSeller)->cc($admin_email,$admin_name)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });
      //END : Send success email to Seller.

      $OrderProducts = OrdersDetails::join('products','products.id', '=', 'orders_details.product_id')->select('products.user_id as product_user','orders_details.*')->where('order_id','=',$GetOrder[0]['id'])->offset(0)->limit(1)->get()->toArray();
      if(!empty($OrderProducts))
      {
        foreach($OrderProducts as $orderDetails)
        {
            $getVariant = VariantProduct::where([['id','=',$orderDetails['variant_id']],['product_id','=',$orderDetails['product_id']]])->first();
            if(!empty($getVariant))
            {
              $getVariant = $getVariant->toArray();
              $remainingQty = $getVariant['quantity'] - $orderDetails['quantity'];
              if($remainingQty < 0)
              {
                $remainingQty = 0;
              }

              $arrUpdate = ['quantity' => $remainingQty];
              VariantProduct::where([['id','=',$getVariant['id']],['product_id','=',$orderDetails['product_id']]])->update($arrUpdate);
            }
        }
      }

 }
 /* function for klarna payment callback*/
 public function showBuyerCheckout($orderId)
 {
    $TmpOrderId = '';
    $user_id = Auth::guard('user')->id();
    if($user_id && Auth::guard('user')->getUser()->role_id == 1)
    {
      $orderId = base64_decode($orderId);

      $checkOrder = TmpOrders::where('id','=',$orderId)->first();
      if(empty($checkOrder))
      {
        return redirect(route('frontShowCart'));
      }
      else
      {
        $checkOrder = $checkOrder->toArray();
        $TmpOrderId = $checkOrder['id'];
      }
    }
    else
    {
      Session::flash('error', trans('errors.login_buyer_required'));
      return redirect(route('frontLogin'));
    }

        $checkExisting = TmpOrders::where('id','=',$TmpOrderId)->first()->toArray();
        $UserData = UserMain::select('users.*')->where('users.id','=',$user_id)->first()->toArray();
        $billing_address= [];
        $billing_address['given_name'] = $UserData['fname'];
        $billing_address['family_name'] = $UserData['lname'];
        $billing_address['email'] = $UserData['email'];
        $billing_address['street_address'] = $UserData['address'];
        $billing_address['postal_code'] = $UserData['postcode'];
        $billing_address['city'] = $UserData['city'];
        $billing_address['phone'] = $UserData['phone_number'];
        
        
        $arrOrderDetails = [];
        $checkExistingOrderProduct = TmpOrdersDetails::join('products','products.id', '=', 'temp_orders_details.product_id')->select('products.title','temp_orders_details.*')->where('order_id','=',$TmpOrderId)->where('temp_orders_details.user_id','=',$checkExisting['user_id'])->get()->toArray();
        if(!empty($checkExistingOrderProduct))
        {
          
            foreach($checkExistingOrderProduct as $orderProduct)
            {
              $arrOrderDetails[] = array(
                "type"=> "physical",
                "reference"=> $orderProduct['product_id'],
                "name"=> $orderProduct['title'].' '.$orderProduct['variant_attribute_id'],
                "quantity"=>$orderProduct['quantity'],
                "quantity_unit"=> "pcs",
                "unit_price"=> (int)ceil($orderProduct['price']),
                "tax_rate"=> 0,
                "total_amount"=> (int)ceil($orderProduct['price'] * $orderProduct['quantity']),
                "total_discount_amount"=> 0,
                "total_tax_amount"=> 0,
              );
            }
          }
    
     $orderLines = $arrOrderDetails;
     $shipping_address = $billing_address;

     $address = ['billing' => json_encode($billing_address), 'shipping' => json_encode($shipping_address)];

       $currentDate = date('Y-m-d H:i:s');
       //START : Create Order
       $arrOrderInsert = [
                           'user_id'     => $checkExisting['user_id'],
                           'address'     => json_encode($address),
                           'order_lines' => json_encode($orderLines),
                           'sub_total'   => $checkExisting['sub_total'],
                           'shipping_total' => $checkExisting['shipping_total'],
                           'total' => $checkExisting['total'],
                           'payment_details' => '',
                           'payment_status' => 'MANUAL',
                           'order_status' => 'PENDING',
                           'order_complete_at' => '',
                           'created_at' => $currentDate,
                           'updated_at' => $currentDate,
                           'klarna_order_reference' => $checkExisting['klarna_order_reference'],
                         ];
     $NewOrderId = Orders::create($arrOrderInsert)->id;
     $temp_orders = TmpOrders::find($checkExisting['id']);
     $temp_orders->delete();
     //END : Create Order
     //START : Create Order Details.
     $checkExistingOrderProduct = TmpOrdersDetails::where('order_id','=',$TmpOrderId)->where('user_id','=',$checkExisting['user_id'])->get()->toArray();
     if(!empty($checkExistingOrderProduct))
     {
       foreach($checkExistingOrderProduct as $details)
       {
         $arrOrderDetailsInsert = [
           'order_id'     => $NewOrderId,
           'user_id'     => $checkExisting['user_id'],
           'product_id' => $details['product_id'],
           'variant_id'   => $details['variant_id'],
           'variant_attribute_id' => $details['variant_attribute_id'],
           'price' => $details['price'],
           'quantity' => $details['quantity'],
           'shipping_type' => $details['shipping_type'],
           'shipping_amount' => $details['shipping_amount'],
           'status' => $details['status'],
           'created_at' => $currentDate,
           'updated_at' => $currentDate

         ];

         OrdersDetails::create($arrOrderDetailsInsert)->id;

         $temp_orders_details = TmpOrdersDetails::find($details['id']);
         $temp_orders_details->delete();
       }
     }
     //END : Create Order Details.

     
     $OrderProducts = OrdersDetails::join('products','products.id', '=', 'orders_details.product_id')->select('products.user_id as product_user','orders_details.*')->where('order_id','=',$NewOrderId)->offset(0)->limit(1)->get()->toArray();

     $GetSeller = UserMain::select('users.fname','users.lname','users.email')->where('id','=',$OrderProducts[0]['product_user'])->first()->toArray();

     //START : Send success email to Seller.
       $emailSeller = trim($GetSeller['email']);
       $nameSeller  = trim($GetSeller['fname']).' '.trim($GetSeller['lname']);

       $admin_email = 'shrik.techbee@gmail.com';
       $admin_name  = 'Tijara Admin';
       
      //  $arrMailData = ['name' => $nameSeller, 'email' => $emailSeller, 'order_details_link' => url('/').'/order-details/'.base64_encode($NewOrderId)];

      //  Mail::send('emails/order_buyer_success', $arrMailData, function($message) use ($emailSeller,$nameSeller) {
      //      $message->to($emailSeller, $nameSeller)->subject
      //          ('Tijara - Order placed for your Product.');
      //      $message->from('developer@techbeeconsulting.com','Tijara');
      //  });
      $GetEmailContents = getEmailContents('Seller Order Success');
      $subject = $GetEmailContents['subject'];
      $contents = $GetEmailContents['contents'];
      $url = url('/').'/order-details/'.base64_encode($NewOrderId);
      $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##'],[$nameSeller,$emailSeller,url('/'),$url],$contents);

      $arrMailData = ['email_body' => $contents];

      Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($emailSeller,$nameSeller,$admin_email,$admin_name,$subject) {
          $message->to($emailSeller, $nameSeller)->cc($admin_email,$admin_name)->subject
              ($subject);
          $message->from( env('FROM_MAIL'),'Tijara');
      });
     //END : Send success email to Seller.
   

     return redirect(route('frontCheckoutSuccess',['id' => base64_encode($NewOrderId)]));
   
}

 public function showWishlist()
    {
      $data = [];
      $wishlistDetails =   $wishlistServiceDetails = [];
      $user_id = Auth::guard('user')->id();
      if($user_id)
      {
        $getWishlist = Wishlist::where('user_id','=',$user_id)->get()->toArray();
        
        if(!empty($getWishlist))
        {
          foreach($getWishlist as $details)
          {
              $wishlistProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                          ->get();
                          //dd(DB::getQueryLog());

                          //dd(count($TrendingProducts));
              if(count($wishlistProducts)>0) 
              {
                foreach($wishlistProducts as $Product)
                {
                  $productCategories = $this->getProductCategories($Product->id);
                  //dd($productCategories);

                  $product_link = url('/').'/product';

                  $product_link .=  '/'.$productCategories[0]['category_slug'];
                  $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                  $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                  $Product->product_link  = $product_link;

                  $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                  $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                  $Product->quantity = 1;
                  $Product->image    = explode(',',$Product->image)[0];
                  $details['product'] = $Product;
                  $wishlistDetails[] = $details;
                }
              }
          }
        }
          
        /*service wishlist table*/ 
        $getServiceWishlist = Wishlist::where('user_id','=',$user_id)->where('service_id','!=','')->get()->toArray();
        if(!empty($getServiceWishlist))
        {
          foreach($getServiceWishlist as $serviceDetails)
          {

               $wishlistServices  = Services::join('category_services', 'services.id', '=', 'category_services.service_id')
                          ->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
                          ->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
                          ->select(['services.*','servicecategories.category_name'])
                          ->where('services.status','=','active')
                          ->where('servicecategories.status','=','active')
                          ->where('serviceSubcategories.status','=','active')
                          ->where('services.id','=',$serviceDetails['service_id'])
                          ->orderBy('services.id', 'DESC')
                          ->groupBy('services.id')
                          ->get();

                           if(count($wishlistServices)>0) 
                          {
                            foreach($wishlistServices as $Service)
                            {
                              $serviceCategories = $this->getServiceCategories($Service->id);

                              $service_link = url('/').'/service';

                              $service_link .=  '/'.$serviceCategories[0]['category_slug'];
                              $service_link .=  '/'.$serviceCategories[0]['subcategory_slug'];

                              $service_link .=  '/'.$Service->service_slug.'-S-'.$Service->service_code;
                              
                              $Service->service_link  = $service_link;

                              $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Service->user_id)->first();
                              $Service->seller  = isset($SellerData['fname']) && isset($SellerData['lname'])? $SellerData['fname'].' '.$SellerData['lname']: '';
                              $Service->quantity = 1;
                              $Service->images    = explode(',',$Service->images)[0];
                              $serviceDetails['service'] = $Service;
                              $wishlistServiceDetails[] = $serviceDetails;
                            }
                          }
          }
        }

        $data['details'] = $wishlistDetails;
        $data['detailsService'] = $wishlistServiceDetails;
        
        return view('Front/wishlist', $data);
      }
      else {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }


    public function addToWishlist(Request $request)
    { 
        $user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $txt_msg = '';
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {

          if(!empty($request->product_variant))
          {
            $productVariant = $request->product_variant;
            $productQuntity = $request->product_quantity;
            if(!empty($productVariant))
            {
                $Product = VariantProduct::join('products', 'variant_product.product_id', '=', 'products.id')
                          ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                          ->select(['products.*','variant_product.price','variant_product.id as variant_id','variant_product_attribute.id as variant_attribute_id'])
                          ->where('variant_product.id','=', $productVariant)
                          ->where('products.status','=','active')
                          ->get()->toArray();
                //dd($Product);
                //Create Temp order
                $checkExisting = Wishlist::where([['user_id','=',$user_id],['product_id','=',$Product[0]['id']],['variant_id','=',$productVariant]])->first();
                if(!empty($checkExisting))
                {
                  $is_added = 0;
                  $is_login_err = 0;
                  $txt_msg = trans('messages.wishlist_product_exists');
                }
                else
                {
                  $variantAttrs = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
                         ->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
                         ->where([['variant_id','=',$productVariant],['product_id','=',$Product[0]['id']]])->get();
                  
                  $attrIds = '';  
                  foreach($variantAttrs as $variantAttr)
                  {
                    if($attrIds == '')
                    {
                      $attrIds = $variantAttr->name.' : '.$variantAttr->attribute_values;
                    }
                    else
                    {
                      $attrIds.= ', '.$variantAttr->name.' : '.$variantAttr->attribute_values;
                    }
                  }   

                  $created_at = date('Y-m-d H:i:s');
                  $arrOrderInsert = [
                      'user_id'             => $user_id,
                      'product_id'          => $Product[0]['id'],
                      'variant_id'          => $productVariant,
                      'variant_attribute_id'=> '['.$attrIds.']',
                      'created_at'          => $created_at,
                      'updated_at'          => $created_at,
                  ];

                  $Id = Wishlist::create($arrOrderInsert)->id;
                }
              }
          }else{
            if(!empty($request->service_id))
            {
              /*code for service wishlist*/
              $service_id = $request->service_id;
              $service_exist = Wishlist::where([['user_id','=',$user_id],['service_id','=',$service_id]])->first();
                if(!empty($service_exist))
                {
                  $is_added = 0;
                  $is_login_err = 0;
                  $txt_msg = trans('messages.wishlist_service_exists');
                }
                else
                {         DB::enableQueryLog();
                  //add service to wishlist
                  $curr_date = date('Y-m-d H:i:s');
                  $arrServiceInsert = [
                      'user_id'             => trim($user_id),
                      'service_id'          => trim($service_id),
                      'created_at'          => trim($curr_date),
                      'updated_at'          => trim($curr_date),
                  ];

                  DB::table('wishlist')->insert($arrServiceInsert);
                }
            }
          }
        }
        else
        {
          $is_added = 0;
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
    }


    function removeWishlistProduct(Request $request)
    {
      $user_id = Auth::guard('user')->id();
        $is_removed = 1;
        $txt_msg =  '';
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
            $OrderDetailsId = $request->OrderDetailsId;
            $ExistingOrder = Wishlist::where('id',$OrderDetailsId)->get()->toArray();
          
            if(!empty($ExistingOrder[0]['service_id'])){
              $wishlist_remove_success=trans('messages.wishlist_service_remove_success');
            }else{
              $wishlist_remove_success=trans('messages.wishlist_product_remove_success');
            }
            foreach($ExistingOrder as $orderDetails)
            {
              Wishlist::where('id',$OrderDetailsId)->delete();
            }
        }
        else
        {
          $is_removed = 0;
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_removed,'msg'=>$txt_msg,'wishlist_remove'=>$wishlist_remove_success));
        exit;
    }


    function showOrderDetails($id,$is_print = false)
    {
		$data['is_print'] = $is_print;
      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $is_buyer_order = 0;
      $orderDetails = [];
      if($user_id)
      {
        $userRole = Auth::guard('user')->getUser()->role_id;
        if($userRole == 2)
        {
          $is_seller = 1;
        }
        
        $OrderId = base64_decode($id);
        $checkOrder = Orders::where('id','=',$OrderId)->first()->toArray();
        $checkExistingOrderProduct = OrdersDetails::join('products','products.id','=','orders_details.product_id')->select('products.user_id as product_user')->where('order_id','=',$OrderId)->limit(1)->get()->toArray();
        if(!empty($checkExistingOrderProduct))
        {
            foreach($checkExistingOrderProduct as $details)
            {
              if($details['product_user'] == $user_id)
              {
                $is_buyer_order = 1;
              }
            }
        }

        if(!empty($checkOrder))
        {
          $data['subTotal'] = $checkOrder['sub_total'];
          $data['Total'] = $checkOrder['total'];
          $data['shippingTotal'] = $checkOrder['shipping_total'];

          if($is_seller == 0 && $checkOrder['user_id'] != $user_id && $is_buyer_order == 0) 
          {
            Session::flash('error', trans('errors.not_authorize_order'));
            return redirect(route('frontHome'));
          }
          else
          {
              $checkExistingOrderProduct = OrdersDetails::where('order_id','=',$OrderId)->get()->toArray();
              if(!empty($checkExistingOrderProduct))
              {
                  foreach($checkExistingOrderProduct as $details)
                  {
                      $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                                  ->get();
                        
                      if(count($TrendingProducts)>0) 
                      {
                        foreach($TrendingProducts as $Product)
                        {
                          if($is_seller == 1 && $Product->user_id != $user_id) 
                          {
                            Session::flash('error', trans('errors.not_authorize_order'));
                            return redirect(route('frontHome'));
                            exit;
                          }

                          $productCategories = $this->getProductCategories($Product->id);
                          //dd($productCategories);

                          $product_link = url('/').'/product';

                          $product_link .=  '/'.$productCategories[0]['category_slug'];
                          $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                          $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                          $Product->product_link  = $product_link;

                          $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                          $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                          
                          $data['seller_name'] = $Product->seller;
                          $sellerLink = route('sellerProductListingByCategory',['seller_name' => $Product->seller, 'seller_id' => base64_encode($Product->user_id)]);
                          $data['seller_link'] = $sellerLink;
                          
                          $Product->quantity = $details['quantity'];
                          $Product->image    = explode(',',$Product->image)[0];
                          $details['product'] = $Product;
                          $orderDetails[] = $details;

                          if($is_buyer_order == 1)
                          {
                            $CheckIfAlreadyMarked = Products::where('id',$Product->id)->first();
                            if(!empty($CheckIfAlreadyMarked))
                            {
                              $CheckIfAlreadyMarked = $CheckIfAlreadyMarked->toArray();
                              if($CheckIfAlreadyMarked['is_sold'] == '0' && $is_seller == 0)
                              { 
                                $arrUpdateProduct = ['is_sold' => '1', 'sold_date' => date('Y-m-d H:i:s')];
                                Products::where('id',$Product->id)->update($arrUpdateProduct);
                              }
                              
                            }
                            
                          }
                        }
                      }
                  }
              }
              
          }

          $data['details'] = $orderDetails;
          $data['order'] = $checkOrder;
          $data['is_seller'] = $is_seller;
          $data['is_buyer_order'] = $is_buyer_order;
          $address = json_decode($checkOrder['address'],true);
          
          $data['billingAddress'] = json_decode($address['billing'],true);
          $data['shippingAddress'] = json_decode($address['shipping'],true);
          return view('Front/order_details', $data);
        }
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

    function downloadOrderDetails($id)
    {
      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $is_buyer_order = 0;
      $orderDetails = [];
      if($user_id)
      {
        $userRole = Auth::guard('user')->getUser()->role_id;
        if($userRole == 2)
        {
          $is_seller = 1;
        }
        
        $OrderId = base64_decode($id);
        $checkOrder = Orders::where('id','=',$OrderId)->first()->toArray();
        $checkExistingOrderProduct = OrdersDetails::join('products','products.id','=','orders_details.product_id')->select('products.user_id as product_user')->where('order_id','=',$OrderId)->limit(1)->get()->toArray();
        if(!empty($checkExistingOrderProduct))
        {
            foreach($checkExistingOrderProduct as $details)
            {
              if($details['product_user'] == $user_id)
              {
                $is_buyer_order = 1;
              }
            }
        }

        if(!empty($checkOrder))
        {
          $data['subTotal'] = $checkOrder['sub_total'];
          $data['Total'] = $checkOrder['total'];
          $data['shippingTotal'] = $checkOrder['shipping_total'];

          if($is_seller == 0 && $checkOrder['user_id'] != $user_id && $is_buyer_order == 0) 
          {
            Session::flash('error', trans('errors.not_authorize_order'));
            return redirect(route('frontHome'));
          }
          else
          {
              $checkExistingOrderProduct = OrdersDetails::where('order_id','=',$OrderId)->get()->toArray();
              if(!empty($checkExistingOrderProduct))
              {
                  foreach($checkExistingOrderProduct as $details)
                  {
                      $TrendingProducts   = Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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
                                  ->get();
                        
                      if(count($TrendingProducts)>0) 
                      {
                        foreach($TrendingProducts as $Product)
                        {
                          if($is_seller == 1 && $Product->user_id != $user_id) 
                          {
                            Session::flash('error', trans('errors.not_authorize_order'));
                            return redirect(route('frontHome'));
                            exit;
                          }

                          $productCategories = $this->getProductCategories($Product->id);
                          //dd($productCategories);

                          $product_link = url('/').'/product';

                          $product_link .=  '/'.$productCategories[0]['category_slug'];
                          $product_link .=  '/'.$productCategories[0]['subcategory_slug'];

                          $product_link .=  '/'.$Product->product_slug.'-P-'.$Product->product_code;

                          $Product->product_link  = $product_link;

                          $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                          $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                          
                          $data['seller_name'] = $Product->seller;
                          $sellerLink = route('sellerProductListingByCategory',['seller_name' => $Product->seller, 'seller_id' => base64_encode($Product->user_id)]);
                          $data['seller_link'] = $sellerLink;
                          
                          $Product->quantity = $details['quantity'];
                          $Product->image    = explode(',',$Product->image)[0];
                          $details['product'] = $Product;
                          $orderDetails[] = $details;

                          if($is_buyer_order == 1)
                          {
                            $CheckIfAlreadyMarked = Products::where('id',$Product->id)->first();
                            if(!empty($CheckIfAlreadyMarked))
                            {
                              $CheckIfAlreadyMarked = $CheckIfAlreadyMarked->toArray();
                              if($CheckIfAlreadyMarked['is_sold'] == '0' && $is_seller == 0)
                              { 
                                $arrUpdateProduct = ['is_sold' => '1', 'sold_date' => date('Y-m-d H:i:s')];
                                Products::where('id',$Product->id)->update($arrUpdateProduct);
                              }
                              
                            }
                            
                          }
                        }
                      }
                  }
              }
              
          }

          $data['details'] = $orderDetails;
          $data['order'] = $checkOrder;
          $data['is_seller'] = $is_seller;
          $data['is_buyer_order'] = $is_buyer_order;
          $address = json_decode($checkOrder['address'],true);
          
          $data['billingAddress'] = json_decode($address['billing'],true);
          $data['shippingAddress'] = json_decode($address['shipping'],true);
          //return view('Front/download_order_details', $data);
          $pdf = PDF::loadView('Front/download_order_details',$data);
          return $pdf->download('order-#'.$OrderId.'.pdf');
        }
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

    public function showBuyerOrders(Request $request){
       $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $orderDetails = [];
      if($user_id)
      {
           
        $monthYearDropdown    = "<select name='monthYear' id='monthYear' class='form-control debg_color' style='color:#fff;margin-top: -2px;'><option value=''>".trans('lang.select_label')."</option>";
        
          
        $monthYearSql = Orders::select(DB::raw('count(id) as `orders`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->where('user_id','=',$user_id)->groupby('year','month')
              ->get();
           

        if(!empty($monthYearSql) && count($monthYearSql)>0){
            foreach ($monthYearSql as $key => $value) {
              $i=$value['month'];
              $year =$value['year'];
              $month =  date("M", strtotime("$i/12/10"));
              $new_date = $value['new_date'];
             
              if($new_date==$request['monthYear']){
                $selected = "selected";
              }else{
                $selected = "";
              }
         
              $monthYearDropdown    .=  "<option value='".$new_date."' ".$selected.">$month $year</option>";
            }
         }
          
        $monthYearDropdown    .= "</select>";
 
        $orders  = Orders::join('orders_details', 'orders.id', '=', 'orders_details.order_id')
                  ->join('products', 'products.id', '=', 'orders_details.product_id')
                ->join('variant_product as v1', 'products.id', '=', 'v1.product_id')
                ->join('variant_product as v2', 'orders_details.variant_id', '=', 'v2.id')
               ->join('users','users.id','=','products.user_id')
               ->select('orders.created_at','orders.id as order_id','products.title','products.product_code','products.product_slug','orders_details.quantity','users.store_name','v2.image','orders_details.price','products.id as product_id','users.fname','users.lname','users.id as seller_id')->where('orders.user_id','=',$user_id);




                  /*  $checkExistingOrderProduct = TmpOrdersDetails::
            join('products', 'temp_orders_details.product_id', '=', 'products.id')->join('variant_product as v1', 'v1.product_id', '=', 'products.id')->join('variant_product as v2', 'v2.price', '=', 'temp_orders_details.price')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','v2.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->where('v1.quantity','>',0)->groupBy('temp_orders_details.variant_id')->get()->toArray();
*/



        if(!empty($request->monthYear)) {
          $month_year_explod =explode("-",$request->monthYear);
          $orders = $orders->whereMonth('orders.created_at', '=', $month_year_explod[0])
          ->whereYear('orders.created_at',$month_year_explod[1]);

        }

        $orders       = $orders->groupBy('orders_details.id');
        $orders       = $orders->orderby('orders.id', 'DESC');
        //$orders       = $orders->groupBy('orders_details.id')->orderby('orders.id', 'DESC');
        $orders       = $orders->paginate(config('constants.buyer_product'));

        $data['ordersDetails']  = $orders;
        $data['monthYearHtml']     = $monthYearDropdown;
        $data['is_seller']         = $is_seller;
        $data['user_id']           = $user_id;

        return view('Front/all_buyer_orders', $data);
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

    public function showAllOrders(Request $request)
    {
      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $orderDetails = [];
      if($user_id)
      {
        $userRole = Auth::guard('user')->getUser()->role_id;
        if($userRole == 2)
        {
          $is_seller = 1;
        }


        /*month year filter*/
        $month = !empty( $_GET['month'] ) ? $_GET['month'] : 0;
        $year = !empty( $_GET['year'] ) ? $_GET['year'] : 0;
        $monthYearDropdown    = "<select name='monthYear' id='monthYear' class='form-control debg_color' style='color:#fff;margin-top: -2px;'>";
        //$monthYearDropdown    .= "<option value=''>".trans('lang.select_label')."</option>";

      
          $monthYearSql = Orders::select(DB::raw('count(id) as `orders`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'));
            if($userRole == 1){
                $monthYearSql = $monthYearSql->where('user_id','=',$user_id);
            }
             
            $monthYearSql = $monthYearSql->groupby('year','month')
              ->get();

            if(!empty($monthYearSql) && count($monthYearSql)>0){
                foreach ($monthYearSql as $key => $value) {
                  $i=$value['month'];
                  $year =$value['year'];
                  $month =  date("M", strtotime("$i/12/10"));
                  $new_date = $value['new_date'];
              
                  $monthYearDropdown    .=  "<option  value='".$new_date."'>$month $year</option>";
                }
             }else{
                 $monthYearDropdown    .= "<option value=''>".trans('lang.select_label')."</option>";  
             }
            

            /*$curr_yr = date("Y");

            for ($j = 2000; $j <= $curr_yr; $j++) {
            for ($i = 1; $i <= 12; $i++) {

            echo "<br>".date("M", strtotime("$i/12/10"));

            $time = strtotime(sprintf('+%d months', $i));

            $label = date('F ', $i); 
            //echo 
            //echo $i."<br>";
            $value = date('m', $time);       
            $time_year = strtotime(sprintf('-%d years', $curr_yr));
            $label_year = date('Y ', $time);
            $value_year = date('Y', $time);


            $selected = ( $value==$month &&  $value_year== $year ) ? ' selected=true' : '';
            $month_year = $value."-".$j;

            $get_curr_month_yr = date('m Y');
            $explod_mY = explode(' ', $get_curr_month_yr);

            $curr_month_yr = $explod_mY[0]."-".$explod_mY[1];

            if($curr_month_yr==$month_year){
            $selected= "selected";
            }else{
            $selected='';
            }

            $monthYearDropdown    .=  "<option  value='".$month_year."' ".$selected.">$label $j</option>";

            }

            }*/
        $monthYearDropdown    .= "</select>";

        $data['monthYearHtml']     = $monthYearDropdown;
        $data['is_seller'] = $is_seller;
        $data['user_id'] = $user_id;

        return view('Front/all_orders', $data);
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

    /**

     * [getRecords for product list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) 
    {
      
      if(!empty($request['is_seller']) && $request['is_seller'] == '1') 
      {
          $orders = Orders::join('users','users.id','=','orders.user_id')->join('orders_details','orders_details.order_id','=','orders.id')->join('products','products.id','=','orders_details.product_id')->select('orders.*','users.fname','users.lname','users.email')->where('products.user_id','=',$request['user_id']);
      }
      else 
      {
          $orders = Orders::join('users','users.id','=','orders.user_id')->select('orders.*','users.fname','users.lname','users.email')->where('user_id','=',$request['user_id']);
      }
      if(!empty($request['search']['value'])) 
      {
            $field = ['users.fname','users.lname','users.email','orders.order_status'];
            $namefield = ['users.fname','users.lname','users.email','orders.order_status'];
            $search=($request['search']['value']);
            
            $orders = $orders->Where(function ($query) use($search, $field,$namefield) 
            { 
                if(strpos($search, ' ') !== false)
                {
                      $s=explode(' ',$search);
                      foreach($s as $val) {
                          for ($i = 0; $i < count($namefield); $i++){
                              $query->orwhere($namefield[$i], 'like',  '%' . $val .'%');
                          }  
                      }
                  }
                  else 
                  {
                    for ($i = 0; $i < count($field); $i++){
                        $query->orwhere($field[$i], 'like',  '%' . $search .'%');
                  }  

                }        
              }); 
      }


        if(!empty($request['status'])) 
        {

            $orders = $orders->Where('orders.order_status', '=', $request['status']);

        }

        if(!empty($request->monthYear)) {
          $month_year_explod =explode("-",$request->monthYear);
          $orders = $orders->whereMonth('orders.created_at', '=', $month_year_explod[0])
          ->whereYear('orders.created_at',$month_year_explod[1]);

        }
       
        
          $recordsTotal = $orders->groupBy('orders.id')->get()->count();
          // if(isset($request['order'][0])){

          //     $postedorder=$request['order'][0];
          //     if($postedorder['column']<=1) $orderby='products.title';
          //     if($postedorder['column']==2) $orderby='variant_product.sku';
          //     if($postedorder['column']==3) $orderby='variant_product.price';
          //     if($postedorder['column']==5) $orderby='products.sort_order';
          //     if($postedorder['column']==6) $orderby='products.created_at';
          //     $orderorder=$postedorder['dir'];
              

          // }
          $orders = $orders->groupBy('orders.id')->orderby('orders.id', 'DESC');

      
        
     
          $recordDetails = $orders->offset($request->input('start'))->limit($request->input('length'))->get();
    
          $arr = [];

          if (count($recordDetails) > 0) {

              $recordDetails = $recordDetails->toArray();
              foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
              {
                  $action = $status = $image = '-';
                  $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                  $user = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';
                  $subtotal = (!empty($recordDetailsVal['sub_total'])) ? number_format($recordDetailsVal['sub_total'],2) : '-';
                  $shipping_total = (!empty($recordDetailsVal['shipping_total'])) ? number_format($recordDetailsVal['shipping_total'],2) : '00.00';
                  $total = (!empty($recordDetailsVal['total'])) ? number_format($recordDetailsVal['total'],2) : '-';
                  $payment_status = (!empty($recordDetailsVal['payment_status'])) ? $recordDetailsVal['payment_status'] : '-';
                  $order_status = (!empty($recordDetailsVal['order_status'])) ? $recordDetailsVal['order_status'] : '-';
                  $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['created_at']));
                  /* $action = '<a href="'.route('frontShowOrderDetails', base64_encode($id)).'" title="'. trans('lang.txt_view').'"><i style="color:#2EA8AB;" class="fas fa-eye open_order_details"></i> </a>&nbsp;&nbsp;
                  <a href="'.route('frontDownloadOrderDetails', base64_encode($id)).'" title="Download"><i style="color:gray;" class="fas fa-file-download"></i> </a>';*/
                  $action = '<a href="javascript:void(0)" class="seller_odr_dtls" title="'. trans('lang.txt_view').'" onclick="print_window('.$id.')" product_link="'.route('frontShowOrderDetails', base64_encode($id)).'"><i style="color:#2EA8AB;" class="fas fa-eye"></i> </a>&nbsp;&nbsp;
                  <a href="'.route('frontDownloadOrderDetails', base64_encode($id)).'" title="Download"><i style="color:gray;" class="fas fa-file-download"></i> </a>';

                  if(!empty($request['is_seller']) && $request['is_seller'] == '1') 
                  {
                    $arr[] = [ '#'.$id, $user, $subtotal.' kr', $shipping_total.' kr',$total.' kr',  $payment_status, $order_status, $dated, $action];
                  }
                  else
                  {
                    $arr[] = [ '#'.$id, $subtotal.' kr', $shipping_total.' kr',$total.' kr',  $payment_status, $order_status, $dated, $action];
                  }
                    

              }

          } 

          else {

            if(!empty($request['is_seller']) && $request['is_seller'] == '1') 
            {
              $arr[] = [ '', '', '', '', trans('lang.datatables.sEmptyTable'), '', '', '', ''];
            }
            else
            {
              $arr[] = [ '', '', '', '', trans('lang.datatables.sEmptyTable'), '', '', ''];
            }

          }



          $json_arr = [

              'draw'            => $request->input('draw'),

              'recordsTotal'    => $recordsTotal,

              'recordsFiltered' => $recordsTotal,

              'data'            => ($arr),

          ];

      

      return json_encode($json_arr);

  }

  function changeOrderStatus(Request $request)
  {
    $user_id = Auth::guard('user')->id();
    $is_updated = 1;
    $is_login_err = 0;
    $txt_msg =  trans('lang.order_status_success');
    $is_buyer_order = 0;

    $OrderId = $request->order_id;
    $checkOrder = Orders::where('id','=',$OrderId)->first()->toArray();
    $checkExistingOrderProduct = OrdersDetails::join('products','products.id','=','orders_details.product_id')->select('products.user_id as product_user')->where('order_id','=',$OrderId)->limit(1)->get()->toArray();
    if(!empty($checkExistingOrderProduct))
    {
        foreach($checkExistingOrderProduct as $details)
        {
          if($details['product_user'] == $user_id)
          {
            $is_buyer_order = 1;
          }
        }
    }

    if($user_id && (Auth::guard('user')->getUser()->role_id == 2 || $is_buyer_order == 1))
    {        
        $OrderStatus = $request->order_status;
        $arrOrderUpdate = [
          'order_status'    => $OrderStatus,
          'updated_at'      => date('Y-m-d H:i:s'),
      ];

      Orders::where('id',$OrderId)->update($arrOrderUpdate);
    }
    else
    {
      $is_updated = 0;
      $is_login_err = 1;
      $txt_msg = trans('errors.login_seller_required');
    }
    echo json_encode(array('status'=>$is_updated,'msg'=>$txt_msg,'is_login_err' => $is_login_err));
    exit;
  }

  public function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }

  function headersToArray( $str )
  {
      $headers = array();
      $headersTmpArray = explode( "\r\n" , $str );
      for ( $i = 0 ; $i < count( $headersTmpArray ) ; ++$i )
      {
          // we dont care about the two \r\n lines at the end of the headers
          if ( strlen( $headersTmpArray[$i] ) > 0 )
          {
              // the headers start with HTTP status codes, which do not contain a colon so we can filter them out too
              if ( strpos( $headersTmpArray[$i] , ":" ) )
              {
                  $headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
                  $headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+1 );
                  $headers[$headerName] = $headerValue;
              }
          }
      }
      return $headers;
  }

  public function getPaymentRequest($location) {

    $CAINFO = base_path().'/Getswish_Test_Certificates/Swish_TLS_RootCA.pem';
    $SSLCERT = base_path().'/Getswish_Test_Certificates/Swish_Merchant_TestCertificate_1234679304.pem';
    $SSLKEY =base_path().'/Getswish_Test_Certificates/Swish_Merchant_TestCertificate_1234679304.key';

    $ch = curl_init($location);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '1');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CAINFO, $CAINFO);
    curl_setopt($ch, CURLOPT_SSLCERT, $SSLCERT);
    curl_setopt($ch, CURLOPT_SSLKEY, $SSLKEY);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    /*curl_setopt($ch, CURLOPT_HEADERFUNCTION,
      function($curl, $header) use (&$headers) {
        // this function is called by curl for each header received
          $len = strlen($header);
          $header = explode(':', $header, 2);
          if (count($header) < 2) {
            // ignore invalid headers
              return $len;
          } 

          $name = strtolower(trim($header[0]));
          echo "[". $name . "] => " . $header[1];

          return $len;
       }
    ); */                                                                                                          $result = curl_exec($ch);
        // how big are the headers
        $headerSize = curl_getinfo( $ch , CURLINFO_HEADER_SIZE );
        $headerStr = substr( $result , 0 , $headerSize );
        $bodyStr = substr( $result , $headerSize );

        // convert headers to array
        $headers = $this->headersToArray( $headerStr );
        echo "<pre>";print_r($headers);     

    if(!$response = curl_exec($ch)) { 
          trigger_error(curl_error($ch)); 
      }
    curl_close($ch);
  }


  public function createPaymentRequest($amount, $message,$payerAlias,$order_id) {
    
    $instructionUUID = CartController::guidv4();
 //echo $id = uuid.NewV4().String();exit;
    $CAINFO = base_path().'/Getswish_Test_Certificates/Swish_TLS_RootCA.pem';
    //echo  file_exists($rootCert);exit;
   // $SSLCERT = base_path().'/Getswish_Test_Certificates/Swish_TechnicalSupplier_TestCertificate_9871065216.pem';
    
  $SSLCERT = base_path().'/Getswish_Test_Certificates/Swish_Merchant_TestCertificate_1234679304.pem';
   // $SSLKEY =base_path().'/Getswish_Test_Certificates/Swish_TechnicalSupplier_TestCertificate_9871065216.key';
   $SSLKEY =base_path().'/Getswish_Test_Certificates/Swish_Merchant_TestCertificate_1234679304.key';
  
    $username ='1231181189.p12';
     $password ="swish";
    //  $url ="https://mss.cpc.getswish.net/swish-cpcapi/api/v2/paymentrequests/11A86BE70EA346E4B1C39C874173F088";
    $url = "https://mss.cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests";
    $resultArr=array();
    //"https://mss.cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests"
    //$url ="https://mss.cpc.getswish.net/swish-cpcapi/api/v2/paymentrequests/".$instructionUUID;
      
       $data =[
             "payeePaymentReference"=> "0123456789",
              "callbackUrl"=>  url("/")."/checkout-swish-number-callback",
              "payerAlias"=> "46739866319",// 4671234768
              "payeeAlias"=> "1233144318",// 1231181189
              "amount"=> "100",
              "currency"=> "SEK",
              "message"=> "Kingston USB Flash Drive 8 GB"
        ];
         $data = json_encode($data);
        $data =str_replace("\/\/", "//", $data);
        $data =str_replace("\/", "/", $data);
        /* echo "<pre>";
         print_r($data );exit;*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        //curl_setopt($ch, CURLOPT_PUT, true);
       curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
       // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
       // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 50000); //in miliseconds

        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       //curl_setopt($ch, CURLOPT_PROXY_SSLCERTTYPE, "p12");
        curl_setopt($ch, CURLOPT_CAINFO, $CAINFO);
        curl_setopt($ch, CURLOPT_SSLCERT, $SSLCERT);
        curl_setopt($ch, CURLOPT_SSLKEY, $SSLKEY);
        curl_setopt($ch, CURLOPT_HEADER, 1);
    /*  $location =  curl_setopt($ch, CURLOPT_HEADERFUNCTION,
      function($ch, $header) use (&$headers) {
        // this function is called by curl for each header received
          $len = strlen($header);
          $header = explode(':', $header, 2);
          if (count($header) < 2) {
            // ignore invalid headers
              return $len;
          } 

          $name = strtolower(trim($header[0]));
         echo "[". $name . "] => " . $header[1];
        // $resultArr[$name] = $header[1];
         if($name=="location"){
          return $header[1];
         }
          //return $len;
       }
    );*/
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'swish');
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, 'swish');
        /*curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);*/
        
        $result = curl_exec($ch);
        // how big are the headers
        $headerSize = curl_getinfo( $ch , CURLINFO_HEADER_SIZE );
        $headerStr = substr( $result , 0 , $headerSize );
        $bodyStr = substr( $result , $headerSize );

        // convert headers to array
        $headers = $this->headersToArray( $headerStr );
        // echo "<pre>";print_r($headers);
       // echo "<pre>";print_r($headers['Date']);
        //dd($password);
       $location =  $headers['Location']; 
      // echo $location;
       $getPaymentRequest =$this->getPaymentRequest($location);
        echo "<pre>";print_r($getPaymentRequest);
        if (curl_errno($ch)) {
           $error_msg = curl_error($ch);
           echo $error_msg;
        }
        curl_close($ch);
        
        //$response = json_decode($result,true);

/*echo "<pre>---------";print_r($response);
       exit;*/
       

       
    /*const data = {
      payeeAlias: '1231111111',
      currency: 'SEK',
      callbackUrl: 'https://your-callback-url.com',
      amount,
      message,
    };

    try {
      const response = await client.put(
        `https://mss.cpc.getswish.net/swish-cpcapi/api/v2/paymentrequests/${instructionUUID}`,
        data
      );

      if (response.status === 201) {
        const { paymentrequesttoken } = response.headers;
        return { id: instructionUUID, token: paymentrequesttoken };
      }
    } catch (error) {
      console.error(error);
    }*/
  }


  public function CheckoutSwishNumberCallback(Request $request) {
    echo "<pre>";print_r($request->all());exit;
  }
    
}

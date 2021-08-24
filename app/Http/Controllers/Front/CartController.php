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
              $checkExistingOrderDetails = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->select(['products.user_id','temp_orders_details.order_id'])->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();
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
                    $attrIds = $variantAttr->name.' : '.$variantAttr->attribute_values;
                  }
                  else
                  {
                    $attrIds.= ', '.$variantAttr->name.' : '.$variantAttr->attribute_values;
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
              $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->select(['products.user_id as product_user','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();
              if(!empty($checkExistingOrderProduct))
              {
                  foreach($checkExistingOrderProduct as $details)
                  {
                      //Get Seller Shipping Informations
                      $SellerShippingData = UserMain::select('users.id','users.free_shipping','users.shipping_method','users.shipping_charges')->where('users.id','=',$details['product_user'])->first()->toArray();

                      if(!empty($Products[0]['shipping_method']) && !empty($Products[0]['shipping_charges']))
                      {
                        if($Products[0]['shipping_method'] == trans('users.flat_shipping_charges'))
                        {
                          $product_shipping_type = 'flat';
                          $product_shipping_amount = (float)$Products[0]['shipping_charges'];
                        }
                        else if($Products[0]['shipping_method'] == trans('users.prcentage_shipping_charges'))
                        {
                          $product_shipping_type = 'percentage';
                          $product_shipping_amount =((float)$Products[0]['price'] * $Products[0]['shipping_charges']) / 100;
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
                                $product_shipping_amount = ((float)$Products[0]['price'] * $SellerShippingData['shipping_charges']) / 100;
                              }
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
        $checkExisting = TmpOrders::where('user_id','=',$user_id)->get()->toArray();
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

            //Update Order Totals
            $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->join('variant_product', 'variant_product.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();
            if(!empty($checkExistingOrderProduct))
            {
                $subTotal       = 0;
                $shippingTotal  = 0;
                $total          = 0;

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
                              ->get();
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

                      $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.role_id')->where('users.id','=',$Product->user_id)->first()->toArray();
                      if($SellerData['role_id'] == 1)
                      {
                        $is_buyer_product = 1;
                        $orderDetails[$OrderId]['is_buyer_product'] = 1;
                      }
                      else
                      {
                        $orderDetails[$OrderId]['is_buyer_product'] = 0;
                      }
                      $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
                      $Product->quantity = $details['quantity'];
                      $Product->image    = explode(',',$Product->image)[0];
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

    public function showCheckout($orderId)
    {
      $data = [];
      $user_id = Auth::guard('user')->id();
      if($user_id && Auth::guard('user')->getUser()->role_id == 1)
      {
        $orderId = base64_decode($orderId);
        
        $checkExisting = TmpOrders::select('id')->where('id','=',$orderId)->get()->toArray();
        if(empty($checkExisting))
        {
          return redirect(route('frontShowCart'));
        }
        else
        {
          
          $OrderId = $checkExisting[0]['id'];
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
            $checkExistingOrderProduct = TmpOrdersDetails::join('products', 'temp_orders_details.product_id', '=', 'products.id')->join('variant_product', 'variant_product.product_id', '=', 'products.id')->select(['products.user_id as product_user','products.shipping_method','products.shipping_charges','products.discount','variant_product.price as product_price','temp_orders_details.*'])->where('order_id','=',$OrderId)->where('temp_orders_details.user_id','=',$user_id)->get()->toArray();
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

              $TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
                          ->join('categories', 'categories.id', '=', 'category_products.category_id')
                          ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
                          ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
                          ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
                          ->join('users', 'users.id', '=', 'products.user_id')
                          //->join('attributes',  'attributes.id', '=', 'variant_product_attribute.attribute_value_id')
                          ->select(['products.*','categories.category_name', 'variant_product.image','variant_product.price','variant_product.id as variant_id','users.klarna_username','users.klarna_password'])
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
                  
                  if(empty($username) && empty($password))
                  {
                    $username = $Product->klarna_username;
                    $password = base64_decode($Product->klarna_password);
                  }
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
      
        

      if(empty($username) || empty($password))
        {
          $blade_data['error_messages']= trans('errors.seller_credentials_err');
          return view('Front/payment_error',$blade_data); 
        }
      
        $UserData = UserMain::select('users.*')->where('users.id','=',$user_id)->first()->toArray();
        $billing_address= [];
        $billing_address['given_name'] = $UserData['fname'];
        $billing_address['family_name'] = $UserData['lname'];
        $billing_address['email'] = $UserData['email'];
        $billing_address['street_address'] = $UserData['address'];
        $billing_address['postal_code'] = $UserData['postcode'];
        $billing_address['city'] = $UserData['city'];
        $billing_address['phone'] = $UserData['phone_number'];
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
                 "checkout"=> url("/")."/checkout_callback?order_id={checkout.order.id}",
                 "confirmation"=> url("/")."/checkout_callback?order_id={checkout.order.id}",
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
        
        if(empty($response))
        {
          $blade_data['error_messages']= 'Något gick fel, kontakta admin.';
           return view('Front/payment_error',$blade_data); 
        }
        if(!empty($response->error_messages))
        {
          $cnt_err = count($response->error_messages);
        }
        
        if (isset($error_msg) || @$cnt_err ) {
           $blade_data['error_messages']= trans('errors.payment_failed_err');
           return view('Front/payment_error',$blade_data); 
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
        return view('Front/checkout', $param);

      }
      else
      {
        Session::flash('error', trans('errors.login_buyer_required'));
        return redirect(route('frontLogin'));
      }
    }

    /* function for klarna payment callback*/
    public function checkoutCallback(Request $request)
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

        if($order_amount != $Total)
        {
          $data['error_messages']=trans('errors.order_amount_mismatched');
          return view('Front/payment_error',$data);
        }
        else
        {
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
      }

        return redirect(route('frontCheckoutSuccess',['id' => base64_encode($NewOrderId)]));
      }
  }

  public function showCheckoutSuccess($id)
  {
    $data = [];
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

        $GetOrder = Orders::join('users', 'users.id', '=', 'orders.user_id')->select('users.fname','users.lname','users.email','orders.*')->where('orders.id','=',$checkExisting['id'])->get()->toArray();

        //START : Send success email to User.
          $email = trim($GetOrder[0]['email']);
          $name  = trim($GetOrder[0]['fname']).' '.trim($GetOrder[0]['lname']);

          // $arrMailData = ['name' => $name, 'email' => $email, 'order_details_link' => url('/').'/order-details/'.base64_encode($GetOrder[0]['id'])];

          // Mail::send('emails/order_success', $arrMailData, function($message) use ($email,$name) {
          //     $message->to($email, $name)->subject
          //         ('Tijara - Order successfull.');
          //     $message->from('developer@techbeeconsulting.com','Tijara');
          // });

          $GetEmailContents = getEmailContents('Order Success');
          $subject = $GetEmailContents['subject'];
          $contents = $GetEmailContents['contents'];
          $url = url('/').'/order-details/'.base64_encode($GetOrder[0]['id']);
          $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##'],[$name,$email,url('/'),$url],$contents);

          $arrMailData = ['email_body' => $contents];

          Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$name,$subject) {
              $message->to($email, $name)->subject
                  ($subject);
              $message->from( env('FROM_MAIL'),'Tijara');
          });

        //END : Send success email to User.


        $OrderProducts = OrdersDetails::join('products','products.id', '=', 'orders_details.product_id')->select('products.user_id as product_user','orders_details.*')->where('order_id','=',$GetOrder[0]['id'])->offset(0)->limit(1)->get()->toArray();

        $GetSeller = UserMain::select('users.fname','users.lname','users.email')->where('id','=',$OrderProducts[0]['product_user'])->first()->toArray();

        //START : Send success email to Seller.
          $emailSeller = trim($GetSeller['email']);
          $nameSeller  = trim($GetSeller['fname']).' '.trim($GetSeller['lname']);

          $admin_email = 'shrik.techbee@gmail.com';
          $admin_name  = 'Tijara Admin';
          
          // $arrMailData = ['name' => $nameSeller, 'email' => $emailSeller, 'order_details_link' => url('/').'/order-details/'.base64_encode($GetOrder[0]['id'])];

          // Mail::send('emails/order_success', $arrMailData, function($message) use ($emailSeller,$nameSeller,$admin_email,$admin_name) {
          //     $message->to($emailSeller, $nameSeller)->cc($admin_email,$admin_name)->subject
          //         ('Tijara - New Order placed');
          //     $message->from('developer@techbeeconsulting.com','Tijara');
          // });

          $GetEmailContents = getEmailContents('Order Success');
          $subject = $GetEmailContents['subject'];
          $contents = $GetEmailContents['contents'];
          $url = url('/').'/order-details/'.base64_encode($GetOrder[0]['id']);
          $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##'],[$nameSeller,$emailSeller,url('/'),$url],$contents);

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
              $wishlistProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
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

                  $product_link	=	url('/').'/product';

                  $product_link	.=	'/'.$productCategories[0]['category_slug'];
                  $product_link	.=	'/'.$productCategories[0]['subcategory_slug'];

                  $product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

                  $Product->product_link	=	$product_link;

                  $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                  $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
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

                              $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Service->user_id)->first()->toArray();
                              $Service->seller  = $SellerData['fname'].' '.$SellerData['lname'];
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


    function showOrderDetails($id)
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

                          $product_link	=	url('/').'/product';

                          $product_link	.=	'/'.$productCategories[0]['category_slug'];
                          $product_link	.=	'/'.$productCategories[0]['subcategory_slug'];

                          $product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

                          $Product->product_link	=	$product_link;

                          $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                          $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
                          
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

                          $product_link	=	url('/').'/product';

                          $product_link	.=	'/'.$productCategories[0]['category_slug'];
                          $product_link	.=	'/'.$productCategories[0]['subcategory_slug'];

                          $product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

                          $Product->product_link	=	$product_link;

                          $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
                          $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
                          
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
          return $pdf->download('pdfview.pdf');
        }
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
                  $shipping_total = (!empty($recordDetailsVal['shipping_total'])) ? number_format($recordDetailsVal['shipping_total'],2) : '-';
                  $total = (!empty($recordDetailsVal['total'])) ? number_format($recordDetailsVal['total'],2) : '-';
                  $payment_status = (!empty($recordDetailsVal['payment_status'])) ? $recordDetailsVal['payment_status'] : '-';
                  $order_status = (!empty($recordDetailsVal['order_status'])) ? $recordDetailsVal['order_status'] : '-';
                  $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['created_at']));
                  
                  $action = '<a href="'.route('frontShowOrderDetails', base64_encode($id)).'" title="'. trans('lang.txt_view').'"><i class="fas fa-eye"></i> </a>&nbsp;&nbsp;<a href="'.route('frontDownloadOrderDetails', base64_encode($id)).'" title="Download"><i class="fas fa-file-download"></i> </a>';

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
}

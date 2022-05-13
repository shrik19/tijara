<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\UserMain;
use App\Models\Products;
use App\Models\ProductCategory;
use App\Models\Settings;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;
use PDF;
use File;

class OrderController extends Controller
{
    /*
    * Define abjects of models, services.
    */
    function __construct() {

    }

    /**
    * Show list of records for Package.
    * @return [array] [record array]
    */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.order_title');
        $data['current_module_name']    = trans('users.order_title');
        $data['module_name']            = trans('users.order_title');
        $data['module_url']             = route('adminOrder');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Orders/index', $data);
    }

    /**

     * [getRecords for product list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) 
    {
      
    $orders = Orders::join('users','users.id','=','orders.user_id')->join('orders_details','orders_details.order_id','=','orders.id')->join('products','products.id','=','orders_details.product_id')->select('orders.*','users.fname','users.lname','users.email');
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
                  $subtotal = (!empty($recordDetailsVal['sub_total'])) ? swedishCurrencyFormat($recordDetailsVal['sub_total']) : '-';
                  $shipping_total = (!empty($recordDetailsVal['shipping_total'])) ? swedishCurrencyFormat($recordDetailsVal['shipping_total']).' kr' : '-';
                  $total = (!empty($recordDetailsVal['total'])) ? number_format($recordDetailsVal['total'],2) : '-';
                  $payment_status = (!empty($recordDetailsVal['payment_status'])) ? $recordDetailsVal['payment_status'] : '-';
                  $order_status = (!empty($recordDetailsVal['order_status'])) ? $recordDetailsVal['order_status'] : '-';

                 if($order_status=="PENDING"){
                       $order_status =trans("users.pending_order_status");
                  }else if($order_status=="SHIPPED"){
                       $order_status = trans("users.shipped_order_status");
                  }else if($order_status=="CANCELLED"){
                       $order_status = trans("users.cancelled_order_status");
                  }else{
                       $order_status = $order_status;
                  }

                   if($payment_status=="Pending"){
                       $payment_status =trans("users.pending_order_status");
                  }else if($payment_status=="PAID" || $payment_status=="CAPTURED" || $payment_status=="checkout_complete"){
                       $payment_status = trans("users.paid_payment_status");
                  }else if($payment_status=="CANCELLED"){
                       $payment_status = trans("users.cancelled_order_status");
                  }else{
                       $payment_status = $payment_status;
                  }

                date_default_timezone_set("Europe/Stockholm");  
                $dated = $recordDetailsVal['created_at'];
                $dated = date('Y-m-d g:i a',strtotime("$dated UTC"));

                 // $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['created_at']));
                  
                  $action = '<a href="'.route('adminOrderView', base64_encode($id)).'" title="'. trans('lang.txt_view').'"><i class="fas fa-eye"></i> </a>&nbsp;&nbsp;<a href="'.route('adminDownloadOrderDetails', base64_encode($id)).'" title="Download"><i class="fas fa-file-download"></i> </a>';

                  $arr[] = [ '#'.$id, $user, $subtotal.' kr', $shipping_total,$total.' kr',  $payment_status, $order_status, $dated, $action];
                    

              }

          } 

          else {

            $arr[] = [ '', '', '', '', trans('lang.datatables.sEmptyTable'), '', '', '', ''];

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
    $txt_msg =  trans('lang.order_status_success');;
    if($user_id && session('role_id') == 2)
    {
        $OrderId = $request->order_id;
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

  function view($id)
  {
        $data['pageTitle']              = trans('users.order_title');
        $data['current_module_name']    = trans('users.order_title');
        $data['module_name']            = trans('users.order_title');
        $data['module_url']             = route('adminOrder');

      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $orderDetails = [];
        
        $OrderId = base64_decode($id);
        $checkOrder = Orders::where('id','=',$OrderId)->first()->toArray();
        if(!empty($checkOrder))
        {
          $data['subTotal'] = $checkOrder['sub_total'];
          $data['Total'] = $checkOrder['sub_total'] +  $checkOrder['shipping_total'];//$checkOrder['total'];
          $data['shippingTotal'] = $checkOrder['shipping_total'];

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
                        }
                      }
                  }
              }
              
          

          $data['details'] = $orderDetails;
          $data['order'] = $checkOrder;
          $data['is_seller'] = $is_seller;
          $address = json_decode($checkOrder['address'],true);
          
          $data['billingAddress'] = json_decode($address['billing'],true);
          $data['shippingAddress'] = json_decode($address['shipping'],true);
          return view('Admin/Orders/view', $data);
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
          $data['Total'] = $checkOrder['sub_total'] +  $checkOrder['shipping_total'];//$checkOrder['total'];
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

                          $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Product->user_id)->first()->toArray();
                          //$Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];
                          $Product->seller  = $SellerData['store_name'];
                          
                          $data['seller_name'] = $Product->seller;
                          $seller_name = str_replace( array( '\'', '"', 
                          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '',$Product->seller);
                          $seller_name = str_replace(" ", '-', $seller_name);
                          $seller_name = strtolower($seller_name);

                          $sellerLink = route('sellerProductListingByCategory',['seller_name' => $seller_name]);
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
          $site_details          = Settings::first();
          $data['siteDetails']   = $site_details;
         // return view('Admin/Orders/download_order_details', $data);
          $pdf = PDF::loadView('Admin/Orders/download_order_details',$data);
          return $pdf->download('order-#'.$OrderId.'.pdf');
        }
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }
}

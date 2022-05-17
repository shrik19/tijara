<?php
use App\Models\Page;
use App\Models\TmpOrdersDetails;
use App\Models\VariantProduct;
use App\Models\Wishlist;
use App\Models\Emails;
use App\Models\Categories;
use App\Models\UserMain;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ServiceRequest;
use App\Models\Services;

//use DB;

/** Get all Custom Pages. */
function getCustomPages()
{
    /*$allPages = Page::where('slug_en','!=','contact-us')->where('slug_en','!=','faq')->where('slug_en','!=','how-to-sell')->where([['status','=','active'],['is_deleted','=', '0']])->get()->toArray();*/
    $allPages = Page::where('display_in_section','=','About Tijara')->where([['status','=','active'],['is_deleted','=', '0']])->get()->toArray();
    return $allPages;
}
/** Get FAQ Custom Pages. */
function getCustomerServicePage()
{
  /*  $getCustomerServicePage = Page::where('slug_en','=','contact-us')->orwhere('slug_en','=','faq')->where([['status','=','active'],['is_deleted','=', '0']])->orderBy('pages.id','DESC')->get()->toArray();*/

    $getCustomerServicePage = Page::where('display_in_section','=','Customer service')->where([['status','=','active'],['is_deleted','=', '0']])->orderBy('pages.id','DESC')->get()->toArray();
    return $getCustomerServicePage;
}

/** Get How to Sell Custom Pages. */
function getHowToSellPage()
{
    $getHowToSellPage = Page::where('display_in_section','=','Sell')->where([['status','=','active'],['is_deleted','=', '0']])->get()->toArray();
    return $getHowToSellPage;
}

function getOrderProducts($userId)
{
  $allOrderProducts = TmpOrdersDetails::
  join('temp_orders', 'temp_orders_details.order_id', '=', 'temp_orders.id')->
  where('temp_orders.user_id','=',$userId)->where('temp_orders.show_in_cart','=',1)
  ->select(['temp_orders_details.*'])
  ->get()->toArray();
  if(!empty($allOrderProducts))
  {
    foreach($allOrderProducts as $key => $order)
    {
      $checkVariant = VariantProduct::where('id','=',$order['variant_id'])->get()->toArray();
      if(empty($checkVariant))
      {
        $tmpOrderDetails = TmpOrdersDetails::find($order['id']);
        $tmpOrderDetails->delete();

        unset($allOrderProducts[$key]);
      }
    }
  }
  return count($allOrderProducts);
}

function getWishlistProducts($userId)
{

  $allWishlistProducts = Wishlist::join('products', 'products.id', '=', 'wishlist.product_id')->where('wishlist.user_id','=',$userId)->where('products.is_deleted','!=',1)->where('products.is_buyer_product','!=',1)->where('products.status','!=',"block")->get()->toArray();

  if(!empty($allWishlistProducts))
  {
    foreach($allWishlistProducts as $key => $details)
    {
      if(!empty($details['product_id'])){
        $checkVariant = VariantProduct::where('id','=',$details['variant_id'])->get()->toArray();
        if(empty($checkVariant))
        {
          $tmpWishlistDetails = Wishlist::find($details['id']);
          $tmpWishlistDetails->delete();

          unset($allWishlistProducts[$key]);
        }
      }
    }
  }
  return count($allWishlistProducts);
}

function checkPackageSubscribe($userId)
{
  $is_subscribe_package=   DB::table('user_packages')
    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
    ->where('packages.is_deleted','!=',1)
    ->where('user_packages.status','=','active')
    ->where('user_id','=',$userId)
    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status','user_packages.payment_status')
    ->orderByRaw('user_packages.id')
    ->get();
                   
    return count($is_subscribe_package);
}

function getEmailContents($title)
{
  $emailContent = Emails::where('title','=',$title)->first()->toArray();
  return $emailContent;
}
/*function to get categoried and subcategories in subheader menus*/
function getCategorySubcategoryList() {
  DB::enableQueryLog();
    $Categories     = Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
                ->select('categories.id','categories.category_name','categories.category_slug','subcategories.subcategory_name','subcategories.subcategory_slug')
                ->where('subcategories.status','=','active')
                ->where('categories.status','=','active')
                ->where('categories.is_menu','=','1')
                ->orderBy('categories.sequence_no')
                ->orderBy('subcategories.sequence_no')
               // ->offset(0)->limit(7)
                ->get()
                ->toArray();

  // and then you can get query log
  //print_r(DB::getQueryLog());exit;
    $CategoriesArray  = array();
    foreach($Categories as $category) {
      $CategoriesArray[$category['id']]['category_name']= $category['category_name'];
      $CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
      $CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
    }
    
    $CategoriesArray = array_slice($CategoriesArray, 0, 7);
    return $CategoriesArray;
  }

/*function to get seller to add in link if login as seller*/
function get_link_seller_name($SellerId){
  $tmpSellerData = UserMain::where('id',$SellerId)->first()->toArray();
  $seller_name = $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
  $seller_name = str_replace( array( '\'', '"', 
  ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
  $seller_name = str_replace(" ", '-', $seller_name);
  $seller_name = strtolower($seller_name);
  return $seller_name;
}


function getFirstOrderYear()
{
  $tmpOrderData = Orders::select('created_at')->orderBy('id','asc')->take(1)->first()->toArray();
  return $tmpOrderData;
}

function getTotalOrders($filterMonth='', $filterYear='', $sellerId = 0)
{
  if($filterMonth!='' &&  $filterYear!=''){
    $orderCount = Orders::where('orders.created_at', 'like', $filterYear.'-'.$filterMonth. '%');
  }else{
    $orderCount = Orders::select('orders.id');
  }

  if($sellerId)
  { 
    $orderCount = $orderCount->join('orders_details','orders_details.order_id', '=', 'orders.id');
    $orderCount = $orderCount->join('products', 'products.id', '=', 'orders_details.product_id');
    $orderCount = $orderCount->where('products.user_id', '=', $sellerId);
  }
  $orderCount = $orderCount->count();
  return $orderCount;
}

function getTotalServiceRequests($filterMonth, $filterYear, $sellerId = 0)
{
  if($filterMonth!='' &&  $filterYear!=''){
      $serviceRequestCount = ServiceRequest::where('service_requests.service_date', 'like', $filterYear.'-'.$filterMonth. '%')->where('service_requests.is_deleted', '=', '0');
  }else{
      $serviceRequestCount = ServiceRequest::select('service_requests.id')->where('service_requests.is_deleted', '=', '0');
  }

  if($sellerId)
  { 
    $serviceRequestCount = $serviceRequestCount->join('services','services.id', '=', 'service_requests.service_id');
    $serviceRequestCount = $serviceRequestCount->where('services.user_id', '=', $sellerId);
  }
  $serviceRequestCount = $serviceRequestCount->count();
  return $serviceRequestCount;
}


function getTotalProducts($filterMonth, $filterYear, $sellerId = 0)
{
  if($filterMonth!='' &&  $filterYear!=''){
      $productCount = Products::where('created_at', 'like', $filterYear.'-'.$filterMonth. '%')->where('is_deleted', '=', '0');
  }else{
        $productCount = Products::select('products.id')->where('is_deleted', '=', '0');
  }

  if($sellerId)
  { 
    $productCount = $productCount->where('products.user_id', '=', $sellerId);
  }
  $productCount = $productCount->count();
  return $productCount;
}

function getTotalServices($filterMonth, $filterYear, $sellerId = 0)
{
  if($filterMonth!='' &&  $filterYear!=''){
      $servicesCount = Services::where('created_at', 'like', $filterYear.'-'.$filterMonth. '%')->where('is_deleted', '=', '0');
  } else {
    $servicesCount = Services::select('services.id')->where('is_deleted', '=', '0');
  }
  if($sellerId)
  { 
    $servicesCount = $servicesCount->where('services.user_id', '=', $sellerId);
  }
  $servicesCount = $servicesCount->count();
  return $servicesCount;
}

function getTotalAmount($filterMonth, $filterYear, $sellerId = 0)
{
  if($filterMonth!='' &&  $filterYear!=''){
     $orderTotal = Orders::where('orders.created_at', 'like', $filterYear.'-'.$filterMonth. '%');
  } else {
    $orderTotal = Orders::select('orders.id');
  }
  if($sellerId)
  { 
    $orderTotal = $orderTotal->join('orders_details','orders_details.order_id', '=', 'orders.id');
    $orderTotal = $orderTotal->join('products', 'products.id', '=', 'orders_details.product_id');
    $orderTotal = $orderTotal->where('products.user_id', '=', $sellerId);
  }
  $orderTotal = $orderTotal->sum('total');
  
  return number_format($orderTotal,2,'.','');
}

function getNewOrders($userId)
{

 $allOrders = Orders::join('orders_details', 'orders_details.order_id', '=', 'orders.id')->join('products','products.id','=','orders_details.product_id')->where('products.user_id','=',$userId)->where('orders.is_new','=','1')->get()->toArray();

  return count($allOrders);
}

function getNewBookings($userId)
{
//DB::enableQueryLog();

  $allBookings = ServiceRequest::join('services', 'service_requests.service_id', '=', 'services.id')->where('services.user_id','=',$userId)->where('service_requests.is_new','=','1')->where("services.is_deleted",'=','0')->get()->toArray();

  return count($allBookings);
}

/*
function to convert amount into swedish number format
@param:$amount
@return:swedish currency format
*/
function swedishCurrencyFormat($amount){

 $number = number_format(str_ireplace('.','.',$amount), 2, ',',' ');
 return $number;
}
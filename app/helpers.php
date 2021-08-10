<?php
use App\Models\Page;
use App\Models\TmpOrdersDetails;
use App\Models\VariantProduct;
use App\Models\Wishlist;
use App\Models\Emails;

/** Get all Custom Pages. */
function getCustomPages()
{
    $allPages = Page::where([['status','=','active'],['is_deleted','=', '0']])->get()->toArray();
    return $allPages;
}


function getOrderProducts($userId)
{
  $allOrderProducts = TmpOrdersDetails::where('user_id','=',$userId)->get()->toArray();
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
  $allWishlistProducts = Wishlist::where('user_id','=',$userId)->get()->toArray();
  if(!empty($allWishlistProducts))
  {
    foreach($allWishlistProducts as $key => $details)
    {
      $checkVariant = VariantProduct::where('id','=',$details['variant_id'])->get()->toArray();
      if(empty($checkVariant))
      {
        $tmpWishlistDetails = Wishlist::find($details['id']);
        $tmpWishlistDetails->delete();

        unset($allWishlistProducts[$key]);
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

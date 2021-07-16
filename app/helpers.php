<?php
use App\Models\Page;
use App\Models\TmpOrdersDetails;
use App\Models\VariantProduct;
use App\Models\Wishlist;

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

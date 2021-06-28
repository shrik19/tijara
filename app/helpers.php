<?php
use App\Models\Page;
use App\Models\TmpOrdersDetails;

/** Get all Custom Pages. */
function getCustomPages()
{
    $allPages = Page::where([['status','=','active'],['is_deleted','=', '0']])->get()->toArray();
    return $allPages;
}


function getOrderProducts($userId)
{
  $allOrderProducts = TmpOrdersDetails::where('user_id','=',$userId)->get()->toArray();
  return count($allOrderProducts);
}

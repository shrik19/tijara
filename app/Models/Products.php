<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Products extends Model
{
    protected $table 	= 'products';

    protected $fillable = ['id','title','meta_title','meta_description','meta_keyword','description',
    'status','sort_order','user_id','is_deleted','product_slug','product_code','shipping_method',
    'shipping_charges','is_buyer_product','is_sold','sold_date','rating','rating_count','discount','free_shipping','store_pick_address','is_pick_from_store','created_at','updated_at'];

    public $timestamp=false;
    /* function to get products count*/
    public static function get_product_count(){
        $product = DB::table('products')
                   ->where('is_deleted','!=',1)
                   ->where('status','=','active')
                   ->get();
        $product_count = count($product);
        return $product_count;
    } 

}

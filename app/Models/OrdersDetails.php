<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    protected $table = 'orders_details';
    protected $fillable = ['id','order_id','user_id','product_id','variant_id','variant_attribute_id','price','quantity','shipping_type','shipping_amount','status','created_at','updated_at'];

}

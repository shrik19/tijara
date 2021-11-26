<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class AdminOrders extends Model
{
    protected $table = 'admin_orders';
    protected $fillable = ['id','user_id','address','order_lines','total','payment_details','payment_status','order_status','created_at','updated_at','klarna_order_reference','product_id','tmp_order_id'];

}

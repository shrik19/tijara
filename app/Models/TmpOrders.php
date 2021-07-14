<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class TmpOrders extends Model
{
    protected $table = 'temp_orders';
    protected $fillable = ['id','user_id','sub_total','shipping_total','total','payment_details','payment_status','order_status','created_at','updated_at','klarna_order_reference'];

}

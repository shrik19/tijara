<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = ['id','user_id','address','order_lines','sub_total','shipping_total','total','payment_details','payment_status','order_status','created_at','updated_at','order_complete_at','klarna_order_reference'];

}

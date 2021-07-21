<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class TmpAdminOrders extends Model
{
    protected $table = 'temp_admin_orders';
    protected $fillable = ['id','user_id','total','product_details','order_status','created_at','updated_at','klarna_order_reference'];

}

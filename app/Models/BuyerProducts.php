<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerProducts extends Model
{
    protected $table 	= 'buyer_products';

    protected $fillable = ['id','product_id','user_id','user_name','user_email','user_phone_no',
    'country','location','price'];
    public $timestamps = false;
}

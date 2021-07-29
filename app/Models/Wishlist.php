<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table 	= 'wishlist';
    protected $fillable = ['id','user_id','product_id','variant_id','variant_attribute_id','created_at','updated_at','service_id' ];
}

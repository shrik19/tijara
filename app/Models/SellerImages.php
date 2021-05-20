<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerImages extends Model
{
    protected $table 	= 'seller_images';
    protected $fillable = ['user_id','image','image_order'];
}

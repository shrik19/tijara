<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantProduct extends Model
{
    protected $table 	= 'variant_product';
    protected $fillable = ['product_id','price','sku','weight','image','quantity'];
    public $timestamps = false;
}

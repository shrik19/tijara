<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $table 	= 'product_attributes';
    protected $fillable = ['product_id','attribute_id','attribute_value_id','sku','image','weight','price'];
}

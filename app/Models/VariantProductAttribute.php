<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantProductAttribute extends Model
{
    protected $table 	= 'variant_product_attribute';
    protected $fillable = ['variant_id','product_id','attribute_id','attribute_value_id'];
    public $timestamps = false;
}

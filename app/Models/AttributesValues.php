<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributesValues extends Model
{
    protected $table 	= 'attributes_values';
    protected $fillable = ['attribute_id','attribute_values'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubcategories extends Model
{
    protected $table 	= 'serviceSubcategories';
    protected $fillable = ['subcategory_name','category_id'];
}

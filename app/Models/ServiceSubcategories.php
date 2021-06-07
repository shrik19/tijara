<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubcategories extends Model
{
    protected $table 	= 'servicesubcategories';
    protected $fillable = ['subcategory_name','category_id','sequence_no','subcategory_slug'];
}

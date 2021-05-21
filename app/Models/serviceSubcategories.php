<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class serviceSubcategories extends Model
{
    protected $table 	= 'serviceSubcategories';
    protected $fillable = ['subcategory_name','category_id'];
}

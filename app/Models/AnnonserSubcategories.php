<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnonserSubcategories extends Model
{
    protected $table 	= 'annonserSubcategories';
    protected $fillable = ['subcategory_name','category_id','sequence_no','subcategory_slug'];
}

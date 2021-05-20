<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    protected $table 	= 'subcategories';
    protected $fillable = ['subcategory_name','category_id','sequence_no'];
}

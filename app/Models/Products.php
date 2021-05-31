<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table 	= 'products';
    protected $fillable = ['id','title','meta_title','meta_description','meta_keyword','description', 'product_slug','status','sort_order','user_id','is_deleted'];
    
    
}

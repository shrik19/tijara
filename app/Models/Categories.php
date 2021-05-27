<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table 	= 'categories';
    protected $fillable = ['id','category_name','description','sequence_no','category_slug'];
    
    public function getSubCat(){
   	    return $this->hasMany('App\Models\Subcategories', 'category_id','id');
   }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategories extends Model
{
    protected $table 	= 'servicecategories';
    protected $fillable = ['id','category_name','sequence_no','description','category_slug'];
    
    public function getSubCat(){
   	    return $this->hasMany('App\Models\ServiceSubcategories', 'category_id','id');
   }
}

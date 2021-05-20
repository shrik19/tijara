<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategories extends Model
{
    protected $table 	= 'ServiceCategories';
    protected $fillable = ['id','category_name','sequence_no','description'];
    
    public function getSubCat(){
   	    return $this->hasMany('App\Models\ServiceSubcategories', 'category_id','id');
   }
}

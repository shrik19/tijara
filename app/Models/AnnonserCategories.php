<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnonserCategories extends Model
{
    protected $table 	= 'annonsercategories';
    protected $fillable = ['id','category_name','sequence_no','description','category_slug'];
    
    public function getSubCat(){
   	    return $this->hasMany('App\Models\AnnonserSubcategories', 'category_id','id');
   }
}

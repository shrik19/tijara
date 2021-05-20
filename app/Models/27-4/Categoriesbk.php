<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table 	= 'categories';
    protected $fillable = ['id','category_name','parent_id'];
    
    /*public function getSubCat(){
   	    return $this->hasMany('App\Models\Subcategories', 'category_id','id');
   }
*/
   public function getChilds(){
   		return $this->hasMany('App\Models\categories', 'parent_id');
   }


   public function categories()
   {
      return $this->hasMany(Category::class);
   }


}

<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table 	= 'banner';
    protected $fillable = ['id','title','redirect_link','image','display_on_page'];


    public function getSlider() {
      return $this->hasMany('App\Models\Banner', 'id', 'id');
    }

    public static function get_slider($id) {
      $Province = DB::table('banner')
               ->where('is_deleted','!=',1)
               ->where('id','=',$id)
               ->get();
                
      return $Province;
    }

    
}

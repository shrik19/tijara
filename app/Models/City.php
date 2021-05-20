<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table 	= 'cities';
    protected $fillable = ['id','name','status','is_deleted'];
	public $timestamps = false;
	
    public static function get_city($id){
      $city = DB::table('cities')
      ->where('is_deleted','!=',1)
      ->where('id','=',$id)
      ->get();
      return $city;
    }
}

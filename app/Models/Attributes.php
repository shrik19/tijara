<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Attributes extends Model
{
    protected $table 	= 'attributes';
    protected $fillable = ['name','type','user_id'];

    

    /* function to get all attributes*/
    public static function get_attribute(){
        $attributes = DB::table('attributes')
                  ->select('id','name','type')
                  ->get();
        return $attributes;
    }
}

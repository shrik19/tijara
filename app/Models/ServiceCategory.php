<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table 	= 'category_services';
    protected $fillable = ['id','service_id','category_id','subcategory_id'];
    
    public $timestamps = false;
}

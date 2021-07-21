<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table 	= 'services';
    protected $fillable = ['id','title','description','sort_order','service_slug','status',
    'user_id','is_deleted','images','service_code','price_type','service_price'];
    
    
}

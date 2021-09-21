<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table 	= 'services';
    protected $fillable = ['id','title','description','sort_order','service_slug','status',
    'user_id','is_deleted','images','service_code','session_time','service_price','rating','rating_count','address'];
    
    
}

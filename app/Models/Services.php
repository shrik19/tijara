<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table 	= 'services';
    protected $fillable = ['id','title','description','sort_order','status','user_id','is_deleted'];
    
    
}

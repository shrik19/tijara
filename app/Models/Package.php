<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table 	= 'packages';
    protected $fillable = ['id','title','description','amount','validity_days','recurring_payment','status','is_deleted','created_at','updated_at' ];
}

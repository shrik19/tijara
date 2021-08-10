<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    protected $table 	= 'service_review';
    protected $fillable = ['id','service_id','user_id','comments','rating','is_approved','created_at','updated_at' ];
}

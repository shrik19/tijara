<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table 	= 'product_review';
    protected $fillable = ['id','product_id','user_id','comments','rating','is_approved','created_at','updated_at' ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportProduct extends Model
{
    protected $table 	= 'product_report';
    protected $fillable = ['id','buyer_email','product_id','product_link','message','seller_name','created_at','updated_at'];
    public $timestamp=false;
}

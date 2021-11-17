<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportService extends Model
{
    protected $table 	= 'service_report';
    protected $fillable = ['id','buyer_email','service_id','service_link','message','seller_name','created_at','updated_at'];
    public $timestamp=false;
}

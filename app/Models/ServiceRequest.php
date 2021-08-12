<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';
    protected $fillable = ['id','user_id','service_id','service_date','is_deleted','created_at','updated_at'
    ,'service_time','phone_number','location','service_title'];

}

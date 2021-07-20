<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';
    protected $fillable = ['id','user_id','service_id','message','created_at','updated_at'];

}

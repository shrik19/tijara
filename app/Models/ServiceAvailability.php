<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAvailability extends Model
{
    protected $table 	= 'service_availability';
    protected $fillable = ['service_id','service_date','start_time'];
    public $timestamps = false;
}

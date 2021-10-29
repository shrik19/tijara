<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackages extends Model
{
    protected $table 	= 'user_packages';
    protected $fillable = ['id','user_id','package_id','start_date','end_date','status','order_id','payment_status','payment_response','is_trial'];
    public $timestamps = false;
}

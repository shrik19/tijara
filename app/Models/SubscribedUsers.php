<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribedUsers extends Model
{
    protected $table 	= 'subscribed_users';
    protected $fillable = ['id','email','is_subscriber','created_at','updated_at'];
}

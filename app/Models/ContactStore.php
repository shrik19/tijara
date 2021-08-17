<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactStore extends Model
{
    protected $table 	= 'contact_store';
    protected $fillable = ['id','seller_id','email','message','created_at','updated_at'];
    public $timestamp=false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPersonalPage extends Model
{
    protected $table 	= 'seller_personal_page';
    protected $fillable = ['id','user_id','header_img','logo'];
    
    public $timestamp=false;
}

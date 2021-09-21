<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPersonalPage extends Model
{
    protected $table 	= 'seller_personal_page';
    protected $fillable = ['id','user_id','header_img','logo','store_information','payment_policy','return_policy','shipping_policy','cancellation_policy','updated_at'];
    
    public $timestamp=false;
}

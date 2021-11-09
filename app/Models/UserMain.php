<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserMain extends Model
{
    protected $table 	= 'users';

    protected $fillable = ['role_id','fname','lname','email','paypal_email','phone_number','address','store_name','city','swish_number','postcode','profile','description','where_find_us','is_verified','free_shipping','shipping_method','shipping_charges','klarna_username','klarna_password','swish_api_key','swish_merchant_account','swish_client_key','strip_api_key','strip_secret','is_featured','card_fname','card_lname','card_number','card_exp_date','card_security_code','activation_token','activation_status','shop_close_date','is_shop_closed'];


    /* function to get buyers*/
     public static function get_buyers($id){
        $buyer = DB::table('users')
                   ->where('is_deleted','!=',1)
                   ->where('role_id','=',1)
                   ->where('id','=',$id)
                   ->get();

        return $buyer;
    }   
    
    /* function to get buyers count*/
    public static function get_buyers_count(){
        $buyer = DB::table('users')
                   ->where('is_deleted','!=',1)
                   ->where('role_id','=',1)
                   ->where('is_verified','=',1)
                   ->get();
        $buyer_count = count($buyer);
        return $buyer_count;
    } 

    /* function to get sellers*/
    public static function get_seller($id){
        $seller = DB::table('users')
                   ->where('is_deleted','!=',1)
                   ->where('role_id','=',2)
                   ->where('id','=',$id)
                   ->get();
        return $seller;
    }

    public function getImages() {
        return $this->hasMany('App\Models\SellerImages', 'user_id', 'id');
    }
}

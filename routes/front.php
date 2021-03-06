<?php
     
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Front\HomeController@index')->name('frontHome');
Route::any('/front-login','Front\AuthController@login')->name('frontLogin');
Route::post('/validate-login','Front\AuthController@doLogin')->name('doLogin');
Route::get('/front-logout','Front\AuthController@logout')->name('frontLogout');
Route::get('/buyer-register','Front\AuthController@buyer_register')->name('buyer_register');
Route::get('/seller-register','Front\AuthController@seller_register')->name('seller_register');
Route::post('/do-register','Front\AuthController@doRegister')->name('do-register');
Route::get('/register-success','Front\AuthController@register_success')->name('frontRegisterSuccess');


/*forgot password*/
Route::post('/forgot-password','Front\AuthController@forgotPassword')->name('frontForgotPassword');
Route::get('/password/reset/{token?}','Front\AuthController@showResetPassword')->name('frontshowResetPassword');
Route::post('/reset-password','Front\AuthController@resetPassword')->name('frontResetPassword');
/*end forgot password*/


/* Product Management  */	
Route::group(['prefix'=>'manage-products'], function() {	    
Route::get('/','Front\ProductController@index')->name('manageFrontProducts');	    
Route::get('/saveproduct','Front\ProductController@productform')->name('frontProductCreate');	    
Route::post('/store','Front\ProductController@store')->name('frontProductStore');	    
Route::any('/getRecords','Front\ProductController@getRecords')->name('frontProductGetRecords');	    
Route::get('/saveproduct/{id}','Front\ProductController@productform')->name('frontProductEdit');	    	    
Route::get('/delete/{id}','Front\ProductController@delete')->name('frontProductDelete');	    
Route::post('/upload-variant-image','Front\ProductController@uploadVariantImage')->name('uploadVariantImage');
});	/*end Product Management  */

/* product Attributes */
Route::group(['prefix'=>'product-attributes'], function() {
	Route::get('/','Front\ProductAttributesController@index')->name('frontProductAttributes');
    Route::get('/create','Front\ProductAttributesController@create')->name('frontAttributeCreate');
    Route::post('/store','Front\ProductAttributesController@store')->name('frontAttributeStore');
    Route::any('/getRecords','Front\ProductAttributesController@getRecords')->name('frontAttributeGetRecords');
    Route::get('/edit/{id}','Front\ProductAttributesController@edit')->name('frontAttributeEdit');
    Route::post('/updateAttribute/{id}','Front\ProductAttributesController@update')->name('frontAttributeUpdate');
    Route::get('/delete/{id}','Front\ProductAttributesController@delete')->name('frontAttributeDelete');
    Route::post('/deleteAttributeValue','Front\ProductAttributesController@deleteAttributeValue')->name('frontdeleteAttributeValue');
    Route::get('/getattributevaluebyattributeid','Front\ProductAttributesController@getattributevaluebyattributeid')->name('getattributevaluebyattributeid');
});

/* Product Management  */	
Route::group(['prefix'=>'manage-services'], function() {	    
Route::get('/','Front\ServiceController@index')->name('manageFrontServices');	    
Route::get('/saveservice','Front\ServiceController@serviceform')->name('frontServiceCreate');	    
Route::post('/store','Front\ServiceController@store')->name('frontServiceStore');	    
Route::any('/getRecords','Front\ServiceController@getRecords')->name('frontServiceGetRecords');	    
Route::get('/saveservice/{id}','Front\ServiceController@serviceform')->name('frontServiceEdit');	    	    
Route::get('/delete/{id}','Front\ServiceController@delete')->name('frontServiceDelete');	    
});	/*end Product Management  */

Route::get('/clear-cache', function() {
   $exitCode = Artisan::call('cache:clear');
   // return what you want
});
Route::get('/clear-config', function() {
   $exitCode = Artisan::call('config:clear');
   // return what you want
});

Route::group(['middleware'=>['front-login']],function()
{ 
	Route::get('/seller-profile/{edit?}', 'Front\AuthController@sellerProfile')->name('frontSellerProfile');
	Route::post('/seller-profile-update', 'Front\AuthController@sellerProfileUpdate')->name('frontSellerProfileUpdate');
	Route::get('/delete-image/{id}','Front\AuthController@deleteImage')->name('SellerImageDelete');
	Route::get('/buyer-profile/{edit?}', 'Front\AuthController@buyerProfile')->name('frontBuyerProfile');
	Route::post('/buyer-profile-update', 'Front\AuthController@buyerProfileUpdate')->name('frontBuyerProfileUpdate');
	Route::get('/seller-packages', 'Front\AuthController@sellerPackages')->name('frontSellerPackages');
	Route::get('/profile', 'Front\AuthController@userProfile')->name('frontUserProfile');
   Route::post('/subscribe-package', 'Front\AuthController@subscribePackage')->name('frontSubscribePackage');
	//Route::get('/subscribe-package/{user_id}/{p_id}/{v_days}', 'Front\AuthController@subscribePackage')->name('frontSubscribePackage');
  // change password
  Route::get('/change-password', 'Front\AuthController@changePassword')->name('frontChangePassword');
  Route::post('/change-password-store', 'Front\AuthController@changePasswordStore')->name('frontChangePasswordStore');
});
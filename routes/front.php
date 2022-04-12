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
use Illuminate\Support\Facades\Session;

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/', 'Front\FrontController@index')->name('frontHome');

//products

Route::any('/seller/{seller_name}/{category_slug?}/{subcategory_slug?}','Front\FrontController@sellerProductListing')->name('sellerProductListingByCategory');

//Route::get('/seller/{seller_name}/products/{category_slug?}/{subcategory_slug?}','Front\FrontController@sellerProductListing')->name('sellerProductListingByCategory');
Route::any('/get_product_listing/','Front\FrontController@getProductsByParameter')->name('getProductsyParameter'); 
Route::any('/products/','Front\FrontController@productListing')->name('AllproductListing');
Route::get('/products/{category_slug}','Front\FrontController@productListing')->name('productListingByCategory');
Route::get('/products/{category_slug}/{subcategory_slug}','Front\FrontController@productListing')->name('productListingBySubcategory');

Route::any('/annonser/','Front\FrontController@buyerProductListing')->name('AllbuyerProductListing');
Route::get('/annonser/{category_slug}','Front\FrontController@buyerProductListing')->name('buyerProductListingByCategory');
Route::get('/annonser/{category_slug}/{subcategory_slug}','Front\FrontController@buyerProductListing')->name('buyerProductListingBySubcategory');


Route::any('/product/{product_slug}','Front\FrontController@productDetails')->name('productDetails');
Route::get('/product/{category_slug}/{product_slug}','Front\FrontController@productDetails')->name('productDetailsWithCategory');
Route::get('/product/{category_slug}/{subcategory_slug}/{product_slug}','Front\FrontController@productDetails')->name('productDetailsWithCategorySubcategory');
Route::any('/get_product_attribute_details','Front\FrontController@getProductAttributeDetails')->name('getProductAttributeDetails');

Route::post('/getCity', 'Front\FrontController@getCity')->name('getCity');
Route::post('/contact-store', 'Front\FrontController@contactStore')->name('FrontContactStore');
Route::any('/getCatSubList','Front\FrontController@getCatSubList')->name('getCatSubList');
Route::any('/getServiceCatSubcatList','Front\FrontController@getServiceCatSubcatList')->name('getServiceCatSubcatList');
Route::post('/report-product', 'Front\FrontController@reportProduct')->name('FrontReportProduct');
Route::post('/report-service', 'Front\FrontController@reportService')->name('FrontReportService');

//services
//Route::get('/seller/{seller_name}','Front\FrontController@sellerServiceListing')->name('sellerServiceListingByCategory');
//Route::get('/seller/{seller_name}/services/{category_slug?}/{subcategory_slug?}','Front\FrontController@sellerServiceListing')->name('sellerServiceListingByCategory');
Route::any('/get_service_listing/','Front\FrontController@getServicesByParameter')->name('getServicesyParameter'); 
Route::any('/services/','Front\FrontController@serviceListing')->name('AllserviceListing');
Route::get('/services/{category_slug}','Front\FrontController@serviceListing')->name('serviceListingByCategory');
Route::get('/services/{category_slug}/{subcategory_slug}','Front\FrontController@serviceListing')->name('serviceListingBySubcategory');
Route::any('/service/{service_slug}','Front\FrontController@serviceDetails')->name('serviceDetails');
Route::get('/service/{category_slug}/{service_slug}','Front\FrontController@serviceDetails')->name('serviceDetailsWithCategory');
Route::get('/service/{category_slug}/{subcategory_slug}/{service_slug}','Front\FrontController@serviceDetails')->name('serviceDetailsWithCategorySubcategory');
Route::post('/send-service-request','Front\FrontController@sendServiceRequest')->name('sendServiceRequest');

//auth
Route::any('/front-login/buyer','Front\AuthController@login')->name('frontLogin');
Route::any('/front-login/seller','Front\AuthController@login')->name('frontLoginSeller');
Route::post('/validate-login','Front\AuthController@doLogin')->name('doLogin');
Route::get('/front-logout','Front\AuthController@logout')->name('frontLogout');
Route::get('/buyer-register','Front\AuthController@buyer_register')->name('buyer_register');
Route::get('/seller-register','Front\AuthController@seller_register')->name('seller_register');
Route::post('/do-register','Front\AuthController@doRegister')->name('do-register');
Route::get('/register-success','Front\AuthController@register_success')->name('frontRegisterSuccess');

Route::any('/users-subscription','Front\AuthController@usersSubscription')->name('frontusersSubscription');
Route::get('/user/verify/{token}', 'Front\AuthController@verifyUser');

/*forgot password*/
Route::post('/forgot-password','Front\AuthController@forgotPassword')->name('frontForgotPassword');
Route::get('/password/reset/{token?}','Front\AuthController@showResetPassword')->name('frontshowResetPassword');
Route::post('/reset-password','Front\AuthController@resetPassword')->name('frontResetPassword');
/*end forgot password*/


/*seller registration*/
Route::any('/new-seller-register','Front\AuthController@newsellerRegister')->name('frontNewSellerRegister');
Route::any('/klarna-payment', 'Front\AuthController@klarnaPayment')->name('frontklarnaPayment');
Route::any('/seller-register-second-step', 'Front\AuthController@sellerRegisterSecondStep')->name('frontsellerRegisterSecondStep');

Route::post('/subscribe-package', 'Front\AuthController@subscribePackage')->name('frontSubscribePackage');

Route::any('/package_callback', 'Front\AuthController@packageCallback')->name('frontPackageCallback');
Route::get('/seller-packages', 'Front\AuthController@sellerPackages')->name('frontSellerPackages');
Route::any('/select-package', 'Front\AuthController@selectPackage')->name('frontSelectPackage');

Route::any('/third-step-seller-register','Front\AuthController@thirdStepsellerRegister')->name('frontThirdStepSellerRegister');
Route::post('/upload-seller-banner-image','Front\AuthController@uploadSellerBannerImage')->name('uploadSellerBannerImage');
Route::post('/upload-seller-logo-image','Front\AuthController@uploadSellerLogoImage')->name('uploadSellerLogoImage');
Route::any('/seller-info-page', 'Front\AuthController@seller_info_page')->name('frontSellerInfoPage');
/*end seller registration*/
/*CMS Pages*/
Route::get('/page/{page_slug}','Front\FrontController@cmsPage')->name('frontCmsPage');

 
/* Product Management  */
Route::group(['prefix'=>'manage-products'], function() {
Route::get('/','Front\ProductController@index')->name('manageFrontProducts');
Route::get('/saveproduct','Front\ProductController@productform')->name('frontProductCreate');
Route::post('/store','Front\ProductController@store')->name('frontProductStore');
Route::post('/buyer-store','Front\ProductController@buyerStore')->name('frontBuyerProductStore');
Route::any('/getRecords','Front\ProductController@getRecords')->name('frontProductGetRecords');
Route::get('/saveproduct/{id}','Front\ProductController@productform')->name('frontProductEdit');
Route::get('/delete/{id}','Front\ProductController@delete')->name('frontProductDelete');
Route::post('/upload-variant-image','Front\ProductController@uploadVariantImage')->name('uploadVariantImage');
Route::get('/check-slugname','Front\ProductController@checkUniqueSlugName')->name('frontProductCheckUniqueSlug');

//Route::any('/checkout','Front\ProductController@showCheckout')->name('frontProductShowCheckout');
Route::any('/checkout-swish/{type}','Front\ProductController@showCheckoutSwish')->name('frontProductShowCheckout');
Route::any('/checkout_complete/{id}', 'Front\ProductController@showCheckoutSuccess')->name('frontProductCheckoutSuccess');
 
Route::get('/buyer-products','Front\ProductController@listBuyerProduct')->name('manageBuyerProducts');


Route::any('/checkout-swish-number/{type}','Front\ProductController@showCheckoutSwishNumber')->name('frontProductShowCheckoutNumber');

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
Route::post('/upload-image','Front\ServiceController@uploadServiceImage')->name('uploadServiceImage');

Route::get('/check-slugname','Front\ServiceController@checkUniqueSlugName')->name('frontServiceCheckUniqueSlug');
});	/*end Product Management  */


/* Cart Routes */

/* end Cart Routes */


Route::get('/clear-cache', function() {
   $exitCode = Artisan::call('cache:clear');
   // return what you want
});
Route::get('/clear-config', function() {
   $exitCode = Artisan::call('config:clear');
   // return what you want
});

Route::any('/push_notification', 'Front\AuthController@pushNotification')->name('frontPushNotification');
Route::any('/checkout_push_notification', 'Front\CartController@pushNotification')->name('frontCheckoutPushNotification');
Route::any('/product_push_notification', 'Front\ProductController@pushNotification')->name('frontProductPushNotification');


Route::group(['middleware'=>['front-login']],function()
{
	Route::get('/seller-profile/{edit?}', 'Front\AuthController@sellerProfile')->name('frontSellerProfile');
   Route::any('/seller-personal-page', 'Front\AuthController@seller_personal_page')->name('frontSellerPersonalPage');
	Route::post('/seller-profile-update', 'Front\AuthController@sellerProfileUpdate')->name('frontSellerProfileUpdate');
  Route::any('/delete-card-details', 'Front\AuthController@deleteCardDetails')->name('frontSellerDeleteCardDetails');
	Route::get('/delete-image/{id}','Front\AuthController@deleteImage')->name('SellerImageDelete');
	Route::get('/buyer-profile/{edit?}', 'Front\AuthController@buyerProfile')->name('frontBuyerProfile');
	Route::post('/buyer-profile-update', 'Front\AuthController@buyerProfileUpdate')->name('frontBuyerProfileUpdate');
  Route::any('/remove-buyer-profile','Front\AuthController@removeBuyerProfile')->name('removeBannerImage');
	/*Route::get('/seller-packages', 'Front\AuthController@sellerPackages')->name('frontSellerPackages');*/
	Route::get('/profile', 'Front\AuthController@userProfile')->name('frontUserProfile');
 
 // Route::any('/push_notification', 'Front\AuthController@pushNotification')->name('frontPushNotification');
	//Route::get('/subscribe-package/{user_id}/{p_id}/{v_days}', 'Front\AuthController@subscribePackage')->name('frontSubscribePackage');
  // change password
  Route::get('/change-password', 'Front\AuthController@changePassword')->name('frontChangePassword');
  Route::post('/change-password-store', 'Front\AuthController@changePasswordStore')->name('frontChangePasswordStore');

  Route::any('/seller-shop-close/{id}', 'Front\AuthController@SellerShopClose')->name('frontShopClose');

  Route::any('/seller-shop-close-cron', 'Front\AuthController@ShopCloseCron')->name('frontShopCloseCron');
});
 Route::any('/upload-profile-image','Front\AuthController@uploadProfileImage')->name('uploadProfileImage');

Route::any('/remove-banner-image','Front\AuthController@removeBannerImage')->name('removeBannerImage');
Route::any('/remove-logo-image','Front\AuthController@removeLogoImage')->name('removeLogoImage');
Route::post('/add-to-cart','Front\CartController@addToCart')->name('frontAddToCart');
Route::get('/show-cart','Front\CartController@showCart')->name('frontShowCart');
Route::post('/remove-from-cart','Front\CartController@removeCartProduct')->name('frontRemoveCartProduct');
Route::post('/update-cart','Front\CartController@updateCartProduct')->name('frontUpdateCartProduct');

Route::post('/add-to-wishlist','Front\CartController@addToWishlist')->name('frontAddToWishlist');
Route::get('/wishlist','Front\CartController@showWishlist')->name('frontShowWishlist');
Route::post('/remove-from-wishlist','Front\CartController@removeWishlistProduct')->name('frontRemoveWishlistProduct');


Route::post('/get-product-options','Front\FrontController@getProductOptions')->name('frontProductOptions');
Route::post('/add-review','Front\FrontController@addReview')->name('frontAddReview');

Route::post('/add-service-review','Front\FrontController@addServiceReview')->name('frontAddServiceReview');
Route::post('/update-product-review', 'Front\FrontController@updateProductReview')->name('FrontUpdateProductReview');
Route::post('/delete-product-review','Front\FrontController@deleteProductReview')->name('frontDeleteProductReview');

Route::post('/update-service-review', 'Front\FrontController@updateServiceReview')->name('FrontUpdateServiceReview');
Route::post('/delete-service-review','Front\FrontController@deleteServiceReview')->name('frontDeleteServiceReview');

Route::any('/paymentoptions/{id}','Front\CartController@showPaymentOptions')->name('frontShowPaymentOptions');
Route::post('/update-shipping-charges','Front\CartController@updateShippingCharges')->name('frontupdateShippingCharges');
Route::any('/checkout/{id}/{paymentoption}','Front\CartController@showCheckout')->name('frontShowCheckout');
Route::any('/checkout_callback', 'Front\CartController@checkoutCallback')->name('frontCheckoutCallback');
Route::any('/checkout_complete/{id}', 'Front\CartController@showCheckoutSuccess')->name('frontCheckoutSuccess');
Route::any('/klarna_checkout_callback', 'Front\CartController@checkoutKlarnaCallback')->name('frontProductKlarnaCheckoutCallback');

Route::any('/all-orders', 'Front\CartController@showAllOrders')->name('frontAllOrders');
Route::any('/all-buyer-orders', 'Front\CartController@showBuyerOrders')->name('frontAllBuyerOrders');
Route::any('/order-details/{id}', 'Front\CartController@showOrderDetails')->name('frontShowOrderDetails');
Route::any('/download-order-details/{id}', 'Front\CartController@downloadOrderDetails')->name('frontDownloadOrderDetails');
Route::any('/getOrderRecords','Front\CartController@getRecords')->name('frontOrdersGetRecords');
Route::post('/change-order-status','Front\CartController@changeOrderStatus')->name('frontChangeOrderStatus');

Route::any('/all-service-request', 'Front\ServiceController@showAllServiceRequest')->name('frontAllServiceRequest');
Route::any('/booking-request', 'Front\ServiceController@bookingRequest')->name('frontAllbookingRequest');
Route::any('/getAllServiceRequest','Front\ServiceController@getAllServiceRequest')->name('frontServiceRequestGetRecords');
Route::get('/deleteServiceRequest/{id}','Front\ServiceController@deleteServiceRequest')->name('frontServiceRequestDel');
Route::any('/product-checkout/{id}','Front\CartController@showBuyerCheckout')->name('frontShowBuyerCheckout');

Route::any('/seller-dashboard', 'Front\AuthController@dashboard')->name('frontDashboard');

Route::get('/result/{id}/{type}', 'Front\ProductController@result')->name('result');
Route::match(['get', 'post'],'/swish-ipn-url', 'Front\CartController@swishIpnUrl')->name('swishIpnUrl');

Route::match(['get', 'post'],'/swish-ipn-callback', 'Front\ProductController@swishIpnCallback')->name('swishIpnCallback');
Route::post('/api/getPaymentMethods', 'Front\ProductController@getPaymentMethods');
Route::post('/api/initiatePayment', 'Front\ProductController@initiatePayment');
Route::post('/api/submitAdditionalDetails', 'Front\ProductController@submitAdditionalDetails');
Route::match(['get', 'post'], '/api/handleShopperRedirect', 'Front\ProductController@handleShopperRedirect');

Route::any('/seller-payment-details', 'Front\AuthController@showPaymentDetails')->name('frontSellerPaymentDetails');
Route::post('/seller-payment-update', 'Front\AuthController@sellerPaymentDetailsUpdate')->name('frontStorePaymentDetails');
  Route::any('/check-old-password', 'Front\AuthController@checkOldPassword')->name('frontcheckOldPassword');


Route::match(['get', 'post'], '/checkouthandleShopperRedirect', 'Front\CartController@checkouthandleShopperRedirect');
Route::match(['get', 'post'], '/checkout-swish-ipn', 'Front\CartController@checkoutSwishIpn');

Route::match(['get', 'post'],'/checkout-swish-callback', 'Front\CartController@CheckoutswishCallback')->name('CheckoutswishCallback');
Route::post('/getSwishPaymentMethods', 'Front\CartController@getSwishPaymentMethods');
Route::post('/swishInitiatePayment', 'Front\CartController@swishInitiatePayment');
Route::post('/checkoutSubmitAdditionalDetails', 'Front\CartController@checkoutSubmitAdditionalDetails');
Route::post('/checkout-strip-process', 'Front\CartController@checkoutStripProcess')->name('checkoutStripProcess');
Route::get('stripe-packages-subscriptions','Front\FrontController@stripePackageSubscription')->name('stripePackageSubscription');
Route::match(['get', 'post'],'/checkout-swish-number-callback', 'Front\CartController@CheckoutSwishNumberCallback')->name('CheckoutSwishNumberCallback');
Route::any('check-order-status/{order_id}','Front\CartController@CheckOrderStatus')->name('SwishCheckOrderStatus');
Route::any('update-order-status/{order_id}','Front\CartController@updateOrderStatus')->name('SwishUpdateOrderStatus');
Route::any('update-admin-order-status/{order_id}','Front\ProductController@updateOrderStatus')->name('SwishUpdateOrderStatus');
Route::any('order-success','Front\CartController@orderSuccess')->name('SwishOrderSuccess');
Route::any('payment-error','Front\CartController@paymentError')->name('SwishPaymentError');
Route::match(['get', 'post'],'/buyer-swish-number-callback', 'Front\ProductController@BuyerSwishNumberCallback')->name('BuyerSwishNumberCallback');
Route::any('buyer-check-order-status/{order_id}','Front\ProductController@BuyerCheckOrderStatus')->name('BuyerSwishCheckOrderStatus');
Route::any('order-success-swish','Front\ProductController@orderSuccess')->name('SwishNumberOrderSuccess');
Route::any('payment-error-swish','Front\ProductController@paymentError')->name('SwishNumberPaymentError');

Route::any('get-seller-list','Front\FrontController@sellerAutoSuggest')->name('FrontGetSellersList');
Route::any('swish-number-payment','Front\CartController@swishNumberPayment')->name('FrontSwishNumberPayment');

Route::any('swish-number-order','Front\CartController@swishNumberOrder')->name('SwishNumberOrderplaced');
/*Package*/
Route::group(['prefix'=>'package'], function() {
	Route::get('/package-history/{id}','Front\AuthController@sellerPackagesHistory')->name('frontPackage');
});

Route::any('/update-booking-notification', 'Front\ServiceController@ServiceRequestView')->name('frontServiceRequestView');
Route::any('/update-orders-notification', 'Front\CartController@ordersView')->name('frontOrdersView');
Route::any('/check-seller-setting', 'Front\AuthController@checkSellerSetting')->name('frontCheckSellerSetting');
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

Route::get("/invoice", function(){
	return View::make("Admin/Payments/orderpdftemp");
 });

Route::get('/admin', 'Admin\AuthController@index')->name('adminLogin');
Route::get('/admin/refreshcaptcha', 'Admin\AuthController@refreshCaptcha');
Route::post('/admin/login', 'Admin\AuthController@login')->name('adminDoLogin');
Route::get('/admin/ForgotPassword','Admin\AuthController@ForgotPassword')->name('ForgotPassword');
Route::post('/admin/ProcessForgotPassword','Admin\AuthController@ProcessForgotPassword')->name('ProcessForgotPassword');

Route::group(['prefix'=>'admin','middleware'=>['general','prevent-back-history'], 'namespace'=>'Admin'],function() {
	/*General*/
	Route::any('/logout', 'AuthController@logout')->name('adminLogout');
	Route::get('/dashboard', 'AuthController@dashboard')->name('adminDashboard');
	Route::get('/change-password', 'AuthController@changePassword')->name('adminChangePassword');
	Route::post('/change-password-store', 'AuthController@changePasswordStore')->name('adminChangePasswordStore');

	 /* Category  */
	Route::group(['prefix'=>'category'], function() {
	    Route::get('/','CategoryController@index')->name('adminCategory');
	    Route::get('/create','CategoryController@create')->name('adminCategoryCreate');
	    Route::post('/store','CategoryController@store')->name('adminCategoryStore');
	    Route::any('/getRecords','CategoryController@getRecords')->name('adminCategoryGetRecords');
	    Route::get('/edit/{id}','CategoryController@edit')->name('adminCategoryEdit');
	    Route::post('/updateCategory/{id}','CategoryController@update')->name('adminCategoryUpdate');
	    Route::get('/delete/{id}','CategoryController@delete')->name('adminCategoryDelete');
	    Route::get('/changeStatus/{id}/{status}','CategoryController@changeStatus')->name('adminCategoryChangeStatus');
	    Route::get('/check-slugname','CategoryController@checkUniqueSlugName')->name('adminCategoryCheckUniqueSlugName');
	   
	});
	
	  /*  SubCategory */
	Route::group(['prefix'=>'subcategory'], function() {
		Route::get('/check-slugname/','SubcategoryController@checkUniqueSlugName')->name('adminSubcategoryUniqueSlug');
		/*Route::get('/clean-slug/','SubcategoryController@php_cleanAccents')->name('adminSubcategoryCleanSlug');*/

		Route::get('/check-unique-subcat/','SubcategoryController@checkUniqueSubcat')->name('adminSubcategoryUniqueSubcat');
	    Route::get('/{id}','SubcategoryController@index')->name('adminSubcategory');
	    Route::any('/getRecords/{id}','SubcategoryController@getRecords')->name('adminSubcategoryGetRecords'); 
	    Route::get('/edit/{id}','CategoryController@edit')->name('adminCategoryEdit');
	    Route::post('/updateCategory','SubcategoryController@update')->name('adminSubcategoryUpdate');
	    Route::get('/delete/{id}','SubcategoryController@delete')->name('adminSubcategoryDelete');
	    Route::post('/subCategoryStore', 'SubcategoryController@subCategoryStore')->name('adminSubcategoryStore');
	    Route::get('/changeStatus/{id}/{status}','SubcategoryController@changeStatus')->name('adminSubcategoryChangeStatus');

	});
	

	/*Service Category  */
	Route::group(['prefix'=>'ServiceCategory'], function() {
		Route::get('/check-slugname/','ServiceCatController@checkUniqueSlugName')->name('adminServiceCatUniqueSlug');
	    Route::get('/','ServiceCatController@index')->name('adminServiceCat');
	    Route::get('/create','ServiceCatController@create')->name('adminServiceCatCreate');
	    Route::post('/store','ServiceCatController@store')->name('adminServiceCatStore');
	    Route::any('/getRecords','ServiceCatController@getRecords')->name('adminServiceCatGetRecords');
	    Route::get('/edit/{id}','ServiceCatController@edit')->name('adminServiceCatEdit');
	    Route::post('/updateCategory/{id}','ServiceCatController@update')->name('adminServiceCatUpdate');
	    Route::get('/delete/{id}','ServiceCatController@delete')->name('adminServiceCatDelete');
	    Route::get('/changeStatus/{id}/{status}','ServiceCatController@changeStatus')->name('adminServiceCatChangeStatus');
	});
	
	  /*Service  SubCategory */
	Route::group(['prefix'=>'ServiceSubcategory'], function() {
		
		Route::get('/check-unique-subcat/','ServiceSubcatController@checkUniqueSubcat')->name('adminServiceSubcatUniqueSubcat');
		Route::get('/check-slugname/','ServiceSubcatController@checkUniqueSlugName')->name('adminServiceSubcatUniqueSlug');
	    Route::get('/{id}','ServiceSubcatController@index')->name('adminServiceSubcat');
	    Route::any('/getRecords/{id}','ServiceSubcatController@getRecords')->name('adminServiceSubcatGetRecords');
 		Route::get('/edit/{id}','ServiceSubcatController@edit')->name('adminServiceSubCatEdit');
	    Route::post('/update/{id}','ServiceSubcatController@update')->name('adminServiceSubcatUpdate');
	    Route::get('/delete/{id}','ServiceSubcatController@delete')->name('adminServiceSubcatDelete');
	    Route::post('/subCategoryStore', 'ServiceSubcatController@subCategoryStore')->name('adminServiceSubCatStore');
	    Route::get('/changeStatus/{id}/{status}','ServiceSubcatController@changeStatus')->name('adminServiceSubcatChangeStatus');
	});

	/* Slider Management  */
	Route::group(['prefix'=>'slider'], function() {
	    Route::get('/','SliderController@index')->name('adminSlider');
	    Route::get('/create','SliderController@create')->name('adminSliderCreate');
	    Route::post('/store','SliderController@store')->name('adminSliderStore');
	    Route::any('/getRecords','SliderController@getRecords')->name('adminSliderGetRecords');
	    Route::get('/edit/{id}','SliderController@edit')->name('adminSliderEdit');
	    Route::post('/updateCategory/{id}','SliderController@update')->name('adminSliderUpdate');
	    Route::get('/delete/{id}','SliderController@delete')->name('adminSliderDelete');
	    Route::get('/changeStatus/{id}/{status}','SliderController@changeStatus')->name('adminSliderChangeStatus');
	   
	});

	/*end Slider Management  */			

	/* Product Management  */	
	Route::group(['prefix'=>'product'], function() {	
	    Route::get('/','ProductController@index')->name('adminProduct');	   
	    Route::get('/create','ProductController@create')->name('adminProductCreate');	    
	    Route::post('/store','ProductController@store')->name('adminProductStore');	    
	    Route::any('/getRecords','ProductController@getRecords')->name('adminProductGetRecords');	   
	    Route::get('/edit/{id}','ProductController@edit')->name('adminProductEdit');	    	    
	    Route::get('/delete/{id}','ProductController@delete')->name('adminProductDelete');	    
	    Route::get('/changeStatus/{id}/{status}','ProductController@changeStatus')->name('adminProductChangeStatus');	   	
	});	/*end Product Management  */

	/* product Attributes */
	Route::group(['prefix'=>'product_attributes'], function() {
	    Route::get('/','ProductAttributesController@index')->name('adminProductAttributes');
	    Route::get('/create','ProductAttributesController@create')->name('adminAttributeCreate');
	    Route::post('/store','ProductAttributesController@store')->name('adminAttributeStore');
	    Route::any('/getRecords','ProductAttributesController@getRecords')->name('adminAttributeGetRecords');
	    Route::get('/edit/{id}','ProductAttributesController@edit')->name('adminAttributeEdit');
	    Route::post('/updateAttribute/{id}','ProductAttributesController@update')->name('adminAttributeUpdate');
	    Route::get('/delete/{id}','ProductAttributesController@delete')->name('adminAttributeDelete');
	    Route::post('/deleteAttributeValue','ProductAttributesController@deleteAttributeValue')->name('admindeleteAttributeValue');
	});
	
	/* Banner */
	    Route::group(['prefix'=>'banner'], function() {
		Route::get('/','BannerController@index')->name('adminBanner');
		Route::any('/getRecords','BannerController@getRecords')->name('adminBannerGetRecords');
		Route::get('/edit/{id}','BannerController@edit')->name('adminBannerEdit');
		Route::post('/update/{id}','BannerController@update')->name('adminBannerUpdate');
		Route::get('/addnew/','BannerController@addnew')->name('adminBannerCreate');
		Route::any('/addnewBanner','BannerController@addNewBanner')->name('adminBannerAddnew');
		Route::get('/delete/{id}','BannerController@delete')->name('adminBannerDelete');
		Route::get('/changeStatus/{id}/{status}','BannerController@changeStatus')->name('adminBannerChangeStatus');
	});

	/* Buyers */
	Route::group(['prefix'=>'buyers'], function() {
		Route::get('/','BuyerController@index')->name('adminBuyers');
		Route::any('/getRecords','BuyerController@getRecords')->name('adminBuyersGetRecords');
		Route::get('/create','BuyerController@create')->name('adminBuyersCreate');
		Route::post('/storeupdate','BuyerController@StoreUpdate')->name('adminBuyersStoreUpdate');
		Route::get('/edit/{id}','BuyerController@edit')->name('adminBuyersEdit');
		Route::post('/updateBuyer/{id}','BuyerController@update')->name('adminBuyersUpdate');
		Route::get('/changeStatus/{id}/{status}','BuyerController@changeStatus')->name('adminBuyersChangeStatus');
		Route::get('/delete/{id}','BuyerController@delete')->name('adminBuyersDelete');
		Route::get('/exportdata','BuyerController@exportdata')->name('adminBuyerexportdata');
		
	});

	/* City */
	Route::group(['prefix'=>'city'], function() {
		Route::get('/','CityController@index')->name('adminCity');
		Route::any('/getRecords','CityController@getRecords')->name('adminCityGetRecords');
		Route::get('/addnew/','CityController@addnew')->name('adminCityCreate');
	    Route::post('/store','CityController@StoreCity')->name('adminCityStore');
	    Route::get('/delete/{id}','CityController@delete')->name('adminCityDelete');
	    Route::get('/changeStatus/{id}/{status}','CityController@changeStatus')->name('adminCityChangeStatus');
	    Route::get('/edit/{id}','CityController@edit')->name('adminCityEdit');
	    Route::post('/update/{id}','CityController@update')->name('adminCityUpdate');		
	});


	/* Seller */
	Route::group(['prefix'=>'seller'], function() {
		Route::get('/','SellerController@index')->name('adminSeller');
		Route::any('/getRecords','SellerController@getRecords')->name('adminSellerGetRecords');
		Route::get('/create','SellerController@create')->name('adminSellerCreate');
		Route::post('/store','SellerController@store')->name('adminSellerStore');
		Route::get('/edit/{id}','SellerController@edit')->name('adminSellerEdit');
		Route::get('/delete-image/{id}','SellerController@deleteImage')->name('SellerImageDelete');
		Route::any('/update/{id}','SellerController@update')->name('adminSellerUpdate');
		Route::get('/changeStatus/{id}/{status}','SellerController@changeStatus')->name('adminSellerChangeStatus');
		Route::get('/delete/{id}','SellerController@delete')->name('adminSellerDelete');
	    Route::get('/exportdata','SellerController@exportdata')->name('adminSellerexportdata');
	    Route::get('/checkstore','SellerController@checkstore')->name('adminSellerCheckStore');
		Route::get('/showpackages/{id}','SellerController@showpackages')->name('adminSellerShowPackages');
	});

	/*Package*/
	Route::group(['prefix'=>'package'], function() {
		Route::get('/','PackageController@index')->name('adminPackage');
		Route::any('/getRecords','PackageController@getRecords')->name('adminPackageGetRecords');
		Route::get('/create','PackageController@create')->name('adminPackageCreate');
		Route::post('/store','PackageController@store')->name('adminPackageStore');
		Route::get('/edit/{id}','PackageController@edit')->name('adminPackageEdit');
		Route::any('/update/{id}','PackageController@update')->name('adminPackageUpdate');
		Route::get('/delete/{id}','PackageController@delete')->name('adminPackageDelete');
	    Route::get('/exportdata','PackageController@exportdata')->name('adminPackageexportdata');
		Route::get('/changeStatus/{id}/{status}','PackageController@changeStatus')->name('adminPackageChangeStatus');
	});

	/*Pages*/
	Route::group(['prefix'=>'pages'], function() {
		Route::get('/','PageController@index')->name('adminPage');
		Route::any('/getRecords','PageController@getRecords')->name('adminPageGetRecords');
		Route::get('/create','PageController@create')->name('adminPageCreate');
		Route::post('/store','PageController@store')->name('adminPageStore');
		Route::get('/edit/{id}','PageController@edit')->name('adminPageEdit');
		Route::any('/update/{id}','PageController@update')->name('adminPageUpdate');
		Route::get('/delete/{id}','PageController@delete')->name('adminPageDelete');
	    Route::get('/changeStatus/{id}/{status}','PageController@changeStatus')->name('adminPageChangeStatus');
	});

	/*Orders*/
	Route::group(['prefix'=>'orders'], function() {
		Route::get('/','OrderController@index')->name('adminOrder');
		Route::any('/getRecords','OrderController@getRecords')->name('adminOrderGetRecords');
		Route::get('/view/{id}','OrderController@view')->name('adminOrderView');
	});

	/*Emails*/
	Route::group(['prefix'=>'emails'], function() {
		Route::get('/','EmailController@index')->name('adminEmail');
		Route::any('/getRecords','EmailController@getRecords')->name('adminEmailGetRecords');
		Route::get('/create','EmailController@create')->name('adminEmailCreate');
		Route::post('/store','EmailController@store')->name('adminEmailStore');
		Route::get('/edit/{id}','EmailController@edit')->name('adminEmailEdit');
		Route::any('/update/{id}','EmailController@update')->name('adminEmailUpdate');
		Route::get('/delete/{id}','EmailController@delete')->name('adminEmailDelete');
	    Route::get('/changeStatus/{id}/{status}','EmailController@changeStatus')->name('adminEmailChangeStatus');
	});


	/*setting*/
	Route::group(['prefix'=>'setting'], function() {
		Route::get('/create','SettingController@create')->name('adminSettingCreate');
		Route::post('/store','SettingController@store')->name('adminSettingStore');
		
	});
});
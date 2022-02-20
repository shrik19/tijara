<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
use App\Models\Banner;
use App\Models\Categories;
use App\Models\Subcategories;

use App\Models\Services;
use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;

use App\Models\Products;
use App\Models\Settings;
use App\Models\Page;
use App\Models\Attributes;
use App\Models\AttributesValues;
use App\Models\VariantProduct;
use App\Models\VariantProductAttribute;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\SellerPersonalPage;
use App\Models\ServiceAvailability;

use App\Models\UserMain;
use App\Models\ProductReview;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\ServiceRequest;
use App\Models\ServiceReview;
use App\Models\ContactStore;
use App\Models\ReportProduct;
use App\Models\ReportService;
use App\Models\BuyerProducts;
use App\Models\AnnonserCategories;
use App\Models\AnnonserSubcategories;
use App\Models\UserPackages;

use DB;
use Auth;
use Validator;
use Session;
use Flash;
use Mail;
use Stripe;

class FrontController extends Controller
{

    /* function to display home page*/
    public function index() {
		
        /*get slider images*/
        $SliderDetails 		=  Sliders::select('id','title','sliderImage','description','link','status')->where('status','=','active')->orderBy('sequence_no')->get();
        $banner 			=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Home')->first();

       // $sliders = Sliders::with(['getImages'])->limit(9)->orderBy('id', 'DESC')->get()->toArray();
	    $site_details		= Settings::first();
 
        $data['pageTitle'] 		= 'Home';
        //$data['siteDetails'] 	= $site_details;
		$data['SliderDetails']  = $SliderDetails;
        $data['banner'] 	   	= $banner;
		$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';

		$data['Categories']    	= $this->getCategorySubcategoryList()	;
		$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList()	;
		$data['TrendingProducts']= $this->getTrendingProducts('','',2);
		$data['PopularProducts']= $this->getPopularProducts(config('constants.Popular_Product_limits'));
		$data['FeaturedSellers']= $this->getFeaturedSellers();
		$data['PopularServices']= $this->getPopularServices();
		$data['FeaturedProducts']= $this->getFeaturedProducts('','',1); 
        return view('Front/home', $data);
    }

    /*Function to get treding seller*/
    function getFeaturedSellers(){
    	$today          = date('Y-m-d H:i:s');
    	$featuredSellers 	= UserMain::join('user_packages', 'users.id', '=', 'user_packages.user_id')
    							->join('seller_personal_page', 'users.id', '=', 'seller_personal_page.user_id')
								->select('users.id','users.fname','users.lname','users.email','user_packages.package_id','users.store_name','users.description','seller_personal_page.logo')
								->where('users.role_id','=','2')
								->where('users.is_featured','=','1')
								->where('users.is_verified','=','1')
								->where('users.status','=','active')
								->where('users.is_deleted','=','0')
                			    ->where('users.is_shop_closed','=','0')
								->where('user_packages.status','=','active')
								->where('user_packages.start_date','<=', $today)
								->where('user_packages.end_date','>=', $today)
								->orderBy('users.id', 'DESC')
								//->limit(4)
								->get();
			
		return $featuredSellers;			
    }

     /*Function to get treding seller*/
    function getFeaturedProductsOld($category_slug='',$subcategory_slug='',$role_id=''){
    	//DB::enableQueryLog();
		// and then you can get query log
	
    	$currentDate = date('Y-m-d H:i:s'); 
		$featuredproducts 	= UserMain::join('user_packages', 'users.id', '=', 'user_packages.user_id')
    							->join('products', 'users.id', '=', 'products.user_id')
    							->join('variant_product', 'products.id', '=', 'variant_product.product_id')
								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
								->join('category_products', 'products.id', '=', 'category_products.product_id')
								->join('categories', 'categories.id', '=', 'category_products.category_id')
								->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('products.*','users.id as user_id','users.fname','users.lname','users.email','user_packages.package_id','users.store_name','users.description','variant_product.image','variant_product.price','variant_product.id as variant_id','categories.category_name')
								->where('users.role_id','=','2')
								->where('users.is_deleted','=','0')
								->where('users.is_featured','=','1')
								->where('users.is_verified','=','1')
								->where('users.status','=','active')
								->where('user_packages.status','=','active')
								->where('user_packages.start_date','<=', $currentDate)
								->where('user_packages.end_date','>=', $currentDate)
								->orderBy('users.id', 'DESC')
								->groupBy('products.id')
								->limit(5);
								if($role_id!='')
								$featuredproducts->where('users.role_id','=',$role_id);
								$featuredproducts	=	$featuredproducts->get();

		if(count($featuredproducts)>0) {
			foreach($featuredproducts as $Product) {
        $productCategories = $this->getProductCategories($Product->id);

				$product_link	=	url('/').'/product';
		if($category_slug!='')
        {
				      $product_link	.=	'/'.$category_slug;
        }
        else {
          $product_link	.=	'/'.$productCategories[0]['category_slug'];
        }
				if($subcategory_slug!='')
        {
				      $product_link	.=	'/'.$subcategory_slug;
        }
        else {
          $product_link	.=	'/'.$productCategories[0]['subcategory_slug'];
        }

				$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;

       // $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
       // $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

				$Product->product_link	=	$product_link;

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
					if(!empty($Product->discount))
					{
						$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
						$Product->discount_price = $Product->price - $discount;
					}
					else
					{
						$Product->discount_price = 0;
					}
				}
			}
		}
		// echo "<pre>";
		// print_r($featuredproducts);exit;
		return $featuredproducts;			
    }

	function getFeaturedProducts($category_slug='',$subcategory_slug='',$role_id=''){
    	//DB::enableQueryLog();
		// and then you can get query log
	
    	$currentDate = date('Y-m-d H:i:s'); 
		$featuredproducts 	= UserMain::join('products', 'users.id', '=', 'products.user_id')
    							->join('variant_product', 'products.id', '=', 'variant_product.product_id')
								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
								->join('category_products', 'products.id', '=', 'category_products.product_id')
								->join('annonsercategories', 'annonsercategories.id', '=', 'category_products.category_id')
								->join('annonserSubcategories', 'annonsercategories.id', '=', 'annonserSubcategories.category_id')
								->select('products.*','users.id as user_id','users.fname',
								'users.lname','users.email','users.store_name','users.description','variant_product.image','variant_product.price','variant_product.id as variant_id','annonsercategories.category_name')
								
								->where('users.is_deleted','=','0')	
								->where('products.is_deleted','=','0')									
                			    ->where('users.is_shop_closed','=','0')
								//->where('users.is_featured','=','1')
								->where('products.is_buyer_product','=','1')
								->where('users.status','=','active')
								
								->orderBy('users.id', 'DESC')
								->groupBy('products.id')
								->limit(5);
								if($role_id!='')
								$featuredproducts->where('users.role_id','=',$role_id);
								$featuredproducts	=	$featuredproducts->get();

		if(count($featuredproducts)>0) {
			foreach($featuredproducts as $Product) {
        $productCategories = $this->getProductCategories($Product->id,$annonser=1);

				$product_link	=	url('/').'/product';
		if($category_slug!='')
        {
				      $product_link	.=	'/'.$category_slug;
        }
        else {
          $product_link	.=	'/'.@$productCategories[0]['category_slug'];
        }
				if($subcategory_slug!='')
        {
				      $product_link	.=	'/'.$subcategory_slug;
        }
        else {
          $product_link	.=	'/'.@$productCategories[0]['subcategory_slug'];
        }

				$product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code.'?annonser=1';
				//$product_link	.=	$Product->product_slug.'?annonser=1';
       // $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
       // $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

				$Product->product_link	=	$product_link;

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
					if(!empty($Product->discount))
					{
						$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
						$Product->discount_price = $Product->price - $discount;
					}
					else
					{
						$Product->discount_price = 0;
					}
				}
			}
		}
		// echo "<pre>";
		// print_r($featuredproducts);exit;
		return $featuredproducts;			
    }
	//get category & subcategory listings
	public function getCategorySubcategoryList($seller_id='',$category_slug='', $subcategory_slug='') {
		//DB::enableQueryLog();
		$Categories 		= Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('categories.id','categories.category_name','categories.category_slug','subcategories.subcategory_name','subcategories.subcategory_slug')
								->where('subcategories.status','=','active')
								->where('categories.status','=','active')
								->orderBy('categories.sequence_no')
								->orderBy('subcategories.sequence_no')
								//->groupBy('categories.id')
								->get()
								->toArray();
		//print_r(DB::getQueryLog());exit;
		$CategoriesArray	=	array();
		$currentDate = date('Y-m-d H:i:s');
	
		foreach($Categories as $category) {
					
			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

			//echo "<pre>";print_r($CategoriesArray);exit;
		return $CategoriesArray;
	}

	public function getAnnonserCategorySubcategoryList($seller_id='',$category_slug='', $subcategory_slug='') {
		//DB::enableQueryLog();
		$Categories 		= Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('categories.id','categories.category_name','categories.category_slug','subcategories.subcategory_name','subcategories.subcategory_slug')
								->where('subcategories.status','=','active')
								->where('categories.status','=','active')
								->orderBy('categories.sequence_no')
								->orderBy('subcategories.sequence_no')
								//->groupBy('categories.id')
								->get()
								->toArray();
		$Categories 		= AnnonserCategories::join('annonserSubcategories', 'annonsercategories.id', '=', 'annonserSubcategories.category_id')
								->select('annonsercategories.id','annonsercategories.category_name','annonsercategories.category_slug','annonserSubcategories.subcategory_name','annonserSubcategories.subcategory_slug')
								->where('annonserSubcategories.status','=','active')
								->where('annonsercategories.status','=','active')
								->orderBy('annonsercategories.sequence_no')
								->orderBy('annonserSubcategories.sequence_no')
								//->groupBy('categories.id')
								->get()
								->toArray();
		//print_r(DB::getQueryLog());exit;
		$CategoriesArray	=	array();
		$currentDate = date('Y-m-d H:i:s');
	
		foreach($Categories as $category) {
					
			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

			
		return $CategoriesArray;
	}

public function getCatSubList(Request $request) {
		
		$Categories 		= Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('categories.id','categories.category_name','categories.category_slug','subcategories.subcategory_name','subcategories.subcategory_slug')
								->where('subcategories.status','=','active')
								->where('categories.status','=','active')
								->orderBy('categories.sequence_no')
								->orderBy('subcategories.sequence_no')
								->groupBy('categories.id')
								->get()
								->toArray();

		$CategoriesArray	=	array();
		$currentDate = date('Y-m-d H:i:s');
	
		foreach($Categories as $category) {
			$CategoriesArray[$category['id']]['product_count']=0;
				
			$productCount 			= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
			  ->join('categories', 'categories.id', '=', 'category_products.category_id')
			  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
			  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
			  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
			  ->join('users', 'products.user_id', '=', 'users.id')
			  ->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')
			  ->select(['products.id','products.title','categories.category_name'])
			  ->where('products.status','=','active')
			  ->where('products.is_deleted','=','0')
			  ->where('categories.status','=','active')
			  ->where('subcategories.status','=','active')
			  ->where('users.status','=','active')
			  ->where('variant_product.quantity','>',0)
			  ->where('users.is_deleted','=','0')
			  ->where('users.is_shop_closed','=','0')
			 
			  ->where('category_products.category_id','=',$category['id'])
			  ->where(function($q) use ($currentDate) {

				$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
				})
			  ->orderBy('categories.sequence_no')
			  ->orderBy('subcategories.sequence_no');
			 //->groupBy('categories.id');

			if(!empty($request->sellers)){
				$productCount	=	$productCount->where('products.user_id','=',$request->sellers);
			}
			if(!empty($request->search_seller_product)){
				$productCount	=	$productCount->where('products.title', 'like', '%' . $request->search_seller_product . '%');
			}

			

			$productCount = $productCount->groupBy('products.id')->get()->toArray();

			$CategoriesArray[$category['id']]['product_count'] =  count($productCount);
					
			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

		return $CategoriesArray;
	}

	//get category & subcategory listings
	function getServiceCategorySubcategoryList($seller_id='') {
	
		$Categories 		= ServiceCategories::join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->select('servicecategories.id','servicecategories.category_name','servicecategories.category_slug','serviceSubcategories.subcategory_name','serviceSubcategories.subcategory_slug')
								->where('serviceSubcategories.status','=','active')
								->where('servicecategories.status','=','active')
								->orderBy('servicecategories.sequence_no')
								->orderBy('serviceSubcategories.sequence_no')
								->get()
								->toArray();
		

		$CategoriesArray	=	array();
		$currentDate = date('Y-m-d H:i:s');
		foreach($Categories as $category) {
			$CategoriesArray[$category['id']]['service_count']=0;
			
			/*$servicesCount 			= Services::join('category_services', 'services.id', '=', 'category_services.service_id')
							  ->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
							  ->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')							  				  
							  ->join('users', 'services.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->select(['services.*','servicecategories.category_name'])
							  ->where('services.status','=','active')
							  ->where('services.is_deleted','=','0')
							   ->where('category_services.category_id','=',$category['id'])
							  ->where('servicecategories.status','=','active')
							  ->where('serviceSubcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
	
			if(!empty($seller_id)){
					
				$seller_id=base64_decode($seller_id);
				$servicesCount = $servicesCount ->where('services.user_id','=',$seller_id);
			}

			$servicesCount = $servicesCount->groupBy('services.id')->get()->toArray();
			$CategoriesArray[$category['id']]['service_count']=  count($servicesCount);*/

			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

		return $CategoriesArray;
	}

	//get category & subcategory listings
	function getServiceCatSubcatList(Request $request) {
	
		$Categories 		= ServiceCategories::join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->select('servicecategories.id','servicecategories.category_name','servicecategories.category_slug','serviceSubcategories.subcategory_name','serviceSubcategories.subcategory_slug')
								->where('serviceSubcategories.status','=','active')
								->where('servicecategories.status','=','active')
								->orderBy('servicecategories.sequence_no')
								->orderBy('serviceSubcategories.sequence_no')
								->get()
								->toArray();
		

		$CategoriesArray	=	array();
		$currentDate = date('Y-m-d H:i:s');
		foreach($Categories as $category) {
			$CategoriesArray[$category['id']]['service_count']=0;
			
			$servicesCount 			= Services::join('category_services', 'services.id', '=', 'category_services.service_id')
							  ->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
							  ->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')							  				  
							  ->join('users', 'services.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->select(['services.*','servicecategories.category_name'])
							  ->where('services.status','=','active')
							  ->where('services.is_deleted','=','0')
							   ->where('category_services.category_id','=',$category['id'])
							  ->where('servicecategories.status','=','active')
							  ->where('serviceSubcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where('users.is_deleted','=','0')  
							  ->where('users.is_shop_closed','=','0')
							  ->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
			->orderBy('servicecategories.sequence_no')
			->orderBy('serviceSubcategories.sequence_no');
			if(!empty($seller_id)){
					
				$seller_id=base64_decode($seller_id);
				$servicesCount = $servicesCount ->where('services.user_id','=',$seller_id);
			}

			if(!empty($request->sellers)){
				$servicesCount	=	$servicesCount->where('services.user_id','=',$request->sellers);
			}

			if(!empty($request->search_seller_product)){
				$servicesCount	=	$servicesCount->where('services.title', 'like', '%' . $request->search_seller_product . '%');
			}
			
			$servicesCount = $servicesCount->groupBy('services.id')->get()->toArray();
			$CategoriesArray[$category['id']]['service_count']=  count($servicesCount);

			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

		return $CategoriesArray;
	}

    //get seller listings
	function getSellersList($category_slug = '',$subcategory_slug = '', $price_filter = '',$city_filter = '',  $search_string = '',$productsServices='',$path='') {
	
    	$today          = date('Y-m-d H:i:s');
		$Sellers 		= UserMain::join('user_packages', 'users.id', '=', 'user_packages.user_id')
								->select('users.id','users.fname','users.lname','users.email','user_packages.package_id')
								->where('users.role_id','=','2')
								->where('users.status','=','active')
								->where('users.is_deleted','=','0')
								->where('users.is_shop_closed','=','0')
								->where(function($q) use ($today) {
									$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$today],['end_date','>=',$today]])
									->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$today],['trial_end_date','>=',$today]]);
								});
		
		if($city_filter != ''){
			$Sellers	=	$Sellers->where('users.city','like', '%' .$city_filter.'%');
		}
			
		$Sellers	=   $Sellers->orderBy('users.id')
						->get()
						->toArray();
//echo "<pre>";print_r($path);exit;//if(strpos(@$request->path, 'annonser') !== false){
		$SellersArray	=	array();
		if($productsServices=='products') {
			
			foreach($Sellers as $seller) {
				  $productCnt = 0;

				  $sellerProducts = Products::join('variant_product_attribute', 'products.id', '=', 'variant_product_attribute.product_id')
											->join('variant_product', 'products.id', '=', 'variant_product.product_id')
											->join('category_products', 'products.id', '=', 'category_products.product_id')
											->join('categories', 'categories.id', '=', 'category_products.category_id')
											->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
											->select('products.id')->where('products.user_id','=', $seller['id'])->where('products.status','=','active')
											->where('products.is_deleted','=','0')
											->where('categories.status','=','active')
											->where('subcategories.status','=','active');

				  if($category_slug !='')
				  {
			  		if(strpos(@$request->path, 'annonser') !== false){
			  			$category 		=  AnnonserCategories::select('id')->where('category_slug','=',$category_slug)->first();
			  			$sellerProducts	=	$sellerProducts->where('category_products.category_id','=',@$category['id']);
			  		}else{
			  			$category 		=  Categories::select('id')->where('category_slug','=',$category_slug)->first();
			  			$sellerProducts	=	$sellerProducts->where('category_products.category_id','=',@$category['id']);
			  		}
					
					
				  }
				  if($subcategory_slug !='')
				  {
				  	if(strpos(@$request->path, 'annonser') !== false){
				  		$subcategory 		=  AnnonserSubcategories::select('id')->where('subcategory_slug','=',$subcategory_slug)->first();
				  	}else{
				  		$subcategory 		=  Subcategories::select('id')->where('subcategory_slug','=',$subcategory_slug)->first();
				  	}
					
					$sellerProducts	=	$sellerProducts->where('category_products.subcategory_id','=',@$subcategory['id']);
				  }
				  if($price_filter !='')
				  {
					$tmpPrice 		  =  explode(',',$price_filter);
					$sellerProducts	=	$sellerProducts->whereBetween('variant_product.price',$tmpPrice);
				  }
				  if($search_string !='')
				  {
					$sellerProducts	=	$sellerProducts->where('products.title','like','%'.$search_string.'%');
				  }

				  $sellerProducts	= $sellerProducts->groupBy('variant_product_attribute.product_id')->get();

				  if(!empty($sellerProducts))
				  {
					$productCnt = count($sellerProducts);
				  }
				$SellersArray[$seller['id']]['product_cnt']= $productCnt;
				  $SellersArray[$seller['id']]['name']= $seller['fname'].' '.$seller['lname'];
				  $SellersArray[$seller['id']]['package']= $seller['package_id'];
			}
		}
		else {

			foreach($Sellers as $seller) { 
			//	DB::enableQueryLog();
				  $ServiceCnt = 0;

				  $sellerServices	=	array();

				  $sellerServices = Services::
											join('category_services', 'services.id', '=', 'category_services.Service_id')
											->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
											->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
											->select('services.id')->where('services.user_id','=', $seller['id'])->where('services.status','=','active')
											->where('services.is_deleted','=','0')
											->where('servicecategories.status','=','active')
											->where('serviceSubcategories.status','=','active');


				  if($category_slug !='')
				  {
					$category 		=  ServiceCategories::select('id')->where('category_slug','=',$category_slug)->first();
					$sellerServices	=	$sellerServices->where('category_services.category_id','=',@$category['id']);
				  }
				  if($subcategory_slug !='')
				  {
					$subcategory 		=  ServiceSubcategories::select('id')->where('subcategory_slug','=',$subcategory_slug)->first();
					$sellerServices	=	$sellerServices->where('category_services.subcategory_id','=',@$subcategory['id']);
				  }
				  
				  if($search_string !='')
				  {
					$sellerServices	=	$sellerServices->where('services.title','like','%'.$search_string.'%');
				  }

				  $sellerServices	=	$sellerServices->groupBy('services.id')->get();
				 // 	print_r(DB::getQueryLog());exit;
				  if(!empty($sellerServices))
				  {					  
					$ServiceCnt = count($sellerServices);
				  }
				$SellersArray[$seller['id']]['service_cnt']= $ServiceCnt;
				  $SellersArray[$seller['id']]['name']= $seller['fname'].' '.$seller['lname'];
				  $SellersArray[$seller['id']]['package']= $seller['package_id'];
			}
		}

		return $SellersArray;
	}


	//get popular products
	function getPopularProducts($limit,$category_slug='',$subcategory_slug='') {
	
		$currentDate = date('Y-m-d H:i:s');
		//DB::enableQueryLog();
		$PopularProducts 	= Products::join('orders_details', 'products.id', '=', 'orders_details.product_id')
								->join('variant_product', 'products.id', '=', 'variant_product.product_id')
								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
								->join('category_products', 'products.id', '=', 'category_products.product_id')
								->join('categories', 'categories.id', '=', 'category_products.category_id')
								->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->join('users', 'products.user_id', '=', 'users.id')
								->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')//DB::raw("DATEDIFF(products.created_at, '".$currentDate."') AS posted_days")
								->select(['products.*',DB::raw("DATEDIFF('".$currentDate."', products.sold_date) as sold_days"), DB::raw("DATEDIFF('".$currentDate."', products.created_at) as created_days") , DB::raw("count(orders_details.id) as totalOrderedProducts"),'variant_product.image','variant_product.price','variant_product.id as variant_id','categories.category_name','products.discount'])
								->where('products.status','=','active')
								->where('products.is_deleted','=','0')
								->where('categories.status','=','active')
							  	->where('subcategories.status','=','active')
								->where('users.status','=','active')
                			    ->where('users.is_shop_closed','=','0')
								->where('users.is_deleted','=','0')
								->where(function($q) use ($currentDate) {

									$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								})
								// ->when( "users.role_id" == 2 , function ($query) use ($currentDate) {
								// 	return $query->join('user_packages', 'user_packages.user_id', '=', 'users.id')->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
								// }, function ($query) use ($currentDate) {
								// 	return $query->where([['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								// })
								//->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
								->orderBy('totalOrderedProducts', 'DESC')
								->orderBy('variant_product.id', 'ASC')
								->groupBy('products.id')
								->offset(0)->limit($limit)->get();
		//print_r(DB::getQueryLog());	exit;
		/*$current_uri = request()->segments();
        
        if(!empty($current_uri[0]) && @$current_uri[0]=='products'){
        	$PopularProducts->offset(0)->limit(20)->get();
		}else{
			$PopularProducts->offset(0)->limit(config('constants.Popular_Product_limits'))->get();
		}*/

		if(count($PopularProducts)<10 && $limit ==10){
			$number =10-count($PopularProducts);
			//DB::enableQueryLog();
			$currentDate = date('Y-m-d H:i:s');
			$roleId = 2;
			$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id','users.role_id']) //
							  ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])
								->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])
								->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								})
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
                			  ->where('users.status','=','active')
                			  ->where('users.is_deleted','=','0')
                			  ->where('users.is_shop_closed','=','0')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  //->orderBy('variant_id', 'ASC')
							  ->groupBy('products.id')
							// ->offset(0)->limit(config('constants.Front_Products_limits'));
							    ->offset(0)->limit($number);
							  if($roleId!='')
								$TrendingProducts->where('users.role_id','=',$roleId);

								$TrendingProducts	=$TrendingProducts->get();
								//echo "<pre>";print_r(count($TrendingProducts));exit;
								$PopularProducts = $PopularProducts->merge($TrendingProducts);

		}
		if(count($PopularProducts)>0) {
			foreach($PopularProducts as $Product) {
        $productCategories = $this->getProductCategories($Product->id);

				$product_link	=	url('/').'/product';
				if($category_slug!='')
        {
				      $product_link	.=	'/'.$category_slug;
        }
        else {
          $product_link	.=	'/'.@$productCategories[0]['category_slug'];
        }
				if($subcategory_slug!='')
        {
				      $product_link	.=	'/'.$subcategory_slug;
        }
        else {
          $product_link	.=	'/'.@$productCategories[0]['subcategory_slug'];
        }

				$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;

        $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Product->user_id)->where('users.is_deleted','=','0')->first()->toArray();
        $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
        $Product->store_name	=	$SellerData['store_name'];

		$Product->product_link	=	$product_link;

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
					if(!empty($Product->discount))
					{
						$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
						$Product->discount_price = $Product->price - $discount;
					}
					else
					{
						$Product->discount_price = 0;
					}
				}
			}
		}

		
			return $PopularProducts;
		
		
	}
	// get trending products
	function getTrendingProducts($number,$category_slug='',$subcategory_slug='',$role_id='') {
		//DB::enableQueryLog();
		$currentDate = date('Y-m-d H:i:s');
		$roleId = 2;
		$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id','users.role_id']) //
							  ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])
								->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])
								->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								})
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
                			  ->where('users.status','=','active')
                			  ->where('users.is_deleted','=','0')
                			  ->where('users.is_shop_closed','=','0')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  //->orderBy('variant_id', 'ASC')
							  ->groupBy('products.id');
							  if($number==''){
							  	$TrendingProducts->offset(0)->limit(config('constants.Front_Products_limits'));
							  }else{
							  	 $TrendingProducts->offset(0)->limit($number);
							  }
							// 
							   
							  if($role_id!='')
								$TrendingProducts->where('users.role_id','=',$role_id);

								$TrendingProducts	=$TrendingProducts->get();
          //   print_r(DB::getQueryLog());exit;
		//dd($TrendingProducts);
                //dd(count($TrendingProducts));
		if(count($TrendingProducts)>0) 
		{
		foreach($TrendingProducts as &$Product) 
		{
        	$productCategories = $this->getProductCategories($Product->id);
        	//dd($productCategories);

			$product_link	=	url('/').'/product';

			if($category_slug!='')
			{
				$product_link	.=	'/'.$category_slug;
			}
			else {
				$product_link	.=	'/'.@$productCategories[0]['category_slug'];
			}
			
			if($subcategory_slug!='')
			{
				$product_link	.=	'/'.$subcategory_slug;
			}
			else 
			{
				$product_link	.=	'/'.@$productCategories[0]['subcategory_slug'];
			}

			$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;
			$Product->product_link	=	$product_link;

			$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Product->user_id)->first()->toArray();
			$Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
			$Product->store_name	=	$SellerData['store_name'];

			$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
			foreach($variantProduct as $vp)
			{
				$Product->image = explode(",",$vp->image)[0];
				$Product->price = $vp->price;
				$Product->variant_id = $vp->variant_id;
				if(!empty($Product->discount))
				{
					$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
					$Product->discount_price = $Product->price - $discount;
				}
				else
				{
					$Product->discount_price = 0;
				}
			}

		}

			

			//dd($Product);
		}

		//dd($TrendingProducts);
							  return $TrendingProducts;
	}

	//function to get products list by provided parameters
	public function getProductsByParameter(Request $request) {

		//DB::enableQueryLog();
		//print_r($request->path);exit;
		$currentDate = date('Y-m-d H:i:s');
		$data = [];
		
								

							//   ->when( "users.role_id" == 2 , function ($query) {
							// 		return $query->join('user_packages', 'user_packages.user_id', '=', 'users.id')->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
							//   }, function ($query) use ($currentDate) {
							// 	return $query->where([['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
							// });
			if(strpos(@$request->path, 'annonser') !== false){

				$Products 			= Products::leftjoin('category_products', 'products.id', '=', 'category_products.product_id')
							  ->leftJoin('annonsercategories', 'annonsercategories.id', '=', 'category_products.category_id')
							  ->leftJoin('annonserSubcategories', 'annonsercategories.id', '=', 'annonserSubcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->leftJoin('orders_details', 'variant_product.id', '=', 'orders_details.variant_id')
							  ->select(['products.*','annonsercategories.category_name','variant_product.image','variant_product.price as discounted_price','variant_product.id as variant_id','users.role_id',DB::raw("count(orders_details.id) as totalOrderedProducts")])
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
							  ->where('annonsercategories.status','=','active')
							  ->where('annonserSubcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where('users.is_deleted','=','0')
							  ->where('users.role_id','=',$request->role_id)

							  ->where(function($q) use ($currentDate) {

								$q->where([['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								});
			}else{
				
				$Products 			= Products::leftjoin('category_products', 'products.id', '=', 'category_products.product_id')
							  ->leftJoin('categories', 'categories.id', '=', 'category_products.category_id')
							  ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->leftJoin('orders_details', 'variant_product.id', '=', 'orders_details.variant_id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id',DB::raw('( variant_product.price -  ROUND((variant_product.price  * products.discount) / 100, 2) ) AS discounted_price'),'users.role_id',DB::raw("count(orders_details.id) as totalOrderedProducts")])
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where('users.is_deleted','=','0')
							  ->where('users.is_shop_closed','=','0')
							  ->where('users.role_id','=',$request->role_id)
							  ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])
									->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate],['variant_product.quantity', '>', 0]])
								->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								});
			}
			if($request->category_slug !='') {
				if(strpos(@$request->path, 'annonser') !== false){
					$category 		=  AnnonserCategories::select('id')->where('category_slug','=',$request->category_slug)->first();
				}else{
					$category 		=  Categories::select('id')->where('category_slug','=',$request->category_slug)->first();
				}
				
				$Products	=	$Products->where('category_products.category_id','=',@$category['id']);
				//$Products	=	$Products->where('categories.category_slug','=',$request->category_slug);
			}
			if($request->subcategory_slug !='') {
				if(strpos(@$request->path, 'annonser') !== false){
					$subcategory 		=  AnnonserSubcategories::select('id')->where('subcategory_slug','=',@$request->subcategory_slug)->first();
				}else{
					$subcategory 		=  Subcategories::select('id')->where('subcategory_slug','=',$request->subcategory_slug)->first();
				}
				
				$Products	=	$Products->where('category_products.subcategory_id','=',$subcategory['id']);
				//$Products	=	$Products->where('subcategories.subcategory_slug','=',$request->subcategory_slug);
			}
			if($request->sellers != '')
			{
				$tmpSeller = explode(',',$request->sellers);
				$Products	=	$Products->whereIn('products.user_id',$tmpSeller);
			}
			if($request->price_filter != '')
			{
				$tmpPrice = explode(',',$request->price_filter);
				$Products	=	$Products->whereBetween('variant_product.price',$tmpPrice);
			}

			if($request->city_filter != '')
			{ 
				$Products	=	$Products->where('users.city', 'like', '%' . $request->city_filter . '%');
			}

			if($request->search_string != '')
			{
				$Products	=	$Products->where('products.title', 'like', '%' . $request->search_string . '%');
			}

			if($request->search_seller_product != '')
			{
				$Products	=	$Products->where('products.title', 'like', '%' . $request->search_seller_product . '%')->where('products.user_id','=',$request->sellers);
			}

			//echo '<pre>';print_r($request->sort_order); print_r($request->sort_by); echo '</pre>';
			//dd('STOP');
			if($request->sort_order != '' && $request->sort_by != '')
			{
				if($request->sort_by == 'name')
				{
					$Products	=	$Products->orderBy('products.title', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'price')
				{
					/*if(!empty($Product->discount))
					{
						$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
						$Product->discount_price = $Product->price - $discount;
					}
					else
					{
						$Product->discount_price = 0;
					}*/

					//$Products	=	$Products->orderBy('variant_product.price', strtoupper($request->sort_order));
					$Products	=	$Products->orderBy('discounted_price', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'rating')
				{
					$Products	=	$Products->orderBy('products.rating', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'popular')
				{
					$Products	=	$Products->orderBy('totalOrderedProducts', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'discount')
				{
					$Products	=	$Products->orderBy('products.discount', strtoupper($request->sort_order));
				}
			}
			else
			{
				$Products	=	$Products->orderBy('products.sort_order', 'ASC')->orderBy('variant_product.id', 'ASC');
			}

	  		$Products			=	$Products->groupBy('products.id');

		//$data['ProductsTotal'] = $Products->count();

		$Products 			= $Products->paginate(config('constants.Products_limits'));
		//print_r(DB::getQueryLog());exit;
		//echo "<pre>";print_r(count($Products));exit;
		//$data['show_products'] =$Products[0]->role_id;
		//print_r(DB::getQueryLog());exit;
		if(count($Products)>0) {
			foreach($Products as $Product) {
				$product_link	=	url('/').'/product';
				if($request->category_slug!='')
				$product_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='')
				$product_link	.=	'/'.$request->subcategory_slug;

				$product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

				$Product->product_link	=	$product_link;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Product->user_id)->first()->toArray();
				$Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
				$Product->store_name	=	$SellerData['store_name'];

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
					if(!empty($Product->discount))
					{
						$discount = number_format((($Product->price * $Product->discount) / 100),2,'.','');
						$Product->discount_price = $Product->price - $discount;
					}
					else
					{
						$Product->discount_price = 0;
					}
				}

			}
     		 $data['Products']	= $Products;
     		// echo "djhjghghgjh";exit;
    //  dd($Products);
		}
		else {
		$data['Products']	= 'Inga produkter tillgängliga.';
		}


		$Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->city_filter,$request->search_string,'products',@$request->path);
	//	echo "<pre>";$Sellers[$request->sellers]['product_cnt']);
		$sellerData = '';
		if(!empty($Sellers))
		{
			$tmpSeller = array();
			if($request->sellers != '')
			{
				$tmpSeller = explode(',',$request->sellers);
			}
			$sellerData = '<ul class="category_list">';
			foreach($Sellers as $SellerId => $Seller)
			{
				$strChecked = '';
				if(in_array($SellerId, $tmpSeller))
				{
					$strChecked = 'checked="checked"';
				}

				$tmpSellerData = UserMain::where('id',$SellerId)->first()->toArray();
				$seller_name = $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
				$seller_name = str_replace( array( '\'', '"', 
				',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
				$seller_name = str_replace(" ", '-', $seller_name);
				$seller_name = strtolower($seller_name);
				if(!empty($Seller['name'])){
					$display_name = $Seller['name'];
				}else{
					$display_name = $Seller['store_name'];
				}
				if(!empty($Seller['product_cnt']) && $Seller['product_cnt'] > 0){
				$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerProductListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($SellerId)]).'\';">'.$display_name.' ( '.$Seller['product_cnt'].' )</span></a></li>';
				}
			}
			$sellerData .= '</ul>';
		}
		$data['path'] = @$request->path;

		$productListing = view('Front/products_list', $data)->render();
			echo json_encode(array('products'=>$productListing,'sellers'=>$sellerData));
		exit;
		//return view('Front/products_list', $data);
	}
    /* function to display products page*/
	public function buyerProductListing($category_slug='',$subcategory_slug='',Request $request)
    {

		$data	=	$this->productListingFunction($request->all(),$category_slug,$subcategory_slug);
		$data['current_role_id']	=	'1';
		$cities = DB::table('users')
			->where('is_deleted','=',0)
			->where('status','=','active')
			->where('city','!=','')
	        ->groupBy('city')
	        ->select('id','city')
	        ->get();
	    $tmpSBuyerData = UserMain::where('id',Auth::guard('user')->id())->first();
	    if(!empty($tmpSBuyerData) && $tmpSBuyerData->role_id=='1'){
	    	$data['role_id'] = $tmpSBuyerData->role_id;
	    }else{
	    	$data['role_id'] =2;
	    }
	    $data['allCities'] = $cities;
		return view('Front/products', $data);
	}

	public function productListing($category_slug='',$subcategory_slug='',Request $request)
    {
		
		$data	=	$this->productListingFunction($request->all(),$category_slug,$subcategory_slug);
		if(!empty($category_slug)){
			$getCategoryName = Categories::where('category_slug','like', '%' .$category_slug.'%')->first();
			$getSubCategoryName = Subcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
			$data['meta_title'] 	=  $getCategoryName['category_name'].' - '.$getSubCategoryName['subcategory_name'];
			$data['meta_description'] 	=  strip_tags($getCategoryName['description']);
		}
		$data['PopularServices']	= $this->getPopularServices($category_slug,$subcategory_slug);
		$data['current_role_id']	=	'2';
		$cities = DB::table('users')
			->where('is_deleted','=',0)
			->where('users.is_shop_closed','=','0')
			->where('status','=','active')
			->where('city','!=','')
	        ->groupBy('city')
	        ->select('id','city')
	        ->get();
	       
	    $data['allCities'] = $cities;
		return view('Front/products', $data);
	}
    public function productListingFunction($request,$category_slug='',$subcategory_slug='')
    {  
    	$current_uri = request()->segments();
        $data['pageTitle'] 	= 'Home';
        if($current_uri[0]=='annonser'){
        	$data['Categories'] = $this->getAnnonserCategorySubcategoryList();
        }else{
        	$data['Categories'] = $this->getCategorySubcategoryList();
        }
		
			
		
    	$data['PopularProducts']	= $this->getPopularProducts(config('constants.similar_product_limits'),$category_slug,$subcategory_slug);
		$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList();
    	$data['category_slug']		=	'';
    	$data['subcategory_slug']	=	'';
        $data['seller_id']		=	'';
        $data['search_string']		=	'';

    		if($category_slug!='')
    			$data['category_slug']	= $category_slug;

    	  if($subcategory_slug!='')
    			$data['subcategory_slug']	= $subcategory_slug;

        if(!empty($request['search']))
        	$data['search_string']	= $request['search'];
        	$data['all_cat_link'] = url('/')."/products";
        
        if(!empty($category_slug)){
        	 if($current_uri[0]=='annonser'){ 
        	 	$data['category_link'] = url('/')."/annonser/".$category_slug;
        	 	$getCategoryName = AnnonserCategories::where('category_slug','like', '%' .$category_slug.'%')->first();
        	 }else{
        	 	$data['category_link'] = url('/')."/products/".$category_slug;
        	 	$getCategoryName = Categories::where('category_slug','like', '%' .$category_slug.'%')->first();
        	 }
        	$data['category_name'] = @$getCategoryName['category_name'];
        	if(!empty($subcategory_slug)){
        		 if($current_uri[0]=='annonser'){
        		 	$getSubCategoryName = AnnonserSubcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
	         		$data['subcategory_link'] = url('/')."/annonser/".$category_slug."/".$subcategory_slug;
	        		$data['subcategory_name'] = @$getSubCategoryName['subcategory_name'];
        		 }else{
        		 
	         		$getSubCategoryName = Subcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
	         		$data['subcategory_link'] = url('/')."/products/".$category_slug."/".$subcategory_slug;
	        		$data['subcategory_name'] = $getSubCategoryName['subcategory_name'];
        		}
        	}
        }else{
        	//$getCategoryName = Categories::orderBy('sequence_no', 'asc')->first();
        	//$data['category_name'] = $getCategoryName['category_name'];
        	$data['category_name'] = trans('lang.all_category');
        	
        }
          
		return $data;
		//echo'<pre>';print_r($data);exit;
       //echo $data['category_name'];exit;
        
	}
	
	/* function to display products page*/
    public function sellerProductListing($store_name ='', $category_slug = '', $subcategory_slug= '', Request $request)
    {
    	//echo "<pre>---".print_r($request->category_slug);exit;
    	$category_slug=$request->category_slug;
    	$subcategory_slug=$request->$subcategory_slug;
    	if($request->hidden_type == "products" || empty($request->hidden_type)){

    		$store_name = str_replace('-', " ", $store_name);		
			$seller_user = UserMain::where('store_name',$store_name)->first()->toArray();		
			$seller_id = base64_encode($seller_user['id']);		
			
			$data = [];
			$data['hidden_type'] = $request->hidden_type;
	    	$data['path'] = @$request->path;
	        $data['pageTitle'] 	= 'Sellers Products';

			if($request->segment(4)=='products'){
				$data['Categories'] = $this->getCategorySubcategoryList($seller_id,$category_slug, $subcategory_slug);
			}else{
				$data['Categories'] = $this->getCategorySubcategoryList();
			}
			$subcategory_slug = $request->subcategory_slug;
			$data['PopularProducts']	= $this->getPopularProducts(config('constants.Products_limits'),$category_slug,$subcategory_slug);
			$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList();
	    	$data['category_slug']		=	'';
			$data['subcategory_slug']	=	'';
			$store_name = str_replace( array( '\'', '"', 
			',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $store_name);
			$store_name = str_replace(" ", '-', $store_name);
			$store_name = strtolower($store_name);
			$data['link_seller_name']		=	$store_name;
			$id = base64_decode($seller_id);
			$data['seller_id']			=	$id;
			
			$Seller = UserMain::where('id',$id)->first()->toArray();
			$data['seller_name']		=	$Seller['fname'].' '.$Seller['lname'];
			$data['store_name']			= 	$Seller['store_name'];
			$data['description']		= 	$Seller['description'];
			$data['city_name']		    =	$Seller['city'];
			$data['country_name']		    =	$Seller['country'];
			$data['seller_email']       =   $Seller['email'];
			$data['seller_name_url']		=	$store_name = str_replace(" ", '-', $store_name);	
			$data['header_image']       = '';
			$data['logo']       = '';
			$sellerImages = SellerPersonalPage::where('user_id',$id)->first();
			if(!empty($sellerImages))
			{
				$sellerImages = $sellerImages->toArray();
				
				if(!empty($sellerImages['header_img']))
				{
					$data['header_image']       = url('/').'/uploads/Seller/'.$sellerImages['header_img'];
				}
				if(!empty($sellerImages['logo']))
				{
					$data['logo']       = url('/').'/uploads/Seller/'.$sellerImages['logo'];
				}
				if(!empty($sellerImages['store_information']))
				{
					$data['store_information']       = $sellerImages['store_information'];
				}
			}

			$avgProductRating 	 = 0.00;
			$getAllProductRatings = ProductReview::join('products','product_review.product_id','products.id','')->select(['products.id','product_review.rating as product_rating'])->where('products.user_id','=',$id)->get();

			// and then you can get query log
		
			
			$productsRatingCnt 	 = $getAllProductRatings->count();
			if($productsRatingCnt>0){
				$totalProductRating = $getAllProductRatings->sum('product_rating');
				$avgProductRating = ($totalProductRating / $productsRatingCnt);
				$avgProductRating = number_format($avgProductRating,2);	
			}
				


			$avgServiceRating 	 = 0.00;
			
			$getAllServiceRatings = ServiceReview::join('services','service_review.service_id','services.id','')->select(['services.id','service_review.rating as service_rating'])->where('services.user_id','=',$id)->get();

			$ServicesRatingCnt 	 = $getAllServiceRatings->count();

			if($ServicesRatingCnt>0){
				$totalServiceRating = $getAllServiceRatings->sum('service_rating');
				$avgServiceRating = ($totalServiceRating / $ServicesRatingCnt);
				$avgServiceRating = number_format($avgServiceRating,2);	
			}
			$ratingSum = $avgProductRating + $avgServiceRating;
			$totalRating = 0.00;
			if($ratingSum>0){
				$totalRating = $ratingSum/2;
			}
			
	    	if($category_slug!='')
	    		$data['category_slug']	= $category_slug;

	    	if($subcategory_slug!='')
	    		$data['subcategory_slug']	= $subcategory_slug;

			// and then you can get query log

	    	/*get product review*/   	
			$data['productReviews']= $this->getReviews('products',$id,'');

	    	$getTerms =  SellerPersonalPage::where('user_id',$id)->first();

	    	$data['all_cat_link'] = url('/')."/products";
	    	if(!empty($category_slug)){
		    	$getCategoryName = Categories::where('category_slug','like', '%' .$category_slug.'%')->first();
		    	$data['category_link'] = url('/')."/products/".$category_slug;
		    	if(!empty($subcategory_slug)){
		        	$getSubCategoryName = Subcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
		        	$data['category_name'] = $getCategoryName['category_name'];
		           	$data['subcategory_name'] = $getSubCategoryName['subcategory_name'];
		           	$data['subcategory_link'] = url('/')."/products/".$category_slug."/".$subcategory_slug;
		        }
	    	}else{
	        	/*$getCategoryName = Categories::orderBy('sequence_no', 'asc')->first();
	        	$data['category_name'] = $getCategoryName['category_name'];*/
	        	$data['category_name'] = trans('lang.all_category');
	        }
	       // echo "<pre>";print_r($data['PopularProducts']);exit;
	        $data['role_id'] 			= 2;
			$data['is_seller'] 			= 1;
			$data['totalRating']  		= $totalRating;
			$data['getTerms']  			= $getTerms;
	        return view('Front/seller-products', $data);
    	}else if($request->hidden_type == "services"){

    		$store_name = str_replace('-', " ", $store_name);		
			$seller_user = UserMain::where('store_name',$store_name)->first()->toArray();		
			$seller_id = base64_encode($seller_user['id']);
			
			$data['hidden_type'] = $request->hidden_type;
			$data['pageTitle'] 	= 'Sellers Services';
	        $data['path'] = @$request->path;
			$data['Categories'] = $this->getCategorySubcategoryList()	;
			$subcategory_slug = $request->subcategory_slug;
    		//echo "<pre>---".print_r($subcategory_slug);exit;
			$data['PopularServices']	= $this->getPopularServices($category_slug,$subcategory_slug);
			if($request->segment(4)=='services'){
				$data['ServiceCategories'] = $this->getServiceCategorySubcategoryList($seller_id);
			}else{
				$data['ServiceCategories'] = $this->getServiceCategorySubcategoryList();
			}
			
			
	    	$data['category_slug']		=	'';
			$data['subcategory_slug']	=	'';
			$store_name = str_replace( array( '\'', '"', 
			',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $store_name);
			$store_name = str_replace(" ", '-', $store_name);
			$store_name = strtolower($store_name);
			$data['link_seller_name']		=	$store_name;
			$id = base64_decode($seller_id);
			$data['seller_id']			=	$id;
			
			$Seller = UserMain::where('id',$id)->first()->toArray();
			$data['seller_name']		=	$Seller['fname'].' '.$Seller['lname'];
			$data['store_name']			= 	$Seller['store_name'];
			$data['city_name']		    =	$Seller['city'];
			$data['country_name']		    =	$Seller['country'];
			$data['seller_email']       =   $Seller['email'];
			$data['seller_name_url']		=	$store_name = str_replace(" ", '-', $store_name);
			$data['description']		= 	$Seller['description'];
			
			$data['header_image']       = '';
			$data['logo']       = '';
			$sellerImages = SellerPersonalPage::where('user_id',$id)->first();
			if(!empty($sellerImages))
			{
				$sellerImages = $sellerImages->toArray();
				
				if(!empty($sellerImages['header_img']))
				{
					$data['header_image']       = url('/').'/uploads/Seller/'.$sellerImages['header_img'];
				}
				if(!empty($sellerImages['logo']))
				{
					$data['logo']       = url('/').'/uploads/Seller/'.$sellerImages['logo'];
				}
				if(!empty($sellerImages['store_information']))
				{
					$data['store_information']       = $sellerImages['store_information'];
				}
			}
			
			$avgProductRating 	 = 0.00;
			$getAllProductRratings = Products::where('user_id','=',$id)->get();
			$productsRatingCnt 	 = $getAllProductRratings->count();
			$totalProductRating = $getAllProductRratings->sum('rating');

			if(!empty($productsRatingCnt)){
				$avgProductRating = ($totalProductRating / $productsRatingCnt);
			}

			$avgProductRating = number_format($avgProductRating,2);		


			$avgServiceRating 	 = 0.00;
			$getAllServiceRratings = Services::where('user_id','=',$id)->get();
			$ServicesRatingCnt 	 = $getAllServiceRratings->count();
			$totalServiceRating = $getAllServiceRratings->sum('rating');

			if(!empty($ServicesRatingCnt)){
				$avgServiceRating = ($totalServiceRating / $ServicesRatingCnt);
	    	}

			$avgServiceRating = number_format($avgServiceRating,2);	
			
			$data['totalRating'] = ($avgProductRating + $avgServiceRating)/2;
			

	    	if($category_slug!='')
	    		$data['category_slug']	= $category_slug;

	    	if($subcategory_slug!='')
	    		$data['subcategory_slug']	= $subcategory_slug;
	    	 $data['all_cat_link'] = url('/')."/services";
	    	if(!empty($category_slug)){
	        	$getCategoryName = ServiceCategories::where('category_slug','like', '%' .$category_slug.'%')->first();
	        	$data['category_name'] = $getCategoryName['category_name'];
	        	$data['category_link'] = url('/')."/services/".$category_slug;
	        	if(!empty($subcategory_slug)){
	        		$getSubCategoryName = ServiceSubcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
	        		$data['subcategory_name'] = $getSubCategoryName['subcategory_name'];
	        		$data['subcategory_link'] = url('/')."/services/".$category_slug."/".$subcategory_slug;
	        	}
			}else{
	        	// $getCategoryName = ServiceCategories::orderBy('sequence_no', 'asc')->first();
	        	// $data['category_name'] = $getCategoryName['category_name'];
	        	$data['category_name'] = trans('lang.all_category');
	        }
	    	/*get service review*/   
			$data['serviceReviews']= $this->getReviews('services',$id,'','');
	    	$getTerms =  SellerPersonalPage::where('user_id',$id)->first();
			$data['is_seller'] = 1;	
			$data['getTerms']  			= $getTerms;
			//echo "hg".$store_name;exit;
	        return view('Front/seller-services', $data);
    	}
    	/*if(Session::get('hidservice_selected_page')=="services"){
    		$this->sellerServiceListing($store_name ='', $category_slug = null, $subcategory_slug= null, Request $request);
    	}*/
    	//echo Session::get('hidservice_selected_page');exit;
		
    }

    /*function to get revirew for product and services*/
	public function getReviews($type='',$sellerid='',$ids=''){

		if($type=='products'){
		 	$getAllReviews = ProductReview::join('products','product_review.product_id','products.id','')->join('users','product_review.user_id','users.id','')->select(['products.id','product_review.rating as product_rating','product_review.id as rating_id','product_review.product_id','product_review.comments','product_review.user_id as user_id','users.id','users.fname','users.lname','users.profile','product_review.updated_at']);
		 	if(!empty($sellerid)){
		 		$getAllReviews = $getAllReviews->where('products.user_id','=',$sellerid);
		 	}
		 	if(!empty($ids)){
		 		$getAllReviews = $getAllReviews->where('products.id','=',$ids);
		 		
		 	}
		 	
		 	$getAllReviews =  $getAllReviews->orderBy('product_review.id', 'DESC');	
		}else{

			$getAllReviews = ServiceReview::join('services','service_review.service_id','services.id','')->join('users','service_review.user_id','users.id','')->select(['services.id','service_review.rating as service_rating','service_review.service_id','service_review.id as rating_id','service_review.user_id as user_id','service_review.comments','users.id','users.fname','users.lname','users.profile','service_review.updated_at']);
			if(!empty($sellerid)){
		 		$getAllReviews = $getAllReviews->where('services.user_id','=',$sellerid);
		 	}
		 	if(!empty($ids)){
		 		$getAllReviews = $getAllReviews->where('services.id','=',$ids);
		 	
		 	}
		 	$getAllReviews =  $getAllReviews->orderBy('service_review.id', 'DESC');		
		}
			
	
		$getAllReviews =  $getAllReviews->paginate(config('constants.review_limits'));	
		return $getAllReviews;
	}
	//function for product details page

	public function productDetails($first_parameter='',$second_parameter='',$third_parameter='') 
	{	
		//echo "here".$_GET['annonser'];exit;
	 $current_uri = request()->segments();
    // DB::enableQueryLog();
      if(@$_GET['annonser'] ==1){
      	$Products 			=  Products::join('category_products', 'products.id', '=', 'category_products.product_id')
										->join('annonsercategories', 'annonsercategories.id', '=', 'category_products.category_id')
										->join('annonserSubcategories', 'annonsercategories.id', '=', 'annonserSubcategories.category_id')
										->select(['products.*','products.id as product_id','annonsercategories.id as catId'])
										->where('products.status','=','active')
										->where('products.is_deleted','=','0')
										->where('annonsercategories.status','=','active')
										->where('annonserSubcategories.status','=','active');
										
      }else{
      	$Products 			=  Products::join('category_products', 'products.id', '=', 'category_products.product_id')
										->join('categories', 'categories.id', '=', 'category_products.category_id')
										->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
										->select(['products.*','products.id as product_id','categories.id as catId'])
										->where('products.status','=','active')
										->where('products.is_deleted','=','0')
										->where('categories.status','=','active')
										->where('subcategories.status','=','active');
      }

		
										
							  			
	
		if($first_parameter!='' && $second_parameter!='' && $third_parameter!='' && strpos($third_parameter, '-P-') !== false){

			$category_slug	=	$first_parameter;
			$subcategory_slug=	$second_parameter;
			$product_slug	=	$third_parameter;
		}
		if($first_parameter=='' || $second_parameter=='' || $third_parameter=='') {

			if(strpos($first_parameter, '-P-') !== false)
				$product_slug	=	$first_parameter;

			else if(strpos($second_parameter, '-P-') !== false)
				$product_slug	=	$second_parameter;

			else if(strpos($third_parameter, '-P-') !== false)
				$product_slug	=	$third_parameter;


		}

		if($first_parameter=='' && $second_parameter=='' && $third_parameter=='') {
			return redirect(route('AllproductListing'));
		}
		if(!isset($product_slug)) {
			return redirect(route('AllproductListing'));
		}
		if(isset($category_slug) && $category_slug!='') {
			if(@$_GET['annonser'] ==1){
				$Products		=	$Products->where('annonsercategories.category_slug','=',$category_slug);
			}else{
				$Products		=	$Products->where('categories.category_slug','=',$category_slug);
			}
		}
		if(isset($subcategory_slug) && $subcategory_slug!='') {
			if(@$_GET['annonser'] ==1){
				$Products		=	$Products->where('annonserSubcategories.subcategory_slug','=',$subcategory_slug);
			}else{
				$Products		=	$Products->where('subcategories.subcategory_slug','=',$subcategory_slug);
			}
			
		}
		if(isset($product_slug) && $product_slug!='') {

			$product_parts	=	explode('-P-',$product_slug);
			if(!isset($product_parts[0]) || !isset($product_parts[1]))
				return redirect(route('AllproductListing'));
			$Products		=	$Products->where('products.product_slug','=',$product_parts[0])->where('products.product_code','=',$product_parts[1]);
		}
		$Products			=	$Products->get();// ->groupBy('products.id')
	    //print_r(DB::getQueryLog());exit;
		if(isset($product_slug) && count($Products)<=0) {
			$product_parts	=	explode('-P-',$product_slug);
			$Products		=	Products::where('products.status','=','active');
			if(isset($product_parts[0]))
				$Products	=	$Products->where('products.product_slug','=',$product_parts[0]);
			if(isset($product_parts[1]))
				$Products	=	$Products->Orwhere('products.product_code','=',$product_parts[1]);

			$Products		=	$Products->first();
			return redirect('/product/'.$Products->product_slug.'-P-'.$Products->product_code);
			if(count($Products)<=0){
				return redirect(route('AllproductListing'));
			}
			
		}

		$variantData		=	$ProductImages	=	$ProductAttributes	=	array();
	
		$Product = $Products[0]; 
		/*$ProductVariants = VariantProduct::where('product_id','=',$Product->id)->orderBy('id','ASC')->get();*/
		//$ProductVariants = VariantProduct::where('product_id','=',$Product->id)->where('quantity','>',0)->orderBy('id','ASC')->get();
		$ProductVariants = VariantProduct::where('product_id','=',$Product->id)->orderBy('id','ASC')->get();
			//echo "<pre>";print_r($ProductVariants);exit;
		foreach($ProductVariants as $variant)
		{
			$variantData[$variant->id]['id']			=	$variant->id;
			$variantData[$variant->id]['sku']			=	$variant->sku;
			$variantData[$variant->id]['price']			=	$variant->price;
			$discount_price = 0;
			if(!empty($Product->discount))
			{
				$discount = number_format((($variant->price * $Product->discount) / 100),2,'.','');
				$discount_price = $variant->price - $discount;
			}
			
			$variantData[$variant->id]['discount_price']			=	$discount_price;
			$variantData[$variant->id]['weight']		=	$variant->weight;
			$variantData[$variant->id]['quantity']		=	$variant->quantity;
			$variantData[$variant->id]['images']		=	explode(',',$variant->image);//[];

			$variantAttrs = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
											 ->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
											 ->where([['variant_id','=',$variant->id],['product_id','=',$Product->id]])->get();
			//dd($variantAttrs);								 
			foreach($variantAttrs as $variantAttr)
			{
				$variantData[$variant->id]['attr'][$variantAttr->name] = $variantAttr->attribute_values;
				$variantData[$variant->id]['attributes'][] = $variantAttr;
				
				$ProductAttributes[$variantAttr->attribute_id]['attribute_name']=$variantAttr->name;
				$ProductAttributes[$variantAttr->attribute_id]['attribute_type']=$variantAttr->type;
				$ProductAttributes[$variantAttr->attribute_id]['attribute_values'][$variantAttr->attribute_value_id]=$variantAttr->attribute_values;
        		$ProductAttributes[$variantAttr->attribute_id]['variant_values'][$variantAttr->attribute_value_id]=$variant->id;
			}

		}
		if(@$_GET['annonser'] ==1){
			$data['Categories'] = $this->getAnnonserCategorySubcategoryList();
		}else{
			$data['Categories'] = $this->getCategorySubcategoryList();
		}
		
		//$Product->id limit 5
		/*DB::enableQueryLog();

		// and then you can get query log


		  OrdersDetails::join('products', 'products.id', '=', 'orders_details.product_id')
		  select('orders_details.*')->where('orders_details.product_id','!=',80)->whereIn('orders_details.order_id', function($query){
		    $query->select('orders_details.order_id')
		    ->from('orders_details')    
		    ->where('orders_details.product_id','=',$Product->id);
		})->get();
		print_r(DB::getQueryLog());exit;*/
		//echo "<pre>";print_r($Products[0]['id']);exit;

$p_id =$Products[0]['id'];
//echo $p_id;exit;

	$currentDate = date('Y-m-d H:i:s');
		$PopularProducts	= OrdersDetails::join('products', 'products.id', '=', 'orders_details.product_id')
									->join('variant_product', 'products.id', '=', 'variant_product.product_id')
									->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
									->join('category_products', 'products.id', '=', 'category_products.product_id')
									->join('categories', 'categories.id', '=', 'category_products.category_id')
									->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
									->join('users', 'products.user_id', '=', 'users.id')
									->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')//DB::raw("DATEDIFF(products.created_at, '".$currentDate."') AS posted_days")
									->select(['products.*',DB::raw("DATEDIFF('".$currentDate."', products.sold_date) as sold_days"), DB::raw("DATEDIFF('".$currentDate."', products.created_at) as created_days") , DB::raw("count(orders_details.id) as totalOrderedProducts"),'variant_product.image','variant_product.price','variant_product.id as variant_id','categories.category_name','products.discount','users.store_name'])
									->where('products.status','=','active')
									->where('products.is_deleted','=','0')
									->where('categories.status','=','active')
									->where('subcategories.status','=','active')
									->where('users.status','=','active')
									->where('users.is_shop_closed','=','0')
									->where('users.is_deleted','=','0')
									->where('orders_details.product_id','!=',$p_id)->whereIn('orders_details.order_id', function($query)use ($p_id){

									$query->select('orders_details.order_id')
									->from('orders_details')
									->where('orders_details.product_id','=',$p_id);  
									//->where('orders_details.product_id','=',$p_id);
									})->groupBy('products.id')
									->offset(0)->limit(config('constants.Popular_Product_limits'))->get();
								
									if(count($PopularProducts)>0){
										$data['PopularProducts']=$PopularProducts;
									}else{
										$data['PopularProducts']	= $this->getPopularProducts(5);
									}

										

		$data['Product']			= $Product;
		$data['variantData']		= $variantData;
		$data['ProductAttributes']	= $ProductAttributes;

		$tmpSellerData = UserMain::where('id',$Product['user_id'])->first()->toArray();
		$seller_name = $tmpSellerData['store_name'];
		$data['seller_name'] = $seller_name;
		$data['store_name'] = $tmpSellerData['store_name'];
		if(empty($seller_name)){
			$seller_name =  $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
		}
		$seller_name = str_replace( array( '\'', '"', 
		',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
		$seller_name = str_replace(" ", '-', $seller_name);
		$seller_name = strtolower($seller_name);
		
		$sellerLink = route('sellerProductListingByCategory',['seller_name' => $seller_name]);
		$data['seller_link'] = $sellerLink;
		/*get product review*/
		$data['productReviews']= $this->getReviews('products','',$Product->id);
		$data['getTerms'] =  SellerPersonalPage::where('user_id',$Product['user_id'])->first();
		$data['meta_title']	=	'Tijara - '.$Product['title'];

	
	
		$data['product_id'] = $Products[0]->id;
		$data['product_link']	= url()->full();
		if(Auth::guard('user')->id()) {
			$loginUserData = UserMain::where('id',Auth::guard('user')->id())->where('is_deleted','=','0')->first();
			$data['loginUserEmail']	= $loginUserData['email'];
		}else{
			$data['loginUserEmail']='';
		}
//echo $tmpSellerData['role_id'];exit;
		//dd($loginUserData);
		if($tmpSellerData['role_id']==2){// echo "<pre>";print_r($data);exit;
        	return view('Front/seller_product_details', $data);
        }
		else {
DB::enableQueryLog();
			$currentDate = date('Y-m-d H:i:s');

						$similarProducts	=   Products::join('variant_product', 'products.id', '=', 'variant_product.product_id')
									->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
									->join('users', 'products.user_id', '=', 'users.id')
									->select(['products.*',
									 DB::raw("DATEDIFF('".$currentDate."', products.created_at) as created_days") ,
									 'variant_product.image','variant_product.price','variant_product.id as variant_id'])
									->where('products.status','=','active')
									->where('products.is_deleted','=','0')
									->where('products.is_buyer_product','=','1')
									->where('users.status','=','active')
									->where('users.is_deleted','=','0')
									->where(function($q) use ($currentDate) {

										$q->where([["users.role_id",'=',"1"],['is_sold','=','0']])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
									})
									->orderBy('variant_product.id', 'ASC')
									->groupBy('products.id')
									->where('products.id','!=',$Product->id)->offset(0)->limit(config('constants.similar_product_limits'));
									
	//print_r(DB::getQueryLog());exit;
			/*if(isset($category_slug) && $category_slug!='') {

				$similarProducts=	$similarProducts->where('categories.category_slug','=',$category_slug);
			}
			else {
				$similarProducts=	$similarProducts->where('categories.id','=',$Product->catId);
			
			}*/
			//$similarProducts	=	$similarProducts;
			//echo $Product->catId;exit;
		
			$product_sold_data = BuyerProducts::where('product_id',$Product->id)->first();
			$data['product_location'] = @$product_sold_data['location'];
			$data['product_seller_name'] = $product_sold_data['user_name'];
			$data['similarProducts']	=	$similarProducts;
			
			$data['buyer_product_details']	=	BuyerProducts::where('product_id',$Product->id)->first();

			return view('Front/buyer_product_details', $data);
		}
			
    }
    public function getProductAttributeDetails(Request $request) {
		$getVariantIds			=	VariantProductAttribute::where('variant_product_attribute.attribute_value_id','=',$request->attribute_value_id)->orderBy('id','asc')->get(['variant_id']);
		$attributes				=	array();
		if(count($getVariantIds)>0) {

			foreach($getVariantIds as $getVariantId) {
				$VariantProduct 		=  Products::join('variant_product', 'products.id', '=', 'variant_product.product_id')
										->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
										->join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
										->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
										->select(['variant_product.*','variant_product_attribute.*','attributes_values.attribute_values'])
							  			->where('variant_product_attribute.variant_id','=',$getVariantId->variant_id)->get();

				//echo'<pre>';print_r($VariantProduct);exit;

				foreach($VariantProduct as $Variant) {
						$attributes[$Variant->attribute_id][$Variant->attribute_value_id]=$Variant->attribute_values;
				}
			}
		}

			$json_arr = array(

			'image'             => $VariantProduct[0]->image,

			'price'    			=> $VariantProduct[0]->price,

			'attributes'	 	=> $attributes,


			);



		return json_encode($json_arr);
	}

	public function cmsPage($page_slug)
	{

        $details 			= Page::where('slug', $page_slug)->first();
    		$data['details'] 	= $details;
    		$data['page_slug']		=	$page_slug;
    		$data['pageTitle'] 	= $details['title'];

        return view('Front/pages', $data);
	}

  function getProductCategories($productId,$annonser='')
  {
    $productCategories = [];
    if(!empty($productId))
    {
	    if($annonser == 1){
	    	$productCategories = ProductCategory::join('annonsercategories', 'annonsercategories.id', '=', 'category_products.category_id')
                         ->join('annonserSubcategories', 'annonserSubcategories.id', '=', 'category_products.subcategory_id')
                         ->select(['category_products.*','annonsercategories.category_name', 'annonsercategories.category_slug', 'annonserSubcategories.subcategory_name', 'annonserSubcategories.subcategory_slug'])
                         ->where('category_products.product_id','=',$productId)
                         ->get()->toArray();
	    }else{
	    	$productCategories = ProductCategory::join('categories', 'categories.id', '=', 'category_products.category_id')
                         ->join('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')
                         ->select(['category_products.*','categories.category_name', 'categories.category_slug', 'subcategories.subcategory_name', 'subcategories.subcategory_slug'])
                         ->where('category_products.product_id','=',$productId)
                         ->get()->toArray();
	    }
    }
    return $productCategories;
  }


  function getProductOptions(Request $request)
  {
	$attribute_id = $request->attribute_id;
	$attribute_value = $request->attribute_value;
	$product_id = $request->product_id;

	$attributeDetails = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
											->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
											->join('variant_product', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
											->join('products', 'variant_product.product_id', '=', 'products.id')
											->select('attributes.name as attribute_name','attributes.type as attribute_type','attributes_values.attribute_values','variant_product_attribute.*','variant_product.*','products.discount')
											->where([['variant_product_attribute.attribute_id','=',$attribute_id],['attribute_value_id','=',$attribute_value],['variant_product_attribute.product_id','=',$product_id]])->get()->toArray();
	if(!empty($attributeDetails[0]['discount']))
	{
		$discount = number_format((($attributeDetails[0]['price'] * $attributeDetails[0]['discount']) / 100),2,'.','');
		$discount_price = $attributeDetails[0]['price'] - $discount;
	}
	else
	{
		$discount_price = 0;
	}		
	
	$attributeDetails[0]['discount_price'] = $discount_price;
	
	$otherAttributeDetails = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
	->select('attributes.name as attribute_name','attributes.type as attribute_type','attributes_values.attribute_values','variant_product_attribute.*')
	->where([['variant_product_attribute.attribute_id','<>',$attribute_id],['variant_product_attribute.variant_id','=',$attributeDetails[0]['variant_id']],['variant_product_attribute.product_id','=',$product_id]])->get()->toArray();	

				
	if(!empty($otherAttributeDetails))
	{
		$getOtherAvailableOptions = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
		->select('attributes.name as attribute_name','attributes.type as attribute_type','attributes_values.attribute_values','variant_product_attribute.*')
		->where([['variant_product_attribute.variant_id','<>',$attributeDetails[0]['variant_id']],['variant_product_attribute.attribute_id','=',$otherAttributeDetails[0]['attribute_id']],
		['variant_product_attribute.attribute_value_id','=',$otherAttributeDetails[0]['attribute_value_id']],['variant_product_attribute.product_id','=',$product_id]])->get()->toArray();
	}
	else if(empty($getOtherAvailableOptions))
	{
		$getOtherAvailableOptions = VariantProductAttribute::join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
		->select('attributes.name as attribute_name','attributes.type as attribute_type','attributes_values.attribute_values','variant_product_attribute.*')
		->where([['variant_product_attribute.attribute_id','<>',$attribute_id],['variant_product_attribute.variant_id','=',$attributeDetails[0]['variant_id']],['variant_product_attribute.product_id','=',$product_id]])->get()->toArray();
	}

	echo json_encode(['other_option' => $getOtherAvailableOptions, 'current_variant' => $attributeDetails[0]]);
	exit;
  }

  //services functions

  //function to get services list by provided parameters
	public function getServicesByParameter(Request $request) {
	
		$currentDate = date('Y-m-d H:i:s');
		$Services 			= Services::join('category_services', 'services.id', '=', 'category_services.service_id')
							  ->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
							  ->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')							  				  
							  ->join('users', 'services.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->leftJoin('service_requests', 'services.id', '=', 'service_requests.service_id')
							  ->select(['services.*','servicecategories.category_name',DB::raw("count(service_requests.id) as totalServiceRequests")])
							  ->where('services.status','=','active')
							  ->where('services.is_deleted','=','0')
							  ->where('servicecategories.status','=','active')
							  ->where('serviceSubcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where('users.is_deleted','=','0')
							  ->where('users.is_shop_closed','=','0')
							  ->where(function($q) use ($currentDate) {
								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
									->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate]]);
								
								});
							  //->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
							  //->orwhere([['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate]]);

			if($request->category_slug !='') {
				$category 		=  ServiceCategories::select('id')->where('category_slug','=',$request->category_slug)->first();
				$Services	=	$Services->where('category_services.category_id','=',@$category['id']);
				//$Services	=	$Services->where('servicecategories.category_slug','=',$request->category_slug);
			}
			if($request->subcategory_slug !='') {
				$subcategory 		=  ServiceSubcategories::select('id')->where('subcategory_slug','=',$request->subcategory_slug)->first();
				$Services	=	$Services->where('category_services.subcategory_id','=',@$subcategory['id']);
				//$Services	=	$Services->where('serviceSubcategories.subcategory_slug','=',$request->subcategory_slug);
			}
			if($request->sellers != '')
			{
				$tmpSeller = explode(',',$request->sellers);
				$Services	=	$Services->whereIn('services.user_id',$tmpSeller);
			}
			
			if($request->search_string != '')
			{
				$Services	=	$Services->where('services.title', 'like', '%' . $request->search_string . '%');
			}

			if($request->city_filter != '')
			{
				$Services	=	$Services->where('users.city', 'like', '%' . $request->city_filter . '%');
			}
			if($request->price_filter != '')
			{
				$tmpPrice = explode(',',$request->price_filter);
				$Services	=	$Services->whereBetween('services.service_price',$tmpPrice);
			}

			if($request->search_seller_product != '')
			{
				$Services	=	$Services->where('services.title', 'like', '%' . $request->search_seller_product . '%')->where('services.user_id','=',$request->sellers);
			}


			if($request->sort_order != '' && $request->sort_by != '')
			{
				if($request->sort_by == 'name')
				{
					$Services	=	$Services->orderBy('services.title', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'price')
				{
				
					$Services	=	$Services->orderBy('services.service_price', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'rating')
				{
					$Services	=	$Services->orderBy('services.rating', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'popular')
				{
					$Services	=	$Services->orderBy('totalServiceRequests', strtoupper($request->sort_order));
				}
		
				
			}
			else
			{
				$Services	=	$Services->orderBy('services.sort_order', 'ASC');
			}

	  		$Services			=	$Services->groupBy('services.id');

		//$data['ServicesTotal'] = $Services->count();

		$Services 			= $Services->paginate(config('constants.Services_limits'));
		//dd($Services);
		 // print_r(DB::getQueryLog());
		 // exit;
		if(count($Services)>0) {
			foreach($Services as $Service) {
				$service_link	=	url('/').'/service';
				if($request->category_slug!='')
				$service_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='')
				$service_link	.=	'/'.$request->subcategory_slug;

				$service_link	.=	'/'.$Service->service_slug.'-S-'.$Service->service_code;

				$Service->service_link	=	$service_link;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Service->user_id)->first()->toArray();
				$Service->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
				$Service->store_name	=	$SellerData['store_name'];

				$Service->image = explode(",",$Service->images)[0];
				//echo'<pre>';print_r($Service);
			}
			//exit;
     		 $data['Services']	= $Services;
      
		}
		else {
		$data['Services']	= trans('lang.noServicesFound');
		}

		//dd(is_object($data['Services']));

		$Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->city_filter,$request->search_string,'services');
		// echo "<pre>";
		// print_r($Sellers);exit;
		$sellerData = '';
		if(!empty($Sellers))
		{
			$tmpSeller = array();
			if($request->sellers != '')
			{
				$tmpSeller = explode(',',$request->sellers);
			}
			$sellerData = '<ul class="category_list">';
			foreach($Sellers as $SellerId => $Seller)
			{
				$strChecked = '';
				if(in_array($SellerId, $tmpSeller))
				{
					$strChecked = 'checked="checked"';
				}

				$tmpSellerData = UserMain::where('id',$SellerId)->first()->toArray();
				$seller_name = $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
				$seller_name = str_replace( array( '\'', '"', 
				',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
				$seller_name = str_replace(" ", '-', $seller_name);
				$seller_name = strtolower($seller_name);
				/*if(!empty($Seller['service_cnt']) && $Seller['service_cnt'] > 0){
					$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerServiceListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($SellerId)]).'\';">'.$Seller['name'].' ( '.$Seller['service_cnt'].' )</span></a></li>';
				}*/
				if(!empty($Seller['service_cnt']) && $Seller['service_cnt'] > 0){
					$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerProductListingByCategory',['seller_name' => $seller_name]).'\';">'.$Seller['name'].' ( '.$Seller['service_cnt'].' )</span></a></li>';
				}
			}
			$sellerData .= '</ul>';
		}
		$data['path'] = @$request->path;

		$serviceListing = view('Front/services_list', $data)->render();
			echo json_encode(array('services'=>$serviceListing,'sellers'=>$sellerData));
		exit;
		//return view('Front/services_list', $data);
	}

	 /* function to display services page*/
	 public function serviceListing($category_slug='',$subcategory_slug='',Request $request)
	 {

			$data['pageTitle'] 	= 'Home';
			$data['Categories'] = $this->getCategorySubcategoryList()	;
			$data['PopularServices']	= $this->getPopularServices($category_slug,$subcategory_slug);
			$data['PopularProducts']	= $this->getPopularProducts(5,$category_slug,$subcategory_slug);
			//echo "<pre>";print_r($data['PopularProducts']);exit;
			//$data['Services']			= $Services;
			$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList()	;
			$data['category_slug']		=	'';
			$data['subcategory_slug']	=	'';
			$data['seller_id']		=	'';
			$data['search_string']		=	'';
 
			 if($category_slug!='')
				 $data['category_slug']	= $category_slug;
 
		   if($subcategory_slug!='')
				 $data['subcategory_slug']	= $subcategory_slug;

		 if(!empty($request->search))
			 $data['search_string']	= $request->search;

		 $data['all_cat_link'] = url('/')."/services";
 		if(!empty($category_slug)){
        	$getCategoryName = ServiceCategories::where('category_slug','like', '%' .$category_slug.'%')->first();
        	$data['category_name'] = $getCategoryName['category_name'];
        	$data['category_link'] = url('/')."/services/".$category_slug;
        	if(!empty($subcategory_slug)){
        		$getSubCategoryName = ServiceSubcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
        		$data['subcategory_name'] = $getSubCategoryName['subcategory_name'];
        		$data['subcategory_link'] = url('/')."/services/".$category_slug."/".$subcategory_slug;
        	}        	
		} else{
        	/*$getCategoryName = ServiceCategories::orderBy('sequence_no', 'asc')->first();
        	$data['category_name'] = $getCategoryName['category_name'];*/
        	$data['category_name'] = trans('lang.all_category');
        }
        $cities = DB::table('users')
			->where('is_deleted','=',0)
			->where('users.is_shop_closed','=','0')
			->where('status','=','active')
			->where('city','!=','')
	        ->groupBy('city')
	        ->select('id','city')
	        ->get();
	       
	    $data['allCities'] = $cities;
			//echo "<pre>";print_r($data['PopularServices']);exit;
		 return view('Front/services', $data);
	 }

	 //function for service details page

	public function serviceDetails($first_parameter='',$second_parameter='',$third_parameter='') 
	{
		$Services 			=  Services::join('category_services', 'services.id', '=', 'category_services.service_id')
										->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
										->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
										->select(['services.*','services.id as service_id'])
										->where('services.status','=','active')
										->where('services.is_deleted','=','0')
										->where('servicecategories.status','=','active')
										->where('serviceSubcategories.status','=','active');
										
							  			
		
		if($first_parameter!='' && $second_parameter!='' && $third_parameter!='' && strpos($third_parameter, '-S-') !== false){

			$category_slug	=	$first_parameter;
			$subcategory_slug=	$second_parameter;
			$service_slug	=	$third_parameter;
		}
		if($first_parameter=='' || $second_parameter=='' || $third_parameter=='') {

			if(strpos($first_parameter, '-S-') !== false)
				$service_slug	=	$first_parameter;

			else if(strpos($second_parameter, '-S-') !== false)
				$service_slug	=	$second_parameter;

			else if(strpos($third_parameter, '-S-') !== false)
				$service_slug	=	$third_parameter;


		}

		if($first_parameter=='' && $second_parameter=='' && $third_parameter=='') {
			return redirect(route('AllserviceListing'));
		}
		if(!isset($service_slug)) {
			return redirect(route('AllserviceListing'));
		}
		if(isset($category_slug) && $category_slug!='') {

			$Services		=	$Services->where('servicecategories.category_slug','=',$category_slug);
		}
		if(isset($subcategory_slug) && $subcategory_slug!='') {
			$Services		=	$Services->where('serviceSubcategories.subcategory_slug','=',$subcategory_slug);
		}
		if(isset($service_slug) && $service_slug!='') {

			$service_parts	=	explode('-S-',$service_slug);
			if(!isset($service_parts[0]) || !isset($service_parts[1]))
				return redirect(route('AllserviceListing'));
			$Services		=	$Services->where('services.service_slug','=',$service_parts[0])->where('services.service_code','=',$service_parts[1]);
		}
		$Services			=	$Services->get();// ->groupBy('services.id')
		

		if(isset($service_slug) && count($Services)<=0) {
			$service_parts	=	explode('-S-',$service_slug);
			$Services		=	Services::where('services.status','=','active');
			if(isset($service_parts[0]))
				$Services	=	$Services->where('services.service_slug','=',$service_parts[0]);
			if(isset($service_parts[1]))
				$Services	=	$Services->Orwhere('services.service_code','=',$service_parts[1]);

			$Services		=	$Services->first();
			return redirect('/service/'.$Services->service_slug.'-S-'.$Services->service_code);
			if(count($Services)<=0)
			return redirect(route('AllserviceListing'));
		}

		$variantData		=	$ServiceImages	=	$ServiceAttributes	=	array();
		
		$Service = $Services[0];

		$data['Categories'] = $this->getCategorySubcategoryList()	;

		$data['PopularServices']	= $this->getPopularServices();
		$data['Service']			= $Service;
		//echo "<pre>";print_r($Service);exit;
		$tmpSellerData = UserMain::where('id',$Service['user_id'])->first()->toArray();
		$seller_name = $tmpSellerData['store_name'];
		$data['seller_name'] = $seller_name;
		$data['store_name'] = $tmpSellerData['store_name'];
		$seller_name = str_replace( array( '\'', '"', 
		',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
		$seller_name = str_replace(" ", '-', $seller_name);
		$seller_name = strtolower($seller_name);
		
		//$sellerLink = route('sellerServiceListingByCategory',['seller_name' => $seller_name]);
		$sellerLink = route('sellerProductListingByCategory',['seller_name' => $seller_name]);
		$data['seller_link'] = $sellerLink;
		$data['service_link']	= url()->full();

		if(Auth::guard('user')->id()) {
			$loginUserData = UserMain::where('id',Auth::guard('user')->id())->where('is_deleted','=','0')->first();

			$data['loginUserFname']	= $loginUserData['fname'];
			$data['loginUserLname']	= $loginUserData['lname'];
			$data['loginUserEmail']	= $loginUserData['email'];
			$data['loginUserId']	= $loginUserData['id'];			
			$data['loginUserAddress']	= $loginUserData['address'];
			$data['loginUserPostcode']	= $loginUserData['postcode'];
			$data['loginUserCity']	    = $loginUserData['city'];
			$data['loginUserRoleId']	= $loginUserData['role_id'];
		}else{ 
			$data['loginUserFname']=$data['loginUserFname']=$data['loginUserEmail']=$data['loginUserId']=$data['loginUserAddress']=$data['loginUserPostcode']=$data['loginUserCity']=$data['loginUserRoleId']='';
		}

		$data['serviceReviews']= $this->getReviews('services','',$Service->id);

		$data['service_id'] = $Services[0]->id;
		$data['serviceAvailability']=   ServiceAvailability::where('service_id',$Service->id)->get();
      
		$data['getTerms'] =  SellerPersonalPage::where('user_id',$Service['user_id'])->first();

        return view('Front/service_details', $data);
    }
	
	//get popular services
	function getPopularServices($category_slug='',$subcategory_slug='') {
		$currentDate = date('Y-m-d H:i:s');
		$current_uri = request()->segments();
		if(!empty($current_uri[0]) && @$current_uri[0]=='services'){
        	$limit = config('constants.similar_product_limits');
		}else{
			$limit = config('constants.Popular_Product_limits');
		}
	
	/*	$PopularServices 	= Services::join('service_requests', 'services.id', '=', 'service_requests.service_id')								
								->join('category_services', 'services.id', '=', 'category_services.service_id')
								->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
								->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->join('users', 'services.user_id', '=', 'users.id')
								->join('user_packages', 'user_packages.user_id', '=', 'users.id')
								->select(['services.*',DB::raw("count(service_requests.id) as totalOrderedServices"),'servicecategories.category_name'])
								->where('services.status','=','active')
								->where('services.is_deleted','=','0')
								->where('servicecategories.status','=','active')
							  	->where('serviceSubcategories.status','=','active')
								->where('users.status','=','active')
								->where('users.is_deleted','=','0')
                			    ->where('users.is_shop_closed','=','0')
                			    ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
									->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate]]);
								
								})

								->orderBy('totalOrderedServices', 'DESC')
								->groupBy('services.id')
								->offset(0)->limit($limit)->get();*/
	$PopularServices 	= Services::leftjoin('service_requests', 'services.id', '=', 'service_requests.service_id')								
								->join('category_services', 'services.id', '=', 'category_services.service_id')
								->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
								->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->join('users', 'services.user_id', '=', 'users.id')
								->join('user_packages', 'user_packages.user_id', '=', 'users.id')
								->select(['services.*',DB::raw("count(service_requests.id) as totalOrderedServices"),'servicecategories.category_name'])
								->where('services.status','=','active')
								->where('services.is_deleted','=','0')
								->where('servicecategories.status','=','active')
							  	->where('serviceSubcategories.status','=','active')
								->where('users.status','=','active')
								->where('users.is_deleted','=','0')
                			    ->where('users.is_shop_closed','=','0')
                			    ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
									->orwhere([["user_packages.is_trial",'=',"1"],['user_packages.status','=','active'],['trial_start_date','<=',$currentDate],['trial_end_date','>=',$currentDate]]);
								
								})

								->orderBy('service_requests.service_id', 'DESC')
								->groupBy('services.id')
								->offset(0)->limit($limit)->get();

		if(count($PopularServices)>0) {
			foreach($PopularServices as $Service) {
				$serviceCategories = $this->getServiceCategories($Service->id);

				$service_link	=	url('/').'/service';
				if($category_slug!='')
				{
				 $service_link	.=	'/'.$category_slug;
				}
				else {
				  $service_link	.=	'/'.$serviceCategories[0]['category_slug'];
				}
				if($subcategory_slug!='')
				{
							  $service_link	.=	'/'.$subcategory_slug;
				}
				else {
				  $service_link	.=	'/'.$serviceCategories[0]['subcategory_slug'];
				}

				$service_link	.=	'/'.$Service->service_slug.'-S-'.$Service->service_code;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email','users.store_name')->where('users.id','=',$Service->user_id)->first()->toArray();
				$Service->seller	=	$SellerData['fname'].' '.$SellerData['lname'];
				$Service->store_name	=	$SellerData['store_name'];
				$Service->service_link	=	$service_link;

				$Service->image = explode(",",$Service->images)[0];
			}
		}
								return $PopularServices;
	}
	/* function to display services page*/
    public function sellerServiceListing($store_name ='', $category_slug = null, $subcategory_slug= null, Request $request)
    {
        $store_name = str_replace('-', " ", $store_name);		
		$seller_user = UserMain::where('store_name',$store_name)->first()->toArray();		
		$seller_id = base64_encode($seller_user['id']);
		
		
		$data['pageTitle'] 	= 'Sellers Services';
        $data['path'] = @$request->path;
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		$data['PopularServices']	= $this->getPopularServices($category_slug,$subcategory_slug);
		if($request->segment(4)=='services'){
			$data['ServiceCategories'] = $this->getServiceCategorySubcategoryList($seller_id);
		}else{
			$data['ServiceCategories'] = $this->getServiceCategorySubcategoryList();
		}
		
		
    	$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';
		$store_name = str_replace( array( '\'', '"', 
		',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $store_name);
		$store_name = str_replace(" ", '-', $store_name);
		$store_name = strtolower($store_name);
		$data['link_seller_name']		=	$store_name;
		$id = base64_decode($seller_id);
		$data['seller_id']			=	$id;
		
		$Seller = UserMain::where('id',$id)->first()->toArray();
		$data['seller_name']		=	$Seller['fname'].' '.$Seller['lname'];
		$data['store_name']			= 	$Seller['store_name'];
		$data['city_name']		    =	$Seller['city'];
		$data['country_name']		    =	$Seller['country'];
		$data['seller_email']       =   $Seller['email'];
		$data['seller_name_url']		=	$store_name = str_replace(" ", '-', $store_name);
		$data['description']		= 	$Seller['description'];
		
		$data['header_image']       = '';
		$data['logo']       = '';
		$sellerImages = SellerPersonalPage::where('user_id',$id)->first();
		if(!empty($sellerImages))
		{
			$sellerImages = $sellerImages->toArray();
			
			if(!empty($sellerImages['header_img']))
			{
				$data['header_image']       = url('/').'/uploads/Seller/'.$sellerImages['header_img'];
			}
			if(!empty($sellerImages['logo']))
			{
				$data['logo']       = url('/').'/uploads/Seller/'.$sellerImages['logo'];
			}
			if(!empty($sellerImages['store_information']))
			{
				$data['store_information']       = $sellerImages['store_information'];
			}
		}
		
		$avgProductRating 	 = 0.00;
		$getAllProductRratings = Products::where('user_id','=',$id)->get();
		$productsRatingCnt 	 = $getAllProductRratings->count();
		$totalProductRating = $getAllProductRratings->sum('rating');

		if(!empty($productsRatingCnt)){
			$avgProductRating = ($totalProductRating / $productsRatingCnt);
		}

		$avgProductRating = number_format($avgProductRating,2);		


		$avgServiceRating 	 = 0.00;
		$getAllServiceRratings = Services::where('user_id','=',$id)->get();
		$ServicesRatingCnt 	 = $getAllServiceRratings->count();
		$totalServiceRating = $getAllServiceRratings->sum('rating');

		if(!empty($ServicesRatingCnt)){
			$avgServiceRating = ($totalServiceRating / $ServicesRatingCnt);
    	}

		$avgServiceRating = number_format($avgServiceRating,2);	
		
		$data['totalRating'] = ($avgProductRating + $avgServiceRating)/2;
		

    	if($category_slug!='')
    		$data['category_slug']	= $category_slug;

    	if($subcategory_slug!='')
    		$data['subcategory_slug']	= $subcategory_slug;
    	 $data['all_cat_link'] = url('/')."/services";
    	if(!empty($category_slug)){
        	$getCategoryName = ServiceCategories::where('category_slug','like', '%' .$category_slug.'%')->first();
        	$data['category_name'] = $getCategoryName['category_name'];
        	$data['category_link'] = url('/')."/services/".$category_slug;
        	if(!empty($subcategory_slug)){
        		$getSubCategoryName = ServiceSubcategories::where('subcategory_slug','like', '%' .$subcategory_slug.'%')->where('category_id','=',$getCategoryName['id'])->first();
        		$data['subcategory_name'] = $getSubCategoryName['subcategory_name'];
        		$data['subcategory_link'] = url('/')."/services/".$category_slug."/".$subcategory_slug;
        	}
		}else{
        	// $getCategoryName = ServiceCategories::orderBy('sequence_no', 'asc')->first();
        	// $data['category_name'] = $getCategoryName['category_name'];
        	$data['category_name'] = trans('lang.all_category');
        }
    	/*get service review*/   
		$data['serviceReviews']= $this->getReviews('services',$id,'','');
    	$getTerms =  SellerPersonalPage::where('user_id',$id)->first();
		$data['is_seller'] = 1;	
		$data['getTerms']  			= $getTerms;
        return view('Front/seller-services', $data);
    }

	/*function to send a service request*/
    public function sendServiceRequest(Request $request)
    {
        $service_time	=	$request->input('service_time');//str_replace('/','-',$request->input('service_time'));
        $request_id	=	DB::table('service_requests')->insertGetId([
            'service_title' => $request->input('service_title'),
			'location' => $request->input('location'),
			'service_date' => $request->input('service_date'),
			'service_time' => $request->input('service_time'),
			//'phone_number' => $request->input('phone_number'),
            'user_id' => Auth::guard('user')->id(),
			'service_id'=>$request->input('service_id'),
			'service_price'=>$request->input('service_price'),
            'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
        ]);
		
		$service_request = Services::join('users', 'users.id', '=', 'services.user_id')							
							->where('services.id', '=', $request->input('service_id'))->first();
        
       
		$user = DB::table('users')->where('id', '=', Auth::guard('user')->id())->first();
		$customername = $user->fname;
		$customeraddress	=	$user->address.' '.$user->city.' '.$user->postcode;
		$sellername 	=$service_request->fname;

		$service	=	$service_request->title;
		$email		=	$service_request->email;
		$servicemessage	=	$request->input('message');
		$service_date=	$request->input('service_date');
		$service_time=	$request->input('service_time');
		//$service_time	=	date('Y-m-d H:i:s',strtotime($service_time));
		$seller	=	$service_request->fname;
		

		$GetEmailContents = getEmailContents('Service Request');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];
        
        $contents = str_replace(['##CUSTOMERNAME##', '##NAME##','##SERVICE##','##SERVICETIME##'
		,'##SERVICEDATE##','##SERVICELOCATION##','##SERVICECOST##','##SITE_URL##',
			'##CUSTOMERADDRESS##','##SELLER##'],
		[$customername,$seller,$service,$service_time,$service_date,$request->input('location'),
		$request->input('service_price'),url('/'),$customeraddress,$sellername],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$seller,$subject) {
            $message->to($email, $seller)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });

		
		

        echo 1;
        exit;
    }
	function getServiceCategories($serviceId)
	{
		$serviceCategories = [];
		if(!empty($serviceId))
		{
		$serviceCategories = ServiceCategory::join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
							->join('serviceSubcategories', 'serviceSubcategories.id', '=', 'category_services.subcategory_id')
							->select(['category_services.*','servicecategories.category_name', 'servicecategories.category_slug', 'serviceSubcategories.subcategory_name', 'serviceSubcategories.subcategory_slug'])
							->where('category_services.service_id','=',$serviceId)
							->get()->toArray();
		}
		return $serviceCategories;
	}

	public function addReview(Request $request)
	{
		$user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $txt_msg = trans('lang.txt_comments_success');
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
			$rating = $request->rating;
			$product_id = $request->product_id;
			$comments = $request->comments;
			$checkExistsOrder = OrdersDetails::where([['user_id','=',$user_id],['product_id','=',$product_id]])->get()->toArray();
			if(empty($checkExistsOrder))
			{
				$is_added = 0;
                $txt_msg = trans('errors.product_review_not_error');
                echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                exit;
			}

			$checkExists = ProductReview::where([['user_id','=',$user_id],['product_id','=',$product_id]])->get()->toArray();
			if(!empty($checkExists))
			{
				$is_added = 0;
                $txt_msg = trans('errors.product_review_error');
                echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                exit;
			}
			else
			{
				
				$currentDate = date('Y-m-d H:i:s');
				$arrInsert = [
								'product_id' => $product_id,
								'user_id' => $user_id,
								'comments' => $comments,
								'rating' => $rating,
								'is_approved' => '1',
								'created_at' => $currentDate,
								'updated_at' => $currentDate,
							 ];

				ProductReview::create($arrInsert);

				$getAllratings = ProductReview::where([['product_id','=',$product_id]]);

				$ratingCnt 	 = $getAllratings->count();
				$totalRating = $getAllratings->sum('rating');
						 
				$avgRating 	 = 0.00;
				if(!empty($ratingCnt))
				{
					$avgRating = ($totalRating / $ratingCnt);
					$avgRating = number_format($avgRating,2);
					$arrUpdate = ['rating' => $avgRating,'rating_count' => $ratingCnt];
					Products::where('id',$product_id)->update($arrUpdate);
				}
			}

		}
		else
        {
          $is_added = 0;
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
	}

	/*function to update product review*/
	public function updateProductReview(Request $request)
	{
		$user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $txt_msg = trans('lang.txt_comments_success');
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
			$rating = $request->rating;
			$product_id = $request->product_id;
			$comments = $request->comments;
			$id = $request->rating_id;
			

				$currentDate = date('Y-m-d H:i:s');
				$arrUpdateReview = [
								'user_id'    => $user_id,
								'comments'   => $comments,
								'rating'     => $rating,
								'is_approved' => '1',
								'updated_at' => $currentDate,
							 ];
							
			ProductReview::where('id', '=', $id)->update($arrUpdateReview);  

				$getAllratings = ProductReview::where([['product_id','=',$product_id]]);

				$ratingCnt 	 = $getAllratings->count();
				$totalRating = $getAllratings->sum('rating');
						 
				$avgRating 	 = 0.00;
				if(!empty($ratingCnt))
				{
					$avgRating = ($totalRating / $ratingCnt);
					$avgRating = number_format($avgRating,2);
					$arrUpdate = ['rating' => $avgRating,'rating_count' => $ratingCnt];
					Products::where('id',$product_id)->update($arrUpdate);
				}
			

		}
		else
        {
          $is_added = 0;
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
	}

	/*function to delete product review
	@parama:rating id
	*/
	public function deleteProductReview(Request $request){
		$id = $request->rating_id;
		if(empty($id)) {
           $txt_msg = trans('errors.something_went_wrong');
           $is_deleted = 0;
        }

        $id = base64_decode($id);

        $result = ProductReview::find($id);

        if (!empty($result)) {
           $res=ProductReview::where('id',$id)->delete();
            $txt_msg =trans('lang.review_del_success');
            $is_deleted = 1;
        } else {
        	$txt_msg = trans('errors.something_went_wrong');
            $is_deleted = 0;   
        }

        echo json_encode(array('status'=>$is_deleted,'msg'=>$txt_msg));
        exit;
	}
	/*function to add service review*/
	public function addServiceReview(Request $request)
	{
		$user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $txt_msg = trans('lang.txt_comments_success');
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
			$rating = $request->rating;
			$service_id = $request->service_id;
			$comments = $request->comments;
			
			$checkExistsServiceReq = ServiceRequest::where([['user_id','=',$user_id],['service_id','=',$service_id]])->get()->toArray();
			if(empty($checkExistsServiceReq))
			{
				$is_added = 0;
                $txt_msg = trans('errors.service_review_not_error');
                echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                exit;
			}

			$checkExists = ServiceReview::where([['user_id','=',$user_id],['service_id','=',$service_id]])->get()->toArray();
			if(!empty($checkExists))
			{
				$is_added = 0;
                $txt_msg = trans('errors.service_review_error');
                echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
                exit;
			}
			else
			{
				
				$currentDate = date('Y-m-d H:i:s');
				$arrInsert = [
								'service_id' => $service_id,
								'user_id' => $user_id,
								'comments' => $comments,
								'rating' => $rating,
								'is_approved' => '1',
								'created_at' => $currentDate,
								'updated_at' => $currentDate,
							 ];

				ServiceReview::create($arrInsert);

				$getAllratings = ServiceReview::where([['service_id','=',$service_id]]);

				$ratingCnt 	 = $getAllratings->count();
				$totalRating = $getAllratings->sum('rating');
						 
				$avgRating 	 = 0.00;
				if(!empty($ratingCnt))
				{
					$avgRating = ($totalRating / $ratingCnt);
					$avgRating = number_format($avgRating,2);
					$arrUpdate = ['rating' => $avgRating,'rating_count' => $ratingCnt];
					Services::where('id',$service_id)->update($arrUpdate);
				}
			}

		}
		else
        {
          $is_added = 0;
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
	}

	/*function to update product review*/
	public function updateServiceReview(Request $request)
	{
		$user_id = Auth::guard('user')->id();
        $is_added = 1;
        $is_login_err = 0;
        $txt_msg = trans('lang.txt_comments_success');
        if($user_id && Auth::guard('user')->getUser()->role_id == 1)
        {
			$rating = $request->rating;
			$comments = $request->comments;
			$id = $request->rating_id;
			$product_id = $request->product_id;

				$currentDate = date('Y-m-d H:i:s');
				$arrUpdateReview = [
								'user_id'    => $user_id,
								'comments'   => $comments,
								'rating'     => $rating,
								'is_approved' => '1',
								'updated_at' => $currentDate,
							 ];
							
			ServiceReview::where('id', '=', $id)->update($arrUpdateReview);  

				$getAllratings = ProductReview::where([['product_id','=',$product_id]]);

				$ratingCnt 	 = $getAllratings->count();
				$totalRating = $getAllratings->sum('rating');
						 
				$avgRating 	 = 0.00;
				if(!empty($ratingCnt))
				{
					$avgRating = ($totalRating / $ratingCnt);
					$avgRating = number_format($avgRating,2);
					$arrUpdate = ['rating' => $avgRating,'rating_count' => $ratingCnt];
					Services::where('id',$product_id)->update($arrUpdate);
				}
			

		}
		else
        {
          $is_added = 0;
          $is_login_err = 1;
          if($user_id && Auth::guard('user')->getUser()->role_id != 1)
          {
            $is_login_err = 0;
          }
          $txt_msg = trans('errors.login_buyer_required');
        }
        echo json_encode(array('status'=>$is_added,'msg'=>$txt_msg, 'is_login_err' => $is_login_err));
        exit;
	}

	/*function to delete product review
	@parama:rating id
	*/
	public function deleteServiceReview(Request $request){
		$id = $request->rating_id;
		if(empty($id)) {
           $txt_msg = trans('errors.something_went_wrong');
           $is_deleted = 0;
        }

        $id = base64_decode($id);

        $result = ServiceReview::find($id);

        if (!empty($result)) {
           $res=ServiceReview::where('id',$id)->delete();
            $txt_msg =trans('lang.record_delete');
            $is_deleted = 1;
        } else {
        	$txt_msg = trans('errors.something_went_wrong');
            $is_deleted = 0;   
        }

        echo json_encode(array('status'=>$is_deleted,'msg'=>$txt_msg));
        exit;
	}

	public function getCity(Request $request) {
     	if($request->get('query')){
	      $query = $request->get('query');

	      $data = DB::table('users')
	        ->where('users.city', 'LIKE', "%{$query}%")
	        ->where('users.is_shop_closed','=','0')
	        ->where('users.is_deleted','=','0')
	        ->groupBy('users.city')
	        ->get();
	        $output='';
	        if(count($data) > 0){
	          $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%">';
		      foreach($data as $row)
		      {
		       $output .= '
		       <li class="city_autocomplete"><a href="#">'.$row->city.'</a></li>
		       ';
		      }
		      $output .= '</ul>';
	        }
	      
	      echo $output;
	    }
    }

    /*
	*function to contact store
	*@param: seller_email,message
    */
   public function contactStore(Request $request) { 
      	
      	$success_msg = $err_msg='';
   		if(Auth::guard('user')->id()) {
   			$user_message = $request->user_message;
	   		$to_email = $request->user_email;
	   		$id = $request->seller_id;
	   		$seller_name = $request->seller_name;
           	$loginUser = UserMain::where('id',Auth::guard('user')->id())->first();
           	$from_email=$loginUser->email;
			$name = $loginUser->fname." ".$loginUser->lname;
			$currentDate = date('Y-m-d H:i:s');

			$arrInsert = [
				'seller_id' => $id,
				'email' => $from_email,
				'message' => $user_message,
				'created_at' => $currentDate,
				'updated_at' => $currentDate,
			 ];

			ContactStore::create($arrInsert);
	        // $arrMailData = ['name' => $name, 'email' => $from_email, 'user_message'=> $user_message,'seller_name' => $seller_name];

	        //     Mail::send('emails/contact_seller', $arrMailData, function($message) use ($to_email,$seller_name) {
	        //     $message->to($to_email, $seller_name)->subject
	        //         (trans('users.message_from_buyer_sub'));
	        //     $message->from( env('FROM_MAIL'),'Tijara');
	        // });
		//echo  env('FROM_MAIL');exit;


        
        $GetEmailContents = getEmailContents('Contact Seller');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];

        $contents = str_replace(['##SELLER_NAME##','##NAME##','##EMAIL##','##MESSAGE##','##SITE_URL##'],[$seller_name,$name,$from_email,$user_message,url('/')],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($to_email,$seller_name) {
            $message->to($to_email, $seller_name)->subject
	                (trans('users.message_from_buyer_sub'));
	        $message->from( env('FROM_MAIL'),'Tijara');
         });

	       $success_msg = trans('users.mail_sent_to_seller_alert');
        }else{
        	$err_msg = trans('users.please_login_alert');
        }
        return response()->json(['success'=>$success_msg,'error'=>$err_msg]);
   }

       /*
	*function to report product
	*@param: seller_email,message
    */
   public function reportProduct(Request $request) { 
      	
      	$success_msg = $err_msg='';
   		//if(Auth::guard('user')->id()) {
   			$user_message = $request->user_message;
   			$user_email = $request->user_email;
   			$product_id = $request->product_id;
   			$product_link = $request->product_link;
   			if(!empty($request->seller_name)){
   				$seller_name = $request->seller_name;
   			}else{
   				$seller_name = 'Anonymous';
   			}
   			
   			//$to_email = $request->seller_email;
	   		$to_email = env('ADMIN_EMAIL');
	   		//$id = $request->seller_id;
	   		
           	/*$loginUser = UserMain::where('id',Auth::guard('user')->id())->first();
           	$from_email=$loginUser->email;
			$name = $loginUser->fname." ".$loginUser->lname;*/
			$currentDate = date('Y-m-d H:i:s');

			$arrInsert = [
				'buyer_email'  => $user_email,
				'product_id'   => $product_id,
				'product_link' => $product_link,
				'message'      => $user_message,
				'seller_name'  => $seller_name,
				'created_at'   => $currentDate,
				'updated_at'   => $currentDate,
			 ];

			ReportProduct::create($arrInsert);

		$admin_email = env('ADMIN_EMAIL');
        $admin_name  = env('ADMIN_NAME');
        
        $GetEmailContents = getEmailContents('Buyer Product Report');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];

        $contents = str_replace(['##PRODUCT_NO##','##EMAIL##','##PRODUCT_LINK##','##MESSAGE##','##SITE_URL##'],[$product_id,$user_email,$product_link,$user_message,url('/')],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($admin_email,$admin_name,$subject) {
             $message->to($admin_email, $admin_name)->subject
                 ($subject);
             $message->from( env('FROM_MAIL'),'Tijara');
         });

	       $success_msg = trans('users.mail_sent_message');
      
        return response()->json(['success'=>$success_msg,'error'=>$err_msg]);
   }

          /*
	*function to report product
	*@param: seller_email,message
    */
   public function reportService(Request $request) { 
      	
      	$success_msg = $err_msg='';
   		//if(Auth::guard('user')->id()) {
   			$user_message = $request->user_message;
   			$user_email = $request->user_email;
   			$service_id = $request->service_id;
   			$service_link = $request->service_link;
   			$seller_name = $request->seller_name;
   			//$to_email = $request->seller_email;
	   		$to_email = env('ADMIN_EMAIL');
	   		//$id = $request->seller_id;
	   		
           	/*$loginUser = UserMain::where('id',Auth::guard('user')->id())->first();
           	$from_email=$loginUser->email;
			$name = $loginUser->fname." ".$loginUser->lname;*/
			$currentDate = date('Y-m-d H:i:s');

			$arrInsert = [
				'buyer_email'  => $user_email,
				'service_id'   => $service_id,
				'service_link' => $service_link,
				'message'      => $user_message,
				'seller_name'  => $seller_name,
				'created_at'   => $currentDate,
				'updated_at'   => $currentDate,
			 ];

			ReportService::create($arrInsert);

		$admin_email = env('ADMIN_EMAIL');
        $admin_name  = env('ADMIN_NAME');
        
        $GetEmailContents = getEmailContents('Buyer Service Report');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];

        $contents = str_replace(['##SERVICE_NO##','##EMAIL##','##SERVICE_LINK##','##MESSAGE##','##SITE_URL##'],[$service_id,$user_email,$service_link,$user_message,url('/')],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($admin_email,$admin_name,$subject) {
             $message->to($admin_email, $admin_name)->subject
                 ($subject);
             $message->from( env('FROM_MAIL'),'Tijara');
         });

	       $success_msg = trans('users.mail_sent_message');
      
        return response()->json(['success'=>$success_msg,'error'=>$err_msg]);
   }
   
   public function stripePackageSubscription() {
	//\DB::connection()->enableQueryLog();
		$currentDate = date('Y-m-d H:i:s');
		$ending_subscriptions = DB::table('user_packages')
		->join('packages', 'packages.id', '=', 'user_packages.package_id')
		->join('users', 'users.id', '=', 'user_packages.user_id')
		->where('packages.is_deleted','!=',1)
		->where('users.is_deleted','!=',1)
		->where('users.stripe_customer_id','!=','')
		->selectRaw('max(user_packages.id) as id')
		->groupBy('users.id')
		->get();
		//print_r(\DB::getQueryLog());
		//echo "<pre>";print_r($ending_subscriptions);exit;
		if(!empty($ending_subscriptions)) {  
				foreach($ending_subscriptions as $subscriptionId) {
					$subscription = DB::table('user_packages')
					->join('packages', 'packages.id', '=', 'user_packages.package_id')
					->join('users', 'users.id', '=', 'user_packages.user_id')
					->where('user_packages.id',$subscriptionId->id)
					->select('user_packages.id','user_packages.package_id','user_packages.end_date','packages.amount','user_packages.trial_end_date','user_packages.is_trial',
					'packages.validity_days','packages.recurring_payment',
					'user_packages.user_id','users.stripe_customer_id')
					->first();
					if($subscription->is_trial==1)
							$currentEndDate	=	$subscription->trial_end_date;
					else
							$currentEndDate	=	$subscription->end_date;
					if($currentEndDate < date('Y-m-d H:i:s') && $subscription->recurring_payment=='Yes') {
						Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
		
						$response = Stripe\Charge::create ([
							"amount" => ($subscription->amount*100),
							"currency" => "SEK",
							"customer" => $subscription->stripe_customer_id,
							"description" => "Package Subscription payment for UserId 
							#".$subscription->user_id.' & package Id #'.$subscription->package_id ,
							
						]);
						$package_recurrig = "logs/package_recurring_subscription.log";
					    $package_recurrig_file = Storage::path($package_recurrig);
					    $package_recurrig_file=fopen($package_recurrig_file,'a+');
					    fwrite($package_recurrig_file,json_encode($response));
					    fclose($package_recurrig_file);
						if($response->id){
							//$startDate 	 =	date('Y-m-d H:i:s', strtotime($currentDate . ' +1 day'));
							$startDate 	 =	date('Y-m-d H:i:s', strtotime($currentDate));
							$ExpiredDate = date('Y-m-d H:i:s', strtotime($startDate.'+'.$subscription->validity_days.' days'));
							$arrInsertPackage = [
								//'user_id'    => $subscription->user_id,
								//'package_id' => $subscription->package_id,
								'status'     => "active",
								'start_date' => $startDate,
								'end_date'   => $ExpiredDate,
								'order_id'   => $response->id,
								'is_trial'   => '0',
								'trial_start_date' => '0000-00-00 00:00:00',
								'trial_end_date'   =>'0000-00-00 00:00:00',
								'payment_status'   => 'CAPTURED',
								'payment_response' =>	json_encode($response)
							];
						UserPackages::where('user_packages.id',$subscription->id)->update($arrInsertPackage);
							
						}
					}
				}
		}
    }

    public function sellerAutoSuggest(Request $request)
	{

		$Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->city_filter,$request->search_string,'products',@$request->path);

		if(!empty($Sellers))
		{
			
			
			$output='';
			
			foreach($Sellers as $SellerId => $Seller)
			{
				

				$tmpSellerData = UserMain::join('seller_personal_page', 'users.id', '=', 'seller_personal_page.user_id')
    							
								->select('users.*','seller_personal_page.logo')
								->where('users.id',$SellerId)
								->where('users.role_id','=','2')
								->where('users.is_deleted','=','0')
                			    ->where('users.is_shop_closed','=','0')
                			    ->where('users.store_name', 'like','%' .$request['query']. '%')
								->first();//UserMain::where('id',$SellerId)->first()->toArray();
								if(!empty($tmpSellerData['logo'])){
									$logoPath = url('/').'/uploads/Seller/resized/'.$tmpSellerData['logo'];
								}else{
									$logoPath = url('/').'/uploads/Seller/resized/no-image.png';
								}
				
				if(!empty($tmpSellerData)> 0){
				$seller_name = $tmpSellerData['store_name'];
				$seller_name = str_replace( array( '\'', '"', 
				',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
				$seller_name = str_replace(" ", '-', $seller_name);
				$seller_name = strtolower($seller_name);
			
				if(!empty($tmpSellerData['store_name'])){
					$display_name = $tmpSellerData['store_name'];
				}
				if(!empty($Seller['product_cnt']) && $Seller['product_cnt'] > 0){
				//$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerProductListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($SellerId)]).'\';">'.$display_name.' ( '.$Seller['product_cnt'].' )</span></a></li>';
					$output .= '<li class="seller_autocomplete_search" onclick="location.href=\''.route('sellerProductListingByCategory',['seller_name' => $seller_name]).'\';"><img src="'.$logoPath.'" style="height:50px;width:50px;"><p style="padding:10px;">'.$display_name.'</p></li>';
				}
				}
			}
		}
		echo $output;
	}
}

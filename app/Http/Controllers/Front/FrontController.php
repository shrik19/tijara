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

use App\Models\UserMain;
use App\Models\ProductReview;
use App\Models\Orders;
use App\Models\OrdersDetails;
use Vinkla\Instagram\Instagram;


use IlluminateHttpRequest;
use AppHttpRequests;
use GuzzleHttp\Client;

use DB;
use Auth;
use Validator;
use Session;
use Flash;
use Mail;

class FrontController extends Controller
{

    /* function to display home page*/
    public function index() {

        /*get slider images*/
        $SliderDetails 		=  Sliders::select('id','title','sliderImage','description','link','status')->where('status','=','active')->orderBy('sequence_no')->get();
        $banner 			=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Home')->first();


       // $sliders = Sliders::with(['getImages'])->limit(9)->orderBy('id', 'DESC')->get()->toArray();
	    $site_details		= Settings::first();


  		//$client = new GuzzleHttpClient;
		$client = new Client();

	    $url = sprintf('https://www.instagram.com/%s/media', 'techbee.nashik');

        $response = $client->get($url);

        $items = json_decode((string) $response->getBody(), true)['items'];


        $data['pageTitle'] 		= 'Home';
        //$data['siteDetails'] 	= $site_details;
		$data['SliderDetails']  = $SliderDetails;
        $data['banner'] 	   	= $banner;
		$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';
		$data['ig_images']			= $items;


		$data['Categories']    	= $this->getCategorySubcategoryList()	;
		$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList()	;
		$data['TrendingProducts']= $this->getTrendingProducts();
		$data['PopularProducts']= $this->getPopularProducts();
        return view('Front/home', $data);
    }

	//get category & subcategory listings
	function getCategorySubcategoryList() {
		$Categories 		= Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('categories.id','categories.category_name','categories.category_slug','subcategories.subcategory_name','subcategories.subcategory_slug')
								->where('subcategories.status','=','active')
								->where('categories.status','=','active')
								->orderBy('categories.sequence_no')
								->orderBy('subcategories.sequence_no')
								->get()
								->toArray();
		$CategoriesArray	=	array();
		foreach($Categories as $category) {
			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

		return $CategoriesArray;
	}

	//get category & subcategory listings
	function getServiceCategorySubcategoryList() {
		$Categories 		= ServiceCategories::join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->select('servicecategories.id','servicecategories.category_name','servicecategories.category_slug','serviceSubcategories.subcategory_name','serviceSubcategories.subcategory_slug')
								->where('serviceSubcategories.status','=','active')
								->where('servicecategories.status','=','active')
								->orderBy('servicecategories.sequence_no')
								->orderBy('serviceSubcategories.sequence_no')
								->get()
								->toArray();
		$CategoriesArray	=	array();
		foreach($Categories as $category) {
			$CategoriesArray[$category['id']]['category_name']= $category['category_name'];
			$CategoriesArray[$category['id']]['category_slug']= $category['category_slug'];
			$CategoriesArray[$category['id']]['subcategory'][]= array('subcategory_name'=>$category['subcategory_name'],'subcategory_slug'=>$category['subcategory_slug']);
		}

		return $CategoriesArray;
	}

  //get seller listings
	function getSellersList($category_slug = '',$subcategory_slug = '', $price_filter = '',  $search_string = '',$productsServices='') {
    	$today          = date('Y-m-d H:i:s');
		$Sellers 		= UserMain::join('user_packages', 'users.id', '=', 'user_packages.user_id')
								->select('users.id','users.fname','users.lname','users.email','user_packages.package_id')
								->where('users.role_id','=','2')
								->where('users.status','=','active')
								->where('user_packages.status','=','active')
								->where('user_packages.start_date','<=', $today)
								->where('user_packages.end_date','>=', $today)
								->orderBy('users.id')
								->get()
								->toArray();

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
					$category 		=  Categories::select('id')->where('category_slug','=',$category_slug)->first();
					$sellerProducts	=	$sellerProducts->where('category_products.category_id','=',$category['id']);
				  }
				  if($subcategory_slug !='')
				  {
					$subcategory 		=  Subcategories::select('id')->where('subcategory_slug','=',$subcategory_slug)->first();
					$sellerProducts	=	$sellerProducts->where('category_products.subcategory_id','=',$subcategory['id']);
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
					$sellerServices	=	$sellerServices->where('category_services.category_id','=',$category['id']);
				  }
				  if($subcategory_slug !='')
				  {
					$subcategory 		=  ServiceSubcategories::select('id')->where('subcategory_slug','=',$subcategory_slug)->first();
					$sellerServices	=	$sellerServices->where('category_services.subcategory_id','=',$subcategory['id']);
				  }
				  
				  if($search_string !='')
				  {
					$sellerServices	=	$sellerServices->where('services.title','like','%'.$search_string.'%');
				  }

				  $sellerServices	=	$sellerServices->get();
				  
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
	function getPopularProducts($category_slug='',$subcategory_slug='') {
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
								->select(['products.*',DB::raw("DATEDIFF('".$currentDate."', products.sold_date) as sold_days"), DB::raw("DATEDIFF('".$currentDate."', products.created_at) as created_days") , DB::raw("count(orders_details.id) as totalOrderedProducts"),'variant_product.image','variant_product.price','variant_product.id as variant_id'])
								->where('products.status','=','active')
								->where('products.is_deleted','=','0')
								->where('categories.status','=','active')
							  	->where('subcategories.status','=','active')
								->where('users.status','=','active')
								->where(function($q) use ($currentDate) {

									$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
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
								->offset(0)->limit(config('constants.Products_limits'))->get();
		//dd(DB::getQueryLog());								
		if(count($PopularProducts)>0) {
			foreach($PopularProducts as $Product) {
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

        $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
        $Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

				$Product->product_link	=	$product_link;

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
				}
			}
		}

		//dd($PopularProducts);
								return $PopularProducts;
	}
	// get trending products
	function getTrendingProducts($category_slug='',$subcategory_slug='') {
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

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								})
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
                			  ->where('users.status','=','active')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  //->orderBy('variant_id', 'ASC')
							  ->groupBy('products.id')
							  ->offset(0)->limit(config('constants.Products_limits'))->get();
                //dd(DB::getQueryLog());
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
				$product_link	.=	'/'.$productCategories[0]['category_slug'];
			}
			
			if($subcategory_slug!='')
			{
				$product_link	.=	'/'.$subcategory_slug;
			}
			else 
			{
				$product_link	.=	'/'.$productCategories[0]['subcategory_slug'];
			}

			$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;
			$Product->product_link	=	$product_link;

			$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
			$Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

			$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
			foreach($variantProduct as $vp)
			{
				$Product->image = explode(",",$vp->image)[0];
				$Product->price = $vp->price;
				$Product->variant_id = $vp->variant_id;
			}

		}

			

			//dd($Product);
		}

		//dd($TrendingProducts);
							  return $TrendingProducts;
	}

	//function to get products list by provided parameters
	public function getProductsByParameter(Request $request) {
	
		$currentDate = date('Y-m-d H:i:s');
		$Products 			= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->leftJoin('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id'])
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where(function($q) use ($currentDate) {

								$q->where([["users.role_id",'=',"2"],['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([["users.role_id",'=',"1"],['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
								});
							//   ->when( "users.role_id" == 2 , function ($query) {
							// 		return $query->join('user_packages', 'user_packages.user_id', '=', 'users.id')->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
							//   }, function ($query) use ($currentDate) {
							// 	return $query->where([['is_sold','=','0'],[DB::raw("DATEDIFF('".$currentDate."', products.created_at)"),'<=', 30]])->orWhere([['is_sold','=','1'],[DB::raw("DATEDIFF('".$currentDate."',products.sold_date)"),'<=',7]]);
							// });
							  //
			if($request->category_slug !='') {
				$category 		=  Categories::select('id')->where('category_slug','=',$request->category_slug)->first();
				$Products	=	$Products->where('category_products.category_id','=',$category['id']);
				//$Products	=	$Products->where('categories.category_slug','=',$request->category_slug);
			}
			if($request->subcategory_slug !='') {
				$subcategory 		=  Subcategories::select('id')->where('subcategory_slug','=',$request->subcategory_slug)->first();
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
			if($request->search_string != '')
			{
				$Products	=	$Products->where('products.title', 'like', '%' . $request->search_string . '%');
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
					$Products	=	$Products->orderBy('variant_product.price', strtoupper($request->sort_order));
				}
				else if($request->sort_by == 'rating')
				{
					$Products	=	$Products->orderBy('products.rating', strtoupper($request->sort_order));
				}
			}
			else
			{
				$Products	=	$Products->orderBy('products.sort_order', 'ASC')->orderBy('variant_product.id', 'ASC');
			}

	  		$Products			=	$Products->groupBy('products.id');

		//$data['ProductsTotal'] = $Products->count();

		$Products 			= $Products->paginate(config('constants.Products_limits'));
		//dd($Products);
		//dd(DB::getQueryLog());
		if(count($Products)>0) {
			foreach($Products as $Product) {
				$product_link	=	url('/').'/product';
				if($request->category_slug!='')
				$product_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='')
				$product_link	.=	'/'.$request->subcategory_slug;

				$product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

				$Product->product_link	=	$product_link;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
				$Product->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

				$variantProduct  =	VariantProduct::select('image','price','variant_product.id as variant_id')->where('product_id',$Product->id)->where('id','=', $Product->variant_id)->orderBy('variant_id', 'ASC')->limit(1)->get();
				foreach($variantProduct as $vp)
				{
					$Product->image = explode(",",$vp->image)[0];
					$Product->price = $vp->price;
					$Product->variant_id = $vp->variant_id;
				}

			}
     		 $data['Products']	= $Products;
      //dd($Products);
		}
		else {
		$data['Products']	= 'Inga produkter tillgängliga.';
		}

		//dd(is_object($data['Products']));

		$Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->search_string,'products');
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
				
				$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerProductListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($SellerId)]).'\';">'.$Seller['name'].' ( '.$Seller['product_cnt'].' )</span></a></li>';
			}
			$sellerData .= '</ul>';
		}

		$productListing = view('Front/products_list', $data)->render();
			echo json_encode(array('products'=>$productListing,'sellers'=>$sellerData));
		exit;
		//return view('Front/products_list', $data);
	}
    /* function to display products page*/
    public function productListing($category_slug='',$subcategory_slug='',Request $request)
    {

        $data['pageTitle'] 	= 'Home';

		    $data['Categories'] = $this->getCategorySubcategoryList()	;

    		$data['PopularProducts']	= $this->getPopularProducts($category_slug,$subcategory_slug);
    		//$data['Products']			= $Products;
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

        //$data['Sellers'] = $this->getSellersList($category_slug,$subcategory_slug);

        return view('Front/products', $data);
	}
	
	/* function to display products page*/
    public function sellerProductListing($seller_name ='', $seller_id = '', $category_slug = null, $subcategory_slug= null, Request $request)
    {
        $data['pageTitle'] 	= 'Sellers Products';
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		$data['PopularProducts']	= $this->getPopularProducts($category_slug,$subcategory_slug);
		$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList()	;
    	$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';
		$data['link_seller_name']		=	$seller_name;
		$id = base64_decode($seller_id);
		$data['seller_id']			=	$id;
		
		$Seller = UserMain::where('id',$id)->first()->toArray();
		$data['seller_name']		=	$Seller['fname'].' '.$Seller['lname'];
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
		}
		
    	if($category_slug!='')
    		$data['category_slug']	= $category_slug;

    	if($subcategory_slug!='')
    		$data['subcategory_slug']	= $subcategory_slug;

		$data['is_seller'] = 1;
        return view('Front/seller-products', $data);
    }

	//function for product details page

	public function productDetails($first_parameter='',$second_parameter='',$third_parameter='') 
	{
		$Products 			=  Products::join('category_products', 'products.id', '=', 'category_products.product_id')
										->join('categories', 'categories.id', '=', 'category_products.category_id')
										->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
										->select(['products.*','products.id as product_id'])
										->where('products.status','=','active')
										->where('products.is_deleted','=','0')
										->where('categories.status','=','active')
										->where('subcategories.status','=','active');
										
							  			
		// $Products 			=  Products::join('variant_product', 'products.id', '=', 'variant_product.product_id')
		// 								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
		// 								->join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
		// 								->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
		// 								->join('category_products', 'products.id', '=', 'category_products.product_id')
		// 								->join('categories', 'categories.id', '=', 'category_products.category_id')
		// 								->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
		// 								->select(['products.*','variant_product.*','variant_product_attribute.*','products.id as product_id','attributes.name','attributes.type','attributes_values.attribute_values','variant_product_attribute.attribute_value_id'])
		// 					  			->where('products.status','=','active')
		// 								->where('categories.status','=','active')
		// 					  			->where('subcategories.status','=','active');

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

			$Products		=	$Products->where('categories.category_slug','=',$category_slug);
		}
		if(isset($subcategory_slug) && $subcategory_slug!='') {
			$Products		=	$Products->where('subcategories.subcategory_slug','=',$subcategory_slug);
		}
		if(isset($product_slug) && $product_slug!='') {

			$product_parts	=	explode('-P-',$product_slug);
			if(!isset($product_parts[0]) || !isset($product_parts[1]))
				return redirect(route('AllproductListing'));
			$Products		=	$Products->where('products.product_slug','=',$product_parts[0])->where('products.product_code','=',$product_parts[1]);
		}
		$Products			=	$Products->get();// ->groupBy('products.id')
		

		if(isset($product_slug) && count($Products)<=0) {
			$product_parts	=	explode('-P-',$product_slug);
			$Products		=	Products::where('products.status','=','active');
			if(isset($product_parts[0]))
				$Products	=	$Products->where('products.product_slug','=',$product_parts[0]);
			if(isset($product_parts[1]))
				$Products	=	$Products->Orwhere('products.product_code','=',$product_parts[1]);

			$Products		=	$Products->first();
			return redirect('/product/'.$Products->product_slug.'-P-'.$Products->product_code);
			if(count($Products)<=0)
			return redirect(route('AllproductListing'));
		}

		$variantData		=	$ProductImages	=	$ProductAttributes	=	array();
		
		$Product = $Products[0];

		$ProductVariants = VariantProduct::where('product_id','=',$Product->id)->orderBy('id','ASC')->get();
		
		foreach($ProductVariants as $variant)
		{
			$variantData[$variant->id]['id']			=	$variant->id;
			$variantData[$variant->id]['sku']			=	$variant->sku;
			$variantData[$variant->id]['price']			=	$variant->price;
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
		//dd($ProductAttributes);
		// foreach($Products as $Product) 
		// {
			
		// 	$variantData[$Product->variant_id]['sku']			=	$Product->sku;
		// 	$variantData[$Product->variant_id]['price']			=	$Product->price;
		// 	$variantData[$Product->variant_id]['weight']		=	$Product->weight;
		// 	$variantData[$Product->variant_id]['quantity']		=	$Product->quantity;

		// 	$variantData[$Product->variant_id]['attributes'][]	=	array('attribute_id'=>$Product->attribute_id,'attribute_name'=>$Product->name,'attribute_type'=>$Product->type,'attribute_value'=>$Product->attribute_values,'attribute_value_id'=>$Product->attribute_value_id);
		// 	$ProductImages[$Product->variant_id]['image']		=	$Product->image;

		// }

		// foreach($variantData as $variant_id=>$variant) {
		// 	foreach($variant['attributes'] as $val) {
		// 		$ProductAttributes[$val['attribute_id']]['attribute_name']=$val['attribute_name'];
		// 		$ProductAttributes[$val['attribute_id']]['attribute_type']=$val['attribute_type'];
		// 		$ProductAttributes[$val['attribute_id']]['attribute_values'][$val['attribute_value_id']]=$val['attribute_value'];
        // 		$ProductAttributes[$val['attribute_id']]['variant_values'][$val['attribute_value_id']]=$variant_id;
		// 	}
		// }
		//echo'<pre>';print_r($variantData);echo'<pre>';print_r($ProductAttributes);exit;
		$data['Categories'] = $this->getCategorySubcategoryList()	;

		$data['PopularProducts']	= $this->getPopularProducts();
		$data['Product']			= $Product;
		$data['variantData']		= $variantData;
		$data['ProductAttributes']	= $ProductAttributes;

		$tmpSellerData = UserMain::where('id',$Product['user_id'])->first()->toArray();
		$seller_name = $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
		$data['seller_name'] = $seller_name;

		$seller_name = str_replace( array( '\'', '"', 
		',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
		$seller_name = str_replace(" ", '-', $seller_name);
		$seller_name = strtolower($seller_name);
		
		$sellerLink = route('sellerProductListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($Product['user_id'])]);
		$data['seller_link'] = $sellerLink;
		
		//dd($data['variantData']);
        return view('Front/product_details', $data);
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

  function getProductCategories($productId)
  {
    $productCategories = [];
    if(!empty($productId))
    {
      $productCategories = ProductCategory::join('categories', 'categories.id', '=', 'category_products.category_id')
                         ->join('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')
                         ->select(['category_products.*','categories.category_name', 'categories.category_slug', 'subcategories.subcategory_name', 'subcategories.subcategory_slug'])
                         ->where('category_products.product_id','=',$productId)
                         ->get()->toArray();
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
											->select('attributes.name as attribute_name','attributes.type as attribute_type','attributes_values.attribute_values','variant_product_attribute.*','variant_product.*')
											->where([['variant_product_attribute.attribute_id','=',$attribute_id],['attribute_value_id','=',$attribute_value],['variant_product_attribute.product_id','=',$product_id]])->get()->toArray();
	
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
	
		DB::enableQueryLog();
		$currentDate = date('Y-m-d H:i:s');
		$Services 			= Services::join('category_services', 'services.id', '=', 'category_services.service_id')
							  ->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
							  ->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')							  				  
							  ->join('users', 'services.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->select(['services.*','servicecategories.category_name'])
							  ->where('services.status','=','active')
							  ->where('services.is_deleted','=','0')
							  ->where('servicecategories.status','=','active')
							  ->where('serviceSubcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
			if($request->category_slug !='') {
				$category 		=  ServiceCategories::select('id')->where('category_slug','=',$request->category_slug)->first();
				$Services	=	$Services->where('category_services.category_id','=',$category['id']);
				//$Services	=	$Services->where('servicecategories.category_slug','=',$request->category_slug);
			}
			if($request->subcategory_slug !='') {
				$subcategory 		=  ServiceSubcategories::select('id')->where('subcategory_slug','=',$request->subcategory_slug)->first();
				$Services	=	$Services->where('category_services.subcategory_id','=',$subcategory['id']);
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

			if($request->sort_order != '' && $request->sort_by != '')
			{
				if($request->sort_by == 'name')
				{
					$Services	=	$Services->orderBy('services.title', strtoupper($request->sort_order));
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
		//print_r(DB::getQueryLog());
		//exit;
		if(count($Services)>0) {
			foreach($Services as $Service) {
				$service_link	=	url('/').'/service';
				if($request->category_slug!='')
				$service_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='')
				$service_link	.=	'/'.$request->subcategory_slug;

				$service_link	.=	'/'.$Service->service_slug.'-S-'.$Service->service_code;

				$Service->service_link	=	$service_link;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Service->user_id)->first()->toArray();
				$Service->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

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

		$Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->search_string,'services');
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
				
				$sellerData .= '<li><a href="javascript:void(0)"><input onclick="selectSellers();" type="checkbox" name="seller" value="'.$SellerId.'" class="sellerList" '.$strChecked.'>&nbsp;&nbsp;<span style="cursor:pointer;" onclick="location.href=\''.route('sellerServiceListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($SellerId)]).'\';">'.$Seller['name'].' ( '.$Seller['service_cnt'].' )</span></a></li>';
			}
			$sellerData .= '</ul>';
		}

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
		$tmpSellerData = UserMain::where('id',$Service['user_id'])->first()->toArray();
		$seller_name = $tmpSellerData['fname'].' '.$tmpSellerData['lname'];
		$data['seller_name'] = $seller_name;

		$seller_name = str_replace( array( '\'', '"', 
		',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
		$seller_name = str_replace(" ", '-', $seller_name);
		$seller_name = strtolower($seller_name);
		
		$sellerLink = route('sellerServiceListingByCategory',['seller_name' => $seller_name, 'seller_id' => base64_encode($Service['user_id'])]);
		$data['seller_link'] = $sellerLink;
		
		//dd($data['variantData']);
        return view('Front/service_details', $data);
    }
	
	//get popular services
	function getPopularServices($category_slug='',$subcategory_slug='') {
		$currentDate = date('Y-m-d H:i:s');
		$PopularServices 	= Services::join('service_requests', 'services.id', '=', 'service_requests.service_id')								
								->join('category_services', 'services.id', '=', 'category_services.service_id')
								->join('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
								->join('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
								->join('users', 'services.user_id', '=', 'users.id')
								->join('user_packages', 'user_packages.user_id', '=', 'users.id')
								->select(['services.*',DB::raw("count(service_requests.id) as totalOrderedServices")])
								->where('services.status','=','active')
								->where('services.is_deleted','=','0')
								->where('servicecategories.status','=','active')
							  	->where('serviceSubcategories.status','=','active')
								->where('users.status','=','active')
								->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
								->orderBy('totalOrderedServices', 'DESC')
								->groupBy('services.id')
								->offset(0)->limit(config('constants.Services_limits'))->get();

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

				$service_link	.=	$Service->service_slug.'-S-'.$Service->service_code;

				$SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Service->user_id)->first()->toArray();
				$Service->seller	=	$SellerData['fname'].' '.$SellerData['lname'];

				$Service->service_link	=	$service_link;

				$Service->image = explode(",",$Service->images)[0];
			}
		}
								return $PopularServices;
	}
	/* function to display services page*/
    public function sellerServiceListing($seller_name ='', $seller_id = '', $category_slug = null, $subcategory_slug= null, Request $request)
    {
        $data['pageTitle'] 	= 'Sellers Services';
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		$data['PopularServices']	= $this->getPopularServices($category_slug,$subcategory_slug);
		$data['ServiceCategories']	= $this->getServiceCategorySubcategoryList()	;
    	$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';
		$data['link_seller_name']		=	$seller_name;
		$id = base64_decode($seller_id);
		$data['seller_id']			=	$id;
		
		$Seller = UserMain::where('id',$id)->first()->toArray();
		$data['seller_name']		=	$Seller['fname'].' '.$Seller['lname'];
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
		}
		
    	if($category_slug!='')
    		$data['category_slug']	= $category_slug;

    	if($subcategory_slug!='')
    		$data['subcategory_slug']	= $subcategory_slug;

		$data['is_seller'] = 1;
        return view('Front/seller-services', $data);
    }

	/*function to send a service request*/
    public function sendServiceRequest(Request $request)
    {
        $service_time	=	$request->input('service_time');//str_replace('/','-',$request->input('service_time'));
        $request_id	=	DB::table('service_requests')->insertGetId([
            'message' => $request->input('message'),
            'user_id' => Auth::guard('user')->id(),
			'service_id'=>$request->input('service_id'),
			'service_time'=>date('Y-m-d H:i:s',strtotime($service_time)),
            'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
        ]);
		
		$service_request = Services::join('users', 'users.id', '=', 'services.user_id')							
							->where('services.id', '=', $request->input('service_id'))->first();
        
       
		$user = DB::table('users')->where('id', '=', Auth::guard('user')->id())->first();
		$customername = $user->fname;

		

		$service	=	$service_request->title;
		$email		=	$service_request->email;
		$servicemessage	=	$request->input('message');
		$service_time	=	date('Y-m-d H:i:s',strtotime($service_time));
		$seller	=	$service_request->fname;
		//echo'<pre>';print_r($request);exit;
        $arrMailData = ['customername' => $customername, 'seller' => $seller, 'service' => $service,
		'service_time'=>$service_time, 'servicemessage' => $servicemessage,'phone_number'=>$user->phone_number];

		Mail::send('emails/service_request_success_seller', $arrMailData, function($message) use ($email,$seller) {
			$message->to($email, $seller)->subject
				(trans('lang.newRequestReceived'));
			$message->from(env('MAIL_USERNAME'),env('APP_NAME'));
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
}

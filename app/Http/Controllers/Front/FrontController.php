<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
use App\Models\Banner;
use App\Models\Categories;
use App\Models\Subcategories;

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
use App\Models\SellerPersonalPage;

use App\Models\UserMain;

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

        $data['pageTitle'] 		= 'Home';
        //$data['siteDetails'] 	= $site_details;
		$data['SliderDetails']  = $SliderDetails;
        $data['banner'] 	   	= $banner;
		$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';

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
		$Categories 		= ServiceCategories::join('servicesubcategories', 'servicecategories.id', '=', 'servicesubcategories.category_id')
								->select('servicecategories.id','servicecategories.category_name','servicecategories.category_slug','servicesubcategories.subcategory_name','servicesubcategories.subcategory_slug')
								->where('servicesubcategories.status','=','active')
								->where('servicecategories.status','=','active')
								->orderBy('servicecategories.sequence_no')
								->orderBy('servicesubcategories.sequence_no')
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
	function getSellersList($category_slug = '',$subcategory_slug = '', $price_filter = '',  $search_string = '') {
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

		return $SellersArray;
	}

	//get popular products
	function getPopularProducts($category_slug='',$subcategory_slug='') {
		$currentDate = date('Y-m-d H:i:s');
		$PopularProducts 	= Products::join('orders_details', 'products.id', '=', 'orders_details.product_id')
								->join('variant_product', 'products.id', '=', 'variant_product.product_id')
								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
								->join('category_products', 'products.id', '=', 'category_products.product_id')
								->join('categories', 'categories.id', '=', 'category_products.category_id')
								->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->join('users', 'products.user_id', '=', 'users.id')
								->join('user_packages', 'user_packages.user_id', '=', 'users.id')
								->select(['products.*',DB::raw("count(orders_details.id) as totalOrderedProducts"),'variant_product.image','variant_product.price','variant_product.id as variant_id'])
								->where('products.status','=','active')
								->where('products.is_deleted','=','0')
								->where('categories.status','=','active')
							  	->where('subcategories.status','=','active')
								->where('users.status','=','active')
								->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
								->orderBy('totalOrderedProducts', 'DESC')
								->orderBy('variant_product.id', 'ASC')
								->groupBy('products.id')
								->offset(0)->limit(config('constants.Products_limits'))->get();

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
								return $PopularProducts;
	}
	// get trending products
	function getTrendingProducts($category_slug='',$subcategory_slug='') {
	//DB::enableQueryLog();
		$currentDate = date('Y-m-d H:i:s');
		$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id']) //
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
                			  ->where('users.status','=','active')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]])
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
	public function getProductsyParameter(Request $request) {
	//DB::enableQueryLog();
		$currentDate = date('Y-m-d H:i:s');
		$Products 			= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
							  ->join('users', 'products.user_id', '=', 'users.id')
							  ->join('user_packages', 'user_packages.user_id', '=', 'users.id')
							  ->select(['products.*','categories.category_name','variant_product.image','variant_product.price','variant_product.id as variant_id'])
							  ->where('products.status','=','active')
							  ->where('products.is_deleted','=','0')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->where('users.status','=','active')
							  ->where([['user_packages.status','=','active'],['start_date','<=',$currentDate],['end_date','>=',$currentDate]]);
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

    $Sellers = $this->getSellersList($request->category_slug,$request->subcategory_slug,$request->price_filter,$request->search_string);
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

}

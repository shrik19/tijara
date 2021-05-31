<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
use App\Models\Banner;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Settings;

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
		$data['Categories']    	= $this->getCategorySubcategoryList()	;
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
	//get popular products
	function getPopularProducts() {
			
		$PopularProducts 	= Products::join('order_products', 'products.id', '=', 'order_products.product_id')
								->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							->select(['products.*',DB::raw("count(order_products.id) as totalOrderedProducts"),'variant_product.image'])
							->where('products.status','=','active')
							->orderBy('totalOrderedProducts', 'DESC')
							->orderBy('variant_product.id', 'ASC')
							->groupBy('products.id')
							->offset(0)->limit(6)->get(); 
			return $PopularProducts;
	}
	// get trending products
	function getTrendingProducts() {
		$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->select(['products.*','categories.category_name','variant_product.image'])
							  ->where('products.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  ->orderBy('variant_product.id', 'ASC')
							  ->groupBy('products.id')
							  ->offset(0)->limit(6)->get()->toarray(); 
			return $TrendingProducts; 
	}

    /* function to display home page*/
    public function productListing($category_slug='',$subcategory_slug='') {
        
						
		$Products 			= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')								
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->select(['products.*','categories.category_name','variant_product.image'])
							  ->where('products.status','=','active')							 
							  ->orderBy('products.sort_order', 'ASC');

			if($category_slug!='') {
				$Products	=	$Products->where('categories.category_slug','=',$category_slug);
			}
			if($subcategory_slug!='') {
				$Products	=	$Products->where('subcategories.subcategory_slug','=',$subcategory_slug);
			}				  
		$Products			=	$Products->groupBy('products.id')
							  ->get()->toarray();   
       // $sliders = Sliders::with(['getImages'])->limit(9)->orderBy('id', 'DESC')->get()->toArray();
	    $site_details		= Settings::first();
	   
        $data['pageTitle'] 	= 'Home';
        
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		
		$data['PopularProducts']= $this->getPopularProducts();
		$data['Products']	= $Products;
		
        return view('Front/products', $data);
    }

	//function for product details page

    public function productDetails($product_slug) {
        
						
		$Products 			=  Products::join('variant_product', 'products.id', '=', 'variant_product.product_id')
										->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
										->join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
										->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
										->select(['products.*','variant_product.*','variant_product_attribute.*','products.id as product_id','attributes.name','attributes_values.attribute_values'])
							  			->where('products.status','=','active')	;
		$Products			=	$Products->where('products.product_slug','=',$product_slug);
							  
		$Products			=	$Products->get();   
       
		$variantData		=	$ProductImages	=	array();
		foreach($Products as $Product) {

			$variantData[$Product->variant_id]['sku']			=	$Product->sku;
			$variantData[$Product->variant_id]['price']			=	$Product->price;	
			$variantData[$Product->variant_id]['weight']		=	$Product->weight;	
			$variantData[$Product->variant_id]['quantity']		=	$Product->quantity;	

			$variantData[$Product->variant_id]['attributes'][]	=	array('attribute_name'=>$Product->name,'attribute_value'=>$Product->attribute_values);
			$ProductImages[$Product->variant_id]['image']		=	$Product->image;

		}
		//
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		
		$data['PopularProducts']= $this->getPopularProducts();
		$data['Product']		= $Products[0];
		$data['variantData']	= $variantData;
		$data['ProductImages']	= $ProductImages;
		
        return view('Front/product_details', $data);
    }
    
   
}

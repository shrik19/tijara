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

class HomeController extends Controller
{
    
    /* function to display home page*/
    public function index() {
        
        /*get slider images*/
        $SliderDetails 		=  Sliders::select('id','title','sliderImage','description','link','status')->where('status','=','active')->orderBy('sequence_no')->get();   
        $banner 			=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Home')->first();   
		$Categories 		= Categories::join('subcategories', 'categories.id', '=', 'subcategories.category_id')
								->select('categories.category_name','subcategories.subcategory_name')->where('subcategories.status','=','active')
								->where('categories.status','=','active')->orderBy('categories.sequence_no')->orderBy('categories.sequence_no')->get()->toArray();  
		$CategoriesArray	=	array();
		foreach($Categories as $category) {
			$CategoriesArray[$category['category_name']][]= $category['subcategory_name'];
		}						
		$PopularProducts 	= Products::join('order_products', 'products.id', '=', 'order_products.product_id')
		                        ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->select(['products.*',DB::raw("count(order_products.id) as totalOrderedProducts"),'variant_product.image'])
							  ->where('products.status','=','active')
							  ->orderBy('totalOrderedProducts', 'DESC')
							  ->orderBy('variant_product.id', 'ASC')
							  ->groupBy('products.id')
							  ->offset(0)->limit(6)->get();   
		
		$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->select(['products.*','categories.category_name','variant_product.image'])
							  ->where('products.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  ->orderBy('variant_product.id', 'ASC')
							  ->groupBy('products.id')
							  ->offset(0)->limit(6)->get()->toarray();   
       // $sliders = Sliders::with(['getImages'])->limit(9)->orderBy('id', 'DESC')->get()->toArray();
	    $site_details		= Settings::first();
	   
        $data['pageTitle'] 		= 'Home';
        //$data['siteDetails'] 	= $site_details;
		$data['SliderDetails']  = $SliderDetails;
        $data['banner'] 	   	= $banner;
		$data['Categories']    	= $CategoriesArray	;
		$data['TrendingProducts']= $TrendingProducts;
		$data['PopularProducts']= $PopularProducts;
        return view('Front/home', $data);
    }

    
    
   
}

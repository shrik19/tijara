<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
use App\Models\Banner;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Settings;
use App\Models\ VariantProductAttribute;

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
	function getPopularProducts(Request $request) {
			
		$PopularProducts 	= Products::join('order_products', 'products.id', '=', 'order_products.product_id')
								->join('variant_product', 'products.id', '=', 'variant_product.product_id')
								->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id') 
								->join('category_products', 'products.id', '=', 'category_products.product_id')
								->join('categories', 'categories.id', '=', 'category_products.category_id')
								->join('subcategories', 'categories.id', '=', 'subcategories.category_id')	
								->select(['products.*',DB::raw("count(order_products.id) as totalOrderedProducts"),'variant_product.image'])
								->where('products.status','=','active')
								->where('categories.status','=','active')
							  	->where('subcategories.status','=','active')
								->orderBy('totalOrderedProducts', 'DESC')
								->orderBy('variant_product.id', 'ASC')
								->groupBy('products.id')
								->offset(0)->limit(config('constants.Products_limits'))->get(); 
		
		if(count($PopularProducts)>0) {
			foreach($PopularProducts as $Product) {

				$product_link	=	url('/').'product';
				if($request->category_slug!='') 
				$product_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='') 
				$product_link	.=	'/'.$request->subcategory_slug;

				$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;

				$Product->product_link	=	$product_link;
			}
		}						
								return $PopularProducts;
	}
	// get trending products
	function getTrendingProducts(Request $request) {
		$TrendingProducts 	= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')			
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id') 
							  ->select(['products.*','categories.category_name','variant_product.image'])
							  ->where('products.status','=','active')
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')
							  ->orderBy('products.id', 'DESC')
							  ->orderBy('variant_product.id', 'ASC')
							  ->groupBy('products.id')
							  ->offset(0)->limit(config('constants.Products_limits'))->get(); 
		if(count($TrendingProducts)>0) {
			foreach($TrendingProducts as $Product) {
				$product_link	=	url('/').'product';
				if($request->category_slug!='') 
				$product_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='') 
				$product_link	.=	'/'.$request->subcategory_slug;

				$product_link	.=	$Product->product_slug.'-P-'.$Product->product_code;

				$Product->product_link	=	$product_link;
			}
		}
							  return $TrendingProducts; 
	}

	//function to get products list by provided parameters
	public function getProductsyParameter(Request $request) {
			
		$Products 			= Products::join('category_products', 'products.id', '=', 'category_products.product_id')
							  ->join('categories', 'categories.id', '=', 'category_products.category_id')
							  ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')								
							  ->join('variant_product', 'products.id', '=', 'variant_product.product_id')
							  ->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id') 
							  ->select(['products.*','categories.category_name','variant_product.image'])
							  ->where('products.status','=','active')	
							  ->where('categories.status','=','active')
							  ->where('subcategories.status','=','active')						 
							  ->orderBy('products.sort_order', 'ASC')
							  ->orderBy('variant_product.id', 'ASC');
			if($request->category_slug!='') {
				$Products	=	$Products->where('categories.category_slug','=',$request->category_slug);
			}
			if($request->subcategory_slug!='') {
				$Products	=	$Products->where('subcategories.subcategory_slug','=',$request->subcategory_slug);
			}				  
		$Products			=	$Products->groupBy('products.id');   

		//$data['ProductsTotal'] = $Products->count();

		$Products 			= $Products->paginate(config('constants.Products_limits'));
		
		if(count($Products)>0) {
			foreach($Products as $Product) {
				$product_link	=	url('/').'/product';
				if($request->category_slug!='') 
				$product_link	.=	'/'.$request->category_slug;
				if($request->subcategory_slug!='') 
				$product_link	.=	'/'.$request->subcategory_slug;

				$product_link	.=	'/'.$Product->product_slug.'-P-'.$Product->product_code;

				$Product->product_link	=	$product_link;
			}
		}
		
		$data['Products']	= $Products; 
		//echo json_encode($data);
		return view('Front/products_list', $data);
	}
    /* function to display products page*/
    public function productListing($category_slug='',$subcategory_slug='') {
        
					
        $data['pageTitle'] 	= 'Home';
        
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		
		$data['PopularProducts']	= $this->getPopularProducts();
		//$data['Products']			= $Products;
		
		$data['category_slug']		=	'';
		$data['subcategory_slug']	=	'';
		
		if($category_slug!='')
			$data['category_slug']	= $category_slug;

		
			if($subcategory_slug!='')
			$data['subcategory_slug']	= $subcategory_slug;

        return view('Front/products', $data);
    }

	//function for product details page

    public function productDetails($first_parameter='',$second_parameter='',$third_parameter='') {
        
						
		$Products 			=  Products::join('variant_product', 'products.id', '=', 'variant_product.product_id')
										->join('variant_product_attribute', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
										->join('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
										->join('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
										->join('category_products', 'products.id', '=', 'category_products.product_id')
										->join('categories', 'categories.id', '=', 'category_products.category_id')
										->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
										->select(['products.*','variant_product.*','variant_product_attribute.*','products.id as product_id','attributes.name','attributes.type','attributes_values.attribute_values','variant_product_attribute.attribute_value_id'])
							  			->where('products.status','=','active')
										->where('categories.status','=','active')
							  			->where('subcategories.status','=','active');
		
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
		$Products			=	$Products->get();   
       
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
		foreach($Products as $Product) {

			$variantData[$Product->variant_id]['sku']			=	$Product->sku;
			$variantData[$Product->variant_id]['price']			=	$Product->price;	
			$variantData[$Product->variant_id]['weight']		=	$Product->weight;	
			$variantData[$Product->variant_id]['quantity']		=	$Product->quantity;	

			$variantData[$Product->variant_id]['attributes'][]	=	array('attribute_id'=>$Product->attribute_id,'attribute_name'=>$Product->name,'attribute_type'=>$Product->type,'attribute_value'=>$Product->attribute_values,'attribute_value_id'=>$Product->attribute_value_id);
			$ProductImages[$Product->variant_id]['image']		=	$Product->image;
			
		}
	
		foreach($variantData as $variant_id=>$variant) {
			foreach($variant['attributes'] as $val) {
				$ProductAttributes[$val['attribute_id']]['attribute_name']=$val['attribute_name'];
				$ProductAttributes[$val['attribute_id']]['attribute_type']=$val['attribute_type'];
				$ProductAttributes[$val['attribute_id']]['attribute_values'][$val['attribute_value_id']]=$val['attribute_value'];
			}
		}
		//echo'<pre>';print_r($variantData);echo'<pre>';print_r($ProductAttributes);exit;
		$data['Categories'] = $this->getCategorySubcategoryList()	;
		
		$data['PopularProducts']= $this->getPopularProducts();
		$data['Product']		= $Products[0];
		$data['variantData']	= $variantData;
		$data['ProductAttributes']	= $ProductAttributes;
		$data['ProductImages']	= $ProductImages;
		
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
   
}

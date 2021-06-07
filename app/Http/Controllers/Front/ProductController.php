<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\UserMain;

use App\Models\Products;

use App\Models\City;

use App\Models\ProductImages;

use App\Models\UserPackages;

use App\Models\Categories;

use App\Models\Subcategories;

use App\Models\ProductCategory;

use App\Models\Attributes;

use App\Models\ AttributesValues;

use App\Models\ VariantProductAttribute;

use App\Models\ VariantProduct;

use App\Models\Package;


/*Uses*/

use Auth;

use Session;

use flash;

use Validator;

use DB;



class ProductController extends Controller

{

    /*

	 * Define abjects of models, services.

	 */

    function __construct() {

    
    	//$data['CurrentUser']   =   UserMain::where('id',Auth::guard('user')->id())->first();

    }



    /**

     * Show list of records for products.

     * @return [array] [record array]

     */

    public function index() {
        $data = [];
        $data['subscribedError']   =    '';
        if(!Auth::guard('user')->id()) {
            return redirect(route('frontLogin'));
        }
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) 
            {
               
                $data['subscribedError'] = trans('messages.products_with_active_subscription');
                //return redirect(route('frontSellerPackages'));
            }
        }
        else {
            $checkProductExistOfBuyer   =   Products::where('user_id',Auth::guard('user')->id())->first();
            if(!empty($checkProductExistOfBuyer)) {
                return redirect(route('frontProductEdit',$checkProductExistOfBuyer->id));
            }
            else
                return redirect(route('frontProductCreate'));
        }

        $data['pageTitle']              = trans('lang.products_title');

        $data['current_module_name']    = trans('lang.products_title');

        $data['module_name']            = trans('lang.products_title');

        $data['module_url']             = route('manageFrontProducts');

        $data['recordsTotal']           = 0;

        $data['currentModule']          = '';

		$CategoriesAndSubcategories		= Products::Leftjoin('category_products', 'products.id', '=', 'category_products.product_id')
											->Leftjoin('categories', 'categories.id', '=', 'category_products.category_id')
											->Leftjoin('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')
											->select(['categories.category_name','subcategories.id','subcategories.category_id','subcategories.subcategory_name'])
											->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id())
											->groupBy('subcategories.id')->orderBy('categories.sequence_no')->get();
		
		$categoriesArray				=	array();
		foreach($CategoriesAndSubcategories as $category) {
			$categoriesArray[$category->category_id]['mainCategory']	=	$category->category_name;
			
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		
		$categoriesHtml					=	'<option value="">'.trans('lang.category_label').'</option>';
		
		$subCategoriesHtml				=	'<option value="">'.trans('lang.subcategory_label').'</option>';
		
		foreach($categoriesArray as $category_id=>$category) {
			if($category['mainCategory']!='')
			$categoriesHtml				.=	'<option value="'.$category_id.'">'.$category['mainCategory'].'</option>';
			
			foreach($category['subcategories'] as $subcategory_id=>$subcategory) {
				if($subcategory!='')
				$subCategoriesHtml		.=	'<option class="subcatclass" style="display:none;" id="subcat'.$category_id.'" value="'.$subcategory_id.'">'.$subcategory.'</option>';
			}
		}
		
		$data['categoriesHtml']        	= $categoriesHtml;
		$data['subCategoriesHtml']     	= $subCategoriesHtml;
		
		
        return view('Front/Products/index', $data);

    }

    

    
    /**

     * [getRecords for product list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) {

	if(!empty($request['category']) || !empty($request['subcategory'])) {
        $ProductsDetails = Products::Leftjoin('category_products', 'products.id', '=', 'category_products.product_id')	
                                            ->Leftjoin('variant_product', 'products.id', '=', 'variant_product.product_id')
											->select(['products.*','variant_product.sku','variant_product.price','variant_product.image'])
											->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id());

         
	}
	else {
		$ProductsDetails = Products::Leftjoin('variant_product', 'products.id', '=', 'variant_product.product_id')
		                    ->select(['products.*','variant_product.sku','variant_product.price','variant_product.image'])
							->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id());
	}
		if(!empty($request['search']['value'])) {

			

          $field = ['products.title','products.description'];

		  $namefield = ['products.title','products.description'];

          $search=($request['search']['value']);

            

            $ProductsDetails = $ProductsDetails->Where(function ($query) use($search, $field,$namefield) {

                if (strpos($search, ' ') !== false){

                    $s=explode(' ',$search);

                    foreach($s as $val) {

                        for ($i = 0; $i < count($namefield); $i++){

                            $query->orwhere($namefield[$i], 'like',  '%' . $val .'%');

                        }  

                    }

                }

                else {

                    for ($i = 0; $i < count($field); $i++){

                        $query->orwhere($field[$i], 'like',  '%' . $search .'%');

                    }  

                }				 

            }); 

        }

       

        if(!empty($request['status'])) {

            $ProductsDetails = $ProductsDetails->Where('products.status', '=', $request['status']);

        }
		
		if(!empty($request['category']) || !empty($request['subcategory'])) {
			if(!empty($request['category'])) {

				$ProductsDetails = $ProductsDetails->Where('category_products.category_id', '=', $request['category']);

			}
			
			if(!empty($request['subcategory'])) {

				$ProductsDetails = $ProductsDetails->Where('category_products.subcategory_id', '=', $request['subcategory']);

			}
			
		}
		$ProductsDetails = $ProductsDetails->groupBy('products.id');
        if(isset($request['order'][0])){

            $postedorder=$request['order'][0];

           
			if($postedorder['column']<=1) $orderby='products.title';

			if($postedorder['column']==2) $orderby='variant_product.sku';
			
			if($postedorder['column']==3) $orderby='variant_product.price';
            
            if($postedorder['column']==5) $orderby='products.sort_order';
            
            if($postedorder['column']==6) $orderby='products.created_at';

            $orderorder=$postedorder['dir'];

            $ProductsDetails = $ProductsDetails->orderby($orderby, $orderorder);

        }

       
		
        $recordsTotal = $ProductsDetails->count();

        $recordDetails = $ProductsDetails->offset($request->input('start'))->limit($request->input('length'))->get();

        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 

            {

                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';

                
				$title = (!empty($recordDetailsVal['title'])) ? substr($recordDetailsVal['title'], 0, 50) : '-';

               
                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';


                if(!empty($recordDetailsVal['image'])) {
                    
                    $image  =   url('/').'/uploads/ProductImages/resized/'.$recordDetailsVal['image'];
                }
                else
                    $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
                
                $image      =   '<img src="'.$image.'" width="70" height="70">';
                
                $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['updated_at']));
                
                $categories =   Products::Leftjoin('category_products', 'products.id', '=', 'category_products.product_id')	
                                            ->Leftjoin('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')	
											->select(['subcategories.subcategory_name'])
											->where('products.is_deleted','!=',1)->where('products.id',$recordDetailsVal['id'])->get();

                $categoriesData=    '';
                
                if(!empty($categories)) {
                    foreach($categories as $category){
                        $categoriesData .=   $category->subcategory_name.', ';
                    }
                }
                $categoriesData =   rtrim($categoriesData,', ');
                
                $action = '<a href="'.route('frontProductEdit', base64_encode($id)).'" title="'.trans('lang.edit_label').'" class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';



                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('frontProductDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class=""><i class="fas fa-trash"></i></a>';

            

                $arr[] = [ $image, $title, $recordDetailsVal['sku'],'Kr'.$recordDetailsVal['price'],$categoriesData,  $sort_order, $dated, $action];

            }

        } 

        else {

            $arr[] = [ '', '', '', '', trans('lang.datatables.sEmptyTable'), '', '', ''];

        }



        $json_arr = [

            'draw'            => $request->input('draw'),

            'recordsTotal'    => $recordsTotal,

            'recordsFiltered' => $recordsTotal,

            'data'            => ($arr),

        ];

        

        return json_encode($json_arr);

    }

     /* function to open Products create form */

    public function productform($id='') {

    $data['subscribedError']   =    '';
    if(!Auth::guard('user')->id()) {
            return redirect(route('frontLogin'));
        }
        $currentDate = date('Y-m-d H:i:s');
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) {
                
                $data['subscribedError']   =    trans('messages.subscribe_package_to_manage_prod_attri');
            }
        }
                    
       
        $data['pageTitle']              = 'Save Product';

        $data['current_module_name']    = 'Save';

        $data['module_name']            = 'Products';

        $data['module_url']             = route('manageFrontProducts');		
		
		$categories						=  Categories::Leftjoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
											->select('*')->get();
											
		$categoriesArray				=	array();
		
		foreach($categories as $category) {
			
			$categoriesArray[$category->category_id]['maincategory']	=	$category->category_name;
			
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		$data['categories']				=	$categoriesArray;
	    $data['attributesToSelect'] =   Attributes::where('user_id',Auth::guard('user')->id())->get(); 
		if($id) {
			$product_id					=	base64_decode($id);
			$data['product_id']			=	$product_id;
			$data['product']			=	Products::where('id',$product_id)->first();
            
            //$data['AttributesValues']  =   AttributesValues::get();
          
			$selectedCategories			=	ProductCategory::where('product_id',$product_id)->get();
			$selectedCategoriesArray	=	array();
			foreach($selectedCategories as $category) {
				$selectedCategoriesArray[]=	$category->subcategory_id;
			}
			
			$data['selectedCategories']	=	$selectedCategoriesArray;
			
		//	$data['VariantProduct']     =   VariantProduct::where('product_id',$product_id)->orderBy('id','asc')->get();
			//DB::enableQueryLog();


			$VariantProductAttribute    =   VariantProductAttribute::Leftjoin('attributes', 'attributes.id', '=', 'variant_product_attribute.attribute_id')
			                                    ->Leftjoin('variant_product', 'variant_product.id', '=', 'variant_product_attribute.variant_id')
			                                    ->Leftjoin('attributes_values', 'attributes_values.id', '=', 'variant_product_attribute.attribute_value_id')
			                                    ->select(['attributes.name','attributes_values.attribute_values','variant_product.*','variant_product_attribute.*'])
			                                    ->where('variant_product.product_id',$product_id)->orderBy('variant_product.id','asc')->orderBy('variant_product_attribute.id','asc')->get();
			
			$VariantProductAttributeArr  =   array();

			$i                           =   0;
			foreach($VariantProductAttribute as $variant) {
			    $VariantProductAttributeArr[$variant->variant_id]['variant_id']           =   $variant->variant_id;
			    $VariantProductAttributeArr[$variant->variant_id]['sku']                  =   $variant->sku;
			    $VariantProductAttributeArr[$variant->variant_id]['price']                =   $variant->price;
			    $VariantProductAttributeArr[$variant->variant_id]['quantity']             =   $variant->quantity;
			    $VariantProductAttributeArr[$variant->variant_id]['weight']               =   $variant->weight;
			    $VariantProductAttributeArr[$variant->variant_id]['image']                =   $variant->image;
			    $VariantProductAttributeArr[$variant->variant_id]['attributes'][]           =   array('id'=>$variant->id,'attribute_id'=>$variant->attribute_id,
			                                                                    'name'=>$variant->name,'attribute_values'=>$variant->attribute_values,
			                                                                    'attribute_value_id'=>$variant->attribute_value_id);
			    $i++;
			}
			$data['VariantProductAttributeArr']         =   $VariantProductAttributeArr;
			return view('Front/Products/edit', $data);
		}
		else {
			$data['product_id']			=	0;
			$data['VariantProduct']     =   array();
			return view('Front/Products/create', $data);
		}
			
		
		
        

    }


/*
 Function to remove accent from string
 @param:string
*/
    public function php_cleanAccents($string){
       
        if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = strtr($string, $chars);
        return $string;  
    }
    
    public function store(Request $request) {
        
         $product_slug = $request->input('product_slug');
         $is_accents = preg_match('/^[\p{L}-]*$/u', $product_slug);
         if($is_accents ==1){
            $slug =   $this->php_cleanAccents($product_slug);
         }else{
            $slug = $request->input('product_slug');
         }
        
        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:1500',
            'sort_order'		=>'numeric',      
            'product_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:products,product_slug',  

        ];

        $messages = [
            'title.required'         =>  trans('lang.required_field_error'),           
            'title.regex'            => trans('lang.required_field_error'),     
            'description.max'        => trans('lang.max_1000_char'),
            'product_slug.required'  => trans('errors.product_slug_req'),
            'product_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'product_slug.unique'    => trans('messages.category_slug_already_taken'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
         
            $messages = $validator->messages();
              // echo "<pre>";print_r($messages);exit;
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
//echo "<no err>";exit;
        $arrProducts = [

                'title'        		=> trim(ucfirst($request->input('title'))),

                'product_slug'      => trim(strtolower($slug)),

                'meta_title'        => trim($request->input('meta_title')),       

				'meta_description'  => trim($request->input('meta_description')),
				
				'meta_keyword'  	=> trim($request->input('meta_keyword')),

                'description'       => trim($request->input('description')),

				'status'       		=> trim($request->input('status')),
				
				'sort_order'       	=> trim($request->input('sort_order')),
               
				'user_id'			=>	Auth::guard('user')->id()
            ];


		if($request->input('product_id')==0) {
			$id = Products::create($arrProducts)->id;

            //unique product code
            $string     =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            Products::where('id', $id)->update(['product_code'=>substr(str_shuffle($string),0, 4).$id]);


		} else {
			$id		= $request->input('product_id');
			Products::where('id', $request->input('product_id'))->where('user_id', Auth::guard('user')->id())->update($arrProducts);
		}

        
		DB::table('variant_product')->where('product_id', $id)->delete();
		DB::table('variant_product_attribute')->where('product_id', $id)->delete();
		
		
		if(!empty($request->input('sku'))) {
		           $order = 0; 
		    foreach($request->input('sku') as $variant_key=>$variant) {
		        if($variant!='' && $_POST['price'][$variant_key]!='' && $_POST['quantity'][$variant_key]!='') {
		            $producVariant['product_id']=   $id;
		            $producVariant['price']     =   $_POST['price'][$variant_key];
		            $producVariant['sku']       =   $_POST['sku'][$variant_key];
		            $producVariant['weight']    =   $_POST['weight'][$variant_key];
		            $producVariant['quantity']  =   $_POST['quantity'][$variant_key]; 
		            
                    if(isset($_POST['previous_image'][$variant_key]) ) {
                        $producVariant['image'] =   $_POST['previous_image'][$variant_key];
                    }
		            $variant_id=VariantProduct::create($producVariant)->id;
		            foreach($_POST['attribute'][$variant_key] as $attr_key=>$attribute) {
		                if($_POST['attribute'][$variant_key][$attr_key]!='' && $_POST['attribute_value'][$variant_key][$attr_key])
		                {
		                    $productVariantAttr['product_id']   =   $id;
    		                $productVariantAttr['variant_id']   =   $variant_id;
    		                $productVariantAttr['attribute_id'] =   $_POST['attribute'][$variant_key][$attr_key];
    		                $productVariantAttr['attribute_value_id'] =   $_POST['attribute_value'][$variant_key][$attr_key];
    		                VariantProductAttribute::create($productVariantAttr);
		                }
		                
		            }
		        }
		    }
		}
		ProductCategory::where('product_id', $id)->delete();
			 
         if(!empty($request->input('categories'))) {
			 
			 foreach($request->input('categories') as $subcategory) {
				 $category	=	Subcategories::where('id',$subcategory)->first();
				 $producCategories['product_id']	=	$id;
				 $producCategories['category_id']	=	$category->category_id;
				 $producCategories['subcategory_id']	=	$category->id;
				 
				 ProductCategory::create($producCategories);
				 
			 }
		 }

   

        

        Session::flash('success', trans('lang.product_saved_success'));

        return redirect(route('manageFrontProducts')); 

    }



    public function uploadVariantImage(Request $request){
        
         if(($request->file('fileUpload'))){

                        $fileError = 0;
                        $image=$request->file('fileUpload');
                        
            
                           
                     {
            
                            $name=$image->getClientOriginalName();
            
                            $fileExt  = strtolower($image->getClientOriginalExtension());
            
            
            
                            if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
            
                                $fileName = 'product-'.date('YmdHis').'.'.$fileExt;
            
                                $image->move(public_path().'/uploads/ProductImages/', $fileName);  // your folder path
            
            
            
                                $path = public_path().'/uploads/ProductImages/'.$fileName;
            
                                $mime = getimagesize($path);
            
            
            
                                if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
            
                                if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
            
                                if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
            
                                if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }
            
            
            
                                $old_x = imageSX($src_img);
            
                                $old_y = imageSY($src_img);
            
            
            
                                $newWidth = 300;
            
                                $newHeight = 300;
            
            
            
                                if($old_x > $old_y) {
            
                                    $thumb_w    =   $newWidth;
            
                                    $thumb_h    =   $old_y/$old_x*$newWidth;
            
                                }
            
            
            
                                if($old_x < $old_y) {
            
                                    $thumb_w    =   $old_x/$old_y*$newHeight;
            
                                    $thumb_h    =   $newHeight;
            
                                }
            
            
            
                                if($old_x == $old_y) {
            
                                    $thumb_w    =   $newWidth;
            
                                    $thumb_h    =   $newHeight;
            
                                }
            
            
            
                                $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);
            
                                imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
            
                                // New save location
            
                                $new_thumb_loc = public_path().'/uploads/ProductImages/resized/' . $fileName;
            
            
            
                                if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
            
                                if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
            
                                if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
            
                                if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
            
            
            
                                imagedestroy($dst_img);
            
                                imagedestroy($src_img);
            
            
            
                            } else {
            
                                    $fileError = 1;
            
                            }
            
                        }
            
            
            
                        if($fileError == 1) {
            
                            //Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
            
                            //return redirect()->back();
            
                        }
                        //$producVariant['image']=$fileName;
                        echo $fileName;
                    } 


    }
    /**

     * Edit record details

     * @param  $id = User Id

    
*/

    

    /**

     * Change status for Record [active/block].

     * @param  $id = Id, $status = active/block 

     */

    public function changeStatus($id, $status)  {

        if(empty($id)) {

            Session::flash('error', trans('errors.refresh_your_page_err'));

            return redirect(route('frontCustomer'));

        }

        $id = base64_decode($id);



        $result = Products::where('id', $id)->update(['status' => $status]);

        if ($result) {

            Session::flash('success', trans('lang.status_updated_successfullly'));

            return redirect()->back();

         } else  {

            Session::flash('error', trans('errors.something_went_wrong'));

            return redirect()->back();

        }

    }



     /**

     * Delete Record

     * @param  $id = Id

     */

    public function delete($id) {

        if(empty($id)) {

            Session::flash('error', trans('errors.refresh_your_page_err'));

            return redirect(route('frontProduct'));

        }



        $id = base64_decode($id);

        $result = Products::find($id);



        if (!empty($result)) {

           $product = Products::where('id', $id)->update(['is_deleted' =>1]);

           Session::flash('success', trans('lang.record_delete'));

                return redirect()->back();  

        } else {

            Session::flash('error', trans('errors.something_went_wrong'));

            return redirect()->back();

        }

    }





    /* funtion to delete image on edit form 

    @param : $id

    */

     public function deleteImage($id) {

        if(empty($id))  {

            Session::flash('error', trans('errors.refresh_your_page_err'));

            return redirect(route('frontProduct'));

        }

        $id = base64_decode($id);

        $result = productimages::find($id);

        

        if (!empty($result)) 

        {

            if ($result->delete()) 

            {

                Session::flash('success',trans('lang.record_delete') );

                return redirect()->back();

            } 

            else 

            {

                Session::flash('error', trans('errors.something_went_wrong'));

                return redirect()->back();

            }

        } 

        else 

        {

            Session::flash('error', trans('errors.something_went_wrong'));

            return redirect()->back();

        }

    }

    /* function to check for unique slug name
    * @param:storename
    */
    function checkUniqueSlugName(Request $request){
        $slug_name = $request->slug_name;
        $is_accents = preg_match('/^[\p{L}-]*$/u', $slug_name);
        if($is_accents ==1){
            $slug =   $this->php_cleanAccents($slug_name);
        }else{
            $slug = $slug_name;
        }
        $id = base64_decode($request->id);
     
        if(!empty($id)){
            $data =  Products::where('product_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  Products::where('product_slug', $slug)->get();
        }
       $messages = '';
        if(!empty($data[0]['product_slug'])){
            $messages =trans('messages.category_slug_already_taken');
             return $messages;
        }
       
    }


}


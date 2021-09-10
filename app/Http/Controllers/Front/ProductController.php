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
use App\Models\AdminOrders;
use App\Models\TmpAdminOrders;
use App\Models\BuyerProducts;

use App\CommonLibrary;

use App\Http\AdyenClient;

/*Uses*/

use Auth;

use Session;

use flash;

use Validator;

use DB;

use Mail;



class ProductController extends Controller

{

    protected $checkout;

    /*

	 * Define abjects of models, services.

	 */

    function __construct(AdyenClient $checkout) {

        $this->checkout = $checkout->service;
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
            
           /* $checkProductExistOfBuyer   =   Products::where('user_id',Auth::guard('user')->id())->first();
            
            if(!empty($checkProductExistOfBuyer)) {
                Session::flash('success', trans('lang.product_saved_success'));

                return redirect(route('frontProductEdit',base64_encode($checkProductExistOfBuyer->id)));
            }
            else */
                //Session::flash('success', trans('lang.product_saved_success'));
            return redirect(route('manageBuyerProducts'));
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
                 //  DB::enableQueryLog();
    // and then you can get query log
 //   dd(DB::getQueryLog());

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
				$subCategoriesHtml		.=	'<option class="subcatclass subcat'.$category_id.'" style="display:none;" id="subcat'.$category_id.'" value="'.$subcategory_id.'">'.$subcategory.'</option>';
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
            DB::enableQueryLog();
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
  
    // and then you can get query log

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
		$ProductsDetails = $ProductsDetails->groupBy('products.id')->orderby('products.id', 'DESC');
        $recordsTotal = $ProductsDetails->get()->count();
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

		
       
       // $recordsTotal = 14;
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
                    $imagesParts    =   explode(',',$recordDetailsVal['image']);
                    
                    $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
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
                
                $action = '<a href="'.route('frontProductEdit', base64_encode($id)).'" title="'.trans('lang.edit_label').'" style="color:#03989E;" class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';


 
                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('frontProductDelete', base64_encode($id)).'\');"  style="color:red;"  title="'.trans('lang.delete_title').'" class=""><i class="fas fa-trash"></i></a>';

            

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

    public function productform($id='') 
    {

        $data['subscribedError']   =    '';
        $max_seq_no ='';
        $max_seq_no = Products::max('sort_order');
        $data['max_seq_no'] = $max_seq_no + 1;
        $is_seller = 0;

        if(!Auth::guard('user')->id()) 
        {
                return redirect(route('frontLogin'));
        }
        $currentDate = date('Y-m-d H:i:s');
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) 
        {
            $is_seller = 1;
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
        else
        {
            $is_seller = 0;
        }
                    
        $data['is_seller']              = $is_seller;
        
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
	    //$data['attributesToSelect'] =   Attributes::where('user_id',Auth::guard('user')->id())->get(); 
        $data['attributesToSelect'] =   Attributes::select('*')->get(); 

		if($id) {
			$product_id					=	base64_decode($id);
			$data['product_id']			=	$product_id;
			$data['product']			=	Products::where('id',$product_id)->first();
            $data['buyerProduct']		=	BuyerProducts::where('product_id',$product_id)->first();
           // $data['buyerProduct']       =   Products::where('id',$product_id)->first();
            
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
           // echo'<pre>';print_r($VariantProductAttributeArr);exit;
			$data['VariantProductAttributeArr']         =   $VariantProductAttributeArr;
            if($User->role_id==2) 
			    return view('Front/Products/seller-edit', $data);
            else
                //echo "<pre>";print_r($data);exit;
                return view('Front/Products/buyer-edit', $data);
		}
		else {
			$data['product_id']			=	0;
			$data['VariantProduct']     =   array();
            if($User->role_id==2) 
			    return view('Front/Products/seller-create', $data);
            else
                return view('Front/Products/buyer-create', $data);
		}
    }

    
    public function store(Request $request) {
        
        //echo'<pre>';print_r($_POST);exit;
        $product_slug = $request->input('product_slug');
        $slug =   CommonLibrary::php_cleanAccents($product_slug);

        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:3000',
            'sort_order'		=>'numeric',      
            'product_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u',  

        ];

        $messages = [
            'title.required'         =>  trans('lang.required_field_error'),           
            'title.regex'            => trans('lang.required_field_error'),     
            'description.max'        => trans('lang.max_1000_char'),
            'product_slug.required'  => trans('errors.product_slug_req'),
            'product_slug.regex'     => trans('errors.input_aphanum_dash_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrProducts = [

                'title'        		=> trim(ucfirst($request->input('title'))),

                'product_slug'      => trim(strtolower($slug)),

                'meta_title'        => trim($request->input('meta_title')),       

				'meta_description'  => trim($request->input('meta_description')),

                'shipping_method'   =>  trim($request->input('shipping_method_ddl')),  

                'shipping_charges'  =>  trim($request->input('shipping_charges')),  
				
				'meta_keyword'  	=> trim($request->input('meta_keyword')),

                'description'       => trim($request->input('description')),

				'status'       		=> trim($request->input('status')),
				
                'discount'       	=> trim($request->input('discount')),
                'free_shipping'     =>  trim($request->input('free_shipping')),

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

        
		//DB::table('variant_product')->where('product_id', $id)->delete();
		//DB::table('variant_product_attribute')->where('product_id', $id)->delete();

        if(!empty($request->input('user_name'))) {
            BuyerProducts::where('product_id',$id)->delete();
            $buyerProductArray['product_id']=$id;
            $buyerProductArray['user_id']=Auth::guard('user')->id();
            $buyerProductArray['user_name']=$request->input('user_name');
            $buyerProductArray['user_email']=$request->input('user_email');
            $buyerProductArray['user_phone_no']=$request->input('user_phone_no');
            $buyerProductArray['country']=$request->input('country');
            $buyerProductArray['location']=$request->input('location');
            //$buyerProductArray['price']=$request->input('price');
            BuyerProducts::create($buyerProductArray);
        }
        //echo'<pre>';print_r($_POST);exit;
		$producVariant=[];
		if(!empty($request->input('sku'))) {
            
		           $order = 0; 
		    foreach($request->input('sku') as $variant_key=>$variant) {

		        if($variant!='' && $_POST['price'][$variant_key]!='' && $_POST['quantity'][$variant_key]!='') {
                    $producIsSold = array();
                    if($_POST['quantity'][$variant_key]>0){
                        $producIsSold['is_sold']     =   '0';
                       
                    }else{
                          $producIsSold['is_sold']     =   '1';
                    }
                    
                    Products::where('id', $id)->where('user_id', Auth::guard('user')->id())->update($producIsSold);
		            $producVariant['product_id']=   $id;
		            $producVariant['price']     =   $_POST['price'][$variant_key];
		            $producVariant['sku']       =   $_POST['sku'][$variant_key];
		            $producVariant['weight']    =   $_POST['weight'][$variant_key];
		            $producVariant['quantity']  =   $_POST['quantity'][$variant_key]; 
		            
                    $producVariant['image']     =   '';
                   if(isset($_POST['hidden_images'][$variant_key]) && !empty($_POST['hidden_images'][$variant_key]) ) {
                        
                        foreach($_POST['hidden_images'][$variant_key] as $img)
                            $producVariant['image'].=   $img.',';
                        $producVariant['image'] =   rtrim($producVariant['image'],',');
                    }
                   
                    if(isset($_POST['variant_id'][$variant_key])) {

                        $checkVariantExist   =   DB::table('variant_product')->where('id', $_POST['variant_id'][$variant_key])->first();

                        if(!empty($checkVariantExist)) {
                                VariantProduct::where('id', $checkVariantExist->id)->update($producVariant);
                                $variant_id=$checkVariantExist->id;
                        }
                        else{
                          $variant_id=VariantProduct::create($producVariant)->id;
                        }
                    }
                    else{
		              $variant_id=VariantProduct::create($producVariant)->id;
                    }

		            foreach($_POST['attribute'][$variant_key] as $attr_key=>$attribute) {
                       
		                if($_POST['attribute'][$variant_key][$attr_key]!='' && $_POST['attribute_value'][$variant_key][$attr_key])
		                {
		                    $productVariantAttr['product_id']   =   $id;
    		                $productVariantAttr['variant_id']   =   $variant_id;
    		                $productVariantAttr['attribute_id'] =   $_POST['attribute'][$variant_key][$attr_key];
    		                $productVariantAttr['attribute_value_id'] =   $_POST['attribute_value'][$variant_key][$attr_key];
                            if(isset($_POST['variant_attribute_id'][$variant_key][$attr_key])) {
                                $checkRecordExist   =   VariantProductAttribute::where('id', $_POST['variant_attribute_id'][$variant_key][$attr_key])->first();

                            if(!empty($checkRecordExist)) {
                                VariantProductAttribute::where('id', $checkRecordExist->id)->update($productVariantAttr);
                            }
                            else
                              VariantProductAttribute::create($productVariantAttr);
                            } 
                             else{
                                VariantProductAttribute::create($productVariantAttr);
                             }
                              
		                }
		                
		            }
		        }
		    }

		}
		ProductCategory::where('product_id', $id)->delete();

		if(empty($request->input('categories'))) {	
           // echo "in";exit;
             $category  =   Subcategories::where('subcategory_name','Uncategorized')->first();
            $request->input('categories')[]=  $category->id;
            $producCategories['product_id']    =   $id;
            $producCategories['category_id']   =   $category->category_id;
            $producCategories['subcategory_id']    =   $category->id;
            ProductCategory::create($producCategories);
        } 
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


    public function showCheckout(Request $request) {
        
        $product_slug = $request->input('product_slug');
        $slug =   CommonLibrary::php_cleanAccents($product_slug);
        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:3000',
            'sort_order'		=>'numeric',      
            'product_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u',  

        ];

        $messages = [
            'title.required'         =>  trans('lang.required_field_error'),           
            'title.regex'            => trans('lang.required_field_error'),     
            'description.max'        => trans('lang.max_1000_char'),
            'product_slug.required'  => trans('errors.product_slug_req'),
            'product_slug.regex'     => trans('errors.input_aphanum_dash_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
         
            $messages = $validator->messages();
              // echo "<pre>";print_r($messages);exit;
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {
            $username = env('KLORNA_USERNAME');
            $password = env('KLORNA_PASSWORD');
            $productPostAmount = env('PRODUCT_POST_AMOUNT');
            $url = env('BASE_API_URL');

            //echo '=='.env('APP_URL');exit;
            $user_id = Auth::guard('user')->id();
            $productData = $request->all();
            unset($productData['image']);
            unset($productData['_token']);
            $currentDate = date('Y-m-d H:i:s');
            
            $arrInsertOrder = [
                'user_id' => $user_id,
                'total' => $productPostAmount,
                'product_details' => json_encode($productData),
                'order_status' => 'PENDING',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];
            $orderId = TmpAdminOrders::create($arrInsertOrder)->id;

            
            $UserData = UserMain::select('users.*')->where('users.id','=',$user_id)->first()->toArray();
            $billing_address= [];
            $billing_address['given_name'] = $UserData['fname'];
            $billing_address['family_name'] = $UserData['lname'];
            $billing_address['email'] = $UserData['email'];
            $billing_address['street_address'] = $UserData['address'];
            $billing_address['postal_code'] = $UserData['postcode'];
            $billing_address['city'] = $UserData['city'];
            $billing_address['phone'] = $UserData['phone_number'];

            /*klarna api to create order*/
            
            $totalProductAmount = (int)ceil($productPostAmount)*100;
            //$url = "https://api.playground.klarna.com/checkout/v3/orders";
            $data = array("purchase_country"=> "SE",
            "purchase_currency"=> "SEK",
            "locale"=> "en-SE",
            "order_amount"=> $totalProductAmount,
            "order_tax_amount"=> 0,
            "billing_address" => $billing_address,
            );
            
            $arrOrderDetails[] = array(
                "type"=> "physical",
                "name"=> 'Buyer Product Posting Cost',
                "quantity"=>1,
                "quantity_unit"=> "pcs",
                "unit_price"=> $totalProductAmount,
                "tax_rate"=> 0,
                "total_amount"=> $totalProductAmount,
                "total_discount_amount"=> 0,
                "total_tax_amount"=> 0,
            );
            
            
            $data['order_lines'] = $arrOrderDetails;
        
            $data['merchant_urls'] =array(
                    "terms"=>  url("/"),
                    "checkout"=> url("/")."/manage-products/checkout_callback?order_id={checkout.order.id}",
                    "confirmation"=> url("/")."/manage-products/checkout_callback?order_id={checkout.order.id}",
                    "push"=> url("/")."/product_push_notification?order_id={checkout.order.id}",
                
            );

            $data['merchant_data'] = $orderId;
            $data['options']= ['allow_separate_shipping_address' => true,'color_button' => '#03989e','color_button_text' => '#ffffff'];

            $data = json_encode($data);
            $data =str_replace("\/\/", "//", $data);
            $data =str_replace("\/", "/", $data);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
    
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            curl_close($ch);

            $response = json_decode($result);
            
            if(!empty($response->error_messages))
            {
                $cnt_err = count($response->error_messages);
            }
            
            if (isset($error_msg) || @$cnt_err ) 
            {
                $blade_data['error_messages']= trans('errors.payment_failed_err');
                return view('Front/payment_error',$blade_data); 
            }
        
            $order_id = $response->order_id;
            if(empty($order_id))
            {
                $blade_data['error_messages']= trans('errors.seller_credentials_err');
                return view('Front/payment_error',$blade_data); 
            }

            $order_status = $response->status;
            
            $arrUpdateOrder = [
                'klarna_order_reference'  => $order_id,
            ];

            TmpAdminOrders::where('id',$orderId)->update($arrUpdateOrder);

            $param["html_snippet"] = $response->html_snippet;
            return view('Front/checkout', $param);
        }
    }

    public function showCheckoutSwish(Request $request) {
        
        $product_slug = $request->input('product_slug');
        $slug =   CommonLibrary::php_cleanAccents($product_slug);
        $user_id = Auth::guard('user')->id();
        $productData = $request->all();


        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:3000',
            'sort_order'		=>'numeric',      
            'product_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u',  

        ];

        $messages = [
            'title.required'         =>  trans('lang.required_field_error'),           
            'title.regex'            => trans('lang.required_field_error'),     
            'description.max'        => trans('lang.max_1000_char'),
            'product_slug.required'  => trans('errors.product_slug_req'),
            'product_slug.regex'     => trans('errors.input_aphanum_dash_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
         
            $messages = $validator->messages();
              // echo "<pre>";print_r($messages);exit;
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {
            $currentDate = date('Y-m-d H:i:s');
            $arrInsertOrder = [
                'user_id' => $user_id,
                'total' => env('PRODUCT_POST_AMOUNT'),
                'product_details' => json_encode($productData),
                'order_status' => 'PENDING',
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];
            $orderId = TmpAdminOrders::create($arrInsertOrder)->id;
            Session::put('current_buyer_order_id', $orderId);
            $data = array(
                'type' => $request->type,
                'clientKey' => env('CLIENT_KEY'),
                'orderId'=>$orderId,
                'paymentAmount'=>env('PRODUCT_POST_AMOUNT')*100
            );
            return view('Front/checkout_swish', $data);
        }
    }

public function swishIpnCallback(Request $request){
    if(isset($_REQUEST['success']) && $_REQUEST['success']==true) {
        $order_id = $_REQUEST['merchantReference'];
            //$order_id = '104313e3-46e3-6d8c-9b7a-36ff0078646a';
            $currentDate = date('Y-m-d H:i:s');
            
            $username = '';
            $password = '';
            $checkExisting = TmpAdminOrders::where('id','=',$order_id)->first()->toArray();
            if(!empty($checkExisting)) {
                $ProductData = json_decode($checkExisting['product_details'],true);
                $user_id = $checkExisting['user_id'];
                
                $currentDate = date('Y-m-d H:i:s');


                
                $address = array();
                $Total = (float)ceil($checkExisting['total']);
                $paymentDetails = $_REQUEST;
                $order_status   =   'Success';
                $slug =   CommonLibrary::php_cleanAccents($ProductData['product_slug']);

                $arrOrderInsert = [
                    'user_id'     => $user_id,
                    'address'     => '',
                    'order_lines' => '',
                    'total' => $checkExisting['total'],
                    'payment_details' => '',
                    'payment_status' => '',
                    'order_status' => 'PENDING',
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                    'klarna_order_reference' => $_REQUEST['pspReference'],
                ];
                $NewOrderId = AdminOrders::create($arrOrderInsert)->id;
                //Create Product
                $arrProducts = [
                    'title'        		=> trim(ucfirst($ProductData['title'])),
                    'product_slug'      => trim(strtolower($slug)),
                    'meta_title'        => trim($ProductData['meta_title']),       
                    'meta_description'  => trim($ProductData['meta_description']),
                    'shipping_method'   => '',  
                    'shipping_charges'  => '',  
                    'meta_keyword'  	=> trim($ProductData['meta_keyword']),
                    'description'       => trim($ProductData['description']),
                    'status'       		=> trim($ProductData['status']),
                    'sort_order'       	=> trim($ProductData['sort_order']),
                    'user_id'			=>	$user_id,
                    'is_buyer_product'  => '1',
                ];


                
                $id = Products::create($arrProducts)->id;

                if(!empty($ProductData['user_name'])) {
                    BuyerProducts::where('product_id',$id)->delete();
                    $buyerProductArray['product_id']=$id;
                    $buyerProductArray['user_id']=$user_id;
                    $buyerProductArray['user_name']=$ProductData['user_name'];
                    $buyerProductArray['user_email']=$ProductData['user_email'];
                    $buyerProductArray['user_phone_no']=$ProductData['user_phone_no'];
                    $buyerProductArray['country']=$ProductData['country'];
                    $buyerProductArray['location']=$ProductData['location'];
                    //$buyerProductArray['price']=$request->input('price');
                    BuyerProducts::create($buyerProductArray);
                }

                //unique product code
                $string     =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $product_code = substr(str_shuffle($string),0, 4).$id;
                Products::where('id', $id)->update(['product_code'=>$product_code]);

                //START : Update Order
                $arrOrderUpdate = [
                    'address'     => json_encode($address),
                    'order_lines' => '',
                    'payment_details' => json_encode($paymentDetails),
                    'payment_status' => $order_status,
                    'order_status' => 'COMPLETE',
                    'updated_at' => $currentDate,
                    'product_id' => $id,
                ];
                AdminOrders::where('id',$NewOrderId)->update($arrOrderUpdate);

                $producVariant=[];
                if(!empty($ProductData['sku'])) 
                {
                    $order = 0; 
                    foreach($ProductData['sku'] as $variant_key=>$variant) 
                    {
                        if($variant!='' && $ProductData['price'][$variant_key]!='' && $ProductData['quantity'][$variant_key]!='') 
                        {
                            $producVariant['product_id']=   $id;
                            $producVariant['price']     =   $ProductData['price'][$variant_key];
                            $producVariant['sku']       =   $ProductData['sku'][$variant_key];
                            $producVariant['weight']    =   $ProductData['weight'][$variant_key];
                            $producVariant['quantity']  =   $ProductData['quantity'][$variant_key]; 
                            
                        if(isset($ProductData['hidden_images'][$variant_key]) && !empty($ProductData['hidden_images'][$variant_key]) ) {
                                $producVariant['image'] =   '';
                                foreach($ProductData['hidden_images'][$variant_key] as $img)
                                    $producVariant['image'].=   $img.',';
                                $producVariant['image'] =   rtrim($producVariant['image'],',');
                            }
                            if(isset($ProductData['variant_id'][$variant_key])) {

                                $checkVariantExist   =   DB::table('variant_product')->where('id', $ProductData['variant_id'][$variant_key])->first();

                                if(!empty($checkVariantExist)) {
                                        VariantProduct::where('id', $checkVariantExist->id)->update($producVariant);
                                        $variant_id=$checkVariantExist->id;
                                }
                                else
                                $variant_id=VariantProduct::create($producVariant)->id;
                            }
                            
                            else
                            $variant_id=VariantProduct::create($producVariant)->id;

                            foreach($ProductData['attribute'][$variant_key] as $attr_key=>$attribute) {
                            
                                if($ProductData['attribute'][$variant_key][$attr_key]!='' && $ProductData['attribute_value'][$variant_key][$attr_key])
                                {
                                    $productVariantAttr['product_id']   =   $id;
                                    $productVariantAttr['variant_id']   =   $variant_id;
                                    $productVariantAttr['attribute_id'] =   $ProductData['attribute'][$variant_key][$attr_key];
                                    $productVariantAttr['attribute_value_id'] =   $ProductData['attribute_value'][$variant_key][$attr_key];
                                    if(isset($ProductData['variant_attribute_id'][$variant_key][$attr_key])) {
                                        $checkRecordExist   =   VariantProductAttribute::where('id', $ProductData['variant_attribute_id'][$variant_key][$attr_key])->first();

                                    if(!empty($checkRecordExist)) {
                                        VariantProductAttribute::where('id', $checkRecordExist->id)->update($productVariantAttr);
                                    }
                                    else
                                    VariantProductAttribute::create($productVariantAttr);
                                    } 
                                    else
                                    VariantProductAttribute::create($productVariantAttr);
                                }
                                
                            }
                        }
                    }

                }
                
                ProductCategory::where('product_id', $id)->delete();
                $categorySlug = '';
                $subCategorySlug = '';
                if(empty($ProductData['categories'])) 
                {	
                
                    $category  =   Subcategories::where('subcategory_name','Uncategorized')->first();
                    $ProductData['categories'][]        =  $category->id;
                    $producCategories['product_id']     =   $id;
                    $producCategories['category_id']    =   $category->category_id;
                    $producCategories['subcategory_id'] =   $category->id;
                    $subCategorySlug = $category->subcategory_slug;
                    ProductCategory::create($producCategories);

                    $mainCategory = Categories::where('id',$category->category_id)->first();
                    $categorySlug = $mainCategory->category_slug;

                } 
                if(!empty($ProductData['categories'])) 
                {
                    
                    foreach($ProductData['categories'] as $subcategory) {
                        $category	=	Subcategories::where('id',$subcategory)->first();
                        $producCategories['product_id']	=	$id;
                        $producCategories['category_id']	=	$category->category_id;
                        $producCategories['subcategory_id']	=	$category->id;
                        $subCategorySlug = $category->subcategory_slug;
                        ProductCategory::create($producCategories);
                        
                        $mainCategory = Categories::where('id',$category->category_id)->first();
                        $categorySlug = $mainCategory->category_slug;
                        
                    }
                } 
                
                $product_link	=	url('/').'/product';
                $product_link	.=	'/'.$categorySlug;
                $product_link	.=	'/'.$subCategorySlug;
                $product_link	.=	$slug.'-P-'.$product_code;


                $GetOrder = AdminOrders::join('users', 'users.id', '=', 'admin_orders.user_id')->select('users.fname','users.lname','users.email','admin_orders.*')->where('admin_orders.id','=',$NewOrderId)->get()->toArray();

                //START : Send success email to User.
                $email = trim($GetOrder[0]['email']);
                $name  = trim($GetOrder[0]['fname']).' '.trim($GetOrder[0]['lname']);

                $GetEmailContents = getEmailContents('Product Published');
                $subject = $GetEmailContents['subject'];
                $contents = $GetEmailContents['contents'];
                
                $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##'],[$name,$email,url('/'),$product_link],$contents);

                $arrMailData = ['email_body' => $contents];

                Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$name,$subject) {
                    $message->to($email, $name)->subject
                        ($subject);
                    $message->from( env('FROM_MAIL'),'Tijara');
                });
                
            
                $admin_email = 'priyanka.techbee@gmail.com';
                $admin_name  = 'Tijara Admin';
                
                $GetEmailContents = getEmailContents('Product Published Admin');
                $subject = $GetEmailContents['subject'];
                $contents = $GetEmailContents['contents'];
                
                $contents = str_replace(['##SITE_URL##','##LINK##'],[url('/'),$product_link],$contents);

                $arrMailData = ['email_body' => $contents];

                Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($admin_email,$admin_name,$subject) {
                    $message->to($admin_email, $admin_name)->subject
                        ($subject);
                    $message->from( env('FROM_MAIL'),'Tijara');
                });
                //END : Send success email to Seller.

                
        }
    }
    
}
     // Result pages
  public function result(Request $request){
    $type = $request->type;
  
    $current_buyer_order_id   =  $request->id;
    Session::put('current_buyer_order_id', 0);

    if($type=='success') {
    
        
        return redirect()->route('frontProductCheckoutSuccess', ['id' => base64_encode($current_buyer_order_id)]);
    }
    else
    {
        $data['error_messages']=trans('errors.payment_failed_err');
        return view('Front/payment_error',$data);
    }
    return view('pages.result')->with('type', $type);
}

    /* ################# API ENDPOINTS ###################### */
  // The API routes are exempted from app/Http/Middleware/VerifyCsrfToken.php

  public function getPaymentMethods(Request $request){
    error_log("Request for getPaymentMethods $request");

    $params = array(
        "merchantAccount" => env('MERCHANT_ACCOUNT'),
        "channel" => "Web"
    );

    $response = $this->checkout->paymentMethods($params);
    foreach($response as $key => $r)
    {
        if($key > 0)
        {
          unset($response[$key]);
        }
    }
    return $response;
}

public function initiatePayment(Request $request){
    error_log("Request for initiatePayment $request");

    $orderRef = session('current_buyer_order_id');
    $params = array(
        "merchantAccount" => env('MERCHANT_ACCOUNT'),
        "channel" => "Web", // required
        "amount" => array(
            "currency" => 'SEK',
            "value" => env('PRODUCT_POST_AMOUNT')*100 // value is 10€ in minor units
        ),
        "reference" => $orderRef, // required
        // required for 3ds2 native flow
        "additionalData" => array(
            "allow3DS2" => "true"
        ),
        "origin" => url('/'), // required for 3ds2 native flow
        "shopperIP" => $request->ip(),// required by some issuers for 3ds2
        // we pass the orderRef in return URL to get paymentData during redirects
        // required for 3ds2 redirect flow
        "returnUrl" => url('/')."/api/handleShopperRedirect?orderRef=${orderRef}",
        "paymentMethod" => $request->paymentMethod,
        "browserInfo" => $request->browserInfo // required for 3ds2
        );
//echo'<pre>';print_r($params);exit;
    $response = $this->checkout->payments($params);

    return $response;
}


public function submitAdditionalDetails(Request $request){
    error_log("Request for submitAdditionalDetails $request");

    $payload = array("details" => $request->details, "paymentData" => $request->paymentData);

    $response = $this->checkout->paymentsDetails($payload);

    //echo'<pre>';print_r($response);exit;
    return $response;
}

public function handleShopperRedirect(Request $request){
    error_log("Request for handleShopperRedirect $request");

    $redirect = $request->all();

    $details = array();
    if (isset($redirect["redirectResult"])) {
      $details["redirectResult"] = $redirect["redirectResult"];
    } else if (isset($redirect["payload"])) {
      $details["payload"] = $redirect["payload"];
    }
    $orderRef = $request->orderRef;

    $payload = array("details" => $details);

    $response = $this->checkout->paymentsDetails($payload);

    //echo'<pre>';print_r($response);exit; 
    // switch ($response["resultCode"]) {
    //     case "Authorised":
    //         return redirect()->route('result', ['type' => 'success']);
    //     case "Pending":
    //     case "Received":
    //         return redirect()->route('result', ['type' => 'pending']);
    //     case "Refused":
    //         return redirect()->route('result', ['type' => 'failed']);
    //     default:
    //         return redirect()->route('result', ['type' => 'error']);
    // }
}

/* ################# end API ENDPOINTS ###################### */

// Util functions
public function findCurrency($type){

    switch ($type) {
    case "ach":
        return "USD";
    case "wechatpayqr":
    case "alipay":
        return "CNY";
    case "dotpay":
        return "PLN";
    case "boletobancario":
    case "boletobancario_santander":
        return "BRL";
    default:
        return "SEK";
    }
}

    /* function for klarna payment callback*/
    public function checkoutCallback(Request $request)
    {
      $order_id = $request->order_id;
      $username = env('KLORNA_USERNAME');
      $password = env('KLORNA_PASSWORD');
      
      /*klarna api call to read order*/
    
      $url = env('BASE_API_URL')."/".$order_id;
      /*  $url ="https://api.playground.klarna.com/checkout/v3/orders/d1d90381-3cda-6a89-a22c-33f1ed95eb9e";*/
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
   
      $result = curl_exec($ch);

      if (curl_errno($ch)) 
      {
        $error_msg = curl_error($ch);
      }
      curl_close($ch);

      if (isset($error_msg)) 
      {
        $data['error_messages']=trans('errors.payment_failed_err');
        return view('Front/payment_error',$data); 
      }
    
      $response = json_decode($result);

      $order_id     =  $response->order_id;
      $order_status = $response->status;
      
      if($order_status == 'checkout_complete')
      {
        $orderLines = $response->order_lines;
        $billing_address = $response->billing_address;
        $shipping_address = $response->shipping_address;

        $address = ['billing' => json_encode($billing_address), 'shipping' => json_encode($shipping_address)];

        $order_amount = (float)($response->order_amount/100);
        $TmpOrderId   = $response->merchant_data;
        //$orderCompleteAt = $response->complete_at;

        $checkExisting = TmpAdminOrders::where('id','=',$TmpOrderId)->where('klarna_order_reference','=',$order_id)->first()->toArray();
        $Total = (float)ceil($checkExisting['total']);
        if($order_amount != $Total)
        {
          $data['error_messages']=trans('errors.order_amount_mismatched');
          return view('Front/payment_error',$data);
        }
        
        return redirect(route('frontProductCheckoutSuccess',['id' => base64_encode($checkExisting['id'])]));
      }
  }


  public function showCheckoutSuccess($id)
  {
    $data = [];
    $user_id = Auth::guard('user')->id();
    if($user_id && session('role_id') == 1)
    {
      $OrderId = base64_decode($id);
      $checkOrder = TmpAdminOrders::where('id','=',$OrderId)->first()->toArray();

      if(!empty($checkOrder)) {

        if($checkOrder['user_id'] != $user_id)
        {
            Session::flash('error', trans('errors.not_authorize_order'));
            return redirect(route('frontHome'));
        }
        else
        {
            $data['OrderId'] = $OrderId;
            return view('Front/Products/order_success', $data);
        }
        $temp_orders = TmpAdminOrders::find($checkOrder['id']);
                $temp_orders->delete();
      }
      
    }
    else
    {
      Session::flash('error', trans('errors.login_buyer_required'));
      return redirect(route('frontLogin'));
    }
  }

   /*push notification request from Klarna*/
   public function pushNotification(Request $request)
   {
      
    /*get order from klarm by order id*/
    $order_id = $request->order_id;
    //$order_id = '104313e3-46e3-6d8c-9b7a-36ff0078646a';
    $currentDate = date('Y-m-d H:i:s');
    
    $username = '';
    $password = '';
    $checkExisting = TmpAdminOrders::where('klarna_order_reference','=',$order_id)->first()->toArray();
    $ProductData = json_decode($checkExisting['product_details'],true);
    $user_id = $checkExisting['user_id'];
    
    $currentDate = date('Y-m-d H:i:s');
    //START : Create Order
    $arrOrderInsert = [
                        'user_id'     => $user_id,
                        'address'     => '',
                        'order_lines' => '',
                        'total' => $checkExisting['total'],
                        'payment_details' => '',
                        'payment_status' => '',
                        'order_status' => 'PENDING',
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                        'klarna_order_reference' => $checkExisting['klarna_order_reference'],
                    ];
    $NewOrderId = AdminOrders::create($arrOrderInsert)->id;
    $temp_orders = TmpAdminOrders::find($checkExisting['id']);
    $temp_orders->delete();
     
    $username = env('KLORNA_USERNAME');
    $password = env('KLORNA_PASSWORD');

    $Total = (float)ceil($checkExisting['total'])*100;

    /*capture order after push request recieved from klarna*/
    $capture_url  = env('ORDER_MANAGEMENT_URL').$order_id."/captures";

    $data = <<<DATA
            {
                "captured_amount" : $Total
            }
        DATA;
 
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL,$capture_url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 
      $response = curl_exec($curl);
 
      if (curl_errno($curl)) {
          $error_msg = curl_error($curl);
      }
      curl_close($curl);
 
      if (isset($error_msg)) {
        //echo $error_msg;
         $arrOrderUpdate = [
           'payment_details' => json_encode($response),
           'payment_status' => 'DECLINED',
           'order_status' => 'PENDING',
           'updated_at' => $currentDate,
         ];
 
         AdminOrders::where('id',$NewOrderId)->update($arrOrderUpdate);
         exit;
     }
 
      /* api call to get order details*/
      $url = env('ORDER_MANAGEMENT_URL').$order_id;        
  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      
      $res = curl_exec($ch);
 
      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
      }
      curl_close($ch);
 
      if (isset($error_msg)) {
       //echo $error_msg;
        $arrOrderUpdate = [
          'payment_details' => json_encode($response),
          'payment_status' => 'FAILED',
          'order_status' => 'PENDING',
          'updated_at' => $currentDate,
        ];
 
        AdminOrders::where('id',$NewOrderId)->update($arrOrderUpdate);
        exit;
    }
 
      $response = json_decode($res);
      $order_status = $response->status;

      //dd($response);
      
      /*create file to check push request recieved or not*/
      
      if($order_status == 'CAPTURED')
      {
        $orderLines = $response->order_lines;
        $billing_address = $response->billing_address;
        $shipping_address = $response->shipping_address;

        $address = ['billing' => json_encode($billing_address), 'shipping' => json_encode($shipping_address)];

         $Total = (float)ceil($checkExisting['total']);
         $paymentDetails = ['captures' => $response->captures, 'klarna_reference' => $response->klarna_reference];
         
         $slug =   CommonLibrary::php_cleanAccents($ProductData['product_slug']);
         //Create Product
         $arrProducts = [
            'title'        		=> trim(ucfirst($ProductData['title'])),
            'product_slug'      => trim(strtolower($slug)),
            'meta_title'        => trim($ProductData['meta_title']),       
            'meta_description'  => trim($ProductData['meta_description']),
            'shipping_method'   => '',  
            'shipping_charges'  => '',  
            'meta_keyword'  	=> trim($ProductData['meta_keyword']),
            'description'       => trim($ProductData['description']),
            'status'       		=> trim($ProductData['status']),
            'sort_order'       	=> trim($ProductData['sort_order']),
            'user_id'			=>	$user_id,
            'is_buyer_product'  => '1',
        ];


        
        $id = Products::create($arrProducts)->id;

         if(!empty($ProductData['user_name'])) {
            BuyerProducts::where('product_id',$id)->delete();
            $buyerProductArray['product_id']=$id;
            $buyerProductArray['user_id']=$user_id;
            $buyerProductArray['user_name']=$ProductData['user_name'];
            $buyerProductArray['user_email']=$ProductData['user_email'];
            $buyerProductArray['user_phone_no']=$ProductData['user_phone_no'];
            $buyerProductArray['country']=$ProductData['country'];
            $buyerProductArray['location']=$ProductData['location'];
            //$buyerProductArray['price']=$request->input('price');
            BuyerProducts::create($buyerProductArray);
        }

        //unique product code
        $string     =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $product_code = substr(str_shuffle($string),0, 4).$id;
        Products::where('id', $id)->update(['product_code'=>$product_code]);

        //START : Update Order
        $arrOrderUpdate = [
            'address'     => json_encode($address),
            'order_lines' => json_encode($orderLines),
             'payment_details' => json_encode($paymentDetails),
             'payment_status' => $order_status,
             'order_status' => 'COMPLETE',
             'updated_at' => $currentDate,
             'product_id' => $id,
           ];
        AdminOrders::where('id',$NewOrderId)->update($arrOrderUpdate);

        $producVariant=[];
        if(!empty($ProductData['sku'])) 
        {
            $order = 0; 
            foreach($ProductData['sku'] as $variant_key=>$variant) 
            {
                if($variant!='' && $ProductData['price'][$variant_key]!='' && $ProductData['quantity'][$variant_key]!='') 
                {
		            $producVariant['product_id']=   $id;
		            $producVariant['price']     =   $ProductData['price'][$variant_key];
		            $producVariant['sku']       =   $ProductData['sku'][$variant_key];
		            $producVariant['weight']    =   $ProductData['weight'][$variant_key];
		            $producVariant['quantity']  =   $ProductData['quantity'][$variant_key]; 
		            
                   if(isset($ProductData['hidden_images'][$variant_key]) && !empty($ProductData['hidden_images'][$variant_key]) ) {
                        $producVariant['image'] =   '';
                        foreach($ProductData['hidden_images'][$variant_key] as $img)
                            $producVariant['image'].=   $img.',';
                        $producVariant['image'] =   rtrim($producVariant['image'],',');
                    }
                    if(isset($ProductData['variant_id'][$variant_key])) {

                        $checkVariantExist   =   DB::table('variant_product')->where('id', $ProductData['variant_id'][$variant_key])->first();

                        if(!empty($checkVariantExist)) {
                                VariantProduct::where('id', $checkVariantExist->id)->update($producVariant);
                                $variant_id=$checkVariantExist->id;
                        }
                        else
                          $variant_id=VariantProduct::create($producVariant)->id;
                    }
                    
                    else
		              $variant_id=VariantProduct::create($producVariant)->id;

		            foreach($ProductData['attribute'][$variant_key] as $attr_key=>$attribute) {
                       
		                if($ProductData['attribute'][$variant_key][$attr_key]!='' && $ProductData['attribute_value'][$variant_key][$attr_key])
		                {
		                    $productVariantAttr['product_id']   =   $id;
    		                $productVariantAttr['variant_id']   =   $variant_id;
    		                $productVariantAttr['attribute_id'] =   $ProductData['attribute'][$variant_key][$attr_key];
    		                $productVariantAttr['attribute_value_id'] =   $ProductData['attribute_value'][$variant_key][$attr_key];
                            if(isset($ProductData['variant_attribute_id'][$variant_key][$attr_key])) {
                                $checkRecordExist   =   VariantProductAttribute::where('id', $ProductData['variant_attribute_id'][$variant_key][$attr_key])->first();

                            if(!empty($checkRecordExist)) {
                                VariantProductAttribute::where('id', $checkRecordExist->id)->update($productVariantAttr);
                            }
                            else
                              VariantProductAttribute::create($productVariantAttr);
                            } 
                             else
                              VariantProductAttribute::create($productVariantAttr);
		                }
		                
		            }
		        }
		    }

        }
        
        ProductCategory::where('product_id', $id)->delete();
        $categorySlug = '';
        $subCategorySlug = '';
        if(empty($ProductData['categories'])) 
        {	
           // echo "in";exit;
            $category  =   Subcategories::where('subcategory_name','Uncategorized')->first();
            $ProductData['categories'][]        =  $category->id;
            $producCategories['product_id']     =   $id;
            $producCategories['category_id']    =   $category->category_id;
            $producCategories['subcategory_id'] =   $category->id;
            $subCategorySlug = $category->subcategory_slug;
            ProductCategory::create($producCategories);

            $mainCategory = Categories::where('id',$category->category_id)->first();
            $categorySlug = $mainCategory->category_slug;

        } 
        if(!empty($ProductData['categories'])) 
        {
			 
			 foreach($ProductData['categories'] as $subcategory) {
				 $category	=	Subcategories::where('id',$subcategory)->first();
				 $producCategories['product_id']	=	$id;
				 $producCategories['category_id']	=	$category->category_id;
                 $producCategories['subcategory_id']	=	$category->id;
                 $subCategorySlug = $category->subcategory_slug;
                 ProductCategory::create($producCategories);
                 
                 $mainCategory = Categories::where('id',$category->category_id)->first();
                 $categorySlug = $mainCategory->category_slug;
				 
			 }
         } 
         
        $product_link	=	url('/').'/product';
		$product_link	.=	'/'.$categorySlug;
        $product_link	.=	'/'.$subCategorySlug;
        $product_link	.=	$slug.'-P-'.$product_code;

 
        $GetOrder = AdminOrders::join('users', 'users.id', '=', 'admin_orders.user_id')->select('users.fname','users.lname','users.email','admin_orders.*')->where('admin_orders.id','=',$NewOrderId)->get()->toArray();

        //START : Send success email to User.
        $email = trim($GetOrder[0]['email']);
        $name  = trim($GetOrder[0]['fname']).' '.trim($GetOrder[0]['lname']);

        // $arrMailData = ['name' => $name, 'email' => $email, 'product_details_link' => $product_link];

        // Mail::send('emails/product_success', $arrMailData, function($message) use ($email,$name) {
        //     $message->to($email, $name)->subject
        //         ('Tijara - Product Posted successfully.');
        //     $message->from('developer@techbeeconsulting.com','Tijara');
        // });

        $GetEmailContents = getEmailContents('Product Published');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];
        
        $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##'],[$name,$email,url('/'),$product_link],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$name,$subject) {
            $message->to($email, $name)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });
        
        //END : Send success email to User.


        //START : Send success email to Admin.
        $admin_email = 'shrik.techbee@gmail.com';
        $admin_name  = 'Tijara Admin';
        
        // $arrMailData = ['product_details_link' => $product_link];

        // Mail::send('emails/product_success_admin', $arrMailData, function($message) use ($admin_email,$admin_name) {
        //     $message->to($admin_email, $admin_name)->subject
        //         ('Tijara - New Product Posted.');
        //     $message->from('developer@techbeeconsulting.com','Tijara');
        // });

        $GetEmailContents = getEmailContents('Product Published Admin');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];
        
        $contents = str_replace(['##SITE_URL##','##LINK##'],[url('/'),$product_link],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($admin_email,$admin_name,$subject) {
            $message->to($admin_email, $admin_name)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });
        //END : Send success email to Seller.
    }
    else
    {
        $arrOrderUpdate = [
        'payment_details' => json_encode($response),
        'payment_status' => $order_status,
        'order_status' => 'PENDING',
        'order_complete_at' => '',
        'updated_at' => $currentDate,
        ];

        AdminOrders::where('id',$checkExisting['id'])->update($arrOrderUpdate);
    }

    exit;
 
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

                         /*resized for product details page*/
                        $mime = getimagesize($path);
            
            
            
                                if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
            
                                if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
            
                                if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
            
                                if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }
            
            
            
                                $old_x = imageSX($src_img);
            
                                $old_y = imageSY($src_img);
            
            
            
                                $newWidth = 800;
            
                                $newHeight = 800;
            
            
            
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
            
                                $new_thumb_loc = public_path().'/uploads/ProductImages/productDetails/' . $fileName;
            
            
            
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
        $id = base64_decode($request->id);
        // Clean up multiple dashes or whitespaces
        $slug_trim = trim(preg_replace('/\s+/', ' ', $slug_name));
        // Convert whitespaces and underscore to dash
        $slug_hypen = preg_replace("/[\s_]/", "-", $slug_trim); 
        $slug_p = str_replace("-p-", '', $slug_hypen); 
        $slug_s = str_replace("-s-", '', $slug_p); 
        $slug =   CommonLibrary::php_cleanAccents($slug_s);
    
        if(!empty($id)){
            $data =  Products::where('product_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  Products::where('product_slug', $slug)->get();
        }

       $unique_slug =1;
        if(!empty($data[0]['product_slug'])){
            do{
                    $slug = $slug."-".$unique_slug;
                    if(!empty($id)){
                        $data =  Products::where('product_slug', $slug)->where('id','!=',$id)->get();
                    } else{
                        $data =  Products::where('product_slug', $slug)->get();
                    }
                }while(!empty($data[0]['product_slug']));
                return $slug;
        }else{
            return $slug;
        }
       
    }

    /*function show buyer products*/
    public function listBuyerProduct() {

        $data = [];
        $data['subscribedError']   =    '';
        if(!Auth::guard('user')->id()) {
            return redirect(route('frontLogin'));
        }
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        //$buyerProducts   =   Products::where('user_id',Auth::guard('user')->id())->get();
        $buyerProducts = Products::Leftjoin('variant_product', 'products.id', '=', 'variant_product.product_id')
                            ->select(['products.*','variant_product.sku','variant_product.price','variant_product.image'])
                            ->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id())->groupBy('products.id');


         $buyerProducts       = $buyerProducts->paginate(12);
       /* $checkProductExistOfBuyer   =   Products::where('user_id',Auth::guard('user')->id())->first();
            
        if(!empty($checkProductExistOfBuyer)) {
            Session::flash('success', trans('lang.product_saved_success'));

            return redirect(route('frontProductEdit',base64_encode($checkProductExistOfBuyer->id)));
        }
        else{
            //Session::flash('success', trans('lang.product_saved_success'));
            return redirect(route('frontProductCreate'));
    
        }
         */      

        $data['pageTitle']              = trans('lang.products_title');

        $data['current_module_name']    = trans('lang.products_title');

        $data['module_name']            = trans('lang.products_title');

        $data['module_url']             = route('manageFrontProducts');

        $data['recordsTotal']           = 0;

        $data['currentModule']          = '';
        $data['buyerProducts']          = $buyerProducts;

      /*  $CategoriesAndSubcategories     = Products::Leftjoin('category_products', 'products.id', '=', 'category_products.product_id')
                                            ->Leftjoin('categories', 'categories.id', '=', 'category_products.category_id')
                                            ->Leftjoin('subcategories', 'subcategories.id', '=', 'category_products.subcategory_id')
                                            ->select(['categories.category_name','subcategories.id','subcategories.category_id','subcategories.subcategory_name'])
                                            ->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id())
                                            ->groupBy('subcategories.id')->orderBy('categories.sequence_no')->get();
                 //  DB::enableQueryLog();
    // and then you can get query log
 //   dd(DB::getQueryLog());

        $categoriesArray                =   array();
        foreach($CategoriesAndSubcategories as $category) {
            $categoriesArray[$category->category_id]['mainCategory']    =   $category->category_name;
            
            $categoriesArray[$category->category_id]['subcategories'][$category->id]=   $category->subcategory_name;
        }
        
        $categoriesHtml                 =   '<option value="">'.trans('lang.category_label').'</option>';
        
        $subCategoriesHtml              =   '<option value="">'.trans('lang.subcategory_label').'</option>';
        
        foreach($categoriesArray as $category_id=>$category) {
            if($category['mainCategory']!='')
            $categoriesHtml             .=  '<option value="'.$category_id.'">'.$category['mainCategory'].'</option>';
        
            foreach($category['subcategories'] as $subcategory_id=>$subcategory) {
                if($subcategory!='')
                $subCategoriesHtml      .=  '<option class="subcatclass subcat'.$category_id.'" style="display:none;" id="subcat'.$category_id.'" value="'.$subcategory_id.'">'.$subcategory.'</option>';
            }
        }
  
        $data['categoriesHtml']         = $categoriesHtml;
        $data['subCategoriesHtml']      = $subCategoriesHtml;
        */
        return view('Front/Products/listBuyerProducts', $data);

    }


}


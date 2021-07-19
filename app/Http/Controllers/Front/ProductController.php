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

use App\CommonLibrary;

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
                Session::flash('success', trans('lang.product_saved_success'));

                return redirect(route('frontProductEdit',base64_encode($checkProductExistOfBuyer->id)));
            }
            else
                Session::flash('success', trans('lang.product_saved_success'));
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
		$ProductsDetails = $ProductsDetails->groupBy('products.id');
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
    $max_seq_no ='';
    $max_seq_no = Products::max('sort_order');
    $data['max_seq_no'] = $max_seq_no + 1;

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
	    //$data['attributesToSelect'] =   Attributes::where('user_id',Auth::guard('user')->id())->get(); 
        $data['attributesToSelect'] =   Attributes::select('*')->get(); 

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
           // echo'<pre>';print_r($VariantProductAttributeArr);exit;
			$data['VariantProductAttributeArr']         =   $VariantProductAttributeArr;
			return view('Front/Products/edit', $data);
		}
		else {
			$data['product_id']			=	0;
			$data['VariantProduct']     =   array();
			return view('Front/Products/create', $data);
		}
    }

    
    public function store(Request $request) {
        
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

		$producVariant=[];
		if(!empty($request->input('sku'))) {
            
		           $order = 0; 
		    foreach($request->input('sku') as $variant_key=>$variant) {
               
               

		        if($variant!='' && $_POST['price'][$variant_key]!='' && $_POST['quantity'][$variant_key]!='') {
		            $producVariant['product_id']=   $id;
		            $producVariant['price']     =   $_POST['price'][$variant_key];
		            $producVariant['sku']       =   $_POST['sku'][$variant_key];
		            $producVariant['weight']    =   $_POST['weight'][$variant_key];
		            $producVariant['quantity']  =   $_POST['quantity'][$variant_key]; 
		            
                   if(isset($_POST['hidden_images'][$variant_key]) && !empty($_POST['hidden_images'][$variant_key]) ) {
                        $producVariant['image'] =   '';
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
                        else
                          $variant_id=VariantProduct::create($producVariant)->id;
                    }
                    
                    else
		              $variant_id=VariantProduct::create($producVariant)->id;

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
                             else
                              VariantProductAttribute::create($productVariantAttr);
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
        $id = base64_decode($request->id);
        // Clean up multiple dashes or whitespaces
        $slug_trim = trim(preg_replace('/\s+/', ' ', $slug_name));
        // Convert whitespaces and underscore to dash
        $slug_hypen = preg_replace("/[\s_]/", "-", $slug_trim);  
        $slug =   CommonLibrary::php_cleanAccents($slug_hypen);
     
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


}


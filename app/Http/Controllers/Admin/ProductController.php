<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\UserMain;

use App\Models\Products;

use App\Models\City;

use App\Models\ProductsImages;

use App\Models\UserPackages;

use App\Models\Categories;

use App\Models\Subcategories;

use App\Models\Attributes;

use App\Models\ AttributesValues;

use App\Models\ VariantProductAttribute;

use App\Models\ VariantProduct;

use App\Models\BuyerProducts;

use App\Models\ProductCategory;

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



    }



    /**

     * Show list of records for products.

     * @return [array] [record array]

     */

    public function index() {

        $data = [];

        $data['pageTitle']              = trans('lang.products_title');

        $data['current_module_name']    = trans('lang.products_title');

        $data['module_name']            = trans('lang.products_title');

        $data['module_url']             = route('adminProduct');

        $data['recordsTotal']           = 0;

        $data['currentModule']          = '';



        return view('Admin/Product/index', $data);

    }



    public function exportdata(Request $request) {

        $ProductsDetails = Products::select('products.*')->where('is_deleted','!=',1);



        if(!empty($request->search)) {



            $field = ['products.title','products.description'];

            $namefield = ['products.title','products.description'];

            $search=($request->search);



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



        if(!empty($request->status)) {

            $ProductsDetails = $ProductsDetails->Where('products.status', '=', $request->status);

        }



        $recordDetails = $ProductsDetails->get(['products.title','products.sell_price','products.list_price','products.description','products.sort_order']);



        $filename = "ProductsFromTijara.csv";



        $handle = fopen('ProductsDetails/'.$filename, 'w+');

        fputcsv($handle, array('Seller','Products','Description','Categories','Sell Price','List Price','Sort Order','Status'));



        foreach($recordDetails as $row) {

            fputcsv($handle, array($row->title,$row->sell_price,$row->list_price,$row->description,$row->sort_order,$row->status));

        }



        fclose($handle);

        return $filename;

    }





    /**

     * [getRecords for product list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) {

        $ProductsDetails = Products::Leftjoin('users', 'users.id', '=', 'products.user_id')->select(['products.*','users.fname','users.lname','variant_product.sku','variant_product.price','variant_product.image']) ->Leftjoin('variant_product', 'products.id', '=', 'variant_product.product_id')
						->where('products.is_deleted','!=',1)->where('users.is_deleted','!=',1);

                           // $ProductsDetails = Products::Leftjoin('category_products', 'products.id', '=', 'category_products.product_id')   
                           //                  ->Leftjoin('variant_product', 'products.id', '=', 'variant_product.product_id')
                           //                  ->select(['products.*','variant_product.sku','variant_product.price','variant_product.image'])
                           //                  ->where('products.is_deleted','!=',1)->where('products.user_id',Auth::guard('user')->id());

		if(!empty($request['search']['value'])) {



          $field = ['users.fname','users.lname','products.title','products.description'];

		  $namefield = ['users.fname','users.lname','products.title','products.description'];

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



        if (!empty($request['status']) && !empty($request['search']['value'])) {

            $ProductsDetails = $ProductsDetails->Where('products.status', '=', $request['status']);

        }

        else if(!empty($request['status'])) {

            $ProductsDetails = $ProductsDetails->Where('products.status', '=', $request['status']);

        }

        if(isset($request['order'][0])){

            $postedorder=$request['order'][0];
            if($postedorder['column']==0) $orderby='products.id';
            if($postedorder['column']==1) $orderby='products.title';

            if($postedorder['column']==2) $orderby='variant_product.sku';
            
            if($postedorder['column']==3) $orderby='variant_product.price';
            
            if($postedorder['column']==5) $orderby='products.sort_order';
            
            if($postedorder['column']==6) $orderby='products.created_at';


            if($postedorder['column']==0){
                $orderorder="DESC";
            }else{
                $orderorder=$postedorder['dir'];
            }
            

            $ProductsDetails = $ProductsDetails->orderby($orderby, $orderorder);

        }



        $recordsTotal = $ProductsDetails->count();

        $recordDetails = $ProductsDetails->offset($request->input('start'))->limit($request->input('length'))->get();

        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal)

            {
                //echo "<pre>";print_r($recordDetailsVal);exit;
                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                if(!empty($recordDetailsVal['image'])) {
                    $imagesParts    =   explode(',',$recordDetailsVal['image']);
                    
                   $image_path  =   url('/').'/uploads/ProductImages/productIcons/'.$imagesParts[0];
                    $image  =  '/uploads/ProductImages/productIcons/'.$imagesParts[0];
                }
               
                if(file_exists(public_path($image))){
                    $image      =   '<img src="'.$image_path.'" width="70" height="70">';
                }else{
                    $no_image =  url('/').'/uploads/ProductImages/productIcons/no-image.png';
                    $image      =   '<img src="'.$no_image.'" width="70" height="70">';
                }
                

                $uname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';

				$title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                $sku   = (!empty($recordDetailsVal['sku'])) ? $recordDetailsVal['sku'] : '-';
                $price = (!empty($recordDetailsVal['price'])) ? $recordDetailsVal['price']." kr" : '-';
              
                date_default_timezone_set("Europe/Stockholm");  
                $dated = $recordDetailsVal['created_at'];
                $dated = date('Y-m-d g:i a',strtotime("$dated UTC"));

                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

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
/*

                if ($recordDetailsVal['status'] == 'active') {

                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminProductChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';

                 } else {

                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminProductChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';

                 }



                $action = '<a href="'.route('adminProductEdit', base64_encode($id)).'" title="Edit" class="btn btn-icon btn-success"><i class="fas fa-comments"></i></a>&nbsp;&nbsp;';
*/
                 $action = '<a href="'.route('adminReviews' ,['product',base64_encode($id)]).'" title="'.trans('users.review_title').'" class="btn btn-icon btn-success"><i class="fas fa-comments"></i></a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminProductDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';



                $arr[] = [$image ,$uname, $title, $sku, $price, $categoriesData, $dated ,$action];

            }

        }

        else {

            $arr[] = ['','',  '', 'No Records Found', '', '', '', ''];

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

    public function create() {



        $data['pageTitle']              =  trans('lang.add_product');

        $data['current_module_name']    = trans('lang.save_service_date_btn');

        $data['module_name']            =  trans('lang.category_product_title');

        $data['module_url']             = route('adminProduct');

		$data['users']					=  UserMain::select(['users.id','users.role_id','users.fname','users.lname'])->where('is_deleted','!=',1)->get();

		$categories						=  Categories::Leftjoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
											->select('*')->get();

		$categoriesArray				=	array();
		foreach($categories as $category) {
			$categoriesArray[$category->category_id]['maincategory']	=	$category->category_name;
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}

        $data['attributesToSelect'] =   Attributes::select('*')->get(); 

		$data['categories']				=	$categoriesArray;
        return view('Admin/Product/create', $data);

    }





    public function store(Request $request) {


        //echo'<pre>';print_r($_POST);exit;
        $product_slug = $request->input('product_slug');
        $slug =   CommonLibrary::php_cleanAccents($product_slug);

        $rules = [ 
            'title'         => 'required',
            'description'   => 'required|max:3000',
            'sort_order'    =>'numeric',      
            'product_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u',
            'categories'  => 'required',  

        ];

        $messages = [
            'title.required'         =>  trans('lang.required_field_error'),           
            //'title.regex'            => trans('lang.required_field_error'), 
            'description.required'   => trans('lang.required_field_error'),    
            'description.max'        => trans('lang.max_1000_char'),
            'product_slug.required'  => trans('errors.product_slug_req'),
            'product_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'categories.required'  => trans('lang.required_field_error'), 
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
            $messages = $validator->messages();
           // echo "<pre>";print_r( $messages );exit;
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrProducts = [

                'title'             => trim(ucfirst($request->input('title'))),

                'product_slug'      => trim(strtolower($slug)),

                'meta_title'        => trim($request->input('meta_title')),       

                'meta_description'  => trim($request->input('meta_description')),

                'shipping_method'   =>  trim($request->input('shipping_method_ddl')),  

                'shipping_charges'  =>  trim($request->input('shipping_charges')),  
                
                'meta_keyword'      => trim($request->input('meta_keyword')),

                'description'       => trim($request->input('description')),

                'status'            => trim($request->input('status')),
                
                'discount'          => trim($request->input('discount')),
                'free_shipping'     =>  trim($request->input('free_shipping')),

                'sort_order'        => trim($request->input('sort_order')),
               
                'user_id'           =>  Auth::guard('user')->id(),
                'store_pick_address'  => trim($request->input('store_pick_address')),
                'is_pick_from_store'  => trim($request->input('is_pick_from_store')),
            ];


        if($request->input('product_id')==0) {
            $id = Products::create($arrProducts)->id;
            //unique product code
            $string     =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            Products::where('id', $id)->update(['product_code'=>substr(str_shuffle($string),0, 4).$id]);

        } else {
            $id     = $request->input('product_id');
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
                 $category  =   Subcategories::where('id',$subcategory)->first();
                 $producCategories['product_id']    =   $id;
                 $producCategories['category_id']   =   $category->category_id;
                 $producCategories['subcategory_id']    =   $category->id;
                 ProductCategory::create($producCategories);
                 
             }
         } 
   
        Session::flash('success', trans('lang.product_saved_success'));

        return redirect(route('adminProduct'));

    }



    /**

     * Edit record details

     * @param  $id = User Id

     */

    public function edit($id) {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Refresh your page.');

            return redirect()->back();

        }



        $data = $details = [];



        $data['id'] = $id;

        $id = base64_decode($id);

        //$details=Products::get_Products($id);



       // $imagedetails=  Products::where('id', $id)->with(['getImages'])->first();



        // if(empty($details)) {

        //     Session::flash('error', 'Something went wrong. Refresh your page.');

        //     return redirect()->back();

        //  }

        $categories                     =  Categories::Leftjoin('subcategories', 'categories.id', '=', 'subcategories.category_id')->where('categories.status','=','active')->where('subcategories.status','=','active')
                                            ->select('*')->get();
                                            
        $categoriesArray                =   array();
        
        foreach($categories as $category) {
            
            $categoriesArray[$category->category_id]['maincategory']    =   $category->category_name;
            
            $categoriesArray[$category->category_id]['subcategories'][$category->id]=   $category->subcategory_name;
        }
        $data['categories']             =   $categoriesArray;
        //$data['attributesToSelect'] =   Attributes::where('user_id',Auth::guard('user')->id())->get(); 
        $data['attributesToSelect'] =   Attributes::select('*')->get(); 

        if($id) {
            $product_id                 =  $id;
      
            $data['product_id']         =   $product_id;
            $data['product']            =   Products::where('id',$product_id)->first();
            $data['buyerProduct']       =   BuyerProducts::where('product_id',$product_id)->first();
           // $data['buyerProduct']       =   Products::where('id',$product_id)->first();
            
            //$data['AttributesValues']  =   AttributesValues::get();
          
            $selectedCategories         =   ProductCategory::where('product_id',$product_id)->get();
            $selectedCategoriesArray    =   array();
            foreach($selectedCategories as $category) {
                $selectedCategoriesArray[]= $category->subcategory_id;
            }
            
            $data['selectedCategories'] =   $selectedCategoriesArray;
            
        //  $data['VariantProduct']     =   VariantProduct::where('product_id',$product_id)->orderBy('id','asc')->get();
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
        }
        $data['pageTitle']              = 'Edit Products';

        $data['current_module_name']    = 'Edit';

        $data['module_name']            = 'Products';

        $data['module_url']             = route('adminProduct');

      

     



        return view('Admin/Product/edit', $data);

    }


     /**

     * Delete Record

     * @param  $id = Id

     */

    public function delete($id) {

        if(empty($id)) {

            Session::flash('error', trans('errors.something_went_wrong'));

            return redirect(route('adminProduct'));

        }



        $id = base64_decode($id);

        $result = Products::find($id);



        if (!empty($result)) {

           $product = Products::where('id', $id)->update(['is_deleted' =>1]);

           Session::flash('success',trans('lang.record_delete'));

                return redirect()->back();

        } else {

            Session::flash('error', trans('errors.something_went_wrong'));

            return redirect()->back();

        }

    }

}

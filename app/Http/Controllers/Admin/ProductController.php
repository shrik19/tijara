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

        $data['pageTitle']              = 'Products';

        $data['current_module_name']    = 'Products';

        $data['module_name']            = 'Products';

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

        $ProductsDetails = Products::Leftjoin('users', 'users.id', '=', 'products.user_id')->select(['products.*','users.fname','users.lname'])
						->where('products.is_deleted','!=',1)->where('users.is_deleted','!=',1);

         

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

            if($postedorder['column']==0) $orderby='users.fname';
			
			if($postedorder['column']==1) $orderby='products.title';

            if($postedorder['column']==2) $orderby='products.sell_price';

            if($postedorder['column']==3) $orderby='products.list_price';            

            if($postedorder['column']==4) $orderby='products.sort_order';

			if($postedorder['column']==5) $orderby='products.created_at';
            

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

                $uname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';
				
				$title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';

                $sell_price = (!empty($recordDetailsVal['sell_price'])) ? $recordDetailsVal['sell_price'] : '-';

                $list_price = (!empty($recordDetailsVal['list_price'])) ? $recordDetailsVal['list_price'] : '-';

                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

                

                if ($recordDetailsVal['status'] == 'active') {

                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminProductChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';

                 } else { 

                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminProductChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';

                 }

              

                $action = '<a href="'.route('adminProductEdit', base64_encode($id)).'" title="Edit" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';



                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminProductDelete', base64_encode($id)).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

            

                $arr[] = [$uname, $title, $sell_price, $list_price, $sort_order, $status, $action];

            }

        } 

        else {

            $arr[] = ['',  '', '', 'No Records Found', '', '', '', ''];

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

    

        $data['pageTitle']              = 'Add Products';

        $data['current_module_name']    = 'Add';

        $data['module_name']            = 'Products';

        $data['module_url']             = route('adminProduct');
		
		$data['users']					=  UserMain::select(['users.id','users.role_id','users.fname','users.lname'])->where('is_deleted','!=',1)->get();
      
		$categories						=  Categories::Leftjoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
											->select('*')->get();
											
		$categoriesArray				=	array();
		foreach($categories as $category) {
			$categoriesArray[$category->category_id]['maincategory']	=	$category->category_name;
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		$data['categories']				=	$categoriesArray;
        return view('Admin/Product/create', $data);

    }





    public function store(Request $request) {

  

        $rules = [ 

            'title'         => 'required|regex:/^[\pL\s\-]+$/u',

            'sell_price'         => 'numeric',
			'list_price'         => 'required|numeric',
           
            'description'   => 'nullable|max:500',

           

           /* 'phone_number'  => 'required',

            'address'       => 'required',

            'postcode'      => 'required',

            'description'          => 'required',

            'swish_number'  => 'required',

            'list_price'    => 'required',

            'paypal_email'  => 'required',

            'productimages'  => 'required',*/

        ];



        $messages = [

            'title.required'         => 'Please fill in Products name',

            'list_price.required'         => 'Please fill in Sell price',

            'title.regex'            => 'Please input alphabet characters only',

            'description.max'        => 'Maximum 500 characters allowed',

            

        ];



        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {

            $messages = $validator->messages();

            return redirect()->back()->withInput($request->all())->withErrors($messages);

        }

          

        $arrProductsInsert = [

               

                'title'        => trim($request->input('title')),

                'sell_price'        => trim($request->input('sell_price')),       

				'list_price'   => trim($request->input('list_price')),

                'description'         => trim($request->input('description')),

               

            ];



        $id = Products::create($arrProductsInsert)->id;

         

   

        if($request->hasfile('productimages')){

            $fileError = 0;

            $order = (ProductsImages::where('user_id','=',$id)->count())+1;

               

            foreach($request->file('productimages') as $image) {

                $name=$image->getClientOriginalName();

                $fileExt  = strtolower($image->getClientOriginalExtension());



                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {

                    $fileName = 'Products'.$id.'_'.date('YmdHis').'_'.$order.'.'.$fileExt;

                    $image->move(public_path().'/uploads/ProductsImages/', $fileName);  // your folder path



                    $path = public_path().'/uploads/ProductsImages/'.$fileName;

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

                    $new_thumb_loc = public_path().'/uploads/ProductsImages/resized/' . $fileName;



                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }

                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }



                    imagedestroy($dst_img);

                    imagedestroy($src_img);



                    $arrInsert = ['user_id'=>$id,'images'=>$fileName,'image_order'=>$order];

                    productimages::insert($arrInsert);

                    $order++;



                } else {

                        $fileError = 1;

                }

            }



            if($fileError == 1) {

                Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');

                return redirect()->back();

            }

        } 



        Session::flash('success', 'Products details Inserted successfully!');

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

        $details=Products::get_Products($id);



        $imagedetails=  Products::where('id', $id)->with(['getImages'])->first();



        if(empty($details)) {

            Session::flash('error', 'Something went wrong. Refresh your page.');

            return redirect()->back();   

         }



        $data['pageTitle']              = 'Edit Products';

        $data['current_module_name']    = 'Edit';

        $data['module_name']            = 'Products';

        $data['module_url']             = route('adminProduct');

        $data['productDetails']          = $details;

        $data['imagedetails']           =  $imagedetails;



        return view('Admin/Product/edit', $data);

    }



    

    /**

     * Change status for Record [active/block].

     * @param  $id = Id, $status = active/block 

     */

    public function changeStatus($id, $status)  {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('adminCustomer'));

        }

        $id = base64_decode($id);



        $result = Products::where('id', $id)->update(['status' => $status]);

        if ($result) {

            Session::flash('success', 'Status updated successfully!');

            return redirect()->back();

         } else  {

            Session::flash('error', 'Oops! Something went wrong!');

            return redirect()->back();

        }

    }



     /**

     * Delete Record

     * @param  $id = Id

     */

    public function delete($id) {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('adminProduct'));

        }



        $id = base64_decode($id);

        $result = Products::find($id);



        if (!empty($result)) {

           $product = Products::where('id', $id)->update(['is_deleted' =>1]);

           Session::flash('success', 'Record deleted successfully!');

                return redirect()->back();  

        } else {

            Session::flash('error', 'Oops! Something went wrong!');

            return redirect()->back();

        }

    }





    /* funtion to delete image on edit form 

    @param : $id

    */

     public function deleteImage($id) {

        if(empty($id))  {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('adminProduct'));

        }

        $id = base64_decode($id);

        $result = productimages::find($id);

        

        if (!empty($result)) 

        {

            if ($result->delete()) 

            {

                Session::flash('success', 'Selected Image deleted successfully!');

                return redirect()->back();

            } 

            else 

            {

                Session::flash('error', 'Oops! Something went wrong!');

                return redirect()->back();

            }

        } 

        else 

        {

            Session::flash('error', 'Oops! Something went wrong!');

            return redirect()->back();

        }

    }




}


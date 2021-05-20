<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMain;

/*Uses*/

use Auth;
use Session;
use flash;
use Validator;
use DB;

class ServicesController extends Controller {

    /*
	 * Define abjects of models, services.
	 */

    function __construct() {
    
    }

    /**
     * Show list of records for Services.
     * @return [array] [record array]
     */

    public function index() {

        $data = [];
        $data['pageTitle']              = 'Services';
        $data['current_module_name']    = 'Services';
        $data['module_name']            = 'Services';
       // $data['module_url']             = route('frontSellerServices');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
		
        return view('Front/Services/index', $data);

    }
    
    /**

     * [getRecords for Services list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */

    public function getRecords(Request $request) {

		if(!empty($request['search']['value'])) {

			

          $field = ['services.title','services.description','services.sort_order'];

		  $namefield = ['Services.title','Services.description','services.sort_order'];

          $search=($request['search']['value']);

            $ServicesDetails = $ServicesDetails->Where(function ($query) use($search, $field,$namefield) {

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

            $ServicesDetails = $ServicesDetails->Where('services.status', '=', $request['status']);

        }

        else if(!empty($request['status'])) {

            $ServicesDetails = $ServicesDetails->Where('services.status', '=', $request['status']);

        }
		
		// if(!empty($request['category']) || !empty($request['subcategory'])) {
		// 	if(!empty($request['category'])) {

		// 		$ServicesDetails = $ServicesDetails->Where('category_Services.category_id', '=', $request['category']);

		// 	}
			
		// 	if(!empty($request['subcategory'])) {

		// 		$ServicesDetails = $ServicesDetails->Where('category_Services.subcategory_id', '=', $request['subcategory']);

		// 	}
		// 	$ServicesDetails = $ServicesDetails->groupBy('category_Services.product_id');
		// }
        if(isset($request['order'][0])){
            $postedorder=$request['order'][0];
			if($postedorder['column']==0) $orderby='services.title';
			if($postedorder['column']==1) $orderby='services.created_at';
            if($postedorder['column']==2) $orderby='services.description';
            $orderorder=$postedorder['dir'];
            $ServicesDetails = $ServicesDetails->orderby($orderby, $orderorder);
        }

        $recordsTotal = $ServicesDetails->count();
        $recordDetails = $ServicesDetails->offset($request->input('start'))->limit($request->input('length'))->get();

        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal)  {

                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';

                
				$title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';

               
                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

                $description = (!empty($recordDetailsVal['description'])) ? $recordDetailsVal['description'] : '-';

                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('frontServiceChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';

                 } else { 
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('frontServiceChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';
                 }

                $action = '<a href="'.route('frontServiceEdit', base64_encode($id)).'" title="Edit" class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('frontServiceDelete', base64_encode($id)).'\');"  title="Delete" class=""><i class="fas fa-trash"></i></a>';

                $arr[] = [ $title, $sort_order, $description, $action];
            }
        } 
        else {

            $arr[] = [ '', 'No Records Found', '', ''];
        }

        $json_arr = [

            'draw'            => $request->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => ($arr),
        ];

        return json_encode($json_arr);
    }



     /* function to open Services create form */

    public function serviceform($id='') {
        $data['pageTitle']              = 'Save Service';
        $data['current_module_name']    = 'Save';
        $data['module_name']            = 'Services';
        $data['module_url']             = route('frontSellerServices');		
		
		$categories						=  Categories::Leftjoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
											->select('*')->get();
											
		$categoriesArray				=	array();
		
		foreach($categories as $category) {
			
			$categoriesArray[$category->category_id]['maincategory']	=	$category->category_name;
			
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		$data['categories']				=	$categoriesArray;
		
		if($id) {
			$product_id					=	base64_decode($id);
			$data['product_id']			=	$product_id;
			$data['product']			=	Services::where('id',$product_id)->first();
            $data['attributes']         =   Attributes::get();
            //$data['AttributesValues']   =   AttributesValues::get();
          
			$selectedCategories			=	ProductCategory::where('product_id',$product_id)->get();
			$selectedCategoriesArray	=	array();
			foreach($selectedCategories as $category) {
				$selectedCategoriesArray[]=	$category->subcategory_id;
			}
			
			$data['selectedCategories']	=	$selectedCategoriesArray;
			return view('Front/Services/edit', $data);
		}
		else {
			$data['product_id']			=	0;
			return view('Front/Services/create', $data);
		}
			
		
		
        

    }





    public function store(Request $request) {

        $rules = [ 
            'title'         => 'required|regex:/^[\pL\s\-]+$/u',
            'description'   => 'nullable|max:500',
            'sort_order'		=>'numeric',        
        ];

        $messages = [
            'title.required'         => 'Please fill in Services name',           
            'title.regex'            => 'Please input alphabet characters only',
            'description.max'        => 'Maximum 500 characters allowed',
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrServices = [

                'title'        		=> trim($request->input('title')),

                'meta_title'        => trim($request->input('meta_title')),       

				'meta_description'  => trim($request->input('meta_description')),
				
				'meta_keyword'  	=> trim($request->input('meta_keyword')),

                'description'       => trim($request->input('description')),

				'status'       		=> trim($request->input('status')),
				
				'sort_order'       	=> trim($request->input('sort_order')),
               
				'user_id'			=>	Auth::guard('user')->id()
            ];


		if($request->input('product_id')==0) {
			$id = Services::create($arrServices)->id;
		} else {
			$id		= $request->input('product_id');
			Services::where('id', $request->input('product_id'))->where('user_id', Auth::guard('user')->id())->update($arrServices);
		}
			
         if(!empty($request->input('categories'))) {
			 ProductCategory::where('product_id', $id)->delete();
			 
			 foreach($request->input('categories') as $subcategory) {
				 $category	=	Subcategories::where('id',$subcategory)->first();
				 $producCategories['product_id']	=	$id;
				 $producCategories['category_id']	=	$category->category_id;
				 $producCategories['subcategory_id']	=	$category->id;
				 
				 ProductCategory::create($producCategories);
				 
			 }
		 }

   

        if($request->hasfile('productimages')){

            $fileError = 0;

            $order = (ServicesImages::where('user_id','=',$id)->count())+1;

               

            foreach($request->file('productimages') as $image) {

                $name=$image->getClientOriginalName();

                $fileExt  = strtolower($image->getClientOriginalExtension());



                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {

                    $fileName = 'Services'.$id.'_'.date('YmdHis').'_'.$order.'.'.$fileExt;

                    $image->move(public_path().'/uploads/ServicesImages/', $fileName);  // your folder path



                    $path = public_path().'/uploads/ServicesImages/'.$fileName;

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

                    $new_thumb_loc = public_path().'/uploads/ServicesImages/resized/' . $fileName;



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



        Session::flash('success', 'Services details Inserted successfully!');

        return redirect(route('manageFrontServices')); 

    }



    /**

     * Edit record details

     * @param  $id = User Id

     

    public function edit($id) {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Refresh your page.');

            return redirect()->back();

        }



        $data = $details = [];

         

        $data['id'] = $id;

        $id = base64_decode($id);

        $details=Services::get_Services($id);



        $imagedetails=  Services::where('id', $id)->with(['getImages'])->first();



        if(empty($details)) {

            Session::flash('error', 'Something went wrong. Refresh your page.');

            return redirect()->back();   

         }



        $data['pageTitle']              = 'Edit Services';

        $data['current_module_name']    = 'Edit';

        $data['module_name']            = 'Services';

        $data['module_url']             = route('frontProduct');

        $data['productDetails']          = $details;

        $data['imagedetails']           =  $imagedetails;



        return view('Front/Services/edit', $data);

    }

*/

    

    /**

     * Change status for Record [active/block].

     * @param  $id = Id, $status = active/block 

     */

    public function changeStatus($id, $status)  {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('frontCustomer'));

        }

        $id = base64_decode($id);



        $result = Services::where('id', $id)->update(['status' => $status]);

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

            return redirect(route('frontProduct'));

        }



        $id = base64_decode($id);

        $result = Services::find($id);



        if (!empty($result)) {

           $product = Services::where('id', $id)->update(['is_deleted' =>1]);

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

            return redirect(route('frontProduct'));

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


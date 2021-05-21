<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserMain;
use App\Models\City;
use App\Models\SellerImages;
use App\Models\UserPackages;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class SellerController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for sellers.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.sellers_title');
        $data['current_module_name']    = trans('users.sellers_title');
        $data['module_name']            = trans('users.sellers_title');
        $data['module_url']             = route('adminSeller');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Seller/index', $data);
    }
    
    public function exportdata(Request $request) {           
        $SellerDetails = UserMain::select('users.*')->where('role_id','=',2)->where('is_deleted','!=',1);
         
        if(!empty($request->search)) {
			
            $field = ['users.fname','users.lname','users.store_name','users.city','users.where_find_us'];
            $namefield = ['users.fname','users.lname','users.store_name','users.city','users.where_find_us'];
            $search=($request->search);
            
            $SellerDetails = $SellerDetails->Where(function ($query) use($search, $field,$namefield) {
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
            $SellerDetails = $SellerDetails->Where('users.status', '=', $request->status);
        }

        $recordDetails = $SellerDetails->get(['users.fname','users.lname','users.store_name','users.city','users.where_find_us']);
           
        $filename = "SellerFromTijara.csv";
       
        $handle = fopen('SellerDetails/'.$filename, 'w+');
        fputcsv($handle, array('First name','Last name','Store Name','City','Where Find Us','Status'));
   
        foreach($recordDetails as $row) {
            fputcsv($handle, array($row->fname,$row->lname,$row->store_name,$row->city,$row->where_find_us,$row->status));
        }
    
        fclose($handle);
        return $filename;
    }


    /**
     * [getRecords for seller list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {
        $SellerDetails = UserMain::select('users.*')->where('users.role_id','=',2)->where('users.is_deleted','!=',1);
         
		if(!empty($request['search']['value'])) {
			
          $field = ['users.fname','users.lname','users.store_name','users.city','users.where_find_us'];
		  $namefield = ['users.fname','users.lname','users.store_name','users.city','users.where_find_us'];
          $search=($request['search']['value']);
            
            $SellerDetails = $SellerDetails->Where(function ($query) use($search, $field,$namefield) {
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
            $SellerDetails = $SellerDetails->Where('users.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $SellerDetails = $SellerDetails->Where('users.status', '=', $request['status']);
        }
        if(isset($request['order'][0])){
            $postedorder=$request['order'][0];
            if($postedorder['column']==0) $orderby='users.fname';
            if($postedorder['column']==1) $orderby='users.lname';
            if($postedorder['column']==2) $orderby='users.store_name';
            if($postedorder['column']==3) $orderby='users.city';
            if($postedorder['column']==4) $orderby='users.where_find_us';
            
            $orderorder=$postedorder['dir'];
            $SellerDetails = $SellerDetails->orderby($orderby, $orderorder);
        }
       
        $recordsTotal = $SellerDetails->count();
        $recordDetails = $SellerDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
            {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $fname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'] : '-';
                $lname = (!empty($recordDetailsVal['lname'])) ? $recordDetailsVal['lname'] : '-';
                $store_name = (!empty($recordDetailsVal['store_name'])) ? $recordDetailsVal['store_name'] : '-';
                $city = (!empty($recordDetailsVal['city'])) ? $recordDetailsVal['city'] : '-';
                $whereFindUs = (!empty($recordDetailsVal['where_find_us'])) ? $recordDetailsVal['where_find_us'] : '-';
                $showPackges =  '<a href="'.route('adminSellerShowPackages', base64_encode($id)).'" title="'.__('users.show_ackages_thead').'" class="btn btn-icon btn-info"><i class="fas fa-history"></i> </a>&nbsp;&nbsp;'; 


                if ($recordDetailsVal['is_verified'] == 1) {
                    $is_verified = '<a href="javascript:void(0)" class="btn btn-icon btn-success" title="'.__('users.verified_seller_title').'"><i class="fas fa-circle"></i> </a>';
                 } else { 
                    $is_verified = '<a href="javascript:void(0)"  class="btn btn-icon btn-danger" title="'.__('users.pending_seller_title').'"><i class="fas fa-circle"></i> </a>';
                }

                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSellerChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                 } else { 
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSellerChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }
              
                $action = '<a href="'.route('adminSellerEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminSellerDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
            
                $arr[] = [$fname, $lname, $store_name, $city, $whereFindUs, $showPackges, $is_verified, $status, $action];
            }
        } 
        else {
            $arr[] = ['',  '', '', trans('lang.datatables.sEmptyTable'), '', '', '', '',''];
        }

        $json_arr = [
            'draw'            => $request->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => ($arr),
        ];
        
        return json_encode($json_arr);
    }

     /* function to open Seller create form */
    public function create() {
    
        $data['pageTitle']              = trans('users.add_seller_btn');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.sellers_title');
        $data['module_url']             = route('adminSeller');
      
        return view('Admin/Seller/create', $data);
    }


    public function store(Request $request) {
  
        $rules = [ 
            'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'email'         => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,email',
            'paypal_email'  => 'nullable|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email',
            'description'   => 'nullable|max:500',
           
           /* 'phone_number'  => 'required',
            'address'       => 'required',
            'postcode'      => 'required',
            'city'          => 'required',
            'swish_number'  => 'required',
            'store_name'    => 'required',
            'paypal_email'  => 'required',
            'sellerimages'  => 'required',*/
        ];

        $messages = [
            'fname.required'         => trans('errors.fill_in_first_name_err'),
            'lname.required'         => trans('errors.fill_in_last_name_err'),
            'fname.regex'            => trans('errors.input_alphabet_err'),
            'lname.regex'            => trans('errors.input_alphabet_err'),
            'email.required'         => trans('errors.fill_in_email_err'),
            'email.unique'           => trans('errors.unique_email_err'),
            'email.regex'            => trans('errors.valid_email_err'),
            'paypal_email.unique'    => trans('errors.unique_paypal_email_err'),
            'paypal_email.regex'     => trans('errors.valid_paypal_email_err'),
            'description.max'        => trans('errors.max_char_err'),
            /*'phone_number.required'  => 'Please fill in your Phone Number',
            'address.required'       => 'Please fill in your address',
            'postcode.required'      => 'Please fill in your postcode',
            'city.required'          => 'Please select your City',
            'swish_number.required'  => 'Please fill in you swish number',
            'store_name.required'    => 'Please fill in your Store Name',
            'sellerimages.required'  => 'Please Upload Seller Images',*/
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
          
        $arrSellerInsert = [
                'role_id'      => 2,
                'fname'        => trim($request->input('fname')),
                'lname'        => trim($request->input('lname')),  
                'email'        => trim($request->input('email')),
                'phone_number' => trim($request->input('phone_number')),
                'address'      => trim($request->input('address')),
                'city'         => trim($request->input('city')),
                'swish_number' => trim($request->input('swish_number')),
                'postcode'     => trim($request->input('postcode')),
                'store_name'   => trim($request->input('store_name')),
                'paypal_email' => trim($request->input('paypal_email')),
                'description'  => trim($request->input('description')),
            ];

        $id = UserMain::create($arrSellerInsert)->id;
         
   
        if($request->hasfile('sellerimages')){
            $fileError = 0;
            $order = (SellerImages::where('user_id','=',$id)->count())+1;
               
            foreach($request->file('sellerimages') as $image) {
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());

                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'Seller'.$id.'_'.date('YmdHis').'_'.$order.'.'.$fileExt;
                    $image->move(public_path().'/uploads/SellerImages/', $fileName);  // your folder path

                    $path = public_path().'/uploads/SellerImages/'.$fileName;
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
                    $new_thumb_loc = public_path().'/uploads/SellerImages/resized/' . $fileName;

                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    imagedestroy($dst_img);
                    imagedestroy($src_img);

                    $arrInsert = ['user_id'=>$id,'image'=>$fileName,'image_order'=>$order];
                    sellerimages::insert($arrInsert);
                    $order++;

                } else {
                        $fileError = 1;
                }
            }

            if($fileError == 1) {
                Session::flash('error', trans('errors.invalid_files_err'));
                return redirect()->back();
            }
        } 

        Session::flash('success', trans('messages.seller_save_success'));
        return redirect(route('adminSeller')); 
    }

    /**
     * Edit record details
     * @param  $id = User Id
     */
    public function edit($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data = $details = [];
         
        $data['id'] = $id;
        $id = base64_decode($id);
        $details=UserMain::get_Seller($id);

        $imagedetails=  UserMain::where('id', $id)->with(['getImages'])->first();

        if(empty($details)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();   
         }

        $data['pageTitle']              = trans('users.edit_seller_title');
        $data['current_module_name']    = trans('users.add_seller_btn');
        $data['module_name']            = trans('users.sellers_title');
        $data['module_url']             = route('adminSeller');
        $data['sellerDetails']          = $details;
        $data['imagedetails']           =  $imagedetails;

        return view('Admin/Seller/edit', $data);
    }

    /**
     * Update Seller details
     * @param  $id = user Id
     */
    public function update(Request $request, $id) {
    	if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);

        $rules = [
            'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'email'         => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email,'.$id,
            'paypal_email'  => 'nullable|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email,'.$id,
            'description'   => 'nullable|max:500',
        /*    'phone_number'  => 'required',
            'address'       => 'required',
            'postcode'      => 'required',
            'city'          => 'required',
            'swish_number'  => 'required',
            'store_name'    => 'required',
            'paypal_email'  => 'required',*/
        ];

        $messages = [
            'fname.required'         => trans('errors.fill_in_first_name_err'),
            'lname.required'         => trans('errors.fill_in_last_name_err'),
            'fname.regex'            => trans('errors.input_alphabet_err'),
            'lname.regex'            => trans('errors.input_alphabet_err'),
            'email.required'         => trans('errors.fill_in_email_err'),

            'email.unique'           => trans('errors.unique_email_err'),
            'email.regex'            => trans('errors.valid_email_err'),
            'paypal_email.unique'    => trans('errors.unique_paypal_email_err'),
            'paypal_email.regex'     => trans('errors.valid_paypal_email_err'),
            'description.max'        => trans('errors.max_char_err'),
            //'paypal_email.required'  => 'Please fill in your Paypal Email',
            /*'paypal_email.unique'    => 'Please enter different Paypal email , its already taken.',
            'paypal_email.regex'     => 'Please enter Valid Paypal Email.',
            'phone_number.required'  => 'Please fill in your Phone Number',
            'address.required'       => 'Please fill in your address',
            'postcode.required'      => 'Please fill in your postcode',
            'city.required'          => 'Please select your City',
            'swish_number.required'  => 'Please fill in you swish number',
            'store_name.required'    => 'Please fill in your Store Name',*/
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else {
         
            $arrUpdate = [ 
                            'role_id'      => 2,
                            'fname'        => trim($request->input('fname')),
                            'lname'        => trim($request->input('lname')),  
                            'email'        => trim($request->input('email')),
                            'phone_number' => trim($request->input('phone_number')),
                            'address'      => trim($request->input('address')),
                            'city'         => trim($request->input('city')),
                            'swish_number' => trim($request->input('swish_number')),
                            'postcode'     => trim($request->input('postcode')),
                            'store_name'   => trim($request->input('store_name')),
                            'paypal_email' => trim($request->input('paypal_email')),
                            'description'  => trim($request->input('description')),
                            'is_verified'  => trim($request->input('is_verified')),
                        ];
            UserMain::where('id', '=', $id)->update($arrUpdate);   
            
            
            
        if($request->hasfile('sellerimages')) {
            $fileError = 0;
            $order = (sellerImages::where('user_id','=',$id)->count())+1;

            foreach($request->file('sellerimages') as $image) {
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());

                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'Seller'.$id.'_'.date('YmdHis').'_'.$order.'.'.$fileExt;
                    $image->move(public_path().'/uploads/SellerImages/', $fileName);  // your folder path
                    $path = public_path().'/uploads/SellerImages/'.$fileName;
                    $mime = getimagesize($path);

                    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
                    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

                    $old_x = imageSX($src_img);
                    $old_y = imageSY($src_img);

                    $newWidth = 300;
                    $newHeight = 300;

                    if($old_x > $old_y){
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
                    $new_thumb_loc = public_path().'/uploads/SellerImages/resized/' . $fileName;

                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    imagedestroy($dst_img);
                    imagedestroy($src_img);

                    $arrInsert = ['user_id'=>$id,'image'=>$fileName];
                    sellerimages::insert($arrInsert);
                    $order++;
                }
                else {
                    $fileError = 1;
                }
            }

            if($fileError == 1)
            {
                Session::flash('error', trans('errors.invalid_files_err'));
                return redirect()->back();
            }
        }           
            Session::flash('success', trans('messages.seller_update_success'));
            return redirect(route('adminSeller'));
        }
    }

    
    /**
     * Change status for Record [active/block].
     * @param  $id = Id, $status = active/block 
     */
    public function changeStatus($id, $status)  {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminCustomer'));
        }
        $id = base64_decode($id);

        $result = UserMain::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
         } else  {
            Session::flash('error', trans('errors.something_wrong_err'));
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
            return redirect(route('adminSeller'));
        }

        $id = base64_decode($id);
        $result = UserMain::find($id);

        if (!empty($result)) {
           $seller = UserMain::where('id', $id)->update(['is_deleted' =>1]);
           Session::flash('success', trans('messages.record_deleted_success'));
                return redirect()->back();  
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }


    /* funtion to delete image on edit form 
    @param : $id
    */
     public function deleteImage($id) {
        if(empty($id))  {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminSeller'));
        }
        $id = base64_decode($id);
        $result = sellerimages::find($id);
        
        if (!empty($result)) 
        {
            if ($result->delete()) 
            {
                Session::flash('success', trans('messages.image_deleted_success'));
                return redirect()->back();
            } 
            else 
            {
                Session::flash('error', trans('error.something_wrong_err'));
                return redirect()->back();
            }
        } 
        else 
        {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

    /* function to check for unique store name
    * @param:storename
    */
    function checkstore(Request $request){
        $store_name = $request->store_name;
        if(!empty($request->id)){
            $storeDtails =  UserMain::where('store_name', $store_name)->where('id','!=',$request->id)->get();
        } else{
            $storeDtails =  UserMain::where('store_name', $store_name)->get();
        }
       
        if(!empty($storeDtails[0]['store_name'])){
            $messages =trans('messages.store_name_alreay_taken');
             return $messages;
        }
       
    }

    /*function to display all packages
    * @param : seller id
    */
    function showpackages($id){

        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        
        $id = base64_decode($id);
        $packageDetails = UserPackages::where('user_packages.user_id', $id)
        ->Join('packages', 'user_packages.package_id', '=', 'packages.id')
        ->Join('users', 'users.id', '=', 'user_packages.user_id')
        ->select('user_packages.*','packages.id','packages.title','users.id','users.fname','users.lname')->get();

        $data = [];
        $data['pageTitle']              = trans('users.package_history_title');
        $data['current_module_name']    = trans('users.package_history_title');
        $data['module_name']            = trans('users.package_history_title');
        $data['module_url']             = route('adminSeller');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        $data['id'] = $id;
        $data['details']           =  $packageDetails;

        return view('Admin/Seller/packageHistory', $data);
    }

}

<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMain;
use App\Models\City;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;
use Response;
use File;

    class BuyerController extends Controller
    {
        /*
        * Define abjects of models, services.
        */
        function __construct() {

    }

    /**
    * Show list of records for buyers.
    * @return [array] [record array]
    */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.buyers_title');
        $data['current_module_name']    = trans('users.buyers_title');
        $data['module_name']            = trans('users.buyers_title');
        $data['module_url']             = route('adminBuyers');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        return view('Admin/Buyer/index', $data);
    }

    /*function to export buyers records*/
    public function exportdata(Request $request) {

        $buyerDetails = UserMain::select('users.*')->where('role_id','=',1)->where('is_deleted','!=',1);

        if(!empty($request->search))
        {
            $field = ['users.fname','users.lname','users.email','users.where_find_us'];
            $namefield = ['users.fname','users.lname','users.email','users.where_find_us'];
            $search=($request->search);

            $buyerDetails = $buyerDetails->Where(function ($query) use($search, $field,$namefield) {
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
            $buyerDetails = $buyerDetails->Where('users.status', '=', $request['status']);
        }
        else if(!empty($request->status)) {
            $buyerDetails = $buyerDetails->Where('users.status', '=', $request->status);
        }

        $recordDetails = $buyerDetails->get(['users.fname','users.lname','users.email','users.phone_number','users.address','users.city','users.swish_number','users.postcode','users.where_find_us','users.status']);

        $filename = "BuyesfromTijara.csv";
        $handle = fopen('BuyerDetails/'.$filename, 'w+');
        fputcsv($handle, array('First name','Last name','Email','Phone number','Address','City','Swish Number','Post Code','Where Find Us','Status'));

        foreach($recordDetails as $row) {
         
            fputcsv($handle, array($row->fname,$row->lname,$row->email,$row->phone_number,$row->address,$row->city,$row->swish_number,$row->postcode,$row->where_find_us,$row->status));
        }

        fclose($handle);
        return $filename;
    }

    /**
    * [getRecords for buyer list.This is a ajax function for dynamic datatables list]
    * @param  Request $request [sent filters if applied any]
    * @return [JSON]           [buyer list in json format]
    */
    public function getRecords(Request $request) {
        $customerDetails = UserMain::select('users.*')->where('role_id','=',1)->where('is_deleted','!=',1);

        if(!empty($request['search']['value']))
        {
            $field = ['users.fname','users.lname','users.email','users.where_find_us'];
            $namefield = ['users.fname','users.lname','users.email','users.where_find_us'];
            $search=($request['search']['value']);

            $customerDetails = $customerDetails->Where(function ($query) use($search, $field,$namefield) {
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
            $customerDetails = $customerDetails->Where('users.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $customerDetails = $customerDetails->Where('users.status', '=', $request['status']);
        }

        if(isset($request['order'][0])){
            $postedorder=$request['order'][0];
            if($postedorder['column']==0) $orderby='users.fname';
            if($postedorder['column']==1) $orderby='users.lname';
            if($postedorder['column']==2) $orderby='users.email';
            if($postedorder['column']==3) $orderby='users.where_find_us';

            $orderorder=$postedorder['dir'];
            $customerDetails = $customerDetails->orderby($orderby, $orderorder);
        }

        $recordsTotal  = $customerDetails->count();
        $recordDetails = $customerDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) {

                $id    = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $fname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'] : '-';
                $lname = (!empty($recordDetailsVal['lname'])) ? $recordDetailsVal['lname'] : '-';
                $email = (!empty($recordDetailsVal['email'])) ? '<a href="mailto:'.$recordDetailsVal['email'].'">'.$recordDetailsVal['email'].'</a>' : '-';
                $whereFindUs = (!empty($recordDetailsVal['where_find_us'])) ? $recordDetailsVal['where_find_us'] : '-';

                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminBuyersChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminBuyersChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminBuyersEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\'customer\',\''.route('adminBuyersDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

                $arr[] = [$fname, $lname, $email, $whereFindUs, $status, $action];
            }
        } 
        else {
            $arr[] = ['', '', trans('lang.datatables.sEmptyTable'), '', '', ''];
        }

        $json_arr = [
        'draw' => $request->input('draw'),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsTotal,
        'data' => ($arr),
        ];

        return json_encode($json_arr);
    }

    /* function to open Buyer create form */
    public function create() {
        $citiesdetails =[];
        $citiesdetails = City::where('is_deleted', '!=','1')->where('status','=','active')->orderby('cities.name')->get()->toArray();
        $data['pageTitle']              = trans('users.add_buyers_btn');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.buyers_title');
        $data['info']                   = trans('users.add_buyer_details');
        $data['module_url']             = route('adminBuyers');
        $data['citiesdetails']          = $citiesdetails;

        return view('Admin/Buyer/create', $data);
    }

    /**
    * Edit buyer details
    * @param  $id = buyer Id
    */
    public function edit($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data = $details = [];
        $data['id'] = $id;
        $id = base64_decode($id);
        $details=UserMain::get_buyers($id);
        $citiesdetails =[];
        $citiesdetails = City::where('is_deleted', '!=','1')->where('status','=','active')->orderby('cities.name')->get();

        if(empty($details)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();   
        }

        $data['pageTitle']              = trans('users.edit_buyer_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.buyers_title');
        $data['info']                   = trans('users.edit_buyer_details');
        $data['module_url']             = route('adminBuyers');
        $data['buyerDetails']           = $details;    
        $data['citiesdetails']          = $citiesdetails;   

        return view('Admin/Buyer/create', $data);
    }

    /**
    * Update buyer details
    * @param  $id = buyer Id
    */
    public function StoreUpdate(Request $request) {

        $id =$request->input('hid');
        $rules = [];

        if(!empty($id)){
            $rules = [ 
                        'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
                        'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
                        'email'        => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,email,'.$id,
                    ];
        }else{
           $rules =  [
                'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
                'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
                'email'         => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,email',
                'profile'       =>'required',
            ];
            
        }
   
        $messages = [
            'fname.required'         => trans('errors.fill_in_first_name_err'),
            'fname.regex'            => trans('errors.input_alphabet_err'),
            'lname.required'         => trans('errors.fill_in_last_name_err'),
            'lname.regex'            => trans('errors.input_alphabet_err'),
            'email.required'         => trans('errors.fill_in_email_err'),
            'email.unique'           => trans('errors.unique_email_err'),
            'email.regex'            => trans('errors.valid_email_err'),
            'profile.required'       => trans('errors.upload_buyer_profile'),
            /*'phone_number.required'  => 'Please fill in your Phone Number',
            'address.required'       => 'Please fill in your address',
            'postcode.required'      => 'Please fill in your postcode',
            'city.required'          => 'Please select your City',
            'swish_number.required'  => 'Please fill in you swish number',
            'profile.required'       => 'Please Upload Image',*/
        ];
        $fileName ='';
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $buyerDetails = UserMain::find($id);
            if($request->hasfile('profile'))
            {
                if(!empty($id)){
                  
                    $image_path = public_path("/uploads/Buyer/".$buyerDetails->profile);

                    $resized_image_path = public_path("/uploads/Buyer/resized/".$buyerDetails->profile);
                    if (File::exists($image_path)) {
                       //   echo "in".$image_path;exit;
                        unlink($image_path);
                    }

                    if (File::exists($resized_image_path)) {
                        unlink($resized_image_path);
                    }
                }

                $fileError = 0;
                $image = $request->file('profile');
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());
                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'Buyer_'.date('YmdHis').'.'.$fileExt;
                    $path =$image->move(public_path().'/uploads/Buyer/', $fileName);  // your folder path
                    /* $new_thumb_loc = public_path().'/uploads/Buyer/resized/' . $fileName;
                    $path = public_path().'/uploads/Buyer/'.$fileName;*/
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

                    if($old_x < $old_y){
                        $thumb_w    =   $old_x/$old_y*$newHeight;
                        $thumb_h    =   $newHeight;
                    }

                    if($old_x == $old_y){
                        $thumb_w    =   $newWidth;
                        $thumb_h    =   $newHeight;
                    }

                    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);
                    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

                    // New save location
                    $new_thumb_loc = public_path().'/uploads/Buyer/resized/' . $fileName;

                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    imagedestroy($dst_img);
                    imagedestroy($src_img);
                }
                else {
                    $fileError = 1;
                }

                if($fileError == 1)
                {
                    Session::flash('error', trans('errors.invalid_files_err'));
                    return redirect()->back();
                }
            } else{
                if(!empty($id)){
                $fileName = $buyerDetails->profile;  
            }
        }     


        $arrBuyerInsert = [
                'role_id'      => 1,
                'fname'        => trim($request->input('fname')),
                'lname'        => trim($request->input('lname')),  
                'email'        => trim($request->input('email')),
                'phone_number' => trim($request->input('phone_number')),
                'address'      => trim($request->input('address')),
                'city'         => trim($request->input('city')),
                'swish_number' => trim($request->input('swish_number')),
                'postcode'     => trim($request->input('postcode')),
                'profile'      => $fileName
            ];

        if(!empty($id)){
            UserMain::where('id', '=', $id)->update($arrBuyerInsert);
            Session::flash('success', trans('messages.buyer_update_success'));
        }else{
            UserMain::create($arrBuyerInsert);
            Session::flash('success', trans('messages.buyer_save_success'));
        }

        return redirect(route('adminBuyers'));
        }
    }

    /**
    * Change status for Record [active/block].
    * @param  $id = Id, $status = active/block 
    */
    public function changeStatus($id, $status) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminCustomer'));
        }

        $id = base64_decode($id);
        $result = UserMain::where('id', $id)->update(['status' => $status]);

        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
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
            return redirect(route('adminBuyers'));
        }

        $id = base64_decode($id);
        $result = UserMain::find($id);
        if (!empty($result)) {
            $user = UserMain::where('id', $id)->update(['is_deleted' =>1]);
            Session::flash('success', trans('messages.record_deleted_success'));
            return redirect()->back();
        } 
        else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

}

<?php

namespace App\Http\Controllers\Front;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/*Models*/
use App\User;
use App\Models\Banner;
use App\Models\Settings;
use App\Models\UserMain;
use App\Models\SellerImages;
use App\Models\Package;
use App\Models\UserPackages;
use App\Models\SellerPersonalPage;
use App\Models\SubscribedUsers;
/*Uses*/
use Socialite;
use DB;
use Validator;
use Session;
use Flash;
use Auth;
use Hash;
use Log;
use Mail;
use File;


class AuthController extends Controller
{

    function __construct() {
        $site_details          = Settings::first();
        $data['siteDetails']   = $site_details;
    }
	/**
     * function to Show Login Page.
     * @return null
     */
    public function login()
    {
        $site_details          = Settings::first();
        $data['siteDetails']   = $site_details;

		$banner		 		=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Login')->first();
		$data['banner'] 	= $banner;
        $data['pageTitle'] = 'Sign In';
		$data['tijara_front_login']	=	'';
		$data['tijara_front_password']=	'';
		$data['tijara_remember_me']	=	'';
		if(isset($_COOKIE['tijara_front_login'])) {
            $data['tijara_front_login']=$_COOKIE['tijara_front_login'];
            $data['tijara_front_password']=$_COOKIE['tijara_front_password'];
            $data['tijara_remember_me']=$_COOKIE['tijara_remember_me'];
        }
        else {
				setcookie('tijara_front_login', '', time() + (86400 * 30), "/");
                setcookie('tijara_front_password', '', time() + (86400 * 30), "/");
                setcookie('tijara_remember_me', '', time() + (86400 * 30), "/");
        }
//echo Auth::guard('user')->id();exit;
        if(Auth::guard('user')->id()) {
            return redirect(route('frontHome'));
        }
        return view('Front/login', $data);
    }



    public function doLogin(Request $request)
    {

        $rules = [
            'email'             => 'required|email',
            'password'          =>  'required',
        ];
        $messages = [
            'email.required'            => trans('errors.fill_in_email_err'),
            'email.email'               => trans('errors.valid_email_err'),
            'password.required'         => trans('errors.fill_in_password_err'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $checkUser   = User::select('id','status','role_id','is_verified')->where('email','=', trim($request->input('email')))->get();

        if(!isset($checkUser[0]))
        {
            Session::flash('error', trans('errors.invalid_credentials_try_again_err'));
                return redirect()->back();
        }

        if($checkUser[0]['role_id'] == 2 && $checkUser[0]['is_verified'] == 0){

            Session::flash('error', trans('errors.account_blocked_contact_admin_err'));
            // return redirect(route('frontLogin'));
            return redirect()->back();
        }
        if($checkUser[0]['role_id'] != trim($request->input('role_id'))){
            if($checkUser[0]['role_id'] == 2){
                Session::flash('error', trans('errors.please_check_your_profile'));
                return redirect(route('frontLogin'));
            }else{
                Session::flash('error', trans('errors.please_check_your_profile'));
                return redirect(route('frontLoginSeller'));
            }
        }

        if($validator->fails()) {
            $error_messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($error_messages);
        }
        else
        {

            if(Auth::guard('user')->attempt(['email' => $request->input('email'),'password' => $request->input('password')]))
            {
                
               
               // if($checkUser[0]['status'] == 'active'){
                if($checkUser[0]['is_verified'] == 1){

                    if(Auth::guard('user')->loginUsingId($checkUser[0]['id'])){

                        //Session::flash('success', 'Login successfull.');
                        if($request->input('remember')) {
                            setcookie('tijara_front_login', $request->input('email'), time() + (86400 * 30), "/");
                            setcookie('tijara_front_password', $request->input('password'), time() + (86400 * 30), "/");
                            setcookie('tijara_remember_me', 1, time() + (86400 * 30), "/");
                        }
                        else {
                            setcookie('tijara_front_login', '', time() + (86400 * 30), "/");
                            setcookie('tijara_front_password', '', time() + (86400 * 30), "/");
                            setcookie('tijara_remember_me', '', time() + (86400 * 30), "/");
                        }
                        $user_id = Auth::guard('user')->id();
                        /*get role id*/
                        $getRoleId = DB::table('users')
                            ->where('id', $user_id)->first();


                        //session_start();
                        $currentUser=array('role_id'=>$getRoleId->role_id,'name'=>$getRoleId->fname.' '.$getRoleId->lname);
                        //$_SESSION['currentUser']=$currentUser;
                        session($currentUser);
                        if($getRoleId->role_id==2){
                            return redirect(route('frontSellerProfile'));
                        }else{
                            return redirect(route('frontBuyerProfile'));
                        }
                    }
                    else
                    {
                        Session::flash('error', trans('errors.invalid_credentials_try_again_err'));
                        return redirect()->back();
                    }
                }
                else
                {
                    Session::flash('error', trans('errors.account_blocked_contact_admin_err'));
                    return redirect()->back();
                }
                

            }
            else
            {
                Session::flash('error', trans('errors.invalid_credentials_try_again_err'));
                return redirect()->back();
            }
        }

    }


    public function userProfile(){
        $User   =   User::where('id',Auth::guard('user')->id())->first();
        if($User->role_id=='1')
            return redirect(route('frontBuyerProfile'));
        if($User->role_id=='2')
            return redirect(route('frontSellerProfile'));

    }
	public function buyer_register()
    {
		$banner		 		=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Register')->first();
		$data['banner'] 	= $banner;
		$data['role_id'] 	= 1;
		$data['registertype'] = trans('users.buyers_title');
        $data['pageTitle']    = trans('lang.sign_up_title');


        if(Auth::guard('user')->id()) {
            return redirect(route('frontHome'));
        }
        return view('Front/register', $data);
    }
	public function seller_register()
    {
        $data = $details = $is_subscriber = [];
		$banner		 		=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Register')->first();
        $details = Package::select('packages.*')->where('status','=','active')->where('packages.is_deleted','!=',1)->get();
        $currentDate = date('Y-m-d H:i:s');

        $session_user_id=Session::get('seller_register_form_id');

        $is_subscriber = DB::table('user_packages')
                    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                    ->where('packages.is_deleted','!=',1)
                    ->where('user_packages.end_date','>=',$currentDate)
                    ->where('user_id','=',$session_user_id)
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status','user_packages.payment_status')
                    ->orderByRaw('user_packages.id ASC')
                    ->get();
        
        $data['packageDetails']    = $details;
        $data['subscribedPackage'] = $is_subscriber;
		$data['banner'] 	       = $banner;
		$data['registertype']      = trans('users.sellers_title');
		$data['role_id'] 	       = 2;
        $data['next_step']         = 1;
        $data['pageTitle']         = trans('lang.sign_up_title');
        $data['headingTitle']      = trans('users.sell_with_tijara_head');


        if(Auth::guard('user')->id()) {
            return redirect(route('frontHome'));
        }

        return view('Front/seller_register', $data);

    }

    /* function to register as a buyer*/
    public function doRegister(Request $request)
    {
        $rules = [
            'email'      => 'required|email|unique:users,email',
            'password'   =>  'required|confirmed|min:6',
            'password_confirmation'   =>  'required',
        ];
        $messages = [
            'email.required'                    => trans('errors.fill_in_email_err'),
            'email.unique'                      => trans('errors.unique_email_err'),
            'email.email'                       => trans('errors.valid_email_err'),
            'password.required'                 => trans('errors.fill_in_password_err'),
            'password.min'                      => trans('errors.password_min_6_char'),
            'password.confirmed'                => trans('errors.password_not_matched'),
            'password_confirmation.required'    => trans('errors.fill_in_confirm_password_err'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {
            $arrInsert =array();
            $arrInsert = [
                          'email'         => trim($request->input('email')),
                          'password'      => bcrypt(trim($request->input('password'))),
                          'status'        => 'active',
                          'role_id'       => $request->input('role_id'),
                        ];

            if($request->input('role_id') == 1){
                $arrInsert['is_verified'] = 1;
            }
            
            $user_id = User::create($arrInsert)->id;

            //Session::flash('success', 'Registration successfull!');
            return redirect(route('frontRegisterSuccess'));
        }

    }

     public function newsellerRegister(Request $request)
    {

        $rules = [
            'email'      => 'required|email|unique:users,email',          
        ];
        $messages = [
            'email.required'                    => trans('errors.fill_in_email_err'),
            'email.unique'                      => trans('errors.unique_email_err'),
            'email.email'                       => trans('errors.valid_email_err'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {

            $messages = $validator->messages();
             return response()->json(['error_msg'=>$messages]);
        }
        else
        {

            $data = [];
            $email = trim($request->input('email'));
            //$password = bcrypt(trim($request->input('password')));
            $password = trim($request->input('password'));
            $status = 'active';
            $role_id = 2;
            $cpassword = trim($request->input('password_confirmation'));

            Session::put('new_seller_email', $email);
            Session::put('new_seller_password', $password);
            Session::put('new_seller_cpassword', $cpassword);
            Session::put('new_seller_status', $status);
            Session::put('new_seller_role_id', $role_id);
            Session::put('next_step', 2);
       
            return response()->json(['success'=>'Got Simple Ajax Request']);
        }   
    }

    /*function to save third step seller registration form values*/
    public function thirdStepsellerRegister(Request $request){
     
        $session_user_id = Session::get('new_seller_user_id');

        $arrUpdate = [
                'fname'        => trim($request->input('fname')),
                'lname'        => trim($request->input('lname')),
                'address'      => trim($request->input('address')),
                'postcode'     => trim($request->input('postcode')),
            ];

        UserMain::where('id','=',$session_user_id)->update($arrUpdate);
        Session::put('new_seller_fname');
        Session::put('new_seller_lname');
        Session::forget('next_step');
        Session::forget('StepsHeadingTitle');
        Session::put('next_step',4);
        Session::put('StepsHeadingTitle',trans('users.sell_with_tijara_head'));
        return response()->json(['success'=>'third step success']);
    }

    public function register_success()
    {
        return view('Front/register_success');
    }

    /**
     * Show the Seller Profile Page.
     *
     * @return null
     */
    public function sellerProfile($edit = '')
    {

        $data['pageTitle'] = trans('users.seller_profile_title');
        if(!Auth::guard('user')->id())
        {
            return redirect(route('frontHome'));
        }

        $user_id = Auth::guard('user')->id();
        $details=UserMain::get_Seller($user_id);
        if(count($details) == 0){
             return redirect(route('frontHome'));
        }

        $imagedetails=  UserMain::where('id', $user_id)->with(['getImages'])->first();
        /*check if  seller package expiry and show alert if less than 30 day is remaining to expire*/
        $currentDate = date('Y-m-d H:i:s');
        /*$is_subscriber = DB::table('user_packages')
                    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                    ->where('packages.is_deleted','!=',1)
                    ->where('user_packages.end_date','>=',$currentDate)
                    ->where('user_id','=',$user_id)
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status')
                    ->orderByRaw('user_packages.id')
                    ->get();

        $date_diff='';

        if(count($is_subscriber) != 0){
           //calculate expiry date
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($is_subscriber[0]->start_date.'+'.$is_subscriber[0]->validity_days.' days'));
            
            $date1 = strtotime($currentDate);
            $date2 = strtotime($ExpiredDate);
            $diff = $date2 - $date1;
            $date_diff = round($diff / 86400);
        }

        if(!empty($date_diff) && $date_diff <= 30){
            $data['package_exp_msg'] =  trans('users.package_exp_message');
        }*/

        $show_exp_message=   DB::table('user_packages')
                    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                    ->where('packages.is_deleted','!=',1)
                   // ->where('user_packages.end_date','>=',$currentDate)
                    ->where('user_packages.status','=','active')
                    ->where('user_id','=',$user_id)
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status','user_packages.payment_status')
                    ->orderByRaw('user_packages.id DESC')
                    ->get();
        if(count($show_exp_message) == 0  ){
  
            $data['package_exp_msg'] = trans('messages.products_with_active_subscription');
        }
        else {
                $next_date = date('Y-m-d', strtotime($currentDate. ' + 30 days'));

                if (count($show_exp_message) <= 1 && $show_exp_message[0]->end_date <=$next_date ) {
                    foreach($show_exp_message as $v) {
                        if($v->end_date >= $currentDate){
                            $data['package_exp_msg'] = trans('users.package_exp_message');
                        }
                    }
                } 

            /*
            foreach($show_exp_message as $v) {
                if($v->end_date >= $currentDate)
                    $data['package_exp_msg'] = trans('users.package_exp_message');
            }*/
        }

        $data['id'] = $user_id;
        $data['registertype'] =  trans('users.sellers_title');
        $data['role_id']      = 2;

        $data['sellerDetails']          = $details;
        $data['imagedetails']           =  $imagedetails;
        return view('Front/seller_profile', $data);
    }

     /**
     * Update user with given details.
     */

    public function sellerProfileUpdate(Request $request)
    {

        $user_id = Auth::guard('user')->id();
        $rules = [
            'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'email'         => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email,'.$user_id,
          //  'paypal_email'  => 'nullable|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email,'.$user_id,
          //  'description'     => 'nullable|max:3000',
            'klarna_username' =>'required',
            'klarna_password' =>'required|min:6',
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
            'klarna_username.required'         => trans('errors.fill_in_klarna_username_err'),
            'klarna_password.required'         => trans('errors.fill_in_klarna_password_err'),
            'klarna_password.min'         => trans('errors.password_min_6_char'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {

            $arrUpdate = [
                'role_id'      => 2,
                'fname'        => trim($request->input('fname')),
                'lname'        => trim($request->input('lname')),
                'email'        => trim($request->input('email')),
                'phone_number' => trim($request->input('phone_number')),
                'address'      => trim($request->input('address')),
                'city'         => trim($request->input('city')),
              //  'swish_number' => trim($request->input('swish_number')),
                'postcode'     => trim($request->input('postcode')),
                'store_name'   => trim($request->input('store_name')),
                'paypal_email' => trim($request->input('paypal_email')),
              //  'description'  => trim($request->input('description')),
              //  'where_find_us'=> trim($request->input('find_us')),
                'free_shipping'    => trim($request->input('free_shipping')),
                'shipping_method'  => trim($request->input('shipping_method_ddl')),
                'shipping_charges' => trim($request->input('shipping_charges')),
                'klarna_username'  => trim($request->input('klarna_username')),
                'klarna_password' => base64_encode(trim($request->input('klarna_password'))),
            ];

            UserMain::where('id','=',$user_id)->update($arrUpdate);
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect(route('frontSellerProfile'));
        }

    }


    /**
     * Show the Seller Profile Page.
     *
     * @return null
     */
    public function seller_personal_page(Request $request)
    {


        if(!Auth::guard('user')->id())
        {
            return redirect(route('frontHome'));
        }
        $user_id = Auth::guard('user')->id();

        $details=SellerPersonalPage::join('users', 'users.id', '=', 'seller_personal_page.user_id')->where('user_id',$user_id)->first();
        
        $toUpdateData  =   array();
        if($request->input('store_information'))
            $toUpdateData['store_information']  =   trim($request->input('store_information'));
        
        if($request->input('store_policy'))
            $toUpdateData['store_policy']  =   trim($request->input('store_policy'));
        
        if($request->input('store_name')){
             $storeName['store_name']  =   trim($request->input('store_name'));
            UserMain::where('id', '=', $user_id)->update($storeName);
           
        }
        
        if($request->input('return_policy'))
            $toUpdateData['return_policy']  =   trim($request->input('return_policy'));
        
        if($request->input('shipping_policy'))
            $toUpdateData['shipping_policy']  =   trim($request->input('shipping_policy'));
            
        if($request->input('other_information'))
            $toUpdateData['other_information']  =   trim($request->input('other_information'));
            
        if($request->hasfile('header_img'))
            {
                if(!empty($details->header_img)){

                    $image_path = public_path("/uploads/Seller/".$details->header_img);
                    $resized_image_path = public_path("/uploads/Seller/".$details->header_img);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    if (File::exists($resized_image_path)) {
                        File::delete($resized_image_path);
                    }
                }

                $fileError = 0;
                $image = $request->file('header_img');
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());
                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'header_'.date('YmdHis').'.'.$fileExt;
                    $toUpdateData['header_img']=$fileName;
                    $image->move(public_path().'/uploads/Seller/', $fileName);  // your folder path
                    $path = public_path().'/uploads/Seller/'.$fileName;
                    $mime = getimagesize($path);

                    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
                    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

                    $old_x = imageSX($src_img);
                    $old_y = imageSY($src_img);

                    $newWidth = 200;
                    $newHeight = 200;

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
                    $new_thumb_loc = public_path().'/uploads/Seller/resized/' . $fileName;

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
                    Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
                    return redirect()->back();
                }
            }

            if($request->hasfile('logo'))
            {
                if(!empty($details->logo)){

                    $image_path = public_path("/uploads/Seller/".$details->logo);
                    $resized_image_path = public_path("/uploads/Seller/".$details->logo);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    if (File::exists($resized_image_path)) {
                        File::delete($resized_image_path);
                    }
                }

                $fileError = 0;
                $image = $request->file('logo');
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());
                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'logo_'.date('YmdHis').'.'.$fileExt;
                    $toUpdateData['logo']=$fileName;
                    $image->move(public_path().'/uploads/Seller/', $fileName);  // your folder path
                    $path = public_path().'/uploads/Seller/'.$fileName;
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
                    $new_thumb_loc = public_path().'/uploads/Seller/resized/' . $fileName;

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
                    Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
                    return redirect()->back();
                }
            }
            if(!empty($toUpdateData)) {
                if(!empty($details))
                    SellerPersonalPage::where('user_id',$user_id)->update($toUpdateData);
                else
                {
                    $toUpdateData['user_id']=$user_id;
                    SellerPersonalPage::insert($toUpdateData);
                }
                Session::flash('success', trans('users.seller_personal_info_saved'));
                    return redirect()->back();
            }
        $data['details']=$details;
        return view('Front/seller_personal_page', $data);
    }


    /**
     * function to save last step data of seller registration procee and remove all session variable.
     *
     * @return null
     */
    public function seller_info_page(Request $request)
    {

        $session_user_id = Session::get('new_seller_user_id');
        $UpdateStore  =   array();
        $UpdateStore = [
                'store_name'      => trim($request->input('store_name')),
        ];
         if(!empty($UpdateStore)) {
                UserMain::where('id',$session_user_id)->update($UpdateStore);
        }

        $details=SellerPersonalPage::where('user_id',$session_user_id)->first();

        $toUpdateData  =   array();
        $toUpdateData = [
                'user_id'      => $session_user_id,
                'header_img'        => trim($request->input('banner_image')),
                'logo'        => trim($request->input('logo_image')),
        ];
    
        if(!empty($toUpdateData)) {
            if(!empty($details)){
                SellerPersonalPage::where('user_id',$session_user_id)->update($toUpdateData);
            }
            else
            {
                $toUpdateData['user_id']=$session_user_id;
                SellerPersonalPage::insert($toUpdateData);
            }
        }

        $email =   Session::get('new_seller_email');
        $name  = Session::get('new_seller_fname').' '.Session::get('new_seller_lname');
        $admin_email = env('ADMIN_EMAIL');
        $admin_name  = env('ADMIN_NAME');
        
        $GetEmailContents = getEmailContents('Seller Register Admin');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];

        $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##SELLER_ADMIN_URL##'],[$name,$email,url('/'),route('adminSellerEdit', base64_encode($session_user_id))],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($admin_email,$admin_name,$subject) {
             $message->to($admin_email, $admin_name)->subject
                 ($subject);
             $message->from( env('FROM_MAIL'),'Tijara');
         });


        Session::forget('new_seller_user_id');
        Session::forget('new_seller_email');
        Session::forget('new_seller_fname');
        Session::forget('new_seller_lname');
        Session::forget('new_seller_password');
        Session::forget('new_seller_cpassword');
        Session::forget('new_seller_status');
        Session::forget('new_seller_role_id');
        Session::forget('next_step');
        Session::forget('seller_register_form_id');
        return response()->json(['success'=>'last step success']);
    }


    /**
     * function to upload seller banner image.
     *
     * @return null
     */
    public function uploadSellerBannerImage(Request $request){
     
        if(($request->file('fileUpload'))){

            $fileError = 0;
            $image=$request->file('fileUpload');
            $name=$image->getClientOriginalName();
            $fileExt  = strtolower($image->getClientOriginalExtension());
            
            if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                $fileName = 'seller-banner-'.date('YmdHis').'.'.$fileExt;
                $image->move(public_path().'/uploads/Seller/', $fileName);  // your folder path
                $path = public_path().'/uploads/Seller/'.$fileName;
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
            
                $dst_img  =  ImageCreateTrueColor($thumb_w,$thumb_h);
                imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
                // New save location
                $new_thumb_loc = public_path().'/uploads/Seller/resized/' . $fileName;
            
                if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }

                if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                imagedestroy($dst_img);
                imagedestroy($src_img);

            } else {

                $fileError = 1;
            }
            
            if($fileError == 1) {
            
                            
                }
                       
            echo $fileName;
        } 
    }

    /**
     * function to upload seller logo image.
     *
     * @return null
     */
    public function uploadSellerLogoImage(Request $request){
     
        if(($request->file('fileUpload'))){

            $fileError = 0;
            $image=$request->file('fileUpload');
            $name=$image->getClientOriginalName();
            $fileExt  = strtolower($image->getClientOriginalExtension());
            
            if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                $fileName = 'seller-logo-'.date('YmdHis').'.'.$fileExt;
                $image->move(public_path().'/uploads/Seller/', $fileName);  // your folder path
                $path = public_path().'/uploads/Seller/'.$fileName;
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
            
                $dst_img  =  ImageCreateTrueColor($thumb_w,$thumb_h);
                imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
                // New save location
                $new_thumb_loc = public_path().'/uploads/Seller/resized/' . $fileName;
            
                if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }

                if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                imagedestroy($dst_img);
                imagedestroy($src_img);

            } else {

                $fileError = 1;
            }
            
            if($fileError == 1) {
                
                }
                        //$producVariant['image']=$fileName;
                        echo $fileName;
        } 
    }


    /* funtion to seller delete image
    @param : $id
    */
     public function deleteImage($id) {
        if(empty($id))  {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('frontHome'));
        }
        $id = base64_decode($id);
        $result = sellerimages::find($id);

        if (!empty($result))
        {
            if ($result->delete())
            {
                Session::flash('success', trans('errors.selected_img_del_success_err'));
                return redirect()->back();
            }
            else
            {
                Session::flash('error', trans('errors.something_wrong_err'));
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

    /**
     * Show the buyer Profile Page.
     *
     * @return null
     */
    public function buyerProfile($edit = '')
    {
        $data['pageTitle'] = trans('users.buyer_profile_title');
        if(!Auth::guard('user')->id())
        {
            return redirect(route('frontHome'));
        }

        $user_id = Auth::guard('user')->id();
        $details=UserMain::get_buyers($user_id);
        if(count($details)==0){
            return redirect(route('frontHome'));
        }

        $data['id'] = $user_id;
        $data['registertype']= trans('users.buyers_title');
        $data['role_id']    = 1;

        $data['buyerDetails']          = $details;
        return view('Front/buyer_profile', $data);
    }

    /**
     * Update buyer with given details.
     */
    public function buyerProfileUpdate(Request $request)
    {

        $user_id = Auth::guard('user')->id();
        $rules = [
            'fname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'lname'         => 'required|regex:/^[\pL\s\-]+$/u',
            'email'        => 'required|regex:/(.*)\.([a-zA-z]+)/i|unique:users,email,'.$user_id,
        ];

        $messages = [
            'fname.required'         => trans('errors.fill_in_first_name_err'),
            'fname.regex'            => trans('errors.input_alphabet_err'),
            'lname.required'         => trans('errors.fill_in_last_name_err'),
            'lname.regex'            => trans('errors.input_alphabet_err'),
            'email.required'         => trans('errors.fill_in_email_err'),
            'email.unique'           => trans('errors.unique_email_err'),
            'email.regex'            => trans('errors.valid_email_err'),
            'profile.required'       => trans('errors.upload_buyer_profile'),
        ];

        $fileName ='';
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }else
        {
            $buyerDetails = UserMain::find($user_id);
            if($request->hasfile('profile'))
            {
                if(!empty($buyerDetails->profile)){

                    $image_path = public_path("/uploads/Buyer/".$buyerDetails->profile);
                    $resized_image_path = public_path("/uploads/Buyer/".$buyerDetails->profile);
                    if (File::exists($image_path)) {
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
                    $image->move(public_path().'/uploads/Buyer/', $fileName);  // your folder path
                    $path = public_path().'/uploads/Buyer/'.$fileName;
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
                if(!empty($buyerDetails->profile)){
                    $fileName = $buyerDetails->profile;
                }
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
                'profile'      => $fileName,
                'where_find_us'=> trim($request->input('find_us')),
            ];


            UserMain::where('id', '=', $user_id)->update($arrBuyerInsert);
            Session::flash('success', trans('messages.buyer_update_success'));
            return redirect(route('frontBuyerProfile'));

    }


    /**
     * Update user with given billing details.
     */

    public function profileBillingUpdate(Request $request)
    {
        $user_id = Auth::guard('user')->id();

        $rules = [
           // 'billing_address'    =>  'required|string',
            'billing_street'     =>  'required|string',
            //'billing_province'   =>  'required',
			'billing_city'   	 =>  'required',
            'billing_suburb'     =>  'required',
            'billing_postcode'   =>  'required',
        ];
        $messages = [
            'billing_address.required'    => trans('errors.billing_address_req_err'),
            'billing_street.required'     => trans('errors.billing_street_req_err'),
            'billing_province.required'   => trans('errors.billing_province_req_err'),
			'billing_city.required'   	  => trans('errors.billing_city_req_err'),
            'billing_suburb.required'     => trans('errors.billing_suburb_req_err'),
            'billing_postcode.required'   => trans('errors.billing_postcode_req_err'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {
            $citiesdetails = Cities::select('name')->where('name','=',trim($request->input('shipping_city')))->get();
            if($citiesdetails->count()>0) {
                $arrData = [
                            'billing_address'=>trim($request->input('billing_address')),
                            'billing_street'=>trim($request->input('billing_street')),
                            'billing_province'=>'',//trim($request->input('billing_province')),
    						'billing_city'=>trim($request->input('billing_city')),
                            'billing_suburb'=>trim($request->input('billing_suburb')),
                            'billing_postcode'=>trim($request->input('billing_postcode')),
                           ];
                $checkAddress = UsersAddress::where('user_id','=',$user_id)->first();
                if(!empty($checkAddress->id))
                {
                    UsersAddress::where('id','=',$checkAddress->id)->update($arrData);
                }
                else
                {
                    $arrData['user_id'] = $user_id;
                    UsersAddress::create($arrData);
                }


                $checksAddress = UsersAddress::where('user_id','=',$user_id)->where('shipping_address','=',null)->first();

                if(!empty($checksAddress->id))
                {
                     $sarrData = [
                            'shipping_address'=>trim($request->input('billing_address')),
                            'shipping_street'=>trim($request->input('billing_street')),
                            'shipping_province'=>'',//trim($request->input('billing_province')),
    						'shipping_city'=>trim($request->input('billing_city')),
                            'shipping_suburb'=>trim($request->input('billing_suburb')),
                            'shipping_postcode'=>trim($request->input('billing_postcode')),
                           ];
                    UsersAddress::where('id','=',$checksAddress->id)->update($sarrData);
                }


                Session::flash('success', trans('messages.billing_update_success'));
                return redirect(route('frontPersonalProfile'));
            }
            else {
                Session::flash('error', trans('errors.incorrect_city_name_err'));
                return redirect(route('frontPersonalProfile'));
            }
        }
    }


     /** Logout user */
     public function logout($type='',$msg='',Request $request)
     {
        if(Auth::guard('user')->id())
        {
            Auth::guard('user')->logout();
        }

        $request->session()->forget('email');
        $request->session()->flush();
         if(!empty($msg)) {
             if($type=='success') {
                 // \Session::flash('success', $msg);
             }
             else {
                 \Session::flash('error', $msg);
             }
         }

         return redirect(route('frontHome'));
     }


    /*function to process forgot password*/
    public function forgotPassword(Request $request)
    {
        $site_details          = Settings::first();
       // $data['siteDetails']   = $site_details;
        $data['pageTitle'] = trans('lang.forgot_password_title');

        $user = DB::table('users')->where('email', '=', $request->input('forgot_email'))
            ->first();//Check if the user exists
        if (empty($user->id))
        {
            Session::flash('error', trans('errors.user_not_exist_err'));
            return redirect(route('frontLogin'));
        }

        $token = $this->getRandom(60);
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->input('forgot_email'),
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);//Get the token just created above

        $email = $user->email;
        $name = $user->fname;
        $url = route('frontshowResetPassword',[$token]);

        $GetEmailContents = getEmailContents('Forgot Password');
        $subject = $GetEmailContents['subject'];
        $contents = $GetEmailContents['contents'];

        $contents = str_replace(['##NAME##','##EMAIL##','##SITE_URL##','##LINK##','##DEVELOPER_MAIL##'],[$name,$email,url('/'),$url,env('FROM_MAIL')],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$name,$subject) {
             $message->to($email, $name)->subject
                 ($subject);
             $message->from( env('FROM_MAIL'),'Tijara');
         });

        Session::flash('success', trans('messages.reset_pwd_email_sent_success'));
        return redirect(route('frontLogin'));
    }

    public function showResetPassword($token)
    {
        $site_details          = Settings::first();
        $data['siteDetails']   = $site_details;
        $data['pageTitle']     = trans('messages.reset_password_btn_label');
        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();// Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData)
        {
            Session::flash('error', trans('errors.invalid_pwd_reset_link_err'));
            return redirect(route('frontLogin'));
        }

        $data['token'] = $token;
        return view('Front/reset_password', $data);
    }

    /* function to proceed reset password*/
    public function resetPassword(Request $request)
    {

        //Validate input
        $validator = Validator::make($request->all(), [
            'password'   =>  'required|confirmed|min:6',
            'password_confirmation'   =>  'required',
            'token' => 'required' ]);

        //check if payload is valid before moving on
        if ($validator->fails())
        {
            $error_messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($error_messages);
        }

        $password = $request->password;// Validate the token
        $tokenData = DB::table('password_resets')
        ->where('token', $request->token)->first();// Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData)
        {
            Session::flash('error', trans('errors.pwd_reset_token_exp_err'));
            return redirect(route('frontLogin'));
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) return redirect()->back()->withErrors(['email' => trans('errors.user_not_exist_err')]);//Hash and update the new password
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        //Auth::guard('user')->login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
        ->delete();

        Session::flash('success', trans('messages.pwd_reset_success'));
        return redirect(route('frontLogin'));

    }

    private function getRandom($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

      /**
     * Function for Change Password
     */
    public function changePassword() {

        $site_details        = Settings::first();
        $data['module_url']  = route('frontChangePassword');
        $data['pageTitle']   = trans('users.change_password_title');
        $data['module_url']  = route('frontChangePassword');
        return view('Front/change_password', $data);
    }


    /**
     * Function for Change Password Store
     */
    public function changePasswordStore(Request $request) {

        $rules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        $messages = [
            'password.required'                    => trans('errors.fill_in_password_err'),
            'password.confirmed'                   => trans('errors.password_not_matched'),
            'password_confirmation.required'       => trans('errors.fill_in_confirm_password_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else
        {
            $user_id = \Auth::guard('user')->id();

            $arrUpdate = ['password'=>bcrypt($request->input('password'))];
            User::where('id', $user_id)->update($arrUpdate);

            Session::Flash('success', trans('messages.pwd_changed_success'));
            return redirect(route('frontChangePassword'));
        }
    }


     /**
     * Show packages to seller.
     *
     * @return null
     */
    public function sellerPackages()
    {
        $data['pageTitle'] = trans('users.seller_packages_title');
        if(!Auth::guard('user')->id())
        {
            return redirect(route('seller_register'));
        }
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id!=2) {
            return redirect(route('frontUserProfile'));
        }

        $user_id = Auth::guard('user')->id();
        $is_seller = UserMain::get_Seller($user_id);
        if(count($is_seller) == 0){
              return redirect(route('frontHome'));
        }
        $data = $details = $is_subscriber = $date_diff = $ExpiredDate= [];
        $currentDate = date('Y-m-d H:i:s');

        $is_subscriber = DB::table('user_packages')
                    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                    ->where('packages.is_deleted','!=',1)
                    ->where('user_packages.end_date','>=',$currentDate)
                    ->where('user_id','=',$user_id)
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status','user_packages.payment_status')
                    ->orderByRaw('user_packages.id ASC')
                    ->get();

        if(count($is_subscriber) != 0){
           //calculate expiry date
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($is_subscriber[0]->start_date.'+'.$is_subscriber[0]->validity_days.' days'));
            /*calculate days remailning for package expiry*/
            $date1 = strtotime($currentDate);
            $date2 = strtotime($ExpiredDate);
            $diff = $date2 - $date1;
            $date_diff = round($diff / 86400);
        }



        if(count($is_subscriber) == 0 || $date_diff <= 30){
            $details = Package::select('packages.*')->where('status','=','active')->where('packages.is_deleted','!=',1)->get();
        }
   
        $show_exp_message=   DB::table('user_packages')
                    ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                    ->where('packages.is_deleted','!=',1)
                   // ->where('user_packages.end_date','>=',$currentDate)
                    ->where('user_packages.status','=','active')
                    ->where('user_id','=',$user_id)
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status','user_packages.payment_status')
                    ->orderByRaw('user_packages.id DESC')
                    ->get();
        
        if(count($show_exp_message) == 0  ){
  
            $data['package_exp_msg'] = trans('messages.products_with_active_subscription');
        }
        else {
        
            $next_date = date('Y-m-d', strtotime($currentDate. ' + 30 days'));

            if (count($show_exp_message) <= 1 && $show_exp_message[0]->end_date <=$next_date ) {
                foreach($show_exp_message as $v) {
                    if($v->end_date >= $currentDate){
                        $data['package_exp_msg'] = trans('users.package_exp_message');
                    }
                }
            }
        }

        $data['user_id']           = $user_id;
        $data['title']             = trans('users.subscribe_package_label');
        $data['packageDetails']    = $details;
        $data['subscribedPackage'] = $is_subscriber;
        $data['ramainingDays']     = $date_diff;
        $data['expiryDate']        = $ExpiredDate;


        return view('Front/Packages/index', $data);
        
    }

    public function klarnaPayment(Request $request){
        $username = env('KLORNA_USERNAME');
        $password = env('KLORNA_PASSWORD');

        $user_id       = $request->input('user_id');
        $amount         = $request->input('amount');
        $validity_days = $request->input('validity_days');
        $package_id    = $request->input('p_id');
        $package_name = $request->input('p_name');

        /*klarna api to create order*/
        $url = env('BASE_API_URL');
        //$url = "https://api.playground.klarna.com/checkout/v3/orders";
        $data = array("purchase_country"=> "SE",
          "purchase_currency"=> "SEK",
          "locale"=> "en-SE",
          "order_amount"=> (int)ceil($amount),
          "order_tax_amount"=> 0,          
        );
        $data['order_lines'] = [array(
                 "type"             => "physical",
                  "reference"       => $package_id,
                  "name"            => $package_name,
                  "quantity"        => 1,
                  "quantity_unit"   => "pcs",
                  "unit_price"      => (int)ceil($amount),
                  "tax_rate"        => 0,
                  "total_amount"    => (int)ceil($amount),
                  "total_discount_amount" => 0,
                  "total_tax_amount"      => 0,
        )];

       
        $data['merchant_urls'] =array(
                 "terms"=>  url("/"),
                 "checkout"=> url("/")."/package_callback?order_id={checkout.order.id}",
                 "confirmation"=> url("/")."/package_callback?order_id={checkout.order.id}",
                 "push"=> url("/")."/push_notification?order_id={checkout.order.id}",
               
        );
   

        $data = json_encode($data);
        $data =str_replace("\/\/", "//", $data);
        $data =str_replace("\/", "/", $data);
        $error_msg ='';
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

        if(!empty($response->error_messages)){
            $cnt_err = count($response->error_messages);
        }

        if(!empty($error_msg) || @$cnt_err ) {
           $blade_data['error_messages']= trans('errors.payment_failed_err');
           return view('Front/Packages/payment_error',$blade_data); 
        }
  
        $order_id = $response->order_id;
        $order_status = $response->status;
        $currentDate = date('Y-m-d H:i:s');
        $html_data=[];

        /*check package already activated*/
        $is_activePackage = DB::table('user_packages')
                    //->where('status','=','active')
                    ->where('user_id','=',$user_id)
                    ->where('end_date','>=',$currentDate)
                    ->select('user_packages.*')
                    ->orderByRaw('user_packages.id DESC')
                    ->get();

        if(count($is_activePackage) != 0){
            $start_date = date('Y-m-d H:i:s', strtotime($is_activePackage[0]->end_date.'+ 1 days')); 
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$validity_days.' days'));
        }else{
            $start_date = date("Y-m-d H:i:s");
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$validity_days.' days'));
          
        }

        if(Auth::guard('user')->id()) {
            $arrInsertPackage = [
                              'user_id'    =>$user_id,
                              'package_id' => $package_id,
                              'status'     => "block",
                              'start_date' => $start_date,
                              'end_date'   => $ExpiredDate,
                              'order_id'   => $order_id,
                              'payment_status' =>$order_status,
                            ];

            UserPackages::create($arrInsertPackage);
            $html_data["html_snippet"] = $response->html_snippet;
            return view('Front/Packages/payment_integration',$html_data); 
        }else{

        
            Session::put('new_seller_package_id', $package_id);
            Session::put('new_seller_package_status', 'block');
            Session::put('new_seller_package_start_date', $start_date);
            Session::put('new_seller_package_end_date', $ExpiredDate);
            Session::put('new_seller_package_order_id', $order_id);
            Session::put('new_seller_package_payment_status', $order_status);

            $html_snippet = $response->html_snippet;
      
            return response()->json(['success'=>'package subscribed','html_snippet'=>$html_snippet,'error_msg'=>$error_msg]);
           
        }
       
    }

    /* function for klarna payment callback*/
     public function packageCallback(Request $request){
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
 
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($error_msg)) {
          //echo $error_msg;exit;
           $data['error_messages']=trans('errors.payment_failed_err');
           return view('Front/Packages/payment_error',$data); 
        }
      
        $response = json_decode($result);
        
        $order_id     =  $response->order_id;
        $order_status = $response->status;

        $data=[];
        $data["html_snippet"] = $response->html_snippet;
        //return view('Front/Packages/payment_confirm',$data); 

       


        if(Auth::guard('user')->id()) {
            Session::Flash('success', trans('messages.package_subscribe_success'));
            return redirect(route('frontSellerPackages'));
        }else{
            Session::forget('next_step');
             $arrInsert = [
                          'email'         => Session::get('new_seller_email'),
                          'password'      => bcrypt(Session::get('new_seller_password')),
                          'status'        => Session::get('new_seller_status'),
                          'role_id'       => Session::get('new_seller_role_id'),
                        ];


            $user_id = User::create($arrInsert)->id; 
            Session::put('new_seller_user_id',$user_id);
            $arrInsertPackage = [
                              'user_id'    => $user_id,
                              'package_id' => Session::get('new_seller_package_id'),
                              'status'     => "block",
                              'start_date' => Session::get('new_seller_package_start_date'),
                              'end_date'   => Session::get('new_seller_package_end_date'),
                              'order_id'   => Session::get('new_seller_package_order_id'),
                              'payment_status' => Session::get('new_seller_package_payment_status'),
                            ];

            UserPackages::create($arrInsertPackage);
            Session::put('next_step', 3);
            Session::put('StepsHeadingTitle', trans('users.third_step_title'));
            return redirect(route('seller_register'));
            //return $this->view('Front/seller_register',$data); 
        }
       // $this->newsellerRegister();
        //return view('Front/Packages/seller-packages',$data); 
    }

    /*push notification request from Klarna*/
     public function pushNotification(Request $request){
       /*get order from klarm by order id*/
        $order_id = $request->order_id;
        $username = env('KLORNA_USERNAME');
        $password = env('KLORNA_PASSWORD');

        $package_details = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('user_packages.order_id','=',$order_id)
                        ->first();

        /*acknowledged the order by calling this API.*/
       /*  $ack_url = "https://api.playground.klarna.com/ordermanagement/v1/orders/".$order_id."/acknowledge";        
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$ack_url);        
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        
        $res = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
*/


        /*capture order after push request recieved from klarna*/
        $capture_url  = "https://api.playground.klarna.com/ordermanagement/v1/orders/".$order_id."/captures";

        $data = <<<DATA
                {
                    "captured_amount" : $package_details->amount
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
           $data['error_messages']=trans('errors.payment_failed_err');
           return view('Front/Packages/payment_error',$data); 
        }


        /* api call to get order details*/
        $url = "https://api.playground.klarna.com/ordermanagement/v1/orders/".$order_id;        
    
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
          $data['error_messages']=trans('errors.payment_failed_err');
          return view('Front/Packages/payment_error',$data); 
        }

        $result = json_decode($res);
     
        $order_status = $result->status;
        /*create file to check push request recieved or not*/
        $newFilename ="myText.txt";
        Storage::disk('local')->put($newFilename, $res);

        
        //check package is already active or not
        $currentDate = date('Y-m-d H:i:s');
        
/*
        $is_activePackage = DB::table('user_packages')
                   // ->where('status','=','active')
                   // ->where('payment_status','=',"CAPTURED")
                    ->where('user_id','=',$package_details->user_id)
                    ->where('end_date','>=',$currentDate)
                    ->select('user_packages.*')
                    ->orderByRaw('user_packages.id DESC')
                    ->get();

        if(count($is_activePackage) > 0){
            
            $start_date = date('Y-m-d H:i:s', strtotime($is_activePackage[0]->end_date.'+ 1 days'));  
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$package_details->validity_days.' days'));
            $status='block';
        }else{
            $start_date = date("Y-m-d H:i:s");
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$package_details->validity_days.' days'));
            if($order_status == "CAPTURED")
                $status='active';
            else
                $status='block';
       }

*/
       $check_exist_order = DB::table('user_packages')
                    ->where('order_id','=',$order_id)
                    ->first();
        if(!empty($check_exist_order)) {
            //normal flow here
            if( $order_status == "CAPTURED"){
                $status='active';
            }
        else
            $status='block';
            $arrUpdatePackage = [
                              'status'     => $status,
                              //'start_date' => $start_date,
                              //'end_date'   => $ExpiredDate,
                              'payment_status' => $order_status,
                              'payment_response' => $res,
                            ];

            UserPackages::where('order_id', '=', $order_id)->update($arrUpdatePackage);
        }
         //else recurring flow with insert new record
        

    }

    /*
    *function to save subscribe users
    *@param:email_id
    */
    public function usersSubscription(Request $request){

        $email = $request->email;
        $success_message = $err_message = '';

        if(!empty($email)){

            $is_exists =  SubscribedUsers::where('email','like', $email.'%')->first();

            if(!empty($is_exists)){
                $err_message = trans('errors.already_subscribed');
                return response()->json(['error'=>$err_message]);
            }else{
                $newsLetter['email']   =   $email;
                SubscribedUsers::create($newsLetter);
                $success_message=trans('messages.news_letter_subscribe_success');
                return response()->json(['success'=>$success_message]);
            }
          
        }
    }
}

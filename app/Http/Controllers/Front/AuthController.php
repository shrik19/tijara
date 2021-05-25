<?php

namespace App\Http\Controllers\Front;

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
            'email.required'            => 'Please provide email address!',
            'email.email'               => 'Please enter valid email address!',
            'password.required'         => 'Please enter your password!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
		
		

        if($validator->fails()) {
            $error_messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($error_messages);
        }
        else 
        {
			
            if(Auth::guard('user')->attempt(['email' => $request->input('email'),'password' => $request->input('password')]))
            {
                $checkUser   = User::select('id','status')->where('email','=', trim($request->input('email')))->get();
				
                if($checkUser[0]['status'] == 'active')
                {
                    if(Auth::guard('user')->loginUsingId($checkUser[0]['id'])) 
                    {
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
                        Session::flash('error', 'Invalid login details. Please Try again.');
                        return redirect()->back();
                    }
                }
                else
                {
                    Session::flash('error', 'Your account is currently blocked. Please contact Admin.');
                    return redirect()->back();
                }
                
                
            }
            else
            {
                Session::flash('error', 'Invalid login details. Please Try again.');
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
		$data['registertype']='Buyer';
        $data['pageTitle'] = 'Sign Up';
		
		
        if(Auth::guard('user')->id()) {
            return redirect(route('frontHome'));
        }
        return view('Front/register', $data);
    }
	public function seller_register()
    {
		$banner		 		=  Banner::select('banner.*')->where('is_deleted','!=',1)->where('status','=','active')->where('display_on_page','=','Register')->first();  
		$data['banner'] 	= $banner;
		$data['registertype']='Seller';
		$data['role_id'] 	= 2;
        $data['pageTitle'] = 'Sign Up';
		
		
        if(Auth::guard('user')->id()) {
            return redirect(route('frontHome'));
        }
        return view('Front/register', $data);
    }
   
    public function doRegister(Request $request)
    {
        $rules = [
            'email'      => 'required|email|unique:users,email',
            'fname'   =>  'required|string',
            'lname'   =>  'required|string',
            'password'   =>  'required|confirmed|min:6',
            'password_confirmation'   =>  'required',
        ];
        $messages = [
            'email.required'                    => 'Please provide email address!',
            'email.unique'                      => 'Please enter different email , its already taken.',
            'email.email'                       => 'Please enter valid email address!',
            'password.required'                 => 'Please enter your password!',
            'password.min'                      => 'Password should be minimum 6 characters in length!',
            'password.confirmed'                => 'Password and Confirm password not matching!',
            'password_confirmation.required'    => 'Please enter your confirm password!',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages(); 
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $arrInsert =array();
            $arrInsert = ['fname'=>trim($request->input('fname')),
                          'lname'=>trim($request->input('lname')),						  
                          'email' => trim($request->input('email')),
                          'password' => bcrypt(trim($request->input('password'))),
                          'status' => 'active',
                          'role_id' => $request->input('role_id'),
                          'profile' =>'profile.png',
                        ];

            if($request->input('role_id') == 2){
                $arrInsert['is_verified'] = 0;
            }
                      
            $user_id = User::create($arrInsert)->id;
			
            if(Auth::guard('user')->loginUsingId($user_id)) 
            {
				
				$email = trim($request->input('email'));
				$name  = trim($request->input('fname')).' '.trim($request->input('lname'));
				
				$arrMailData = ['name' => $name, 'email' => $email];
				
				// Mail::send('emails/user_registration', $arrMailData, function($message) use ($email,$name) {
				// 	$message->to($email, $name)->subject
				// 		('Tijara - User Registrations');
				// 	$message->from('developer@techbeeconsulting.com','Tijara');
				// });
		
                //Session::flash('success', 'Registration successfull!');
                return redirect(route('frontRegisterSuccess'));
            }
            else
            {
                Session::flash('error', 'Invalid login details. Please Try again.');
                return redirect()->back();
            }
        }

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
        
        $data['pageTitle'] = 'Seller Profile';
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

        $data['id'] = $user_id;
        $data['registertype']='Seller';
        $data['role_id']    = 2;
        
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
            'paypal_email'  => 'nullable|regex:/(.*)\.([a-zA-z]+)/i|unique:users,paypal_email,'.$user_id,
            'description'   => 'nullable|max:500',
        ];

        $messages = [
            'fname.required'         => 'Please fill in your First Name',
            'lname.required'         => 'Please fill in your Last Name',
            'fname.regex'            => 'Please input alphabet characters only',
            'lname.regex'            => 'Please input alphabet characters only',
            'email.required'         => 'Please fill in your Email',
            'email.unique'           => 'Please enter different email , its already taken.',
            'email.regex'            => 'Please enter Valid Email.',
            'paypal_email.unique'    => 'Please enter different Paypal email , its already taken.',
            'paypal_email.regex'     => 'Please enter Valid Paypal Email.',
            'description.max'        => 'Maximum 500 characters allowed',
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
                'swish_number' => trim($request->input('swish_number')),
                'postcode'     => trim($request->input('postcode')),
                'store_name'   => trim($request->input('store_name')),
                'paypal_email' => trim($request->input('paypal_email')),
                'description'  => trim($request->input('description')),
                'where_find_us'=> trim($request->input('find_us')),
            ];

            UserMain::where('id','=',$user_id)->update($arrUpdate);
            if($request->hasfile('sellerimages')) {
            $fileError = 0;
            $order = (sellerImages::where('user_id','=',$user_id)->count())+1;
       
            foreach($request->file('sellerimages') as $image) {
                 
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());

                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'Seller'.$user_id.'_'.date('YmdHis').'_'.$order.'.'.$fileExt;
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

                    $arrInsert = ['user_id'=>$user_id,'image'=>$fileName,'image_order'=>$order];
                    
                    sellerimages::insert($arrInsert);
                    $order++;
                }
                else {
                    $fileError = 1;
                }
            }

            if($fileError == 1)
            {
                Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
                return redirect()->back();
            }
        }
            

            Session::flash('success', 'Seller profile updated successfully!');
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
        
        $details=SellerPersonalPage::where('user_id',$user_id)->first();
         $toUpdateData  =   array();
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

    /* funtion to seller delete image 
    @param : $id
    */
     public function deleteImage($id) {
        if(empty($id))  {
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('frontHome'));
        }
        $id = base64_decode($id);
        $result = sellerimages::find($id);
        
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

    /**
     * Show the buyer Profile Page.
     *
     * @return null
     */
    public function buyerProfile($edit = '')
    {
        $data['pageTitle'] = 'Buyer Profile';
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
        $data['registertype']='Buyer';
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
            'fname.required'         => 'Please fill in your First Name',
            'fname.regex'            => 'Please input alphabet characters only',
            'lname.required'         => 'Please fill in your Last Name',
            'lname.regex'            => 'Please input alphabet characters only',
            'email.required'         => 'Please fill in your Email',
            'email.unique'           => 'Please enter different email , its already taken.',
            'email.regex'            => 'Please enter Valid Email.',
            'profile.required'       => 'Please Upload Buyer Profile',
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
                        File::delete($image_path);
                    }

                    if (File::exists($resized_image_path)) {
                        File::delete($resized_image_path);
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
                    Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
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
            Session::flash('success', 'Buyer profile updated successfully!');
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
            'billing_address.required'    => 'Please enter Billing address!',
            'billing_street.required'     => 'Please enter Billing street!',
            'billing_province.required'   => 'Please select Billing province!',
			'billing_city.required'   	  => 'Please select Billing city!',
            'billing_suburb.required'     => 'Please enter Billing suburb!',
            'billing_postcode.required'   => 'Please enter Billing postcode!',

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
               
                    
                Session::flash('success', 'Personal billing details updated successfully!');
                return redirect(route('frontPersonalProfile'));
            }
            else {
                Session::flash('error', 'Enter city name correctly');
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
        $data['pageTitle'] = 'Forgot Password';
        
        $user = DB::table('users')->where('email', '=', $request->input('forgot_email'))
            ->first();//Check if the user exists
        if (empty($user->id)) 
        {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
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
        
        $arrMailData = ['name' => $name,'email' => $email,'url' => $url, 'siteDetails'  =>$site_details];
        Mail::send('emails/forgot_password', $arrMailData, function($message) use ($email,$name) {
            $message->to($email, $name)->subject
                ('Tijara - Forgot Password');
            $message->from('developer@techbeeconsulting.com','Tijara');
        });
        
        Session::flash('success', 'Reset Password email sent successfully.');
        return redirect(route('frontLogin'));
    }

    public function showResetPassword($token)
    {
        $site_details          = Settings::first();
        $data['siteDetails']   = $site_details;
        $data['pageTitle'] = 'Reset Password';
        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();// Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) 
        {
            Session::flash('error', 'Invalid password reset link, please try again.');
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
            Session::flash('error', 'Password reset token expired, please try again.');
            return redirect(route('frontLogin'));
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) return redirect()->back()->withErrors(['email' => 'User not found']);//Hash and update the new password
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::guard('user')->login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
        ->delete();

        Session::flash('success', 'Password reset successfully.');
        return redirect(route('frontHome'));

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

        $site_details          = Settings::first();
        $data['module_url'] = route('frontChangePassword');
        $data['pageTitle'] = 'Change Password';
        $data['module_url'] = route('frontChangePassword');
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
            'password.required'                    => 'Please enter Password!',
            'password.confirmed'                   => 'Password and Confirm Password must be same!',
            'password_confirmation.required'       => 'Please enter Confirm Password!',
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

            Session::Flash('success', 'Password changed successfully!');
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
        $data['pageTitle'] = 'Seller Packages';
        if(!Auth::guard('user')->id())
        {
            return redirect(route('frontHome'));
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
                    ->select('packages.id','packages.title','packages.description','packages.amount','packages.validity_days','packages.recurring_payment','packages.is_deleted','user_packages.id','user_packages.user_id','user_packages.package_id','user_packages.start_date','user_packages.end_date','user_packages.status')
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
        

        $data['user_id']           = $user_id;
        $data['title']             =  'Subscribe Packages';
        $data['packageDetails']    = $details;
        $data['subscribedPackage'] = $is_subscriber;
        $data['ramainingDays']     = $date_diff;
        $data['expiryDate']        = $ExpiredDate;
      
        return view('Front/Packages/index', $data);
    }

    public function subscribePackage(Request $request){

        $user_id       = $request->input('user_id'); 
        $package_id    = $request->input('p_id');
        $validity_days = $request->input('validity_days');
        $currentDate = date('Y-m-d H:i:s');

        $is_activePackage = DB::table('user_packages')
                    ->where('status','=','active')
                    ->where('user_id','=',$user_id)
                    ->where('end_date','>=',$currentDate)
                    ->select('user_packages.*')
                    ->get();
                    
        if(count($is_activePackage) != 0){
            $start_date = $is_activePackage[0]->end_date;
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$validity_days.' days'));
        }else{
            $start_date = date("Y-m-d H:i:s");
            $ExpiredDate = date('Y-m-d H:i:s', strtotime($start_date.'+'.$validity_days.' days'));
        }

        $arrInsertPackage = [ 
                              'user_id'    =>$user_id,
                              'package_id' => $package_id,
                              'status'     => 'active',
                              'start_date' => $start_date,
                              'end_date'   => $ExpiredDate,
                            ];

        UserPackages::create($arrInsertPackage);                   
        
        Session::flash('success', 'Package Subscribe successfully!');
        return $this->sellerPackages();
    }
    
}

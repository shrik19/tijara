<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Admin;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;

class AuthController extends Controller
{
    /*
     * Define abjects of models, services.
     */
    function __construct() {
       
    }
    use ThrottlesLogins;

    /*added for restrict for too many login attempt*/
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 5; // Default is 1 and we set it to 5 mininuts
    /**
     * Function for Show Login
     */
    public function index() {
        $data = [];

        if (Auth::guard('admin')->check()) {
            return redirect(route('adminDashboard'));
        }
        
        $data['pageTitle'] = 'Login';
        
        return view('Admin/login')->with($data);
    }

    /**
     * Function for refresh captcha
     */
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    /*function use for ThrottlesLogins to check too many logins */
    public function username() {
     return 'email';
    }
    /**
     * Login functionlity for admin
     * @param  Request $request 
     */
    public function login(Request $request) {

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $messages = [
            'email.required'     => 'Please enter Email!',
            'email.email'        => 'Please enter valid Email!',
            'password.required'       => 'Please enter Password!',
        ];

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $credentials = [
                'email'     => $request->input('email'),
                'password'  => $request->input('password'),
            ];

            $remember = false;
            if($request->input('remember'))
            {
                $remember = true;
            }
           
            $res = Auth::guard('admin')->attempt($credentials,$remember);
            if ($res) {
                $user = Auth::guard('admin')->user();
                if($user->status=='block') {
                    return redirect(route('adminLogout', ['error','Your account is blocked, contact the admin!']));
                }
                else
                {
                    // clear login attempt
                   $this->clearLoginAttempts($request);
                    return redirect(route('adminDashboard'));
                }
            }
            else {
                /*increament login attempt*/
                $this->incrementLoginAttempts($request);
                Session::flash('error', 'Invalid email or password!');
                return redirect()->back()->withInput();
            }
        }
    }

     /**
     * Function for dashboard
     */
    public function dashboard() {
        
        $data = $siteSetting = [];

        $data['pageTitle'] = 'Dashboard';
        $data['module_name'] = 'Summary';
        $data['current_module_name'] = '';
        $data['module_url'] = route('adminDashboard');
        return view('Admin/dashboard', $data);
    }

    /**
     * Function for Change Password
     */
    public function changePassword() {
        $data = $siteSetting = [];

        $data['pageTitle'] = trans('lang.change_password_menu');
        $data['module_name'] = trans('lang.change_password_menu');
        $data['current_module_name'] = '';
        $data['module_url'] = route('adminChangePassword');
        return view('Admin/change_password', $data);
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
            $user_id = \Auth::guard('admin')->id();
            
            $arrUpdate = ['password'=>bcrypt($request->input('password'))];
            Admin::where('id', $user_id)->update($arrUpdate);

            Session::Flash('success', 'Password changed successfully!');
            return redirect(route('adminChangePassword'));
        }
    }


    

    /**
     * Function for logout
     */
    public function logout($type='',$msg='', Request $request) {
        Auth::guard('admin')->logout();
        if(!empty($msg)) {
            if($type=='success') {
                \Session::flash('success', $msg);    
            }
            else {
                \Session::flash('error', $msg);
            }
        }
        
        return redirect(route('adminDashboard'));
        
    }
    
}

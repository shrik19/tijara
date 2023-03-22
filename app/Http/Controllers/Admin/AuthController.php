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
use App\Models\UserMain;
use App\Models\Products;

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
        
        $data['pageTitle'] = trans('lang.login_btn');
        
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
            'email.required'     => trans('errors.fill_in_email_err'),
            'email.email'        => trans('errors.valid_email_err'),
            'password.required'  => trans('errors.fill_in_password_err'),
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
                    return redirect(route('adminLogout', ['error',trans('errors.account_blocked_contact_admin_err')]));
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
                Session::flash('error', trans('errors.invalid_email_password_err'));
                return redirect()->back()->withInput();
            }
        }
    }

     /**
     * Function for dashboard
     */
    public function dashboard(Request $request) {
        
        $data = $siteSetting = [];

        $data['pageTitle']           = trans('lang.dashboard_menu');
        $data['module_name']         = trans('lang.summary_menu');
        $data['current_module_name'] = '';
        $data['module_url']          = route('adminDashboard');

        $currentYear = date('Y');
        $currentMonth = date('m');

        $monthName = ['01' => 'Januari', '02' => 'Februari', '03' => 'Mars', '04' => 'April', '05' => 'Maj', '06' => 'Juni', '07' => 'Juli', '08' => 'Augusti', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'December'];
        $filterData = [];

        $firstOrderDate = getFirstOrderYear();
        if(!empty($firstOrderDate))
        {
            $tmpDate = date('Y-m',strtotime($firstOrderDate['created_at']));
            $splitData = explode('-',$tmpDate);
            $year = $splitData[0];
            $month = $splitData[1];

            if($year == $currentYear)
            {
                for($i = $month; $i <= $currentMonth; $i++)
                {
                    if(strlen($i) == 1)
                    {
                        $i = '0'.$i;
                    }
                    $filterData[$i.'-'.$currentYear] = $monthName[$i].' '.$currentYear;
                }
            }
            else
            {
                for($j=$year; $j<=$currentYear; $j++)
                {
                    if($j < $currentYear)
                    {
                        for($i = $month; $i <= 12; $i++)
                        {
                            if(strlen($i) == 1)
                            {
                                $i = '0'.$i;
                            }
                            $filterData[$i.'-'.$j] = $monthName[$i].' '.$j;
                        }
                    }
                    else if($j == $currentYear)
                    {
                        for($i = 1; $i <= $currentMonth; $i++)
                        {
                            if(strlen($i) == 1)
                            {
                                $i = '0'.$i;
                            }
                            $filterData[$i.'-'.$j] = $monthName[$i].' '.$j;
                        }
                    }
                    
                }
            }
        }
        else
        {
            $filterData[$currentMonth.'-'.$currentYear] = $monthName[$currentMonth].' '.$currentYear;
        }

        $data['filterDate'] = $filterData;
        if($request->input('filter_date') == 'all_month')
        {
            $currentMonth=$currentYear='';
        }
        if($request->input('filter_date') != null && ($request->input('filter_date') != 'all_month'))
        {
            $tempDate = explode('-',$request->input('filter_date'));
            $currentMonth = $tempDate[0];
            $currentYear = $tempDate[1];
        }

        $data['orderCount'] = getTotalOrders($currentMonth,$currentYear);
        $data['serviceRequestCount'] = getTotalServiceRequests($currentMonth,$currentYear);
        $data['productCount'] = getTotalProducts($currentMonth,$currentYear);
        $data['servicesCount'] = getTotalServices($currentMonth,$currentYear);
        $data['totalAmount'] = getTotalAmount($currentMonth,$currentYear);
        $data['totalUsers'] = UserMain::get_buyers_count();
        $data['totalAds'] = Products::get_product_count();
        $data['currentDate'] = $currentMonth.'-'.$currentYear;
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
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',

        ];
        $messages = [
            'password.required'              => trans('errors.fill_in_password_err'),
            'password.confirmed'             => trans('errors.pwd_and_confirm_pwd_same_err'),
            'password_confirmation.required' => trans('errors.fill_in_confirm_password_err'),
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

            Session::Flash('success', trans('messages.pwd_changed_success'));
            return redirect(route('adminChangePassword'));
        }
    }


    

    /**
     * Function for logout
     */
    public function logout( Request $request, $type='',$msg='') {
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

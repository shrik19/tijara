<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings;
/*Uses*/
use Session;
use Validator;

class SettingController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /*function to open category open form*/
    public function create() {
        $data['pageTitle']              = trans('users.add_setting_title');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.settings_title');
        $data['module_url']             = route('adminSettingCreate');		
        $data['sitedata']				= Settings::get();
        return view('Admin/Setting/create', $data);
    }
    
     /**
     * Save Category details
     */
    public function store(Request $request) {		
          
        $rules = [
            'site_title'       => 'required',
            'footer_address'   => 'required',
            'copyright_content'      => 'required',
            // 'footer_logo'      => 'required',
        ];
        $messages = [
            'site_title.required'      => trans('errors.fill_in_site_title_err'),
            'footer_address.required'  => trans('errors.fill_in_footer_address_err'),
            'header_logo.required'     => trans('errors.upload_header_logo_err'),
            'footer_logo.required'     => trans('errors.upload_footer_logo_err'),
            'copyright_content.required'     => trans('lang.required_field_error'),
        ];
        
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }		 

        $arrInsertSettings = [                                
                                'site_title'  =>trim($request->input('site_title')),          
                                'footer_address' =>trim($request->input('footer_address')),  
                                'copyright_content' =>trim($request->input('copyright_content')),

                            ];

        if($request->hasfile('header_logo')){
            $image = $request->file('header_logo');
            $imageName = time().'_'.rand().'.' . $image->extension();
            $image->move(public_path('uploads/Images'), $imageName);

            $path = public_path().'/uploads/Images/'.$imageName;			
            $arrInsertSettings[] = ['header_logo' => $imageName];
        }
     
        if($request->hasfile('footer_logo')){
            $footer_logo = $request->file('footer_logo');
            $footerLogo = time().'_'.rand().'.' . $footer_logo->extension();
            $footer_logo->move(public_path('uploads/Images'), $footerLogo);

            $path = public_path().'/uploads/Images/'.$footerLogo;			
            $arrInsertSettings[]=['footer_logo' => $footerLogo];
        }

       
        if (Settings::exists()) {
          Settings::where('id', '>', 0)->update($arrInsertSettings);
        } else {
          Settings::create($arrInsertSettings);
        }                 
        
        Session::flash('success', trans('messages.settings_save_success'));
        return redirect(route('adminSettingCreate'));
    }
}
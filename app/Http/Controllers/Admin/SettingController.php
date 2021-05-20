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
        $data['pageTitle']              = 'Add Setting';
        $data['current_module_name']    = 'Add';
        $data['module_name']            = 'Settings';
        $data['module_url']             = route('adminSettingCreate');		$data['sitedata']				= Settings::get();
        return view('Admin/Setting/create', $data);
    }
    
     /**
     * Save Category details
     */
    public function store(Request $request) {		
          
        $rules = [
            'site_title'       => 'required',
            'footer_address'   => 'required',
            //'header_logo'      => 'required',
//            'footer_logo'      => 'required',
        ];
        $messages = [
            'site_title.required'      => 'Please fill in Site Tittle',
            'footer_address.required'  => 'Please fill in Footer Address',
            'header_logo.required'     => 'Please Upload Header Logo',
            'footer_logo.required'     => 'Please Upload Footer Logo',
        ];
        
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }		 $arrInsertSettings = [                                'site_title'  =>trim($request->input('site_title')),                                'footer_address' =>trim($request->input('footer_address')),                                                                                               ];
        if($request->hasfile('header_logo')){
            $image = $request->file('header_logo');
            $imageName = time().'_'.rand().'.' . $image->extension();
            $image->move(public_path('uploads/Images'), $imageName);

            $path = public_path().'/uploads/Images/'.$imageName;			$arrInsertSettings[] = ['header_logo' => $imageName];
        }
     
        if($request->hasfile('footer_logo')){
            $footer_logo = $request->file('footer_logo');
            $footerLogo = time().'_'.rand().'.' . $footer_logo->extension();
            $footer_logo->move(public_path('uploads/Images'), $footerLogo);

            $path = public_path().'/uploads/Images/'.$footerLogo;			$arrInsertSettings[]=['footer_logo' => $footerLogo];
        }

       

        Settings::create($arrInsertSettings);                   
        
        Session::flash('success', 'Details Inserted successfully!');
        return redirect(route('adminSettingCreate'));
    }
}
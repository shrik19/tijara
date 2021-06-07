<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\UserMain;

use App\Models\Services;

use App\Models\City;

use App\Models\UserPackages;

use App\Models\servicecategories;

use App\Models\ServiceSubcategories;

use App\Models\ServiceCategory;

use App\Models\Package;


/*Uses*/

use Auth;

use Session;

use flash;

use Validator;

use DB;



class ServiceController extends Controller

{

    /*

	 * Define abjects of models, services.

	 */

    function __construct() {

    
    	//$data['CurrentUser']   =   UserMain::where('id',Auth::guard('user')->id())->first();

    }



    /**

     * Show list of records for services.

     * @return [array] [record array]

     */

    public function index() {
        $data = [];
        $data['subscribedError']   =    '';
        if(!Auth::guard('user')->id()) {
            return redirect(route('frontLogin'));
        }
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) 
            {
               
                $data['subscribedError'] = 'Only services can show on site with active subscription';
                //return redirect(route('frontSellerPackages'));
            }
        }
        

        $data['pageTitle']              = 'Services';

        $data['current_module_name']    = 'Services';

        $data['module_name']            = 'Services';

        $data['module_url']             = route('manageFrontServices');

        $data['recordsTotal']           = 0;

        $data['currentModule']          = '';

		$CategoriesAndSubcategories		= Services::Leftjoin('category_services', 'services.id', '=', 'category_services.service_id')
											->Leftjoin('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
											->Leftjoin('serviceSubcategories', 'serviceSubcategories.id', '=', 'category_services.subcategory_id')
											->select(['servicecategories.category_name','serviceSubcategories.id','serviceSubcategories.category_id','serviceSubcategories.subcategory_name'])
											->where('services.is_deleted','!=',1)->where('services.user_id',Auth::guard('user')->id())
											->groupBy('serviceSubcategories.id')->orderBy('servicecategories.sequence_no')->get();
		
		$categoriesArray				=	array();
		foreach($CategoriesAndSubcategories as $category) {
			$categoriesArray[$category->category_id]['mainCategory']	=	$category->category_name;
			
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		
		$categoriesHtml					=	'<option value="">'.trans('lang.category_label').'</option>';
		
		$subCategoriesHtml				=	'<option value="">'.trans('lang.subcategory_label').'</option>';
		
		foreach($categoriesArray as $category_id=>$category) {
			if($category['mainCategory']!='')
			$categoriesHtml				.=	'<option value="'.$category_id.'">'.$category['mainCategory'].'</option>';
			
			foreach($category['subcategories'] as $subcategory_id=>$subcategory) {
				if($subcategory!='')
				$subCategoriesHtml		.=	'<option class="subcatclass" style="display:none;" id="subcat'.$category_id.'" value="'.$subcategory_id.'">'.$subcategory.'</option>';
			}
		}
		
		$data['categoriesHtml']        	= $categoriesHtml;
		$data['subCategoriesHtml']     	= $subCategoriesHtml;
		
		
        return view('Front/Services/index', $data);

    }

    

    
    /**

     * [getRecords for service list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) {

	if(!empty($request['category']) || !empty($request['subcategory'])) {
        $ServicesDetails = Services::Leftjoin('category_services', 'services.id', '=', 'category_services.service_id')	
											->select(['services.*'])
											->where('services.is_deleted','!=',1)->where('services.user_id',Auth::guard('user')->id());

         
	}
	else {
		$ServicesDetails = Services::select(['services.*'])
							->where('services.is_deleted','!=',1)->where('services.user_id',Auth::guard('user')->id());
	}
		if(!empty($request['search']['value'])) {

			

          $field = ['services.title','services.description'];

		  $namefield = ['services.title','services.description'];

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

       

        if(!empty($request['status'])) {

            $ServicesDetails = $ServicesDetails->Where('services.status', '=', $request['status']);

        }
		
		if(!empty($request['category']) || !empty($request['subcategory'])) {
			if(!empty($request['category'])) {

				$ServicesDetails = $ServicesDetails->Where('category_services.category_id', '=', $request['category']);

			}
			
			if(!empty($request['subcategory'])) {

				$ServicesDetails = $ServicesDetails->Where('category_services.subcategory_id', '=', $request['subcategory']);

			}
			
		}
		$ServicesDetails = $ServicesDetails->groupBy('services.id');
        if(isset($request['order'][0])){

            $postedorder=$request['order'][0];

           
			if($postedorder['column']==0) $orderby='services.title';
            
            if($postedorder['column']==1) $orderby='services.sort_order';
            
            if($postedorder['column']==2) $orderby='services.created_at';

            $orderorder=$postedorder['dir'];

            $ServicesDetails = $ServicesDetails->orderby($orderby, $orderorder);

        }

       
		
        $recordsTotal = $ServicesDetails->count();

        $recordDetails = $ServicesDetails->offset($request->input('start'))->limit($request->input('length'))->get();

        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 

            {

                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';

                
				$title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';

               
                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

                
                $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['updated_at']));
                
                $categories =   Services::Leftjoin('category_services', 'services.id', '=', 'category_services.service_id')	
                                            ->Leftjoin('serviceSubcategories', 'serviceSubcategories.id', '=', 'category_services.subcategory_id')	
											->select(['serviceSubcategories.subcategory_name'])
											->where('category_services.service_id',$recordDetailsVal['id'])->get();

                $categoriesData=    '';
                
                if(!empty($categories)) {
                    foreach($categories as $category){
                        $categoriesData .=   $category->subcategory_name.', ';
                    }
                }
                $categoriesData =   rtrim($categoriesData,', ');
                
                $action = '<a href="'.route('frontServiceEdit', base64_encode($id)).'" title="'.trans('lang.edit_label').'" class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';



                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('frontServiceDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class=""><i class="fas fa-trash"></i></a>';

            

                $arr[] = [ $title, $categoriesData,  $sort_order, $dated, $action];

            }

        } 

        else {

            $arr[] = [ '','',trans('lang.datatables.sEmptyTable'),  '','',''];

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

    $data['subscribedError']   =    '';
    if(!Auth::guard('user')->id()) {
            return redirect(route('frontLogin'));
        }
        $currentDate = date('Y-m-d H:i:s');
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) {
                
                $data['subscribedError']   =    'You must subscribe package to manage services';
            }
        }
                    
       
        $data['pageTitle']              = 'Save Service';

        $data['current_module_name']    = 'Save';

        $data['module_name']            = 'Services';

        $data['module_url']             = route('manageFrontServices');		
		
		$categories						=  servicecategories::Leftjoin('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
											->select('*')->get();
											
		$categoriesArray				=	array();
		
		foreach($categories as $category) {
			
			$categoriesArray[$category->category_id]['maincategory']	=	$category->category_name;
			
			$categoriesArray[$category->category_id]['subcategories'][$category->id]=	$category->subcategory_name;
		}
		$data['categories']				=	$categoriesArray;
	    
		if($id) {
			$service_id					=	base64_decode($id);
			$data['service_id']			=	$service_id;
			$data['service']			=	Services::where('id',$service_id)->first();
            
            //$data['AttributesValues']  =   AttributesValues::get();
          
			$selectedCategories			=	ServiceCategory::where('service_id',$service_id)->get();
			$selectedCategoriesArray	=	array();
			foreach($selectedCategories as $category) {
				$selectedCategoriesArray[]=	$category->subcategory_id;
			}
			
			$data['selectedCategories']	=	$selectedCategoriesArray;
			
		
			return view('Front/Services/edit', $data);
		}
		else {
			$data['service_id']			=	0;
		
			return view('Front/Services/create', $data);
		}
        

    }

/*
 Function to remove accent from string
 @param:string
*/
    public function php_cleanAccents($string){
       
        if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = strtr($string, $chars);
        return $string;  
    }
    
    public function store(Request $request) {
       // echo'<pre>';print_r($_POST);exit;
        $service_slug = $request->input('service_slug');
         $is_accents = preg_match('/^[\p{L}-]*$/u', $service_slug);
         if($is_accents ==1){
            $slug =   $this->php_cleanAccents($service_slug);
         }else{
            $slug = $request->input('service_slug');
         }

        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:1000',
            'sort_order'		=>'numeric',
            'service_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:services,service_slug',         
        ];

        $messages = [
            'title.required'         => trans('lang.required_field_error'),           
            'title.regex'            =>trans('lang.required_field_error'),     
            'description.max'        => trans('lang.max_1000_char'),
            'service_slug.required'  => trans('errors.service_slug_req'),
            'service_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'service_slug.unique'    => trans('messages.category_slug_already_taken'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrServices = [

                'title'        		=> trim(ucfirst($request->input('title'))),

                'service_slug'      => trim($slug),

                'description'       => trim($request->input('description')),

				'status'       		=> trim($request->input('status')),
				
				'sort_order'       	=> trim($request->input('sort_order')),
               
				'user_id'			=>	Auth::guard('user')->id()
            ];


		if($request->input('service_id')==0) {
			$id = Services::create($arrServices)->id;
		} else {
			$id		= $request->input('service_id');
			Services::where('id', $request->input('service_id'))->where('user_id', Auth::guard('user')->id())->update($arrServices);
		}
		
		ServiceCategory::where('service_id', $id)->delete();
			 
         if(!empty($request->input('categories'))) {
			 
			 foreach($request->input('categories') as $subcategory) {
				 $category	=	ServiceSubcategories::where('id',$subcategory)->first();
				 $servicecategories['service_id']	=	$id;
				 $servicecategories['category_id']	=	$category->category_id;
				 $servicecategories['subcategory_id']	=	$category->id;
				 
				 ServiceCategory::create($servicecategories);
				 
			 }
		 }

   

        

        Session::flash('success',trans('servicelang.service_saved_success'));

        return redirect(route('manageFrontServices')); 

    }

    

     /**

     * Delete Record

     * @param  $id = Id

     */

    public function delete($id) {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('frontService'));

        }



        $id = base64_decode($id);

        $result = Services::find($id);



        if (!empty($result)) {

           $service = Services::where('id', $id)->update(['is_deleted' =>1]);

           Session::flash('success', trans('lang.record_delete'));

                return redirect()->back();  

        } else {

            Session::flash('error', trans('lang.something_went_wrong'));

            return redirect()->back();

        }

    }

     /* function to check for unique slug name
    * @param:storename
    */
    function checkUniqueSlugName(Request $request){
        $slug_name = $request->slug_name;
        $id = base64_decode($request->id);
        $is_accents = preg_match('/^[\p{L}-]*$/u', $slug_name);
        if($is_accents ==1){
            $slug =   $this->php_cleanAccents($slug_name);
        }else{
            $slug = $slug_name;
        }

        if(!empty($id)){
            $data =  Services::where('service_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  Services::where('service_slug', $slug)->get();
        }
       $messages = '';
        if(!empty($data[0]['service_slug'])){
            $messages =trans('messages.category_slug_already_taken');
             return $messages;
        }
       
    }

}


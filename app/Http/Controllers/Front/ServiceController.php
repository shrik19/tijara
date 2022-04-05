<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserMain;
use App\Models\Services;
use App\Models\City;
use App\Models\UserPackages;
use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;
use App\Models\ServiceCategory;
use App\Models\Package;
use App\Models\ServiceRequest;
use App\Models\ServiceAvailability;
use App\Models\Settings;

use App\CommonLibrary;
use Intervention\Image\Facades\Image;

/*Uses*/

use Auth;

use Session;

use flash;

use Validator;

use DB;
use Mail;


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

        $CategoriesAndSubcategories     = Services::Leftjoin('category_services', 'services.id', '=', 'category_services.service_id')
                                            ->Leftjoin('servicecategories', 'servicecategories.id', '=', 'category_services.category_id')
                                            ->Leftjoin('serviceSubcategories', 'serviceSubcategories.id', '=', 'category_services.subcategory_id')
                                            ->select(['servicecategories.category_name','serviceSubcategories.id','serviceSubcategories.category_id','serviceSubcategories.subcategory_name'])
                                            ->where('services.is_deleted','!=',1)->where('services.user_id',Auth::guard('user')->id())
                                            ->groupBy('serviceSubcategories.id')->orderBy('servicecategories.sequence_no')->get();
        
        $categoriesArray                =   array();
        foreach($CategoriesAndSubcategories as $category) {
            $categoriesArray[$category->category_id]['mainCategory']    =   $category->category_name;
            
            $categoriesArray[$category->category_id]['subcategories'][$category->id]=   $category->subcategory_name;
        }
        
        $categoriesHtml                 =   '<option value="">'.trans('lang.category_label').'</option>';
        
        $subCategoriesHtml              =   '<option value="">'.trans('lang.subcategory_label').'</option>';
        
        foreach($categoriesArray as $category_id=>$category) {
            if($category['mainCategory']!='')
            $categoriesHtml             .=  '<option value="'.$category_id.'">'.$category['mainCategory'].'</option>';
            
            foreach($category['subcategories'] as $subcategory_id=>$subcategory) {
                if($subcategory!='')
                $subCategoriesHtml      .=  '<option class="subcatclass" style="display:none;" id="subcat'.$category_id.'" value="'.$subcategory_id.'">'.$subcategory.'</option>';
            }
        }
        
        $data['categoriesHtml']         = $categoriesHtml;
        $data['subCategoriesHtml']      = $subCategoriesHtml;
        
        
        return view('Front/Services/index', $data);

    }

    

    
    /**

     * [getRecords for service list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) {
      DB::enableQueryLog();

// and then you can get query log

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
            
            if($postedorder['column']==2) $orderby='services.sort_order';
            
            if($postedorder['column']==3) $orderby='services.created_at';

            $orderorder=$postedorder['dir'];

            $ServicesDetails = $ServicesDetails->orderby($orderby, $orderorder);

        }

       
        
        $recordsTotal = $ServicesDetails->get()->count();
        
        $recordDetails = $ServicesDetails->offset($request->input('start'))->limit($request->input('length'))->get();
       // echo "<pre>";
        //print_r(DB::getQueryLog());exit;
        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 

            {

                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';

                
                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';

               
                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

                
                $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['created_at']));
                
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
                
                $action = '<a href="'.route('frontServiceEdit', base64_encode($id)).'" 
                title="'.trans('lang.edit_label').'" style="color:#06999F;" class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';



                $action .= '<a href="javascript:void(0)" style="color:red;" onclick=" return ConfirmDeleteFunction(\''.route('frontServiceDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class=""><i class="fas fa-trash"></i></a>';

            

                $arr[] = [ $title, $categoriesData,  $sort_order, $dated, $action];

            }

        } 

        else {

            $arr[] = [ '',trans('lang.datatables.sEmptyTable'), '', '',''];

        }



        $json_arr = [

            'draw'            => $request->input('draw'),

            'recordsTotal'    => $recordsTotal,

            'recordsFiltered' => $recordsTotal,

            'data'            => ($arr),

        ];

        

        return json_encode($json_arr);

    }


    public function uploadServiceImage(Request $request){
        
        if(($request->file('fileUpload'))){

                       $fileError = 0;
                       $image=$request->file('fileUpload');
                       
           
                          
                    {
           
                           $name=$image->getClientOriginalName();
           
                           $fileExt  = strtolower($image->getClientOriginalExtension());
           
           
           
                            if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
           
                               $fileName = 'service-'.date('YmdHis').'.'.$fileExt;
           
                               $image->move(public_path().'/uploads/ServiceImages/', $fileName);  // your folder path
           
           
           
                               $path = public_path().'/uploads/ServiceImages/'.$fileName;
           
                               $mime = getimagesize($path);
           
           
           
                               if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
           
                               if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
           
                               if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
           
                               if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }
           
           
           
                               $old_x = imageSX($src_img);
           
                               $old_y = imageSY($src_img);
           
           
                                $width = 600;
                                $height = 600;
                               // echo "here".$old_x;exit;$img    = Image::make($image->getRealPath());
                                // we need to resize image, otherwise it will be cropped 
                                $imageNew = Image::make($path);

                                /*if ($old_x < $width) { 
                                    $imageNew->resize($width, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }

                                if ($old_y < $height) {
                                    $imageNew->resize(null, $height, function ($constraint) {
                                        $constraint->aspectRatio();
                                    }); 
                                }
                                $imageNew->resizeCanvas($width, $height, 'center', false, '#ffffff');
								*/
								
								$imageNew->resize($width, $height, function ($constraint) {
                                        $constraint->aspectRatio();
								});		
                                $imageNew->save(public_path("uploads/ServiceImages/{$fileName}"));
                                $img = Image::make(public_path("uploads/ServiceImages/{$fileName}"));
								
                                //$img->resize(300, 300, function ($constraint) {
                                //$constraint->aspectRatio();
                                //$constraint->upsize();
                                //})->save(public_path().'/uploads/ServiceImages/resized/' . $fileName);
                                //$img->destroy();
								
								$img->fit(300, 300);
                                $img->save(public_path().'/uploads/ServiceImages/resized/' . $fileName);
                                $img->destroy();
								
								
                                $img = Image::make(public_path("uploads/ServiceImages/{$fileName}"));
								$img->resizeCanvas($width, $height, 'center', false, '#ffffff');
                                //$img->resize(600, 600, function ($constraint) {
                                //$constraint->aspectRatio();
                                //$constraint->upsize();
                                //})
								$img->save(public_path().'/uploads/ServiceImages/serviceDetails/' . $fileName);
                                $img->destroy();
                                $img = Image::make(public_path("uploads/ServiceImages/{$fileName}"));
                                //$img->resize(100, 100, function ($constraint) {
                                //$constraint->aspectRatio();
                                //$constraint->upsize();
                                //})
								$img->fit(100, 100);
								$img->save(public_path().'/uploads/ServiceImages/serviceIcons/' . $fileName);
                                $img->destroy();

                   
                            } else {
           
                                   $fileError = 1;
           
                           }
           
                       }
           
           
           
                       if($fileError == 1) {
           
                           //Session::flash('error', 'Oops! Some files are not valid, Only .jpeg, .jpg, .png files are allowed.');
           
                           //return redirect()->back();
           
                       }
                       //$producVariant['image']=$fileName;
                       echo $fileName;
                   } 


   }

     /* function to open Services create form */

    public function serviceform($id='') {

    $data['subscribedError']   =    '';

    $max_seq_no ='';
    $max_seq_no = Services::max('sort_order');
    $data['max_seq_no'] = $max_seq_no + 1;

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
        
        $categories                     =  ServiceCategories::Leftjoin('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')->where('servicecategories.status','=','active')->where('serviceSubcategories.status','=','active')
                                            ->select('*')->get();
                                            
        $categoriesArray                =   array();
        
        foreach($categories as $category) {
            
            $categoriesArray[$category->category_id]['maincategory']    =   $category->category_name;
            
            $categoriesArray[$category->category_id]['subcategories'][$category->id]=   $category->subcategory_name;
        }
        $data['categories']             =   $categoriesArray;
        
        if($id) {
            $service_id                 =   base64_decode($id);
            $data['service_id']         =   $service_id;
            $data['service']            =   Services::where('id',$service_id)->first();
            
            //$data['AttributesValues']  =   AttributesValues::get();
          
            $selectedCategories         =   ServiceCategory::where('service_id',$service_id)->get();
            $selectedCategoriesArray    =   array();
            foreach($selectedCategories as $category) {
                $selectedCategoriesArray[]= $category->subcategory_id;
            }
            
            $data['selectedCategories'] =   $selectedCategoriesArray;
            
        
            $data['serviceAvailability']=   serviceAvailability::where('service_id',$service_id)->get();
            return view('Front/Services/edit', $data);
        }
        else {
            $data['service_id']         =   0;
        
            return view('Front/Services/create', $data);
        }
        

    }

    public function store(Request $request) {
      //echo "<pre>";print_r($_POST);exit;
        $service_slug = $request->input('service_slug');
        $slug =   CommonLibrary::php_cleanAccents($service_slug);
        $id     = $request->input('service_id');
        $rules = $messages = [];
        $rules = [ 
            'title'         => 'required',
            'description'   => 'required|max:2000',
            'sort_order'    =>'numeric',
            'service_price' => 'required', 
            'status' => 'required', 
            'session_time'  => 'required',
            'categories'  => 'required',
            'address'  => 'required',
            'telephone_number'  => 'required',
            //'service_availability'  => 'required',
          
           /* 'categories'  => 'required',
            'service_month' => 'required',
            'service_date'  => 'required',
            'start_time'    => 'required',*/
        ];
        if($request->input('service_id')==0) {

            $rules['service_slug'] = 'required|regex:/^[\pL0-9a-z-]+$/u';    
            $rules['start_date_time'] =  'required';
            $rules['to_date_time']    =  'required';
            $rules['del_start_time']  =  'required';
            
            
        }else{
            $rules ['service_slug'] = 'required|regex:/^[\pL0-9a-z-]+$/u';
            $rules ['service_availability']  = 'required';
        }

        $messages = [
            'title.required'         => trans('lang.required_field_error'),           
            'title.regex'            =>trans('lang.required_field_error'),     
            'description.required'        => trans('lang.required_field_error'),
            'description.max'        => trans('lang.max_1000_char'),
            'service_slug.required'  => trans('errors.service_slug_req'),
            'service_slug.regex'     => trans('errors.input_aphanum_dash_err'),  
            'session_time.required'  => trans('lang.required_field_error'),  
            'service_price.required'  => trans('lang.required_field_error'),  
            'start_date_time.required' => trans('errors.service_start_end_datetime_req'),
            'to_date_time.required'  => trans('errors.service_start_end_datetime_req'),
            'del_start_time.required' => trans('lang.required_field_error'),
            'status.required'            =>trans('lang.required_field_error'),
            'categories.required'  => trans('lang.required_field_error'), 
            'address.required'  => trans('lang.required_field_error'), 
            'telephone_number.required'    =>trans('lang.required_field_error'),
            //  'service_availability.required'  => trans('lang.required_field_error'),
            /*'service_year.required'  => trans('lang.required_field_error'),           
            'service_month.required' => trans('lang.required_field_error'), 
            'service_date.required'  => trans('lang.required_field_error'),           
            */
            
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails())  {
       
            $messages = $validator->messages();
             /*  echo "<pre>";
          print_r($messages);exit;*/
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrServices = [

                'title'             => trim(ucfirst($request->input('title'))),

                'service_slug'      => trim($slug),

                'description'       => trim($request->input('description')),

                'status'            => trim($request->input('status')),

                'address'            => trim($request->input('address')),

                'telephone_number'   => trim($request->input('telephone_number')),

                'service_price'      => trim($request->input('service_price')),
                
                'sort_order'        => trim($request->input('sort_order')),

                'session_time'        => trim($request->input('session_time')),
               
                'user_id'           =>  Auth::guard('user')->id()
            ];

            if(isset($_POST['hidden_images']) && !empty($_POST['hidden_images']) ) {
                $arrServices['images'] =   '';
                foreach($_POST['hidden_images'] as $img)
                    $arrServices['images'].=   $img.',';
                $arrServices['images'] =   rtrim($arrServices['images'],',');
            }
        if($request->input('service_id')==0) {
            $id = Services::create($arrServices)->id;
            //unique product code
            $string     =   'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            Services::where('id', $id)->update(['service_code'=>substr(str_shuffle($string),0, 4).$id]);
        } else {
            $id     = $request->input('service_id');
            Services::where('id', $request->input('service_id'))->where('user_id', Auth::guard('user')->id())->update($arrServices);
        }

      if($request->input('service_id')==0) {
      
        if(!empty($request->input('start_date_time')) && !empty($request->input('to_date_time'))){

          $startTime = strtotime( $request->input('start_date_time') );
          $endTime = strtotime( $request->input('to_date_time') );

          // Loop between timestamps, 24 hours at a time
          for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
            $availability = date( 'Y-m-d H:i', $i ); // 2010-05-01, 2010-05-02, etc
           
            $dateTime   =   explode(' ',$availability);           
            $service_availability['service_id']   =   $id;
            $service_availability['service_date']  =   $dateTime[0];
            $service_availability['start_time']   =   $dateTime[1];

            if($request->input('del_start_time')=='insert'){
              //echo "sdjkhk";exit;
                 $checkISExist = DB::table('service_availability')
                  ->where('service_id',$id)
                  ->where('service_date',$dateTime[0])
                  ->where('start_time',$dateTime[1])
                  ->get();

                  if($checkISExist->count() == 0){
              
                    ServiceAvailability::create($service_availability);
                  }
                 
                                   
                }else{
                  //echo "sdjhg";exit;
                  DB::table('service_availability')
                  ->where('service_id',$id)
                  ->where('service_date',$dateTime[0])
                  ->where('start_time',$dateTime[1])
                  ->delete();
                }
          }

        }

      }else{
        
        if(!empty($request->input('service_availability'))) {

          foreach($request->input('service_availability') as $availability) {
             //echo "<pre>";print_r($request->input('service_availability'));exit;
              //date('Y-m-d',strtotime($availability))
                 
              $dateTime   =   explode(' ',$availability);

              $service_availability['service_id']   =   $id;
              $service_availability['service_date']  =   $dateTime[0];
              $service_availability['start_time']   =   $dateTime[1];

              if($request->input('del_start_time')=='insert'){
           
                $checkISExist = DB::table('service_availability')
                  ->where('service_id',$id)
                  ->where('service_date',$dateTime[0])
                  ->where('start_time',$dateTime[1])
                  ->get();

                if($checkISExist->count() == 0){
     
                  ServiceAvailability::create($service_availability);
                }
                                                    
              }else{

                if(!empty($request->input('del_service_availability'))) {

                  foreach($request->input('del_service_availability') as $availability) {
                 
                    $dateTime   =   explode(' ',$availability);

                    $service_availability['service_id']   =   $id;
                    $service_availability['service_date']  =   $dateTime[0];
                    $service_availability['start_time']   =   $dateTime[1];
                    //print_r($service_availability);exit;
                       DB::table('service_availability')
                      ->where('service_id',$id)
                      ->where('service_date',$dateTime[0])
                      ->where('start_time',$dateTime[1])
                      ->delete();
                  }
                }
            } 
          }
        }
      
        }
        

        ServiceCategory::where('service_id', $id)->delete();

        if(empty($request->input('categories'))) {  
            $category  =   ServiceSubcategories::where('subcategory_name','Uncategorized')->first();
            
            $request->input('categories')[]=  $category->id;
            $servicecategories['service_id']    =   $id;
            $servicecategories['category_id']   =   $category->category_id;
            $servicecategories['subcategory_id']    =   $category->id;
            ServiceCategory::create($servicecategories);          
        } 
             
         if(!empty($request->input('categories'))) {
             
             foreach($request->input('categories') as $subcategory) {
                 $category  =   ServiceSubcategories::where('id',$subcategory)->first();
                 $servicecategories['service_id']   =   $id;
                 $servicecategories['category_id']  =   $category->category_id;
                 $servicecategories['subcategory_id']   =   $category->id;
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

           /*Session::flash('success', trans('lang.record_delete'));

                return redirect()->back();  */
            return response()->json(['success'=>trans('lang.record_delete')]);

        } else {

            /*Session::flash('error', trans('lang.something_went_wrong'));

            return redirect()->back();*/
             return response()->json(['error'=>trans('errors.something_went_wrong')]);    
        }

    }

     /* function to check for unique slug name
    * @param:storename
    */
    function checkUniqueSlugName(Request $request){
        
        $slug_name = $request->slug_name;
        $id = $request->id;
        // Clean up multiple dashes or whitespaces
        $slug_trim = trim(preg_replace('/\s+/', ' ', $slug_name));
        // Convert whitespaces and underscore to dash
        $slug_hypen = preg_replace("/[\s_]/", "-", $slug_trim);  
        $slug_p = str_replace("-p-", '', $slug_hypen); 
        $slug_s = str_replace("-s-", '', $slug_p); 
        $slug =   CommonLibrary::php_cleanAccents($slug_s);

        
        $data =  Services::where('service_slug','like', $slug.'%')->offset(0)->limit(1)->orderBy('id','desc');
        
        if(!empty($id))
            $data   =   $data->where('id','!=',$id);
            $data   =   $data->first();
        if(!empty($data)) {
            $slugParts  =   explode('-',$data->service_slug);
            $slug_number=   end($slugParts);
            
            if(is_numeric($slug_number))
                return $slug.'-'.($slug_number+1);
            else
            
            return $slug.'-1';
        }
        else
            return $slug;
        

       /*$unique_slug =1;
        if(!empty($data[0]['service_slug'])){
            do{
                $slug = $slug."-".$unique_slug;
                if(!empty($id)){
                    $data =  Services::where('service_slug', $slug)->where('id','!=',$id)->get();
                } else{
                    $data =  Services::where('service_slug', $slug)->get();
                }
            }while(!empty($data[0]['service_slug']));
            return $slug;
        }else{
            return $slug;
        }*/
    }


    public function showAllServiceRequest(Request $request)
    {
      
      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $orderDetails = [];
      if($user_id)
      {
        $userRole = session('role_id');
        if($userRole == 2)
        {
          $is_seller = 1;
        }  

     
        
          $monthYearDropdown    = "<select name='monthYear' id='monthYear' class='form-control debg_color' style='color:#fff !important;margin-top: -2px;'>";
          $monthYearSql = ServiceRequest::select(DB::raw('count(id) as `service_requests`'), DB::raw("DATE_FORMAT(service_date, '%m-%Y') new_date"),  DB::raw('YEAR(service_date) year, MONTH(service_date) month'));
            if($userRole == 1){
                $monthYearSql = $monthYearSql->where('user_id','=',$user_id);
            }
             
            $monthYearSql = $monthYearSql->groupby('year','month')
              ->get();
            $monthYearDropdown    .= "<option value=''>".trans('lang.select_label')."</option>";  
          if(!empty($monthYearSql) && count($monthYearSql)>0){ 

            foreach ($monthYearSql as $key => $value) {
              $i=$value['month'];
              $year =$value['year'];
              $month =  date("M", strtotime("$i/12/10"));
              $new_date = $value['new_date'];

              if($new_date==$request['monthYear']){
                $selected = "selected";
              }else{
                $selected = "";
              }
          
              $monthYearDropdown    .=  "<option  value='".$new_date."' ".$selected.">$month $year</option>";
            }
          }else{
             $monthYearDropdown    .= "<option value=''>".trans('lang.select_label')."</option>";
          }
          $monthYearDropdown    .= "</select>";
           //$User   =   UserMain::where('id',Auth::guard('user')->id())->first();
 
        $serviceRequest = serviceRequest::join('users','users.id','=','service_requests.user_id')
          ->join('services','services.id','=','service_requests.service_id')

          ->select('services.user_id as seller_id','services.title','services.service_price as price','services.images','services.description','services.service_slug','services.telephone_number','services.service_code','users.fname','users.lname','users.email'
          ,'users.store_name','service_requests.*')->where('service_requests.is_deleted','!=',1)->where('service_requests.user_id','=',$user_id);
           

        if(!empty($request['monthYear'])) {
              
          $month_year_explod =explode("-",$request['monthYear']);
           
          $serviceRequest = $serviceRequest->whereMonth('service_requests.service_date', '=', $month_year_explod[0])
          ->whereYear('service_requests.service_date',$month_year_explod[1]);

        }
        
          $serviceRequest = $serviceRequest->groupBy('service_requests.id')->orderby('service_requests.id', 'DESC');
          $serviceRequest       = $serviceRequest->paginate(12);

 
        //print_r(DB::getQueryLog());exit;
        $data['buyerBookingRequest']  = $serviceRequest;
        $data['monthYearHtml']     = $monthYearDropdown;
        $data['is_seller']         = $is_seller;
        $data['user_id']           = $user_id;
        return view('Front/buyer_booking_request', $data);
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
    }

   /*
    public function getAllServiceRequest(Request $request) 
    {
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();

        $serviceRequest = serviceRequest::join('users','users.id','=','service_requests.user_id')
          ->join('services','services.id','=','service_requests.service_id')
          ->select('services.title','services.description','users.fname','users.lname','users.email'
          ,'service_requests.*')->where('service_requests.is_deleted','!=',1);

        if($User->role_id==2) {
          //seller
          $serviceRequest = $serviceRequest->where('services.user_id','=',$request['user_id']);
      }
      else 
      {
        $serviceRequest = $serviceRequest->where('service_requests.user_id','=',$request['user_id']);
      }

      if(!empty($request['search']['value'])) 
      {
            $field = ['users.fname','users.lname','users.email','services.title'];
            $namefield = ['users.fname','users.lname','users.email','services.title'];
            $search=($request['search']['value']);
            
            $serviceRequest = $serviceRequest->Where(function ($query) use($search, $field,$namefield) 
            { 
                if(strpos($search, ' ') !== false)
                {
                      $s=explode(' ',$search);
                      foreach($s as $val) {
                          for ($i = 0; $i < count($namefield); $i++){
                              $query->orwhere($namefield[$i], 'like',  '%' . $val .'%');
                          }  
                      }
                  }
                  else 
                  {
                    for ($i = 0; $i < count($field); $i++){
                        $query->orwhere($field[$i], 'like',  '%' . $search .'%');
                  }  

                }        
              }); 
      }               

        if(!empty($request['monthYear'])) {
              
          $month_year_explod =explode("-",$request['monthYear']);
           
          $serviceRequest = $serviceRequest->whereMonth('service_requests.service_date', '=', $month_year_explod[0])
          ->whereYear('service_requests.service_date',$month_year_explod[1]);

        }
        
          $recordsTotal = $serviceRequest->groupBy('service_requests.id')->get()->count();
      
          $serviceRequest = $serviceRequest->groupBy('service_requests.id')->orderby('service_requests.id', 'DESC');

      
        
     
          $recordDetails = $serviceRequest->offset($request->input('start'))->limit($request->input('length'))->get();

          $arr = [];

          if (count($recordDetails) > 0) {

              $recordDetails = $recordDetails->toArray();
              foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
              {
                  $action = $status = $image = '-';
                  $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                  $user = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';
                  $serviceName = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                  //$message = (!empty($recordDetailsVal['message'])) ? $recordDetailsVal['message'] : '-';
                  $description = (!empty($recordDetailsVal['description'])) ? substr(strip_tags($recordDetailsVal['description']), 0, 110) : '-';
                  $dated      =   date('Y-m-d g:i a',strtotime($recordDetailsVal['created_at']));
                  $service_time  = (!empty($recordDetailsVal['service_time'])) ? $recordDetailsVal['service_date'].' '.$recordDetailsVal['service_time'] : '-';
                  $location   = (!empty($recordDetailsVal['location'])) ? $recordDetailsVal['location'] : '-';
                  $service_price = (!empty($recordDetailsVal['service_price'])) ? $recordDetailsVal['service_price'] : '-';
                  //$price_type    = (!empty($recordDetailsVal['price_type'])) ? $recordDetailsVal['price_type'] : '-';
                   $action = '<a style="margin-left:38px;" href="javascript:void(0);" 
                   user_name="'.$user.'" serviceName="'.$serviceName.'" 
                    dated="'.$dated.'" id="'.$id.'" 
                    class="serviceReqDetails  " title="'.$serviceName.'" 
                    id="'.$id.'" description="'.$description.'" 
                     service_time="'.$service_time.'" 
                     service_price="'.$service_price.'" location="'.$location.'"  
                     ><i style="color:#2EA8AB;" class="fas fa-eye"></i></a>&nbsp&nbsp&nbsp';

                 

                  if(!empty($request['is_seller']) && $request['is_seller'] == '1') 
                  {
                    $arr[] = [ '#'.$id, $user,$serviceName,$service_time,$service_price,$location, $dated, $action];
                  }
                  else
                  {
                    $action .= '<a href="javascript:void(0)" 
                    onclick=" return ConfirmDeleteFunction(\''.route('frontServiceRequestDel', base64_encode($id)).'\');" 
                     title="'.trans('lang.delete_title').'" class="" style="color:red;">
                     <i class="fas fa-trash"></i></a>';
                    $arr[] = [ '#'.$id, $serviceName,$service_time,$service_price,$location, $dated, $action];
                  }
                
              }

          } 

          else {

            if(!empty($request['is_seller']) && $request['is_seller'] == '1') 
            {
              $arr[] = [ '', '', '', '', trans('lang.datatables.sEmptyTable'), '', '',''];
            }
            else
            {
              $arr[] = [ '', '', '', trans('lang.datatables.sEmptyTable'), '','',''];
            }

          }



          $json_arr = [

              'draw'            => $request->input('draw'),

              'recordsTotal'    => $recordsTotal,

              'recordsFiltered' => $recordsTotal,

              'data'            => ($arr),

          ];

      return json_encode($json_arr);

  }*/

  /*function to get all booking request*/
   public function bookingRequest(Request $request) 
    {
      $user_id = Auth::guard('user')->id();
      $is_seller = 0;
      $orderDetails = [];
      if($user_id)
      {
        $userRole = session('role_id');
        if($userRole == 2)
        {
          $is_seller = 1;
        }  

        //$User   =   UserMain::where('id',$user_id)->first();

        $serviceRequest = serviceRequest::join('users','users.id','=','service_requests.user_id')
          ->join('services','services.id','=','service_requests.service_id')
          ->select('services.title','services.description','users.fname','users.lname','users.email'
          ,'users.address','users.postcode','users.city','service_requests.*')->where('service_requests.is_deleted','!=',1)->where('services.user_id','=',$user_id)->groupBy('service_requests.id')->orderby('service_requests.id', 'DESC')->get();
          
        $data['bookingRequest']    = $serviceRequest;
        $data['is_seller']         = $is_seller;
        $data['user_id']           = $user_id;

        return view('Front/service_booking_request', $data);
      }
      else 
      {
          Session::flash('error', trans('errors.login_buyer_required'));
          return redirect(route('frontLogin'));
      }
     
        //  $recordDetails = $serviceRequest->offset($request->input('start'))->limit($request->input('length'))->get();

  }

   /**
     * Delete service request 
     * @param  $id = Id
     */

    public function deleteServiceRequest($id) {

      if(empty($id)) {
        Session::flash('error', 'Something went wrong. Reload your page!');
        return redirect(route('frontAllServiceRequest'));
      }

      $id = base64_decode($id);
      $result = ServiceRequest::find($id);

      if (!empty($result)) {
        $service_request = Services::join('users', 'users.id', '=', 'services.user_id')
                            ->join('service_requests', 'services.id', '=', 'service_requests.service_id')
                            ->where('service_requests.id', '=', $id)
                            ->where('service_requests.user_id', '=', Auth::guard('user')->id())->first();

        $user = DB::table('users')->where('id', '=', Auth::guard('user')->id())->first();
        $buyer_email =$user->email;
        $customername = $user->fname;
        $customeraddress    =   $user->address.' '.$user->city.' '.$user->postcode;
        $sellername     =$service_request->fname;

        $service    =   $service_request->title;
        $email      =   $service_request->email;
        //$servicemessage =   $request->input('message');
        $service_date=  $service_request->service_date;
        $service_time=  $service_request->service_time;
        //$service_time =   date('Y-m-d H:i:s',strtotime($service_time));
        $seller =   $service_request->fname;
        
        $GetEmailContents = getEmailContents('Delete Service Request');
        $subject      = $GetEmailContents['subject'];
        $contents     = $GetEmailContents['contents'];
        $siteDetails  = Settings::first();
        $siteLogo     = url('/')."/uploads/Images/".$siteDetails->header_logo;
        $fb_link      = env('FACEBOOK_LINK');
        $insta_link   = env('INSTAGRAM_LINK');
        $linkdin_link = env('LINKDIN_LINK');
        $contents = str_replace(['##CUSTOMERNAME##', '##NAME##','##SERVICE##','##SERVICETIME##'
        ,'##SERVICEDATE##','##SERVICELOCATION##','##SERVICECOST##','##SITE_URL##','##SITE_LOGO##','##FACEBOOK_LINK##','##INSTAGRAM_LINK##','##LINKDIN_LINK##',
            '##CUSTOMERADDRESS##','##SELLER##'],
        [$customername,$seller,$service,$service_time,$service_date,$service_request->location,
        $service_request->service_price,url('/'),$siteLogo,$fb_link,$insta_link,$linkdin_link,$customeraddress,$sellername],$contents);

        $arrMailData = ['email_body' => $contents];

        Mail::send('emails/dynamic_email_template', $arrMailData, function($message) use ($email,$seller,$subject) {
            $message->to($email, $seller)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });

        $GetEmailContents = getEmailContents('Delete Service Request');
        $subject = $GetEmailContents['subject'];
        $contents_buyer = $GetEmailContents['contents'];
        $contents_buyer = str_replace(['##CUSTOMERNAME##', '##NAME##','##SERVICE##','##SERVICETIME##'
        ,'##SERVICEDATE##','##SERVICELOCATION##','##SERVICECOST##','##SITE_URL##','##SITE_LOGO##','##FACEBOOK_LINK##','##INSTAGRAM_LINK##','##LINKDIN_LINK##',
            '##CUSTOMERADDRESS##','##SELLER##'],
        [$customername,$customername,$service,$service_time,$service_date,$service_request->location,
        $service_request->service_price,url('/'),$siteLogo,$fb_link,$insta_link,$linkdin_link,$customeraddress,$sellername],$contents_buyer);

        $arrMailDataBuyer = ['email_body' => $contents_buyer];
        Mail::send('emails/dynamic_email_template', $arrMailDataBuyer, function($message) use ($buyer_email,$customername,$subject) {
            $message->to($buyer_email, $customername)->subject
                ($subject);
            $message->from( env('FROM_MAIL'),'Tijara');
        });

        $service = ServiceRequest::where('id', $id)->update(['is_deleted' =>1]);
        return response()->json(['success'=>trans('lang.booking_deleted')]); 
      } else {
         return response()->json(['error'=>trans('errors.something_went_wrong')]);
      }
    }

    /*public function ServiceRequestView(Request $request) {
    
        $user_id = $request->user_id;
      if(empty($user_id)) {
        Session::flash('error', 'Something went wrong. Reload your page!');
        return redirect(route('frontAllServiceRequest'));
      }

      $getBookedServices = Services::where('user_id', $user_id)->get()->toArray();
      $serviceIds=array();
      if(!empty($getBookedServices)) {
          foreach($getBookedServices as $services){ 
            $serviceIds []=$services['id'];
          }
      }

      $getNew  = ServiceRequest::select('service_requests.service_id')->where('is_new','=', 1)->get()->toArray();
       $NewServiceIds=array();
       if(!empty($getNew)){
        foreach($getNew as $new){       
            $NewServiceIds []=$new['service_id'];
        }
       }
      
       $result = array_intersect($serviceIds, $NewServiceIds);
        if(!empty($result)){
            foreach($result as $service_id){ 
                  $service = ServiceRequest::where('service_id', $service_id)->update(['is_new' =>0]);
                }
                return response()->json(['success'=>1]); 
        } else {
         return response()->json(['error'=>0]);
      }      
    }*/

    public function ServiceRequestView(Request $request) {
    
        $user_id = $request->user_id;
       // echo $user_id;exit;
      if(empty($user_id)) {
        Session::flash('error', 'Something went wrong. Reload your page!');
        return redirect(route('frontAllOrders'));
      }
      
       $allBookings = ServiceRequest::join('services', 'service_requests.service_id', '=', 'services.id')->where('services.user_id','=',$user_id)->where('service_requests.is_new','=','1')->where("services.is_deleted",'=','0')->get()->toArray();

       $getTotalOrders = $getTotalBookings = $totalNotifications = '';
      if(!empty($allBookings)) {
          foreach($allBookings as $serviceIDs){ 
             $service = ServiceRequest::where('service_id', $serviceIDs['id'])->update(['is_new' =>0]);
          }
          $getTotalOrders = getNewOrders(Auth::guard('user')->id());
          $getTotalBookings = getNewBookings(Auth::guard('user')->id());
          $totalNotifications = $getTotalOrders + $getTotalBookings;
          return response()->json(['success'=>1,'notification_count'=> $totalNotifications,'orders_count'=>$getTotalOrders,'bookings_count'=>$getTotalBookings]); 
      }else{
        return response()->json(['error'=>0]);
      }           
    }
}



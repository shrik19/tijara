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

use App\CommonLibrary;

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
           
                               $new_thumb_loc = public_path().'/uploads/ServiceImages/resized/' . $fileName;
           
           
           
                               if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
           
                               if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
           
                               if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
           
                               if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
           
           
           
                               imagedestroy($dst_img);
           
                               imagedestroy($src_img);
           
           
           
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
        
        $categories                     =  ServiceCategories::Leftjoin('serviceSubcategories', 'servicecategories.id', '=', 'serviceSubcategories.category_id')
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
            
        
            return view('Front/Services/edit', $data);
        }
        else {
            $data['service_id']         =   0;
        
            return view('Front/Services/create', $data);
        }
        

    }

    public function store(Request $request) {

        $service_slug = $request->input('service_slug');
        $slug =   CommonLibrary::php_cleanAccents($service_slug);
        $id     = $request->input('service_id');
        $rules = $messages = [];
        $rules = [ 
            'title'         => 'required',
            'description'   => 'nullable|max:1000',
            'sort_order'        =>'numeric',
            'service_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:services,service_slug',         
        ];
        if($request->input('service_id')==0) {
            $rules = [ 
            'service_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:services,service_slug',         
            ];
        }else{
            $rules = [ 
                'service_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:services,service_slug,'.$id,   
            ];
        }

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

                'title'             => trim(ucfirst($request->input('title'))),

                'service_slug'      => trim($slug),

                'description'       => trim($request->input('description')),

                'status'            => trim($request->input('status')),
                
                'sort_order'        => trim($request->input('sort_order')),
               
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
        
        ServiceCategory::where('service_id', $id)->delete();
             
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
        $id = $request->id;
        // Clean up multiple dashes or whitespaces
        $slug_trim = trim(preg_replace('/\s+/', ' ', $slug_name));
        // Convert whitespaces and underscore to dash
        $slug_hypen = preg_replace("/[\s_]/", "-", $slug_trim);  
        $slug =   CommonLibrary::php_cleanAccents($slug_hypen);

        if(!empty($id)){
            $data =  Services::where('service_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  Services::where('service_slug', $slug)->get();
        }

       $unique_slug =1;
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
        }
    }

}


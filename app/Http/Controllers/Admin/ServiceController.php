<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;


use App\Models\Services;

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



    }



    /**

     * Show list of records for products.

     * @return [array] [record array]

     */

    public function index() {

        $data = [];

        $data['pageTitle']              = trans('lang.manage_services_menu');

        $data['current_module_name']    = trans('lang.manage_services_menu');

        $data['module_name']            = trans('lang.manage_services_menu');

        $data['module_url']             = route('adminService');

        $data['recordsTotal']           = 0;

        $data['currentModule']          = '';

        return view('Admin/Service/index', $data);

    }




    /**

     * [getRecords for product list.This is a ajax function for dynamic datatables list]

     * @param  Request $request [sent filters if applied any]

     * @return [JSON]           [users list in json format]

     */

    public function getRecords(Request $request) {
        DB::enableQueryLog();

        $ServicesDetails = Services::Leftjoin('users', 'users.id', '=', 'services.user_id')->select(['services.*','users.fname','users.lname'])
                            ->where('services.is_deleted','!=',1)->where('users.is_deleted','!=',1);;
    
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
        
      /*  if(!empty($request['category']) || !empty($request['subcategory'])) {
            if(!empty($request['category'])) {

                $ServicesDetails = $ServicesDetails->Where('category_services.category_id', '=', $request['category']);

            }
            
            if(!empty($request['subcategory'])) {

                $ServicesDetails = $ServicesDetails->Where('category_services.subcategory_id', '=', $request['subcategory']);

            }
            
        }*/
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
        //print_r(DB::getQueryLog());exit;
        $arr = [];

        if (count($recordDetails) > 0) {

            $recordDetails = $recordDetails->toArray();

            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 

            {

                $action = $status = $image = '-';

                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $uname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';
               /* if(!empty($recordDetailsVal['images'])) {
                    $imagesParts    =   explode(',',$recordDetailsVal['images']);
                    
                    $image  =   url('/').'/uploads/ServiceImages/resized/'.$imagesParts[0];
                }
                else{
                    $image  =     url('/').'/uploads/ServiceImages/resized/no-image.png';
                }
                
                $image      =   '<img src="'.$image.'" width="70" height="70">';*/
                 if(!empty($recordDetailsVal['images'])) {
                   $imagesParts    =   explode(',',$recordDetailsVal['images']);
                    
                   $image_path  =   url('/').'/uploads/ServiceImages/serviceIcons/'.$imagesParts[0];
                   $image  =  '/uploads/ServiceImages/serviceIcons/'.$imagesParts[0];
                }
               
                if(file_exists(public_path($image))){
                    $image      =   '<img src="'.$image_path.'" width="70" height="70">';
                }else{
                    $no_image =  url('/').'/uploads/ServiceImages/serviceIcons/no-image.png';
                    $image      =   '<img src="'.$no_image.'" width="70" height="70">';
                }

                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';

               
                $sort_order = (!empty($recordDetailsVal['sort_order'])) ? $recordDetailsVal['sort_order'] : '-';

                date_default_timezone_set('Europe/Stockholm');
                $dated = $recordDetailsVal['created_at'];
                $dated = date('Y-m-d g:i a',strtotime("$dated UTC"));
             
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
                


                $action = '<a href="'.route('adminReviews' ,['service',base64_encode($id)]).'" title="'.trans('users.review_title').'" class="btn btn-icon btn-success"><i class="fas fa-comments"></i></a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminServiceDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

            

                $arr[] = [ $image, $title,$uname,'Kr'.$recordDetailsVal['service_price'],$categoriesData, $dated, $action];

            }

        } 

        else {

            $arr[] = ['', '','',trans('lang.datatables.sEmptyTable'),'', '',''];

        }



        $json_arr = [

            'draw'            => $request->input('draw'),

            'recordsTotal'    => $recordsTotal,

            'recordsFiltered' => $recordsTotal,

            'data'            => ($arr),

        ];

        

        return json_encode($json_arr);

    }



     /**

     * Delete Record

     * @param  $id = Id

     */

    public function delete($id) {

        if(empty($id)) {

            Session::flash('error', 'Something went wrong. Reload your page!');

            return redirect(route('adminService'));

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

}

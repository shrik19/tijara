<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\ServiceReview;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class ReviewController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for banner.
     * @return [array] [record array]
     */
    public function index($page,$id) {
  
        $data = [];
        $data['pageTitle']              = trans('users.review_title');
        $data['current_module_name']    = trans('users.review_title');
        $data['module_name']            = trans('users.review_title');
        $data['module_url']             = route('adminReviews',[$page,$id]);
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        $data['current_id'] = base64_decode($id);
        $data['page'] = $page;
    
        return view('Admin/Review/index', $data);
    }

    /**
     * [getRecords for user list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) 
    {
        $current_id = $request->current_id;
        $current_page = $request->current_page;
        //echo $request->current_id;exit;
        if($current_page == 'product'){
            $reviewDetails = ProductReview::Leftjoin('users', 'users.id', '=', 'product_review.user_id')->Leftjoin('products', 'products.id', '=', 'product_review.product_id')->select('product_review.comments','product_review.rating','product_review.id as review_id','products.title','users.fname','users.lname')->where('users.is_deleted','!=',1)->where('products.is_deleted','!=',1)->where('product_review.product_id','=',$request->current_id);
        
        }

        if($current_page == 'service'){
             $reviewDetails = ServiceReview::Leftjoin('users', 'users.id', '=', 'service_review.user_id')->Leftjoin('services', 'services.id', '=', 'service_review.service_id')->select('service_review.comments','service_review.rating','service_review.id as review_id','services.title','users.fname','users.lname')->where('users.is_deleted','!=',1)->where('services.is_deleted','!=',1)->where('service_review.service_id','=',$request->current_id);
        }
        if(!empty($request['search']['value'])) {


        if($current_page == 'product'){
          $field = ['users.fname','users.lname','products.title','product_review.comments'];
          $namefield = ['users.fname','users.lname','products.title','product_review.comments'];
        }else{
            $field = ['users.fname','users.lname','services.title','service_review.comments'];
            $namefield = ['users.fname','users.lname','services.title','service_review.comments'];
        }
         

          $search=($request['search']['value']);



            $reviewDetails = $reviewDetails->Where(function ($query) use($search, $field,$namefield) {

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

        if(isset($request['order'][0])){
            $postedorder=$request['order'][0];
            
            if($postedorder['column']==0) $orderby='review_id';
            if($postedorder['column']==1) $orderby='users.fname';

            if($current_page == 'product'){
                if($postedorder['column']==2) $orderby='products.title';
                if($postedorder['column']==4) $orderby='product_review.comments';
            }else{
                
                if($postedorder['column']==2) $orderby='services.title';
                if($postedorder['column']==4) $orderby='service_review.comments';
            }
          //  if($postedorder['column']==3) $orderby='variant_product.sku';
            
            $orderorder=$postedorder['dir'];
            $reviewDetails = $reviewDetails->orderby($orderby, $orderorder);
        } 

		
        $recordsTotal = $reviewDetails->count();
        $recordDetails = $reviewDetails->offset($request->input('start'))->limit($request->input('length'))->get();
		
	
        $arr = [];
        if (count($recordDetails) > 0)   {
            $recordDetails = $recordDetails->toArray();
						
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['review_id'])) ? $recordDetailsVal['review_id'] : '-';
                $uname = (!empty($recordDetailsVal['fname'])) ? $recordDetailsVal['fname'].' '.$recordDetailsVal['lname'] : '-';

                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                $comment   = (!empty($recordDetailsVal['comments'])) ? $recordDetailsVal['comments'] : '-';

                /*$action = '<a href="'.route('adminBannerEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';*/
                if($current_page == 'product'){
                    $action = '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminReviewDelete',['product',base64_encode($id)]).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                }else{
                   $action = '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminReviewDelete',['service',base64_encode($id)]).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>'; 
                }
     
                $arr[] = [$uname, $title,$comment,$action];
            }
        } 
        else {
            $arr[] = ['', '', trans('lang.datatables.sEmptyTable'), '', '',  ''];
        }

        $json_arr = [
            'draw'              => $request->input('draw'),
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => ($arr),
        ];
        
        return json_encode($json_arr);
    }
   
     /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($page,$id) {
  
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminReviews',[$page,$id]));
        }
        $id = base64_decode($id);

        if($page == 'product'){
            $result = ProductReview::find($id);
        }

        if($page == 'service'){
            $result = ServiceReview::find($id);
        }

       
        if (!empty($result)) {
            if($page == 'product'){
                $delete=ProductReview::where('id',$id)->delete();
            }
            if($page == 'service'){
                $delete=ServiceReview::where('id',$id)->delete();
            }
            
            Session::flash('success', trans('messages.record_deleted_success'));
            return redirect()->back();
        } 
        else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }  
}

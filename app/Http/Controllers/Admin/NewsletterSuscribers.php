<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserMain;
use App\Models\SubscribedUsers;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;
use File;
use Mail;

class NewsletterSuscribers extends Controller
{
    /*
     * Define abjects of models, services.
     */
    function __construct() {
        
    }

    /**
     * Show list of records for sellers.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.subcribed_title');
        $data['current_module_name']    = trans('users.subcribed_title');
        $data['module_name']            = trans('users.subcribed_title');
        $data['module_url']             = route('adminNewsletterUser');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/NewsletterSubscriber/index', $data);
    }
    
     /**
     * funcyion to export users details in csv format
     * @param  $id = Id, $status = active/block 
     */
    public function exportdata(Request $request) {           
        $usersDetails = SubscribedUsers::select('subscribed_users.*');
         
        if(!empty($request->search)) {
            
            $field = ['subscribed_users.id','subscribed_users.email','subscribed_users.created_at'];
            $namefield = ['subscribed_users.id','subscribed_users.email','subscribed_users.created_at'];
            $search=($request->search);
            
            $usersDetails = $usersDetails->Where(function ($query) use($search, $field,$namefield) {
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
           if($request['status']=='active'){
                  $usersDetails = $usersDetails->Where('subscribed_users.is_subscribed', '=', 1);
            }else{
                 $usersDetails = $usersDetails->Where('subscribed_users.is_subscribed', '=', 0);
            }   
        }

        $recordDetails = $usersDetails->get(['subscribed_users.email','subscribed_users.created_at','subscribed_users.is_subscribed']);
           
        $filename = "NewsLetterSubscriberFromTijara.csv";
       // $path = url('/').'/public/NewsLetterSubscriber/'.$filename;
        $path = '/public/NewsLetterSubscriber/'.$filename;
        $handle = fopen(base_path() .$path,'w+');

        //$handle = fopen($path, 'w+');
        fputcsv($handle, array(trans('users.email_title'),trans('users.is_subscrier_title'),trans('users.page_created_title')));
   
        foreach($recordDetails as $row) {
          if ($row->is_subscribed == 1) {
                $is_subscribed = 'Subscriber';
             } else { 
                $is_subscribed = 'UnSubscribed';
            }
            $created_at = date('Y-m-d',strtotime($row->created_at));
           
            fputcsv($handle, array($row->email,$is_subscribed,$created_at));
        }
    
        fclose($handle);
        return $filename;
    }


    /**
     * [getRecords for users subscribed list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {
        $UsersDetails = SubscribedUsers::select('subscribed_users.*');
           
        if(!empty($request['search']['value'])) {
            
          $field = ['subscribed_users.email','subscribed_users.created_at','subscribed_users.is_subscribed'];
          $namefield = ['subscribed_users.email','subscribed_users.created_at','subscribed_users.is_subscribed'];
          $search=($request['search']['value']);
            
            $SellerDetails = $UsersDetails->Where(function ($query) use($search, $field,$namefield) {
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

        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $UsersDetails = $UsersDetails->Where('subscribed_users.is_subscribed', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
           if($request['status']=='active'){
                  $UsersDetails = $UsersDetails->Where('subscribed_users.is_subscribed', '=', 1);
            }else{
                 $UsersDetails = $UsersDetails->Where('subscribed_users.is_subscribed', '=', 0);
            }   
        }
      
        if(isset($request['order'][0])){
            $postedorder=$request['order'][0];
            if($postedorder['column']==0) $orderby='subscribed_users.id';
            if($postedorder['column']==1) $orderby='subscribed_users.email';
            if($postedorder['column']==2) $orderby='subscribed_users.created_at';
            if($postedorder['column']==3) $orderby='subscribed_users.is_subscribed';
            
            $orderorder=$postedorder['dir'];
            $UsersDetails = $UsersDetails->orderby($orderby, $orderorder);
        }
       
        $recordsTotal = $UsersDetails->count();
        $recordDetails = $UsersDetails->offset($request->input('start'))->limit($request->input('length'))->get();
       
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            $i = 1;
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
            {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $sr_no = $i;
                $email = (!empty($recordDetailsVal['email'])) ? $recordDetailsVal['email'] : '-';
                $created_at = (!empty($recordDetailsVal['created_at'])) ? date('Y-m-d',strtotime($recordDetailsVal['created_at'])) : '-';
              
           

                if ($recordDetailsVal['is_subscribed'] == 1) {
                    $is_subscribed = 'Subscriber';
                 } else { 
                    $is_subscribed = 'UnSubscribed';
                }
           
                $arr[] = [$sr_no,$email,$is_subscribed,$created_at];
                $i++;
            }
        } 
        else {
            $arr[] = ['','',trans('lang.datatables.sEmptyTable'), ''];
        }

        $json_arr = [
            'draw'            => $request->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => ($arr),
        ];
        
        return json_encode($json_arr);
    }
    
}
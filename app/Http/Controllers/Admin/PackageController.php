<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Package;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class PackageController extends Controller
{
    /*
    * Define abjects of models, services.
    */
    function __construct() {

    }

    /**
    * Show list of records for Package.
    * @return [array] [record array]
    */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.Package_title');
        $data['current_module_name']    = trans('users.Package_title');
        $data['module_name']            = trans('users.Package_title');
        $data['module_url']             = route('adminPackage');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Package/index', $data);
    }

    /**
    * [getRecords for package list.This is a ajax function for dynamic datatables list]
    * @param  Request $request [sent filters if applied any]
    * @return [JSON]           [users list in json format]
    */
    public function getRecords(Request $request) {

        $PackageDetails = Package::select('packages.*')->where('packages.is_deleted','!=',1);

        if(!empty($request['search']['value'])){
            $field = ['packages.title','packages.description','packages.amount','packages.validity_days','packages.created_at','packages.status'];
            $namefield = ['packages.title','packages.description','packages.amount','packages.validity_days','packages.created_at','packages.status'];
            $search=($request['search']['value']);

            $PackageDetails = $PackageDetails->Where(function ($query) use($search, $field,$namefield) {
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
            if($postedorder['column']==0) $orderby='packages.title';
            if($postedorder['column']==1) $orderby='packages.description';
            if($postedorder['column']==2) $orderby='packages.amount';
            if($postedorder['column']==3) $orderby='packages.validity_days';
            if($postedorder['column']==4) $orderby='packages.created_at';
            $orderorder=$postedorder['dir'];
            $PackageDetails = $PackageDetails->orderby($orderby, $orderorder);
        }

        $recordsTotal = $PackageDetails->count();
        $recordDetails = $PackageDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        $arr = [];

        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
            {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                $description = (!empty($recordDetailsVal['description'])) ? $recordDetailsVal['description'] : '-';
                $amount = (!empty($recordDetailsVal['amount'])) ? $recordDetailsVal['amount'] : '-';
                $valid_days = (!empty($recordDetailsVal['validity_days'])) ? $recordDetailsVal['validity_days'] : '-';
                $dated =  (!empty($recordDetailsVal['created_at'])) ?  date('d M y', strtotime($recordDetailsVal['created_at'])) : '-';


                 if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminPackageChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminPackageChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminPackageEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';
                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminPackageDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                $arr[] = [$title, $description, $amount, $valid_days,$dated,$status, $action];
            }
        } 
        else {
            $arr[] = ['',  '', '', trans('lang.datatables.sEmptyTable'), '', '',''];
        }

        $json_arr = [
            'draw'            => $request->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => ($arr),
        ];

        return json_encode($json_arr);
    }

    /* function to open package create form */
    public function create() {
        $data['pageTitle']              = trans('users.add_package_btn');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.Package_title');
        $data['module_url']             = route('adminPackage');
        return view('Admin/Package/create', $data);
    }

    /*function to save package details*/
    public function store(Request $request) {
        $rules = [
        'title'             => 'required',
       // 'description'       => 'required',
        'amount'            => 'required',
        'validity_days'     => 'required',
       // 'recurring_payment' => 'required',
        //'status'            => 'required',
        ];
        $messages = [
            'title.required'              => trans('errors.fill_in_title'),
            //'description.required'        => 'Please fill in Description',
            'amount.required'             => trans('errors.fill_in_amount'),
            'validity_days.required'      => trans('errors.fill_in_validity_days'),
            //'recurring_payment.required'  => ' Please select Recurring payment',
            //'status.required'             => 'Please select Status',
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $arrPackageInsert = [
            'title'             => trim($request->input('title')),
            'description'       => trim($request->input('description')),  
            'amount'            => trim($request->input('amount')),
            'validity_days'     => trim($request->input('validity_days')),
            'recurring_payment' => trim($request->input('recurring_payment')),
            'status'            => trim($request->input('status')),
        ];

        $id = Package::create($arrPackageInsert)->id;
        Session::flash('success', trans('messages.package_save_success'));
        return redirect(route('adminPackage'));
    }
    /**
    * Edit record details
    * @param  $id = package Id
    */
    public function edit($id) {
        if(empty($id))  {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data = $details = [];
        $data['id'] = $id;
        $id = base64_decode($id);
        $details = Package::where('Id', $id)->first();

        if(empty($details)){
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();   
        }

        $data['pageTitle']              = trans('users.edit_package_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.Package_title');
        $data['module_url']             = route('adminPackage');
        $data['PackageDetails']         = $details;

        return view('Admin/Package/edit', $data);
    }

    /**
    * Update package details
    * @param  $id = package Id
    */
    public function update(Request $request, $id){
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $id = base64_decode($id);

        $rules = [
            'title'             => 'required',
            //'description'       => 'required',
            'amount'            => 'required',
            'validity_days'     => 'required',
           /* 'recurring_payment' => 'required',
            'status'            => 'required',*/
        ];

        $messages = [
            'title.required'              => trans('errors.fill_in_title'),
            //'description.required'        => 'Please fill in Description',
            'amount.required'             => trans('errors.fill_in_amount'),
            'validity_days.required'      => trans('errors.fill_in_validity_days'),
            /*'recurring_payment.required'   => ' Please select Recurring payment',
            'status.required'              => 'Please select Status',*/
        ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        } else {

            $arrPackageUpdate = [
                'title'              => trim($request->input('title')),
                'description'        => trim($request->input('description')),   
                'amount'             => trim($request->input('amount')),
                'validity_days'      => trim($request->input('validity_days')),
                'recurring_payment'  => trim($request->input('recurring_payment')),
                'status'             => trim($request->input('status')),
            ];

            Package::where('id', '=', $id)->update($arrPackageUpdate);  
            Session::flash('success', trans('messages.package_update_success'));
            return redirect(route('adminPackage'));
        }
    }

    /**
    * Delete Record
    * @param  $id = Id
    */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminPackage'));
        }

        $id = base64_decode($id);
        $result = Package::find($id);

        if (!empty($result)){
            $Package = Package::where('id', $id)->update(['is_deleted' =>1]);
            Session::flash('success', trans('messages.record_deleted_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

     /**
     * Change status for Record [active/block].
     * @param  $id = Id, $status = active/block 
     */
    public function changeStatus($id, $status) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminPackage'));
        }
        $id = base64_decode($id);

        $result = Package::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }
}

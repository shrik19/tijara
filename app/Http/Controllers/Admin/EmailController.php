<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Emails;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class EmailController extends Controller
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
        $data['pageTitle']              = trans('users.email_title');
        $data['current_module_name']    = trans('users.email_title');
        $data['module_name']            = trans('users.email_title');
        $data['module_url']             = route('adminEmail');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Email/index', $data);
    }

    /**
    * [getRecords for package list.This is a ajax function for dynamic datatables list]
    * @param  Request $request [sent filters if applied any]
    * @return [JSON]           [users list in json format]
    */
    public function getRecords(Request $request) {

        $PageDetails = Emails::select('emails.*')->where('emails.is_deleted','!=','1');

        if(!empty($request['search']['value']))
        {
            $field = ['emails.title','emails.subject','emails.created_at','emails.status'];
            $namefield = ['emails.title','emails.subject','emails.created_at','emails.status'];

            $search=($request['search']['value']);

            $PageDetails = $PageDetails->Where(function ($query) use($search, $field,$namefield) {
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

        if(isset($request['order'][0]))
        {
            $postedorder=$request['order'][0];
            if($postedorder['column']==0) $orderby='emails.title';
            if($postedorder['column']==1) $orderby='emails.subject';
            if($postedorder['column']==2) $orderby='emails.created_at';
            $orderorder=$postedorder['dir'];
            $PageDetails = $PageDetails->orderby($orderby, $orderorder);
        }

        $recordsTotal = $PageDetails->count();

        $recordDetails = $PageDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        $arr = [];

        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal)
            {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                $subject = (!empty($recordDetailsVal['subject'])) ? $recordDetailsVal['subject'] : '-';
                $dated =  (!empty($recordDetailsVal['created_at'])) ?  date('d M y', strtotime($recordDetailsVal['created_at'])) : '-';


                 if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminEmailChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminEmailChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminEmailEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';
                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminEmailDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                $arr[] = [$title, $subject, $dated, $status, $action];
            }
        }
        else {
            $arr[] = ['', '', trans('lang.datatables.sEmptyTable'), '', ''];
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
        $data['pageTitle']              = trans('users.add_email_btn');
        $data['current_module_name']    = trans('users.email_title');
        $data['module_name']            = trans('users.email_title');
        $data['module_url']             = route('adminEmail');
        return view('Admin/Email/create', $data);
    }

    /*function to save package details*/
    public function store(Request $request) {
      
        $rules = [
        'title'             => 'required',
        'subject'           => 'required',
        'contents'          => 'required',
        'subject_en'        => 'required',
        'contents_en'       => 'required',
        ];
        $messages = [
            'title.required'              => trans('errors.fill_in_title'),
            'subject.required'              => trans('errors.fill_in_subject'),
            'contents.required'        => trans('errors.fill_in_email_content'),
            'subject_en.required'              => trans('errors.fill_in_subject_en'),
            'contents_en.required'        => trans('errors.fill_in_email_content_en'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $created_at = date('Y-m-d H:i:s');
        $userId = Auth::guard('admin')->id();

        $arrPackageInsert = [
            'title'             => trim($request->input('title')),
            'subject'           => trim($request->input('subject')),
            'contents'          => trim($request->input('contents')),
            'subject_en'        => trim($request->input('subject_en')),
            'contents_en'       => trim($request->input('contents_en')),
            'status'            => trim($request->input('status')),
            'created_by'        => $userId,
            'updated_by'        => $userId,
            'created_at'        => $created_at,
            'updated_at'        => $created_at,
        ];

        $id = Emails::create($arrPackageInsert)->id;
        Session::flash('success', trans('messages.email_save_success'));
        return redirect(route('adminEmail'));
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
        $details = Emails::where('Id', $id)->first();

        if(empty($details)){
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data['pageTitle']              = trans('users.edit_email_title');
        $data['current_module_name']    = trans('users.edit_email_title');
        $data['module_name']            = trans('users.email_title');
        $data['module_url']             = route('adminEmail');
        $data['PageDetails']            = $details;

        return view('Admin/Email/edit', $data);
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
            'subject'           => 'required',
            'contents'          => 'required',
            'subject_en'        => 'required',
            'contents_en'       => 'required',
            ];
            $messages = [
                'title.required'              => trans('errors.fill_in_title'),
                'subject.required'              => trans('errors.fill_in_subject'),
                'contents.required'        => trans('errors.fill_in_email_content'),
                'subject_en.required'              => trans('errors.fill_in_subject_en'),
                'contents_en.required'        => trans('errors.fill_in_email_content_en'),
            ];

        $validator = validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        } else
        {
            $updated_at = date('Y-m-d H:i:s');
            $userId = Auth::guard('admin')->id();
            $arrPackageUpdate = [
                'title'              => trim($request->input('title')),
                'subject'           => trim($request->input('subject')),
                'contents'           => trim($request->input('contents')),
                'subject_en'           => trim($request->input('subject_en')),
                'contents_en'        => trim($request->input('contents_en')),
                'status'             => trim($request->input('status')),
                'updated_by'         => $userId,
                'updated_at'         => $updated_at,
            ];

            Emails::where('id', '=', $id)->update($arrPackageUpdate);
            Session::flash('success', trans('messages.email_update_success'));
            return redirect(route('adminEmail'));
        }
    }

    /**
    * Delete Record
    * @param  $id = Id
    */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminEmail'));
        }

        $id = base64_decode($id);
        $result = Emails::find($id);

        if (!empty($result)){
            $deleted_at = date('Y-m-d H:i:s');
            $userId = Auth::guard('admin')->id();
            $Emails = Emails::where('id', $id)->update(['is_deleted' => '1','deleted_by' => $userId, 'deleted_at' => $deleted_at]);
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
            return redirect(route('adminEmail'));
        }
        $id = base64_decode($id);

        $result = Emails::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }
}

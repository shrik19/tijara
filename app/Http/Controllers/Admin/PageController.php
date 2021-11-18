<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Page;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class PageController extends Controller
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
        $data['pageTitle']              = trans('users.cms_page_title');
        $data['current_module_name']    = trans('users.cms_page_title');
        $data['module_name']            = trans('users.cms_page_title');
        $data['module_url']             = route('adminPage');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Page/index', $data);
    }

    /**
    * [getRecords for package list.This is a ajax function for dynamic datatables list]
    * @param  Request $request [sent filters if applied any]
    * @return [JSON]           [users list in json format]
    */
    public function getRecords(Request $request) {

        $PageDetails = Page::select('pages.*')->where('pages.is_deleted','!=','1');

        if(!empty($request['search']['value']))
        {
            $field = ['pages.title','pages.slug','pages.created_at','pages.status'];
            $namefield = ['pages.title','pages.slug','pages.created_at','pages.status'];

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
            if($postedorder['column']==0) $orderby='pages.title';
            if($postedorder['column']==1) $orderby='pages.slug';
            if($postedorder['column']==2) $orderby='pages.created_at';
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
                $slug = (!empty($recordDetailsVal['slug'])) ? $recordDetailsVal['slug'] : '-';
                $dated =  (!empty($recordDetailsVal['created_at'])) ?  date('d M y', strtotime($recordDetailsVal['created_at'])) : '-';


                 if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminPageChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminPageChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminPageEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';
                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminPageDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                $arr[] = [$title, $slug, $dated, $status, $action];
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
        $data['pageTitle']              = trans('users.add_page_btn');
        $data['current_module_name']    = trans('users.cms_page_title');
        $data['module_name']            = trans('users.cms_page_title');
        $data['module_url']             = route('adminPage');
        return view('Admin/Page/create', $data);
    }

    /*function to save package details*/
    public function store(Request $request) {
      
        $rules = [
        'title'             => 'required',
        'description'       => 'required',
        'title_en'             => 'required',
        'description_en'       => 'required',
        'display_in_section' => 'required',
        ];
        $messages = [
            'title.required'              => trans('errors.fill_in_title'),
            'description.required'        => trans('errors.fill_in_page_content'),
            'title_en.required'              => trans('errors.fill_in_title_en'),
            'description_en.required'        => trans('errors.fill_in_page_content_en'),
            'display_in_section.required' => trans('lang.required_field_error'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $slug = $this->slugify(trim($request->input('title')));
        $slug_en = $this->slugify(trim($request->input('title_en')));
        $created_at = date('Y-m-d H:i:s');
        $userId = Auth::guard('admin')->id();

        $arrPackageInsert = [
            'title'             => trim($request->input('title')),
            'slug'              => $slug,
            'contents'          => trim($request->input('description')),
            'title_en'          => trim($request->input('title_en')),
            'slug_en'           => $slug_en,
            'contents_en'       => trim($request->input('description_en')),
            'display_in_section'       => trim($request->input('display_in_section')),
            'status'            => trim($request->input('status')),
            'created_by'        => $userId,
            'updated_by'        => $userId,
            'created_at'        => $created_at,
            'updated_at'        => $created_at,
        ];

        $id = Page::create($arrPackageInsert)->id;
        Session::flash('success', trans('messages.page_save_success'));
        return redirect(route('adminPage'));
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
        $details = Page::where('Id', $id)->first();

        if(empty($details)){
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data['pageTitle']              = trans('users.edit_page_title');
        $data['current_module_name']    = trans('users.edit_page_title');
        $data['module_name']            = trans('users.cms_page_title');
        $data['module_url']             = route('adminPage');
        $data['PageDetails']            = $details;

        return view('Admin/Page/edit', $data);
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
        'description'       => 'required',
        'title_en'             => 'required',
        'description_en'       => 'required',
         'display_in_section' => 'required',
        ];
        $messages = [
            'title.required'              => trans('errors.fill_in_title'),
            'description.required'        => trans('errors.fill_in_page_content'),
            'title_en.required'              => trans('errors.fill_in_title_en'),
            'description_en.required'        => trans('errors.fill_in_page_content_en'),
            'display_in_section.required' => trans('lang.required_field_error'),
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
                'contents'           => trim($request->input('description')),
                'title_en'           => trim($request->input('title_en')),
                'contents_en'        => trim($request->input('description_en')),
                'display_in_section'       => trim($request->input('display_in_section')),
                'status'             => trim($request->input('status')),
                'updated_by'         => $userId,
                'updated_at'         => $updated_at,
            ];

            Page::where('id', '=', $id)->update($arrPackageUpdate);
            Session::flash('success', trans('messages.page_update_success'));
            return redirect(route('adminPage'));
        }
    }

    /**
    * Delete Record
    * @param  $id = Id
    */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminPage'));
        }

        $id = base64_decode($id);
        $result = Page::find($id);

        if (!empty($result)){
            $deleted_at = date('Y-m-d H:i:s');
            $userId = Auth::guard('admin')->id();
            $Page = Page::where('id', $id)->update(['is_deleted' => '1','deleted_by' => $userId, 'deleted_at' => $deleted_at]);
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
            return redirect(route('adminPage'));
        }
        $id = base64_decode($id);

        $result = Page::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

    public function slugify($text, string $divider = '-')
    {
      // replace non letter or digits by divider
      $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      // trim
      $text = trim($text, $divider);

      // remove duplicate divider
      $text = preg_replace('~-+~', $divider, $text);

      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
    }
}

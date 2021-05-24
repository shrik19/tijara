<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ServiceCategories;
use App\Models\ServiceSubcategories;
/*Uses*/
use Session;
use Validator;

class ServiceCatController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for service category.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.service_category_title');
        $data['current_module_name']    = trans('users.service_category_title');
        $data['module_name']            = trans('users.service_category_title');
        $data['module_url']             = route('adminServiceCat');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/ServiceCategory/index', $data);
    }
    
    /**
     * [getRecords for service category list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [service category list in json format]
     */
    public function getRecords(Request $request) {
        
    	$categoryDetails = ServiceCategories::select('id','category_name','sequence_no','description','status')->with('getSubCat');
    	$recordsTotal = $categoryDetails->count();  
    
    	if(!empty($request['search']['value']))
        {
            $categoryDetails = $categoryDetails->where('ServiceCategories.category_name', 'LIKE', '%'.$request['search']['value'].'%');
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $categoryDetails = $categoryDetails->where('ServiceCategories.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $categoryDetails = $categoryDetails->where('ServiceCategories.status', '=', $request['status']);
        }
        
        if (!empty($request['order'])) {
            $column = 'ServiceCategories.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'ServiceCategories.id',
                            '1' =>  'ServiceCategories.category_name',
                            '2' =>  'ServiceCategories.sequence_no',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];
               $order = $request['order'][0]['dir']; 
            }
            $categoryDetails = $categoryDetails->orderBy($column,$order);
        }

        $recordDetails = $categoryDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
			{
			    $id = $recordDetailsVal['id'];
			    $Category_Name = (!empty($recordDetailsVal['category_name'])) ? $recordDetailsVal['category_name'] : '-';
			    $sequence_no = (!empty($recordDetailsVal['sequence_no'])) ? $recordDetailsVal['sequence_no'] : '-';
            	$subCategoryCount  = (!empty($recordDetailsVal['get_sub_cat'])) ? '<a style="margin-left:42px;" href="'.route('adminServiceSubcat', base64_encode($id)).'">'.count($recordDetailsVal['get_sub_cat']).'</a>' : '<span style="margin-left:42px;">0</span>';
               
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceCatChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceCatChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }
                
                $AddSubCategory = '<a style="margin-left:38px;" href="javascript:void(0);" category_id="'.$id.'" category_name="'.$Category_Name.'" Subcategory_Name="" id="0" class="btn btn-icon btn-warning savesubcategory" title="'.__('users.add_subcategory_title').'" id="'.$id.'"><i class="fa fa-plus"></i> </a>';
                
                $action = '<a href="'.route('adminServiceCatEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminServiceCatDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['',$Category_Name, $sequence_no,$subCategoryCount, $status, $AddSubCategory, $action];
			}
        }
        else {
            $arr[] = ['','','',trans('lang.datatables.sEmptyTable'), '', '', ''];
        }
    	
    	
    	$json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];
    
        return json_encode($json_arr);
    }
    
    /*function to open service category form*/
    public function create()
    {
        $data['pageTitle']              = trans('users.add_Service_category_title');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.service_category_title');
        $data['module_url']             = route('adminServiceCat');
        return view('Admin/ServiceCategory/create', $data);
    }
    
     /**
     * store service category details
     */
    public function store(Request $request) {
	
        $rules = [
            'name'          => 'required|regex:/^[\pL\s\-]+$/u|unique:ServiceCategories,category_name',
            'sequence_no'   => 'required',
        ];

        $messages = [
            'name.required' => trans('errors.category_name_req'),
            'name.regex'    => trans('errors.input_alphabet_err'), 
            'sequence_no'   => trans('errors.sequence_number_err'),
            'name.unique'   => trans('errors.enter_diff_cat_err'),
        ];
        
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrInsertServiceCategories = [
                                        'category_name' => trim($request->input('name')),
                                        'description' =>trim($request->input('description')),
                                        'sequence_no'   => trim($request->input('sequence_no')),
                                    ];
        ServiceCategories::create($arrInsertServiceCategories);                   
        
        Session::flash('success', trans('messages.category_save_success'));
        return redirect(route('adminServiceCat'));
    }
     /**
     * Edit service category details
     * @param  $id = service category Id
     */
    public function edit($id) {
       if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        $data = $details = [];
         
        $data['id'] = $id;
        $id = base64_decode($id);
		
        $details = ServiceCategories::where('id', $id)->first()->toArray();
        
		$data['pageTitle']              = trans('users.edit_Service_category_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.service_category_title');
        $data['module_url']             = route('adminCategory');
		$data['categoryDetails']          = $details;       
        return view('Admin/ServiceCategory/edit', $data);
    }
    
    /**
     * Update service category details
     * @param  $id = service category Id
     */
    public function update(Request $request, $id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);
                 
        $rules = [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|unique:ServiceCategories,category_name,'.$id,
            'sequence_no'   => 'required',
        ];
        $messages = [
            'name.required' => trans('errors.category_name_req'),
            'name.regex'    => trans('errors.input_alphabet_err'), 
            'sequence_no'   => trans('errors.sequence_number_err'),
            'name.unique'   => trans('errors.enter_diff_cat_err'),
        ];
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrUpdateCategory = ['category_name' => trim($request->input('name')),
                             'description' =>trim($request->input('description')),
                             'sequence_no'   => trim($request->input('sequence_no')),];
                            
        ServiceCategories::where('id', '=', $id)->update($arrUpdateCategory);  
        
        Session::flash('success', trans('messages.category_update_success'));
        return redirect(route('adminServiceCat'));
    }
    
      /**
     * Delete Record
     * @param  $id = service category Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminCategory'));
        }
        $id = base64_decode($id);
        
        $ServiceCategories = ServiceCategories::find($id);
        if (!empty($ServiceCategories)) 
		{
            if ($ServiceCategories->delete()) 
			{
                $delSubCategories = serviceSubcategories::where('category_id',$id)->delete();
                Session::flash('success', trans('messages.record_deleted_success'));
                return redirect()->back();
            } else {
                Session::flash('error', trans('errors.something_wrong_err'));
                return redirect()->back();
            }
        } 
        else {
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
            return redirect(route('adminCategory'));
        }
        $id = base64_decode($id);

        $result = ServiceCategories::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }
}
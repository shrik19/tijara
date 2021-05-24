<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Categories;
use App\Models\Subcategories;
use App\Models\ProductCategory;

/*Uses*/
use Session;
use Validator;

class SubcategoryController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for Vendors.
     * @return [array] [record array]
     */
    public function index(Request $request) {
        $data = [];
        $data['pageTitle']              = trans('users.subcategory_title');
        $data['current_module_name']    = trans('users.subcategory_title');
        $data['current_id']             = $request->id;
        $data['module_name']            = trans('users.subcategory_title');
        $data['module_url']             = route('adminSubcategory',$request->id);
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/Subcategory/index', $data);
    }
    
    /**
     * [getRecords for sub category list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {
        $status='';
		$category_id=base64_decode($request->id);
	
		$categoryDetails = Categories::select('categories.category_name')->where('id','=',$category_id)->get()->first();
	
		$SubcategoryDetails = Subcategories::select('subcategories.*')->where('category_id','=',$category_id);
		
		
		
		if(!empty($request['search']['value']))
        {
            $SubcategoryDetails = $SubcategoryDetails->where('subcategories.subcategory_name', 'LIKE', '%'.$request['search']['value'].'%');
           
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $SubcategoryDetails = $SubcategoryDetails->where('subcategories.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $SubcategoryDetails = $SubcategoryDetails->where('subcategories.status', '=', $request['status']);
        }

        if (!empty($request['order'])) {
            $column = 'subcategories.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'subcategories.id',
                            '1' =>  'subcategories.category_id',
                            '2' =>  'subcategories.subcategory_name',
                            '3' =>  'subcategories.sequence_no',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];
               $order = $request['order'][0]['dir']; 
            }
            $SubcategoryDetails = $SubcategoryDetails->orderBy($column,$order);
        }

    	
        $recordsTotal = $SubcategoryDetails->count();
        $recordDetails = $SubcategoryDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
			{
			    $action = $status = '-';
			    $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
			    $Category_Name = (!empty($categoryDetails->category_name)) ? $categoryDetails->category_name : '-';
			   
			    $Subcategory = (!empty($recordDetailsVal['subcategory_name'])) ? $recordDetailsVal['subcategory_name'] : '-';
                $sequence_no = (!empty($recordDetailsVal['sequence_no'])) ? $recordDetailsVal['sequence_no'] : '-';
                
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSubcategoryChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSubcategoryChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }
                
                $action = '<a href="javascript:void(0)"  category_name="'.$Category_Name.'" Subcategory_Name="'.$Subcategory.'"  sequence_no="'.$sequence_no.'"  id="'.$recordDetailsVal['id'].'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success savesubcategory"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminSubcategoryDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['',$Category_Name, $Subcategory, $sequence_no,$status, $action];
			}
        }
        else {
            $arr[] = ['','', '', '', '', trans('lang.datatables.sEmptyTable'), '', '', '', ''];
        }
    	
    	
    	$json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];
    
        return json_encode($json_arr);
    }
    
     /**
     * save sub category deatils details
     */
    public function subCategoryStore(Request $request) {

        $id=$request->id;
        $rules = [];
        if(!empty($id)){
            $rules = [ 
                'subcategory_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:subcategories,subcategory_name,'.$id,
                'category_name'    => 'required',
                'sequence_no'      => 'required',
            ];
        }else{
            $rules = [
                'subcategory_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:subcategories,subcategory_name',
                'category_name'    => 'required',
                'sequence_no'      => 'required',
            ];
        }
        $messages = [
            'category_name.required'         => trans('errors.category_name_req'),
            'subcategory_name.required'      => trans('errors.subcategory_name_req'),
            'subcategory_name.regex'         => trans('errors.input_alphabet_err'),
            'sequence_no.required'                    => trans('errors.sequence_number_err'),
            'subcategory_name.unique'        => trans('errors.unique_subcategory_name'),
        ];
     
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }else{
            
            if(!empty($id)){
                
                 $arrUpdate = ['subcategory_name' => trim($request->input('subcategory_name')),
                               'sequence_no'      => trim($request->input('sequence_no')),
                            ];
                Subcategories::where('id', '=', $id)->update($arrUpdate);
                Session::flash('success', trans('messages.subcat_update_success'));
            }else{
               
                 $arrInsertSubcategory = [
                    'subcategory_name' => trim($request->input('subcategory_name')),
                    'category_id' => trim($request->input('hid_subCategory')),
                    'sequence_no' => trim($request->input('sequence_no')),
                ];
            
                Subcategories::create($arrInsertSubcategory); 
                Session::flash('success', trans('messages.subcat_save_success'));
            }
                          
        }
        
        return redirect()->back();
    }
    
    
      /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminCategory'));
        }
        $id = base64_decode($id);
        
        $categories = Subcategories::find($id);
        if (!empty($categories)) 
		{
            if ($categories->delete()) 
			{
			    ProductCategory::where('subcategory_id', $id)->update(['category_id' => 0,'subcategory_id' => 0]);
                Session::flash('success',  trans('messages.record_deleted_success'));
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
            return redirect(route('adminSubcategory'));
        }
        $id = base64_decode($id);

        $result = Subcategories::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }
}
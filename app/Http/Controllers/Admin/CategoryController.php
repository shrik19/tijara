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

class CategoryController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for Category.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.product_category');
        $data['current_module_name']    = trans('users.product_category');
        $data['module_name']            = trans('users.product_category');
        $data['module_url']             = route('adminCategory');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/Category/index', $data);
    }
    
    /**
     * [getRecords for category list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {

    	$categoryDetails = Categories::select('id','category_name','sequence_no','description','status')->with('getSubCat');
    	$recordsTotal = $categoryDetails->count();
    
    	if(!empty($request['search']['value']))
        {
            $categoryDetails = $categoryDetails->where('categories.category_name', 'LIKE', '%'.$request['search']['value'].'%');
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $categoryDetails = $categoryDetails->where('categories.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $categoryDetails = $categoryDetails->where('categories.status', '=', $request['status']);
        }
        
        if (!empty($request['order'])) {
            $column = 'categories.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'categories.id',
                            '1' =>  'categories.category_name',
                            '2' =>  'categories.sequence_no',
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

            	$subCategoryCount  = (!empty($recordDetailsVal['get_sub_cat'])) ? '<a style="margin-left:42px;" href="'.route('adminSubcategory', base64_encode($id)).'">'.count($recordDetailsVal['get_sub_cat']).'</a>' : '<span style="margin-left:42px;">0</span>';
               
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminCategoryChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminCategoryChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }
                
                $AddSubCategory = '<a style="margin-left:38px;" href="javascript:void(0);" category_id="'.$id.'" category_name="'.$Category_Name.'" Subcategory_Name="" id="0" class="btn btn-icon btn-warning savesubcategory" title="'.__('users.add_subcategory_title').'" id="'.$id.'"><i class="fa fa-plus"></i> </a>';
                
                $action = '<a href="'.route('adminCategoryEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminCategoryDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['',$Category_Name, $sequence_no, $subCategoryCount, $status, $AddSubCategory, $action];
			}
        }
        else {
            $arr[] = ['','',trans('lang.datatables.sEmptyTable'), '', '', ''];
        }
    	
    	$json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];
    
        return json_encode($json_arr);
    }
    
    /*function to open category open form*/
    public function create()
    {
        $data['pageTitle']              = trans('users.add_product_category_title');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.product_category');
        $data['module_url']             = route('adminCategory');
        return view('Admin/Category/create', $data);
    }
    
     /**
     * Save Category details
     */
    public function store(Request $request) {		
          
        $rules = [
            'name'    => 'required|regex:/^[\pL0-9\s\-]+$/u|unique:categories,category_name',
            'name'    => 'required|unique:categories,category_name',
            'sequence_no'   => 'required',
            'category_slug' => 'required|regex:/^[0-9a-z-]+$/u|unique:categories,category_slug',
        ];

        $messages = [
            'name.required'           => trans('errors.category_name_req'),
            'name.regex'              => trans('errors.input_letter_no_err'),
            'name.unique'             => trans('errors.enter_diff_cat_err'),
            'sequence_no.required'    => trans('errors.sequence_number_err'),
            'category_slug.required'  => trans('errors.category_slug_req'),
            'category_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'category_slug.unique'    => trans('messages.category_slug_already_taken'),
        ];
        
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrInsertCategories = [
                                'category_name' => trim($request->input('name')),
                                'description' =>trim($request->input('description')),
                                'sequence_no' => trim($request->input('sequence_no')),
                                'category_slug' => trim($request->input('category_slug')),
                               ];

        Categories::create($arrInsertCategories);                   
        
        Session::flash('success', trans('messages.category_save_success'));
        return redirect(route('adminCategory'));
    }

     /**
     * Edit record details
     * @param  $id = category Id
     */
    public function edit($id) {
       if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $data = $details = [];
        $data['id'] = $id;
        $id = base64_decode($id);
        $details = Categories::where('id', $id)->first()->toArray();

		$data['pageTitle']              = trans('users.edit_product_category_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.product_category');
        $data['module_url']             = route('adminCategory');
		$data['categoryDetails']          = $details; 
        return view('Admin/Category/edit', $data);
    }
    
    /**
     * Update Category details
     * @param  $id = category Id
     */
    public function update(Request $request, $id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);
        $rules = [
            'name' => 'required|regex:/^[\pL0-9\s\-]+$/u|unique:categories,category_name,'.$id,
            'sequence_no'   => 'required',
            'category_slug' => 'required|regex:/^[0-9a-z-]+$/u|unique:categories,category_slug,'.$id,
        ];
       
        $messages = [
            'name.required'         => trans('errors.category_name_req'),
            'name.regex'            => trans('errors.input_alphabet_err'),
            'sequence_no.required'           => trans('errors.sequence_number_err'),
            'name.unique'           => trans('errors.enter_diff_cat_err'),
            'category_slug.required'  => trans('errors.category_slug_req'),
            'category_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'category_slug.unique'    => trans('messages.category_slug_already_taken'),
        ];
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrUpdateCategory = ['category_name' => trim($request->input('name')),
                              'description'   => trim($request->input('description')),
                              'sequence_no'   => trim($request->input('sequence_no')),
                              'category_slug' => trim($request->input('category_slug')),
                            ];
                            
        Categories::where('id', '=', $id)->update($arrUpdateCategory);  
        
        Session::flash('success', trans('messages.category_update_success'));
        return redirect(route('adminCategory'));
    }
    
      /**
     * Delete Record
     * @param  $id = category Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminCategory'));
        }
        $id = base64_decode($id);
        
        $categories = Categories::find($id);
        if (!empty($categories)) 
		{
             $delSubCategories = Subcategories::where('category_id',$id)->delete();
   
            if ($categories->delete()) 
			{
			    ProductCategory::where('category_id', $id)->update(['category_id' => 0,'subcategory_id' => 0]);
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

        $result = Categories::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

    /* function to check for unique slug name
    * @param:storename
    */
    function checkUniqueSlugName(Request $request){
        $slug_name = $request->slug_name;
        $id = base64_decode($request->id);
     
        if(!empty($id)){
            $data =  Categories::where('category_slug', $slug_name)->where('id','!=',$id)->get();
        } else{
            $data =  Categories::where('category_slug', $slug_name)->get();
        }
       $messages = '';
        if(!empty($data[0]['category_slug'])){
            $messages =trans('messages.category_slug_already_taken');
             return $messages;
        }
       
    }
}
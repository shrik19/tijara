<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AnnonserCategories;
use App\Models\AnnonserSubcategories;
/*Uses*/
use Session;
use Validator;

use App\CommonLibrary;

class AnnonserCatController extends Controller
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
        $max_seq_no ='';
        $max_seq_no = AnnonserCategories::max('sequence_no');
        $data['pageTitle']              = trans('users.annonser_category_title');
        $data['current_module_name']    = trans('users.annonser_category_title');
        $data['module_name']            = trans('users.annonser_category_title');
        $data['module_url']             = route('adminAnnonserCat');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        $data['max_seq_no']            = $max_seq_no + 1;
        
        return view('Admin/AnnonserCategory/index', $data);
    }
    
    /**
     * [getRecords for service category list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [service category list in json format]
     */
    public function getRecords(Request $request) {
        
    	$categoryDetails = AnnonserCategories::select('id','category_name','sequence_no','description','status')->with('getSubCat');
    	$recordsTotal = $categoryDetails->count();  
    
    	if(!empty($request['search']['value']))
        {
            $categoryDetails = $categoryDetails->where('annonsercategories.category_name', 'LIKE', '%'.$request['search']['value'].'%');
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $categoryDetails = $categoryDetails->where('annonsercategories.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $categoryDetails = $categoryDetails->where('annonsercategories.status', '=', $request['status']);
        }
        
        if (!empty($request['order'])) {
            $column = 'annonsercategories.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'annonsercategories.id',
                            '1' =>  'annonsercategories.category_name',
                            '2' =>  'annonsercategories.sequence_no',
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
            	$subCategoryCount  = (!empty($recordDetailsVal['get_sub_cat'])) ? '<a style="margin-left:42px;" href="'.route('adminAnnonserSubcat', base64_encode($id)).'">'.count($recordDetailsVal['get_sub_cat']).'</a>' : '<span style="margin-left:42px;">0</span>';
               
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminAnnonserCatChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminAnnonserCatChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $max_seq_no ='';
                $max_seq_no = AnnonserSubcategories::where("category_id",$id)->max('sequence_no');
                $seq_no = $max_seq_no + 1;
                
                $AddSubCategory = '<a style="margin-left:38px;" href="javascript:void(0);" category_id="'.$id.'" sequence_no="'.$seq_no.'" category_name="'.$Category_Name.'" Subcategory_Name="" id="0" class="btn btn-icon btn-warning savesubcategory" title="'.__('users.add_subcategory_title').'" id="'.$id.'"><i class="fa fa-plus"></i> </a>';
                
                $action = '<a href="'.route('adminAnnonserCatEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminAnnonserCatDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
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
        $max_seq_no ='';
        $max_seq_no = AnnonserCategories::max('sequence_no');
        $data['pageTitle']              = trans('users.add_annonser_category_title');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.annonser_category_title');
        $data['module_url']             = route('adminAnnonserCat');
        $data['max_seq_no']            = $max_seq_no + 1;

        return view('Admin/AnnonserCategory/create', $data);
    }
    
     /**
     * store service category details
     */
    public function store(Request $request) {
	    
         $category_slug = $request->input('category_slug');
         $slug =   CommonLibrary::php_cleanAccents($category_slug);

        $rules = [
            'name'          => 'required|unique:annonsercategories,category_name',
            'sequence_no'   => 'required',
            'category_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:annonsercategories,category_slug',
        ];

        $messages = [
            'name.required' => trans('errors.category_name_req'),
            //'name.regex'    => trans('errors.input_alphabet_err'), 
            'sequence_no.required'    => trans('errors.sequence_number_err'),
            'name.unique'             => trans('errors.enter_diff_cat_err'),
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
        
        $arrInsertannonsercategories = [
            'category_name' => trim($request->input('name')),
            'description'   =>trim($request->input('description')),
            'sequence_no'   => trim($request->input('sequence_no')),
            'category_slug' => trim(strtolower($slug)),
        ];
        AnnonserCategories::create($arrInsertannonsercategories);                   
        
        Session::flash('success', trans('messages.category_save_success'));
        return redirect(route('adminAnnonserCat'));
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
		
        $details = AnnonserCategories::where('id', $id)->first()->toArray();
        
		$data['pageTitle']              = trans('users.edit_annonser_category_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.annonser_category_title');
        $data['module_url']             = route('adminAnnonserCat');
		$data['categoryDetails']          = $details;       
        return view('Admin/AnnonserCategory/edit', $data);
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
        
        $category_slug = $request->input('category_slug');
        $slug =   CommonLibrary::php_cleanAccents($category_slug);

        $rules = [
            'name' => 'required|unique:annonsercategories,category_name,'.$id,
            'sequence_no'   => 'required',
            'category_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:annonsercategories,category_slug,'.$id,
        ];
        $messages = [
            'name.required' => trans('errors.category_name_req'),
            //'name.regex'    => trans('errors.input_alphabet_err'), 
            'sequence_no.required'   => trans('errors.sequence_number_err'),
            'name.unique'   => trans('errors.enter_diff_cat_err'),
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
        
        $arrUpdateCategory = [
            'category_name' => trim($request->input('name')),
            'description' =>trim($request->input('description')),
            'sequence_no'   => trim($request->input('sequence_no')),
            'category_slug' => trim(strtolower($slug)),
        ];
                            
        AnnonserCategories::where('id', '=', $id)->update($arrUpdateCategory);  
        
        Session::flash('success', trans('messages.category_update_success'));
        return redirect(route('adminAnnonserCat'));
    }
    
      /**
     * Delete Record
     * @param  $id = service category Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminAnnonserCat'));
        }
        $id = base64_decode($id);
        
        $annonsercategories = AnnonserCategories::find($id);
        if (!empty($annonsercategories)) 
		{
            if ($annonsercategories->delete()) 
			{
                $delSubCategories = AnnonserSubcategories::where('category_id',$id)->delete();
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
            return redirect(route('adminAnnonserCat'));
        }
        $id = base64_decode($id);

        $result = AnnonserCategories::where('id', $id)->update(['status' => $status]);
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
        // Clean up multiple dashes or whitespaces
        $slug_trim = trim(preg_replace('/\s+/', ' ', $slug_name));
        // Convert whitespaces and underscore to dash
        $slug_hypen = preg_replace("/[\s_]/", "-", $slug_trim); 
        $slug_p = str_replace("-p-", '', $slug_hypen); 
        $slug_s = str_replace("-s-", '', $slug_p);       
        $slug =   CommonLibrary::php_cleanAccents($slug_s);

        if(!empty($id)){
            $data =  AnnonserCategories::where('category_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  AnnonserCategories::where('category_slug', $slug)->get();
        }

        $unique_slug =1;
        if(!empty($data[0]['category_slug'])){
           do{
                $slug = $slug."-".$unique_slug;
                if(!empty($id)){
                    $data =  AnnonserCategories::where('category_slug', $slug)->where('id','!=',$id)->get();
                } else{
                    $data =  AnnonserCategories::where('category_slug', $slug)->get();
                }
            }while(!empty($data[0]['category_slug']));
             return $slug;
        }else{
            return $slug;
        }
       
    }
}
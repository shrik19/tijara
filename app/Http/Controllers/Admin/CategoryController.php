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

    /*
 Function to remove accent from string
 @param:string
*/
    public function php_cleanAccents($string){
       
        if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = strtr($string, $chars);
        return $string;  
    }
    
     /**
     * Save Category details
     */
    public function store(Request $request) {		
        $category_slug = $request->input('category_slug');
         $is_accents = preg_match('/^[\p{L}-]*$/u', $category_slug);
         if($is_accents ==1){
            $slug =   $this->php_cleanAccents($category_slug);
         }else{
            $slug = $request->input('category_slug');
         }
        $rules = [
            'name'    => 'required|regex:/^[\pL0-9\s\-]+$/u|unique:categories,category_name',
            'name'    => 'required|unique:categories,category_name',
            'sequence_no'   => 'required',
            'category_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:categories,category_slug',
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
                                'category_slug' => trim(strtolower($slug)),
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

        $is_accents = preg_match('/^[\p{L}-]*$/u', $slug_name);
        if($is_accents ==1){
            $slug =   $this->php_cleanAccents($slug_name);
        }else{
            $slug = $slug_name;
        }

        if(!empty($id)){
            $data =  Categories::where('category_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  Categories::where('category_slug', $slug)->get();
        }
       $messages = '';
        if(count($data) != 0){
            if(!empty($data[0]['category_slug'])){
                $messages =trans('messages.category_slug_already_taken');
                 return $messages;
            }
        }
       
    }
}
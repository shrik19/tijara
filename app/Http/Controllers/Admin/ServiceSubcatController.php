<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ServiceCategories;
use App\Models\serviceSubcategories;

/*Uses*/
use Session;
use Validator;
use DB;

class ServiceSubcatController extends Controller
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
        $data['pageTitle']              = 'Service Subcategory';
        $data['current_module_name']    = 'Service Subcategory';
        $data['current_id']             = $request->id;
        $data['module_name']            = 'Service Subcategory';
        $data['module_url']             = route('adminServiceSubcat',$request->id);
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/ServiceSubcategory/index', $data);
    }
    
    /**
     * [getRecords for sub category list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {
        $status='';
		$category_id=base64_decode($request->id);
	
		$categoryDetails = ServiceCategories::select('ServiceCategories.category_name')->where('id','=',$category_id)->get()->first();
	
		$SubcategoryDetails = serviceSubcategories::select('serviceSubcategories.*')->where('category_id','=',$category_id);
		
		
		
		if(!empty($request['search']['value']))
        {
            $SubcategoryDetails = $SubcategoryDetails->where('serviceSubcategories.subcategory_name', 'LIKE', '%'.$request['search']['value'].'%');
           
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $SubcategoryDetails = $SubcategoryDetails->where('serviceSubcategories.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $SubcategoryDetails = $SubcategoryDetails->where('serviceSubcategories.status', '=', $request['status']);
        }

        if (!empty($request['order'])) {
            $column = 'serviceSubcategories.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'serviceSubcategories.id',
                            '1' =>  'serviceSubcategories.category_id',
                            '2' =>  'serviceSubcategories.subcategory_name',
                            '3' =>  'serviceSubcategories.sequence_no',
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
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceSubcatChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceSubcatChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';
                }
                
               /*  $action = '<a href="javascript:void(0)"  category_name="'.$Category_Name.'" Subcategory_Name="'.$Subcategory.'" id="'.$recordDetailsVal['id'].'" title="Edit" class="btn btn-icon btn-success savesubcategory"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';
*/

                $action = '<a href="'.route('adminServiceSubCatEdit', base64_encode($id)).'" title="Edit" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminServiceSubcatDelete', base64_encode($id)).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['',$Category_Name, $Subcategory, $sequence_no,$status, $action];
			}
        }
        else {
            $arr[] = ['','', '', '', '', 'No Records Found', '', '', '', ''];
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

        $rules = [
            'category_name'    => 'required',
            'subcategory_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:serviceSubcategories,subcategory_name',
            'sequence_no'      => 'required',
        ];
        $messages = [
            'category_name.required'         => 'Please fill in Category Name',
            'subcategory_name.required'      => 'Please fill in Sub Category Name',
            'subcategory_name.regex'         => 'Please input alphabet characters only',
            'sequence_no'                    => 'Please fill in Sequence Number',
            'subcategory_name.unique'        => 'Please enter different SubCategory, its already taken.',
        ];
     
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }else{
            
            /*if(!empty($id)){
                
                 $arrUpdate = ['subcategory_name' => trim($request->input('subcategory_name')),
                                'sequence_no' => trim($request->input('sequence_no')),
                             ];
                serviceSubcategories::where('id', '=', $id)->update($arrUpdate);
                Session::flash('success', 'SubCategory Updated successfully!');
            }else{*/
               
                 $arrInsertSubcategory = [
                    'subcategory_name' => trim($request->input('subcategory_name')),
                    'category_id' => trim($request->input('hid_subCategory')),
                    'sequence_no' => trim($request->input('sequence_no')),
                ];
            
                serviceSubcategories::create($arrInsertSubcategory); 
                Session::flash('success', 'SubCategory Inserted successfully!');
           // }
                          
        }
        
        return redirect()->back();
    }
    

    /**
     * Edit record details
     * @param  $id = User Id
     */
    public function edit($id) {
       if(empty($id)) {
            Session::flash('error', 'Something went wrong. Refresh your page.');
            return redirect()->back();
        }
        $data = $details = [];
         
        $data['id'] = $id;
        $id = base64_decode($id);
        
        $details = serviceSubcategories::where('serviceSubcategories.id', $id)
        ->Join('ServiceCategories', 'serviceSubcategories.category_id', '=', 'ServiceCategories.id')
        ->select('serviceSubcategories.*','ServiceCategories.id','ServiceCategories.category_name')->first();
     
        $data['pageTitle']              = 'Edit Service SubCategory';
        $data['current_module_name']    = 'Edit';
        $data['module_name']            = 'Service SubCategory';
        $data['module_url']             = route('adminServiceSubcat',$details['id']);
        $data['categoryDetails']        = $details;
      
               
        return view('Admin/ServiceSubcategory/edit', $data);
    }
    

    /**
     * Update vendor details
     * @param  $id = Vendor Id
     */
    public function update(Request $request, $id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Refresh your page.');
            return redirect()->back();
        }
        
        $id = base64_decode($id);
 
        $rules = [
            'subcategory_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:serviceSubcategories,subcategory_name,'.$id,
            'sequence_no'      => 'required',
        ];
        $messages = [
            'subcategory_name.required' => 'Please fill in Category Name',
            'subcategory_name.regex'    => 'Please input alphabet characters only',
            'sequence_no'               => 'Please fill in Sequence Number',
            'subcategory_name.unique'   => 'Please enter different SubCategory, its already taken.',
        ];
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrUpdateCategory = ['subcategory_name' => trim($request->input('subcategory_name')),
                              'category_id' => trim($request->input('hid_subCategory')),
                              'sequence_no' => trim($request->input('sequence_no')),
                            ];
                            
        serviceSubcategories::where('id', '=', $id)->update($arrUpdateCategory);  
        
        Session::flash('success', 'Category details updated successfully!');
        return redirect(route('adminServiceCat'));
    }
    

      /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('adminCategory'));
        }
        $id = base64_decode($id);
        
        $categories = serviceSubcategories::find($id);
        if (!empty($categories)) 
		{
            if ($categories->delete()) 
			{
                Session::flash('success', 'Record deleted successfully!');
                return redirect()->back();
            } else {
                Session::flash('error', 'Oops! Something went wrong!');
                return redirect()->back();
            }
        } 
        else {
            Session::flash('error', 'Oops! Something went wrong!');
            return redirect()->back();
        }
    }
    /**
     * Change status for Record [active/block].
     * @param  $id = Id, $status = active/block 
     */
    public function changeStatus($id, $status) {
        if(empty($id)) {
			
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('adminSubcategory'));
        }
        $id = base64_decode($id);

        $result = serviceSubcategories::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', 'Status updated successfully!');
            return redirect()->back();
        } else {
            Session::flash('error', 'Oops! Something went wrong!');
            return redirect()->back();
        }
    }
}
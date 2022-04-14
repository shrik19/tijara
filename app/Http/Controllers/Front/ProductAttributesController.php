<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Attributes;
use App\Models\ AttributesValues;
/*Uses*/
use Auth;
use Session;
use Validator;
use DB;
use App\Models\UserMain;

class ProductAttributesController extends Controller
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
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('lang.product_attribute_title');
        $data['current_module_name']    = trans('lang.product_attribute_title');
        $data['module_name']            = trans('lang.product_attribute_title');
        $data['module_url']             = route('frontAttributeGetRecords');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        $data['subscribedError']        =   '';
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) {
                $data['subscribedError']   =   trans('messages.subscribe_package_to_manage_prod_attri');
            }
        }
        
        return view('Front/ProductAttributes/index', $data);
    }
    
    /**
     * [getRecords for user list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {

    	//$attributeDetails = Attributes::select('id','name')->where('user_id',Auth::guard('user')->id());
        $attributeDetails = Attributes::select('id','name');
      
    	$recordsTotal = $attributeDetails->count();
    	
    	if(!empty($request['search']['value']))
        {
            $attributeDetails = $attributeDetails->where('name', 'LIKE', '%'.$request['search']['value'].'%');
        }
        
        if (!empty($request['order'])) {
            $column = 'name';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'id',
                            '1' =>  'name',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];

               $order = $request['order'][0]['dir']; 
            }
            $attributeDetails = $attributeDetails->orderBy($column,$order);
        }

        $recordDetails = $attributeDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
			{
			    $id = $recordDetailsVal['id'];
			    $name = (!empty($recordDetailsVal['name'])) ? $recordDetailsVal['name'] : '-';
               
                $action = '<a href="'.route('frontAttributeEdit', base64_encode($id)).'" 
                title="'.trans('lang.edit_label').'" style="color:#03989E;"  class=""><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

               /* $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('frontAttributeDelete', base64_encode($id)).'\');"  title="'.trans('lang.delete_title').'" class=""><i class="fas fa-trash"></i></a>';*/

        
                
			    $arr[] = ['',$name,$action];
			}
        }
        else {
            $arr[] = ['', trans('lang.datatables.sEmptyTable'), ''];
        }
    	
    	
    	$json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];
    
        return json_encode($json_arr);
    }
    
    public function create()
    {
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        $data['subscribedError']   =    '';
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) {
               $data['subscribedError']   =    trans('messages.subscribe_package_to_manage_prod_attri');
            }
        }
        $data['pageTitle']              = 'Add Attributes';
        $data['current_module_name']    = 'Add';
        $data['module_name']            = 'Attributes';
        $data['module_url']             = route('frontProductAttributes');
        return view('Front/ProductAttributes/create', $data);
    }
    
     /**
     * insert country details
     */
    public function store(Request $request) {
    
        $rules = [
            'name' => 'required',
            //'name' => 'required|unique:attributes,name',
            'type' => 'required'
        ];
        $messages = [
            'name.required'         => trans('lang.required_field_error'),
            'type.required'         => trans('lang.required_field_error'),
            //'name.unique'           => trans('errors.unique_attr_name_err'),
        ];
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        
        $arrInsertAttribute = ['name' => trim($request->input('name')),
                              'type' => trim($request->input('type')),
                              //'user_id'=> Auth::guard('user')->id(),
                            ];

        $id = Attributes::create($arrInsertAttribute)->id; 
        return $this->edit(base64_encode($id));
    }
     /**
     * Edit record details
     * @param  $id = User Id
     */
    public function edit($id) {
        $data = $details = $valueDetails=[];
        $data['subscribedError']   =    '';
        $User   =   UserMain::where('id',Auth::guard('user')->id())->first();
        if($User->role_id==2) {
            $currentDate = date('Y-m-d H:i:s');
            $isSubscribed = DB::table('user_packages')
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->where('packages.is_deleted','!=',1)
                        ->where('user_packages.end_date','>=',$currentDate)
                        ->where('user_id','=',Auth::guard('user')->id())
                        ->select('packages.id')
                        ->get();
                        
            if(count($isSubscribed)<=0) {
                $data['subscribedError']   =   trans('messages.subscribe_package_to_manage_prod_attri');
            }
        }
       if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
         
        $data['id'] = $id;
        $id = base64_decode($id);
        $details = Attributes::where('id', $id)->first()->toArray();

        $segment = request()->segment(2);
       
        if($segment == 'edit'){
            $valueDetails = DB::table('attributes as attribute')
                    ->join('attributes_values as val','attribute.id','=','val.attribute_id')
                    ->where('attribute.id',$id)
                   // ->where('attribute.user_id',Auth::guard('user')->id())
                    ->select('attribute.*','val.*')
                    ->get();
            $data['attributesValues']          = $valueDetails;
            
        }

        if(!empty($segment)){
             $data['segment']          = $segment;
        }

		$data['pageTitle']              = trans('lang.edit_attribute_tittle');
        $data['current_module_name']    = trans('lang.edit_label');
        $data['module_name']            =  trans('lang.product_attribute_title');
        $data['module_url']             = route('frontProductAttributes');
	    $data['attributesDetails']      = $details;
    
        return view('Front/ProductAttributes/edit', $data);
    }
    
    /**
     * Update vendor details
     * @param  $id = Vendor Id
     */
    public function update(Request $request, $id) { 
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);
      
        $rules = [
            //'name'             => 'required|unique:attributes,name,'.$id,
            'name'             => 'required',
            'type'             => 'required',
			'attribute_values' => 'required',

        ];
        $messages = [
            'name.required'             => trans('lang.required_field_error'),
            'type.required'             => trans('lang.required_field_error'),
			'attribute_values.required' => trans('lang.required_field_error'),
            //'name.unique'           => trans('errors.unique_attr_name_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
      
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
     
        $arrUpdateAttributes = [
                            'name' => trim($request->input('name')),
                            'type' => trim($request->input('type')),
                            ];

        Attributes::where('id', '=', $id)->update($arrUpdateAttributes);  

        // Get multiple input field's value 
        $values_array =$request->input('attribute_values');
        $valueIds =$request->input('attribute_id');
        $count = count($values_array);
            for($i=0;$i<$count;$i++) {
                $attribute_id[$i] = $id;
                $attribute_values[$i] = $values_array[$i];

                if(!empty($valueIds[$i])){
                        DB::table('attributes_values')
                        ->where('id', $valueIds[$i])
                        ->update(array('attribute_id' => $id,'attribute_values'=>$values_array[$i]));  

                }else{
                      DB::table('attributes_values')
                      ->insert(array('attribute_id'=> $id,'attribute_values' => $values_array[$i] ));
                }
           }

        Session::flash('success', trans('lang.attribute_saved_success'));
        return redirect(route('frontProductAttributes'));
    }
    
      /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('lang.something_went_wrong'));
            return redirect(route('frontProductAttributes'));
        }
        $id = base64_decode($id);
        
        $attribute = Attributes::find($id);
        if (!empty($attribute)) 
		{
            if ($attribute->delete()) 
			{
			    DB::table('variant_product_attribute')->where('attribute_id', $id)->delete();
                Session::flash('success', trans('lang.record_delete'));
                return redirect()->back();
            } else {
                Session::flash('error', trans('lang.something_went_wrong'));
                return redirect()->back();
            }
        } 
        else {
            Session::flash('error', trans('lang.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function deleteAttributeValue(Request $request){
       
        $id = $request->input('id');
       
        $attributeValue = AttributesValues::find($id);
        if (!empty($attributeValue)) 
        {
            if ($attributeValue->delete()) 
            {
               DB::table('variant_product_attribute')->where('attribute_value_id', $id)->delete(); 
                return trans('lang.record_delete');
            } else {
                return trans('lang.something_went_wrong');
            }
        }
    }
    public function getattributevaluebyattributeid(Request $request){
       
        $attribute_id = $request->input('attribute_id');
       
        $attributeValue = AttributesValues::where('attribute_id',$attribute_id)->get();
        $html   =   '<option value="">'.trans("lang.attribute_value_label").'</option>';
        if (!empty($attributeValue)) 
        {
            
            foreach($attributeValue as $value) {
                $html   .=  '<option value="'.$value->id.'">'.$value->attribute_values.'</option>';
            }
        }
        echo $html;
    }
}
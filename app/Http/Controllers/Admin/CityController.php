<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class CityController extends Controller
{
    function __construct() {
    	
    }

    /**
     * Show list of records for city.
     * @return [array] [record array]
     */
    public function index(Request $request) {
        $data = [];
        $data['pageTitle']              = 'City';
        $data['current_module_name']    = 'City';
        $data['module_name']            = 'City';
        $data['module_url']             = route('adminCity');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
       
        return view('Admin/City/index', $data);
    }

    /**
     * [getRecords for user list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) 
    {
        $cityDetails = City::select('cities.*')->where('is_deleted','!=',1);
     
        if(!empty($request['search']['value'])){
            $cityDetails = $cityDetails->where('cities.name', 'LIKE', '%'.$request['search']['value'].'%');
        }
       
        if (!empty($request['status']) && !empty($request['search']['value']))
        {
            $cityDetails = $cityDetails->Where('cities.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) 
        {
            $cityDetails = $cityDetails->Where('cities.status', '=', $request['status']);
        }	
       
        if (!empty($request['order'])) {
            $column = 'cities.name';
            $order = 'asc';
            $order_arr = [  
                            '0' =>  'cities.id',
                            '1' =>  'cities.name',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];
               $order = $request['order'][0]['dir']; 
            }

            $cityDetails = $cityDetails->orderBy($column,$order);
        }

        $recordsTotal = $cityDetails->count();
        $recordDetails = $cityDetails->offset($request->input('start'))->limit($request->input('length'))->get();
		
        $arr = [];
        if (count($recordDetails) > 0) 
        {
            $recordDetails = $recordDetails->toArray();
						
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) {
              
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
                $city = (!empty($recordDetailsVal['name'])) ? $recordDetailsVal['name'] : '-';
              
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminCityChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminCityChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';
                }
              

                $action = '<a href="'.route('adminCityEdit', base64_encode($id)).'" title="Edit" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminCityDelete', base64_encode($id)).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

                $arr[] = ['',$city,$status,$action];
            }
        } 
        else {
            $arr[] = ['', '', 'No Records Found',''];
        }
        $json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];
        
        return json_encode($json_arr);
    }

    public function addnew() {
        $data = $details = [];
        $data['pageTitle']              = 'Add City';
        $data['current_module_name']    = 'Add';
        $data['module_name']            = 'City';
        $data['module_url']             = route('adminCity');     
        return view('Admin/City/addnew', $data);
    }

    /**
     * Edit city record details
     * @param  $id = city Id
     */
    public function edit($id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Refresh your page.');
            return redirect()->back();
        }
 
        $data = $details = [];
        $data['id'] = $id;
        $id = base64_decode($id);
        $details= City::get_city($id);

        if(empty($details)) {
            Session::flash('error', 'Something went wrong. Refresh your page.');
            return redirect()->back();   
        }

        $data['pageTitle']              = 'Edit City';
        $data['current_module_name']    = 'Edit';
        $data['module_name']            = 'City';
        $data['module_url']             = route('adminCity');;
        $data['cityData']          = $details;
                   
        return view('Admin/City/edit', $data);
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
            'city' => 'required|regex:/^[\pL\s\-]+$/u'
        ];

        $messages = [
            'city.required' => 'Please fill in City Name',
            'city.regex'    => 'Please input alphabet characters only',
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $city = City::where('id', '=', $id)->first()->toArray();
            $arrUpdate = ['name' => trim($request->input('city')),
                        'updated_at' => date('Y-m-d H:i:s')];
            City::where('id', '=', $id)->update($arrUpdate);  

            Session::flash('success', 'City details updated successfully!');
            return redirect(route('adminCity'));
        }
    }

    /**
     * Change status for Record [active/block].
     * @param  $id = Id, $status = active/block 
     */
    public function changeStatus($id, $status) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('adminCity'));
        }
        $id = base64_decode($id);

        $result = City::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', 'Status updated successfully!');
            return redirect()->back();
        } else {
            Session::flash('error', 'Oops! Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Delete City Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('adminCity'));
        }
        $id = base64_decode($id);

        $result = City::find($id);
        if (!empty($result)) {
            $city = City::where('id', $id)->update(['is_deleted' =>1]);   
            Session::flash('success', 'Record deleted successfully!');
            return redirect()->back();
        } 
        else {
            Session::flash('error', 'Oops! Something went wrong!');
            return redirect()->back();
        }
    }

   
    /* function to save city details*/
    public function StoreCity(Request $request) {
        $rules = [
            'cityName' => 'required|regex:/^[\pL\s\-]+$/u',
        ];
        $messages = [
            'cityName.required' => 'Please enter City Name',
            'cityName.regex' => 'Please input alphabet characters only',  
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $arrCityInsert = ['name' => trim($request->input('cityName')),
                             'created_at' => date('Y-m-d H:i:s')];
            City::insert($arrCityInsert);
            Session::flash('success', 'City details Inserted successfully!');
            return redirect(route('adminCity'));
        }
    }
}

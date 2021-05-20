<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Customers;

/*Uses*/
use Session;
use Validator;

class CustomerController extends Controller
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
        $data['pageTitle']              = 'Customers';
        $data['current_module_name']    = 'Customers';
        $data['module_name']            = 'Customers';
        $data['module_url']             = route('adminCustomers');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/Customer/index', $data);
    }
    
    /**
     * [getRecords for user list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) {
    	$customerDetails = Customers::select('Id','Name','ContactName','Address','Town','County','Country','TelNumber','TelWork','FaxNo','Email','TaxRef','PostCode','SetupDate','Note','FreeComputer','IsSQLDB','IsAccessDB','IsDeleted')->where('IsDeleted','!=',1);
       
    	$recordsTotal = $customerDetails->count();
    	
    	if(!empty($request['search']['value']))
        {
            $customerDetails = $customerDetails->where('Customer.Name', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.Address', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.Town', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.Country', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.County', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.PostCode', 'LIKE', '%'.$request['search']['value'].'%');
            $customerDetails = $customerDetails->orWhere('Customer.SetupDate', 'LIKE', '%'.$request['search']['value'].'%');

        }
        
        if (!empty($request['order'])) {
            $column = 'Customer.id';
            $order = 'desc';
            $order_arr = [  
                            '0' =>  'Customer.Id',
                            '1' =>  'Customer.Name',
                            '2' =>  'Customer.Address',
                            '3' =>  'Customer.Town',
                            '4' =>  'Customer.County',
                            '5' =>  'Customer.Country',
                            '6' =>  'Customer.PostCode',
                            '7' =>  'Customer.SetupDate',
                            '8' =>  'Customer.IsSQLDB',
                            '9' =>  'Customer.IsAccessDB',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];

               $order = $request['order'][0]['dir']; 
            }
            $customerDetails = $customerDetails->orderBy($column,$order);
        }

        $recordDetails = $customerDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
			{
			    $id = $recordDetailsVal['Id'];
			    $name = (!empty($recordDetailsVal['Name'])) ? $recordDetailsVal['Name'] : '-';
                $address = (!empty($recordDetailsVal['Address'])) ? $recordDetailsVal['Address'] : '-';
                $town = (!empty($recordDetailsVal['Town'])) ? $recordDetailsVal['Town'] : '-';
                $county = (!empty($recordDetailsVal['County'])) ? $recordDetailsVal['County'] : '-';
                $country = (!empty($recordDetailsVal['Country'])) ? $recordDetailsVal['Country'] : '-';
                $postCode = (!empty($recordDetailsVal['PostCode'])) ? $recordDetailsVal['PostCode'] : '-';
                $setupDate = (!empty($recordDetailsVal['SetupDate'])) ? $recordDetailsVal['SetupDate'] : '-';
          
                if($recordDetailsVal['IsSQLDB'] == 1){
                    $allow_sql = 'YES';
                }else{
                    $allow_sql = 'NO';
                }
              
                if($recordDetailsVal['IsAccessDB'] == 1){
                    $allow_access = 'YES';
                }else{
                     $allow_access = 'NO';
                }
                 
               
                $action = '<a href="'.route('adminComputerCreate', base64_encode($id)).'" title="Add/View Computers" class="btn btn-icon btn-warning"><i class="fas fa-plus"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="'.route('adminCustomerEdit', base64_encode($id)).'" title="Edit" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminCustomerDelete', base64_encode($id)).'\');"  title="Delete" class="btn btn-icon btn-danger" ><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['',$name, $address, $town,$county,$country,$postCode,$setupDate,$allow_sql,$allow_access,$action];
			}
        }
        else {
            $arr[] = ['','', '', '', '', 'No Records Found', '', '', '', '',''];
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
        $data['pageTitle']              = 'Add Customer';
        $data['current_module_name']    = 'Add';
        $data['module_name']            = 'Customer';
        $data['module_url']             = route('adminCustomers');
        return view('Admin/Customer/create', $data);
    }
    
     /**
     * insert country details
     */
    public function store(Request $request) {
              
        $rules = [
            'name' => 'required',
            'contact_name' => 'required',
        ];
        $messages = [
            'name.required'    => 'Please fill in Name',
            'contact_name.required'    => 'Please fill in Contact Name',
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
     
        $arrInsertCustomer = [ 'Name'=>trim($request->input('name')),
                              'ContactName' => trim($request->input('contact_name')),
                              'Town' => trim($request->input('town')),
                              'Country' => trim($request->input('country')),
                              'TelNumber' => trim($request->input('tel_mobile')),
                              'Email' => trim($request->input('email')),
                              'PostCode' => trim($request->input('post_code')),
                              'SetupDate' => trim($request->input('setup_date')),
                              'FreeComputer' => trim($request->input('free_computer')),
                              'Address' => trim($request->input('address')),
                              'County' => trim($request->input('county')),
                              'TelWork' => trim($request->input('tel_work')),
                              'FaxNo' => trim($request->input('fax_no')),
                              'TaxRef' => trim($request->input('tax_ref')),
                              'Note' => trim($request->input('note')),
                              'IsSQLDB' => trim($request->input('sql')),
                              'IsAccessDB' => trim($request->input('access')),
                            ];

        Customers::create($arrInsertCustomer);                   
        
        Session::flash('success', 'Customer details Inserted successfully!');
        return redirect(route('adminCustomers'));
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
        $details = Customers::where('Id', $id)->first()->toArray();
        
		    $data['pageTitle']              = 'Edit Customer';
        $data['current_module_name']    = 'Edit';
        $data['module_name']            = 'Customer';
        $data['module_url']             = route('adminCustomers');
		    $data['customerDetails']          = $details;
            
        return view('Admin/Customer/edit', $data);
    }
    
    /**
     * Update Customers details
     * @param  $id = Customer Id
     */
    public function update(Request $request, $id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Refresh your page.');
            return redirect()->back();
        }
        
        $id = base64_decode($id);
        
         $rules = [
            'name' => 'required',
            'contact_name' => 'required',
        ];

        $messages = [
            'name.required'    => 'Please fill in Name',
            'contact_name.required'    => 'Please fill in Contact Name',
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
              
        $arrUpdateCustomer = [ 'Name'=>trim($request->input('name')),
                              'ContactName' => trim($request->input('contact_name')),
                              'Town' => trim($request->input('town')),
                              'Country' => trim($request->input('country')),
                              'TelNumber' => trim($request->input('tel_mobile')),
                              'Email' => trim($request->input('email')),
                              'PostCode' => trim($request->input('post_code')),
                              'SetupDate' => trim($request->input('setup_date')),
                              'FreeComputer' => trim($request->input('free_computer')),
                              'Address' => trim($request->input('address')),
                              'County' => trim($request->input('county')),
                              'TelWork' => trim($request->input('tel_work')),
                              'FaxNo' => trim($request->input('fax_no')),
                              'TaxRef' => trim($request->input('tax_ref')),
                              'Note' => trim($request->input('note')),
                              'IsSQLDB' => trim($request->input('sql')),
                              'IsAccessDB' => trim($request->input('access')),
                            ];

        Customers::where('Id', '=', $id)->update($arrUpdateCustomer);  
        
        Session::flash('success', 'Customer details updated successfully!');
        return redirect(route('adminCustomers'));
    }
    
      /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', 'Something went wrong. Reload your page!');
            return redirect(route('adminCustomers'));
        }
        $id = base64_decode($id);
        
        $customers = Customers::find($id);
        if (!empty($customers)) 
		{
             $arrDeleteCutomer = ['IsDeleted' => 1];
            if ($customers::where('Id', '=', $id)->update($arrDeleteCutomer)) 
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
}
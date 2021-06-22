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
        $data['pageTitle']              = trans('users.service_subcategory_title');
        $data['current_module_name']    = trans('users.service_subcategory_title');
        $data['current_id']             = $request->id;
        $data['module_name']            = trans('users.service_subcategory_title');
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

		$categoryDetails = servicecategories::select('servicecategories.category_name')->where('id','=',$category_id)->get()->first();

		$SubcategoryDetails = ServiceSubcategories::select('serviceSubcategories.*')->where('category_id','=',$category_id);



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
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceSubcatChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminServiceSubcatChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminServiceSubCatEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminServiceSubcatDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

			    $arr[] = ['',$Category_Name, $Subcategory, $sequence_no,$status, $action];
			}
        }
        else {
            $arr[] = ['','', trans('lang.datatables.sEmptyTable'),'', '', ''];
        }


    	$json_arr = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => ($arr),
        ];

        return json_encode($json_arr);
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
     * save sub category deatils details
     */
    public function subCategoryStore(Request $request) {
        $id=$request->id;

         $subcategory_slug = $request->input('subcategory_slug');
         $is_accents = preg_match('/^[\p{L}-]*$/u', $subcategory_slug);
         if($is_accents ==1){
            $slug =   $this->php_cleanAccents($subcategory_slug);
         }else{
            $slug = $request->input('subcategory_slug');
         }

        $rules = [
            'category_name'    => 'required',
            'subcategory_name' => 'required|regex:/^[\pL0-9\s\-]+$/u|unique:serviceSubcategories,subcategory_name',
            'sequence_no'      => 'required',
           'subcategory_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:subcategories,subcategory_slug',
        ];
        $messages = [
            'category_name.required'         => trans('errors.category_name_req'),
            'subcategory_name.required' => trans('errors.subcategory_name_req'),
            'subcategory_name.regex'    => trans('errors.input_alphabet_err'),
            'sequence_no.required'               => trans('errors.sequence_number_err'),
            'subcategory_name.unique'   => trans('errors.unique_subcategory_name'),
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
                    'subcategory_slug' => trim(strtolower($slug)),
                ];

                serviceSubcategories::create($arrInsertSubcategory);
                Session::flash('success', trans('messages.subcat_save_success'));
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
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        $data = $details = [];

        $data['id'] = $id;
        $id = base64_decode($id);

        $details = serviceSubcategories::where('serviceSubcategories.id', $id)
        ->Join('servicecategories', 'serviceSubcategories.category_id', '=', 'servicecategories.id')
        ->select('serviceSubcategories.*','ServiceCategories.id','ServiceCategories.category_name')->first();

        $data['pageTitle']              = trans('users.edit_service_subcat_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.service_subcategory_title');
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
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }

        $id = base64_decode($id);

        $subcategory_slug = $request->input('subcategory_slug');
         $is_accents = preg_match('/^[\p{L}-]*$/u', $subcategory_slug);
         if($is_accents ==1){
            $slug =   $this->php_cleanAccents($subcategory_slug);
         }else{
            $slug = $request->input('subcategory_slug');
         }

        $rules = [
            'subcategory_name' => 'required|regex:/^[\pL0-9\s\-]+$/u|unique:serviceSubcategories,subcategory_name,'.$id,
            'sequence_no'      => 'required',
            'subcategory_slug' => 'required|regex:/^[\pL0-9a-z-]+$/u|unique:subcategories,subcategory_slug',
        ];
        $messages = [
            'subcategory_name.required' => trans('errors.subcategory_name_req'),
            'subcategory_name.regex'    => trans('errors.input_alphabet_err'),
            'sequence_no.required'               => trans('errors.sequence_number_err'),
            'subcategory_name.unique'   => trans('errors.unique_subcategory_name'),
            'subcategory_slug.required'  => trans('errors.subcategory_slug_req'),
            'subcategory_slug.regex'     => trans('errors.input_aphanum_dash_err'),
            'subcategory_slug.unique'    => trans('messages.category_slug_already_taken'),
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
                              'subcategory_slug'   => trim(strtolower($slug)),
                            ];

        serviceSubcategories::where('id', '=', $id)->update($arrUpdateCategory);

        Session::flash('success', trans('messages.subcat_update_success'));
        return redirect(route('adminServiceSubcat',base64_encode($request->input('hid_subCategory'))));
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

        $categories = serviceSubcategories::find($id);
        if (!empty($categories))
		{
            if ($categories->delete())
			{
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

        $result = serviceSubcategories::where('id', $id)->update(['status' => $status]);
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
            $data =  serviceSubcategories::where('subcategory_slug', $slug)->where('id','!=',$id)->get();
        } else{
            $data =  serviceSubcategories::where('subcategory_slug', $slug)->get();
        }
       $messages = '';
        if(!empty($data[0]['subcategory_slug'])){
            $messages =trans('messages.category_slug_already_taken');
             return $messages;
        }
    }


    /* function to check for unique subcategory name
    * @param:storename
    */
    function checkUniqueSubcat(Request $request){
        $subcat_name = $request->subcat_name;
        $id = $request->id;
        if(!empty($id)){
            $data =  serviceSubcategories::where('subcategory_name', $subcat_name)->where('id','!=',$id)->get();

        } else{
            $data =  serviceSubcategories::where('subcategory_name', $subcat_name)->get();
        }

       $messages = '';
       if(count($data) != 0){
            if(!empty($data[0]['subcategory_name'])){
                $messages =trans('errors.unique_subcategory_name');
                return $messages;
            }
       }
    }
}

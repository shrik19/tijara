<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sliders;
/*Uses*/
use Session;
use Validator;
use File;

class SliderController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for slider.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.slider_title');
        $data['current_module_name']    = trans('users.slider_title');
        $data['module_name']            = trans('users.slider_title');
        $data['module_url']             = route('adminSlider');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';
        
        return view('Admin/Slider/index', $data);
    }
    
    /**
     * [getRecords for slider list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [slider list in json format]
     */
    public function getRecords(Request $request) {

    	$SliderDetails = Sliders::select('id','title','sliderImage','description','link','sequence_no','status');
    	$recordsTotal = $SliderDetails->count();
    
    	if(!empty($request['search']['value']))
        {
            $SliderDetails = $SliderDetails->where('sliders.title', 'LIKE', '%'.$request['search']['value'].'%');
            $SliderDetails = $SliderDetails->orWhere('sliders.link', 'LIKE', '%'.$request['search']['value'].'%');
            $SliderDetails = $SliderDetails->orWhere('sliders.description', 'LIKE', '%'.$request['search']['value'].'%');
        }
        
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $SliderDetails = $SliderDetails->where('sliders.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $SliderDetails = $SliderDetails->where('sliders.status', '=', $request['status']);
        }
        
        if (!empty($request['order'])) {
            $column = 'sliders.id';
            $order = 'asc';
            $order_arr = [  
                            '0' =>  'sliders.id',
                            '1' =>  'sliders.title',
                            '2' =>  'sliders.sliderImage',
                            '3' =>  'sliders.description',
                            '4' =>  'sliders.link',
                            '5' =>  'sliders.sequence_no',
                         ];
            $column_index = $request['order'][0]['column'];
            if($column_index!=0) {
               $column = $order_arr[$column_index];
               $order = $request['order'][0]['dir']; 
            }
     
            $SliderDetails = $SliderDetails->orderBy($column,$order);
        }

        $recordDetails = $SliderDetails->offset($request->input('start'))->limit($request->input('length'))->get();
        
        $arr = [];
        if (count($recordDetails) > 0) {
            $recordDetails = $recordDetails->toArray();
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) 
			{
			    $id = $recordDetailsVal['id'];
              
			    $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
             
                $sliderImage ='<img src="'.url('/').'/uploads/Slider/'.$recordDetailsVal['sliderImage'].'" alt="" border=3 height="60" width="100"></img>';
		
                $url = (!empty($recordDetailsVal['link'])) ? $recordDetailsVal['link'] : '-';
                $link = '<a href="'.$url.'">'.$url.'</a>';
			     
                $sequence_no = (!empty($recordDetailsVal['sequence_no'])) ? $recordDetailsVal['sequence_no'] : '-';

                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSliderChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminSliderChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }
                
                $action = '<a href="'.route('adminSliderEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminSliderDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';
                
			    $arr[] = ['', $sliderImage, $title, $link, $sequence_no,$status, $action];
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
    
    /* function to open slider form */
    public function create()
    {
        $data['pageTitle']              = trans('users.add_slider_btn');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.add_slider_btn');
        $data['module_url']             = route('adminSlider');
        return view('Admin/Slider/create', $data);
    }
    
     /**
     * Store slider details
     */
    public function store(Request $request) {
        $rules = [
            'title'         => 'required',
            'slider_image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'description'   => 'required',
            'sequence_no'   => 'required',
        ];
        $messages = [
            'title.required'         => trans('errors.fill_in_slider_title_err'),
            'slider_image.required'  => trans('errors.upload_slider_image_err'),
            'slider_image.max'       => trans('errors.image_exceed_max_limit_err'),
            //'slider_image.mimes'     =>  'Only jpeg,png,jpg,gif,and svg file type allowed',
            'link.required'          => trans('errors.fill_in_slider_link_err'),
            'link.regex'             => trans('errors.fill_in_valid_slider_link_err'),
            'description.required'   => trans('errors.fill_in_slider_description_err'),
            'sequence_no.required'   => trans('errors.fill_in_slider_seq_no_err'),
        ];
        
        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }

        $image = $request->file('slider_image');
        $imageName = time().'_'.rand().'.' . $image->extension();
        $image->move(public_path('uploads/Slider'), $imageName);

        $path = public_path().'/uploads/Slider/'.$imageName;

        $arrInsertSlider = [
                            'title'       => trim($request->input('title')),
                            'sliderImage' => trim($imageName),
                            'description' => trim($request->input('description')),
                            'link'        => trim($request->input('link')),
                            'sequence_no' => trim($request->input('sequence_no')),
                           ];
        Sliders::create($arrInsertSlider);                   
        
        Session::flash('success', trans('messages.slider_save_success'));
        return redirect(route('adminSlider'));
    }

     /**
     * Edit record details
     * @param  $id = slider Id
     */
    public function edit($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        $data = $details = [];
         
        $data['id'] = $id;
        $id = base64_decode($id);
		
        $details = Sliders::where('id', $id)->first()->toArray();
        
		$data['pageTitle']              = trans('users.edit_slider_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.slider_title');
        $data['module_url']             = route('adminSlider');
		$data['sliderDetails']          = $details;
            
        return view('Admin/Slider/edit', $data);
    }
    
    /**
     * Update slider details
     * @param  $id = slider Id
     */
    public function update(Request $request, $id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);
        $rules = [
            'title'         => 'required',
            'slider_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link'          => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'description'   => 'required',
            'sequence_no'   => 'required',
        ];

        $messages = [
            'title.required'         => trans('errors.fill_in_slider_title_err'),
            'link.required'          => trans('errors.fill_in_slider_link_err'),
            'link.regex'             => trans('errors.fill_in_valid_slider_link_err'),
            'description.required'   => trans('errors.fill_in_slider_description_err'),
            'slider_image.max'       => trans('errors.image_exceed_max_limit_err'),
            //'slider_image.mimes'     =>  'Only jpeg,png,jpg,gif,and svg file type allowed',
            'sequence_no.required'   => trans('errors.fill_in_slider_seq_no_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
      
/*
        if(!empty($request->slider_image)){
           
            //upload new image
            $image = $request->slider_image;
            $imgPart = explode('.', $image);
            $imagepart1 = $imgPart[0];
            $imageName = time().'_'.rand().'.' . $imgPart[1];
            
            $imagepart1->move(public_path('uploads/Slider'), $imageName);

            $path = public_path().'/uploads/Slider/'.$imageName;

            //code for remove old file
              if($sliderDetails->sliderImage != ''  && $sliderDetails->sliderImage != null){
                echo "-------.>".$sliderDetails->sliderImagel;exit;
                   $file_old = $path.$sliderDetails->sliderImage;
                   unlink($file_old);
              }

            $arrUpdateSlider = ['sliderImage' => trim($imageName)];
        }
        */
        $sliderDetails = Sliders::find($id);
        
        if ($request->hasFile('slider_image')){
   
            $image_path = public_path("/uploads/Slider/".$sliderDetails->sliderImage);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
           /* $sliderImage = $request->file('slider_image');
            $imgPart = explode('.', $sliderImage);
            $imagepart1 = $imgPart[0];
            $imageName = time().'_'.rand().'.' . $imgPart[1];

            $destinationPath = public_path('/uploads/Slider/');
            $imagepart1->move($destinationPath, $imageName);*/
            $image = $request->file('slider_image');
            $imageName = time().'_'.rand().'.' . $image->extension();
            $image->move(public_path('uploads/Slider'), $imageName);
            $path = public_path().'/uploads/Slider/'.$imageName;
        }else{
            $imageName = $sliderDetails->sliderImage;
        }
        $arrUpdateSlider = [
                            'title'       => trim($request->input('title')),
                            'sliderImage' => trim($imageName),
                            'description' => trim($request->input('description')),
                            'link'        => trim($request->input('link')),
                            'sequence_no' => trim($request->input('sequence_no')),
                           ];
                            
        Sliders::where('id', '=', $id)->update($arrUpdateSlider);  
        
        Session::flash('success', trans('messages.slider_update_success'));
        return redirect(route('adminSlider'));
    }
    
      /**
     * Delete Record
     * @param  $id = slider Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminSlider'));
        }
        $id = base64_decode($id);
        
        $sliders = Sliders::find($id);
        if (!empty($sliders)) 
		{
            if ($sliders->delete()) 
			{
                Session::flash('success', trans('messages.record_deleted_success'));
                return redirect()->back();
            } else {
                Session::flash('error', trans('errors.something_wrong_err'));
                return redirect()->back();
            }
        } 
        else {
            Session::flash('error',trans('errors.something_wrong_err'));
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
            return redirect(route('adminSlider'));
        }
        $id = base64_decode($id);

        $result = Sliders::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success',  trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }
}
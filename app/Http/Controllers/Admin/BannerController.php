<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;


/*Uses*/
use Auth;
use Session;
use flash;
use Validator;
use DB;

class BannerController extends Controller
{
    /*
	 * Define abjects of models, services.
	 */
    function __construct() {
    	
    }

    /**
     * Show list of records for banner.
     * @return [array] [record array]
     */
    public function index() {
        $data = [];
        $data['pageTitle']              = trans('users.banner_title');
        $data['current_module_name']    = trans('users.banner_title');
        $data['module_name']            = trans('users.banner_title');
        $data['module_url']             = route('adminBanner');
        $data['recordsTotal']           = 0;
        $data['currentModule']          = '';

        return view('Admin/Banner/index', $data);
    }

    /**
     * [getRecords for user list.This is a ajax function for dynamic datatables list]
     * @param  Request $request [sent filters if applied any]
     * @return [JSON]           [users list in json format]
     */
    public function getRecords(Request $request) 
    {
        $bannerDetails = Banner::select('banner.*')->where('is_deleted','!=',1)->with('getSlider');
          
        if(!empty($request['search']['value'])) {
            $bannerDetails = $bannerDetails->where('banner.title', 'LIKE', '%'.$request['search']['value'].'%');
            $bannerDetails = $bannerDetails->orwhere('banner.display_on_page', 'LIKE', '%'.$request['search']['value'].'%');
        }
       
        if (!empty($request['status']) && !empty($request['search']['value'])) {
            $bannerDetails = $bannerDetails->Where('banner.status', '=', $request['status']);
        }
        else if(!empty($request['status'])) {
            $bannerDetails = $bannerDetails->Where('banner.status', '=', $request['status']);
        }
		
        $recordsTotal = $bannerDetails->count();
        $recordDetails = $bannerDetails->offset($request->input('start'))->limit($request->input('length'))->get();
		
		if(isset($request['order'][0])){
			$postedorder=$request['order'][0];
			if($postedorder['column']==1) {
				if($postedorder['dir']=='asc')
					$recordDetails = $recordDetails->sortBy('title');
				else $recordDetails = $recordDetails->sortByDesc('title');
			}
			if($postedorder['column']==3) {
				if($postedorder['dir']=='asc')
					$recordDetails = $recordDetails->sortBy('display_on_page');
				else $recordDetails = $recordDetails->sortByDesc('display_on_page');
			}	
		} 

        $arr = [];
        if (count($recordDetails) > 0)   {
            $recordDetails = $recordDetails->toArray();
						
            foreach ($recordDetails as $recordDetailsKey => $recordDetailsVal) {
                $action = $status = $image = '-';
                $id = (!empty($recordDetailsVal['id'])) ? $recordDetailsVal['id'] : '-';
               
                $title = (!empty($recordDetailsVal['title'])) ? $recordDetailsVal['title'] : '-';
                $redirect_link   = (!empty($recordDetailsVal['redirect_link'])) ? '<a href="">'.$recordDetailsVal['redirect_link'].'</a>' : '-';

                $display_on_page =(!empty($recordDetailsVal['display_on_page'])) ? $recordDetailsVal['display_on_page'] : '-';
                 
                $Image = (!empty($recordDetailsVal['get_slider'])) ? '<img src="'.url('/').'/uploads/Banner/'.$recordDetailsVal['get_slider'][0]['image'].'" style="width:100px;" />' : '-';
            
                if ($recordDetailsVal['status'] == 'active') {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminBannerChangeStatus', [base64_encode($recordDetailsVal['id']), 'block']).'\');" class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
                } else {
                    $status = '<a href="javascript:void(0)" onclick=" return ConfirmStatusFunction(\''.route('adminBannerChangeStatus', [base64_encode($recordDetailsVal['id']), 'active']).'\');" class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
                }

                $action = '<a href="'.route('adminBannerEdit', base64_encode($id)).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';

                $action .= '<a href="javascript:void(0)" onclick=" return ConfirmDeleteFunction(\''.route('adminBannerDelete', base64_encode($id)).'\');"  title="'.__('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>';

                $arr[] = [$Image, $title,$redirect_link,$display_on_page,$status,$action];
            }
        } 
        else {
            $arr[] = ['', '', trans('lang.datatables.sEmptyTable'), '', '',  ''];
        }

        $json_arr = [
            'draw'              => $request->input('draw'),
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => ($arr),
        ];
        
        return json_encode($json_arr);
    }

    /* Add Banner details */
    public function addNewBanner(Request $request) {
    
        $rules = [
            'title'           => 'required',
            'redirect_link'   => 'required',
            'redirect_link'   => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'display_on_page' =>'required',
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'title.required'                 => trans('errors.fill_in_banner_title_err'),
            'redirect_link.required'         => trans('errors.fill_in_banner_link_err'),
            'redirect_link.regex'            => trans('errors.fill_in_valid_banner_link_err'),
            'display_on_page.required'       => trans('errors.select_page_err'),
            'image.required'                 => trans('errors.upload_banner_image_err'),
            'image.max'                      => trans('errors.banner_image_exceed_max_limit_err'),
           // 'image.mimes'                    =>  'Only jpeg, png, jpg, gif,and svg file type allowed',
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails())  {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else {
            $banner = new Banner;
			$banner->title=trim($request->input('title'));
			$banner->redirect_link=trim($request->input('redirect_link'));
            $banner->display_on_page=trim($request->input('display_on_page'));

            if($request->hasfile('image')){
                $fileError = 0;
                $image = $request->file('image');
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());

                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'Banner'.date('YmdHis').'.'.$fileExt;
                    $image->move(public_path().'/uploads/Banner/', $fileName);  // your folder path

                    $path = public_path().'/uploads/Banner/'.$fileName;
                    $mime = getimagesize($path);

                    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
                    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

                    $old_x = imageSX($src_img);
                    $old_y = imageSY($src_img);

                    $newWidth = 500;
                    $newHeight = 500;

                    if($old_x > $old_y)
                    {
                        $thumb_w    =   $newWidth;
                        $thumb_h    =   $old_y/$old_x*$newWidth;
                    }

                    if($old_x < $old_y)
                    {
                        $thumb_w    =   $old_x/$old_y*$newHeight;
                        $thumb_h    =   $newHeight;
                    }

                    if($old_x == $old_y)
                    {
                        $thumb_w    =   $newWidth;
                        $thumb_h    =   $newHeight;
                    }

                    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

                    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


                    // New save location
                    $new_thumb_loc = public_path().'/uploads/Banner/resized/' . $fileName;

                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    imagedestroy($dst_img);
                    imagedestroy($src_img);
                    $banner->image=$fileName;
                    $banner->save();            
                }else{
                        $fileError = 1;
                }
            }

            if($fileError == 1) {
                Session::flash('error', trans('errors.invalid_files_err'));
                return redirect()->back();
            }
        
            Session::flash('success', trans('messages.banner_save_success'));
            return redirect(route('adminBanner'));
        }
    }


    /* function to open banner create form*/
    public function addnew() {   
        $data = $details = [];
        $data['pageTitle']              = trans('users.add_banner_btn');
        $data['current_module_name']    = trans('users.add_title');
        $data['module_name']            = trans('users.banner_title');
        $data['module_url']             = route('adminBanner');    
        return view('Admin/Banner/addnew', $data);
    }

    /**
     * Edit record details
     * @param  $id = User Id
     */
    public function edit($id) {
        if(empty($id)) {
            Session::flash('error',trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
 
        $data = $details = [];
        $data['id'] = $id;
        $id = base64_decode($id);
        $details= Banner::get_slider($id);

        if(empty($details)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();   
        }

        $data['pageTitle']              = trans('users.edit_banner_title');
        $data['current_module_name']    = trans('users.edit_title');
        $data['module_name']            = trans('users.banner_title');
        $data['module_url']             = route('adminBanner');
        $data['sliderData']          = $details;
               
        return view('Admin/Banner/edit', $data);
    }

    /**
     * Update Banner details
     * @param  $id = banner Id
     */
    public function update(Request $request, $id) {
    	if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect()->back();
        }
        
        $id = base64_decode($id);

        $rules = [
            'title'           => 'required',
            'redirect_link' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'display_on_page' => 'required',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  
        ];

        $messages = [
            'title.required'                 => trans('errors.fill_in_banner_title_err'),
            'redirect_link.required'         => trans('errors.fill_in_banner_link_err'),
            'redirect_link.regex'            => trans('errors.fill_in_valid_banner_link_err'),
            'display_on_page.required'       => trans('errors.select_page_err'),
            'image.required'                 => trans('errors.upload_banner_image_err'),
            'image.max'                      => trans('errors.banner_image_exceed_max_limit_err'),
        ];

        $validator = validator::make($request->all(), $rules, $messages);
        if($validator->fails()) 
        {
            $messages = $validator->messages();
            return redirect()->back()->withInput($request->all())->withErrors($messages);
        }
        else 
        {
            $slider = Banner::where('id', '=', $id)->first()->toArray();
            $arrUpdate = [ 'title'          => trim($request->input('title')),
                          'redirect_link'   => trim($request->input('redirect_link')),  
                          'display_on_page' => trim($request->input('display_on_page')),   
                        ];
            Banner::where('id', '=', $id)->update($arrUpdate);  

            if($request->hasfile('image'))
            {
                $fileError = 0;
                $image = $request->file('image');
                $name=$image->getClientOriginalName();
                $fileExt  = strtolower($image->getClientOriginalExtension());
                    
                if(in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
                    $fileName = 'slider'.$id.'_'.date('YmdHis').'.'.$fileExt;
                    $image->move(public_path().'/uploads/Banner/', $fileName);  // your folder path

                    $path = public_path().'/uploads/Banner/'.$fileName;
                    $mime = getimagesize($path);

                    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
                    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
                    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

                    $old_x = imageSX($src_img);
                    $old_y = imageSY($src_img);

                    $newWidth = 500;
                    $newHeight = 500;

                    if($old_x > $old_y)
                    {
                        $thumb_w    =   $newWidth;
                        $thumb_h    =   $old_y/$old_x*$newWidth;
                    }

                    if($old_x < $old_y)
                    {
                        $thumb_w    =   $old_x/$old_y*$newHeight;
                        $thumb_h    =   $newHeight;
                    }

                    if($old_x == $old_y)
                    {
                        $thumb_w    =   $newWidth;
                        $thumb_h    =   $newHeight;
                    }

                    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

                    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


                    // New save location
                    $new_thumb_loc = public_path().'/uploads/Banner/resized/' . $fileName;

                    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
                    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
                    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

                    imagedestroy($dst_img);
                    imagedestroy($src_img);

                    $arrInsert = ['image'=>$fileName];
                    Banner::where('id', '=', $id)->update($arrInsert);
                }
                else {
                    $fileError = 1;
                }

                if($fileError == 1)
                {
                    Session::flash('error', trans('errors.invalid_files_err'));
                    return redirect()->back();
                }
            }           
            
            Session::flash('success', trans('messages.banner_update_success'));
            return redirect(route('adminBanner'));
        }
    }


    /**
     * Change status for Record [active/block].
     * @param  $id = Id, $status = active/block 
     */
    public function changeStatus($id, $status) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminBanner'));
        }
        $id = base64_decode($id);

        $result = Banner::where('id', $id)->update(['status' => $status]);
        if ($result) {
            Session::flash('success', trans('messages.status_updated_success'));
            return redirect()->back();
        } else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }

     /**
     * Delete Record
     * @param  $id = Id
     */
    public function delete($id) {
        if(empty($id)) {
            Session::flash('error', trans('errors.refresh_your_page_err'));
            return redirect(route('adminBanner'));
        }
        $id = base64_decode($id);

        $result = Banner::find($id);
        if (!empty($result)) {
            $delete = Banner::where('id', $id)->update(['is_deleted' =>1]);
            Session::flash('success', trans('messages.record_deleted_success'));
            return redirect()->back();
        } 
        else {
            Session::flash('error', trans('errors.something_wrong_err'));
            return redirect()->back();
        }
    }  
}

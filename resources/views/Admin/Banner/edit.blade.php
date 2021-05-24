@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('users.edit_banner_details_title')}}</p>
   <form method="POST" action="{{route('adminBannerUpdate', $id)}}" class="needs-validation" enctype="multipart/form-data"  novalidate="">
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                   <label>{{ __('users.select_page_ddl')}}  <span class="text-danger">*</span></label>
                  <select class="form-control" id="display_on_page" name="display_on_page" tabindex="1">
                     <option value="" >{{ __('users.select_page_ddl')}}</option>
                     <option value="Home" <?php if($sliderData[0]->display_on_page=="Home"){ echo "selected"; } ?>>{{ __('users.home_page')}}</option>
                     <option value="Login" <?php if($sliderData[0]->display_on_page=="Login"){ echo "selected"; } ?>>{{ __('lang.login_btn')}}</option>
                     <option value="Register" <?php if($sliderData[0]->display_on_page=="Register"){ echo "selected"; } ?>>{{ __('lang.register_btn')}}</option>
                     <option value="My Account" <?php if($sliderData[0]->display_on_page=="My Account"){ echo "selected"; } ?>>{{ __('lang.my_account_title')}}</option>
                     <option value="Product List" <?php if($sliderData[0]->display_on_page=="Product List"){ echo "selected"; } ?>>{{ __('users.product_list_page')}}</option>
                     <option value="Shopping Cart" <?php if($sliderData[0]->display_on_page=="Shopping Cart"){ echo "selected"; } ?>>{{ __('users.shopping_cart_page')}}</option>
                     <option value="About Us" <?php if($sliderData[0]->display_on_page=="About Us"){ echo "selected"; } ?>>{{ __('users.about_us_page')}}</option>
                     <option value="Contact Us" <?php if($sliderData[0]->display_on_page=="Contact Us"){ echo "selected"; } ?>>{{ __('users.contact_us_page')}}</option>
                  </select>
                  <div class="invalid-feedback">
                  {{ __('errors.select_page_err')}}
               </div>
               <div class="text-danger">{{$errors->first('display_on_page')}}</div>
                </div>
               <div class="form-group">
                  <label>{{ __('users.title_thead')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" required tabindex="2" value="{{ (old('title')) ?  old('title') : $sliderData[0]->title}}">
                  <div class="invalid-feedback">
                     {{ __('errors.fill_in_banner_title_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div> 

                  <div class="form-group">
                  <label>{{ __('users.link_thead')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="redirect_link" id="redirect_link" required tabindex="3" value="{{ (old('redirect_link')) ?  old('redirect_link') : $sliderData[0]->redirect_link}}">
                  <div class="invalid-feedback">
                     {{ __('errors.fill_in_banner_link_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('redirect_link')}}</div>
               </div>
                <div class="form-group incrementerr clonedprofile">
                  <label>{{ __('users.image_thead')}} <span class="text-danger">*</span></label>
                  @php
                     if(!empty($sliderData[0]->image))
                     {
                        echo '<div class="row">';
                        
                           echo '<div class="col-md-4 existing-imagesprofile"><img src="'.url('/').'/uploads/Banner/resized/'.$sliderData[0]->image.'" style="width:200px;height:200px;"></div>';
                        echo '</div>';
                        echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
                     }
                  @endphp
                 
                  <input type="file" name="image" class="form-control" tabindex="4">
                   <p>({{ __('users.image_upload_info')}})</p>
                  <div class="input-group-btn text-right"> 
                  </div>
               </div>
            </div>
			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminBanner')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
@endsection('middlecontent')
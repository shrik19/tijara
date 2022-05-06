@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('users.add_banner_details_title')}}</p>
   <form method="POST" action="{{route('adminBannerAddnew')}}" class="needs-validation"    enctype="multipart/form-data" novalidate="">
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group"> 
                  <label>{{ __('users.select_page_ddl')}}  <span class="text-danger">*</span></label>
                  <select class="form-control" id="display_on_page" name="display_on_page" tabindex="1" >
                     <option value="">{{ __('users.select_page_ddl')}}</option>
                     <!-- <option value="Home">{{ __('users.home_page')}}</option> -->
                     <option value="Login">{{ __('lang.login_btn')}}</option>
                     <option value="Register">{{ __('lang.register_btn')}}</option>
                     <!-- <option value="My Account">{{ __('lang.my_account_title')}}</option>
                     <option value="Product List">{{ __('users.product_list_page')}}</option>
                     <option value="Shopping Cart">{{ __('users.shopping_cart_page')}}</option>
                     <option value="About Us"> {{ __('users.about_us_page')}}</option>
                     <option value="Contact Us">{{ __('users.contact_us_page')}}</option> -->
                  </select>
               <div class="invalid-feedback">
                  {{ __('errors.select_page_err')}}
               </div>
               <div class="text-danger">{{$errors->first('display_on_page')}}</div>
             </div>
               <div class="form-group">
                  <label>{{ __('users.title_thead')}}  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title"  tabindex="2" value="{{ old('title')}}">
                  <div class="invalid-feedback">
                     {{ __('errors.fill_in_banner_title_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div> 
               
               <div class="form-group">
                  <label>{{ __('users.link_thead')}}  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="redirect_link" id="redirect_link"  tabindex="3" value="{{ old('redirect_link')}}">
                  <div class="invalid-feedback">
                    {{ __('errors.fill_in_banner_link_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('redirect_link')}}</div>
               </div>
              
               <div class="form-group">
                  <label>{{ __('users.image_thead')}}  <span class="text-danger">*</span></label>
                   <input type="file" name="image" class="form-control" tabindex="4" value="{{ old('image')}}" >
                  <p>{{ __('users.image_upload_info')}}  <br> {{ __('users.image_upload_info_banner')}}</p>
                  <div class="invalid-feedback">
                    {{ __('errors.upload_banner_image_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('image')}}</div>
               </div> </div>


			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminBanner')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
@endsection('middlecontent')
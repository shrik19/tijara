@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>
</div>
<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
    @include ('Front.alert_messages')
     
		<div class="card-header row">
			  <h2>{{ __('users.seller_personal_form_label')}} </h2>
			  <hr class="heading_line">
		</div>
        <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data">
            @csrf

    
       <div class="col-md-6">
       
        <br>

        
          <div class="login_box">
          

            <div class="form-group">
              <label>{{ __('lang.store_name')}} <span class="de_col"></span></label>
              <input type="text" class="form-control store_name" id="store_name" name="store_name" 
              placeholder="{{ __('lang.store_name')}} " value="@if(!empty($details->store_name)) {{$details->store_name}} @endif" />
              <span class="invalid-feedback" id="err_fname">@if($errors->has('store_name')) {{ $errors->first('store_name') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.store_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_information" id="" 
              placeholder="{{ __('lang.store_information')}}" 
              value="" tabindex="2">@if(!empty($details->store_information)) {{$details->store_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('store_information')) {{ $errors->first('store_information') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.store_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_policy" id="" 
              placeholder="{{ __('lang.store_policy')}}" 
              value="" tabindex="2">@if(!empty($details->store_policy)) {{$details->store_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('store_policy')) {{ $errors->first('store_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.return_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="return_policy" id="" 
              placeholder="{{ __('lang.return_policy')}}" 
              value="" tabindex="2">@if(!empty($details->return_policy)) {{$details->return_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('return_policy')) {{ $errors->first('return_policy') }}@endif </span>
            </div>
           
          </div>
        </div>
        <div class="col-md-6">
        
          <div class="login_box">
          

            <div class="form-group">
              <label>{{ __('lang.shipping_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="shipping_policy" id="" 
              placeholder="{{ __('lang.shipping_policy')}}" 
              value="" tabindex="2">@if(!empty($details->shipping_policy)) {{$details->shipping_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('shipping_policy')) {{ $errors->first('shipping_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.other_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="other_information" id="" 
              placeholder="{{ __('lang.other_information')}}" 
              value="" tabindex="2">@if(!empty($details->other_information)) {{$details->other_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('other_information')) {{ $errors->first('other_information') }}@endif </span>
            </div>
            <div class="form-group increment cloned">
              <label>{{ __('users.seller_header_img_label')}}</label>
              @php
              if(!empty($details->header_img))
              {
                echo '<div class="row">';
                echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->header_img.'" style="width: 235px;height: 150px;" id="previewBanner"></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }else{
               echo '<div class="bannerImage" style="display: none;">';
              echo '<div class="row">';
                echo '<div class="col-md-4 existing-images"><img src="" style="width: 235px;height: 150px;" id="previewBanner"></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div></div>';
            }
              @endphp

              <input type="file" name="header_img" id="bannerInp" class="form-control" value="">
              <p class="seller-banner-info" style="margin-top: 10px;font-size: 13px;">({{ __('users.seller_banner_info')}})</p>
              
              <div class="text-danger">{{$errors->first('filename')}}</div>
              <div class="input-group-btn text-right"> 
              </div>
            </div>

            <div class="form-group increment cloned">
              <label>{{ __('users.seller_logo_label')}}</label>
              @php
              if(!empty($details->logo))
              {
                echo '<div class="row">';
                echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->logo.'" style="width:250px;height:80px;" id="previewLogo"></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }else{

              echo '<div class="logoImage" style="display: none;">';
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="" style="width:250px;height:80px;" id="previewLogo"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div></div>';
                
            }
              @endphp

              <input type="file" name="logo" id="logoInp" class="form-control" value="">
              <p class="seller-logo-info" style="margin-top: 10px;font-size: 13px;">({{ __('users.seller_logo_info')}})</p>
              <div class="text-danger">{{$errors->first('filename')}}</div>
              <div class="input-group-btn text-right"> 
              </div>
            </div>

          
              
          </div>
        </div>
        
        <div class="col-md-9 pull-right">

          <button class="btn btn-black debg_color login_btn">{{ __('lang.update_btn')}}</button>
          <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('lang.cancel_btn')}}</a>
                
        </div>
      </form>    
    </div>
  </div>
  </div>
            </div>
</div> <!-- /container -->
<script type="text/javascript">
bannerInp.onchange = evt => {
  const [file] = bannerInp.files
  if (file) {
    $('.bannerImage').css('display','block');
    previewBanner.src = URL.createObjectURL(file)
  }
}

logoInp.onchange = evt => {
  const [file] = logoInp.files
  if (file) {
    $('.logoImage').css('display','block');
    previewLogo.src = URL.createObjectURL(file)
  }
}
</script>

@endsection
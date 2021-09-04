@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
    @include ('Front.alert_messages')
    <div class="seller_info">
		<div class="card-header row seller_header">
			  <h2>{{ __('users.seller_personal_page_menu')}} </h2>
			  <!-- <hr class="heading_line"> -->
		</div>
    <div class="" style="margin-left: 800px;">
       
        <a href="{{$seller_link}}"><i class="fa fa-eye" aria-hidden="true">{{ __('users.see_show_label')}} </i></a>
       
    </div>
        <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data">
            @csrf

    
       <div class="col-md-6">
       
        <br>

        
          <div class="login_box seller_mid_cont">
         
            <div class="form-group">
              <label>{{ __('lang.store_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_information" id="" rows="10" cols="20" placeholder="{{ __('lang.store_information')}}" 
              value="" tabindex="2">@if(!empty($details->store_information)) {{$details->store_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('store_information')) {{ $errors->first('store_information') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.payment_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="payment_policy" id="" 
              placeholder="{{ __('lang.payment_policy')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->payment_policy)) {{$details->payment_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('payment_policy')) {{ $errors->first('payment_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.booking_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="booking_policy" id="" 
              placeholder="{{ __('lang.booking_policy')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->booking_policy)) {{$details->booking_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('booking_policy')) {{ $errors->first('booking_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.return_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="return_policy" id="" 
              placeholder="{{ __('lang.return_policy')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->return_policy)) {{$details->return_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('return_policy')) {{ $errors->first('return_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.shipping_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="shipping_policy" id="" 
              placeholder="{{ __('lang.shipping_policy')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->shipping_policy)) {{$details->shipping_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('shipping_policy')) {{ $errors->first('shipping_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.other_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="other_information" id="" rows="10" cols="20" placeholder="{{ __('lang.other_information')}}" 
              value="" tabindex="2">@if(!empty($details->other_information)) {{$details->other_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('other_information')) {{ $errors->first('other_information') }}@endif </span>
            </div>
           
          </div>
        </div>
        <div class="col-md-6">
        
          <div class="login_box">
          

             <div class="form-group"  style="display: flex;margin-top: 19px;">
              <label>{{ __('lang.store_name')}} <span class="de_col"></span></label>
              <input type="text" class="form-control store_name" id="store_name" name="store_name" 
              placeholder="{{ __('lang.store_name')}} " value="@if(!empty($details->store_name)) {{$details->store_name}} @endif" style="margin-top: 30px;
    margin-left: -85px;"/>
              <span class="invalid-feedback" id="err_fname"><input type="button" name="check-store-unique" class="btn debg_color"onclick="checkStoreName()" value="{{ __('users.verify_btn')}}" style="margin-left: 335px;margin-top: -55px;padding: 9px 16px !important;" /> @if($errors->has('store_name')) {{ $errors->first('store_name') }}@endif </span>
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

/*function to check unique store name
* @param : store name
*/
function checkStoreName(){

    var store_name= $("#store_name").val();
    if(store_name!=''){
        $.ajax({
          url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name,
          type: 'get',
          data: { },
          success: function(output){
            if(output !=''){
             alert(output);
            }else{
                alert(store_name_is_verified);
            }
            }
        });
    }else{

     alert(please_enter_store_name)
    }
}
</script>

@endsection
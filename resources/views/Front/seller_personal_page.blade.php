@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
 textarea.form-control.login_input {
    color: #222222 !important;
    font-weight: 300 !important;
}
i.fas.fa-exclamation-triangle, i.fas.fa-check-circle {
    color: #ffcc00!important;
}

</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
    @include ('Front.alert_messages')
    <div class="seller_info">
		<div class="card-header row seller_header">
			  <h2 class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('users.seller_personal_page_menu')}} </h2>
			  <!-- <hr class="heading_line"> -->
		</div>
    <div class="store_eye_icon">
       @php 
       $seller_link = $seller_link.''.'?frompage=1';
       @endphp
        <a href="{{$seller_link}}" target="_blank"><span class="visa_img"><i class="fa fa-eye" aria-hidden="true"></i></span> &nbsp;{{ __('users.see_show_label')}} </a>
       
    </div>
        <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data">
            @csrf

    
       <div class="col-sm-6 order-sm2">
       
        <br>

        
        <input type="hidden" name="seller_id" value="{{$seller_id}}" class="seller_id" id="seller_id">
          <div class="login_box seller_mid_cont">
         
            <div class="form-group">
              <label>{{ __('lang.store_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_information" id="" rows="10" cols="20" placeholder="{{ __('users.butik_info_placeholder')}}" 
              value="" tabindex="2">@if(!empty($details->store_information)) {{$details->store_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('store_information')) {{ $errors->first('store_information') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.payment_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="payment_policy" id="" 
              placeholder="{{ __('users.payment_policy_placeholder')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->payment_policy)) {{$details->payment_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('payment_policy')) {{ $errors->first('payment_policy') }}@endif </span>
            </div>
            <?php /*
            <div class="form-group">
              <label>{{ __('lang.booking_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="booking_policy" id="" 
              placeholder="{{ __('lang.booking_policy')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->booking_policy)) {{$details->booking_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('booking_policy')) {{ $errors->first('booking_policy') }}@endif </span>
            </div>*/?>
            <div class="form-group">
              <label>{{ __('lang.return_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="return_policy" id="" 
              placeholder="{{ __('users.return_policy_placeholder')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->return_policy)) {{$details->return_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('return_policy')) {{ $errors->first('return_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('lang.shipping_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="shipping_policy" id="" 
              placeholder="{{ __('users.shipping_policy_placeholder')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->shipping_policy)) {{$details->shipping_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('shipping_policy')) {{ $errors->first('shipping_policy') }}@endif </span>
            </div>
            <div class="form-group">
              <label>{{ __('users.cancellation_policy')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="cancellation_policy" id="" 
              placeholder="{{ __('users.cancellation_policy_placeholder')}}" rows="10" cols="20"
              value="" tabindex="2">@if(!empty($details->cancellation_policy)) {{$details->cancellation_policy}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('cancellation_policy')) {{ $errors->first('cancellation_policy') }}@endif </span>
            </div>
            <?php /*<div class="form-group">
              <label>{{ __('lang.other_information')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="other_information" id="" rows="10" cols="20" placeholder="{{ __('lang.other_information')}}" 
              value="" tabindex="2">@if(!empty($details->other_information)) {{$details->other_information}} @endif</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('other_information')) {{ $errors->first('other_information') }}@endif </span>
            </div>*/?>
           
          </div>
        </div>
        <div class="col-sm-6 order-sm1">
        
          <div class="login_box">
          
             <div class="form-group butik_profile_store_div">
              <label>{{ __('lang.store_name')}} <span class="de_col"></span></label>
              <br>
              <input maxLength="20" type="text" class="alpha-only form-control store_name butik_profile_store_name" id="store_name" name="store_name" 
              placeholder="{{ __('lang.store_name')}} " value="@if(!empty($details->store_name)) {{$details->store_name}} @endif" style="width: 70%;" />
				<input type="button" name="check-store-unique" class="btn debg_color verify-store" onclick="checkStoreName()" value="{{ __('users.verify_btn')}}" style="margin-left: 0px;" />  
				<span class="invalid-feedback" id="err_store_name" style="position: relative;"> </span>
            </div>
            
            <div class="loader"></div>
            <div class="form-group increment cloned">
              <label>{{ __('users.seller_header_img_label')}}</label>
              @php
              if(!empty($details->header_img))
              {
                echo '<div class="row">';
                echo '<div class="col-md-4 banner_existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->header_img.'" class="banner_preview_img" id="previewBanner"><a href="javascript:void(0);" class="remove_banner_image"><i class="fas fa-trash"></i></a></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }else{
               echo '<div class="bannerImage" style="display: none;">';
              echo '<div class="row">';
                echo '<div class="col-md-4 banner_existing-images"><img src="" class="banner_preview_img" id="previewBanner"><a href="javascript:void(0);" class="remove_banner_image"><i class="fas fa-trash"></i></a></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div></div>';
            }
              @endphp

              <input type="file" name="header_img" id="bannerInp" class="form-control" value="">
              <p class="seller-banner-info seller-logo-banner-info">({{ __('users.seller_banner_info')}})</p>
              
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
                echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->logo.'" class="seller_logo" id="previewLogo"><a href="javascript:void(0);" class="remove_logo_image"><i class="fas fa-trash"></i></a></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }else{

              echo '<div class="logoImage" style="display: none;">';
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="" class="seller_logo" id="previewLogo"><a href="javascript:void(0);" class="remove_logo_image"><i class="fas fa-trash"></i></a></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div></div>';
                
            }
              @endphp

              <input type="file" name="logo" id="logoInp" class="form-control" value="">
              <p class="seller-logo-info seller-logo-banner-info">({{ __('users.seller_logo_info')}})</p>
              <div class="text-danger">{{$errors->first('filename')}}</div>
              <div class="input-group-btn text-right"> 
              </div>
            </div>

                     
              
          </div>
        </div>
        
        <div class="col-md-9 pull-right margin_bottom_class tj-personal-action">
          <div class="close_store">
            <a href="javascript:void(0)" onclick="ConfirmCloseStoreFunction('{{ route('frontShopClose',base64_encode($details->user_id)) }}');" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('users.close_store_btn')}}</a>
          </div> 
          <button class="btn btn-black debg_color login_btn" id="update_seller_info">{{ __('lang.update_btn')}}</button>
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

  /*function to check unique store name
* @param : store name
*/
function checkStoreName(){

    var store_name= $("#store_name").val();
    var seller_id = $("#seller_id").val();
	if(store_name.length>=21){
		$("#err_store_name").html(store_name_characters_len_err).show();
		return false;
	}
    if(store_name!=''){
        $.ajax({
          url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+seller_id,
          type: 'get',
          data: {},
          success: function(output){
            if(output !=''){
             showErrorMessage(output);
            }else{
                //alert(store_name_is_verified);
                showSuccessMessageReview(store_name_is_verified);
            }
            }
        });
    }else{
      showErrorMessage(please_enter_store_name);
    }
}

  $('#update_seller_info').click(function(e) {  
    
    e.preventDefault();
    let store_name       = $("#store_name").val();
    var maxLength = 21;
    let err = 0;
    var seller_id = $("#seller_id").val();
    if(store_name!=''){
        $.ajax({
          url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+seller_id,
          type: 'get',
          async:false,
          data: {},
          success: function(output){
            if(output !=''){
             showErrorMessage(output,);
             err=1;
            }else{
               err=0;
               // showSuccessMessageReview(store_name_is_verified,);
            }
            }
        });
    }else{
      showErrorMessage(please_enter_store_name,);
      err=1;
    }

    if(store_name==''){
        $("#err_store_name").html(please_enter_store_name).show();
       // $("#err_store_name").parent().addClass('jt-error');
         err = 1; 
      // showErrorMessage(please_enter_store_name)
    } else if(store_name.length>=maxLength){
    $("#err_store_name").html(store_name_characters_len_err).show();
       err = 1; 
    } else  {
      $("#err_store_name").html('');
    }

    if(err == 1)
    {
      return false;
    }
    else
    {
      $('#seller-personal-form').submit();
      return true;
    }

  });
function createCookie(name,value,minutes) {
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime()+(minutes*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else {
        var expires = "";
    }
    document.cookie = name+"="+value+expires+";domain=.tijara.se; path=/";
    //document.cookie = name+"="+value+expires+"; path=/";
}


bannerInp.onchange = evt => {
  const [file] = bannerInp.files
  if (file) {
    $('.bannerImage').css('display','block');
    $('.banner_existing-images').css('display','block');
    previewBanner.src = URL.createObjectURL(file)
    createCookie("seller_banner_preview", URL.createObjectURL(file), 15);
	console.log('seller_banner_preview');
	
  }
}

logoInp.onchange = evt => {
  const [file] = logoInp.files
  if (file) {
    $('.logoImage').css('display','block');
    $('.existing-images').css('display','block');
    previewLogo.src = URL.createObjectURL(file)
    createCookie("seller_logo_preview", URL.createObjectURL(file), 15);
	console.log('seller_logo_preview');
  }
}
function deleteCookie(name) {
    var expires = "; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    document.cookie = name+"=null"+expires+";domain=.tijara.se; path=/";
	console.log('deleted='+name);
}
$('body').on('click', '.remove_banner_image', function () {
	deleteCookie("seller_banner_preview");

    var path = $('#previewBanner').attr('src');
    var Filename= path.split('/').pop();
    $(".loader").css("display","block");

    $.ajax({
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
            url: "{{url('/')}}"+'/remove-banner-image?image_path='+Filename,
            type: 'post',
            data: {},
          success: function(output){
              $(".loader").css("display","none");
             
              if(output.message==0){
                $('.banner_existing-images').css('display','none');
                $(this).remove();
              }
             
          }
    });
});

$('body').on('click', '.remove_logo_image', function () {
	deleteCookie("seller_logo_preview");
    var path = $('#previewLogo').attr('src');
    var Filename= path.split('/').pop();
    $(".loader").css("display","block");

    $.ajax({
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
            url: "{{url('/')}}"+'/remove-logo-image?image_path='+Filename,
            type: 'post',
            data: {},
          success: function(output){
              $(".loader").css("display","none");
             
              if(output.message==0){
                $('.existing-images').css('display','none');
                $(this).remove();
              }
             
          }
    });
});
$(".alpha-only").on("input", function(e){
  var regexp = /[^a-zA-Z0-9]/g;
  if($(this).val().match(regexp)){
    $(this).val( $(this).val().replace(regexp,'') );
  }
});
</script>

@endsection
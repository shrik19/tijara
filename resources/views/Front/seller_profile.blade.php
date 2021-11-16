@extends('Front.layout.template')
@section('middlecontent')
<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-2 tijara-sidebar ">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content ">
      @if(!empty($package_exp_msg))
          <div class="alert alert-danger" role="alert">
            <a href="{{route('frontSellerPackages')}}" style="color: #a94442">{{$package_exp_msg}}</a>
          </div>
      @endif
    <form id="seller-profile-form" action="{{route('frontSellerProfileUpdate')}}" method="post" enctype="multipart/form-data">
            @csrf
      @include ('Front.alert_messages')
      <div class="col-md-12">
        <div class="seller_info">
	    <div class="card-header row seller_header">
			  <h2 class="page_heading">{{ __('users.profile_update_title')}}</h2>
			  <!-- <hr class="heading_line"> -->
		 </div>  
        <div class="login_box seller_mid_cont" style="margin-top: 40px;">
          
            <h2 class="col-md-12 contact-info seller_mid_header">{{ __('users.contact_person')}}</h2>
          
            <input type="hidden" name="role_id" value="{{$role_id}}">
            <p class="contact_person_head" style="margin-top: 40px;margin-left: 16px;margin-bottom: 20px;">Kontaktperson:</p>

            <div class="form-group col-md-6">
             <!--  <label>{{ __('users.first_name_label')}}<span class="de_col">*</span></label> -->
              <input type="text" class="form-control ge_input" name="fname" id="fname" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('fname')) ?  old('fname') : $sellerDetails[0]->fname}}">
              <span class="invalid-feedback" id="err_fname">@if($errors->has('fname')) {{ $errors->first('fname') }}@endif </span>
            </div>
            <div class="form-group  col-md-6">
              <!-- <label>{{ __('users.phone_number_label')}}</label> -->
              <!-- <span style="margin-top: 10px;" class="col-md-2">+46</span> -->
              <input type="text" class="form-control ge_input" name="phone_number" id="phone_number" placeholder="{{ __('users.phone_number_label')}}" value="{{ (old('phone_number')) ? old('phone_number') : $sellerDetails[0]->phone_number}}">
              <span class="invalid-feedback" id="err_phone_number">@if($errors->has('phone_number')) {{ $errors->first('phone_number') }}@endif</span>
            </div>

            <div class="form-group  col-md-6">
              <!-- <label>{{ __('users.last_name_label')}}<span class="de_col">*</span></label> -->
              <input type="text" class="form-control ge_input" name="lname" id="lname" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('lname')) ?  old('lname') : $sellerDetails[0]->lname}}">
              <span class="invalid-feedback" id="err_lname">@if($errors->has('lname')) {{ $errors->first('lname') }}@endif</span>
            </div>

            
            <div class="form-group col-md-6">
             <!--  <label>{{ __('users.address_label')}}</label> -->
              <input type="text" class="form-control ge_input" name="address" id="address" placeholder="{{ __('users.address_label')}}" value="{{ (old('address')) ? old('address') : $sellerDetails[0]->address}}">
              
              <span class="invalid-feedback" id="err_address">@if($errors->has('address')) {{ $errors->first('address') }}@endif</span>
            </div> 
            <div class="form-group  col-md-6">
             <!--  <label>{{ __('users.email_label')}}<span class="de_col">*</span></label> -->
              <input type="email" class="form-control ge_input" name="email" id="email" placeholder="{{ __('users.email_label')}}" value="{{ (old('email')) ? old('email') : $sellerDetails[0]->email}}">
              <span class="invalid-feedback" id="err_email">@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>
            </div>          

            
            <div class="form-group col-md-6">
              <!-- <label>{{ __('users.postal_code_label')}}</label> -->
              <input type="text" class="form-control ge_input" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="{{ (old('postcode')) ? old('postcode') : $sellerDetails[0]->postcode}}">
              <span class="invalid-feedback" id="err_address">@if($errors->has('postcode')) {{ $errors->first('postcode') }}@endif</span>
            </div>
           
            <?php /*
            <div class="form-group  col-md-6" style="margin-top: ">
              <label>{{ __('users.city_label')}}</label>
              <input type="text" class="form-control ge_input" name="city" id="city" placeholder="{{ __('users.city_label')}}" value="{{ (old('city')) ? old('city') : $sellerDetails[0]->city}}">
              <span class="invalid-feedback" id="err_city">@if($errors->has('city')) {{ $errors->first('city') }}@endif</span>
            </div>
            */?>
            <div style="margin-top: 40px;">
              <hr class="row solid-horizontal-line">
            </div>

            <h2 class="col-md-12" style="margin-top: 40px;margin-bottom: 20px;">{{ __('users.shipping_setting')}}</h2>
            <div class="form-group col-md-6" id="shipping_method_ddl_div">
              <label>{{ __('users.shipping_method_label')}}</label>
             <select class="form-control ge_input" name="shipping_method_ddl" id="shipping_method_ddl">
               <option value="">{{ __('users.select_shipping_method')}}</option>
               <option  <?php if($sellerDetails[0]->shipping_method ==  __('users.flat_shipping_charges')){ echo "selected"; } ?>>{{ __('users.flat_shipping_charges')}}</option>
               <option <?php if($sellerDetails[0]->shipping_method ==  __('users.prcentage_shipping_charges')){ echo "selected"; } ?>>{{ __('users.prcentage_shipping_charges')}}</option>
             </select>
            </div>

            <div class="form-group col-md-6" id="shipping_charges_div">
              <label>{{ __('users.shipping_charges_label')}}</label>
              <input type="text" class="form-control ge_input" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="{{ (old('shipping_charges')) ? old('shipping_charges') : $sellerDetails[0]->shipping_charges}}">
            </div>

            <div class="form-group col-md-6">
            <label>{{ __('users.free_shipping_label')}} </label>
              <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($sellerDetails[0]->free_shipping ==  "free_shipping"){ echo "checked"; } ?> style="float: right">
           
            </div>

            <div class="form-group col-md-6">
              <label> {{ __('users.pick_from_store')}}  </label>
              <div class="row">
                <div class="col-md-1" class="is_pick_from_store">
                 <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  <?php if($sellerDetails[0]->is_pick_from_store ==  "1"){ echo "checked"; } ?>>
               </div>
               <div class="col-md-11">
                <input type="text" class="form-control ge_input" name="store_pick_address" id="store_pick_address" placeholder="{{ __('users.pick_up_address')}}" value="{{ (old('store_pick_address')) ? old('store_pick_address') : $sellerDetails[0]->store_pick_address}}">
              </div>
              </div>              
            </div>
            
         
              <?php /*
           
          
            <div class="form-group col-md-6">
              <label>{{ __('users.klarna_username_label')}}</label>
              <input type="text" class="form-control ge_input" name="klarna_username" id="klarna_username" placeholder="{{ __('users.klarna_username_label')}}" value="{{ (old('klarna_username')) ? old('klarna_username') : $sellerDetails[0]->klarna_username}}">
              <span class="invalid-feedback">@if($errors->has('klarna_username')) {{ $errors->first('klarna_username') }}@endif</span>
            </div>

            <div class="form-group col-md-6">
              <label>{{ __('users.klarna_password_label')}}</label>
              <input type="password" class="form-control ge_input" name="klarna_password" id="klarna_password" placeholder="{{ __('users.klarna_password_label')}}" value="{{ (old('klarna_password')) ? old('klarna_password') : $sellerDetails[0]->klarna_password}}">
              <span class="invalid-feedback">@if($errors->has('klarna_password')) {{ $errors->first('klarna_password') }}@endif</span>
            </div>

            */?>
            <hr class="row solid-horizontal-line">
             <h2 class="col-md-12"  id="scroll_to_payment_details" style="margin-top: 40px;margin-bottom: 20px;">{{ __('users.payment_setting')}}</h2>
             <div class="col-md-12">
              <div class="col-md-6" style="margin-left: -15px;">

                <div class="form-group">
                  <input type="text" class="form-control ge_input" name="card_fname" id="card_fname" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('card_fname')) ?  old('card_fname') : $sellerDetails[0]->card_fname}}">
                  <span class="invalid-feedback" id="err_card_fname">@if($errors->has('card_fname')) {{ $errors->first('card_fname') }}@endif </span>
                </div>

                 <div class="form-group">
                  <input type="text" class="form-control ge_input" name="card_lname" id="card_lname" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('card_lname')) ?  old('card_lname') : $sellerDetails[0]->card_lname}}">
                  <span class="invalid-feedback" id="err_card_lname">@if($errors->has('card_lname')) {{ $errors->first('card_lname') }}@endif</span>
                </div>

                <div class="form-group">
                  <input type="text" class="form-control ge_input" name="card_number" id="card_number" placeholder="{{ __('users.card_number_label')}}" value="{{ (old('card_number')) ?  old('card_number') : $sellerDetails[0]->card_number}}">
                  <span class="invalid-feedback" id="card_number">@if($errors->has('card_number')) {{ $errors->first('card_number') }}@endif</span>
                </div>

                <div class="form-group">
                  <input maxLength="5" type="text" class="form-control ge_input" name="card_exp_date" id="card_exp_date" placeholder="{{ __('users.card_expiry_date_lable')}}" value="{{ (old('card_exp_date')) ?  old('card_exp_date') : $sellerDetails[0]->card_exp_date}}">
                  <span class="invalid-feedback" id="card_exp_date">@if($errors->has('card_exp_date')) {{ $errors->first('card_exp_date') }}@endif</span>
                </div>

                <div class="form-group">
                  <input maxlength="3" type="text" class="form-control ge_input" name="card_security_code" id="card_security_code" placeholder="{{ __('users.security_code_label')}}" value="{{ (old('card_security_code')) ?  old('card_security_code') : $sellerDetails[0]->card_security_code}}">
                  <span class="invalid-feedback" id="card_security_code">@if($errors->has('card_security_code')) {{ $errors->first('card_security_code') }}@endif</span>
                </div>
                
              </div>
             </div>
             

        </div>

      </div>
      
        <div style="text-align: center">
          <button class="btn btn-black debg_color seller-profile-update login_btn">{{ __('lang.update_btn')}}</button>
          <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16">{{ __('lang.cancel_btn')}}</a>
        </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div> <!-- /container -->
</div>



<style>
.ge_input {
  width: 100%;
  font-weight: 300 !important;
  color: #999 !important;
}
</style>
 
<script>
  function showErrorMessage(strContent,redirect_url = '')
{
  $.alert({
      title: 'Oops!',
      content: strContent,
      type: 'red',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-times-circle",
      buttons: {
        Ok: function () {
            if(redirect_url != '')
            {
              if(redirect_url == 'reload')
              {
                location.reload(true);
              }
              else
              {
                window.location.href = redirect_url;
              }
            }
        },
      }
    });
}
  /*function to check unique store name
* @param : store name
*/
  function checkStoreName(inputText){

    var store_name= inputText.value;
     $.ajax({
      url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+$('#hid').val(),
      type: 'get',
      data: { },
      success: function(output){
        if(output !='')
         showErrorMessage(output);
        }
    });
  }

  var seller_account_freeze     = "{{trans('errors.seller_account_freeze')}}";
  var is_disabled = $("#disable_side_menu").val();
  
  if(is_disabled==1 && ( $("#card_fname").val() =="" || $("#card_lname").val() =="" || $("#card_number").val() =="" || $("#card_security_code").val() =='' ||  $("#card_exp_date").val() =="" )){
     showErrorMessage(seller_account_freeze);

    // Handler for .ready() called.
    $('html, body').animate({
        scrollTop: $('#scroll_to_payment_details').offset().top
    }, 'slow');

    $('.seller_cat_list li.make_disabled').on('click', function(event) {
      event.preventDefault();
    });
  }

  $(document).ready(function () {
    $('#phone_number').mask('00 000 00000');
  });


</script>


@endsection
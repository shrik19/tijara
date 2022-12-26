@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
  .sidebar_menu {
    margin-left: -14px !important;
  }
</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
<div class="container-inner-section-1 tjd-sellcontainer">
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
          <h2 class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('users.payment_btn')}} </h2>
        </div> <!--  seller_header -->
        
        <div class="tj-paymentrow" style="margin-top: 40px;">
          
        <div class="col-md-4 tj-mobnopad">
          <div class="info" style="background-color: #e6f2ff;padding: 20px;margin-bottom: 10px;">{{ __('users.payment_method_info_seller_backend')}}</div>
        </div>
        <div class="col-md-6 tj-mobnopad">
          <h3>{{ __('users.payment_method_head')}}</h3>
          <form method="POST" action="{{route('frontStorePaymentDetails')}}" class="needs-validation seller-payment-form" novalidate="">
          @csrf

            <p class="payment_method_title">{{ __('users.klarna_pament_label')}}</p>
            <div class="login_box payment_detail_box klarna_payment" style="margin-top: 20px;">
              <div class="payment-lock-icon"><i class="fa fa-lock payment_lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/klarna-payment-logo.png" width="90"></p>

              <div class="form-group">
                <input type="hidden" name="selected_package" id="selected_package" value="{{$selected_package}}">
                <input type="text" class="form-control" name="klarna_username" id="klarna_username" placeholder="{{ __('users.klarna_username_label')}}" value="{{ (old('klarna_username')) ? old('klarna_username') : @$sellerDetails[0]->klarna_username}}">
                <span class="register_credentials_ex">T.ex. K1234567_dc0a9aclc532</span
                <span class="invalid-feedback" style="position: relative;">@if($errors->has('klarna_username')) {{ $errors->first('klarna_username') }}@endif</span>
              </div>

              <div class="form-group">
                <input type="password" class="form-control" name="klarna_password" id="klarna_password" placeholder="{{ __('users.klarna_password_label')}}" value="{{ (old('klarna_password')) ? old('klarna_password') : base64_decode($sellerDetails[0]->klarna_password)}}">
                <span  class="register_credentials_ex">T.ex. abcDEF123ghij567</span>
                <span class="invalid-feedback">@if($errors->has('klarna_password')) {{ $errors->first('klarna_password') }}@endif</span>
              </div>

              <div class="payment_explanation_text">{{ __('messages.klarna_description_step_1')}} <a target="_blank" href="https://auth.eu.portal.klarna.com/auth/realms/merchants/protocol/openid-connect/auth?client_id=merchant-portal&redirect_uri=https%3A%2F%2Fportal.klarna.com%2F%3F_ga%3D2.246934660.126610679.1646642154-1734669118.1646642154&state=a4c4cce6-9787-49a3-9131-ca7362164c5c&response_mode=fragment&response_type=code&scope=openid&nonce=f23358b1-1fb6-4f06-87e3-3ca8281be740&code_challenge=HCL30L02B40414ZxJeU5kOw8XqhetiyzuAgBZeqnaX0&code_challenge_method=S256">Här</a></br>{{ __('messages.klarna_description_step_2')}}</br>{{ __('messages.klarna_description_step_3')}}</br>{{ __('messages.klarna_description_step_4')}}<br><br>{{__('messages.contact_klarna_support_description')}}
              </div>
            </div>


            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.easy_peyment_title')}}</p>

            <div class="login_box payment_detail_box swish_payment" style="margin-top: 20px;">
              <div class="payment-lock-icon"><i class="fa fa-lock payment_lock swish_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/swish-payment-logo.png" class="register_swish_logo"></p>
              <div class="form-group" style="display: flex;">
                <input type="phone_number" class="form-control login_input " name="swish_number" id="seller_swish_number" placeholder="swish number" value="{{ (old('seller_swish_number')) ? old('seller_swish_number') : $sellerDetails[0]->seller_swish_number}}" style="margin-left: 10px;">
              </div>

              <div class="payment_explanation_text">{{ __('messages.swish_description_step_1')}}</br>{{ __('messages.swish_description_step_2')}}</div> 

            </div>

            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.stripe_pament_label')}}</p>
            <div class="login_box payment_detail_box stripe_payment" style="margin-top: 20px;">
              <div class="payment-lock-icon"><i class="fa fa-lock payment_lock stripe_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/stripe-payment-logo.png" width="200"></p>
              <div class="form-group">
                <input type="text" class="form-control" name="strip_api_key" id="strip_api_key" placeholder="{{ __('users.stripe_api_key_label')}}" value="{{ (old('strip_api_key')) ? old('strip_api_key') : $sellerDetails[0]->strip_api_key}}">
                <span class="register_credentials_ex">Hemlig nyckel</span>
                <span class="invalid-feedback" style="position: relative;">@if($errors->has('strip_api_key')) {{ $errors->first('strip_api_key') }}@endif</span>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name="strip_secret" id="strip_secret" class="stripe_payment" placeholder="{{ __('users.stripe_secret_label')}}" value="{{ (old('strip_secret')) ? old('strip_secret') : $sellerDetails[0]->strip_secret}}">
                <span class="register_credentials_ex">Publicerbar nyckel</span>
                <span class="invalid-feedback">@if($errors->has('strip_secret')) {{ $errors->first('strip_secret') }}@endif</span>
              </div>

              <div class="payment_explanation_text">{{ __('messages.strip_description_step_1')}}<a target="_blank" href=" https://dashboard.stripe.com/login">Här</a></br>{{ __('messages.strip_description_step_2')}}</br>{{ __('messages.strip_description_step_3')}}</br></br>{{ __('messages.register_stripe_description')}}
              </div>  

            </div>

              <div class="tijara-content tj-personal-action" style="margin-bottom: 20px;">
                <button type="submit" name="btnCountryCreate" id="btnSaveDetails" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
                <a href="{{url()->previous()}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>
              </div>
        </form>
        </div>

        <div class="col-md-2"></div>
      </div>
      </div><!--/seller_info -->
    </div><!--/col-md-10  /tijara-content-->
  </div> <!-- /row -->
</div><!-- /container-inner-section-1 -->
</div><!-- /container-fluid -->
</div><!-- /mid-section -->
<script type="text/javascript">
  
$(document).ready(function(){
 
  var selected_package_name =$('#selected_package').val();
 
  if(selected_package_name == "Tijara Bas"){
    //$('.klarna_payment_detail_box').attr('disabled', 'disabled');
    $('.klarna_payment :input').attr('disabled', true);
    $('.stripe_payment :input').attr('disabled', true);
    $(".swish_payment_lock").hide();
  }else{
    $(".payment-lock-icon").hide();
   /* $(".swish_payment_lock").hide();
    $(".stripe_payment_lock").hide();*/
  }

  
  $("#btnSaveDetails").on("click", function(){
    var error = 0;
    var swish_number    = $("#seller_swish_number").val();
    let klarna_username       = $("#klarna_username").val();
    let klarna_password     = $("#klarna_password").val();  
    let strip_api_key           = $("#strip_api_key").val(); 
    let strip_secret        = $("#strip_secret").val();  

    if ((klarna_username != '' && klarna_password != '') || (swish_number != '') || (strip_api_key != '' && strip_secret != '')){
     error = 0;
    
    }else
    {
      error = 1;
      showErrorMessage(please_add_payment_details);
      return false;
    }




   /* if(swish_number !=''){
        if($("#is_swish_number").is(':checked')){
            error = 0;
        } else{
          showErrorMessage(please_check_swish_number);
          error = 1;
        }

    }*/

    if(error == 1)
    {
    return false;
    }
    else
    {
    $('#seller-payment-form').submit();
    return true;
    }
  
  });
});
</script>
@endsection
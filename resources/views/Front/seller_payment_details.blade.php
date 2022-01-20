@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
<div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-12">
    <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>

    <div class="col-md-10 tijara-content">
      <div class="seller_info">

      <div class="card">
        <div class="card-header row seller_header">
          <h2>{{ __('users.payment_btn')}} </h2>
        </div> <!--  seller_header -->
         <div class="seller_mid_cont"  style="margin-top: 40px;">
          @include ('Front.alert_messages')
          <div class="col-md-6">
            <h3>{{ __('users.payment_method_head')}}</h3>
             <form method="POST" action="{{route('frontStorePaymentDetails')}}" class="needs-validation seller-payment-form" novalidate="">
              @csrf

            <p class="payment_method_title">{{ __('users.klarna_pament_label')}}</p>
            <div class="login_box payment_detail_box klarna_payment" style="margin-top: 20px;">

                <div class="payment-lock-icon"><i class="fa fa-lock payment_lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/klarna-payment-logo.png" width="90" height="80"></p>
              <div class="form-group">
              <input type="hidden" name="selected_package" id="selected_package" value="{{$selected_package}}">
              <input type="text" class="form-control" name="klarna_username" id="klarna_username" placeholder="{{ __('users.klarna_username_label')}}" value="{{ (old('klarna_username')) ? old('klarna_username') : $sellerDetails[0]->klarna_username}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('klarna_username')) {{ $errors->first('klarna_username') }}@endif</span>
                </div>

              <div class="form-group">
                   <!-- <label>{{ __('users.klarna_password_label')}}</label> -->
              <input type="password" class="form-control" name="klarna_password" id="klarna_password" placeholder="{{ __('users.klarna_password_label')}}" value="{{ (old('klarna_password')) ? old('klarna_password') : $sellerDetails[0]->klarna_password}}">
              <span class="invalid-feedback">@if($errors->has('klarna_password')) {{ $errors->first('klarna_password') }}@endif</span>
                </div>

				<div class="payment_explanation_text">{{ __('messages.klarna_description_step_1')}}</br>{{ __('messages.klarna_description_step_2')}}</br>{{ __('messages.klarna_description_step_3')}}</br>{{ __('messages.klarna_description_step_4')}}</div>
            </div>
            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.easy_peyment_title')}}</p>
            <div class="login_box payment_detail_box swish_payment" style="margin-top: 20px;">
          
                <div class="payment-lock-icon"><i class="fa fa-lock payment_lock swish_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/swish-payment-logo.png" width="90" height="80"></p>
              <?php /*
              <div class="form-group">
              <input type="text" class="form-control" name="swish_api_key" id="swish_api_key" placeholder="{{ __('users.swish_api_key_label')}}" value="{{ (old('swish_api_key')) ? old('swish_api_key') : $sellerDetails[0]->swish_api_key}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('swish_api_key')) {{ $errors->first('swish_api_key') }}@endif</span>
                </div>

              <div class="form-group">
              <input type="text" class="form-control" name="swish_merchant_account" id="swish_merchant_account" placeholder="{{ __('users.swish_merchant_account_label')}}" value="{{ (old('swish_merchant_account')) ? old('swish_merchant_account') : $sellerDetails[0]->swish_merchant_account}}">
              <span class="invalid-feedback">@if($errors->has('swish_merchant_account')) {{ $errors->first('swish_merchant_account') }}@endif</span>
              </div>


            <div class="form-group">
              <input type="text" class="form-control" name="swish_client_key" id="swish_client_key" placeholder="{{ __('users.swish_client_key_label')}}" value="{{ (old('swish_client_key')) ? old('swish_client_key') : $sellerDetails[0]->swish_client_key}}">
              <span class="invalid-feedback">@if($errors->has('swish_client_key')) {{ $errors->first('swish_client_key') }}@endif</span>
              </div> */?>
              
              <div class="form-group" style="display: flex;">
                <?php 
                  $checked = "";
				  if(!empty($sellerDetails[0]->is_swish_number)){
					  if($sellerDetails[0]->is_swish_number == 1) {
						$checked = "checked";
					  }
				  }
                ?>
               <input type="checkbox" name="is_swish_number" id="is_swish_number" class="swish_number payment_radio" value="1" style="margin-top: 10px;" {{$checked}}> 
               <input type="phone_number" class="form-control login_input " name="swish_number" id="seller_swish_number" placeholder="swish number" value="{{ (old('seller_swish_number')) ? old('seller_swish_number') : $sellerDetails[0]->seller_swish_number}}" style="margin-left: 10px;">
              </div>
			         
               <div class="payment_explanation_text">{{ __('messages.swish_description_step_1')}}</br>{{ __('messages.swish_description_step_2')}}</br>{{ __('messages.swish_description_step_3')}}</div> 

            </div>
            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.stripe_pament_label')}}</p>
            <div class="login_box payment_detail_box stripe_payment" style="margin-top: 20px;">
                <div class="payment-lock-icon"><i class="fa fa-lock payment_lock stripe_payment_lock" aria-hidden="true"></i></div>
              <p><img src="{{url('/')}}/uploads/Images/stripe-payment-logo.png" width="200" height="50"></p>
              <div class="form-group">
                  
              <input type="text" class="form-control" name="strip_api_key" id="strip_api_key" placeholder="{{ __('users.stripe_api_key_label')}}" value="{{ (old('strip_api_key')) ? old('strip_api_key') : $sellerDetails[0]->strip_api_key}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('strip_api_key')) {{ $errors->first('strip_api_key') }}@endif</span>
                </div>

              <div class="form-group">
              <input type="text" class="form-control" name="strip_secret" id="strip_secret" class="stripe_payment" placeholder="{{ __('users.stripe_secret_label')}}" value="{{ (old('strip_secret')) ? old('strip_secret') : $sellerDetails[0]->strip_secret}}">
              <span class="invalid-feedback">@if($errors->has('strip_secret')) {{ $errors->first('strip_secret') }}@endif</span>
              </div>

              <div class="payment_explanation_text">{{ __('messages.strip_description_step_1')}}</br>{{ __('messages.strip_description_step_2')}}</br>{{ __('messages.strip_description_step_3')}}</div>  
             
            </div>
             <div style="margin-top: 30px;">
             
                <button type="submit" name="btnCountryCreate" id="btnSaveDetails" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
                <a href="{{url()->previous()}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>

               </div>
                </form>
          </div>
          <div class="col-md-4">
              <div class="info" style="background-color: #e6f2ff;padding: 20px;margin-bottom: 10px;">{{ __('users.payment_method_info')}}</div>
          </div>
            <div class="col-md-2"></div>
        
            
         </div> <!-- seller_mid_cont -->
       
      </div> <!-- card -->
  
      </div><!--/seller_info -->
    </div><!--/col-md-10  /tijara-content-->
    </div><!--/col-md-12 -->
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
    $(".swish_payment_lock").removeClass("fa-lock");
  }else{
    $(".klarna_payment_lock").removeClass("fa-lock");
    $(".swish_payment_lock").removeClass("fa-lock");
    $(".stripe_payment_lock").removeClass("fa-lock");
  }

  
  $("#btnSaveDetails").on("click", function(){
    var error = 0;
    var swish_number = $("#seller_swish_number").val();
    if(swish_number !=''){
        if($("#is_swish_number").is(':checked')){
            error = 0;
        } else{
          showErrorMessage(please_check_swish_number);
        }

    }
  });
});
</script>
@endsection
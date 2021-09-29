@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
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
             <form method="POST" action="{{route('frontStorePaymentDetails')}}" class="needs-validation" novalidate="">
              @csrf

            <p class="payment_method_title">{{ __('users.klarna_pament_label')}}</p>
            <div class="login_box klarna_payment_detail_box" style="margin-top: 20px;">

                <div class="payment-lock-icon"><i class="fa fa-lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p>Klarna</p>
              <div class="form-group">
                  
              <input type="text" class="form-control" name="klarna_username" id="klarna_username" placeholder="{{ __('users.klarna_username_label')}}" value="{{ (old('klarna_username')) ? old('klarna_username') : $sellerDetails[0]->klarna_username}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('klarna_username')) {{ $errors->first('klarna_username') }}@endif</span>
                </div>

              <div class="form-group">
                   <!-- <label>{{ __('users.klarna_password_label')}}</label> -->
              <input type="password" class="form-control" name="klarna_password" id="klarna_password" placeholder="{{ __('users.klarna_password_label')}}" value="{{ (old('klarna_password')) ? old('klarna_password') : $sellerDetails[0]->klarna_password}}">
              <span class="invalid-feedback">@if($errors->has('klarna_password')) {{ $errors->first('klarna_password') }}@endif</span>
                </div>

                
             
            </div>
            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.easy_peyment_title')}}</p>
            <div class="login_box klarna_payment_detail_box" style="margin-top: 20px;">
          
                <div class="payment-lock-icon"><i class="fa fa-lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p>Swish</p>
              <div class="form-group">
                  
              <input type="text" class="form-control" name="swish_api_key" id="swish_api_key" placeholder="{{ __('users.swish_api_key_label')}}" value="{{ (old('swish_api_key')) ? old('swish_api_key') : $sellerDetails[0]->swish_api_key}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('swish_api_key')) {{ $errors->first('swish_api_key') }}@endif</span>
                </div>

              <div class="form-group">
              <input type="password" class="form-control" name="swish_merchant_account" id="swish_merchant_account" placeholder="{{ __('users.swish_merchant_account_label')}}" value="{{ (old('swish_merchant_account')) ? old('swish_merchant_account') : $sellerDetails[0]->swish_merchant_account}}">
              <span class="invalid-feedback">@if($errors->has('swish_merchant_account')) {{ $errors->first('swish_merchant_account') }}@endif</span>
              </div>


              <div class="form-group">
              <input type="text" class="form-control" name="swish_client_key" id="swish_client_key" placeholder="{{ __('users.swish_client_key_label')}}" value="{{ (old('swish_client_key')) ? old('swish_client_key') : $sellerDetails[0]->swish_client_key}}">
              <span class="invalid-feedback">@if($errors->has('swish_client_key')) {{ $errors->first('swish_client_key') }}@endif</span>
              </div>
                
             
            </div>
            <p class="payment_method_title" style="margin-top: 20px;">{{ __('users.stripe_pament_label')}}</p>
            <div class="login_box klarna_payment_detail_box" style="margin-top: 20px;">
                <div class="payment-lock-icon"><i class="fa fa-lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p>Stripe</p>
              <div class="form-group">
                  
              <input type="text" class="form-control" name="strip_api_key" id="strip_api_key" placeholder="{{ __('users.stripe_api_key_label')}}" value="{{ (old('strip_api_key')) ? old('strip_api_key') : $sellerDetails[0]->strip_api_key}}">
              <span class="invalid-feedback" style="position: relative;">@if($errors->has('strip_api_key')) {{ $errors->first('strip_api_key') }}@endif</span>
                </div>

              <div class="form-group">
              <input type="text" class="form-control" name="strip_secret" id="strip_secret" placeholder="{{ __('users.stripe_account_label')}}" value="{{ (old('strip_secret')) ? old('strip_secret') : $sellerDetails[0]->strip_secret}}">
              <span class="invalid-feedback">@if($errors->has('strip_secret')) {{ $errors->first('strip_secret') }}@endif</span>
                </div>

                
             
            </div>
             <div style="margin-top: 30px;">
             
                <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
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

@endsection
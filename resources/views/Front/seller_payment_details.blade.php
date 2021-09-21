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
            <p class="payment_method_title">{{ __('users.klarna_pament_label')}}</p>
            <div class="login_box klarna_payment_detail_box">
          
              <form method="POST" action="{{route('frontStorePaymentDetails')}}" class="needs-validation" novalidate="">
              @csrf

                <div class="payment-lock-icon"><i class="fa fa-lock klarna_payment_lock" aria-hidden="true"></i></div>
              <p>Klarna</p>
              <div class="form-group">
                  
              <input type="text" class="form-control" name="klarna_username" id="klarna_username" placeholder="{{ __('users.klarna_username_label')}}" value="{{ (old('klarna_username')) ? old('klarna_username') : $sellerDetails[0]->klarna_username}}">
              <span class="invalid-feedback">@if($errors->has('klarna_username')) {{ $errors->first('klarna_username') }}@endif</span>
                </div>

              <div class="form-group">
                   <!-- <label>{{ __('users.klarna_password_label')}}</label> -->
              <input type="password" class="form-control" name="klarna_password" id="klarna_password" placeholder="{{ __('users.klarna_password_label')}}" value="{{ (old('klarna_password')) ? old('klarna_password') : $sellerDetails[0]->klarna_password}}">
              <span class="invalid-feedback">@if($errors->has('klarna_password')) {{ $errors->first('klarna_password') }}@endif</span>
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
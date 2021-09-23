@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-12">
      @if($is_seller==1)
        <div class="col-md-2 tijara-sidebar">
          @include ('Front.layout.sidebar_menu')
        </div>
        <div class="col-md-10 tijara-content">
        <div class="seller_info">
        <div class="card-header row seller_header">
			  <h2>{{ __('users.change_password_title')}}</h2>
			  <hr class="heading_line">
		  </div>
         
      @else
        <div class="col-md-12 tijara-content">
          <div class="seller_info">
          <div class="card">
            <div class="card-header row seller_header">
            <h2>{{ __('users.change_password_title')}} </h2>
        </div></div>
      @endif
      
          @include ('Front.alert_messages')
		  <div class="col-md-12">
        <div class="col-md-2"></div>
          <div class="col-md-5">
          <div class="login_box seller_mid_cont"  style="margin-top: 20px;">
            <form method="POST" action="{{route('frontStorePaymentDetails')}}" class="needs-validation" novalidate="">
              @csrf
                <div class="form-group">
                  <label>{{ __('users.password_label')}}</label>
                  <input type="password" class="form-control ge_input" name="password" required tabindex="1" placeholder="{{ __('users.password_label')}}">
                 <span class="invalid-feedback" id="err_fname">@if($errors->has('password')) {{ $errors->first('password') }}@endif </span>
                </div>

                <div class="form-group" style="margin-top: 25px;">
                  <label>{{ __('users.password_confirmation_label')}}</label>
                  <input type="password" class="form-control ge_input" name="password_confirmation" required tabindex="2" placeholder="{{ __('users.password_confirmation_label')}}">
                  <span class="invalid-feedback" id="err_fname">@if($errors->has('password_confirmation')) {{ $errors->first('password_confirmation') }}@endif </span>
                </div>
               
                @if($is_seller !=1) <div style="margin-top: 30px;margin-bottom: 30px;">@else
              <div style="margin-top: 30px;">
                  @endif
                <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
                <a href="{{url()->previous()}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>

               </div>
                
            </form>
          </div>
        </div>
         @if($is_seller !=1)
          <div class="col-md-5"></div>
         @endif
     </div></div>

</div>
</div>
   </div>

    </div>
</div> <!-- /container -->

@endsection
@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
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
			  <h2 class="page_heading">{{ __('users.change_password_title')}}</h2>
			  
		  </div>
         
      @else
        <div class="col-md-12 tijara-content">
          <div class="seller_info border-none">
          <div class="card">
            <div class="card-header row">
          <!-- <div class="col-md-3"></div>
          <div class="col-md-5"> -->
          <h2 class="page_heading" style="margin-left: 43px;">{{ __('users.change_password_title')}} </h2>
        </div></div>
      @endif
      
          @include ('Front.alert_messages')
		  <div class="col-md-12">
      <!--   <div class="col-md-2"></div> -->
          <div class="col-md-5">

          <div class="login_box seller_mid_cont"  style="margin-top: 20px;">
             
            <form method="POST" action="{{route('frontChangePasswordStore')}}" class="needs-validation seller-chng-pass" novalidate="">
              @csrf
                @if($is_seller==1)
                <div class="form-group">
                  <label class="label_css">{{ __('users.old_password_label')}}</label>
                  <input type="password" class="form-control ge_input" id="old_password" name="old_password" required tabindex="1" placeholder="{{ __('users.old_password_label')}}" onblur="checkPassword(this)">
                 <span class="invalid-feedback" id="err_fname">@if($errors->has('old_password')) {{ $errors->first('old_password') }}@endif </span>
                </div>
                @endif

                <div class="form-group">
                  <label class="label_css">{{ __('users.new_password_label')}}</label>
                  <input type="password" class="form-control ge_input" name="password" required tabindex="1" placeholder="{{ __('users.new_password_label')}}">
                 <span class="invalid-feedback" id="err_fname">@if($errors->has('password')) {{ $errors->first('password') }}@endif </span>
                </div>

                <div class="form-group" style="margin-top: 25px;">
                  <label class="label_css">{{ __('users.password_confirmation_label')}}</label>
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
          <div class="col-md-7"></div>
         @endif
     </div></div>

</div>
</div>
   </div>
	</div>
    </div>
</div> <!-- /container -->
<script type="text/javascript">
  
/*function convertToSlug by its name*/
function checkPassword(inputtxt){
  var password = inputtxt.value;

   $.ajax({
    url: siteUrl+'/check-old-password',
    type: 'get',
    data: { old_password:password},
    success: function(output){
      if(output.error !=''){
        showErrorMessage(output.error);
      }
    }
  });

}
</script>
@endsection
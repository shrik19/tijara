@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
   ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
   .invalid-feedback {
    position: relative;
  }
  .sidebar_menu {
    margin-left: -29px !important;
  }
</style>
<div class="mid-section sellers_top_padding">
	<div class="container-fluid">
		<div class="container-inner-section-1">
			<!-- Example row of columns -->
			<div class="row">
				<div class="col-md-12">
					<input type="hidden" name="is_seller" class="is_seller" value="{{$is_seller}}">
					@if($is_seller==1)
						<div class="col-md-2 tijara-sidebar">
							@include ('Front.layout.sidebar_menu')
						</div>
						<div class="col-md-10 tijara-content">
							@include ('Front.alert_messages')
							<div class="seller_info">
								<div class="card-header row seller_header">
									<h2 @if($is_seller==1) class="seller_page_heading" @endif>{{ __('users.change_password_title')}}</h2>					  
								</div>

					@else
						<div class="tijara-content m-30">
							@include ('Front.alert_messages')
							<div class="seller_info border-none">
								<div class="card">
									<div class="card-header row">
										<!-- <div class="col-md-3"></div>
										<div class="col-md-5"> -->
										<h2 class="page_heading" style="font-size:24px !important;">{{ __('users.change_password_title')}} </h2>
									</div>
								</div>
							@endif
								<div class="col-md-12">
									<div class="row">
										<!--   <div class="col-md-2"></div> -->
										<div class="col-md-5">
											@if($is_seller==1)
												<div class="login_box seller_mid_cont" style="margin-top: 20px;">
											@else
												<div class="login_box" style="margin-top: 20px;">
											@endif	
												<form method="POST" action="{{route('frontChangePasswordStore')}}" class="needs-validation seller-chng-pass" id="seller-change-pass" novalidate="">
													@csrf
													@if($is_seller==1)
													<div class="form-group">
														<label class="label_css">{{ __('users.old_password_label')}}</label>
														<input type="password" class="form-control ge_input" id="old_password" name="old_password" required tabindex="1" placeholder="{{ __('users.old_password_label')}}">
														<span class="invalid-feedback" id="err_old_password">@if($errors->has('old_password')) {{ $errors->first('old_password') }}@endif </span>
													</div>
													@endif

													<div class="form-group">
														<label class="label_css">{{ __('users.new_password_label')}}</label>
														<input type="password" class="form-control ge_input" name="password" id="password" required tabindex="1" placeholder="{{ __('users.new_password_label')}}">
														<span class="invalid-feedback" id="err_password">@if($errors->has('password')) {{ $errors->first('password') }}@endif </span>
													</div>

													<div class="form-group" style="margin-top: 25px;">
														<label class="label_css">{{ __('users.password_confirmation_label')}}</label>
														<input type="password" class="form-control ge_input" name="password_confirmation" required tabindex="2" id="password_confirmation" placeholder="{{ __('users.password_confirmation_label')}}">
														<span class="invalid-feedback" id="err_confirm_password">@if($errors->has('password_confirmation')) {{ $errors->first('password_confirmation') }}@endif </span>
													</div>

													@if($is_seller !=1) 
														<div style="margin-top: 30px;margin-bottom: 30px;">
													@else
														<div style="margin-top: 30px;">
													@endif
															<button type="submit" name="btnCountryCreate" id="changePasswordBtn" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
															<a href="{{url()->previous()}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>
														</div>
												</form>
											</div>
										</div>
										@if($is_seller !=1)
										<div class="col-md-7"></div>
										@endif									
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- /container -->

<script type="text/javascript">

 $("#changePasswordBtn").click(function(){
 	var is_seller = $(".is_seller").val(); 	
 	var password = $("#password").val();
 	var password_confirmation = $("#password_confirmation").val();
 	var error = 0; 	 
	if(is_seller==1){
 		var old_password = $("#old_password").val();
 	
	 	if(old_password==''){
	 		$("#err_old_password").html(required_field_error).show();
		    $("#err_old_password").parent().addClass('jt-error');
		    error = 1;
	 	}else{
	 		$("#err_old_password").html('');
	 		 //checkPassword(old_password)
		  
	 	}

	 	if(old_password !=''){
	 		 $.ajax({
		    url: siteUrl+'/check-old-password',
		    type: 'get',
		    async:false,
		    data: { old_password:old_password},
		    success: function(output){
		      if(output.error !=''){
		        showErrorMessage(output.error);
		        error = 1;
		      }
		    }
		  });
	 
	 	}
	}
	
	if(password == '')
	{
		$("#err_password").html(required_field_error).show();
		$("#err_password").parent().addClass('jt-error');
		error = 1;
	}
	else
	{
		$("#err_password").html('');
	}

   if(password_confirmation == '')
  {
    $("#err_confirm_password").html(required_field_error).show();
    $("#err_confirm_password").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_confirm_password").html('');
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#seller-change-pass').submit();
    return true;
  }
});
/*function convertToSlug by its name*/
/*function checkPassword(inputtxt){
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

}*/
</script>
@endsection
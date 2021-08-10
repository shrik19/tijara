@extends('Front.layout.template')
@section('middlecontent')

<style type="text/css">
   .modal-title{ 
    margin-left: 10px;
    line-height: 1.8;
    color: #03989e;
    margin-bottom: 0;
    font-weight: 700;
    display: block;
  }

  .modal-header .close {
    padding: 1rem 1rem;
    margin-top: -36px;
    font-size: 30px;
}
</style>
<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">
		<div class="register_container">
			<div class="col-md-4 left-section" >
				<div class="register_banner" style="background: #03989e;">
				<div class="register_banner-header">
					<h1>Sveriges första kulturella marknadsplats</h1>
				</div>
				<div class="register_banner-footer text-right">
					<img src="{{url('/')}}/uploads/Images/tijara-image.png" />
				</div> 
				</div>
			</div>

			<div class="col-md-8 right-section">
				<div class="col-md-3"></div>
				<div class="col-md-6 form-section">
					@include ('Front.alert_messages')
					<h2>{{ __('users.create_account_btn')}}</h2>

					<p>{{ __('users.already_have_account')}}
					  <a href="{{url('/')}}/front-login/buyer" class="de_col">{{ __('users.login_label')}}</a>
					</p>					

					<form id="sign-up-form" action="{{url('/')}}/do-register" method="post">
						@csrf
						<input type="hidden" name="role_id" value="{{$role_id}}">

						<div class="form-group">
						  <label>{{ __('users.email_label')}} <span class="de_col">*</span></label>
						  <input type="email" class="form-control login_input" name="email" id="email" value="{{ old('email')}}" placeholder="{{ __('users.email_label')}}">
						  <span class="invalid-feedback" id="err_email" style="">@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>
						</div>

						<div class="form-group">
						  <label>{{ __('users.password_label')}}<span class="de_col">*</span></label>
						  <input type="password" class="form-control login_input" name="password" id="password" value="{{ old('password')}}" placeholder="{{ __('users.password_label')}}">
						  <span class="invalid-feedback" id="err_password" style="">@if($errors->has('password')) {{ $errors->first('password') }}@endif</span>
						</div>

						<div class="form-group">
						  <label>{{ __('users.password_confirmation_label')}}<span class="de_col">*</span></label>
						  <input type="password" class="form-control login_input" name="password_confirmation" value="{{ old('password_confirmation')}}" id="cpassword" placeholder="{{ __('users.password_confirmation_label')}}">
						  <span class="invalid-feedback" id="err_cpassword" style="">@if($errors->has('password_confirmation')) {{ $errors->first('password_confirmation') }}@endif</span>
						</div>

						<div class="remember-section row">
							<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value="">
							<span class="remember-text">{{ __('users.read_and_approve_chk')}}<a href="javascript:void(0)">&nbsp;{{ __('users.terms_of_use')}} &nbsp;</a> {{ __('users.and_chk')}} <a href="javascript:void(0)">{{ __('users.privacy_policy')}}</a></span>		
						</div>
						
						<div class="button-section">
							<div class="tijara-register-button">
								<button class="btn btn-black login_btn gray_color frontregisterbtn">{{ __('users.create_account_btn')}}</button>
							</div>
						</div>						
					  </form>
					
					<div class="seller-link-section">
						<a href="{{route('seller_register')}}" title="{{ __('users.register_as_seller')}}" class="" ><span>{{ __('users.register_as_seller')}}</span> </a><br>
						<a href="{{route('frontLoginSeller')}}" title="{{ __('users.login_as_seller')}}" class="" ><span>{{ __('users.login_as_seller')}}</span> </a>
					</div>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</div>
</div> <!-- /container -->


@endsection
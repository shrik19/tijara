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
		<div class="login_container">
			<div class="col-md-4 left-section" >
				@if(Request::segment(2)=='buyer')
					<div class="login_banner" style="background: #03989e;">
					<div class="login_banner-header">
						<h1>Sveriges första kulturella marknadsplats</h1>
					</div>
					<div class="login_banner-footer text-right">
						<img src="{{url('/')}}/uploads/Images/tijara-image.png" />
					</div> 
					</div>
				@endif
				@if(Request::segment(2)=='seller')
					<div class="login_banner" style="background-image: url({{url('/')}}/uploads/Banner/{{$banner->image}});">
						<div class="login_banner-header">
							<h1>Sveriges första kulturella marknadsplats</h1>
						</div>
						<div class="login_banner-footer text-right">
							<h2>Säljare</h2>
							<img src="{{url('/')}}/uploads/Images/tijara-image.png" />
						</div> 
					</div>
				@endif
			</div>

			<div class="col-md-8 right-section">
				<div class="col-md-3"></div>
				<div class="col-md-6 form-section">
					<h2>{{ __('users.login_label')}}</h2>

					@include ('Front.alert_messages')

					<form id="sign-in-form" action="{{url('/')}}/validate-login" method="post">
						@csrf
						<div class="form-group">
							<label>{{ __('users.email_label')}} <span class="de_col">*</span></label>
							<input id="email-address" type="text" value="{{$tijara_front_login}}" class="form-control login_input" name="email" placeholder="{{ __('users.email_label')}}">
							<span class="invalid-feedback" id="err_email" style="display: none;"></span>
						</div>

						<div class="form-group">
							<label>{{ __('users.password_label')}}</label>
							<input id="password" type="password" value="{{$tijara_front_password}}" class="form-control login_input" name="password" placeholder="{{ __('users.password_label')}}">
							<span class="invalid-feedback" id="err_password" style="display: none;"></span>
						</div>
						<div class="remember-section row">
							<div class="col-md-6 text-left">
								<a href="javascript:void(0);" data-toggle="modal" data-target="#jt-forgot-modal" class="input-group-text de_col">{{ __('users.lost_your_password_label')}}</a>
							</div>
							<div class="col-md-6 text-right">
								<input type="checkbox" checked="checked" name="remember"> {{ __('users.remember_label')}}
							</div>
						</div>						
						<div class="button-section">
							<div class="tijara-login-button">
								<button  class="btn btn-black debg_color login_btn frontloginbtn" >{{ __('lang.login_btn')}}</button>
							</div>
							<div class="tijara-signup-button">
								<a href="@if(Request::segment(2)=='buyer'){{route('buyer_register')}} @else {{route('seller_register')}} @endif" title="{{ __('users.create_account_btn')}}" class="btn btn-black gray_color login_btn" ><span>{{ __('users.create_account_btn')}}</span> </a>
							</div>
						</div>					
					</form>
					
					@if(Request::segment(2)=='buyer')
						<div class="seller-link-section">
							<a href="{{route('seller_register')}}" title="{{ __('users.register_as_seller')}}" class="" ><span>{{ __('users.register_as_seller')}}</span> </a><br>
							<a href="{{route('frontLoginSeller')}}" title="{{ __('users.login_as_seller')}}" class="" ><span>{{ __('users.login_as_seller')}}</span> </a>
						</div>
					@endif
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</div>
</div> <!-- /container -->


<!-- forgot password Modal -->
<div class="modal fade" id="jt-forgot-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('lang.forgot_password_title')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="container">
          <form class="jt-forgot-form" id="jt-forgot-form" action="{{route('frontForgotPassword')}}" method="POST">
            @csrf
            <div class="form-group">
              <label>{{ __('users.email_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control" id="forgot_email" name="forgot_email" placeholder="{{ __('users.email_label')}}" style="width: 47%;">
              <div class="invalid-feedback" id="err_forgot_email">{{ ($errors->has('forgot_email')) ? $errors->first('forgot_email') : '' }}</div>
            </div>
          </form>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="checkForgotForm();" class="btn btn-black debg_color login_btn">{{ __('lang.submit_btn')}}</button>
      </div>
    </div>
  </div>
</div>

<!-- End forgot password modal -->
<script type="text/javascript">
function checkForgotForm(event)
{
  
  let email       = $("#forgot_email").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error = 0;

  if(email == '')
  {
    $("#err_forgot_email").html("{{ __('errors.fill_in_email_err')}}").show();
    $("#err_forgot_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_forgot_email").html("{{ __('errors.valid_email_err')}}").show();
    $("#err_forgot_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_forgot_email").parent().removeClass('jt-error');
    $("#err_forgot_email").html('').hide();
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#jt-forgot-form').submit();
    return true;
  }
}
</script>
@endsection
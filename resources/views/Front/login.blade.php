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
<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  @if(!empty($banner->image))
  <img class="login_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" />
  @endif
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="login_container">
      <div class="col-md-6">
        <h2>{{ __('users.login_label')}}</h2>
        <hr class="login_heading_line"/>
        <div class="login_box">
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

            <button  class="btn btn-black debg_color login_btn frontloginbtn" >{{ __('lang.login_btn')}}</button>
            <label>
              <input type="checkbox" checked="checked" name="remember"> {{ __('users.remember_label')}}
            </label>

          </form>
         <!--  <a href="" class="de_col">Lost your password?</a> -->
        <!--   <div class="input-group-append">  --><a href="javascript:void(0);" data-toggle="modal" data-target="#jt-forgot-modal" class="input-group-text de_col">{{ __('users.lost_your_password_label')}}</a> <!-- </div> -->
        </div>
      </div>

      <div class="col-md-6">
        <h2>{{ __('users.register_title')}}</h2>
        <hr class="heading_line"/>
        <div class="login_box">
          <form>
            <!--<div class="form-group">
              <label>Email address <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" placeholder="Email Address">
            </div>-->
            <div class="form-group">
              <p>{{ __('users.password_sent_static_content')}}<br><br>
              {{ __('users.privacy_policy_login_page')}}
              </p>
            </div>
            <a href="{{url('/')}}/buyer-register" class="btn btn-black debg_color login_btn">{{ __('users.register_as_buyer_btn')}}</a>
			<a  href="{{url('/')}}/seller-register" class="btn btn-black debg_color login_btn">{{ __('users.register_as_seller_btn')}}</a>
          </form>
        </div>
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
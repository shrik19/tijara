@extends('Front.layout.template')
@section('middlecontent')

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
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2>{{ __('users.create_account_btn')}}</h2>

        <hr class="heading_line"/>
        <p>{{ __('users.already_have_account')}}
          <a href="{{url('/')}}/front-login/buyer" class="de_col">{{ __('users.login_label')}}</a>
        </p> 
        <div class="login_box">
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

            <div  style="display: flex;">
                <input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value="">{{ __('users.read_and_approve_chk')}}<a href="javascript:void(0)">&nbsp;{{ __('users.terms_of_use')}} &nbsp;</a> {{ __('users.and_chk')}} <a href="javascript:void(0)">{{ __('users.privacy_policy')}}</a> 
            </div>

            <button class="btn btn-black debg_color login_btn frontregisterbtn">{{ __('users.create_account_btn')}}</button>
          </form>

          <div class="seller-link-section">
              <a href="{{route('seller_register')}}" title="{{ __('users.register_as_seller')}}" class="" ><span>{{ __('users.register_as_seller')}}</span> </a><br>
              <a href="{{route('frontLoginSeller')}}" title="{{ __('users.login_as_seller')}}" class="" ><span>{{ __('users.login_as_seller')}}</span> </a>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection

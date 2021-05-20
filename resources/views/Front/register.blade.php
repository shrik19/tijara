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
        <h2>{{ __('users.register_as_title')}} {{$registertype}}</h2>
        <hr class="heading_line"/>
        <div class="login_box">
		
          <form id="sign-up-form" action="{{url('/')}}/do-register" method="post">
		  @csrf
		  <input type="hidden" name="role_id" value="{{$role_id}}">
            <div class="form-group">
              <label>{{ __('users.first_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="fname" id="fname" value="{{ old('fname')}}" placeholder="{{ __('users.first_name_label')}}">
			  <span class="invalid-feedback" id="err_fname" style="">@if($errors->has('fname')) {{ $errors->first('fname') }}@endif </span>
            </div>
			<div class="form-group">
              <label>{{ __('users.last_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="lname" id="lname" value="{{ old('lname')}}"  placeholder="{{ __('users.last_name_label')}}">
			  <span class="invalid-feedback" id="err_lname" style="">@if($errors->has('lname')) {{ $errors->first('lname') }}@endif</span>
            </div>
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
			
      <div class="form-group">
        <label>{{ __('users.where_did_you_find_us_label')}}</label>
        <input type="text" class="form-control login_input" name="find_us" id="find_us" value="{{ old('find_us')}}" placeholder="{{ __('users.where_did_you_find_us_label')}}">
        <span class="invalid-feedback" id="err_find_us" style="">@if($errors->has('find_us')) {{ $errors->first('find_us') }}@endif</span>
      </div>
            
            <button class="btn btn-black debg_color login_btn frontregisterbtn">{{ __('lang.register_btn')}}</button>
          </form>
		  <a href="{{url('/')}}/front-login" class="de_col">{{ __('lang.back_to_login_btn')}}</a>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection
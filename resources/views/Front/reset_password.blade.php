@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
<div class="container-inner-section-1">
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
      <div class="">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <h2>{{ __('users.reset_password_btn_label')}}</h2>
          <hr class="heading_line"/>
          <div class="login_box">
            <form class="jt-sign-in-form" id="resetPasswordForm" action="{{route('frontResetPassword')}}" method="POST">
              @csrf
              <input type="hidden" name="token" value="{{$token}}" />
              <div class="form-group jt-required {{ ($errors->has('password')) ? 'jt-error' : '' }}">
                <label for="password">{{ __('users.password_label')}}  <span class="de_col">*</span></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('users.password_label')}}">
                <span class="invalid-feedback" id="err_password" style="display:{{ ($errors->has('password')) ? 'inline' : 'none' }};">{{ ($errors->has('password')) ? $errors->first('password') : '' }}</span>
              </div>

              <div class="form-group jt-required {{ ($errors->has('password_confirmation')) ? 'jt-error' : '' }}" style="margin-top:10px;">
                <label for="password_confirmation">{{ __('users.password_confirmation_label')}}  <span class="de_col">*</span></label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="{{ __('users.password_confirmation_label')}}">
                <span class="invalid-feedback" id="err_cpassword" style="display:{{ ($errors->has('password_confirmation')) ? 'inline' : 'none' }};">{{ ($errors->has('password_confirmation')) ? $errors->first('password_confirmation') : '' }}</span>
              </div>

              <div class="form-group jt-form-bottom text-center" style="margin-top: 50px;"> 
                <button type="submit" class="btn btn-black debg_color login_btn ResetPasswordBtn" style="float:none;">{{ __('users.reset_password_btn_label')}}</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
</div> <!-- /container -->
</div>
</div>
</div>

@endsection
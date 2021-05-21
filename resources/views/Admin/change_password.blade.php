@extends('Admin.layout.template')
@section('middlecontent')

  <div class="row justify-content-md-center">
    <div class="col-sm-12 col-md-6">
      @include('Admin.alert_messages')
      <div class="card">
        <form method="POST" action="{{route('adminChangePasswordStore')}}" class="needs-validation" novalidate="">
          @csrf
          <div class="card-header">
            <h4>{{ __('users.change_password_title')}}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.password_label')}}</label>
              <input type="password" class="form-control" name="password" required tabindex="1">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_password_err')}}
              </div>
              <div class="text-danger">{{$errors->first('password')}}</div>
            </div>
            <div class="form-group">
              <label>{{ __('users.password_confirmation_label')}}</label>
              <input type="password" class="form-control" name="password_confirmation" required tabindex="2">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_confirm_password_err')}}
              </div>
              <div class="text-danger">{{$errors->first('password_confirmation')}}</div>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn btn-success" tabindex="3"><i class="fas fa-check">{{ __('lang.save_btn')}}</i></button>
            <a href="{{route('adminServiceCat')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
          </div>
        </form>
      </div>
    </div>  
  </div>
 
@endsection('middlecontent')
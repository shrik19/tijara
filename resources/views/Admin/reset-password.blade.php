<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{config('constants.PROJECT_NAME')}} | {{$pageTitle}}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{url('/')}}/assets/admin/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="{{url('/')}}/assets/admin/css/all.css" crossorigin="anonymous">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{url('/')}}/assets/admin/css/style.css">
  <link rel="stylesheet" href="{{url('/')}}/assets/admin/css/components.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              TIJARA
            </div>
            @include('Admin.alert_messages')
            <div class="card card-primary">
              <div class="card-header" style="display:inline-block;min-height:fit-content;text-align:center;"><h6>{{ __('users.reset_password_btn_label')}}</h6></div>
              <div class="card-body">
                <form method="POST" action="{{route('ProcessResetPassword')}}" class="needs-validation" novalidate="">
                  @csrf

                <div class="form-group">
                  <label>{{ __('users.email_label')}}</label>
                   <input id="email" type="email" class="form-control" name="email" tabindex="1" required value="{{ old('email') }}">
                  <div class="text-danger">{{$errors->first('email')}}</div>
                </div>

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
                <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" tabindex="3">{{ __('lang.save_btn')}}</button>
              </div>
              </form>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{url('/')}}/assets/admin/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
</body>
</html>

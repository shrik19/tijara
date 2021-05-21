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
      
             <img src="{{url('/')}}/assets/img/logo.png" alt="
              {{config('constants.PROJECT_NAME')}}" width="170" class="shadow-light">
              
            </div>
            @include('Admin.alert_messages')
            <div class="card card-primary">
              <div class="card-header" style="display:inline-block;min-height:fit-content;text-align:center;"><h6>{{ __('lang.sign_in_start_session_label')}}</h6></div>
              <div class="card-body">
                <form method="POST" action="{{route('adminDoLogin')}}" class="needs-validation" novalidate="">
                  @csrf
                  <div class="form-group">
                    <!-- <label for="email">Email</label> -->
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required value="{{ old('email') }}">
                    </div>
                    <div class="invalid-feedback">
                      {{ __('errors.fill_in_email_err')}}
                    </div>
                    <div class="text-danger">{{$errors->first('email')}}</div>
                  </div>

                  <div class="form-group">
                    <!-- <div class="d-block"> -->
                      <!-- <label for="password" class="control-label">Password</label> -->
                      <!-- <div class="float-right">
                        <a href="auth-forgot-password.html" class="text-small">
                          Forgot Password?
                        </a>
                      </div> -->
                    <!-- </div> -->
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </div>
                      </div>
                      <input type="password" class="form-control pwstrength" id="password" name="password" tabindex="2" required data-indicator="pwindicator">
                    </div>
                    <div class="invalid-feedback">
                      {{ __('errors.fill_in_password_err')}}
                    </div>
                    <div class="text-danger">{{$errors->first('password')}}</div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="4" id="remember-me" value="1">
                      <label class="custom-control-label" for="remember-me">{{ __('users.remember_label')}}</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="5">{{ __('lang.login_btn')}}</button>
                  </div>
                </form>
                
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{url('/')}}/assets/admin/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
  <script src="{{url('/')}}/assets/admin/js/popper.min.js" crossorigin="anonymous"></script>
  <script src="{{url('/')}}/assets/admin/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="{{url('/')}}/assets/admin/js/jquery.nicescroll.min.js"></script>
  <script src="{{url('/')}}/assets/admin/js/moment.min.js"></script>
  <script src="{{url('/')}}/assets/admin/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{url('/')}}/assets/admin/js//scripts.js"></script>
  <script src="{{url('/')}}/assets/admin/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <!-- Page Specific JS File -->
  <script type="text/javascript">
   $('#refresh').click(function(){
      $.ajax({
          type:'GET',
          url:'admin/refreshcaptcha',
          success:function(data){
            $(".captcha span").html(data.captcha);
          }
      });
   });
   </script>
</body>
</html>

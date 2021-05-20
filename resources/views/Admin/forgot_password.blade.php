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
               <div class="card-header" style="display:inline-block;min-height:fit-content;text-align:center;"><h6>Reset your Password</h6></div>
              <div class="card-body">
                <form method="POST" action="{{route('ProcessForgotPassword')}}" class="needs-validation" novalidate="">
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
                      Please fill in your email
                    </div>
                    <div class="text-danger">{{$errors->first('email')}}</div>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="5">
                      Send Password Reset link
                    </button>
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
</body>
</html>

@extends('Front.layout.template')
@section('middlecontent')
<div class="jt-full-page-bg"> 
 
  <!-- Begin page content -->
  <main role="main" class="flex-shrink-0"> 
    
    <!-- Section One -->
    <div class="container-fuild jt-section jt-sign-in-section">
      <div class="container">
        <div class="jt-logo-wrap"> <a class="navbar-brand" href="index.html"><img class="img-fluid" src="includes/images/jointly-logo-pink.png" alt="Jointly Logo"></a> </div>
        <div class="jt-form-wrapper">
          <div class="jt-normal-forms2">
            <h1>Sign in</h1>
            <p>New user? <a href="register.html">Create an account</a></p>
            <form class="jt-sign-in-form" id="jt-sign-in-form">
              <div class="form-group jt-required jt-error">
                <label for="email-address">Email Address</label>
                <input type="email" class="form-control" id="email-address" placeholder="Enter email address">
                <div class="invalid-feedback">Please provide email address.</div>
              </div>
              <div class="form-group">
                <label for="passwords">Password</label>
                <div class="input-group jt-password-wrap-with-forgot-password">
                  <input type="passwords" class="form-control" id="passwords" placeholder="Enter passwords">
                  <div class="input-group-append"> <a href="forgot-password.html" class="input-group-text">Forgot Password</a> </div>
                </div>
                <div class="invalid-feedback">Please provide passwords.</div>
              </div>
              <div class="form-group jt-form-bottom"> <a href="vendor-profile-sign-in.html" class="btn jt-btn jt-btn-outline-pink">Vendor Profile Sign In</a>
                <button type="submit" class="btn jt-btn jt-btn-purple">Sign In</button>
              </div>
            </form>
          </div>
          <div class="jt-or-devider">
            <p>Or</p>
          </div>
          <ul class="jt-login-via-social-list">
            <li class="google-item"><a href="#">Continue with Google</a></li>
            <li class="facebook-item"><a href="#">Continue with Facebook</a></li>
            <li class="apple-item"><a href="#">Continue with Apple</a></li>
          </ul>
          <small>Protected by reCAPTCHA and subject to the Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Services</a></small> </div>
      </div>
    </div>
  </main>
</div>
@endsection

@extends('Front.layout.template')
@section('middlecontent')

<div class="container containerfluid p_155" style="min-height: 650px;">
  <!-- Example row of columns -->
  <div class="row register-success-page">
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2>{{ __('lang.register_successful_title')}}</h2>
        <!-- <hr class="heading_line"/> -->
        <div class="login_box">
		
          <div class="alert alert-success">{{ __('lang.account_register_success')}} </div>
     
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection
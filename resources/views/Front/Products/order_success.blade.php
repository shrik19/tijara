@extends('Front.layout.template')
@section('middlecontent')

<div class="container containerfluid p_155">
  <div class="row"> 
    <div class="col-md-12 text-center" style="padding: 50px;">
    	<div class="card">
        <div class="card-body">
          <div class="login_box">
        <div class="alert alert-success">{{ __('lang.msg_product_success')}}</div>
      </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection
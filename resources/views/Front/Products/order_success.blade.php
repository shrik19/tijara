@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="">
        </br>     
      
      <div class="col-md-12 text-center" style="padding: 50px;">
      	<div class="card">
          <div class="card-body">
            <h2 style="text-align: center;color: green">{{ __('lang.msg_product_success')}}</h2>
          </div>
		    </div>
      </div>

    </div>
  </div>
</div> <!-- /container -->

@endsection
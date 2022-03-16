@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
        
      <div class="col-md-12 text-center" style="padding: 50px;">
      	<div class="card">
			
			<div class="card-body">
				 <div class="login_box">
              <div class="alert alert-success">{{$error_messages}}</div></div>
				<a href="{{url('/')}}/show-cart" class="btn btn-black debg_color login_btn" style="margin-top: 15px;">{{ __('lang.try_again_btn')}}</a>
			</div>
		</div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

@endsection
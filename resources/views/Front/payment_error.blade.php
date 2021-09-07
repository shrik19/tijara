@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
    <div class="">
        
      <div class="col-md-12">
      	<div class="card">
			<div class="card-header row">
				
			</div>
			<div class="card-body">
				<h1 style="text-align: center;color: red">{{$error_messages}}</h1>
				<a href="{{url('/')}}/show-cart" class="btn btn-black debg_color login_btn" style="margin-left: 450px;">{{ __('lang.try_again_btn')}}</a>
			</div>
		</div>
      </div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

@endsection
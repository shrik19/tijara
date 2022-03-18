@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
    <div class="">
        
      <div class="col-md-12">
      	<div class="card">
			<div class="card-header row">
				
			</div>
			<div class="card-body">
         <div class="login_box">
          <div class="alert alert-danger">{{$error_messages}}</div>
        </div>
				
				<a href="{{route('frontProductCreate')}}" class="btn btn-black debg_color login_btn" style="margin-left: 450px;">{{ __('lang.try_again_btn')}}</a>
			</div>
		</div>
      </div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

@endsection
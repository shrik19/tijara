@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  @if(!empty($banner->image))
  <img class="login_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" />
@endif
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2>{{ __('lang.register_successful_title')}}</h2>
        <hr class="heading_line"/>
        <div class="login_box">
		
          <div class="alert alert-success">{{ __('lang.account_register_success')}}
		  <a href="{{url('/')}}/profile" class="de_col">{{ __('lang.click_here_btn')}}</a>{{ __('lang.go_to_your_profile_msg')}}
		  </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection
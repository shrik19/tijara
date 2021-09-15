@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <!--<div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>-->
  @if(!empty($banner->image))
  <img class="login_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" />
@endif
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="cmspageDiv">
     
        <h2>{{ $details['title'] }}</h2>
        <hr class="heading_line"/>
        <div>
		      {!! $details['contents'] !!}
         
        </div>
 
    </div>
  </div>
</div> <!-- /container -->

@endsection
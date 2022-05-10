@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
	<div class="containerfluid">
	  <!--<div class="col-md-6 hor_strip debg_color">
	  </div>
	  <div class="col-md-6 hor_strip gray_bg_color">
	  </div>-->
	  @if(!empty($banner->image))
	  <img class="login_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" />
	@endif

	<!-- <div class="container"> -->
	  <!-- Example row of columns -->
	  <div class="product_view ov-hi">
		<div class="cmspageDiv">
		 
			<h2>{{ $details['title'] }}</h2>
			<hr class="heading_line"/>
			<div>
				
				@if($details['slug']=="vanliga-fragor")
					@foreach(explode("###", $details['contents']) as $line)
					<?php 
					$displayQueAns = explode('||', $line);

					 ?>
						<main>
						   <summary class="accordion-faq">
							   {{$displayQueAns[0]}}
						   </summary>						    	
					    	<div class="panel-faq">
					    		<div>
							   		{{$displayQueAns[1]}}
							    </div>
							</div>
						</main>

					@endforeach 
		
				@else
				  {!! $details['contents'] !!}
				@endif
			 
			</div>
	 
		</div>
	  </div>
	</div>
</div>
<!-- </div> --> <!-- /container -->

@endsection
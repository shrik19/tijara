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
		
        @foreach($payment_options as $p)
        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;"
         onclick="location.href='{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>$p])}}'" >
                        {{$p}} <span class="glyphicon glyphicon-play"></span>
                        </button>
        @endforeach
    </div>
		</div>
      </div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

@endsection
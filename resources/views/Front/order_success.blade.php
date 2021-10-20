@extends('Front.layout.template')
@section('middlecontent')

<div class="container-fluid p_155">
<div class="container">
  <div class="row">
     
      
      <div class="col-md-12 text-center" style="padding: 50px;">
      	<div class="card">
          <div class="card-body">
          @if(isset($swish_message))

          <h2 style="text-align: center;color: green">
            
            {{$swish_message}}
            </h2>
            @else
            <h2 style="text-align: center;color: green">
            
            {{ __('lang.msg_order_success')}}
            </h2>
            <a href="{{url('/')}}/order-details/{{base64_encode($OrderId)}}" class="btn btn-black debg_color login_btn">{{ __('lang.txt_order_success_btn')}}</a>&nbsp;&nbsp;<a href="{{route('AllproductListing')}}" class="btn btn-black debg_color login_btn">{{ __('lang.buy_now_btn')}}</a>
            @endif
          </div>
		    </div>
      </div>

    </div>
  </div>
</div> <!-- /container -->

@endsection
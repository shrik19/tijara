@extends('Front.layout.template')
@section('middlecontent')

<div class="container-fluid p_155" style="min-height: 500px;">
<div class="container">
  <div class="row">
     
      
      <div class="col-md-12 text-center" style="padding: 50px;">
      	<div class="card">
          <div class="card-body">
          <h2>{{ __('messages.swish_successful_title')}}</h2>
              @if(isset($swish_message1) && isset($swish_message2))
                <div class="login_box">
                  <div class="alert alert-success"><p>{{$swish_message1}}</p><p>{{$swish_message2}}  </p></div>
                </div>
              @else
                <div class="alert alert-success">
                  <p style="text-align: center;color: green"> {{ __('lang.msg_order_success')}} </p>
                  <div style="margin-top:10px;">
                   <a href="{{url('/')}}/all-buyer-orders" class="btn btn-black debg_color login_btn">{{ __('lang.txt_order_success_btn')}}</a>&nbsp;&nbsp;<a href="{{route('AllproductListing')}}" class="btn btn-black debg_color login_btn">{{ __('lang.continue_action_btn')}}</a> </div>
                 </div>
              @endif
            </div>
		    </div>
      </div>

    </div>
  </div>
</div> <!-- /container -->

@endsection
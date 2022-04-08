@extends('Front.layout.template')
@section('middlecontent')
<div class="mid-section p_155"  style="min-height: 600px;">
<div class="container-fluid">
<div class="container">
  <div class="row">
  <div class="col-md-12 text-center" style="padding: 50px;">
    <div class="card">
      <div class="card-body">
        <h2>{{ __('messages.swish_successful_title')}}</h2>
        @if(isset($swish_message))
        <div class="login_box">
          <div class="alert alert-success">{{$swish_message}}</div>
        </div>
        @else
        <div class="login_box">
          <div class="alert alert-success"> {{ __('lang.msg_order_success')}} </div>
        </div>
        <a href="{{url('/')}}/all-buyer-orders" class="btn btn-black debg_color login_btn">{{ __('lang.txt_order_success_btn')}}</a>&nbsp;&nbsp;<a href="{{route('AllproductListing')}}" class="btn btn-black debg_color login_btn">{{ __('lang.continue_shopping_btn')}}</a>
        @endif
      </div>
    </div>
  </div>
  </div>
</div>
</div> <!-- /container -->
</div>
@endsection
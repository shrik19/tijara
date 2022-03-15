@extends('Front.layout.template')
@section('middlecontent')
<div class="mid-section p_155">
<div class="container-fluid">
<div class="container">
  <div class="row">
  <div class="col-md-12 text-center" style="padding: 50px;">
    <div class="card">
      <div class="card-body">
        @if(isset($swish_message))
        <div class="login_box">
          <div class="alert alert-success">{{$swish_message}}</div>
        </div>
        @else
        <div class="login_box">
          <div class="alert alert-success"> {{ __('lang.msg_order_success')}} </div>
        </div>
        <a href="{{url('/')}}/all-buyer-orders" class="btn btn-black debg_color login_btn">{{ __('lang.txt_order_success_btn')}}</a>&nbsp;&nbsp;<a href="{{route('AllproductListing')}}" class="btn btn-black debg_color login_btn">{{ __('lang.buy_now_btn')}}</a>
        @endif
      </div>
    </div>
  </div>
  </div>
</div>
</div> <!-- /container -->
</div>
@endsection
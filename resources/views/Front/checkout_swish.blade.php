@extends('Front.layout.template')
@section('middlecontent')

<script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/4.1.0/adyen.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/4.1.0/adyen.css" crossorigin="anonymous">

<div id="clientKey" class="hidden">{{$clientKey}}</div>
<div id="type" class="hidden">{{$type}}</div>
<div id="orderId" class="hidden">{{$orderId}}</div>
<div id="paymentAmount" class="hidden">{{$paymentAmount}}</div>

<section class="product_details_section">
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container">
            <div class="payment-container">
                <div id={{$type}} class="payment"></div>
                <div id={{$orderId}} class="orderId"></div>
                
            </div>
          </div>
        </div>
      </div>
    </div>
</section>        

<script type="text/javascript" src="{{ url('/') }}/assets/front/js/adyenImplementation.js"></script>
@endsection

@extends('Front.layout.template')
@section('middlecontent')

<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container container-fluid p_155">
            <div class="payment-container">
             
              <input type="hidden" name="order_id" id="order_id" class="PaymentRequestToken" value="{{$order_id}}">
              <div style="text-align: center;">
              <img src="data:image/png;base64, {{$QRCode}}" />
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>        
<script type="text/javascript">
  $( document ).ready(function() {
    setInterval(function () {
      var order_id = $("#order_id").val();
      $.ajax({
          headers: {
          'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          url: "{{url('/')}}"+'/buyer-check-order-status/'+order_id,
          type: 'post',
          // async: false,
          data:{},
          success: function(data){
            if(data.payment_status=="PAID"){
              window.location = "{{ route('SwishOrderSuccess') }}";
            }else{
              window.location = "{{ route('SwishPaymentError') }}";
            }
         
          }
        });

         /* console.log('it works' + new Date());*/
      },5000);


  });
</script>
@endsection

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
            <a href="#" class="btn btn-black gray_color login_btn cancel_payment"> {{ __('lang.cancel_btn')}}</a>
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
          url: "{{url('/')}}"+'/check-order-status/'+order_id,
          type: 'post',
          // async: false,
          data:{},
          success: function(data){
            console.log(data.payment_status)
            if(data.payment_status=="PAID"){
              window.location = "{{ route('SwishOrderSuccess') }}";
            }
            else if(data.payment_status=="ERROR"){
              window.location = "{{ route('SwishPaymentError') }}";
            }else if(data.payment_status=="CANCELLED"){
              window.location = "{{ route('SwishPaymentError') }}";
            }else if(data.payment_status=="DECLINED"){
              window.location = "{{ route('SwishPaymentError') }}";
            }
          }
        });

         /* console.log('it works' + new Date());*/
      },5000);
  });

  $(".cancel_payment").on("click", function() { 
      var order_id = $("#order_id").val();
     $.ajax({
          headers: {
          'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          url: "{{url('/')}}"+'/update-order-status/'+order_id,
          type: 'post',
          // async: false,
          data:{},
          success: function(data){
            if(data.payment_status=="CANCELLED"){
              window.location = "{{ route('SwishPaymentError') }}";
            }
           
          }
        });

  });

</script>
@endsection

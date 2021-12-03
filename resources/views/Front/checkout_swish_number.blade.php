@extends('Front.layout.template')
@section('middlecontent')

<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container container-fluid p_155">
            <div class="payment-container">
             
              <input type="hidden " name="PaymentRequestToken" id="PaymentRequestToken" class="PaymentRequestToken" value="{{$PaymentRequestToken}}">
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
  var PaymentRequestToken = $("#PaymentRequestToken").val();
  $.ajax({
      headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      url: "{{url('/')}}"+'/check-order-status/'+PaymentRequestToken,
      type: 'get',
      // async: false,
      data:{},
      success: function(data){
        $('#orderDetailsmodal').modal('show');
        $('#order_details_box').html(data);
      }
    });
</script>
@endsection

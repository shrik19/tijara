@extends('Front.layout.template')
@section('middlecontent')

<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container container-fluid p_155">
            <div class="payment-container">
              <?php echo "<pre>";print_r($QRCode);exit;?>
              <input type="hidden " name="order_id" id="order_id" class="order_id">
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
  var order_id = $("#order_id").val();
  $.ajax({
      headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      url: "{{url('/')}}"+'/check-order-status/'+order_id,
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

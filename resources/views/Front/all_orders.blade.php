@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  label{
      margin-left: 6px;
  }
</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  
  <div class="row">
      <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">
    @if($is_seller==1)
      <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10 tijara-content margin_bottom_class">
        @else
        <div class="col-md-12 tijara-content">
      @endif
      
		 
	  @include('Front.alert_messages')
    <div class="seller_info">
	  <div class="card">
		<div class="card-header row seller_header">
      <h2 class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('users.my_order_title')}}</h2>
      <!-- <hr class="heading_line"/> -->
      </div>
    </div>
    <div class="seller_mid_cont">
      
  <!--   <div class="col-md-12" style="margin-top: 20px;">
      <div class="tijara-info-section">
        <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
        <p  class="buyer-prod-content">{{__('messages.my_order_info')}}</p>
      </div>
    </div> -->

		<div class="col-md-12 tj-mobnopad tj-orderstable" style="margin-top: 20px;margin-left: 5px;">
		    
		  
		<div class="card-body">
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_order_number')}}</th>
          @if($is_seller)
          <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_name')}}</th>
          @endif
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_subtotal')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_shipping')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_total')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_payment_status')}}</th> 
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_order_status')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.txt_date')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('users.order_table_action')}}</th>
				  </tr>
				</thead>
				  <tbody id="result">
				  </tbody>
			  </table>
			</div>
		  </form>  
		</div>
    </div>
	  </div>
		</div>
    </div>
</div>
  </div>

    <!-- order details model -->
 <div class="modal fade" id="orderDetailsmodal">
    <div class="modal-dialog modal-lg" role = "dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('messages.txt_order_details')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
          <div class="modal-body-wrapper">
        <div class="modal-body" id="order_details_box">
            
        </div>
      </div>
              
      </div>
    </div>
  </div>


</div>
</div> <!-- /container -->

<!-- <script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script> -->
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/dataTables.bootstrap4.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">
<script src="{{url('/')}}/assets/front/js/jquery-confirm.min1.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

  var user_id = $("#user_id").val();
  $.ajax({
    url: siteUrl+'/update-orders-notification/?user_id='+user_id,
    type: 'get',
    async: false,
    data: { },
    success: function(output){
      if(output.success ==1){
        $('#notification_count').html(output.notification_count);
        $('#allSellerOrders').html(output.orders_count);
        $('#allSellerBookings').html(output.bookings_count);
        if($("#notification_count").text()==0){
          $(".notification_count").css('display','none')
        }
      }    
    }
});
}); 
  var dataTable = $('#productTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    "language": {
        "sSearch": "<?php echo __('lang.datatables.search');?>",
        "sInfo": "<?php echo __('lang.datatables.sInfo');?>",
        "sLengthMenu": "<?php echo __('lang.datatables.sLengthMenu');?>",
        "sInfoEmpty": "<?php echo __('lang.datatables.sInfoEmpty');?>",
        "sLoadingRecords": "<?php echo __('lang.datatables.sLoadingRecords');?>",
        "sProcessing": "<?php echo __('lang.datatables.sProcessing');?>",      
        "sZeroRecords": "<?php echo __('lang.datatables.sZeroRecords');?>",
        "oPaginate": {
              "sFirst":    "<?php echo __('lang.datatables.first');?>",
              "sLast":    "<?php echo __('lang.datatables.last');?>",
              "sNext":    "<?php echo __('lang.datatables.next');?>",
              "sPrevious": "<?php echo __('lang.datatables.previous');?>",
          },
        },
    columnDefs: [
          {
              targets: [1,2],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("frontOrdersGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
        data.is_seller = "{{$is_seller}}"
        data.user_id = "{{$user_id}}"
        data.monthYear = $('#monthYear').val()
      },
       type:'post',
    },
  });
  $("<div class='form-group' style='float:right;'>"+
  
  "<?php echo $monthYearHtml; ?>"+
  "</div>").appendTo("#productTable_length");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#monthYear').change(function(){

    dataTable.draw();
    
  });
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control tjselect" id="status" name="status">'+
  '<option value="">{{ __("lang.status_label")}}</option>'+
  '<option value="PENDING">{{ __("users.pending_order_status")}}</option>'+
  '<option value="SHIPPED">SHIPPED</option>'+
  '<option value="CANCELLED">CANCELLED</option>'+
  '</select></div>').appendTo("#productTable_filter");  
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });


function print_window(id){
 
  var order_id = btoa(id);
    $.ajax({
        headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                 },
        url: "{{url('/')}}"+'/order-details/'+order_id,
        type: 'get',
       // async: false,
        data:{},
        success: function(data){
            //console.log(data)
             $('#orderDetailsmodal').modal('show');
           $('#order_details_box').html(data);
           var order_status = $('#order_status :selected').val();
           if(order_status == "PENDING"){
            $('#order_status').css('background-color','red');
             $('#order_status').css('color','white');
           }else if(order_status == "SHIPPED" || order_status == "CANCELLED"){
            $('#order_status').css('background-color','green');
            $('#order_status').css('color','white');
           }
          
        }
    });

}

 function printDiv() {
	 
	var product_link= $('.seller_odr_dtls').attr('product_link')+'?print=1';
	window.open(product_link, '_blank');
      
  /*const section = $(".mid-section");
  const modalBody = $(".modal-body").detach();
  const content = $(".tijara-content").detach();
  section.append(modalBody);
  window.print();
  section.empty();
  section.append(content);
  $(".modal-body-wrapper").append(modalBody);*/

}

function downloadPdf(DownloadLink) 
{
  if(DownloadLink !=''){
    window.location.href = DownloadLink; 
  } 
}

function change_order_status(order_id) {

  var order_status = $('#order_status').val(); 
  var order_id = order_id;

  $.confirm({
      title: js_confirm_msg,
      content: "{{ __('lang.order_status_confirm')}}",
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () 
          {
              $(".loader").show();

              $.ajax({
              url:siteUrl+"/change-order-status",
              headers: {
                  'X-CSRF-Token': $('meta[name="_token"]').attr('content')
              },
              type: 'post',
              data : {'order_status': order_status, 'order_id' : order_id},
              success:function(data)
              {
                  $(".loader").hide();
                  var responseObj = $.parseJSON(data);
                  if(responseObj.status == 1)
                  {
                      showSuccessMessageReview(responseObj.msg,'reload');
                  }
                  else
                  {
                      if(responseObj.is_login_err == 0)
                      {
                          showErrorMessage(responseObj.msg);
                      }
                      else
                      {
                          showErrorMessage(responseObj.msg,'/front-login/seller');
                      }
                  }

              }
              });
          },
          Avbryt: function () {
              
          },
      }
  });
}

</script>

@endsection
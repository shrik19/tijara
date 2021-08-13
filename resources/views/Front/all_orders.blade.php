@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  
</div>
<div class="container">
  <!-- Example row of columns -->
  
  <div class="row">
  <div class="col-md-2">
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10">
		 
	  @include('Front.alert_messages')
	   
	  <div class="card">
		<div class="card-header row">
      <h2>{{ __('lang.txt_seller_order')}}</h2>
      <hr class="heading_line"/>
      </div>
    </div>
    <div class="col-md-12">
      <div class="buyer-prod-msg">
        <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
        <p  class="buyer-prod-content">{{__('messages.my_order_info')}}</p>
      </div>
    </div>
		<div class="col-md-12">
		    
		  
		<div class="card-body">
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th data-orderable="false">{{ __('lang.txt_order_number')}}</th>
          @if($is_seller)
          <th data-orderable="false">{{ __('lang.txt_name')}}</th>
          @endif
				  <th data-orderable="false">{{ __('lang.txt_subtotal')}}</th>
				  <th data-orderable="false">{{ __('lang.txt_shipping')}}</th>
				  <th data-orderable="false">{{ __('lang.txt_total')}}</th>
				  <th data-orderable="false">{{ __('lang.txt_payment_status')}}</th> 
				  <th data-orderable="false">{{ __('lang.txt_order_status')}}</th>
				  <th data-orderable="false">{{ __('lang.txt_date')}}</th>
				  <th data-orderable="false">{{ __('lang.action_label')}}</th>
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
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/dataTables.bootstrap4.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
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
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value="">{{ __("lang.status_label")}}</option>'+
  '<option value="PENDING">PENDING</option>'+
  '<option value="SHIPPED">SHIPPED</option>'+
  '<option value="COMPLETE">COMPLETE</option>'+
  '<option value="CANCELLED">CANCELLED</option>'+
  '</select></div>').appendTo("#productTable_filter");  
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

</script>

@endsection
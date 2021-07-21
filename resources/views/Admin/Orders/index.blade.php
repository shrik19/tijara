@extends('Admin.layout.template')
@section('middlecontent')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{url('/')}}/assets/admin/css/dataTables.bootstrap4.min.css">
<div class="section-body">
  <div class="row">
    <div class="col-12">
      @include('Admin.alert_messages')
      <div class="card">
        <div class="card-header">
          <h4>{{ __('lang.txt_seller_order')}}</h4>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
          @csrf
          <div class="table-responsive">
            <table class="table table-striped" id="orderTable">
            <thead>
            <tr>
              <th data-orderable="false">{{ __('lang.txt_order_number')}}</th>
              <th data-orderable="false">{{ __('lang.txt_name')}}</th>
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
</div>

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.buttons.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/buttons.html5.min.js"></script>
<script type="text/javascript">
  var dataTable = $('#orderTable').DataTable({
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
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminOrderGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
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
  '</select></div>').appendTo("#orderTable_filter");  
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

$('.nav-link').click( function() {
    document.getElementById("orderTable").removeAttribute("style");
}); 

</script>
@endsection('middlecontent')

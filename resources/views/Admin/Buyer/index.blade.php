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
<h4>{{ __('users.all_buyers_list')}}</h4>
<a href="{{route('adminBuyersCreate')}}" title="{{ __('users.add_buyers_btn')}}" class="btn btn-icon btn-success" style="margin-left:586px;"><span>{{ __('users.add_buyers_btn')}}</span> </a>
</div>
<div class="card-body">
<form id="vendorMultipleAction" action="" method="post">
@csrf
<div class="table-responsive">
<table class="table table-striped" id="buyersTable">
<thead>
<tr>
<th>{{ __('users.first_name_label')}}</th>
<th>{{ __('users.last_name_label')}}</th>
<th>{{ __('users.email_label')}}</th>
<th>{{ __('users.where_find_us_thead')}}</th>
<th data-orderable="false">{{ __('lang.status_label')}}</th>
<th data-orderable="false">{{ __('lang.action_thead')}}</th>
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

<!-- Change Password Form -->



<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">

  var dataTable = $('#buyersTable').DataTable({
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
      url : '{{route("adminBuyersGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
      },
      type:'post',
    },

  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value="">{{ __("lang.select_status_ddl")}}</option>'+
  '<option value="active">{{ __("lang.active_label")}}</option>'+
  '<option value="block">{{ __("lang.inactive_label")}}</option>'+
  '</select></div>').appendTo("#buyersTable_length");

  $('<span class="col-md-2 export btn export-btn">'+
  '{{ __("lang.export_btn")}}</span></div>').appendTo("#buyersTable_wrapper dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');


  $('#status').change(function(){
    dataTable.draw();
  });


  $('.export').click(function(){
    $('#exportval').val(1);
    dataTable.draw();
    $('#exportval').val('0');
    $.ajax({
      url: "{{url('/')}}"+'/admin/buyers/exportdata/?status='+$('#status').val()+'&search='+$('#buyersTable_filter').find('input').val(),
      type: 'get',
      data: { },

      success: function(output){
        url="{{url('/')}}"+'/BuyerDetails/BuyesfromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

</script>

@endsection('middlecontent')

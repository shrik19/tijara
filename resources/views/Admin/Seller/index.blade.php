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
          <h4>{{ __('users.all_seller_list')}}</h4>
          <a href="{{route('adminSellerCreate')}}" title="{{ __('users.add_seller_btn')}}" class="btn btn-icon btn-success" style="margin-left:650px;"><span>{{ __('users.add_seller_btn')}}</span> </a>
        </div>

        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="sellerTable">
                <thead>
                  <tr>
                  <th>{{ __('users.first_name_label')}}</th>
                  <th>{{ __('users.last_name_label')}}</th>
                  <th>{{ __('users.store_name_label')}}</th>
                  <th>{{ __('users.city_label')}}</th>
                  <th>{{ __('users.where_find_us_thead')}}</th>
                  <th data-orderable="false">{{ __('users.show_ackages_thead')}}</th>
                  <th data-orderable="false">{{ __('users.is_verified_thead')}}</th>
                  <th data-orderable="false">{{ __('lang.status_thead')}}</th>
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

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.buttons.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/buttons.html5.min.js"></script>
<script type="text/javascript">
  var dataTable = $('#sellerTable').DataTable({
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
              targets: [4,5],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminSellerGetRecords")}}',
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
  '</select></div>').appendTo("#sellerTable_length");

  $('<span class="export btn button export-btn">'+
  '{{ __("lang.export_btn")}}</span></div>').appendTo("#sellerTable_wrapper .dataTables_filter");

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
      url: "{{url('/')}}"+'/admin/seller/exportdata/?status='+$('#status').val()+'&search='+$('#sellerTable_filter').find('input').val(),
      type: 'get',
      data: { },
      success: function(output){
        url="{{url('/')}}"+'/SellerDetails/SellerFromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

$('.nav-link').click( function() {
  document.getElementById("sellerTable").removeAttribute("style");
});
  
</script>
@endsection('middlecontent')

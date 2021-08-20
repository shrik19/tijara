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
          <h4>{{ __('users.all_subscribed_users_list')}}</h4>
        </div>

        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="usersTable">
                <thead>
                  <tr>
                  <th></th>
                  <th>{{ __('users.email_title')}}</th>
                  <th>{{ __('users.is_subscrier_title')}}</th>
                  <th>{{ __('users.page_created_title')}}</th>
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
<script type="text/javascript">

  var dataTable = $('#usersTable').DataTable({
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
              targets: [2,3],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminNewsletterGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value="">{{ __("lang.select_status_ddl")}}</option>'+
  '<option value="active">{{ __("lang.active_label")}}</option>'+
  '<option value="inactive">{{ __("lang.inactive_label")}}</option>'+
  '</select></div>').appendTo("#usersTable_length");

  $('<span class="export btn button export-btn">'+
  '{{ __("lang.export_btn")}}</span></div>').appendTo("#usersTable_wrapper .dataTables_filter");

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
      url: "{{url('/')}}"+'/admin/newsLetterSubscriber/exportdata/?status='+$('#status').val()+'&search='+$('#usersTable_filter').find('input').val(),
      type: 'get',
      data: { },
      success: function(output){
        url="{{url('/')}}"+'/SubscribedUsersDetails/NewsLetterFromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

$('.nav-link').click( function() {
  document.getElementById("usersTable").removeAttribute("style");
});
  
</script>
@endsection('middlecontent')

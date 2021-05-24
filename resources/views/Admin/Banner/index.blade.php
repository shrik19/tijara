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
        <h4>{{ __('users.all_banner_list')}}</h4>
      <div class="pull-right" style="float: right;left: 70%;position: relative;">
      <a href="{{route('adminBannerCreate')}}" title="{{ __('users.add_banner_btn')}}" class="btn btn-icon btn-success">{{ __('users.add_banner_btn')}}</a>
      </div>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="bannerTable">
                <thead>
                  <tr>
                    <th>{{ __('users.image_thead')}}</th>
                    <th>{{ __('users.title_thead')}}</th>
                    <th>{{ __('users.link_thead')}}</th>
                     <th>{{ __('users.display_on_page_title')}}</th>
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




<span content="{{ csrf_token() }}" class="csrf_token"></span>


<span content="{{ csrf_token() }}" class="csrf_token"></span>
<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  
  

  var dataTable = $('#bannerTable').DataTable({
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
          url : '{{route("adminBannerGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        },
        "initComplete":function( settings, json){
		}
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
      '<option value="">{{ __("lang.select_status_ddl")}}</option>'+
      '<option value="active">{{ __("lang.active_label")}}</option>'+
      '<option value="block">{{ __("lang.inactive_label")}}</option>'+
      '</select></div>').appendTo("#bannerTable_wrapper .dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

 




</script>
@endsection('middlecontent')

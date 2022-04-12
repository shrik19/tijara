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
          <h4>{{ __('lang.manage_attributes_menu')}}</h4>
      <div class="pull-right" style="float: right;left: 82%;position: relative;">
      <a href="{{route('adminAttributeCreate')}}" title="{{ __('lang.add_attribute')}}" class="btn btn-icon btn-success">{{ __('lang.add_attribute')}}</a>
      </div>
        </div>

        <div class="card-body">
          <form id="" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="attributeTable">
                <thead>
                  <tr>
                  <th data-orderable="false">&nbsp;</th>
                    <th>{{ __('lang.attribute_label')}}</th>
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
   var dataTable = $('#attributeTable').DataTable({
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
          url : '{{route("adminAttributeGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });
</script>
@endsection('middlecontent')

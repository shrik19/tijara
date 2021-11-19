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
          <h4>{{ __('users.product_list_page')}}</h4>
         <!--  <a href="{{route('adminProductCreate')}}" title="{{ __('lang.add_product')}}" class="btn btn-icon btn-success" style="margin-left:650px;"><span>{{ __('lang.add_product')}}</span> </a> -->
        </div>

        <div class="card-body">
          <form id="" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="productTable">
                <thead>
                  <tr>
                  <th data-orderable="false">{{ __('lang.image_label')}}</th>
				          <th>{{ __('users.sellers_title')}}</th>
                  <th>{{ __('lang.product_label')}}</th>
                   <th>{{ __('lang.sku_label')}}</th>
                  <th>{{ __('lang.price_label')}}</th>
                  <th data-orderable="false">{{ __('lang.category_label')}}</th>                 
                  <th>{{ __('lang.dated_label')}}</th>
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
              targets: [4,6],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminProductGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value="">{{ __("lang.status_label")}}</option>'+
  '<option value="active">{{ __("lang.active_label")}}</option>'+
  '<option value="block">{{ __("lang.block_label")}}</option>'+
  '</select></div>').appendTo("#productTable_length");

  // $('<span class="export btn button export-btn">'+
  // 'Export</span></div>').appendTo("#productTable_wrapper .dataTables_filter");

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
      url: "{{url('/')}}"+'/admin/product/exportdata/?status='+$('#status').val()+'&search='+$('#productTable_filter').find('input').val(),
      type: 'get',
      data: { },
      success: function(output){
        url="{{url('/')}}"+'/ProductDetails/ProductFromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

</script>
@endsection('middlecontent')

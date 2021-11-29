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
        <h4>{{ __('users.all_review_list')}}</h4>
     
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            <input type="hidden" name="current_page" id="current_page" value="{{$page}}">
            <input type="hidden" name="current_id" id="current_id" value="{{$current_id}}">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="reviewTable">
                <thead>
                  <tr>
                    <th>{{ __('users.user_name_thead')}}</th> 
                    <th>{{ __('lang.product_label')}}</th>
                     <th>{{ __('users.review_title')}}</th>
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
  
  

  var dataTable = $('#reviewTable').DataTable({
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
          url : '{{route("adminReviewGetRecords")}}',
          data :{ current_id:$('#current_id').val(),current_page:$('#current_page').val()},
          type:'post',
        },
        "initComplete":function( settings, json){
		}
  });


 
 
$('.nav-link').click( function() {
  document.getElementById("reviewTable").removeAttribute("style");
});



</script>
@endsection('middlecontent')

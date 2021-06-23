@extends('Admin.layout.template')
@section('middlecontent')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{url('/')}}/assets/admin/css/dataTables.bootstrap4.min.css">
<div class="section-body">
  <div class="row">
    <div class="col-12">

      <div class="alert alert-success alert-dismissible show fade" style="display: none;">
         <div class="alert-body">
         <button class="close" data-dismiss="alert">
            <span>&times;</span>
         </button>
         <strong>{{ __('users.success')}}</strong> <p id="success-message"></p>
         </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h4>{{ __('users.all_service_subcategory_list')}}</h4>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="subcategoryTable">
                <thead>
                  <tr>
                    <th data-orderable="false">&nbsp;</th>
                    <th>{{ __('users.category_name_thead')}}</th>
                    <th>{{ __('users.subcategory_name_label')}}</th>
                    <th>{{ __('users.sequence_no_thead')}}</th>
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


<!---------- edit subub------------>

 <div class="modal fade" id="savesubcategorymodal">
    <div class="modal-dialog">
      <div class="modal-content">
      
       
        <div class="modal-header">
          <h4 class="modal-title">{{ __('users.update_subcategory_title')}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
      
        <div class="modal-body">
          
         <div class="container">
          <form action="{{route('adminSubcategoryStore')}}"  enctype="multipart/form-data" method="post" class="savecategoryform">
              @csrf
            <div class="form-group">
              <label>{{ __('users.category_name_thead')}}</label>
              <input type="hidden" class="form-control id" value="" name="id" readonly>
              <input type="text" class="form-control category_name" name="category_name" value="" readonly>
            </div>

            <div class="form-group">
              <label>{{ __('users.subcategory_name_label')}}</label>
              <input type="text" name="subcategory_name" class="form-control subcategory_name"  >
            </div> 
            
          </form>
         </div>
     </div>
        
       <div class="modal-footer">
        <button type="submit" class="savesubcategorydata btn btn-primary">{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('lang.close_btn')}}</button>
        </div>
        
      </div>
    </div>
  </div>



<span content="{{ csrf_token() }}" class="csrf_token"></span>

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  
  
  var dataTable = $('#subcategoryTable').DataTable({
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
          url : '{{route("adminServiceSubcatGetRecords",$current_id)}}',
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
      '</select></div>').appendTo("#subcategoryTable_wrapper .dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });



      $(document).on("click",".savesubcategory",function(event) {
              
        $('#savesubcategorymodal').find('.id').val($(this).attr('id'));
			  $('#savesubcategorymodal').find('.category_name').val($(this).attr('category_name'));
			  $('#savesubcategorymodal').find('.subcategory_name').val($(this).attr('subcategory_name'));
			  if($(this).attr('image')!='') {
                $('#savesubcategorymodal').find('.subcategoryimg').attr('src',$(this).attr('image'));
			  }
			  else $('#savesubcategorymodal').find('.subcategoryimg').hide();
			  
              $('#savesubcategorymodal').modal('show');
			  $('.modal-backdrop').attr('style','position: relative;');
              
            }); 
           
            
            $(document).on("click",".savesubcategorydata",function(event) {
  
            //savecategoryform
            if($('#savesubcategorymodal').find('.subcategory_name').val()!='' && $('#savesubcategorymodal').find('.id').val()=='') {
                $('.savecategoryform').submit();
            }else{
              alert('{{ __("errors.subcategory_name_req")}}');
            }
            }); 


$('.nav-link').click( function() {
  document.getElementById("subcategoryTable").removeAttribute("style");
});
 
</script>
@endsection('middlecontent')

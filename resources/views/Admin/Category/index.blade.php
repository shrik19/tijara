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
          <h4>{{ __('users.all_category_list')}}</h4>
           <a href="{{route('adminCategoryCreate')}}" title="{{ __('users.add_category_btn')}}" class="btn btn-icon btn-success" style="margin-left:586px;"><span>{{ __('users.add_category_btn')}}</span> </a>
        </div>
       
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="categoryTable">
                <thead>
                  <tr>
                    <th data-orderable="false">&nbsp;</th>
                    <th>{{ __('users.category_name_thead')}}</th>
                    <th>{{ __('users.sequence_no_thead')}}</th>
                    <th data-orderable="false">{{ __('users.subcategory_count_thead')}}</th>
                    <th data-orderable="false">Status{{ __('lang.status_label')}}</th>
                    <th data-orderable="false">{{ __('users.add_subcategory_title')}}</th>
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

<!-- add subcategory model Form -->
 <div class="modal fade" id="savesubcategorymodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('users.add_subcategory_title')}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="container">
            <form action="{{route('adminSubcategoryStore')}}"  enctype="multipart/form-data" method="post" class="savecategoryform">
              @csrf
              <div class="form-group">
                <label>{{ __('users.category_name_thead')}}</label>
  			        <input type="hidden" class="form-control category_id"  name="hid_subCategory" value="" readonly>
                <input type="text" name="category_name" class="form-control category_name" value="" readonly>
              </div>

              <div class="form-group">
                <label>{{ __('users.subcategory_name_label')}} <span class="text-danger">*</span></label>
                <input type="text" name="subcategory_name"  placeholder="{{ __('users.subcategory_name_label')}}" class="form-control subcategory_name" onblur="allLetter(this)" >
                <div class="text-danger err-letter">{{ ($errors->has('subcategory_name')) ? $errors->first('subcategory_name') : '' }}</div>
              </div>

              <div class="form-group">
                <label>{{ __('users.sequence_number_label')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder="{{ __('users.sequence_number_label')}}" value="{{ old('sequence_no')}}" tabindex="3"/>
                <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
              </div>

            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="savesubcategorydata btn btn-icon icon-left btn-success"><i class="fas fa-check"></i>{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>{{ __('lang.close_btn')}}</button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- end subcategory model Form -->

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(document).on("click",".savesubcategory",function(event) {
        
        $('#savesubcategorymodal').find('.id').val($(this).attr('id'));
        $('#savesubcategorymodal').find('.category_name').val($(this).attr('category_name'));
        $('#savesubcategorymodal').find('.category_id').val($(this).attr('category_id'));
        $('#savesubcategorymodal').find('.subcategory_name').val($(this).attr('subcategory_name'));
        
        $('#savesubcategorymodal').modal('show');
        $('.modal-backdrop').attr('style','position: relative;');
    }); 
    
    $(document).on("click",".savesubcategorydata",function(event) {
       //savecategoryform
        if($('#savesubcategorymodal').find('.subcategory_name').val()!='') {
          $('.savecategoryform').submit();
        }
        else alert('{{ __("errors.subcategory_name_req")}}');
    }); 
   
      
     var dataTable = $('#categoryTable').DataTable({
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
              targets: [2],
              className: "text-center",
          }
        ],
        "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("adminCategoryGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
      '<option value="">{{ __("lang.select_status_ddl")}}</option>'+
      '<option value="active">{{ __("lang.active_label")}}</option>'+
      '<option value="block">{{ __("lang.inactive_label")}}</option>'+
      '</select></div>').appendTo("#categoryTable_wrapper .dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });


  /*function to validate letters for sub category*/
  function allLetter(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      alert('{{ __("errors.input_alphabet_err")}}');
      return false;
    }
  }

$('.nav-link').click( function() {
  document.getElementById("categoryTable").removeAttribute("style");
});
</script>
@endsection('middlecontent')
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
          <h4>Service Category List</h4>
           <a href="{{route('adminServiceCatCreate')}}" title="Add Service Category" class="btn btn-icon btn-success" style="margin-left:600px;"><span>Add Category</span> </a>
        </div>
       
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="categoryTable">
                <thead>
                  <tr>
                    <th data-orderable="false">&nbsp;</th>
                    <th>Category Name</th>
                    <th>Sequence No</th>
                    <th data-orderable="false">Subcategory Count</th>
                    <th data-orderable="false">Status</th>
                    <th data-orderable="false">Add Subcategory</th>
                    <th data-orderable="false">Action</th>
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
          <h4 class="modal-title">Add Service Subcategory</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="container">
            <form action="{{route('adminServiceSubCatStore')}}"  enctype="multipart/form-data" method="post" class="savecategoryform">
              @csrf
            <div class="form-group">
              <label>Category Name</label>
			  <input type="hidden" class="form-control category_id"  name="hid_subCategory" value="" readonly>
              <input type="text" name="category_name" class="form-control category_name" value="" readonly>
            </div>

            <div class="form-group">
              <label>Subcategory Name <span class="text-danger">*</span></label>
              <input type="text" name="subcategory_name"  placeholder="Enter Subcategory" class="form-control subcategory_name" onblur="allLetter(this)" >
               <div class="text-danger err-letter">{{ ($errors->has('subcategory_name')) ? $errors->first('subcategory_name') : '' }}</div>
            </div>


            <div class="form-group">
              <label>Sequence Number <span class="text-danger">*</span></label>
              <input type="number" class="form-control sequence_no" id="sequence_no" name="sequence_no" placeholder="Sequence Number" value="{{ old('sequence_no')}}" tabindex="3" onblur="validate_seqNo(this)" />
              <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
            </div>

            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="savesubcategorydata btn btn-icon icon-left btn-success"><i class="fas fa-check"></i>Save</button>
        <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>Close</button>
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
        } else alert('Sub Category Name Required');
    }); 
      
     var dataTable = $('#categoryTable').DataTable({
        "processing": true,
        "serverSide": true,
        "paging": true,
        "searching": true,
        columnDefs: [
          {
              targets: [2],
              className: "text-center",
          }
        ],
        "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("adminServiceCatGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
      '<option value="">Select Status</option>'+
      '<option value="active">Active</option>'+
      '<option value="block">Inactive</option>'+
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
      return true;
    }
    else {
      alert('Please input alphabet characters only');
      return false;
    }
  }
</script>
@endsection('middlecontent')
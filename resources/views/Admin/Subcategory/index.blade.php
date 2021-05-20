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
          <h4>All Subcategory List</h4>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="subcategoryTable">
                <thead>
                  <tr>
                    <th data-orderable="false">&nbsp;</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Sequence No</th>
                    <th data-orderable="false">Status</th>
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


<!---------- edit subcat------------>

 <div class="modal fade" id="savesubcategorymodal">
    <div class="modal-dialog">
      <div class="modal-content">
    
        <div class="modal-header">
          <h4 class="modal-title">Update Subcategory</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          
         <div class="container">
          <form action="{{route('adminSubcategoryStore')}}"  enctype="multipart/form-data" method="post" class="savecategoryform">
              @csrf
            <div class="form-group">
              <label>Category Name</label>
              <input type="hidden" class="form-control id" value="" name="id" readonly>
              <input type="text" class="form-control category_name" name="category_name" value="" readonly>
            </div>

            <div class="form-group">
              <label>Subcategory Name <span class="text-danger">*</span></label>
              <input type="text" name="subcategory_name" class="form-control subcategory_name"  required onblur="allLetter(this)">
               <div class="text-danger err-letter">{{ ($errors->has('subcategory_name')) ? $errors->first('subcategory_name') : '' }}</div>
            </div> 

            <div class="form-group">
              <label>Sequence Number <span class="text-danger">*</span></label>
              <input type="number" class="form-control sequence_no" id="sequence_no" name="sequence_no" placeholder="Sequence Number" tabindex="3"/>
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

<span content="{{ csrf_token() }}" class="csrf_token"></span>

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

  var dataTable = $('#subcategoryTable').DataTable({
        "processing": true,
        "serverSide": true,
        "paging": true,
        "searching": true,
         columnDefs: [
          {
              targets: [3],
              className: "text-center",
          }
        ],
        "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("adminSubcategoryGetRecords",$current_id)}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        },
        "initComplete":function( settings, json){
          
        }
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
      '<option value="">Select Status</option>'+
      '<option value="active">Active</option>'+
      '<option value="block">Inactive</option>'+
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
  $('#savesubcategorymodal').find('.sequence_no').val($(this).attr('sequence_no'));

  $('#savesubcategorymodal').modal('show');
  $('.modal-backdrop').attr('style','position: relative;');
}); 
           
            
$(document).on("click",".savesubcategorydata",function(event) {

  //savecategoryform
  if($('#savesubcategorymodal').find('.subcategory_name').val()!='') {
      $('.savecategoryform').submit();
  }
  else alert('Subcategory Name Required');

}); 


/*function to validate letters for sub category*/
  function allLetter(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      $('.err-letter').text('Please input alphabet characters only');
      return false;
    }
  }
</script>
@endsection('middlecontent')

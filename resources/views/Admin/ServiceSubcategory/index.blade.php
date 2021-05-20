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
         <strong>Success!</strong> <p id="success-message"></p>
         </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h4>All Service Subcategory List</h4>
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


<!---------- edit subub------------>

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
              <label>Subcategory Name</label>
              <input type="text" name="subcategory_name" class="form-control subcategory_name"  required>
            </div> 
            
          </form>
         </div>
     </div>
        
       <div class="modal-footer">
        <button type="submit" class="savesubcategorydata btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
              alert('Subcategory Name Required');
            }
           
              
            
            }); 


 
</script>
@endsection('middlecontent')

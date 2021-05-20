@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  
</div>
<div class="container">
  <!-- Example row of columns -->
  
  <div class="row">
    <div class="">
      <div class="col-md-12">
			
	  @include('Front.alert_messages')
	  <div class="card">
		<div class="card-header row">
		<div class="col-md-10">
		  <h2>Your Services</h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-1">
		  <a href="{{route('frontServiceCreate')}}" title="Add Services" class="btn btn-black btn-sm debg_color login_btn" ><span>Add Service</span> </a>
			</div>
		</div>

		<div class="card-body">
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="ServiceTable">
				<thead>
				  <tr>
				  
				  <th>Service</th>
				 
				             
				  <th>Sort order</th>
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
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/dataTables.bootstrap4.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
  var dataTable = $('#ServiceTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    columnDefs: [
          {
              targets: [1,2],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("frontServiceGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value="">Select Status</option>'+
  '<option value="active">Active</option>'+
  '<option value="block">Inactive</option>'+
  '</select></div>').appendTo("#ServiceTable_filter");
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });
 
</script>

@endsection
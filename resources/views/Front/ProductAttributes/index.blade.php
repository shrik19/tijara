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
		  <h2>Products Attributes</h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-1">
		  <a href="{{route('frontAttributeCreate')}}" title="Add Product Attribute" class="btn btn-black btn-sm debg_color login_btn" ><span>Add Attribute</span> </a>
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
                    <th>Attribute Name</th>
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
  var dataTable = $('#attributeTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
  
    "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("frontAttributeGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });

</script>

@endsection
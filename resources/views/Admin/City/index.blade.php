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
          <h4>All City List</h4>
           <a href="{{route('adminCityCreate')}}" title="Add City" class="btn btn-icon btn-success" style="margin-left:730px;"><span>Add City</span> </a>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="CityTable">
                <thead>
                  <tr>
                    <th data-orderable="false"></th>
                    <th>City</th>
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

<span content="{{ csrf_token() }}" class="csrf_token"></span>
<span content="{{ csrf_token() }}" class="csrf_token"></span>
<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
  var dataTable = $('#CityTable').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": true,
      "searching": true,
      "ajax": {
        headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
        url : '{{route("adminCityGetRecords")}}',
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
      '</select></div>').appendTo("#CityTable_wrapper .dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

</script>
@endsection('middlecontent')

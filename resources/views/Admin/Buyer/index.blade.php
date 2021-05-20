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
<h4>All Buyers List</h4>
<a href="{{route('adminBuyersCreate')}}" title="Add Buyers" class="btn btn-icon btn-success" style="margin-left:650px;"><span>Add Buyers</span> </a>
</div>
<div class="card-body">
<form id="vendorMultipleAction" action="" method="post">
@csrf
<div class="table-responsive">
<table class="table table-striped" id="buyersTable">
<thead>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>Email</th>
<th>Where Find Us</th>
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

<!-- Change Password Form -->



<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">

  var dataTable = $('#buyersTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,

    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminBuyersGetRecords")}}',
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
  '</select></div>').appendTo("#buyersTable_length");

  $('<span class="col-md-2 export btn export-btn">'+
  'Export</span></div>').appendTo("#buyersTable_filter");

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
      url: "{{url('/')}}"+'/admin/buyers/exportdata/?status='+$('#status').val()+'&search='+$('#buyersTable_filter').find('input').val(),
      type: 'get',
      data: { },

      success: function(output){
        url="{{url('/')}}"+'/BuyerDetails/BuyesfromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

</script>

@endsection('middlecontent')

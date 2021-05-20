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
          <h4>All Seller List</h4>
          <a href="{{route('adminSellerCreate')}}" title="Add Seller" class="btn btn-icon btn-success" style="margin-left:650px;"><span>Add Seller</span> </a>
        </div>

        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="sellerTable">
                <thead>
                  <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Store Name</th>
                  <th>City</th>
                  <th>Where Find Us</th>
                  <th>show Packages</th>
                  <th>Is Verified</th>
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

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.buttons.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/buttons.html5.min.js"></script>
<script type="text/javascript">
  var dataTable = $('#sellerTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    columnDefs: [
          {
              targets: [4,5],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminSellerGetRecords")}}',
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
  '</select></div>').appendTo("#sellerTable_length");

  $('<span class="export btn button export-btn">'+
  'Export</span></div>').appendTo("#sellerTable_wrapper .dataTables_filter");

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
      url: "{{url('/')}}"+'/admin/seller/exportdata/?status='+$('#status').val()+'&search='+$('#sellerTable_filter').find('input').val(),
      type: 'get',
      data: { },
      success: function(output){
        url="{{url('/')}}"+'/SellerDetails/SellerFromTijara.csv';
        window.open(url,"_self")
      }
    });
  });

</script>
@endsection('middlecontent')

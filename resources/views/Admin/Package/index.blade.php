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
          <h4>Add Package List</h4>
          <a href="{{route('adminPackageCreate')}}" title="Add Package" class="btn btn-icon btn-success" style="margin-left:650px;"><span>Add Package</span> </a>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
          @csrf
          <div class="table-responsive">
            <table class="table table-striped" id="packageTable">
            <thead>
              <tr>
                <th>Package</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Valid days</th>
                <th>Dated</th>
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
  var dataTable = $('#packageTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("adminPackageGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
      },
      type:'post',
    },
  });

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
        url: "{{url('/')}}"+'/admin/seller/exportdata/?status='+$('#status').val()+'&search='+$('#packageTable_filter').find('input').val(),
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

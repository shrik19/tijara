@extends('Admin.layout.template')
@section('middlecontent')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{url('/')}}/assets/admin/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/css/datepicker.min.css">
<style>
  .jquery-datepicker
  {
    z-index:1 !important;
  }
  table.dataTable tbody td {
    vertical-align: middle;
  }
</style>
<div class="section-body">
  <div class="row">
    <div class="col-12">
      @include('Admin.alert_messages')
      <div class="card">
        <div class="card-header">
          <h4>All Baskets</h4>
           <a href="{{route('adminBasketsCreate')}}" title="Add Basket" class="btn btn-icon btn-success" style="margin-left:658px;"><span>Add Basket</span> </a>
          <span class="export btn button btn-primary" style="cursor: pointer;margin-left:10px">
      Export</span>
        </div>
        <div class="card-body">
            <form id="basketMultipleAction" action="{{route('adminBasketsMultipleAction')}}" method="post">
            @csrf
            <input type="hidden" id="currentView" name="currentView" value="">
            <div class="table-responsive">
              <table class="table table-striped" id="basketTable">
                <thead>
                  <tr>
                    <th data-orderable="false">Image</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>Basket Type</th>
                    <th>Price</th>
                    <th>Expiry</th>
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
<script src="{{url('/')}}/assets/js/datepicker.min.js"></script>

<script type="text/javascript">

  var user_id      = '{{(isset($_GET["user"])) ? $_GET["user"] : ''}}';
  var title    = '{{(isset($_GET["title"])) ? $_GET["title"] : ''}}';
  var basket_type    = '{{(isset($_GET["basket_type"])) ? $_GET["basket_type"] : ''}}';
  var status  = '{{(isset($_GET["status"])) ? $_GET["status"] : ''}}';
  
  var dataTable = $('#basketTable').DataTable({
        "processing": true,
        "serverSide": true,
        "paging": true,
        "searching": true,
        "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("adminBasketsGetRecords")}}',
          data: function(data){
              data.status = $("#status").val();
              data.basket_type = $("#basket_type").val();
              data.basket_expiry = $("#basket_expiry").val();
              data.user_id = user_id;
          },
          type:'post',
          "initComplete":function( settings, json){
            //$('#basket_expiry').datepicker({ defaultViewDate: {} });
            
          }
      }
  });

  $('<div class="row"><div class="col-md-4"><select class="form-control" id="basket_type" name="basket_type">'+
    '<option value="">Select Basket Type</option>'+
      '<option value="Collection only">Collection only</option>'+
      '<option value="Multiple address">Multiple address</option>'+
      '<option value="Delivery only">Delivery only</option>'+
      '</select></div><div class="col-md-4"><select class="form-control" id="status" name="status">'+
      '<option value="">Select Status</option>'+
      '<option value="active">Active</option>'+
      '<option value="block">Inactive</option>'+
      '<option value="cancelled">Cancelled</option>'+
      '<option value="draft">Draft</option>'+
      '</select></div><div class="col-md-4"><input type="text" class="form-control" name="basket_expiry" id="basket_expiry" data-toggle="datepicker" autocomplete="off" data-toggle="datepicker" placeholder="Expiry Date"><input type="text" class="form-control" name="sel_basket_expiry" id="sel_basket_expiry" style="display:none;"></div></div>').appendTo("#basketTable_wrapper .dataTables_filter");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

  $('#basket_type').change(function(){
    dataTable.draw();
  });

  const myDatePicker = $('[data-toggle="datepicker"]').datepicker({
    format:'yyyy-mm-dd',
    autoHide: true,
  });

myDatePicker.on('pick.datepicker', function(e){

  var month = e.date.getMonth()+1;
  if(month < 9)
  {
    month = '0'+month;
  }
  var day = e.date.getDate();
  if(day < 9)
  {
    day = '0'+day;
  }
  $("#sel_basket_expiry").val(e.date.getFullYear()+'-'+(month)+'-'+day);
  setTimeout(function(){ dataTable.draw(); }, 100);

});
//excel export
  $('.export').click(function(){
      $('#exportval').val(1);
      dataTable.draw();
      $('#exportval').val('0');
        
      $.ajax({
      url: "{{url('/')}}"+'/admin/baskets/exportdata/?status='+$('#status').val()+'&basket_type='+$('#basket_type').val()+'&basket_expiry='+$('#basket_expiry').val()+'&search='+$('#basketTable_filter').find('input').val(),
      type: 'get',
      data: { },

      success: function(output){
          url="{{url('/')}}"+'/BasketFiles/BasketsData.csv';
          window.open(url,"_self")
      // window.open(url.replace(/\s+/g, '-'), '_blank');
      } 
    });
      
  });
  </script>
@endsection('middlecontent')

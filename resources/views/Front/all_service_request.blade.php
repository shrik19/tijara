@extends('Front.layout.template')
@section('middlecontent')

<div class=" p_155">
<div class="container-fluid">
  <div class="container-inner-section-1 tjd-sellcontainer">
  <!-- Example row of columns -->
  
  <div class="row">
    @if($is_seller==1)
    <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
      @else
      <div class="col-md-12 tijara-content">
    @endif


	  @include('Front.alert_messages')
	 <div class="seller_info">
       <div class="card">
       <div class="card-header row seller_header">
        <h2 class="page_heading">@if($is_seller==1) {{ __('lang.all_service_request')}} @else {{ __('users.my_booking_title')}} @endif</h2>
        <!-- <hr class="heading_line"/> -->
        </div>
      </div>
    <div class="seller_mid_cont">
       <div class="col-md-12" style="margin-top: 20px;">
        <div class="tijara-info-section">
           <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
        <p  class="buyer-prod-content">{{__('messages.service_booking_msg')}}</p>
        </div>
      </div>

	 <div class="col-md-12">
    <div class="card-body">
	
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="serviceRequestTable">
				<thead>
				  <tr>
				  <th data-orderable="false">{{ __('lang.service_no_head')}}</th>
          @if($is_seller)
          <th data-orderable="false">{{ __('lang.txt_name')}}</th>
          @endif
          <th data-orderable="false">{{ __('lang.sevice_name_head')}}</th>
				  <th data-orderable="false">{{ __('lang.service_time')}}</th>
				  <th data-orderable="false">{{ __('lang.service_total_cost')}}</th>
				  <th data-orderable="false">{{ __('lang.location')}}</th>
				  <th data-orderable="false">{{ __('lang.request_date')}}</th>
				  <th data-orderable="false">{{ __('lang.action_label')}}</th>
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
  </div>
</div>
</div> <!-- /container -->
</div>
<!-- add subcategory model Form -->
 <div class="modal fade" id="serviceReqDetailsmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('lang.service_req_details')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <table>
           <!--  <tr><td style="font-weight: 700px;"></td>:<td></td></tr> -->
           @if(session('role_id')==2)  
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.cust_label')}} {{ __('lang.txt_name')}} :</td><td class="user_name" style="padding-left: 10px;"></td></tr>
            @endif
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.sevice_name_head')}} :</td><td class="title" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_label')}} {{ __('lang.product_description_label')}} :</td><td class="description" style="padding-left: 10px;"></td></tr>
            
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_time')}} :</td><td class="service_time" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_total_cost')}} :</td><td class="service_price" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.location')}} :</td><td class="location" style="padding-left: 10px;"></td></tr>

          </table>
        </div>
              
      </div>
    </div>
  </div>
  </div>
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/dataTables.bootstrap4.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
  
$( document ).ready(function() {
   jQuery.noConflict();
   $(".serviceReqDetails").css({"margin-left": ""});
});

  var serviceRequestTable = $('#serviceRequestTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    "order": [[ 0, "desc" ]],
    "language": {
        "sSearch": "<?php echo __('lang.datatables.search');?>",
        "sInfo": "<?php echo __('lang.datatables.sInfo');?>",
        "sLengthMenu": "<?php echo __('lang.datatables.sLengthMenu');?>",
        "sInfoEmpty": "<?php echo __('lang.datatables.sInfoEmpty');?>",
        "sLoadingRecords": "<?php echo __('lang.datatables.sLoadingRecords');?>",
        "sProcessing": "<?php echo __('lang.datatables.sProcessing');?>",      
        "sZeroRecords": "<?php echo __('lang.datatables.sZeroRecords');?>",
        "oPaginate": {
              "sFirst":    "<?php echo __('lang.datatables.first');?>",
              "sLast":    "<?php echo __('lang.datatables.last');?>",
              "sNext":    "<?php echo __('lang.datatables.next');?>",
              "sPrevious": "<?php echo __('lang.datatables.previous');?>",
          },
        },
    columnDefs: [
          {
              targets: [1,2],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("frontServiceRequestGetRecords")}}',
      'data': function(data){
        data.monthYear = $("#monthYear").val();
        data.is_seller = "{{$is_seller}}"
        data.user_id = "{{$user_id}}"
      },
       type:'post',
    },
  });

  $("<div class='form-group' style='float:right;'>"+
  
  "<?php echo $monthYearHtml; ?>"+
  "</div>").appendTo("#serviceRequestTable_length");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#monthYear').change(function(){

    serviceRequestTable.draw();
    
  });

  $(document).on("click",".serviceReqDetails",function(event) {      
  
    jQuery.noConflict();
        $('#serviceReqDetailsmodal').find('.id').text($(this).attr('id'));
        $('#serviceReqDetailsmodal').find('.user_name').text($(this).attr('user_name'));
        $('#serviceReqDetailsmodal').find('.title').text($(this).attr('title'));
        $('#serviceReqDetailsmodal').find('.description').text($(this).attr('description'));
        $('#serviceReqDetailsmodal').find('.location').text($(this).attr('location'));
        $('#serviceReqDetailsmodal').find('.service_time').text($(this).attr('service_time'));
        $('#serviceReqDetailsmodal').find('.service_price').text($(this).attr('service_price'));

        $('#serviceReqDetailsmodal').modal('show');
        //$('.modal-backdrop').attr('style','position: relative;');
    }); 

</script>

@endsection
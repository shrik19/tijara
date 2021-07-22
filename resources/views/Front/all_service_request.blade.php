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
		<div class="col-md-11">
		    
		  <h2>{{ __('lang.all_service_request')}}</h2>
		  <hr class="heading_line"/>
		  </div>
		</div>

		<div class="card-body">
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th data-orderable="false">{{ __('lang.service_no_head')}}</th>
          @if($is_seller)
          <th data-orderable="false">{{ __('lang.txt_name')}}</th>
          @endif
          <th data-orderable="false">{{ __('lang.sevice_name_head')}}</th>
				  <th data-orderable="false">{{ __('lang.message_head')}}</th>
				  <th data-orderable="false">{{ __('lang.txt_date')}}</th>
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
</div> <!-- /container -->

<!-- add subcategory model Form -->
 <div class="modal fade" id="serviceReqDetailsmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('lang.service_req_details')}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <table>
           <!--  <tr><td style="font-weight: 700px;"></td>:<td></td></tr> -->
           @if(session('role_id')==2)  
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.cust_label')}} {{ __('lang.txt_name')}} :</td><td class="user_name" style="padding-left: 10px;"></td></tr>
            @endif
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.sevice_name_head')}} :</td><td class="title" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_label')}} {{ __('lang.product_description_label')}} :</td><td class="description" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.cust_label')}} {{ __('lang.message_head')}} :</td><td class="message" style="padding-left: 10px;"></td></tr>

            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_price')}} :</td><td class="service_price" style="padding-left: 10px;"></td></tr>

            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.price_type')}} :</td><td class="price_type" style="padding-left: 10px;"></td></tr>

            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_time')}} :</td><td class="service_time" style="padding-left: 10px;"></td></tr>
          </table>
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
  var dataTable = $('#productTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
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
        data.status = $("#status").val();
        data.is_seller = "{{$is_seller}}"
        data.user_id = "{{$user_id}}"
      },
       type:'post',
    },
  });

  $(document).on("click",".serviceReqDetails",function(event) {      
  
    jQuery.noConflict();
        $('#serviceReqDetailsmodal').find('.id').text($(this).attr('id'));
        $('#serviceReqDetailsmodal').find('.user_name').text($(this).attr('user_name'));
        $('#serviceReqDetailsmodal').find('.title').text($(this).attr('title'));
        $('#serviceReqDetailsmodal').find('.description').text($(this).attr('description'));
        $('#serviceReqDetailsmodal').find('.message').text($(this).attr('message'));
        $('#serviceReqDetailsmodal').find('.service_time').text($(this).attr('service_time'));
        $('#serviceReqDetailsmodal').find('.price_type').text($(this).attr('price_type'));
        $('#serviceReqDetailsmodal').find('.service_price').text($(this).attr('service_price'));

        $('#serviceReqDetailsmodal').modal('show');
        //$('.modal-backdrop').attr('style','position: relative;');
    }); 

</script>

@endsection
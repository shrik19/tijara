@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
 label{
  margin-left: 8px;
}
</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  
  <div class="row">
  <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10 tijara-content margin_bottom_class">
         @include('Front.alert_messages')
         @if($subscribedError)
        <div class="alert alert-danger">{{$subscribedError}}</div>
        @endif
      <div class="seller_info">
		
	 
	   
	  <div class="card">
		<div class="card-header row seller_header">
		  <h2 class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.service_label')}}</h2>		 
		</div>

		<div class="card-body seller_mid_cont">
    <div class="mb-10 pro-top-btn">
		  <a href="{{route('frontServiceCreate')}}" title="{{ __('servicelang.add_service')}}" class="btn btn-black btn-sm debg_color login_btn a_btn" style="margin-right: -14px;"><span>{{ __('servicelang.add_service')}}</span> </a>
			</div>
      <div class="clearfix"></div>
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="serviceTable">
				<thead>
				  <tr>
          <th class="product_table_heading" data-orderable="false">{{ __('lang.image_label')}}</th>
				  <th class="product_table_heading">{{ __('servicelang.service_label')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.category_label')}}</th> 
          <th class="product_table_heading" >{{ __('lang.price_label')}}</th>
				  <th class="product_table_heading">{{ __('lang.sort_order_label')}}</th>
				  <th class="product_table_heading">{{ __('lang.dated_label')}}</th>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.action_label')}}</th>
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
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/dataTables.bootstrap4.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">

<script src="{{url('/')}}/assets/front/js/jquery-confirm.min1.js"></script>

<script type="text/javascript">
  var dataTable = $('#serviceTable').DataTable({
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
              targets: [2,3],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '{{route("frontServiceGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
		data.category = $("#selectcategory").val();
		data.subcategory = $("#selectsubcategory").val();
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control tjselect" id="status" name="status">'+
  '<option value="">{{ __("lang.status_label")}}</option>'+
  '<option value="active">{{ __("lang.active_label")}}</option>'+
  '<option value="block">{{ __("lang.block_label")}}</option>'+
  '</select></div>').appendTo("#serviceTable_filter");
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control tjselect" id="selectsubcategory" name="subcategory">'+
  
  '<?php echo $subCategoriesHtml; ?>'+
  '</select></div>').appendTo("#serviceTable_length");
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control tjselect" id="selectcategory" name="category">'+
  
  '<?php echo $categoriesHtml; ?>'+
  '</select></div>').appendTo("#serviceTable_length");
  
  
  
  
  $('#serviceTable_filter').parent('div').attr('class','col-xs-5');
  $('#serviceTable_length').parent('div').attr('class','col-xs-7');
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });
  $('#selectcategory').change(function(){
	  var id =	$(this).val();
	  
	  $('#selectsubcategory').val('');
	  
	  $(".subcatclass").each(function() {
		  $(this).hide();
		});
	  
	  $("#subcat"+id).each(function() {
		  $(this).show();
		});
    dataTable.draw();
  });
	$('#selectsubcategory').change(function(){
		dataTable.draw();
  });
</script>

@endsection
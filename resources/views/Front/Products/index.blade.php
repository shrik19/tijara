@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
 label{
  margin-left: 8px;
}
@media(max-width:767px){
  #selectcategory {
    max-width: 140%;
  }
  #productTable_filter:before, #productTable_filter:after{
      content: '';
      display: table;
      width: 100%;
  }
  #productTable_filter label{
    max-width: 50% !important;
    margin-right:20px !important;
    display: block;
  }
  #productTable_filter label input{
    max-width: 80%;
  }
}
</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1 tjd-sellcontainer">
  <!-- Example row of columns -->
  
  <div class="row">
  <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10 tijara-content margin_bottom_class">
           
            @if($subscribedError)
              <div class="alert alert-danger update-alert-css">{{$subscribedError}}</div>
            @endif

             @include('Front.alert_messages')
      <div class="seller_info">
		

	   
	  <div class="card">
		<div class="card-header row seller_header">
		  <h2  class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.your_products_label')}}</h2>		  
		</div>

		<div class="card-body seller_mid_cont">
    <div class="mb-10 pro-top-btn">
		  <a href="{{route('frontProductCreate')}}" title="{{ __('lang.add_product')}}" class="btn btn-black btn-sm debg_color a_btn login_btn" style="margin-right: -14px;"><span>{{ __('lang.add_product')}}</span> </a>
			</div>
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th class="product_table_heading" data-orderable="false">{{ __('lang.image_label')}}</th>
				  <th class="product_table_heading" >{{ __('lang.product_label')}}</th>
				  <th class="product_table_heading" >{{ __('lang.sku_label')}}</th>
				  <th class="product_table_heading" >{{ __('lang.price_label')}}</th>
				  <th class="product_table_heading"  data-orderable="false">{{ __('lang.category_label')}}</th> 
				  <th class="product_table_heading" >{{ __('lang.sort_order_label')}}</th>
				  <th class="product_table_heading" >{{ __('lang.dated_label')}}</th>
				  <th class="product_table_heading"  data-orderable="false">{{ __('lang.action_label')}}</th>
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
      url : '{{route("frontProductGetRecords")}}',
      'data': function(data){
        data.status = $("#status").val();
		data.category = $("#selectcategory").val();
		data.subcategory = $("#selectsubcategory").val();
      },
       type:'post',
    },
  });
	$('#productTable_filter').parent('div').attr('class','col-xs-5');
  $('#productTable_filter').find('label').removeClass('pull-right');
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control tjselect" id="status" name="status">'+
  '<option value="">{{ __("lang.status_label")}}</option>'+
  '<option value="active">{{ __("lang.active_label")}}</option>'+
  '<option value="block">{{ __("lang.block_label")}}</option>'+
  '</select></div>').appendTo("#productTable_filter");
  
  $('#productTable_length').find('label').attr('style','float: left;');
  $('#productTable_length label').find('select').addClass('tjselect');
  $('#productTable_length').parent('div').attr('class','col-xs-7');
  $('<div class="form-group col-xs-3 tjd-prodcatdropdown" style="margin-right: 6%;"><select class="form-control tjselect abc" id="selectcategory" name="category">'+
  
  '<?php echo $categoriesHtml; ?>'+
  '</select></div>').appendTo("#productTable_length");
  
  
  
  $('<div class="form-group col-xs-3" style=""><select class="form-control tjselect" id="selectsubcategory" name="subcategory">'+
  
  '<?php echo $subCategoriesHtml; ?>'+
  '</select></div>').appendTo("#productTable_length");
  
  
  
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
	  
	  $(".subcat"+id).each(function() {
      $(this).show();
		});
    dataTable.draw();
  });
	$('#selectsubcategory').change(function(){
		dataTable.draw();
  });
</script>
<style>
#productTable{
	width:auto !important;
}
@media(max-width:767px){
 #productTable{
  width:100% !important;
} 
}
</style>

@endsection
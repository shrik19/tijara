@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  label{
    margin-left: 20px;
  }
  @media(max-width:767px){
    #attributeTable_length {
      text-align: left;
    }
    div#attributeTable_filter {
        text-align: left;
    }
    label {
        margin-left: 0;
    }
    div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:first-child {
        padding-left: 15px !important;
        padding-right: 15px !important;
        padding-top: 10px !important;
    }
    #attributeTable_length select {
        display: block;
    }

    #attributeTable_filter input.form-control {
        display: block;
        margin-left: 0 !important;
    }

    #attributeTable_filter label {
      padding-top: 10px !important;
      display: block;
    }
    div#attributeTable_wrapper table.dataTable {
      width: 100% !important;
    }
    #attributeTable_length .select2-container{
      display: block;
    }
    #attributeTable_wrapper .col-sm-5.col-xs-8 {
      padding-right:15px !important;
    }
    #attributeTable_wrapper .col-sm-5.col-xs-8 input{
      width: 100% !important;
    }
    #attributeTable_wrapper .row:nth-child(2) .col-sm-12 {
      padding: 0 !important;
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
		  <h2 class="seller_page_heading pl-0"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.add_attribute')}} </h2>
		</div>
<div class="clearfix"></div>
<div class="mb-10 pro-top-btn package_history_btn">
		  <a href="{{route('frontAttributeCreate')}}" title="{{ __('lang.add_attribute_lable')}}" class="btn btn-black btn-sm debg_color login_btn" ><span>{{ __('lang.add_attribute_lable')}}</span> </a>
			</div>
      <br/><br/>
      <div class="clearfix"></div>
		<div class="card-body seller_mid_cont">
 
		  <form id="" action="" method="post">
			@csrf
			<div class="table-responsive">
			   <table class="table table-striped" id="attributeTable">
                <thead>
                  <tr>
                    <th class="product_table_heading" data-orderable="false">&nbsp;</th>
                    <th class="product_table_heading" >{{ __('lang.attribute_label')}}</th>
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
    "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '{{route("frontAttributeGetRecords")}}',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });
  $('#attributeTable_filter').parent('div').attr('class','col-sm-5 col-xs-8');
  $('#attributeTable_length').parent('div').attr('class','col-sm-7 col-xs-4');
  $('#attributeTable_length label').find('select').addClass('tjselect');


</script>

@endsection
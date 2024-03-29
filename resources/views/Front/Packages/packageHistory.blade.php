@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section sellers_top_padding">
<div class="container-fluid">
<div class="container-inner-section-1 tjd-sellcontainer">
  <div class="row">
      @include ('Front.alert_messages')
      <!-- html for seller subscribe packages -->
    	<div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
      	<div class="col-md-10 tijara-content" style="margin-bottom: 30px;">

		<div class="seller_info">
		<div class="seller_header">
      	    <h2 class="seller_page_heading"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('users.your_package_history')}}</h2>
        	<!-- <hr class="heading_line"/> -->
		</div>
		<div class="col-md-12">
	      	<form id="vendorMultipleAction" action="" method="post">
				@csrf
				<div class="table-responsive">
				  <table class="table table-striped" id="packageHistTable">
					<thead>
					  <tr>
					  <th class="product_table_heading" data-orderable="false">{{ __('users.sr_no_thead')}}</th>
					  <th class="product_table_heading" >{{ __('users.package_name_thead')}}</th>
					  <th class="product_table_heading" >{{ __('users.start_date_thead')}}</th>
					  <th class="product_table_heading" >{{ __('users.end_date_thead')}}</th>
					  </tr>
					</thead>
					  <tbody id="result">
						<?php
						if(count($details) !=0){

						$i=1;
						foreach($details as $data){ ?>
						 <?php 
						   if($data['is_trial']==1){
							  $title='Tijara Trial';
							  $start_date=date("Y-m-d",strtotime($data['trial_start_date']));
							  $end_date=date("Y-m-d",strtotime($data['trial_end_date']));
							  $exp_date=strtotime($data['trial_end_date']);
						   }else{
							  $title=$data['title'];
							  $start_date=date("Y-m-d",strtotime($data['start_date']));
							  $end_date=date("Y-m-d",strtotime($data['end_date']));
							  $exp_date=strtotime($data['end_date']);
						   }
						 ?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $title;?></td>
							<td><?php echo $start_date;?></td>
							<td><?php echo $end_date;?></td>
						</tr>
					  <?php    $i++;
						} 
						}else{ ?>
							  <td colspan="6" class="text-center">
							{{ __('lang.datatables.sEmptyTable')}}
							</td>
					   <?php  } ?>

					  </tbody>
				  </table>
				</div>
			  </form> 
		</div>
			</div>
			</div>
</div> <!-- /container -->
</div>
</div>
</div>
@endsection
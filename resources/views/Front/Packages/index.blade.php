@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section sellers_top_padding">
<div class="containerfluid">
<div class="container-inner-section-1">
  <div class="row">
	  <!-- <div class="col-md-12"> -->
      @include ('Front.alert_messages')
      <!-- html for seller subscribe packages -->
    	<div class="col-md-2 tijara-sidebar">
        @include ('Front.layout.sidebar_menu')
      </div>
      	<div class="col-md-10" style="margin-bottom: 30px;">
  		@if(!empty($package_exp_msg))
  		<div class="alert alert-danger" role="alert">
		  {{$package_exp_msg}}
		</div>
		@endif

		@if(!empty($trial_package_msg))
  		<div class="alert trial_package_msg" role="alert">
		  {{$trial_package_msg}}
		</div>
		@endif

		<div class="seller_info">
				@php
					$active ='package-basic';
					$inactive = 'package-pro';
				@endphp
		@if(count($subscribedPackage) != 0 && !empty($subscribedPackage))
		<div class="seller_header">
      	    <h2 class="seller_page_heading">{{ __('users.your_active_package')}}</h2>
        	<!-- <hr class="heading_line"/> -->
		</div>
		<div class="col-md-12 package_history_btn"><a href="{{route('frontPackage', base64_encode($user_id))}}" class="btn btn-black btn-sm debg_color a_btn login_btn pull-right">{{ __('users.history_button')}}</a></div>
	
	      	@foreach($subscribedPackage as $row)
	      		@if($is_trial == 1) 
			 <div class="col-md-6 ">
			 
				<div class="panel panel-default subscribe-packages package_width">
				<div class="panel-heading bold package_heading {{ $active }}">Tijara Trial</div>
				<div class="panel-body package-body">
					<table class="table" style="border: 0px;max-height: 365px;overflow: auto;">
					  <tbody class="">
					  	 <tr>
					  		<td>{{ __('users.amount_label')}}</td>
					  		<td> Free </td>
					    </tr>
					    <tr>
					  		<td>{{ __('users.validity_label')}}</td>
					  		<td>30 Days.</td>
					    </tr>
				        <tr>
					  		<td >{{ __('users.purchased_date_label')}}</td>
					  		@if($row->trial_start_date >= date('Y-m-d H:i:s'))
					  			<td>{{date('l, d F Y',strtotime($row->trial_start_date))}}</td>
					  			
					  		@else
					  			<td>{{date('l, d F Y',strtotime($row->trial_start_date))}}</td>
					  		@endif
					    </tr>
					     <tr>
					  		<td >{{ __('users.expiry_date_label')}}</td>
					  		<td>{{date('l, d F Y',strtotime($row->trial_end_date))}}</td>
					    </tr>
					  <!--   <tr>
					    		<td><a href="javascript:void(0)" class="btn btn-success tj-btn-sucess"> {{ __('users.activated')}} </a></td>
					    </tr> -->
					     <tr>
					    	<td >{{ __('lang.status_label')}}</td>
					    	@if(($row->trial_start_date >= date('Y-m-d H:i:s')))
					  			<td><a href="javascript:void(0)" class="btn btn-warning tj-btn-waring"> {{ __('users.not_activated_label')}}</a></td>
					  		@elseif($row->trial_end_date >= date('Y-m-d H:i:s'))
					  		<td>					  			
					  			<a href="" class="btn btn-warning tj-btn-sucess"> {{ __('users.activated')}}</a>
					  		</td>
					  		@elseif($row->status=="active")
					  			<td><a href="javascript:void(0)" class="btn btn-success tj-btn-sucess"> {{ __('users.activated')}} </a></td>
					  		@endif
					    </tr>
					  </tbody>
					</table>
				</div>
					<div class="panel-heading bold package_footer  	@if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=='CAPTURED' ){{$inactive}} 	@elseif($row->status=='active') {{ $active }}@endif"></div>
				</div></div>
		@endif
	      	 <div class="col-md-6 ">
				  
				   <?php 
				   $class=$inactive;
				   	if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=='CAPTURED' ){
				   		$class = $inactive;
				   	} elseif($row->start_date <= date('Y-m-d H:i:s') && $row->payment_status=='CAPTURED' && $row->end_date >= date('Y-m-d H:i:s')){
				   		$class = $active;
				   	}elseif($row->status=='block' && $row->start_date >= date('Y-m-d H:i:s') ){
				   		$class = $inactive;
				   	}
				   ?>
				<div class="panel panel-default subscribe-packages package_width">
				<div class="panel-heading bold package_heading {{$class}}">{{$row->title}}</div>
				<div class="panel-body package-body">
					<table class="table" style="border: 0px;max-height: 365px;overflow: auto;">
					  <tbody class="">
					  	<?php /* <tr>
					  		<td class="bold">{{ __('users.description_label')}}</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr> */?>

					    <tr>
					  		<td>{{ __('users.amount_label')}}</td>
					  		<td> {{$row->amount}} kr</td>
					    </tr>
					    <tr>
					  		<td>{{ __('users.validity_label')}}</td>
					  		<td>{{$row->validity_days}} Days.</td>
					    </tr>
					    <tr>
					  		<td >{{ __('users.purchased_date_label')}}</td>
					  		@if($row->start_date >= date('Y-m-d H:i:s'))
					  			<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					  			
					  		@else
					  			<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					  		@endif
					    </tr>
					    <tr>
					  		<td >{{ __('users.expiry_date_label')}}</td>
					  		<td>{{date('l, d F Y',strtotime($row->end_date))}}</td>
					    </tr>
					    <tr>
					    	<td >{{ __('lang.status_label')}}</td>
					    	@if(($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=="CAPTURED") || $row->start_date >= date('Y-m-d H:i:s'))
					  			<td><a href="javascript:void(0)" class="btn btn-warning tj-btn-waring"> {{ __('users.not_activated_label')}}</a></td>
					  		@elseif($row->payment_status=="checkout_incomplete")
					  		<td><a href="javascript:void(0)" class="btn btn-danger"> {{ __('lang.pending_label')}}</a>
					  			<p style="font-weight: bold;margin-top: 20px;margin-left:-108px;color: green"> {{ __('messages.payment_in_process')}}</p>
					  			<a href="" class="btn btn-info" style="margin-left: 114px;margin-top: -60px"> Reload</a>
					  		</td>
					  		@elseif($row->payment_status =="")
					  		<td><a href="javascript:void(0)" class="btn btn-success tj-btn-sucess"> 
					  		Payment Pending </a></td>
					  		@elseif($row->status=="active")
					  			<td><a href="javascript:void(0)" class="btn btn-success tj-btn-sucess"> {{ __('users.activated')}} </a></td>
					  		@endif
					    </tr>
					  	
					  </tbody>
				    </table>
				</div>

				<div class="panel-heading bold package_footer {{$class}}"></div>
				</div>
			</div>
			@endforeach
		@endif
		
		@if(count($packageDetails) != 0 && !empty($packageDetails))
			  <div class="col-md-12">
				<h2 class="page_heading" style="margin: 60px 0px 30px 0px;">{{ __('users.subscribe_package_label')}} </h2>
			
					@foreach($packageDetails as $data)
					 <div class="col-md-6">
						<div class="panel panel-default subscribe-packages">
						<div class="panel-heading package_heading package-pro">{{$data['title']}}</div>
						
				<div class="panel-body package-body">
					<table class="table" style="border: 0px;max-height: 365px;overflow: auto;">
					  <tbody class="">
					  	<?php /* <tr>
					  		<td class="bold">{{ __('users.description_label')}}</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr> */?>
					    <input type="hidden" name="selected_package_id" id="selected_package_id" class="selected_package_id" value="{{$data['id']}}">
					    <input type="hidden" id="validity_days" name="validity_days" value="{{$data['validity_days']}}">
					    <tr>
					  		<td cf>{{ __('users.description_label')}}</td>
					  		<td class="short-desc">{{ str_replace("&nbsp;", "",preg_replace('#<[^>]+>#', ' ', @$data['description']))}}</td>
					    </tr>
					    <tr>
					  		<td>{{ __('users.amount_label')}}</td>
					  		<td> {{$data['amount']}} kr</td>
					    </tr>
					    <tr>
					  		<td>{{ __('users.validity_label')}}</td>
					  		<td>{{$data['validity_days']}} Days.</td>
					    </tr>
					  
					    <tr>
					    	<td >
					  		<button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn  tj-btn-sucess" onclick="change_package(<?php echo $data['id']?>,<?php echo $data['validity_days']; ?>)" selected_package_id="{{$data['id']}}" validity_days="{{$data['validity_days']}}">{{ __('users.subscribe_btn')}}</button>
					  		</td>
					    </tr>
					  	
					  </tbody>
				    </table>
				</div>
						<div class="panel-heading bold package_footer package-pro"></div>
						</div>
					</div>
				
					@endforeach
			   </div>
			   @endif
			
			</div>
			</div>
<!-- </div> -->
</div> <!-- /container -->
</div>
</div>
<script type="text/javascript">
	function change_package(id,val){
		/*alert(id)
		var package_id = $(this).attr('selected_package_id');
		var validity_days       = $(this).attr('validity_days');
		alert(selected_package_id)*/
		$.ajax({
	      url:siteUrl+'/select-package',
	      type: 'get',
	      data: { package_id:id,validity_days:val},
	      success: function(output){
	      	var responseObj = $.parseJSON(output);
	      	if(responseObj.status ==1){
	      		showSuccessMessage(responseObj.msg,'reload');
	      	}else{
	      		showErrorMessage(responseObj.msg);
	      	}
	       // slug = output;
	      }
	    });
	}
	/*$("#btnsubscribePackage").on('click', function(event){
		var package_id = $("#selected_package_id").val();
		var validity_days = $("#validity_days").val();
		$.ajax({
	      url:siteUrl+'/select-package',
	      type: 'get',
	      data: { package_id:package_id,validity_days:validity_days},
	      success: function(output){
	      	var responseObj = $.parseJSON(output);
	      	if(responseObj.status ==1){
	      		showSuccessMessage(responseObj.msg,'reload');
	      	}else{
	      		showErrorMessage(responseObj.msg);
	      	}
	      	
	       // slug = output;
	      }
	    });
	});*/
</script>
@endsection
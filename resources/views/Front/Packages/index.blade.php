@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="containerfluid">
<div class="container-inner-section-1">
  <div class="row">
	  <div class="col-md-12">
      @include ('Front.alert_messages')
      <!-- html for seller subscribe packages -->
    	<div class="col-md-2 tijara-sidebar">
        @include ('Front.layout.sidebar_menu')
      </div>
      	<div class="col-md-10">
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
      	    <h2 class="page_heading">{{ __('users.your_active_package')}}</h2>
        	<!-- <hr class="heading_line"/> -->
		</div>
	      	@foreach($subscribedPackage as $row)
	      	
	      	 <div class="col-md-6 ">
				   <br/><br/>
				<div class="panel panel-default subscribe-packages package_width">
				<div class="panel-heading bold package_heading @if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=='CAPTURED' ){{$inactive}} 	@elseif($row->status=='active') {{ $active }}@endif">{{$row->title}}</div>
				<div class="panel-body package-body">
					<table class="table" style="border: 0px;max-height: 365px;overflow: auto;">
					  <tbody class="package-body">
					  	<!-- <tr>
					  		<td class="bold">{{ __('users.description_label')}}</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr> -->

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
					  		@elseif($row->status=="active")
					  			<td><a href="javascript:void(0)" class="btn btn-success tj-btn-sucess"> {{ __('users.activated')}} </a></td>
					  		@endif
					    </tr>
					  	
					  </tbody>
				    </table>
				</div>

				<div class="panel-heading bold package_footer  	@if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=='CAPTURED' ){{$inactive}} 	@elseif($row->status=='active') {{ $active }}@endif"></div>
				</div>
			</div>
			@endforeach
		@endif
		@if(count($packageDetails) != 0 && !empty($packageDetails))
			  <div class="col-md-12">
				<h2>{{ __('users.subscribe_package_label')}} </h2>
				<hr class="heading_line"/>
					@foreach($packageDetails as $data)
					 <div class="col-md-4">
						<div class="panel panel-default subscribe-packages">
						<div class="panel-heading package_heading package-pro">{{$data['title']}}</div>
						<div class="panel-body pacakge_feature_list" >

							<p><span class="bold">{{ __('users.description_label')}} :</span> <?php echo $data->description; ?></p>
							<div class="package_bt">
							<p><span class="bold">{{ __('users.amount_label')}} : </span> {{$data['amount']}} kr</p>
							<p><span class="bold">{{ __('users.validity_label')}} : </span>{{$data['validity_days']}} Days</p>
							<form method="POST" action="{{route('frontklarnaPayment')}}" class="needs-validation" novalidate="">
								 {{ csrf_field() }}
								<input type="hidden" name="user_id" value="{{$user_id}}">
								<input type="hidden" name="p_id" value="{{$data['id']}}">
								<input type="hidden" name="p_name" value="{{$data['title']}}">
								<input type="hidden" name="validity_days" value="{{$data['validity_days']}}">
								<input type="hidden" name="amount" value="{{$data['amount']}}">					 	
								<button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn  tj-btn-sucess">{{ __('users.subscribe_btn')}}</button>
							 </form>
							 </div>
						</div>
						<div class="panel-heading bold package_footer package-pro"></div>
						</div>
					</div>
					<!--  <div class="col-md-4">
						<div class="panel panel-default subscribe-packages">
						<div class="panel-heading">{{$data['title']}}</div>
						<div class="panel-body" style="max-height: 215px;overflow: auto;">
		>>>>>>> 868a036965efb2dd19a88736f85859b8f9f95956
							<p>{{ __('users.description_label')}} : <?php echo $data->description; ?></p>
							<p>{{ __('users.amount_label')}} : {{$data['amount']}} kr</p>
							<p>{{ __('users.validity_label')}} : {{$data['validity_days']}} Days</p>
							<form method="POST" action="{{route('frontklarnaPayment')}}" class="needs-validation" novalidate="">
								 {{ csrf_field() }}
								<input type="hidden" name="user_id" value="{{$user_id}}">
								<input type="hidden" name="p_id" value="{{$data['id']}}">
								<input type="hidden" name="p_name" value="{{$data['title']}}">
								<input type="hidden" name="validity_days" value="{{$data['validity_days']}}">
								<input type="hidden" name="amount" value="{{$data['amount']}}">					 	
								<button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn">{{ __('users.subscribe_btn')}}</button>
							 </form>
						</div>
						</div>
					</div> -->
					@endforeach
			   </div>
			   @endif
			</div>
			</div>
</div>
</div> <!-- /container -->
</div>
</div>
@endsection
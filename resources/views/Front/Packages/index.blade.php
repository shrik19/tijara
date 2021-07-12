@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="">
        </br>
      @include ('Front.alert_messages')
      <!-- html for seller subscribe packages -->
       @if(count($subscribedPackage) != 0 && !empty($subscribedPackage))
      <div class="col-md-12">
      		@if(!empty($package_exp_msg))
      		<div class="alert alert-danger" role="alert">
			  {{$package_exp_msg}}
			</div>
			@endif
      	    <h2>{{ __('users.your_active_package')}}</h2>
        	<hr class="heading_line"/>
	      	@foreach($subscribedPackage as $row)
	      	
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading package-tbl">{{$row->title}}</div>
				<div class="panel-body"  style="">
					<table class="table" style="border: 0px;min-height: 315px;overflow: auto;">
					  <tbody>
					  	<tr>
					  		<td class="package-tbl">{{ __('users.description_label')}}</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.amount_label')}}</td>
					  		<td> {{$row->amount}} kr</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.validity_label')}}</td>
					  		<td>{{$row->validity_days}} Days.</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.purchased_date_label')}}</td>
					  		@if($row->start_date >= date('Y-m-d H:i:s'))
					  			<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					  			
					  		@else
					  			<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					  		@endif
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.expiry_date_label')}}</td>
					  		<td>{{date('l, d F Y',strtotime($row->end_date))}}</td>
					    </tr>
					    <tr>
					    	<td class="package-tbl">{{ __('lang.status_label')}}</td>
					    	@if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=="CAPTURED" )
					  			<td><a href="javascript:void(0)" class="btn btn-warning "> {{ __('users.not_activated_label')}}</a></td>
					  		@elseif(( $row->payment_status=="checkout_incomplete"))
					  		<td><a href="javascript:void(0)" class="btn btn-danger"> {{ __('lang.pending_label')}}</a>
					  			<p style="font-weight: bold;margin-top: 20px;margin-left:-108px;color: green"> {{ __('messages.payment_in_process')}}</p>
					  			<a href="" class="btn btn-info"> Reload</a>
					  		</td>
					  		@elseif($row->status=="active"))
					  			<td><a href="javascript:void(0)" class="btn btn-success "> {{ __('lang.active_label')}} </a></td>
					  		@endif
					    </tr>
					  	
					  </tbody>
				    </table>
				</div>
				</div>
			</div>
			@endforeach
		
	   </div>
	   @endif

	@if(count($packageDetails) != 0 && !empty($packageDetails))
      <div class="col-md-12">
        <h2>{{ __('users.subscribe_package_label')}} </h2>
        <hr class="heading_line"/>
	      	@foreach($packageDetails as $data)
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading">{{$data['title']}}</div>
				<div class="panel-body" style="max-height: 215px;overflow: auto;">
					<p>{{ __('users.description_label')}} : <?php echo $data->description; ?></p>
					<p>{{ __('users.amount_label')}} : {{$data['amount']}} kr</p>
					<p>{{ __('users.validity_label')}} : {{$data['validity_days']}} Days</p>

					<!-- <form method="POST" action="{{route('frontSubscribePackage')}}" class="needs-validation" novalidate=""> -->
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
			</div>
			@endforeach
	   </div>
	   @endif
    </div>
  </div>
</div> <!-- /container -->

@endsection
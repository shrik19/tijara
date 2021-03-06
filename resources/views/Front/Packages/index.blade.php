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
      	    <h2>{{ __('users.your_active_package')}}</h2>
        	<hr class="heading_line"/>
	      	@foreach($subscribedPackage as $row)
	      	
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading package-tbl">{{$row->title}}</div>
				<div class="panel-body">
					<table class="table" style="border: 0px;">
					  <tbody>
					  	<tr>
					  		<td class="package-tbl">{{ __('users.description_label')}}</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.amount_label')}}</td>
					  		<td> {{$row->amount}}</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.validity_label')}}</td>
					  		<td>{{$row->validity_days}} Days.</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.purchased_date_label')}}</td>
					  		<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">{{ __('users.expiry_date_label')}}</td>
					  		<td>{{date('l, d F Y',strtotime($expiryDate))}}</td>
					    </tr>
					    <tr>
					    	<td class="package-tbl">{{ __('lang.status_label')}}</td>
					    	@if($row->start_date >= date('Y-m-d H:i:s') )
					  			<td><a href="javascript:void(0)" class="btn btn-warning "> {{ __('users.not_activated_label')}}</a></td>
					  		@else
					  			
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
				<div class="panel-body">
					<p>{{ __('users.description_label')}} : <?php echo $row->description; ?></p>
					<p>{{ __('users.amount_label')}} : {{$data['amount']}}</p>
					<p>{{ __('users.validity_label')}} : {{$data['validity_days']}} .</p>

					<form method="POST" action="{{route('frontSubscribePackage')}}" class="needs-validation" novalidate="">
						 {{ csrf_field() }}
					 	<input type="hidden" name="user_id" value="{{$user_id}}">
					 	<input type="hidden" name="p_id" value="{{$data['id']}}">
					 	<input type="hidden" name="validity_days" value="{{$data['validity_days']}}">
					 	
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
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
      	    <h2>Your Active Package</h2>
        	<hr class="heading_line"/>
	      	@foreach($subscribedPackage as $row)
	      	
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading package-tbl">{{$row->title}}</div>
				<div class="panel-body">
					<table class="table" style="border: 0px;">
					  <tbody>
					  	<tr>
					  		<td class="package-tbl">Description</td>
					  		<td><?php echo $row->description; ?></td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">Amount</td>
					  		<td> {{$row->amount}}</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">Validity Days</td>
					  		<td>{{$row->validity_days}} Days.</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">Purchase Date</td>
					  		<td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl">Expiry Date</td>
					  		<td>{{date('l, d F Y',strtotime($expiryDate))}}</td>
					    </tr>
					    <tr>
					    	<td class="package-tbl">Status</td>
					    	@if($row->start_date >= date('Y-m-d H:i:s') )
					  			<td><a href="javascript:void(0)" class="btn btn-warning "> Not Yet Activated </a></td>
					  		@else
					  			
					  			<td><a href="javascript:void(0)" class="btn btn-success "> Currently Active </a></td>
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
        <h2>{{$title}}</h2>
        <hr class="heading_line"/>
	      	@foreach($packageDetails as $data)
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading">{{$data['title']}}</div>
				<div class="panel-body">
					<p>Description : <?php echo $row->description; ?></p>
					<p>Amount : {{$data['amount']}}</p>
					<p>Validity Days : {{$data['validity_days']}} Days.</p>

					<form method="POST" action="{{route('frontSubscribePackage')}}" class="needs-validation" novalidate="">
						 {{ csrf_field() }}
					 	<input type="hidden" name="user_id" value="{{$user_id}}">
					 	<input type="hidden" name="p_id" value="{{$data['id']}}">
					 	<input type="hidden" name="validity_days" value="{{$data['validity_days']}}">
					 	
					 	<button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn">Subscribe</button>
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
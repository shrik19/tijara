@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content ">
    <div class="seller_info">
    <div class="card">
		  <div class="card-header row seller_header">
        <h2>{{ __('lang.summary_menu')}}</h2>
        <!-- <hr class="heading_line"/> -->
      </div>
    </div>
  
      <div class="seller_mid_cont">
  <form method="POST" name="filterForm" id="filterForm" action="{{route('frontDashboard')}}">
    @csrf
    <div class="row">
      <div class="summary_page">
     <h3 class="pull-left">{{ __('lang.dashboard_statistics_period')}} : </h3>
     
     
      <select name="filter_date" id="filter_date" class="form-control" onchange="jQuery('#filterForm').submit();">
          @foreach($filterDate as $key => $data)
          <option value="{{$key}}" @if($currentDate == $key) selected="selected" @endif >{{$data}}</option>
          @endforeach
        </select>
     
      </div>
    </div>
    <div class="row"><div class="col-md-12">&nbsp;</div></div>
    <div class="row text-center">
      <div class="col-md-15" >
      
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_orders')}}</h2>
            <br />
            <h2>{{ $orderCount }}</h2>
          </div>
        </div>
      </div>  

      <div class="col-md-15">
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_service_requests')}}</h2>
            <br />
            <h2>{{ $serviceRequestCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-15">

        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_listed_products')}}</h2>
            <br />
            <h2>{{ $productCount }}</h2>
          </div>
        </div>

       
      </div>

      <div class="col-md-15">
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_listed_services')}}</h2>
            <br />
            <h2>{{ $servicesCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-15">
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_total_sales')}}</h2>
            <br />
            <h2>{{ $totalAmount }} Kr</h2>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
           <div class="buyer-prod-msg" style="height:150px;">
            <?php
              $title = (!empty($userpackage['title'])) ? $userpackage['title'] : '-';
              $amount = (!empty($userpackage['amount'])) ? $userpackage['amount']." kr" : '-';
              $validity_days = (!empty($userpackage['validity_days'])) ? " /".$userpackage['validity_days']." Days" : '-';
              $payment_date = (!empty($userpackage['end_date'])) ? date('Y-m-d',strtotime($userpackage['end_date'])) : '-';
             ?>
            <h2 class="buyer-prod-head">{{__('users.your_subscribed_label')}}</h2>
            <p class="buyer-prod-content col_black">{{__('users.Package_title')}} : {{$title}} {{$amount}} {{$validity_days}} </p>
            <p class="buyer-prod-content col_black">{{__('users.next_payment_label')}} : {{ $payment_date }} </p>
          </div>
        </div>
      </div> 
      
      
    </div>

    
    
  </form>
  </div>
  </div>
</div>
  </div>
</div>
</div> <!-- /container -->
</div>
@endsection('middlecontent')
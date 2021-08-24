@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>
</div>
<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
    <div class="card">
		  <div class="card-header row">
        <h2>{{ __('lang.summary_menu')}}</h2>
        <hr class="heading_line"/>
      </div>
    </div>
    
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
      <div class="col-md-3" >
      
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_orders')}}</h2>
            <br />
            <h2>{{ $orderCount }}</h2>
          </div>
        </div>
      </div>  

      <div class="col-md-3">
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_service_requests')}}</h2>
            <br />
            <h2>{{ $serviceRequestCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">

        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_listed_products')}}</h2>
            <br />
            <h2>{{ $productCount }}</h2>
          </div>
        </div>

       
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">{{ __('lang.dashboard_listed_services')}}</h2>
            <br />
            <h2>{{ $servicesCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
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
          <div class="buyer-prod-msg" style="height:250px;">
            <h2 class="buyer-prod-head">Din prenumeration</h2>
            <p class="buyer-prod-content">Paket : Tijara bas 69 kr/månad</p>
            <p class="buyer-prod-content">Din nästa betalning : 2021-10-01</p>
          </div>
        </div>
      </div> 
      
      
    </div>

    
    
  </form>
</div>
  </div>
</div>
</div> <!-- /container -->
</div>
@endsection('middlecontent')
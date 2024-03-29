@extends('Admin.layout.template')
@section('middlecontent')
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
  <form method="POST" name="filterForm" id="filterForm" action="{{route('adminDashboard')}}">
    @csrf
    <div class="row">
      <div class="col-md-3"><h3>{{ __('lang.dashboard_statistics_period')}} : </h3>
      </div>
      <div class="col-md-2">
      <select name="filter_date" id="filter_date" class="form-control" onchange="jQuery('#filterForm').submit();">
         <option value="all_month" >{{ __('users.all_months_option')}} </option>
          @foreach($filterDate as $key => $data)
          <option value="{{$key}}" @if($currentDate == $key) selected="selected" @endif >{{$data}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="row"><div class="col-md-12">&nbsp;</div></div>
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_orders')}}</h4>
            <br />
            @php
              $orderCount = swedishCurrencyFormat($orderCount);
            @endphp
            <h2>{{ $orderCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_service_requests')}}</h4>
            <br />
            @php
              $serviceRequestCount = swedishCurrencyFormat($serviceRequestCount);
            @endphp
            <h2>{{ $serviceRequestCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_listed_products')}}</h4>
            <br />
            @php
              $productCount = swedishCurrencyFormat($productCount);
            @endphp
            <h2>{{ $productCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_listed_services')}}</h4>
            <br />
            @php
              $servicesCount = swedishCurrencyFormat($servicesCount);
            @endphp
            <h2>{{ $servicesCount }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_total_sales')}}</h4>
            <br />
            @php
              $totalAmount = swedishCurrencyFormat($totalAmount);
            @endphp
            <h2>{{ $totalAmount }} Kr</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_total_users')}}</h4>
            <br />
            @php
              $totalUsers = swedishCurrencyFormat($totalUsers);
            @endphp
            <h2>{{ $totalUsers }}</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title">{{ __('lang.dashboard_total_ads')}}</h4>
            <br />
            <h2>
              @php
                $totalAds = swedishCurrencyFormat($totalAds);
              @endphp
            {{ $totalAds }}</h2>
          </div>
        </div>
      </div>

      
      
    </div>  
  </form>

@endsection('middlecontent')
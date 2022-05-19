<div class="pull-left sidebar_menu">

  @if(Auth::guard('user')->id())
 
  <ul class="seller_cat_list sel_cat_list" style="margin-top: 20px;" >
    <li><h3>{{ __('users.intrumentpanel_label')}}</h3></li>

    @if(Auth::guard('user')->getUser()->role_id==2)
       @php

        if((Request::segment(1)=='seller-dashboard')){
        $activeClass = 'leftsideactivemainmenu';
        }
        else{
        $activeClass = '';
        }
     
        $showProductMenu  = 1;
        $isPackagesubcribed = checkPackageSubscribe(Auth::guard('user')->id());
        if(Auth::guard('user')->getUser()->role_id==2 && $isPackagesubcribed ==0)
        $showProductMenu  = 0;
      @endphp

      <li class="{{$activeClass }} make_disabled check_seller_setting"><a href="{{route('frontDashboard')}}" style="margin-bottom: 15px;">{{ __('lang.summary_menu')}}</a></li>
    @endif

    <li><h2>{{ __('users.manage_label')}}</h2></li>

    @if(Auth::guard('user')->getUser()->role_id==2)
      <li class="{{ request()->is('seller-personal-page') ? 'leftsideactivemainmenu' : ''}} make_disabled check_seller_setting"><a href="{{route('frontSellerPersonalPage')}}">{{ __('users.seller_personal_page_menu')}}</a></li>
    @endif

    <?php 
      if((Request::segment(1)=='manage-products')){
        $activeClass = 'leftsideactivemainmenu';
      }
      else{
       $activeClass = '';
      }
    ?>
    @if(Auth::guard('user')->getUser()->role_id==2)
      @if(@$showProductMenu !=0)
        <li class="{{$activeClass}} make_disabled check_seller_setting"><a href="{{route('manageFrontProducts')}}">{{ __('lang.manage_products_menu')}}</a></li>
      @endif
    @endif
    <?php 
      if((Request::segment(1)=='manage-services')){
        $activeClass = 'leftsideactivemainmenu';
      }
      else{
        $activeClass = '';
      }
    ?>

    @if(Auth::guard('user')->getUser()->role_id==2)
    <li  class="{{$activeClass}} make_disabled check_seller_setting"><a href="{{route('manageFrontServices')}}">{{ __('lang.manage_services_menu')}}</a></li>                   

    @endif


    <?php 
    if((Request::segment(1)=='all-orders') || (Request::segment(1)=='order-details')){
    $activeClass = 'leftsideactivemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <li class="{{ $activeClass }} make_disabled check_seller_setting"><a href="{{route('frontAllOrders')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.manage_orders_menu')}} @else {{ __('users.all_orders_menu')}} @endif</a></li>

    <li  class="{{ request()->is('booking-request') ? 'leftsideactivemainmenu' : ''}} make_disabled check_seller_setting"><a href="{{route('frontAllbookingRequest')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.my_service_request')}} @else {{ __('users.booking_request_label')}} @endif</a></li>
 <?php /*  <li  class="{{ request()->is('all-service-request') ? 'leftsideactivemainmenu' : ''}}"><a href="{{route('frontAllServiceRequest')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.my_service_request')}} @else {{ __('lang.all_service_request')}} @endif</a></li> */?>

     <li  class="{{ request()->is('seller-payment-details') ? 'leftsideactivemainmenu' : ''}} make_disabled check_seller_setting"><a href="{{route('frontSellerPaymentDetails')}}">@if(Auth::guard('user')->getUser()->role_id==2) {{ __('users.payment_btn')}} @endif</a></li> 

    @if(Auth::guard('user')->getUser()->role_id==2)                   
    <li class="{{ request()->is('seller-packages') ? 'leftsideactivemainmenu' : ''}} make_disabled check_seller_setting"><a href="{{route('frontSellerPackages')}}">{{ __('lang.packages_menu')}}</a></li>

    @endif
    <?php 
    if((Request::segment(1)=='seller-profile') || (Request::segment(1)=='buyer-profile')){
    $activeClass = 'leftsideactivemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <li class="{{$activeClass }}"><a href="{{route('frontUserProfile')}}">{{ __('users.profile_label')}}</a></li>


    <!--   @php
    $showProductMenu  = 1;
    $isPackagesubcribed = checkPackageSubscribe(Auth::guard('user')->id());
    if(Auth::guard('user')->getUser()->role_id==2 && $isPackagesubcribed ==0)
    $showProductMenu  = 0;
    @endphp -->

    <?php 
    if((Request::segment(1)=='product-attributes')){
    $activeClass = 'leftsideactivemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    @if($showProductMenu !=0)
    <li  class="{{$activeClass}} make_disabled check_seller_setting"><a href="{{route('frontProductAttributes')}}">{{ __('lang.manage_attributes_menu')}}</a></li>
    @endif

    <!--     @if(Auth::guard('user')->getUser()->role_id==2)
    <li  class="{{$activeClass}}"><a href="{{route('manageFrontServices')}}">{{ __('lang.manage_services_menu')}}</a></li>

    <li class="{{ request()->is('seller-packages') ? 'leftsideactivemainmenu' : ''}}"><a href="{{route('frontSellerPackages')}}">{{ __('lang.packages_menu')}}</a></li>

    @endif -->
    <li class="{{ request()->is('change-password') ? 'leftsideactivemainmenu' : ''}} make_disabled check_seller_setting"><a href="{{route('frontChangePassword')}}">{{ __('lang.change_password_menu')}}</a></li>
    <li class="{{ request()->is('front-logout') ? 'leftsideactivemainmenu' : ''}}" ><a href="{{route('frontLogout')}}" style="padding-bottom: 60px;">{{ __('lang.logout_label')}}</a></li>
  </ul>

  @else
  <h3 class="de_col"><a  href="{{route('frontLogin')}}"  title="{{ __('users.login_label')}}"> {{ __('users.login_label')}} <i class="fas fa-user-check de_col"></i></a></h3>
  @endif


</div>
<script type="text/javascript">
$( document ).ready(function() {

     $.ajax({
    url: siteUrl+'/check-seller-setting',
    type: 'get',
    async: false,
    data: { },
    success: function(response){
      console.log(response.error)
      if(response.error!=''){
        $('.check_seller_setting').attr( "disabled", "disabled" );
        $('.check_seller_setting').css( "pointer-events", "none" );
        $('#header_user_menu').attr('href',siteUrl+"/seller-profile");
        showErrorMessage(response.error);
      }
    }
  });

});

</script>
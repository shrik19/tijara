<div class="pull-left sidebar_menu">
  @if(Auth::guard('user')->id())
  <ul class="category_list" style="margin-top: 50px;">
    <li><h3>Intrumentpanel</h3></li>

    @if(Auth::guard('user')->getUser()->role_id==2)
       @php
        if((Request::segment(1)=='seller-dashboard')){
        $activeClass = 'activemainmenu';
        }
        else{
        $activeClass = '';
        }
     
        $showProductMenu  = 1;
        $isPackagesubcribed = checkPackageSubscribe(Auth::guard('user')->id());
        if(Auth::guard('user')->getUser()->role_id==2 && $isPackagesubcribed ==0)
        $showProductMenu  = 0;
      @endphp

      <li class="{{$activeClass }}"><a href="{{route('frontDashboard')}}">{{ __('lang.summary_menu')}}</a></li>
    @endif

    <li><h2>Hantera</h2></li>

    @if(Auth::guard('user')->getUser()->role_id==2)
      <li class="{{ request()->is('seller-personal-page') ? 'activemainmenu' : ''}}"><a href="{{route('frontSellerPersonalPage')}}">{{ __('users.seller_personal_page_menu')}}</a></li>
    @endif

    <?php 
      if((Request::segment(1)=='manage-products')){
        $activeClass = 'activemainmenu';
      }
      else{
       $activeClass = '';
      }
    ?>

    @if($showProductMenu !=0)
      <li class="{{$activeClass}}"><a href="{{route('manageFrontProducts')}}">{{ __('lang.manage_products_menu')}}</a></li>
    @endif

    <?php 
      if((Request::segment(1)=='manage-services')){
        $activeClass = 'activemainmenu';
      }
      else{
        $activeClass = '';
      }
    ?>

    @if(Auth::guard('user')->getUser()->role_id==2)
    <li  class="{{$activeClass}}"><a href="{{route('manageFrontServices')}}">{{ __('lang.manage_services_menu')}}</a></li>                   

    @endif


    <?php 
    if((Request::segment(1)=='all-orders') || (Request::segment(1)=='order-details')){
    $activeClass = 'activemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <li class="{{ $activeClass }}"><a href="{{route('frontAllOrders')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.manage_orders_menu')}} @else {{ __('lang.txt_seller_order')}} @endif</a></li>


    <li  class="{{ request()->is('all-service-request') ? 'activemainmenu' : ''}}"><a href="{{route('frontAllServiceRequest')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.my_service_request')}} @else {{ __('lang.all_service_request')}} @endif</a></li>


    @if(Auth::guard('user')->getUser()->role_id==2)                   
    <li class="{{ request()->is('seller-packages') ? 'activemainmenu' : ''}}"><a href="{{route('frontSellerPackages')}}">{{ __('lang.packages_menu')}}</a></li>

    @endif
    <?php 
    if((Request::segment(1)=='seller-profile') || (Request::segment(1)=='buyer-profile')){
    $activeClass = 'activemainmenu';
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
    $activeClass = 'activemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    @if($showProductMenu !=0)
    <li  class="{{$activeClass}}"><a href="{{route('frontProductAttributes')}}">{{ __('lang.manage_attributes_menu')}}</a></li>
    @endif

    <!--     @if(Auth::guard('user')->getUser()->role_id==2)
    <li  class="{{$activeClass}}"><a href="{{route('manageFrontServices')}}">{{ __('lang.manage_services_menu')}}</a></li>

    <li class="{{ request()->is('seller-packages') ? 'activemainmenu' : ''}}"><a href="{{route('frontSellerPackages')}}">{{ __('lang.packages_menu')}}</a></li>

    @endif -->
    <li class="{{ request()->is('change-password') ? 'activemainmenu' : ''}}"><a href="{{route('frontChangePassword')}}">{{ __('lang.change_password_menu')}}</a></li>
    <li class="{{ request()->is('front-logout') ? 'activemainmenu' : ''}}"><a href="{{route('frontLogout')}}">{{ __('lang.logout_label')}}</a></li>
  </ul>

  @else
  <h3 class="de_col"><a  href="{{route('frontLogin')}}"  title="{{ __('users.login_label')}}"> {{ __('users.login_label')}} <i class="fas fa-user-check de_col"></i></a></h3>
  @endif

</div>
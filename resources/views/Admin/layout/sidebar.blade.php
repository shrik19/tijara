<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
    <!--   <a href="{{route('adminDashboard')}}">TIJARA</a> -->
      <a href="{{route('adminDashboard')}}"> <img src="{{url('/')}}/assets/img/logo.png" alt="{{config('constants.PROJECT_NAME')}}" width="100" class="shadow-light"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{route('adminDashboard')}}">TIJARA</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">{{ __('lang.dashboard_menu')}}</li>
        <li class="nav-item dropdown ">
          <a href="{{route('adminDashboard')}}" class="nav-link"><i class="fas fa-fire"></i><span>{{ __('lang.summary_menu')}}</span></a>
        </li>
      <li class="menu-header">{{ __('lang.managements_menu')}}</li>
        <li class="nav-item dropdown">
          <a href="{{route('adminBuyers')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.buyer_menu')}}</span></a>
        </li>
        <li class="nav-item dropdown">
          <a href="{{route('adminCategory')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.product_category_menu')}}</span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminServiceCat')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.service_category_menu')}}</span></a>
        </li>

         <li class="nav-item dropdown">
          <a href="{{route('adminAnnonserCat')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.buyerAd_category_menu')}}</span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminProduct')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.products_title')}}</span></a></li>  
          
          <li class="nav-item dropdown">
          <a href="{{route('adminService')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.manage_services_menu')}}</span></a></li> 

          <li class="nav-item dropdown">
          <a href="{{route('adminProductAttributes')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.manage_attributes_menu')}}</span></a></li> 

        <li class="nav-item dropdown">
          <a href="{{route('adminBuyersAd')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.buyer_ad_title')}} </span></a>
        </li> 

        <li class="nav-item dropdown">         
          <a href="{{route('adminSlider')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.sliders_menu')}}</span></a>        
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminBanner')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.banners_menu')}}</span></a>
        </li> 
<!--
        <li class="nav-item dropdown">
          <a href="{{route('adminCity')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('users.city_label')}}</span></a>
        </li> -->

        <li class="nav-item dropdown">
          <a href="{{route('adminSeller')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.sellers_menu')}}</span></a>
        </li>  

        <li class="nav-item dropdown">
          <a href="{{route('adminPackage')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.packages_menu')}} </span></a>
        </li>  
        
        <li class="nav-item dropdown">
          <a href="{{route('adminPage')}}" class="nav-link"><i class="fas fa-copy"></i> <span>{{ __('lang.pages_menu')}} </span></a>
        </li> 

        <li class="nav-item dropdown">
          <a href="{{route('adminOrder')}}" class="nav-link"><i class="fas fa-file-invoice"></i> <span>{{ __('users.order_title')}} </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminEmail')}}" class="nav-link"><i class="fas fa-envelope-open-text"></i> <span>{{ __('lang.email_menu')}} </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminNewsletterUser')}}" class="nav-link"><i class="fas fa-envelope-open-text"></i> <span>{{ __('users.newsletter_subscriber')}} </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminSettingCreate')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>{{ __('lang.setting_menu')}} </span></a>
        </li>  

        <!-- <li class="nav-item dropdown">
          <a href="{{route('adminProduct')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Products </span></a>
        </li>  

        <li class="nav-item dropdown">
         <a href="{{route('adminProductAttributes')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Products Attributes</span></a>
        </li>     --> 

      </ul>
  </aside>
</div>
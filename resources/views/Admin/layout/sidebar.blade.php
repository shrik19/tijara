<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{route('adminDashboard')}}">TIJARA</a>
      <!-- <a href="{{route('adminDashboard')}}"> <img src="{{url('/')}}/assets/img/logo.jpeg" alt="{{config('constants.PROJECT_NAME')}}" width="100" class="shadow-light"></a> -->
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{route('adminDashboard')}}">TIJARA</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
        <li class="nav-item dropdown ">
          <a href="{{route('adminDashboard')}}" class="nav-link"><i class="fas fa-fire"></i><span>Summary</span></a>
        </li>
      <li class="menu-header">Managements</li>
        <li class="nav-item dropdown">
          <a href="{{route('adminBuyers')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Buyer</span></a>
        </li>
        <li class="nav-item dropdown">
          <a href="{{route('adminCategory')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Product Category</span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminServiceCat')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Service Category</span></a>
        </li>
<!--
        <li class="nav-item dropdown">
          <a href="{{route('adminProduct')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Products</span></a></li>  -->
        <li class="nav-item dropdown">         
          <a href="{{route('adminSlider')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Sliders</span></a>        
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminBanner')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Banners</span></a>
        </li> 

        <li class="nav-item dropdown">
          <a href="{{route('adminCity')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>City</span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="{{route('adminSeller')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Sellers</span></a>
        </li>  

        <li class="nav-item dropdown">
          <a href="{{route('adminPackage')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Packages </span></a>
        </li>  

        <li class="nav-item dropdown">
          <a href="{{route('adminSettingCreate')}}" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Setting </span></a>
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
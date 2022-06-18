<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
    <!--   <a href="<?php echo e(route('adminDashboard')); ?>">TIJARA</a> -->
      <a href="<?php echo e(route('adminDashboard')); ?>"> <img src="<?php echo e(url('/')); ?>/assets/img/logo.png" alt="<?php echo e(config('constants.PROJECT_NAME')); ?>" width="100" class="shadow-light"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?php echo e(route('adminDashboard')); ?>">TIJARA</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header"><?php echo e(__('lang.dashboard_menu')); ?></li>
        <li class="nav-item dropdown ">
          <a href="<?php echo e(route('adminDashboard')); ?>" class="nav-link"><i class="fas fa-fire"></i><span><?php echo e(__('lang.summary_menu')); ?></span></a>
        </li>
      <li class="menu-header"><?php echo e(__('lang.managements_menu')); ?></li>
        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminBuyers')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.buyer_menu')); ?></span></a>
        </li>
        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminCategory')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.product_category_menu')); ?></span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminServiceCat')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.service_category_menu')); ?></span></a>
        </li>

         <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminAnnonserCat')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.buyerAd_category_menu')); ?></span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminProduct')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.products_title')); ?></span></a></li>  
          
          <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminService')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.manage_services_menu')); ?></span></a></li> 

          <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminProductAttributes')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.manage_attributes_menu')); ?></span></a></li> 

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminBuyersAd')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.buyer_ad_title')); ?> </span></a>
        </li> 

        <li class="nav-item dropdown">         
          <a href="<?php echo e(route('adminSlider')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.sliders_menu')); ?></span></a>        
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminBanner')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.banners_menu')); ?></span></a>
        </li> 
<!--
        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminCity')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('users.city_label')); ?></span></a>
        </li> -->

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminSeller')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.sellers_menu')); ?></span></a>
        </li>  

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminPackage')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.packages_menu')); ?> </span></a>
        </li>  
        
        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminPage')); ?>" class="nav-link"><i class="fas fa-copy"></i> <span><?php echo e(__('lang.pages_menu')); ?> </span></a>
        </li> 

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminOrder')); ?>" class="nav-link"><i class="fas fa-file-invoice"></i> <span><?php echo e(__('users.order_title')); ?> </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminEmail')); ?>" class="nav-link"><i class="fas fa-envelope-open-text"></i> <span><?php echo e(__('lang.email_menu')); ?> </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminNewsletterUser')); ?>" class="nav-link"><i class="fas fa-envelope-open-text"></i> <span><?php echo e(__('users.newsletter_subscriber')); ?> </span></a>
        </li>

        <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminSettingCreate')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span><?php echo e(__('lang.setting_menu')); ?> </span></a>
        </li>  

        <!-- <li class="nav-item dropdown">
          <a href="<?php echo e(route('adminProduct')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Products </span></a>
        </li>  

        <li class="nav-item dropdown">
         <a href="<?php echo e(route('adminProductAttributes')); ?>" class="nav-link"><i class="fas fa-id-card-alt"></i> <span>Products Attributes</span></a>
        </li>     --> 

      </ul>
  </aside>
</div><?php /**PATH C:\wamp64\www\tijara\resources\views/Admin/layout/sidebar.blade.php ENDPATH**/ ?>
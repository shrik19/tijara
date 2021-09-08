<div class="pull-left sidebar_menu">

  <?php if(Auth::guard('user')->id()): ?>
  <ul class="seller_cat_list sel_cat_list" style="margin-top: 50px;">
    <li><h3><?php echo e(__('users.intrumentpanel_label')); ?></h3></li>

    <?php if(Auth::guard('user')->getUser()->role_id==2): ?>
       <?php
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
      ?>

      <li class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('frontDashboard')); ?>"><?php echo e(__('lang.summary_menu')); ?></a></li>
    <?php endif; ?>

    <li><h2><?php echo e(__('users.manage_label')); ?></h2></li>

    <?php if(Auth::guard('user')->getUser()->role_id==2): ?>
      <li class="<?php echo e(request()->is('seller-personal-page') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontSellerPersonalPage')); ?>"><?php echo e(__('users.seller_personal_page_menu')); ?></a></li>
    <?php endif; ?>

    <?php 
      if((Request::segment(1)=='manage-products')){
        $activeClass = 'activemainmenu';
      }
      else{
       $activeClass = '';
      }
    ?>
    <?php if(Auth::guard('user')->getUser()->role_id==2): ?>
      <?php if(@$showProductMenu !=0): ?>
        <li class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('manageFrontProducts')); ?>"><?php echo e(__('lang.manage_products_menu')); ?></a></li>
      <?php endif; ?>
    <?php endif; ?>
    <?php 
      if((Request::segment(1)=='manage-services')){
        $activeClass = 'activemainmenu';
      }
      else{
        $activeClass = '';
      }
    ?>

    <?php if(Auth::guard('user')->getUser()->role_id==2): ?>
    <li  class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('manageFrontServices')); ?>"><?php echo e(__('lang.manage_services_menu')); ?></a></li>                   

    <?php endif; ?>


    <?php 
    if((Request::segment(1)=='all-orders') || (Request::segment(1)=='order-details')){
    $activeClass = 'activemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <li class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('frontAllOrders')); ?>"><?php if(Auth::guard('user')->getUser()->role_id==1): ?> <?php echo e(__('lang.manage_orders_menu')); ?> <?php else: ?> <?php echo e(__('users.all_orders_menu')); ?> <?php endif; ?></a></li>

    <li  class="<?php echo e(request()->is('booking-request') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontAllbookingRequest')); ?>"><?php if(Auth::guard('user')->getUser()->role_id==1): ?> <?php echo e(__('lang.my_service_request')); ?> <?php else: ?> <?php echo e(__('users.booking_request_label')); ?> <?php endif; ?></a></li>
 <!--    <li  class="<?php echo e(request()->is('all-service-request') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontAllServiceRequest')); ?>"><?php if(Auth::guard('user')->getUser()->role_id==1): ?> <?php echo e(__('lang.my_service_request')); ?> <?php else: ?> <?php echo e(__('lang.all_service_request')); ?> <?php endif; ?></a></li> -->


    <?php if(Auth::guard('user')->getUser()->role_id==2): ?>                   
    <li class="<?php echo e(request()->is('seller-packages') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontSellerPackages')); ?>"><?php echo e(__('lang.packages_menu')); ?></a></li>

    <?php endif; ?>
    <?php 
    if((Request::segment(1)=='seller-profile') || (Request::segment(1)=='buyer-profile')){
    $activeClass = 'activemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <li class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('frontUserProfile')); ?>"><?php echo e(__('users.profile_label')); ?></a></li>


    <!--   <?php
    $showProductMenu  = 1;
    $isPackagesubcribed = checkPackageSubscribe(Auth::guard('user')->id());
    if(Auth::guard('user')->getUser()->role_id==2 && $isPackagesubcribed ==0)
    $showProductMenu  = 0;
    ?> -->

    <?php 
    if((Request::segment(1)=='product-attributes')){
    $activeClass = 'activemainmenu';
    }
    else{
    $activeClass = '';
    }
    ?>
    <?php if($showProductMenu !=0): ?>
    <li  class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('frontProductAttributes')); ?>"><?php echo e(__('lang.manage_attributes_menu')); ?></a></li>
    <?php endif; ?>

    <!--     <?php if(Auth::guard('user')->getUser()->role_id==2): ?>
    <li  class="<?php echo e($activeClass); ?>"><a href="<?php echo e(route('manageFrontServices')); ?>"><?php echo e(__('lang.manage_services_menu')); ?></a></li>

    <li class="<?php echo e(request()->is('seller-packages') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontSellerPackages')); ?>"><?php echo e(__('lang.packages_menu')); ?></a></li>

    <?php endif; ?> -->
    <li class="<?php echo e(request()->is('change-password') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontChangePassword')); ?>"><?php echo e(__('lang.change_password_menu')); ?></a></li>
    <li class="<?php echo e(request()->is('front-logout') ? 'activemainmenu' : ''); ?>"><a href="<?php echo e(route('frontLogout')); ?>"><?php echo e(__('lang.logout_label')); ?></a></li>
  </ul>

  <?php else: ?>
  <h3 class="de_col"><a  href="<?php echo e(route('frontLogin')); ?>"  title="<?php echo e(__('users.login_label')); ?>"> <?php echo e(__('users.login_label')); ?> <i class="fas fa-user-check de_col"></i></a></h3>
  <?php endif; ?>


</div><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/layout/sidebar_menu.blade.php ENDPATH**/ ?>
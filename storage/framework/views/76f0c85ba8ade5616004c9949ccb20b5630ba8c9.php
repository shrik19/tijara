
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-2 tijara-sidebar">
      <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="col-md-10 tijara-content ">
    <div class="seller_info">
    <div class="card">
		  <div class="card-header row seller_header">
        <h2 class="page_heading seller_page_heading"><?php echo e(__('lang.summary_menu')); ?></h2>
        <!-- <hr class="heading_line"/> -->
      </div>
    </div>
  
      <div class="seller_mid_cont">
  <form method="POST" name="filterForm" id="filterForm" class="seller-dashboard-form" action="<?php echo e(route('frontDashboard')); ?>">
    <?php echo csrf_field(); ?>
    <div class="row">
      <div class="summary_page sel_cat_list">
     <h3 class="pull-left" style="margin-left: 10px;"><?php echo e(__('lang.dashboard_statistics_period')); ?> : </h3>
     
     
      <select name="filter_date" id="filter_date" class="form-control" onchange="jQuery('#filterForm').submit();">
        <option value="all_month" ><?php echo e(__('users.all_months_option')); ?> </option>
          <?php $__currentLoopData = $filterDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if($currentDate == $key): ?> selected="selected" <?php endif; ?> ><?php echo e($data); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
     
      </div>
    </div>
    <div class="row"><div class="col-md-12">&nbsp;</div></div>
    <div class="text-center sel_cat_list">
      <div class="col-md-15" >
      
        <div class="card buyer-prod-card">
          <div class="buyer-prod-msg">
            <h2 class="buyer-prod-head"><?php echo e(__('lang.dashboard_orders')); ?></h2>
            <br />
            <h2><?php echo e($orderCount); ?></h2>
          </div>
        </div>
      </div>  

      <div class="col-md-15">
        <div class="card buyer-prod-card">
          <div class="buyer-prod-msg">
            <h2 class="buyer-prod-head"><?php echo e(__('lang.dashboard_service_requests')); ?></h2>
            <br />
            <h2><?php echo e($serviceRequestCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-15">

        <div class="card buyer-prod-card">
          <div class="buyer-prod-msg">
            <h2 class="buyer-prod-head"><?php echo e(__('lang.dashboard_listed_products')); ?></h2>
            <br />
            <h2><?php echo e($productCount); ?></h2>
          </div>
        </div>

       
      </div>

      <div class="col-md-15">
        <div class="card buyer-prod-card">
          <div class="buyer-prod-msg">
            <h2 class="buyer-prod-head"><?php echo e(__('lang.dashboard_listed_services')); ?></h2>
            <br />
            <h2><?php echo e($servicesCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-15">
        <div class="card buyer-prod-card">
          <div class="buyer-prod-msg">
            <h2 class="buyer-prod-head"><?php echo e(__('lang.dashboard_total_sales')); ?></h2>
            <br />
            <h2>
              <?php
              if(!empty($totalAmount)){
                $order_total_tbl = str_split(strrev($totalAmount), 3);
                $order_total_price_tbl = strrev(implode(" ", $order_total_tbl));
                if (strpos($order_total_price_tbl, ".") !== false) {
                  $order_total_price_tbl=str_replace('.',',',$order_total_price_tbl);
                }else{
                    $order_total_price_tbl = $order_total_price_tbl.",00";
                 }
              }else{
                  $order_total_price_tbl = '0';
              }
              ?>
              <?php echo e($order_total_price_tbl); ?> Kr
            </h2>
          </div>
        </div>
      </div>

      <div class="col-md-6" style="margin: 10px;">
        <div class="card">
           <div class="buyer-prod-msg-bottom" style="height:150px;">
            <?php
             
              if($userpackage['is_trial']==1){
                 $title =  'Tijara Trial';
                $amount = 'Free';
                $validity_days = "/ 30 Days";
                $payment_date = (!empty($userpackage['trial_end_date'])) ? date('Y-m-d',strtotime($userpackage['trial_end_date'])) : '-';
              }else{
                $title = (!empty($userpackage['title'])) ? $userpackage['title'] : '-';
                $amount = (!empty($userpackage['amount'])) ? $userpackage['amount']." kr" : '-';
                $validity_days = (!empty($userpackage['validity_days'])) ? " /".$userpackage['validity_days']." Days" : '-';
                $payment_date = (!empty($userpackage['end_date'])) ? date('Y-m-d',strtotime($userpackage['end_date'])) : '-';
              }
             ?>
            <h2 class="buyer-prod-head"><?php echo e(__('users.your_subscribed_label')); ?></h2>
            <p class="buyer-prod-content col_black"><?php echo e(__('users.Package_title')); ?> : <?php echo e($title); ?> <?php echo e($amount); ?> <?php echo e($validity_days); ?> </p>
            <p class="buyer-prod-content col_black"><?php echo e(__('users.next_payment_label')); ?> : <?php echo e($payment_date); ?> </p>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/dashboard.blade.php ENDPATH**/ ?>
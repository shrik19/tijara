
<?php $__env->startSection('middlecontent'); ?>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/main.css">
  <form method="POST" name="filterForm" id="filterForm" action="<?php echo e(route('adminDashboard')); ?>">
    <?php echo csrf_field(); ?>
    <div class="row">
      <div class="col-md-3"><h3><?php echo e(__('lang.dashboard_statistics_period')); ?> : </h3>
      </div>
      <div class="col-md-2">
      <select name="filter_date" id="filter_date" class="form-control" onchange="jQuery('#filterForm').submit();">
         <option value="all_month" ><?php echo e(__('users.all_months_option')); ?> </option>
          <?php $__currentLoopData = $filterDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if($currentDate == $key): ?> selected="selected" <?php endif; ?> ><?php echo e($data); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>
    <div class="row"><div class="col-md-12">&nbsp;</div></div>
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_orders')); ?></h4>
            <br />
            <?php
              $orderCount = swedishCurrencyFormat($orderCount);
            ?>
            <h2><?php echo e($orderCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_service_requests')); ?></h4>
            <br />
            <?php
              $serviceRequestCount = swedishCurrencyFormat($serviceRequestCount);
            ?>
            <h2><?php echo e($serviceRequestCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_listed_products')); ?></h4>
            <br />
            <?php
              $productCount = swedishCurrencyFormat($productCount);
            ?>
            <h2><?php echo e($productCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_listed_services')); ?></h4>
            <br />
            <?php
              $servicesCount = swedishCurrencyFormat($servicesCount);
            ?>
            <h2><?php echo e($servicesCount); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_total_sales')); ?></h4>
            <br />
            <?php
              $totalAmount = swedishCurrencyFormat($totalAmount);
            ?>
            <h2><?php echo e($totalAmount); ?> Kr</h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_total_users')); ?></h4>
            <br />
            <?php
              $totalUsers = swedishCurrencyFormat($totalUsers);
            ?>
            <h2><?php echo e($totalUsers); ?></h2>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <div class="card-body text-center">
            <h4 class="card-title"><?php echo e(__('lang.dashboard_total_ads')); ?></h4>
            <br />
            <h2>
              <?php
                $totalAds = swedishCurrencyFormat($totalAds);
              ?>
            <?php echo e($totalAds); ?></h2>
          </div>
        </div>
      </div>

      
      
    </div>  
  </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Admin.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Admin/dashboard.blade.php ENDPATH**/ ?>
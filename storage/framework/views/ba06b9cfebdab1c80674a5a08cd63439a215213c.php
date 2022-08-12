
<?php $__env->startSection('middlecontent'); ?>

<div class="container containerfluid p_155" style="min-height: 650px;">
  <!-- Example row of columns -->
  <div class="row register-success-page">
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2><?php echo e(__('lang.register_successful_title')); ?></h2>
        <!-- <hr class="heading_line"/> -->
        <div class="login_box">
		
          <div class="alert alert-success"><?php echo e(__('lang.account_register_success')); ?> </div>
     
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/register_success.blade.php ENDPATH**/ ?>
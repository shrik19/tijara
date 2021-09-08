
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-12">
      <?php if($is_seller==1): ?>
        <div class="col-md-2 tijara-sidebar">
          <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-10 tijara-content">
        <div class="seller_info">
        <div class="card-header row seller_header">
			  <h2><?php echo e(__('users.change_password_title')); ?> </h2>
			  <hr class="heading_line">
		  </div>
           <div class="col-md-6">
      <?php else: ?>
        <div class="col-md-12 tijara-content">
          <div class="col-md-3"></div>
          <div class="col-md-5">
          <h2><?php echo e(__('users.change_password_title')); ?> </h2>
      <?php endif; ?>
      
          <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		
          <div class="login_box seller_mid_cont">
            <form method="POST" action="<?php echo e(route('frontChangePasswordStore')); ?>" class="needs-validation" novalidate="">
              <?php echo csrf_field(); ?>
                <div class="form-group">
                  <label><?php echo e(__('users.password_label')); ?></label>
                  <input type="password" class="form-control login_input" name="password" required tabindex="1" placeholder="<?php echo e(__('users.password_label')); ?>">
                 <span class="invalid-feedback" id="err_fname"><?php if($errors->has('password')): ?> <?php echo e($errors->first('password')); ?><?php endif; ?> </span>
                </div>

                <div class="form-group">
                  <label><?php echo e(__('users.password_confirmation_label')); ?></label>
                  <input type="password" class="form-control login_input" name="password_confirmation" required tabindex="2" placeholder="<?php echo e(__('users.password_confirmation_label')); ?>">
                  <span class="invalid-feedback" id="err_fname"><?php if($errors->has('password_confirmation')): ?> <?php echo e($errors->first('password_confirmation')); ?><?php endif; ?> </span>
                </div>
               
                <?php if($is_seller !=1): ?> <div style="margin-top: 30px;margin-bottom: 30px;"><?php endif; ?>
                <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn"><?php echo e(__('lang.save_btn')); ?></button>
                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-black gray_color login_btn"> <?php echo e(__('lang.cancel_btn')); ?></a>

               <?php if($is_seller !=1): ?></div> <?php endif; ?>
                
            </form>
          </div>
        </div>
         <?php if($is_seller !=1): ?>
          <div class="col-md-3"></div>
         <?php endif; ?>
     </div>
</div>
</div>
   </div>

    </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/change_password.blade.php ENDPATH**/ ?>
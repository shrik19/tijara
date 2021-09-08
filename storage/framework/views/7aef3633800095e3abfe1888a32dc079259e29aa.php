
<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  <?php if(!empty($banner->image)): ?>
  <img class="login_banner" src="<?php echo e(url('/')); ?>/uploads/Banner/<?php echo e($banner->image); ?>" />
<?php endif; ?>
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2><?php echo e(__('users.create_account_btn')); ?></h2>

        <hr class="heading_line"/>
        <p><?php echo e(__('users.already_have_account')); ?>

          <a href="<?php echo e(url('/')); ?>/front-login/buyer" class="de_col"><?php echo e(__('users.login_label')); ?></a>
        </p> 
        <div class="login_box">
          <form id="sign-up-form" action="<?php echo e(url('/')); ?>/do-register" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="role_id" value="<?php echo e($role_id); ?>">

            <div class="form-group">
              <label><?php echo e(__('users.email_label')); ?> <span class="de_col">*</span></label>
              <input type="email" class="form-control login_input" name="email" id="email" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__('users.email_label')); ?>">
              <span class="invalid-feedback" id="err_email" style=""><?php if($errors->has('email')): ?> <?php echo e($errors->first('email')); ?><?php endif; ?></span>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.password_label')); ?><span class="de_col">*</span></label>
              <input type="password" class="form-control login_input" name="password" id="password" value="<?php echo e(old('password')); ?>" placeholder="<?php echo e(__('users.password_label')); ?>">
              <span class="invalid-feedback" id="err_password" style=""><?php if($errors->has('password')): ?> <?php echo e($errors->first('password')); ?><?php endif; ?></span>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.password_confirmation_label')); ?><span class="de_col">*</span></label>
              <input type="password" class="form-control login_input" name="password_confirmation" value="<?php echo e(old('password_confirmation')); ?>" id="cpassword" placeholder="<?php echo e(__('users.password_confirmation_label')); ?>">
              <span class="invalid-feedback" id="err_cpassword" style=""><?php if($errors->has('password_confirmation')): ?> <?php echo e($errors->first('password_confirmation')); ?><?php endif; ?></span>
            </div>

            <div  style="display: flex;">
                <input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value=""><?php echo e(__('users.read_and_approve_chk')); ?><a href="javascript:void(0)">&nbsp;<?php echo e(__('users.terms_of_use')); ?> &nbsp;</a> <?php echo e(__('users.and_chk')); ?> <a href="javascript:void(0)"><?php echo e(__('users.privacy_policy')); ?></a> 
            </div>

            <button class="btn btn-black debg_color login_btn frontregisterbtn"><?php echo e(__('users.create_account_btn')); ?></button>
          </form>

          <div class="seller-link-section">
              <a href="<?php echo e(route('seller_register')); ?>" title="<?php echo e(__('users.register_as_seller')); ?>" class="" ><span><?php echo e(__('users.register_as_seller')); ?></span> </a><br>
              <a href="<?php echo e(route('frontLoginSeller')); ?>" title="<?php echo e(__('users.login_as_seller')); ?>" class="" ><span><?php echo e(__('users.login_as_seller')); ?></span> </a>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/register.blade.php ENDPATH**/ ?>
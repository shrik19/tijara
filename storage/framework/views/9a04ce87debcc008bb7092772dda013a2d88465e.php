
<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2">
      <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="col-md-10">
    <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     
        <h2> <?php echo e(__('users.seller_personal_form_label')); ?></h2>
        <hr class="heading_line"/>
        <form id="seller-personal-form" action="<?php echo e(route('frontSellerPersonalPage')); ?>" method="post"  enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
           
        <div class="col-md-6">
        
          <div class="login_box">
          

            <div class="form-group">
              <label><?php echo e(__('lang.store_name')); ?> <span class="de_col"></span></label>
              <input type="text" class="form-control store_name" id="store_name" name="store_name" 
              placeholder="<?php echo e(__('lang.store_name')); ?> " value="<?php if(!empty($details->store_name)): ?> <?php echo e($details->store_name); ?> <?php endif; ?>" />
              <span class="invalid-feedback" id="err_fname"><?php if($errors->has('store_name')): ?> <?php echo e($errors->first('store_name')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group">
              <label><?php echo e(__('lang.store_information')); ?>  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_information" id="" 
              placeholder="<?php echo e(__('lang.store_information')); ?>" 
              value="" tabindex="2"><?php if(!empty($details->store_information)): ?> <?php echo e($details->store_information); ?> <?php endif; ?></textarea>
              <span class="invalid-feedback" id="err_description" ><?php if($errors->has('store_information')): ?> <?php echo e($errors->first('store_information')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group">
              <label><?php echo e(__('lang.store_policy')); ?>  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="store_policy" id="" 
              placeholder="<?php echo e(__('lang.store_policy')); ?>" 
              value="" tabindex="2"><?php if(!empty($details->store_policy)): ?> <?php echo e($details->store_policy); ?> <?php endif; ?></textarea>
              <span class="invalid-feedback" id="err_description" ><?php if($errors->has('store_policy')): ?> <?php echo e($errors->first('store_policy')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group">
              <label><?php echo e(__('lang.return_policy')); ?>  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="return_policy" id="" 
              placeholder="<?php echo e(__('lang.return_policy')); ?>" 
              value="" tabindex="2"><?php if(!empty($details->return_policy)): ?> <?php echo e($details->return_policy); ?> <?php endif; ?></textarea>
              <span class="invalid-feedback" id="err_description" ><?php if($errors->has('return_policy')): ?> <?php echo e($errors->first('return_policy')); ?><?php endif; ?> </span>
            </div>
           
          </div>
        </div>
        <div class="col-md-6">
        
          <div class="login_box">
          

            <div class="form-group">
              <label><?php echo e(__('lang.shipping_policy')); ?>  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="shipping_policy" id="" 
              placeholder="<?php echo e(__('lang.shipping_policy')); ?>" 
              value="" tabindex="2"><?php if(!empty($details->shipping_policy)): ?> <?php echo e($details->shipping_policy); ?> <?php endif; ?></textarea>
              <span class="invalid-feedback" id="err_description" ><?php if($errors->has('shipping_policy')): ?> <?php echo e($errors->first('shipping_policy')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group">
              <label><?php echo e(__('lang.other_information')); ?>  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="other_information" id="" 
              placeholder="<?php echo e(__('lang.other_information')); ?>" 
              value="" tabindex="2"><?php if(!empty($details->other_information)): ?> <?php echo e($details->other_information); ?> <?php endif; ?></textarea>
              <span class="invalid-feedback" id="err_description" ><?php if($errors->has('other_information')): ?> <?php echo e($errors->first('other_information')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group increment cloned">
              <label><?php echo e(__('users.seller_header_img_label')); ?></label>
              <?php
              if(!empty($details->header_img))
              {
                echo '<div class="row">';
                echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->header_img.'" style="width:200px;height:200px;"></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              ?>

              <input type="file" name="header_img" class="form-control" value="">
              
              <div class="text-danger"><?php echo e($errors->first('filename')); ?></div>
              <div class="input-group-btn text-right"> 
              </div>
            </div>

            <div class="form-group increment cloned">
              <label><?php echo e(__('users.seller_logo_label')); ?></label>
              <?php
              if(!empty($details->logo))
              {
                echo '<div class="row">';
                echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->logo.'" style="width:200px;height:200px;"></div>';
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              ?>

              <input type="file" name="logo" class="form-control" value="">
              
              <div class="text-danger"><?php echo e($errors->first('filename')); ?></div>
              <div class="input-group-btn text-right"> 
              </div>
            </div>

          
              
          </div>
        </div>
        
        <div class="col-md-9 pull-right">

          <button class="btn btn-black debg_color login_btn"><?php echo e(__('lang.update_btn')); ?></button>
          <a href="<?php echo e(route('frontHome')); ?>" class="btn btn-black gray_color login_btn" tabindex="16"> <?php echo e(__('lang.cancel_btn')); ?></a>
                
        </div>
      </form>    
    </div>
  </div>
</div> <!-- /container -->


<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/seller_personal_page.blade.php ENDPATH**/ ?>
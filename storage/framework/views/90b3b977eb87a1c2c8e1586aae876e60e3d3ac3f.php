
<?php $__env->startSection('middlecontent'); ?>
<div class="section-body">
   <h2 class="section-title"><?php echo e($pageTitle); ?></h2>
   <p class="section-lead"><?php echo e(__('users.add_banner_details_title')); ?></p>
   <form method="POST" action="<?php echo e(route('adminBannerAddnew')); ?>" class="needs-validation"    enctype="multipart/form-data" novalidate="">
   <?php echo csrf_field(); ?>
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group"> 
                  <label><?php echo e(__('users.select_page_ddl')); ?>  <span class="text-danger">*</span></label>
                  <select class="form-control" id="display_on_page" name="display_on_page" tabindex="1" >
                     <option value=""><?php echo e(__('users.select_page_ddl')); ?></option>
                     <!-- <option value="Home"><?php echo e(__('users.home_page')); ?></option> -->
                     <option value="Login"><?php echo e(__('lang.login_btn')); ?></option>
                     <option value="Register"><?php echo e(__('lang.register_btn')); ?></option>
                     <!-- <option value="My Account"><?php echo e(__('lang.my_account_title')); ?></option>
                     <option value="Product List"><?php echo e(__('users.product_list_page')); ?></option>
                     <option value="Shopping Cart"><?php echo e(__('users.shopping_cart_page')); ?></option>
                     <option value="About Us"> <?php echo e(__('users.about_us_page')); ?></option>
                     <option value="Contact Us"><?php echo e(__('users.contact_us_page')); ?></option> -->
                  </select>
               <div class="invalid-feedback">
                  <?php echo e(__('errors.select_page_err')); ?>

               </div>
               <div class="text-danger"><?php echo e($errors->first('display_on_page')); ?></div>
             </div>
               <div class="form-group">
                  <label><?php echo e(__('users.title_thead')); ?>  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title"  tabindex="2" value="<?php echo e(old('title')); ?>">
                  <div class="invalid-feedback">
                     <?php echo e(__('errors.fill_in_banner_title_err')); ?>

                  </div>
                  <div class="text-danger"><?php echo e($errors->first('title')); ?></div>
               </div> 
               
               <div class="form-group">
                  <label><?php echo e(__('users.link_thead')); ?>  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="redirect_link" id="redirect_link"  tabindex="3" value="<?php echo e(old('redirect_link')); ?>">
                  <div class="invalid-feedback">
                    <?php echo e(__('errors.fill_in_banner_link_err')); ?>

                  </div>
                  <div class="text-danger"><?php echo e($errors->first('redirect_link')); ?></div>
               </div>
              
               <div class="form-group">
                  <label><?php echo e(__('users.image_thead')); ?>  <span class="text-danger">*</span></label>
                   <input type="file" name="image" class="form-control" tabindex="4" value="<?php echo e(old('image')); ?>" >
                  <p><?php echo e(__('users.image_upload_info')); ?>  <br> <?php echo e(__('users.image_upload_info_banner')); ?></p>
                  <div class="invalid-feedback">
                    <?php echo e(__('errors.upload_banner_image_err')); ?>

                  </div>
                  <div class="text-danger"><?php echo e($errors->first('image')); ?></div>
               </div> </div>


			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> <?php echo e(__('lang.save_btn')); ?></button>&nbsp;&nbsp;
            <a href="<?php echo e(route('adminBanner')); ?>" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> <?php echo e(__('lang.cancel_btn')); ?></a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Admin.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Admin/Banner/addnew.blade.php ENDPATH**/ ?>
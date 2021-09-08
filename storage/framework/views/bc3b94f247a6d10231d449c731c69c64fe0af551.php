
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
        <?php if($subscribedError): ?>
	    <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
	    <?php endif; ?>
      <div class="col-md-1"></div> 
      <div class="col-md-6">
        <h2><?php echo e(__('lang.attribute_form_label')); ?></h2>
        <hr class="heading_line"/>
        <div class="login_box">
           <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="<?php echo e(route('frontAttributeStore')); ?>" >
             <?php echo e(csrf_field()); ?>


            <div class="form-group">
              <label><?php echo e(__('lang.attribute_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" id="name" name="name" placeholder="<?php echo e(__('lang.attribute_label')); ?> " value="<?php echo e(old('name')); ?>" />
              <span class="invalid-feedback" id="err_fname"><?php if($errors->has('name')): ?> <?php echo e($errors->first('name')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group">
              <label><?php echo e(__('lang.attribute_value_label')); ?> <span class="de_col">*</span></label>
              <select class="form-control login_input" id="type" name="type">
                <option value=""><?php echo e(__('lang.select_label')); ?> </option>
                <option value="radio"><?php echo e(__('lang.radio_label')); ?></option>  
                <option value="dropdown"><?php echo e(__('lang.dropdown_label')); ?></option>
                <option value="textbox"><?php echo e(__('lang.textbox_label')); ?></option>
              </select>
              <span class="invalid-feedback" id="err_fname"><?php if($errors->has('type')): ?> <?php echo e($errors->first('type')); ?><?php endif; ?> </span>
            </div>

            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn"><?php echo e(__('lang.save_btn')); ?></button>
           
            <a href="<?php echo e($module_url); ?>" class="btn btn-black gray_color login_btn"> <?php echo e(__('lang.cancel_btn')); ?></a>
          </form>
    
        </div>
      
    </div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/ProductAttributes/create.blade.php ENDPATH**/ ?>
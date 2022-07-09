
<?php $__env->startSection('middlecontent'); ?>
<div class="section-body">
  <h2 class="section-title"><?php echo e($current_module_name); ?></h2>
  <p class="section-lead"><?php echo e(__('users.add_slider_details_title')); ?></p>

  <div class="row">
    <div class="col-12 col-md-8 col-lg-8">
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="<?php echo e(route('adminSliderStore')); ?>" enctype="multipart/form-data" >
            <?php echo e(csrf_field()); ?>


            <div class="form-group">
              <label><?php echo e(__('users.title_thead')); ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" placeholder="Slider Title" value="<?php echo e(old('title')); ?>" tabindex="1"/>
              <div class="text-danger"><?php echo e(($errors->has('title')) ? $errors->first('title') : ''); ?></div>
            </div>
            
            <div class="form-group">
              <label><?php echo e(__('users.image_thead')); ?> <span class="text-danger">*</span></label>
              <input type="file" id="slider_image" name="slider_image" value="<?php echo e(old('slider_image')); ?>" tabindex="2"/><!-- style="color: #424a50;" -->
              <p class="slider-note" style="color:#000;font-size: 12px;"><?php echo e(__('users.image_upload_info')); ?> <br> <?php echo e(__('users.image_upload_info2')); ?></p>
              <div class="text-danger"><?php echo e(($errors->has('slider_image')) ? $errors->first('slider_image') : ''); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.link_thead')); ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="link" name="link" placeholder="<?php echo e(__('users.link_thead')); ?>" value="<?php echo e(old('link')); ?>" tabindex="3"/>
              <div class="text-danger"><?php echo e(($errors->has('link')) ? $errors->first('link') : ''); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.sequence_number_label')); ?> <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder="<?php echo e(__('users.sequence_number_label')); ?>" value="<?php echo e(old('sequence_no')); ?>" tabindex="3"/>
              <div class="text-danger"><?php echo e(($errors->has('sequence_no')) ? $errors->first('sequence_no') : ''); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.description_label')); ?></label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"></textarea>
              <div class="text-danger"><?php echo e(($errors->has('description')) ? $errors->first('description') : ''); ?></div>
            </div>

            <div class="box-footer">
               <span class="pull-right">
                  <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> <?php echo e(__('lang.save_btn')); ?></button>&nbsp;&nbsp;
                  <a href="<?php echo e($module_url); ?>" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> <?php echo e(__('lang.cancel_btn')); ?></a>
               </span>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/richtext.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Admin.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\tijara\resources\views/Admin/Slider/create.blade.php ENDPATH**/ ?>
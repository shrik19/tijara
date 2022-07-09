
<?php $__env->startSection('middlecontent'); ?>
<div class="section-body">
  <h2 class="section-title"><?php echo e($pageTitle); ?></h2>
  <p class="section-lead"><?php echo e(__('users.edit_email_details_title')); ?></p>
  <form method="POST" action="<?php echo e(route('adminEmailUpdate', $id)); ?>" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    <?php echo csrf_field(); ?>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label><?php echo e(__('users.title_thead')); ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="<?php echo e((old('title')) ?  old('title') : $PageDetails['title']); ?>" readonly="readonly">
              <div class="invalid-feedback">
               <?php echo e(__('errors.fill_in_title')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('title')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.who_received_email')); ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="to_email" id="to_email" required tabindex="1" value="<?php echo e((old('to_email')) ?  old('to_email') : $PageDetails['to_email']); ?>" >
              <div class="text-danger"><?php echo e($errors->first('to_email')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.email_subject')); ?><span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="subject" id="subject" required tabindex="1" value="<?php echo e((old('subject')) ?  old('subject') : $PageDetails['subject']); ?>">
              <div class="invalid-feedback">
              <?php echo e(__('errors.fill_in_subject')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('subject')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.content_label')); ?></label>
           <!--    <textarea class="form-control" id="description" name="description" rows="2" cols="30" style="height:auto" tabindex="2" required><?php if(!empty($PageDetails['description'])){ echo $PageDetails['description']; }?></textarea> -->
               <textarea class="form-control description" name="contents" id="contents" spellcheck="true" required><?php if(!empty($PageDetails['contents'])){ echo $PageDetails['contents']; }?></textarea>
               <div class="invalid-feedback">
               <?php echo e(__('errors.fill_in_email_content')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('contents')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.email_subject_en')); ?><span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="subject_en" id="subject_en" required tabindex="1" value="<?php echo e((old('subject_en')) ?  old('subject_en') : $PageDetails['subject_en']); ?>">
              <div class="invalid-feedback">
               <?php echo e(__('errors.fill_in_subject_en')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('subject_en')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.content_label_en')); ?><span class="text-danger">*</span></label>
               <textarea class="form-control description_en" name="contents_en" id="contents_en" spellcheck="true" required > <?php if(!empty($PageDetails['contents_en'])){ echo $PageDetails['contents_en']; }?></textarea>
               <div class="invalid-feedback">
               <?php echo e(__('errors.fill_in_email_content_en')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('contents_en')); ?></div>
            </div>

            <div class="form-group">
              <label><?php echo e(__('lang.status_thead')); ?></label>
              <select class="form-control" name="status">
                <option value="active"  <?php if($PageDetails['status'] == 'active'){ echo 'selected'; } ?>><?php echo e(__('lang.active_label')); ?></option>
                <option value="block" <?php if($PageDetails['status'] == 'block'){ echo 'selected'; } ?>><?php echo e(__('lang.inactive_label')); ?></option>
              </select>
              <div class="invalid-feedback">
                <?php echo e(__('errors.select_status_err')); ?>

              </div>
              <div class="text-danger"><?php echo e($errors->first('status')); ?></div>
            </div>

            <div class="col-12 text-right">
              <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> <?php echo e(__('lang.update_btn')); ?></button>&nbsp;&nbsp;
              <a href="<?php echo e(route('adminEmail')); ?>" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> <?php echo e(__('lang.cancel_btn')); ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/richtext.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.description').richText();
    $('.description_en').richText();
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\tijara\resources\views/Admin/Email/edit.blade.php ENDPATH**/ ?>
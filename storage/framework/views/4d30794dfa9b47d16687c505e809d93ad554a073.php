<!-- Common Alert Messages  -->
<?php if(Session::has('success')): ?>
<div class="col-md-12 update-alert-css">
<div class="alert alert-success alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <!-- <strong><?php echo e(__('messages.success')); ?></strong>  --><?php echo e(Session::get('success')); ?>

   </div>
</div>
</div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
<div class="col-md-12 update-alert-css">
<div class="alert alert-danger alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong><?php echo e(__('users.oops_heading')); ?></strong> <?php echo e(Session::get('error')); ?>

   </div>
</div>
</div>
<?php endif; ?>
<?php if(Session::has('warning')): ?>
<div class="col-md-12 update-alert-css">
<div class="alert alert-warning alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong><?php echo e(__('errors.warning')); ?></strong> <?php echo e(Session::get('warning')); ?>

   </div>
</div>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/alert_messages.blade.php ENDPATH**/ ?>
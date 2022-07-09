<!-- Common Alert Messages  -->
<?php if(Session::has('success')): ?>
<div class="alert alert-success alert-dismissible show fade">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong><?php echo e(__('messages.success')); ?></strong> <?php echo e(Session::get('success')); ?>

   </div>
</div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
<div class="alert alert-danger alert-dismissible show fade">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong><?php echo e(__('errors.error')); ?></strong> <?php echo e(Session::get('error')); ?>

   </div>
</div>
<?php endif; ?>
<?php if(Session::has('warning')): ?>
<div class="alert alert-warning alert-dismissible show fade">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong><?php echo e(__('errors.warning')); ?></strong> <?php echo e(Session::get('warning')); ?>

   </div>
</div>
<?php endif; ?><?php /**PATH D:\wamp64\www\tijara\resources\views/Admin/alert_messages.blade.php ENDPATH**/ ?>

<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
  <!--<div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>-->
  <?php if(!empty($banner->image)): ?>
  <img class="login_banner" src="<?php echo e(url('/')); ?>/uploads/Banner/<?php echo e($banner->image); ?>" />
<?php endif; ?>
</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="">
      <div class="col-md-8 col-md-offset-2">
        <h2><?php echo e($details['title']); ?></h2>
        <hr class="heading_line"/>
        <div>
		      <?php echo $details['contents']; ?>

         
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/pages.blade.php ENDPATH**/ ?>
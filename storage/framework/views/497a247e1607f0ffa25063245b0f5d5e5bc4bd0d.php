
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
    <div class="">
        </br>     
      
      <div class="col-md-12">
      	<div class="card">
			<div class="card-header row">
				
			</div>
			<div class="card-body">
				<h1 style="text-align: center;color: red"><?php echo e($error_messages); ?></h1>
				<a href="<?php echo e(url('/')); ?>/show-cart" class="btn btn-black debg_color login_btn" style="margin-left: 450px;"><?php echo e(__('lang.try_again_btn')); ?></a>
			</div>
		</div>
      </div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/payment_error.blade.php ENDPATH**/ ?>
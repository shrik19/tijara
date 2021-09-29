
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
    <div class="">
        
      <div class="col-md-12">
      	<div class="card">
			<div class="card-header row">
				
			</div>
			<div class="card-body">
		
        <?php $__currentLoopData = $payment_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;"
         onclick="location.href='<?php echo e(route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>$p])); ?>'" >
                        <?php echo e($p); ?> <span class="glyphicon glyphicon-play"></span>
                        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
		</div>
      </div>
      </div>
    </div>
</div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/shopping_payment_options.blade.php ENDPATH**/ ?>

<?php $__env->startSection('middlecontent'); ?>

<script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/4.1.0/adyen.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/4.1.0/adyen.css" crossorigin="anonymous">

<div id="clientKey" class="hidden"><?php echo e($clientKey); ?></div>
<div id="seller_id" class="hidden"><?php echo e($seller_id); ?></div>
<div id="type" class="hidden"><?php echo e($type); ?></div>
<div id="orderId" class="hidden"><?php echo e($orderId); ?></div>
<div id="paymentAmount" class="hidden"><?php echo e($paymentAmount); ?></div>

<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container">
            <div class="payment-container">
                <div id=<?php echo e($type); ?> class="payment"></div>
                <div id=<?php echo e($orderId); ?> class="orderId"></div>
                
            </div>
          </div>
        </div>
      </div>
    </div>
</section>        

<script type="text/javascript" src="<?php echo e(url('/')); ?>/assets/front/js/checkout-adyenImplementation.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/checkout_swish.blade.php ENDPATH**/ ?>
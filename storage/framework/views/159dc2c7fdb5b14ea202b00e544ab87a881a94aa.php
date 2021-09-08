
<?php $__env->startSection('middlecontent'); ?>

<style>
  .btn span.glyphicon {
    opacity: 1;
}
.error{
    color: red;
  }
</style>

<section class="product_details_section">
<div class="loader"></div>
<div class="container">
      <div class="row" style="margin-bottom:60px;">
        <div class="col-md-10 col-md-offset-1">
        <?php echo $html_snippet;?>
        </div>
      </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/checkout.blade.php ENDPATH**/ ?>
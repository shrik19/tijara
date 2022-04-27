
<?php if(is_object($Products)): ?>
<?php 
$cssVariable = '';
if(strpos(@$path, 'annonser') !== false)
{
 	$cssVariable ="padding-left:53px";
}

?>
<ul class="product_details product_service_list" style="<?php echo e($cssVariable); ?>">
    <?php $__currentLoopData = $Products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php echo $__env->make('Front.products_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<div class="pagination_div">
<?php echo $Products->links(); ?>

</div>
<?php else: ?>
<div class="col-md-12" style="text-align:center;color: red;font-size: 20px;margin-top: 20px;"><?php echo e($Products); ?></div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\tijara\resources\views/Front/products_list.blade.php ENDPATH**/ ?>
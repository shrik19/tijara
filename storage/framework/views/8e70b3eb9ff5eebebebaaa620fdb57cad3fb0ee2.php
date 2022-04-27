
<?php if(is_object($Services)): ?>
<?php 
$cssVariable = '';
if(strpos(@$path, 'services') !== false)
{
 	$cssVariable ="padding-left:38px";
}

?>
<ul class="product_details service_list" style="<?php echo e($cssVariable); ?>">
    <?php $__currentLoopData = $Services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php echo $__env->make('Front.services_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<div class="pagination_div">
<?php echo $Services->links(); ?>

</div>
<?php else: ?>
<div class="col-md-12" style="text-align:center;color: red;font-size: 20px;margin-top: 20px;"><?php echo e($Services); ?></div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\tijara\resources\views/Front/services_list.blade.php ENDPATH**/ ?>
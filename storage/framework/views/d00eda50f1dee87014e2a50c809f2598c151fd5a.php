
<?php if(is_object($Services)): ?>
<ul class="product_details">
    <?php $__currentLoopData = $Services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php echo $__env->make('Front.services_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php echo $Services->links(); ?>

<?php else: ?>
<div style="text-align:center;color: red;font-size: 20px;margin-top: 20px;"><?php echo e($Services); ?></div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/services_list.blade.php ENDPATH**/ ?>
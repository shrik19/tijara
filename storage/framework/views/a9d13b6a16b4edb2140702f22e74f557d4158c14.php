<ul class="breadcrumb-category">
	  <li><a href="<?php if(!empty($all_cat_link)): ?><?php echo e($all_cat_link); ?> <?php endif; ?>"><?php echo e(__('lang.all_category')); ?></a></li>
	  <li><a href="<?php if(!empty($category_link)): ?><?php echo e($category_link); ?> <?php endif; ?>" id="breadcrumb_category"> <?php if(!empty($category_name)): ?> > <?php echo e($category_name); ?><?php endif; ?></a></li>
	  <li><a href="<?php if(!empty($subcategory_link)): ?><?php echo e($subcategory_link); ?> <?php endif; ?>" id="breadcrumb_subcategory"> <?php if(!empty($subcategory_name)): ?> ><?php echo e($subcategory_name); ?> <?php endif; ?></a></li>
</ul>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/category_breadcrumb.blade.php ENDPATH**/ ?>
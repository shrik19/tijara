<ul class="breadcrumb-category">
	<?php if(Request::segment(1) =='annonser'): ?>
	  <li><a href="<?php echo e(route('AllbuyerProductListing')); ?>"><?php echo e(__('lang.all_category')); ?></a></li>
	<?php else: ?>
	  <li><a href="<?php if(!empty($all_cat_link)): ?><?php echo e($all_cat_link); ?> <?php endif; ?>"> <?php echo e(__('lang.all_category')); ?></a></li>
	<?php endif; ?>
	  <li><a href="<?php if(!empty($category_link)): ?><?php echo e($category_link); ?> <?php endif; ?>" id="breadcrumb_category"> <?php if(!empty($category_name)): ?> 
	  </a> <span>></span> <a href="<?php if(!empty($category_link)): ?><?php echo e($category_link); ?> <?php endif; ?>" id="breadcrumb_category"><?php echo e($category_name); ?><?php endif; ?>
		</a>
	</li>
	  <li><a href="<?php if(!empty($subcategory_link)): ?><?php echo e($subcategory_link); ?> <?php endif; ?>" id="breadcrumb_subcategory"> <?php if(!empty($subcategory_name)): ?> 
	  </a>  <span> > </span><a href="<?php if(!empty($subcategory_link)): ?><?php echo e($subcategory_link); ?> <?php endif; ?>" id="breadcrumb_subcategory"> <?php echo e($subcategory_name); ?> <?php endif; ?>
		</a></li>
</ul>
<?php /**PATH C:\wamp64\www\tijara\resources\views/Front/category_breadcrumb.blade.php ENDPATH**/ ?>
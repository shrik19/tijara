<?php
  //dd(Request::path());
?>
<div class="category_list_box"  id="accordion">
        <h2 class="de_col"><?php echo e(__('lang.categories_head')); ?></h2>
        <ul class="category_list">
        <?php $i=0; ?>
        <?php $__currentLoopData = $Categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $i++;
        $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activemaincategory';
        else if($category_slug=='' && $i==1) $cls  =       'activemaincategory';
         ?>
                <?php if(!empty($Categories[$CategoryId]['subcategory'])): ?>
                <li class="expandCollapseSubcategory <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#"><?php echo e($Category['category_name']); ?></a></li>

                <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                <?php $__currentLoopData = $Categories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?> class="activesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div>&nbsp;</div>
        <?php if(Request::path() != "/" ): ?>
          <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/js/css/bootstrap-slider.css" />
          <script src="<?php echo e(url('/')); ?>/assets/front/js/bootstrap-slider.js"></script>
          <h2 class="de_col"><?php echo e(__('lang.price_filter_head')); ?></h2>
          <!-- <br /><b>€ 10</b> -->
          <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="150000" data-slider-step="500" data-slider-value="[0,150000]"/>
          <!-- <b>€ 1000</b> -->
          <div>&nbsp;</div>
          <?php if(empty($is_seller)): ?>
          <!-- Seller Listing -->
          <h2 class="de_col"><?php echo e(__('lang.sellers_head')); ?></h2>
          <span class="seller_list_content"></span>
          <?php endif; ?>
        <?php endif; ?>
</div>

<div class="category_list_box"  id="accordion">
        <h2 class="de_col"><?php echo e(__('lang.service_categories_head')); ?></h2>
        <ul class="category_list">
        <?php $j=0;
        
        ?>
        <?php $__currentLoopData = $ServiceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $j++;
                  $cls='';
                  if($category_slug==$Category['category_slug'])
                                  $cls  =       'activeservicemaincategory';
                  else if( $j==1) $cls  =       'activeservicemaincategory';
                ?>
                <?php if(!empty($ServiceCategories[$CategoryId]['subcategory'])): ?>
                  <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#servicesubcategories<?php echo $j; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#"><?php echo e($Category['category_name']); ?></a></li>

                  <ul id="servicesubcategories<?php echo $j; ?>" class="service_subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                  <?php $__currentLoopData = $ServiceCategories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?> class="activeservicesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/services/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/services/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div>&nbsp;</div>
        
</div>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/categories_sidebar.blade.php ENDPATH**/ ?>
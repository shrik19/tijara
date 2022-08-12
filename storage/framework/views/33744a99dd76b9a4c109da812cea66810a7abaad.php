
  <h2 class="all_cat_sidebar_label" id="all_cat_label"><?php echo e(__('lang.category_title')); ?></h2>
  <ul class="seller_cat_list">
    <li>
      <a href="<?php echo e(route('AllbuyerProductListing')); ?>" title="<?php echo e(__('lang.all_category')); ?>"  class="all_category_bold"><?php echo e(__('lang.all_category')); ?></a>
    </li>
  </ul>



<div class="category_list_box show_product_cat_sidebar"  id="accordion">
  
  <ul class="seller_cat_list">
 
    <?php 
      $i=0; $j=0;
      $productsads =  'annonser';
    ?>


    <?php $__currentLoopData = $Categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php $i++; 
        $cls='';
        if(!empty($Category['product_count'][$j])){
        $product_count = $Category['product_count'][$j]; 
        }else{
        $product_count = '';
        }

        if($category_slug==$Category['category_slug'])
        $cls  ='activemaincategory';
              

      ?>
   
      <?php if(!empty($Categories[$CategoryId]['subcategory'])): ?>

        <li class="expandCollapseSubcategory  <?php echo $cls; ?>" <?php if(empty($is_seller)): ?> 
           href="<?php echo e(url('/')); ?>/<?php echo e($productsads); ?>/<?php echo e($Category['category_slug']); ?>" <?php else: ?> 
           href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/annonser/<?php echo e($Category['category_slug']); ?>" <?php endif; ?> aria-expanded="true" aria-controls="collapseOne"><a <?php if(empty($is_seller)): ?> 
           href="<?php echo e(url('/')); ?>/<?php echo e($productsads); ?>/<?php echo e($Category['category_slug']); ?>" <?php else: ?> 
           href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/annonser/<?php echo e($Category['category_slug']); ?>" <?php endif; ?>  id="main_cat_name<?php echo $i; ?>" <?php if(Request::segment(1) =='seller'): ?> class = 'seller_page_botton_border' <?php endif; ?>><?php echo e($Category['category_name']); ?> <span style="float: right;" id="productCount_<?php echo e($CategoryId); ?>"></span></a></li>

        <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse  <?php if($cls!='') echo 'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">

        <?php $__currentLoopData = $Categories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?>
           class="activesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> 
           href="<?php echo e(url('/')); ?>/<?php echo e($productsads); ?>/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> 
           href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/annonser/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      <?php endif; ?>

    <?php $j++; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>

 

<div>
  <div>&nbsp;</div>
  <div style="margin-left: 4px;margin-bottom: 60px;"> 
    <label class="price_label"><?php echo e(__('users.place_label')); ?></label>
    <select class="form-control" name="city_name" id="city_name">
      <option value=""  class="product_sorting_filter_option"> <?php echo e(__('lang.whole_sweden_option')); ?> </option>
      <?php if(!empty($allCities)): ?>
      <?php $__currentLoopData = $allCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e(@$city->city); ?>" class="product_sorting_filter_option"><?php echo e(@$city->city); ?></option>       
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </select>
  </div>
  <div>&nbsp;</div>
</div>

<script type="text/javascript">
 
$(document).ready(function(){ 
  var search_string = $(".current_search_string").text();
   if(search_string !=''){
    $(".all_category_bold").addClass("activeAllcategory");
   }
});
</script><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/annonser_sidebar.blade.php ENDPATH**/ ?>
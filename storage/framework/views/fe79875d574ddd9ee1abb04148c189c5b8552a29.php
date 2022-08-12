 <?php if(Request::segment(1) =='services' || Request::segment(1) =='products' || Request::segment(1) =='annonser'): ?>
  <h2 class="all_cat_sidebar_label" id="all_cat_label"><?php echo e(__('lang.category_title')); ?></h2>
  <ul class="seller_cat_list">
    <li>
      <a href="<?php echo e(route('AllserviceListing')); ?>" title="<?php echo e(__('lang.all_category')); ?>"  class="all_category_bold"><?php echo e(__('lang.all_category')); ?></a>
    </li>
  </ul>
<?php endif; ?>

 <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/js/css/bootstrap-slider.css" />
<script src="<?php echo e(url('/')); ?>/assets/front/js/bootstrap-slider.js"></script>

<?php if(Request::segment(4) !='products'): ?>
<div class="category_list_box show_service_cat_sidebar" >
  
 <!--  <h2><?php echo e(__('lang.service_categories_head')); ?></h2> -->
  <ul class="seller_cat_list">
    <?php $j=0; ?>

    <?php $__currentLoopData = $ServiceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $j++;
        $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activeservicemaincategory';
        
      ?>

      <?php if(!empty($ServiceCategories[$CategoryId]['subcategory'])): ?>
        <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/services/<?php echo e($Category['category_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/services/<?php echo e($Category['category_slug']); ?>" <?php endif; ?> aria-expanded="true" aria-controls="collapseOne"><a <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/services/<?php echo e($Category['category_slug']); ?> " <?php else: ?> href="javascript:void(0)" sub="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e($Category['category_slug']); ?>" <?php endif; ?> <?php if(Request::segment(1) =='seller'): ?> class = 'seller_page_botton_border cat_subcat_redirect' <?php endif; ?> ><?php echo e($Category['category_name']); ?><span style="float: right;" id="serviceCount_<?php echo e($CategoryId); ?>"></span></a></li>

        <ul id="servicesubcategories<?php echo $j; ?>" class="service_subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
        <?php $__currentLoopData = $ServiceCategories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?> class="activeservicesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/services/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> href="javascript:void(0)" sub="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" class="cat_subcat_redirect" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php endif; ?>

<div class="category_list_box show_product_cat_sidebar" style="display: none">
  <h2 style="display:none;"><?php echo e(__('lang.categories_head')); ?></h2>
  <ul class="seller_cat_list">
    <?php $i=0; ?>

    <?php $__currentLoopData = $Categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php 
        $i++;
        $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activemaincategory';
        
      ?>
<!--else if($category_slug=='' && $i==1) $cls  =       'activemaincategory';-->
      <?php if(!empty($Categories[$CategoryId]['subcategory'])): ?>
        <li class="expandCollapseSubcategory <?php echo $cls; ?>"  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/products/<?php echo e($Category['category_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/products/<?php echo e($Category['category_slug']); ?>" <?php endif; ?> aria-expanded="true" aria-controls="collapseOne"><a <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/products/<?php echo e($Category['category_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/products/<?php echo e($Category['category_slug']); ?>" <?php endif; ?>><?php echo e($Category['category_name']); ?><span style="float: right;" id="productCount_<?php echo e($i); ?>"><?php if(!empty(Request::segment(4))): ?><?php echo e(count($Categories[$CategoryId]['subcategory'])); ?><?php endif; ?></span></a></li>

        <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
        <?php $__currentLoopData = $Categories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?> class="activesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
  <div>&nbsp;</div>
</div>

<div>

<div>&nbsp;</div>
 <?php if(Request::path() != "/" && Request::segment(3) !='products' && Request::segment(3) !='services' &&  Request::segment(1) != 'seller'): ?>
 
    <div style="margin-left: 4px;"> 
      <label class="price_label"><?php echo e(__('lang.sort_by_price')); ?></label>

      <div>&nbsp;</div>
      <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="10000" data-slider-step="500" data-slider-value="[0,10000]"/>
    </div>
      <!-- <b>€ 1000</b> -->
      <div>&nbsp;</div>
      <div>&nbsp;</div>
   <?php if(Request::segment(1) != 'seller'): ?>
   <div style="margin-left: 4px;"> 
  <label class="filter_lable"><?php echo e(__('users.place_label')); ?></label>
  <select class="form-control" name="city_name" id="service_city">
      <option value=""  class="product_sorting_filter_option"> <?php echo e(__('lang.whole_sweden_option')); ?> </option>
      <?php if(!empty($allCities)): ?>
        <?php $__currentLoopData = $allCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e(@$city->city); ?>" class="product_sorting_filter_option"><?php echo e(@$city->city); ?></option>       
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </select>
  </div>
  <?php endif; ?>


<!--   <div>&nbsp;</div>
 <div style="margin-left: 4px;"> 
  <div class="category_button">
  <button class="show_all_cat"  product_link="<?php echo e(url('/')); ?>/products?all=1"><?php echo e(__('users.all_btn')); ?></button>
  <button class="show_product_cat"  product_link="<?php echo e(url('/')); ?>/products"><?php echo e(__('lang.product_label')); ?></button>
  <button class="show_service_cat" service_link="<?php echo e(url('/')); ?>/services"><?php echo e(__('lang.service_label')); ?></button>
</div>
</div> -->
<?php endif; ?>



<?php if(Request::path() != "/" && Request::segment(1) !='products' && Request::segment(1) !='services'): ?>
  <?php if(empty($is_seller)): ?>
  <!-- Seller Listing -->
    <h2 class="de_col"><?php echo e(__('lang.sellers_head')); ?></h2>
    <span class="seller_list_content"></span>
  <?php endif; ?>
<?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var pageURL = $(location).attr("href");
    var array = pageURL.split('/');
    var lastsegment = array[array.length-1];
    
    if(lastsegment == 'products'){
       $(".show_product_cat").addClass('active');
     }else if(lastsegment == 'services'){
        $(".show_service_cat").addClass('active');
     }else{
        $(".show_all_cat").addClass('active');
     }

   //$(".show_all_cat").addClass('active');
  $(document).on('click', '.show_all_cat', function(){  
      var attr_val = $(this).attr('product_link');
      window.location.href = attr_val;
      $(this).addClass('active');
      $('.show_product_cat').removeClass('active');
      $('.show_service_cat').removeClass('active');
      $('.show_product_cat_sidebar').show();
      $('.show_service_cat_sidebar').show();
      $('.all_cat_label').show();
  });

  $(document).on('click', '.show_product_cat', function(){  
    var attr_val = $(this).attr('product_link');
    window.location.href = attr_val;
    $(this).addClass('active');
    $('.show_all_cat').removeClass('active');
    $('.show_service_cat').removeClass('active');
    $('.show_product_cat_sidebar').show();
    $('.show_service_cat_sidebar').hide();
    $('.all_cat_label').hide();
  });

  $(document).on('click', '.show_service_cat', function(){  
    var attr_val = $(this).attr('service_link');
    window.location.href = attr_val;
    $(this).addClass('active');
    $('.show_all_cat').removeClass('active');
    $('.show_product_cat').removeClass('active');
    $('.show_service_cat_sidebar').show();
    $('.show_product_cat_sidebar').hide();
    $('.all_cat_label').hide();
  });


  var search_string = $(".current_search_string").text();
   if(search_string !=''){
    $(".all_category_bold").addClass("activeAllcategory");
  }

});
</script><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/services_sidebar.blade.php ENDPATH**/ ?>
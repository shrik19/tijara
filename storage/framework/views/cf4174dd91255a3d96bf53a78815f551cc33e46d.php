 <?php if(Request::segment(1) =='services' || Request::segment(1) =='products'): ?>
  <label class="all_cat_label"><?php echo e(__('lang.all_category')); ?></label>
<?php endif; ?>

<?php if(Request::segment(4) !='services'): ?>
<div class="category_list_box show_product_cat_sidebar"  id="accordion">

  <h2><?php echo e(__('lang.categories_head')); ?></h2>
  <ul class="seller_cat_list">
    <?php $i=0; $j=0; ?>

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
        else if($category_slug=='' && $i==1) 
        $cls = 'activemaincategory';
        
      ?>

      <?php if(!empty($Categories[$CategoryId]['subcategory'])): ?>

        <li class="expandCollapseSubcategory  <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#" id="main_cat_name<?php echo $i; ?>"><?php echo e($Category['category_name']); ?> <span style="float: right;" id="productCount_<?php echo e($CategoryId); ?>"></span></a></li>

        <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse  <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">

        <?php $__currentLoopData = $Categories[$CategoryId]['subcategory']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li style="list-style: none;" ><a <?php if($subcategory_slug==$subcategory['subcategory_slug']): ?> class="activesubcategory" <?php endif; ?>  <?php if(empty($is_seller)): ?> href="<?php echo e(url('/')); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php else: ?> href="<?php echo e(url('/')); ?>/seller/<?php echo e($link_seller_name); ?>/<?php echo e(base64_encode($seller_id)); ?>/products/<?php echo e($Category['category_slug']); ?>/<?php echo e($subcategory['subcategory_slug']); ?>" <?php endif; ?>><?php echo e($subcategory['subcategory_name']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      <?php endif; ?>

    <?php $j++; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php endif; ?>

<?php if(Request::segment(4) !='products'): ?>
  <div class="category_list_box show_service_cat_sidebar"  id="accordion">
  <h2 class=""><?php echo e(__('lang.service_categories_head')); ?></h2>
  <ul class="seller_cat_list">

  <?php $j=0; ?>

  <?php $__currentLoopData = $ServiceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CategoryId=>$Category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php 
      $j++;
      $cls='';
      if($category_slug==$Category['category_slug'])
        $cls  =       'activeservicemaincategory';
      
    ?>
<!--else if( $j==1) $cls  =       'activeservicemaincategory';-->
    <?php if(!empty($ServiceCategories[$CategoryId]['subcategory'])): ?>
      <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#servicesubcategories<?php echo $j; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#"><?php echo e($Category['category_name']); ?> <span style="float: right;" id="serviceCount_<?php echo e($j); ?>"></span></a></li>

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
<?php endif; ?>
<div>

<div>&nbsp;</div>
<?php if(Request::path() != "/" && Request::segment(4) !='products' && Request::segment(4) !='services'): ?>
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/js/css/bootstrap-slider.css" />
  <script src="<?php echo e(url('/')); ?>/assets/front/js/bootstrap-slider.js"></script>
  <hr>
  <label><?php echo e(__('lang.sort_by_price')); ?></label>

  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="150000" data-slider-step="500" data-slider-value="[0,150000]"/>
  <!-- <b>€ 1000</b> -->
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <label><?php echo e(__('users.place_label')); ?></label>
  <input type="text" name="city_name" id="city_name" class="form-control input-lg" placeholder="<?php echo e(__('users.enter_city_placeholder')); ?>" />
  <div id="cityList"></div>
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <label><?php echo e(__('users.type_label')); ?></label>
  <div class="category_button">
  <button class="show_all_cat"><?php echo e(__('users.all_btn')); ?></button>
  <button class="show_product_cat"><?php echo e(__('lang.product_label')); ?></button>
  <button class="show_service_cat"><?php echo e(__('lang.service_label')); ?></button>
  </div>
  <div>&nbsp;</div>
  <?php if(Request::path() != "/" && Request::segment(1) !='products' && Request::segment(1) !='services'): ?>
    <?php if(empty($is_seller)): ?>
    <!-- Seller Listing -->
      <h2 class="de_col"><?php echo e(__('lang.sellers_head')); ?></h2>
      <span class="seller_list_content"></span>
    <?php endif; ?>
  <?php endif; ?>
  <?php endif; ?>
</div>

<script type="text/javascript">
 
$(document).ready(function(){
  /*search by city */
 $('#city_name').keyup(function(){ 
        var query = $(this).val();
       
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"<?php echo e(route('getCity')); ?>",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
            $('#cityList').fadeIn();  
            $('#cityList').html(data);
          }
         });
        }
    });

   $(document).on('click', '.city_autocomplete', function(){  
        $('#city_name').val($(this).text());  
        $('#cityList').fadeOut();  
    });  

   $(document).on('click', '.show_all_cat', function(){  
     $(this).addClass('active');
     $('.show_product_cat').removeClass('active');
     $('.show_service_cat').removeClass('active');
        $('.show_product_cat_sidebar').show();
        $('.show_service_cat_sidebar').show();
        $('.all_cat_label').show();

    });

   $(document).on('click', '.show_product_cat', function(){ 
    $(this).addClass('active');
     $('.show_all_cat').removeClass('active');
     $('.show_service_cat').removeClass('active');
        $('.show_product_cat_sidebar').show();
        $('.show_service_cat_sidebar').hide();
        $('.all_cat_label').hide();
        
    });

   $(document).on('click', '.show_service_cat', function(){  
    $(this).addClass('active');
     $('.show_all_cat').removeClass('active');
     $('.show_product_cat').removeClass('active');
         $('.show_service_cat_sidebar').show();
        $('.show_product_cat_sidebar').hide();
        $('.all_cat_label').hide();
    });

});
</script><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/products_sidebar.blade.php ENDPATH**/ ?>
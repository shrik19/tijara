<?php
$class = (strpos(@$path, 'annonser') !== false || strpos(@$path, 'seller') !== false || strpos(@$path, 'products') !== false) ? 'col-md-3':'col-md-15';
?>

<li class="<?php echo e($class); ?>">

  <div class="product_data product_link_js" product_link="<?php echo e($product->product_link); ?>" <?php if($product->is_sold == '1'): ?> style="pointer-events: none;opacity: 0.4;"  <?php endif; ?>>
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      <?php if($product->image): ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($product->image); ?>" >
      <?php else: ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" >
      <?php endif; ?>

      <?php 

          $seller_name = $product->seller;
          $seller_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
          $seller_name = str_replace(" ", '-', $seller_name);
          $seller_name = strtolower($seller_name);
                      
          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($product->user_id)."/products"; 
        ?>
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details one_icon">
          <ul>
              <!--<li><a href="<?php echo e($product->product_link); ?>"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" <?php if(Auth::guard('user')->id()): ?> onclick="addToCart('<?php echo e($product->variant_id); ?>');event.stopPropagation();" <?php else: ?> onclick="showErrorMessage('<?php echo e(trans('errors.login_buyer_required')); ?>','<?php echo e(route('frontLogin')); ?>');event.stopPropagation();" <?php endif; ?>><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
              -->
              <li><a href="javascript:void(0);" <?php if(Auth::guard('user')->id()): ?> 
                    onclick="addToWishlist('<?php echo e($product->variant_id); ?>');event.stopPropagation();" 
                    <?php else: ?> onclick="showErrorMessage('<?php echo e(trans('errors.login_buyer_required')); ?>','<?php echo e(route('frontLogin')); ?>');event.stopPropagation();" <?php endif; ?>>
                    <i class="far fa-heart"></i>
                  </a>
              </li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        <?php 
       $category_name = $product->category_name;
        $category_name = str_replace( array( '\'', '"', 
        ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $category_name);
        $category_name = str_replace(" ", '-', $category_name);
        $category_name = strtolower($category_name);
                    
      $product_cat_link= url('/').'/products/'.$category_name; ?>

        <?php if( Request::path() == "/"): ?>
         <a href="<?php echo e($product->product_link); ?>" title="<?php echo e($product->title); ?>"><h4><?php echo substr($product->title, 0, 50) ?></h4></a>
         <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_<?php echo e($product->id); ?>' data-id='rating_<?php echo e($product->id); ?>' data-rating='<?php echo e($product->rating); ?>'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>

        <?php if(!empty($product->price)): ?>
        <h6 class="product_price"> <?php if(!empty($product->discount_price)): ?> <?php echo e($product->discount_price); ?> kr <?php endif; ?> <span <?php if(!empty($product->discount_price)): ?> class="dic_price" <?php endif; ?>><?php echo e($product->price); ?> kr </span><span <?php if(!empty($product->discount)): ?> class="dic_percent" <?php endif; ?> ><?php if(!empty($product->discount)): ?> (<?php echo $product->discount."% off";?>) <?php endif; ?></span></h6>
        <?php endif; ?>
           <a href="<?php echo e($seller_link); ?>"><h5><?php echo e($product->seller); ?></h5></a>
          <?php /*<a href="{{$product_cat_link}}"><h5>{{$product->category_name}}</h5></a> */?>
        <?php else: ?>

          <?php if(Request::segment(1) !='product'): ?>
           <a href="<?php echo e($product_cat_link); ?>"><h5><?php echo e($product->category_name); ?></h5></a> 
          <?php endif; ?>
          <a href="<?php echo e($product->product_link); ?>" title="<?php echo e($product->title); ?>"><h4><?php echo substr($product->title, 0, 50) ?></h4></a>
         <?php if(Request::segment(1) !='products' && Request::segment(1) != 'get_product_listing'): ?>
        <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_<?php echo e($product->id); ?>' data-id='rating_<?php echo e($product->id); ?>' data-rating='<?php echo e($product->rating); ?>'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  
        <?php endif; ?>
        <?php if(!empty($product->price)): ?>
        <h6 class="product_price"> <?php if(!empty($product->discount_price)): ?> <?php echo e($product->discount_price); ?> kr <?php endif; ?> <span <?php if(!empty($product->discount_price)): ?> class="dic_price" <?php endif; ?>><?php echo e($product->price); ?> kr </span><span <?php if(!empty($product->discount)): ?> class="dic_percent" <?php endif; ?> ><?php if(!empty($product->discount)): ?>(<?php echo $product->discount."% off";?>)<?php endif; ?></span></h6>
        <?php endif; ?>

         
        <?php if(Request::segment(1) !='products' && Request::segment(1) != 'get_product_listing'): ?>
         <a href="<?php echo e($seller_link); ?>"><h5><?php echo e($product->seller); ?></h5></a>
        <?php endif; ?>

        <?php endif; ?>
        
        <!--  -->
        <input type="hidden" name="product_quantity_<?php echo e($product->variant_id); ?>" id="product_quantity_<?php echo e($product->variant_id); ?>" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('<?php echo e($product->variant_id); ?>');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>
</li>
<script type="text/javascript">
  $(".product_link_js").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/products_widget.blade.php ENDPATH**/ ?>
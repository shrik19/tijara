<li class="col-xs-15">
  <div class="product_data product_link_js" product_link="<?php echo e($product->product_link); ?>">
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      <?php if($product->image): ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($product->image); ?>" >
      <?php else: ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" >
      <?php endif; ?>
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details one_icon">
          <ul>
             <?php /*<li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li> */?>
              <li><a href="javascript:void(0);" <?php if(Auth::guard('user')->id()): ?> onclick="addToWishlistproducts('<?php echo e($product->id); ?>');" <?php else: ?> onclick="showErrorMessage('<?php echo e(trans('errors.login_buyer_required')); ?>','<?php echo e(route('frontLogin')); ?>');" <?php endif; ?>><i class="far fa-heart"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        <!-- <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_<?php echo e($product->id); ?>' data-id='rating_<?php echo e($product->id); ?>' data-rating='<?php echo e($product->rating); ?>'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  -->
        <?php $product_cat_link= url('/').'/products/'.strtolower($product['category_name']); ?>
        <!-- <a href="<?php echo e($product_cat_link); ?>">
        <h5><?php echo e($product['category_name']); ?></h5></a> -->
        <a href="<?php echo e($product->product_link); ?>" title="<?php echo e($product->title); ?>"><h4><?php echo substr($product->title, 0, 50) ?></h4></a>
        <?php if(!empty($product->price)): ?>
        <h6 class="product_price"><?php echo e($product->price); ?> kr</h6>
        <?php endif; ?>
        <h6 ><?php echo e($product->seller); ?></h6>
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
</script><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/featured_product.blade.php ENDPATH**/ ?>
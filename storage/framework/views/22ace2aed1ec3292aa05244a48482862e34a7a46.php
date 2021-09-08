<li style="min-height:500px;">
  <div class="product_data" <?php if($product->is_sold == '1'): ?> style="pointer-events: none;opacity: 0.4;"  <?php endif; ?>>
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      <?php if($product->image): ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($product->image); ?>" style="width:100%;">
      <?php else: ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" style="width:100%;">
      <?php endif; ?>
      <div class="buy_now_hover_details" style="height:280px !important;">
          <ul>
              <li><a href="<?php echo e($product->product_link); ?>"><i class="fa fa-search"></i></a></li>
            </ul>
      </div>
    </div>
    <div class="product_info">
        <h5><?php echo e($product['category_name']); ?></h5>
          
        <a href="<?php echo e($product->product_link); ?>"><h4><?php echo substr($product->title, 0, 50) ?></h4></a>
        <?php if(!empty($product->price)): ?>
        <h6><?php echo e($product->price); ?> kr</h6>
        <?php endif; ?>
        <h6><?php echo e($product->seller); ?></h6>
        <input type="hidden" name="product_quantity_<?php echo e($product->variant_id); ?>" id="product_quantity_<?php echo e($product->variant_id); ?>" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('<?php echo e($product->variant_id); ?>');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>


</li>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/similar_products_widget.blade.php ENDPATH**/ ?>
<li style="max-height:500px;">
  <?php 


        $product_link = url('/').'/product';
        /*if($category_slug!='')
            {
                  $product_link .=  '/'.$category_slug;
            }
            else {
              $product_link .=  '/'.$productCategories[0]['category_slug'];
            }
            if($subcategory_slug!='')
            {
                  $product_link .=  '/'.$subcategory_slug;
            }
            else {
              $product_link .=  '/'.$productCategories[0]['subcategory_slug'];
            }*/

        $product_link .=  '/'.$product->product_slug.'-P-'.$product->product_code;

       // $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
       // $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];

        $product_link  = $product_link;
          ?>
  <div  product_link="<?php echo e($product_link); ?>" class="product_data" <?php if($product->is_sold == '1'): ?> style="pointer-events: none;opacity: 0.4;"  <?php endif; ?> >
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      <?php if($product->image): ?>
      <?php 
        $productImage = explode(",",$product->image);
        $img =$productImage[0];
       ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($img); ?>" >
      <?php else: ?>
          <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" >
      <?php endif; ?>
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details ">
          <ul>
              <li style="margin-left: 30%;"><a href="<?php echo e($product->product_link); ?>"><i class="fa fa-search"></i></a></li>
            </ul>
      </div>
    </div>

    <div class="product_info">
        <h5><?php echo e($product['category_name']); ?></h5>
         
        <a href="<?php echo e($product_link); ?>" title="<?php echo e($product->title); ?>"><h4><?php echo substr($product->title, 0, 50) ?></h4></a>
        <?php if(!empty($product->price)): ?>
        <h6><?php echo e($product->price); ?> kr</h6>
        <?php endif; ?>
        <h6><?php echo e($product->seller); ?></h6>
        <input type="hidden" name="product_quantity_<?php echo e($product->variant_id); ?>" id="product_quantity_<?php echo e($product->variant_id); ?>" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('<?php echo e($product->variant_id); ?>');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>


</li>
<script type="text/javascript">
  $(".product_data").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/similar_products_widget.blade.php ENDPATH**/ ?>
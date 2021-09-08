
<?php $__env->startSection('middlecontent'); ?>

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<section class="product_details_section-1">
<div class="loader"></div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-md-6">
              <h2><?php echo e(__('lang.shopping_cart')); ?></h2>
              <hr class="heading_line"/>
            </div>
            <div class="col-md-6 text-right">
           <button type="button" class="btn buy_now_btn debg_color" onclick="location.href='<?php echo e(route('frontHome')); ?>';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> <?php echo e(__('lang.shopping_cart_continue')); ?>

                        </button>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                         <th><?php echo e(__('users.butik_btn')); ?></th>
                        <th><?php echo e(__('lang.shopping_cart_product')); ?></th>
                        <th><?php echo e(__('lang.shopping_cart_quantity')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_price')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_shipping')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_total')); ?></th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  <?php if(!empty($details)): ?>
                  <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderId => $tmpOrderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $tmpOrderProduct['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <tr>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="<?php echo e($orderProduct['product']->product_link); ?>"> 
                            <?php if(isset($orderProduct['sellerLogo']) && !empty($orderProduct['sellerLogo'])): ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/Seller/resized/<?php echo e($orderProduct['sellerLogo']); ?>" class="media-object" style="width: 72px; height: 72px;">
                            <?php else: ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            <?php endif; ?>
                                                      
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="<?php echo e($orderProduct['product']->seller_link); ?>"><?php echo e($orderProduct['product']->store_name); ?></a></h4>
                                <!-- <h5 class="media-heading"> <?php echo e($orderProduct['variant_attribute_id']); ?> </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="<?php echo e($orderProduct['product']->product_link); ?>"> 
                            <?php if($orderProduct['product']['image']): ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($orderProduct['product']->image); ?>" class="media-object" style="width: 72px; height: 72px;">
                            <?php else: ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            <?php endif; ?>
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="<?php echo e($orderProduct['product']->product_link); ?>"><?php echo e($orderProduct['product']->title); ?></a></h4>
                                <h5 class="media-heading"> <?php echo e($orderProduct['variant_attribute_id']); ?> </h5>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        <select name="quantity_<?php echo e($orderProduct['id']); ?>" id="quantity_<?php echo e($orderProduct['id']); ?>" class="form-control" onchange="updateCart('<?php echo e($orderProduct['id']); ?>')">
                            <option value="1" <?php if($orderProduct['quantity'] == 1): ?> selected="selected" <?php endif; ?>>1</option>
                            <option value="2" <?php if($orderProduct['quantity'] == 2): ?> selected="selected" <?php endif; ?>>2</option>
                            <option value="3" <?php if($orderProduct['quantity'] == 3): ?> selected="selected" <?php endif; ?>>3</option>
                            <option value="4" <?php if($orderProduct['quantity'] == 4): ?> selected="selected" <?php endif; ?>>4</option>
                            <option value="5" <?php if($orderProduct['quantity'] == 5): ?> selected="selected" <?php endif; ?>>5</option>
                            <option value="6" <?php if($orderProduct['quantity'] == 6): ?> selected="selected" <?php endif; ?>>6</option>
                            <option value="7" <?php if($orderProduct['quantity'] == 7): ?> selected="selected" <?php endif; ?>>7</option>
                            <option value="8" <?php if($orderProduct['quantity'] == 8): ?> selected="selected" <?php endif; ?>>8</option>
                            <option value="9" <?php if($orderProduct['quantity'] == 9): ?> selected="selected" <?php endif; ?>>9</option>
                            <option value="10" <?php if($orderProduct['quantity'] == 10): ?> selected="selected" <?php endif; ?>>10</option>
                        </select>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format($orderProduct['price'],2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong><?php echo e(number_format($orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                        <a href="javascript:void(0);" style="color:red;" onclick="removeCartProduct('<?php echo e($orderProduct['id']); ?>')" title="Remove"><i class="fas fa-trash"></i></button>
                        <!-- <button type="button" class="btn btn-danger" onclick="removeCartProduct('<?php echo e($orderProduct['id']); ?>')">
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button> -->
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5><?php echo e(__('lang.shopping_cart_subtotal')); ?></h5></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($tmpOrderProduct['subTotal'],2)); ?> kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5><?php echo e(__('lang.shopping_cart_shipping')); ?></h5></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($tmpOrderProduct['shippingTotal'],2)); ?> kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3><?php echo e(__('lang.shopping_cart_total')); ?></h3></td>
                        <td class="text-right"><h4><strong><?php echo e(number_format($tmpOrderProduct['Total'],2)); ?> kr</strong></h4></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>
                        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" <?php if($tmpOrderProduct['is_buyer_product']): ?> onclick="location.href='<?php echo e(route('frontShowBuyerCheckout' , ['id' => base64_encode($orderId)])); ?>'" <?php else: ?>  onclick="location.href='<?php echo e(route('frontShowCheckout', ['id' => base64_encode($orderId)])); ?>'" <?php endif; ?>>
                        <?php echo e(__('lang.shopping_cart_checkout')); ?> <span class="glyphicon glyphicon-play"></span>
                        </button></td>
                    </tr>
                    <tr><td colspan="6" style="border:none;line-height:60px;">&nbsp;</td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6"><?php echo e(__('lang.shopping_cart_no_records')); ?> <a href="<?php echo e(route('frontHome')); ?>"><?php echo e(__('lang.shopping_cart_continue')); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/shopping_cart.blade.php ENDPATH**/ ?>
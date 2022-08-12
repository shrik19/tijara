
<?php $__env->startSection('middlecontent'); ?>

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<div class="mid-section p_155" style="background: #dddddd;min-height:62vh;   margin-bottom: 0px;">
<div class="container-fluid">
<div class="container-inner-section-1">

<div>
<div> 
<div class="seller_info shopping_cart_page border-none">
    <div class="card">
        <div class="card-header row">
            <div class="col-md-6 p-m-0 pl-0">
            <h2 class="page_heading m-l-12"><?php echo e(__('lang.shopping_cart')); ?></h2> 
            </div> 
            <div class="col-md-6 text-right">
      <!--      <button type="button" class="btn buy_now_btn debg_color" onclick="location.href='<?php echo e(route('frontHome')); ?>';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> <?php echo e(__('lang.shopping_cart_continue')); ?>

                        </button> -->
            </div>     
        </div>
    </div>
<div class="seller_mid_cont"  style="margin-top: 20px;">
<section class="product_details_section-1">
<div class="loader"></div>
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-sm-12 col-md-12 p-m-0 pl-0">
        <div class="row">
            <!-- <div class="col-md-6">
              <h2><?php echo e(__('lang.shopping_cart')); ?></h2>
              <hr class="heading_line"/>
            </div> -->
            <!-- <div class="col-md-6 text-right">
           <button type="button" class="btn buy_now_btn debg_color" onclick="location.href='<?php echo e(route('frontHome')); ?>';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> <?php echo e(__('lang.shopping_cart_continue')); ?>

                        </button>
            </div> -->
          </div>
            <table class="table table-hover shopping_cart" style="margin-bottom:60px;">
                <thead>
                    <tr>
                         <th><?php echo e(__('users.butik_btn')); ?></th>
                        <th><?php echo e(__('lang.shopping_cart_product')); ?></th>
                        <th><?php echo e(__('lang.shopping_cart_quantity')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_price')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_shipping')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_total')); ?></th>
                        <th class="table_blank"> </th>
                    </tr>
                </thead>
                <tbody>
                  <?php if(!empty($details)): ?>
                  <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderId => $tmpOrderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if(!empty($tmpOrderProduct)): ?>
                   <?php   $inc=1; ?>
                    <?php $__currentLoopData = $tmpOrderProduct['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               
                    <tr>
                        <td class="col-sm-4 col-md-4 p-m-0">
                            <?php if($inc==1): ?>
                        <div class="media cart-store-sec bg-white">
                            <a class="thumbnail pull-left custom_thumbnail" href="<?php echo e($orderProduct['product']->seller_link); ?>"> 
                            <?php if(isset($orderProduct['sellerLogo']) && !empty($orderProduct['sellerLogo'])): ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/Seller/resized/<?php echo e($orderProduct['sellerLogo']); ?>" class="media-object seller-show-icon">
                            <?php else: ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" class="media-object seller-show-icon">
                            <?php endif; ?>
                                                      
                            </a>
                             
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">

                                <h4 class="media-heading product_sorting_filter_option"><a href="<?php echo e($orderProduct['product']->seller_link); ?>"><?php echo e($orderProduct['product']->store_name); ?></a></h4>
                                <!-- <h5 class="media-heading"> <?php echo e($orderProduct['variant_attribute_id']); ?> </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                            
                        </div><?php endif; ?></td>
                        <span>
                        <td class="col-sm-4 col-md-4 bg-white">
                        <div class="media">
                            <a class="thumbnail pull-left custom_thumbnail" href="<?php echo e($orderProduct['product']->product_link); ?>"> 
                            <?php if($orderProduct['product']['image']): ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($orderProduct['product']->image); ?>" class="media-object show-cart-product" style="width: 72px; height: 72px;padding: 1px;">
                            <?php else: ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" class="media-object show-cart-product" style="width: 72px; height: 72px;padding: 1px;">
                            <?php endif; ?>
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading product_sorting_filter_option"><a href="<?php echo e($orderProduct['product']->product_link); ?>"><?php echo e($orderProduct['product']->title); ?></a></h4>
                                <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?> </h5>
                                <?php /*
                                @if($orderProduct['product']->is_pick_from_store ==1)
                                <h4  class="media-heading product_sorting_filter_option"> 
                                    {{ __('users.pick_up_address')}} : {{@$orderProduct['product']->store_pick_address}}
                                </h4>
                                @endif
                                */?>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1 bg-white tijara_quantity" style="text-align: center">
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
                        <td class="col-sm-2 col-md-2 text-right bg-white"><p class="product_sorting_filter_option">
                            <?php                                 
                                $price_tbl = swedishCurrencyFormat($orderProduct['price']);
                            ?>
                               <?php echo e($price_tbl); ?> kr
                        <?php /*$price_tbl = strrev(implode(" ", $price_array_tbl));
                                $price_tbl = $price_tbl.",00";
                                {{ number_format($orderProduct['price'],2) }} kr */ ?></p></td>
                        <td class="col-sm-1 col-md-1 text-right bg-white"><p  class="product_sorting_filter_option">
                            <?php 
                                $shipping_array_tbl = str_split(strrev($orderProduct['shipping_amount']), 3);
                                $shipping_tbl = strrev(implode(" ", $shipping_array_tbl));
                                $shipping_tbl = $shipping_tbl.",00";
                            ?>
                            <?php echo e($shipping_tbl); ?> kr
                       <?php /*  {{ number_format($orderProduct['shipping_amount'],2)}} kr */ ?></p></td>
                        <td class="col-sm-2 col-md-2 text-right bg-white">
                            <p  class="product_sorting_filter_option p-l-8">
                                <?php 
                                    $amt_total =(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount']); $total_price_tbl = swedishCurrencyFormat($amt_total);

                                ?>
                               <?php echo e($total_price_tbl); ?> kr
                               
                                <?php /*
                                  //$total_tbl = str_split(strrev($amt_total), 3);
                                    //$total_price_tbl = strrev(implode(" ", $total_tbl));
                                   // $total_price_tbl = $total_price_tbl.",00";
                                {{ number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)}} kr */ ?>
                            </p>
                        </td>
                        <td class="col-sm-1 col-md-1 text-right bg-white">
                        <a href="javascript:void(0);" style="color:red;" onclick="removeCartProduct('<?php echo e($orderProduct['id']); ?>')" title="Remove"><i class="fas fa-trash"></i></button>
                        <!-- <button type="button" class="btn btn-danger" onclick="removeCartProduct('<?php echo e($orderProduct['id']); ?>')">
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button> -->
                      </td>
                    </tr>
                    <?php $inc++; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="ttl-sec">
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white bbvbvb"><h5  class="product_sorting_filter_option p-l-8"><?php echo e(__('lang.shopping_cart_subtotal')); ?></h5></td>
                        <td class="text-right bg-white"><h5 class="product_sorting_filter_option">
                            <?php 
                                $subTotal = swedishCurrencyFormat($tmpOrderProduct['subTotal']);
                            ?>
                            <?php echo e($subTotal); ?> kr
                        <?php /* $price_subTotal_array = str_split(strrev($tmpOrderProduct['subTotal']), 3);
                                $subTotal = strrev(implode(" ", $price_subTotal_array));
                                $subTotal = $subTotal.",00";
                                {{number_format($tmpOrderProduct['subTotal'],2)}} kr */?></h5></td>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank" >   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white"><h5 class="product_sorting_filter_option p-l-8"><?php echo e(__('lang.shopping_cart_shipping')); ?></h5></td>
                        <td class="text-right bg-white"><h5 class="product_sorting_filter_option">

                             <?php  
                                $price_shipping_array = str_split(strrev($tmpOrderProduct['shippingTotal']), 3);
                                $shippingTotal = strrev(implode(" ", $price_shipping_array));
                                $shippingTotal = $shippingTotal.",00";
                            ?>
                            <?php echo e($shippingTotal); ?> kr
                            <?php /* {{number_format($tmpOrderProduct['shippingTotal'],2)}} kr */?></h5></td>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white"><h4 class="cart_total_css p-l-8"><?php echo e(__('lang.shopping_cart_total')); ?></h4></td>
                        <td class="text-right bg-white"><h4 class="cart_total_css ">
                            <?php 
                                $price_nice = swedishCurrencyFormat($tmpOrderProduct['Total']);
                            ?>
                        <?php echo e($price_nice); ?> kr</h4></td>
                        <?php /*$price_array = str_split(strrev($tmpOrderProduct['Total']), 3);
                                $price_nice = strrev(implode(" ", $price_array));
                                $price_nice = $price_nice.",00";
                                {{number_format($tmpOrderProduct['Total'],2)}} kr */?>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td>
                        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" <?php if($tmpOrderProduct['is_buyer_product']): ?> onclick="location.href='<?php echo e(route('frontShowBuyerCheckout' , ['id' => base64_encode($orderId)])); ?>'" <?php else: ?>  onclick="location.href='<?php echo e(route('frontShowPaymentOptions', ['id' => base64_encode($orderId)])); ?>'" <?php endif; ?>>
                        <?php echo e(__('lang.shopping_cart_checkout')); ?> <span class="glyphicon glyphicon-play"></span>
                        </button></td>
						<td>   </td>
                    </tr>
                    <tr><td colspan="6" style="border:none;line-height:60px;">&nbsp;</td></tr>
                    <?php endif; ?>
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
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/shopping_cart.blade.php ENDPATH**/ ?>
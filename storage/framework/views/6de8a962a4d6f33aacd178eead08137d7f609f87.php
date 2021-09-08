<!DOCTYPE html>
<html>
<head>
  <title><?php echo e(__('messages.txt_order_details')); ?> - #<?php echo e($order['id']); ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tr>
                            <td><img class="logo" src="<?php echo e(url('/')); ?>/assets/img/logo.png" height="70"/></td>
                            <td><h2><?php echo e(__('messages.txt_order_details')); ?> - #<?php echo e($order['id']); ?></h2></td>
                        </tr>
                    </table>        
                    
                </div>
                <div class="col-md-12">
                <hr class="heading_line"/>
                <table class="table table-hover">
                    <tr>
                        <td>
                            <?php if(!empty($billingAddress)): ?>
                                <h4><strong><?php echo e(__('messages.txt_billing_address')); ?></strong></h4>
                                <span style="font-size:16px;"><?php echo e($billingAddress['given_name']); ?> <?php echo e($billingAddress['family_name']); ?><br />
                                <?php echo e($billingAddress['email']); ?><br />
                                <?php echo e($billingAddress['street_address']); ?><br />
                                <?php echo e($billingAddress['city']); ?>, <?php echo e($billingAddress['postal_code']); ?><br />
                                <?php echo e($billingAddress['phone']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!empty($shippingAddress)): ?>
                                <h4><strong><?php echo e(__('messages.txt_billing_address')); ?></strong></h4>
                                <span style="font-size:16px;"><?php echo e($shippingAddress['given_name']); ?> <?php echo e($shippingAddress['family_name']); ?><br />
                                <?php echo e($shippingAddress['email']); ?><br />
                                <?php echo e($shippingAddress['street_address']); ?><br />
                                <?php echo e($shippingAddress['city']); ?>, <?php echo e($shippingAddress['postal_code']); ?><br />
                                <?php echo e($shippingAddress['phone']); ?></span>
                            <?php endif; ?>
                        </td>

                    </tr>
                </table>
                </div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <table class="table table-hover" width="100%" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th><?php echo e(__('lang.shopping_cart_product')); ?></th>
                        <th><?php echo e(__('lang.shopping_cart_quantity')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_price')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_shipping')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_total')); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($details)): ?>
                  <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="col-sm-1 col-md-1">
                            <a href="<?php echo e($orderProduct['product']->product_link); ?>"> 
                                <?php if($orderProduct['product']['image']): ?>
                                    <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($orderProduct['product']->image); ?>" style="max-width: none; width: 10%">
                                <?php else: ?>
                                    <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" style="max-width: none; width: 10%">
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <h4 class="media-heading"><a href="<?php echo e($orderProduct['product']->product_link); ?>"><?php echo e($orderProduct['product']->title); ?></a></h4>
                            <h5 class="media-heading"> <?php echo e($orderProduct['variant_attribute_id']); ?> </h5>
                        </td>    
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        <?php echo e($orderProduct['quantity']); ?>

                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format($orderProduct['price'],2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong><?php echo e(number_format($orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5><?php echo e(__('lang.shopping_cart_subtotal')); ?></h5></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($subTotal,2)); ?> kr</strong></h5></td>
                    </tr>
                    <tr>
                    <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5><?php echo e(__('lang.shopping_cart_shipping')); ?></h5></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($shippingTotal,2)); ?> kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td colspan="2"> 
                        <span style="font-size:16px;">
                            <?php echo e(__('messages.txt_seller')); ?> : <a href="<?php echo e($seller_link); ?>"><?php echo e($seller_name); ?></a><br />
                            <?php echo e(__('messages.txt_payment_status')); ?> : <?php echo e($order['payment_status']); ?> <br />
                            <?php echo e(__('messages.txt_order_status')); ?> : <?php echo e($order['order_status']); ?> 
                        </span> 
                        </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3><?php echo e(__('lang.shopping_cart_total')); ?></h3></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($Total,2)); ?> kr</strong></h5></td>
                    </tr>
                <?php endif; ?>  
                </tbody>
            </table>    
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/download_order_details.blade.php ENDPATH**/ ?>
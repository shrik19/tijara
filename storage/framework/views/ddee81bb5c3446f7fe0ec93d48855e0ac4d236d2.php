
<?php $__env->startSection('middlecontent'); ?>

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<section class="product_details_section">
<div class="loader"></div>
<div class="container printdiv">
    <div class="row">
    <div class="col-md-2">
      <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
        <div class="col-sm-12 col-md-10">
            <div class="row">
                <div class="col-md-12">
                <h2><?php echo e(__('messages.txt_order_details')); ?> - #<?php echo e($order['id']); ?></h2>
                <hr class="heading_line"/>
                </div>
            </div>
            <div class="row">
                <?php if(!empty($billingAddress)): ?>
                <div class="col-sm-12 col-md-6">
                    <h4><strong><?php echo e(__('messages.txt_billing_address')); ?></strong></h4>
                    <span style="font-size:16px;"><?php echo e($billingAddress['given_name']); ?> <?php echo e($billingAddress['family_name']); ?><br />
                    <?php echo e($billingAddress['email']); ?><br />
                    <?php echo e($billingAddress['street_address']); ?><br />
                    <?php echo e($billingAddress['city']); ?>, <?php echo e($billingAddress['postal_code']); ?><br />
                    <?php echo e($billingAddress['phone']); ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($shippingAddress)): ?>
                <div class="col-sm-12 col-md-6 text-right">
                    <h4><strong><?php echo e(__('messages.txt_billing_address')); ?></strong></h4>
                    <span style="font-size:16px;"><?php echo e($shippingAddress['given_name']); ?> <?php echo e($shippingAddress['family_name']); ?><br />
                    <?php echo e($shippingAddress['email']); ?><br />
                    <?php echo e($shippingAddress['street_address']); ?><br />
                    <?php echo e($shippingAddress['city']); ?>, <?php echo e($shippingAddress['postal_code']); ?><br />
                    <?php echo e($shippingAddress['phone']); ?><span />
                </div>
                <?php endif; ?>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
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
                        <span id="quantity_<?php echo e($orderProduct['id']); ?>" > <?php echo e($orderProduct['quantity']); ?> </span>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format($orderProduct['product']->price,2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong><?php echo e(number_format($orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format(($orderProduct['product']->price * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)); ?> kr</strong></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
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
                        <td><h5><?php echo e(__('lang.shopping_cart_shipping')); ?></h5></td>
                        <td class="text-right"><h5><strong><?php echo e(number_format($shippingTotal,2)); ?> kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td> 
                        <span style="font-size:16px;">
                            <?php echo e(__('messages.txt_seller')); ?> : <a href="<?php echo e($seller_link); ?>"><?php echo e($seller_name); ?></a><br />
                            <?php echo e(__('messages.txt_payment_status')); ?> : <?php echo e($order['payment_status']); ?> <br />
                            <?php echo e(__('messages.txt_order_status')); ?> : 
                            <?php if($is_seller || $is_buyer_order): ?> 
                            <select name="order_status" id="order_status" class="form-control" style="width: 50%;display: inline-block;">
                                <option value="PENDING" <?php if($order['order_status'] == 'PENDING'): ?> selected="selected" <?php endif; ?>>PENDING</option>
                                <option value="SHIPPED" <?php if($order['order_status'] == 'SHIPPED'): ?> selected="selected" <?php endif; ?>>SHIPPED</option>
                                <option value="COMPLETE" <?php if($order['order_status'] == 'COMPLETE'): ?> selected="selected" <?php endif; ?>>COMPLETE</option>
                                <option value="CANCELLED" <?php if($order['order_status'] == 'CANCELLED'): ?> selected="selected" <?php endif; ?>>CANCELLED</option>

                            </select> 
                            <?php else: ?> 
                                <?php echo e($order['order_status']); ?> 
                            <?php endif; ?>
                        </span> 
                        </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3><?php echo e(__('lang.shopping_cart_total')); ?></h3></td>
                        <td class="text-right"><h4><strong><?php echo e(number_format($Total,2)); ?> kr</strong></h4></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="5"><?php echo e(__('lang.shopping_cart_no_records')); ?> <a href="<?php echo e(route('frontHome')); ?>"><?php echo e(__('lang.shopping_cart_continue')); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" onclick="printDiv();"><?php echo e(__('messages.txt_order_details_print')); ?> <span class="glyphicon glyphicon-print"></span></button>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
</div>

</section>
<script type="text/javascript">
    function printDiv() 
    {
        var divToPrint=jQuery(".printdiv");
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.html()+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }

    if($("#order_status").length)
    {
        $("#order_status").change(function()
        {
            var order_status = $(this).val();
            var order_id = "<?php echo e($order['id']); ?>";
            
            $.confirm({
                title: 'Confirm!',
                content: "<?php echo e(__('lang.order_status_confirm')); ?>",
                type: 'orange',
                typeAnimated: true,
                columnClass: 'medium',
                icon: 'fas fa-exclamation-triangle',
                buttons: {
                    okay: function () 
                    {
                        $(".loader").show();

                        $.ajax({
                        url:siteUrl+"/change-order-status",
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        type: 'post',
                        data : {'order_status': order_status, 'order_id' : order_id},
                        success:function(data)
                        {
                            $(".loader").hide();
                            var responseObj = $.parseJSON(data);
                            if(responseObj.status == 1)
                            {
                                showSuccessMessage(responseObj.msg);
                            }
                            else
                            {
                                if(responseObj.is_login_err == 0)
                                {
                                    showErrorMessage(responseObj.msg);
                                }
                                else
                                {
                                    showErrorMessage(responseObj.msg,'/front-login');
                                }
                            }

                        }
                        });
                    },
                    cancel: function () {
                        
                    },
                }
            });
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/order_details.blade.php ENDPATH**/ ?>
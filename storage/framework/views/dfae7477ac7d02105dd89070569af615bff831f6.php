
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
            <div class="col-md-12">
              <h2><?php echo e(__('lang.shopping_cart_product')); ?> <?php echo e(__('messages.txt_wishlist')); ?></h2>
              <hr class="heading_line"/>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th><?php echo e(__('lang.shopping_cart_product')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_price')); ?></th>
                        <th> </th>
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
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format($orderProduct['product']->price,2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                        <a href="javascript:void(0);" class="" style="color:#05999F;" 
                        onclick="addToCartWishlist('<?php echo e($orderProduct['variant_id']); ?>')"
                         title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></a>&nbsp;&nbsp;
                        <a href="javascript:void(0);" class="" style="color:red;" 
                        onclick="removeWishlistProduct('<?php echo e($orderProduct['id']); ?>')" 
                        title="Remove"><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3"><?php echo e(__('messages.wishlist_empty')); ?> <a href="<?php echo e(route('frontHome')); ?>"><?php echo e(__('lang.shopping_cart_continue')); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>


<!-- services wishlist -->

<section class="product_details_section-1">
<div class="loader"></div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
              <h2><?php echo e(__('lang.service_label')); ?> <?php echo e(__('messages.txt_wishlist')); ?></h2>
              <hr class="heading_line"/>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th><?php echo e(__('lang.service_label')); ?></th>
                        <th class="text-right"><?php echo e(__('lang.shopping_cart_price')); ?></th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  <?php if(!empty($detailsService)): ?>
                  <?php $__currentLoopData = $detailsService; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reqService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="<?php echo e($reqService['service']->service_link); ?>"> 
                            <?php if($reqService['service']['images']): ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/resized/<?php echo e($reqService['service']->images); ?>" class="media-object" style="width: 72px; height: 72px;">
                            <?php else: ?>
                              <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            <?php endif; ?>
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="<?php echo e($reqService['service']->service_link); ?>"><?php echo e($reqService['service']->title); ?></a></h4>
                               <!--  <h5 class="media-heading"> <?php echo e($reqService['variant_attribute_id']); ?> </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong><?php echo e(number_format((float)$reqService['service']->service_price,2)); ?> kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                     <!--    <button class="btn btn-success" onclick="addToCartWishlist('<?php echo e($reqService['id']); ?>')" title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></button> -->
                        <a href="<?php echo e($reqService['service']->service_link); ?>" style="color:#05999F;" class=""><?php echo e(__('lang.book_service')); ?></i></a>
                        &nbsp;&nbsp;
                        <a href="javascript:void(0);" class=" " 
                        style="color:red;" onclick="removeWishlistProduct('<?php echo e($reqService['id']); ?>')" 
                        title="Remove"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3"><?php echo e(__('messages.wishlist_empty')); ?> <a href="<?php echo e(route('frontHome')); ?>"><?php echo e(__('lang.shopping_cart_continue')); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/wishlist.blade.php ENDPATH**/ ?>
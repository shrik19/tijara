
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section p_155">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <!-- Example row of columns -->

      <div class="row">
        <div class="col-md-12">

          <div class="tijara-content">
             <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php if($subscribedError): ?>
              <div class="alert alert-danger update-alert-css"><?php echo e($subscribedError); ?></div>
            <?php endif; ?>
           
            <div class="seller_info border-none">
            <div class="card">
            <div class="card-header ml-0 row">
            <div class="col-md-9 pl-0">

            <h2 class="page_heading new_add_heading" ><?php echo e(__('users.buyer_product_list_title')); ?></h2>
            <!-- <hr class="heading_line"/> -->
            </div>
            <div class="col-md-3 new_add text-right">
            <a href="<?php echo e(route('frontProductCreate')); ?>" title="<?php echo e(__('lang.add_product')); ?>" class="btn btn-black btn-sm debg_color a_btn login_btn" ><span>+ <?php echo e(__('users.add_ads_btn')); ?></span> </a>
            </div>
            </div>
            </div>
            <div style="margin-top: 20px;">

            <div class="card">


            <div class="card-body">
            <div class="row">
            <?php if(!empty($buyerProducts) && count($buyerProducts) > 0): ?>

            <?php $__currentLoopData = $buyerProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php 
            if(!empty($value['image'])) {
            $imagesParts    =   explode(',',$value['image']); 
            $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
            }
            else{
            $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
            }
            $dated      =   date('Y-m-d',strtotime($value['created_at']));
            $title = (!empty($value['title'])) ? substr($value['title'], 0, 50) : '-';
            $price = $value['price'];
            $id =  $value['id'];

            $discount_price =0;
            if(!empty($value['discount'])) {
            $discount = number_format((($price * $value['discount']) / 100),2,'.','');
            $discount_price = $price - $discount;
            }

            ?> 
         <div class="col-md-15 buyer-ht">
                    <div class="card product-card product_data_img product_link_js">
            <img class="card-img-top buyer-product-img product_img_prd" src="<?php echo e($image); ?>" >
            <div class="card-body product_all">
            <h5 class="card-title"><?php echo e($dated); ?></h5>
            <p class="card-text buyer-product-title"><?php echo e($title); ?></p>
            <p class="card-text" style="margin-bottom: 20px;">  
            <span class="buyer-price buyer-product-price" id="product_variant_price">
            <span style="<?php if(!empty($discount_price)): ?> text-decoration: line-through; <?php endif; ?>">
                <?php                                 
                    $price_tbl = swedishCurrencyFormat($value['price']);
                ?>
                <?php echo e(@$price_tbl); ?> kr
            </span>
            <?php if(!empty($discount_price)): ?>
            <?php                                 
                $discount_price_tbl = swedishCurrencyFormat($discount_price);
            ?>
                 &nbsp;&nbsp;<?php echo e(@$discount_price_tbl); ?> kr <?php endif; ?> 
            &nbsp;&nbsp;
            <?php if(!empty($value->discount)): ?>
            <?php echo "(".$value->discount."% off)";?> <?php endif; ?></span> 
            </p>
            <!--     <div class="quantity_box">              
            <span style="padding-top:6px;position:absolute;font-size:20px;" id="product_variant_price"><span style="<?php if(!empty($value['discount_price'])): ?> text-decoration: line-through; <?php endif; ?>"><?php echo e(number_format($value['price'],2)); ?> kr</span> <?php if(!empty($value['discount_price'])): ?> &nbsp;&nbsp;<?php echo e(number_format($value['discount_price'],2)); ?> kr <?php endif; ?></span> 
            </div> -->

            <div class="buyer-button">
            <a href="<?php echo e(route('frontProductEdit', base64_encode($id))); ?>" class="btn btn-black btn-sm debg_color login_btn a_btn" title="<?php echo e(__('lang.edit_label')); ?>"><?php echo e(__('lang.edit_label')); ?></a>
<br>

            <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("<?php echo e(route("frontProductDelete", base64_encode($id))); ?>");'  title="<?php echo e(__('lang.remove_title')); ?>" class="btn btn-black btn-sm login_btn remove-btn-col"><?php echo e(__('lang.remove_title')); ?></a>
            </div>
            </div>
            </div>
            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>
            <div style="text-align: center;margin-top: 50px;margin-bottom: 30px;"><?php echo e(__('lang.datatables.sEmptyTable')); ?></div>
            <?php endif; ?>
            </div>
            </div>

              <?php echo $buyerProducts->links(); ?>

            </div>
            </div>

           
            <div class="col-md-12">
			<div class="row">
            <div class="buyer-prod-msg tijara-info-section">
            <h1 class="buyer-prod-head"><?php echo e(__('messages.Obs_head')); ?></h1>
            <p  class="buyer-prod-content"><?php echo e(__('messages.buyer_product_msg')); ?></p>
            </div>
			</div>
            </div>
          </div>
          </div>
        </div>
      </div>    
    </div>       
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/Products/listBuyerProducts.blade.php ENDPATH**/ ?>

<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  
</div>

<div class="container">
  <!-- Example row of columns -->
  
  <div class="row">
    <div class="">
      <div class="col-md-12">
    <?php if($subscribedError): ?>
      <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
      <?php endif; ?>
    <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     
    <div class="card">
    <div class="card-header row">
    <div class="col-md-10">
        
      <h2><?php echo e(__('lang.your_products_label')); ?></h2>
      <hr class="heading_line"/>
      </div>
      <div class="col-md-1">
      <a href="<?php echo e(route('frontProductCreate')); ?>" title="<?php echo e(__('lang.add_product')); ?>" class="btn btn-black btn-sm debg_color login_btn" ><span><?php echo e(__('lang.add_product')); ?></span> </a>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
          <?php if(!empty($buyerProducts)): ?>
          <?php 
              

           
           
          ?> 
          <?php $__currentLoopData = $buyerProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
          <?php 
              if(!empty($value['image'])) {
                  $imagesParts    =   explode(',',$value['image']); 
                  $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
                }
                else{
                  $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
                }
                  $dated      =   date('Y-m-d',strtotime($value['updated_at']));
                  $title = (!empty($value['title'])) ? substr($value['title'], 0, 50) : '-';
                  $price = $value['price'].' Kr';
                  $id =  $value['id'];

                
          ?> 
            <div class="col-sm-3">
              <div class="card product-card">
              <img class="card-img-top buyer-product-img" src="<?php echo e($image); ?>" >
              <div class="card-body">
                <h5 class="card-title"><?php echo e($dated); ?></h5>
                <p class="card-text buyer-product-title"><?php echo e($title); ?></p>
                 <p class="card-text buyer-price"><?php echo e($price); ?></p>
                 <div class="buyer-button">
                  <a href="<?php echo e(route('frontProductEdit', base64_encode($id))); ?>" class="btn btn-black btn-sm debg_color login_btn" title="<?php echo e(__('lang.edit_label')); ?>"><?php echo e(__('lang.edit_label')); ?></a>
                  

                  <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("<?php echo e(route("frontProductDelete", base64_encode($id))); ?>");'  title="<?php echo e(__('lang.remove_title')); ?>" class="btn btn-black btn-sm login_btn remove-btn-col"><?php echo e(__('lang.remove_title')); ?></a>
                  </div>
              </div>
              </div>
            </div>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          <?php else: ?>
          <div>No product found</div>
          <?php endif; ?>
      </div>

    </div>
    </div>
        
    </div> 
    <div class="col-md-12">
      <div class="buyer-prod-msg">
        <h1 class="buyer-prod-head"><?php echo e(__('messages.Obs_head')); ?></h1>
        <p  class="buyer-prod-content"><?php echo e(__('messages.buyer_product_msg')); ?></p>
      </div>
    </div>
    </div>
  </div>
</div> <!-- /container -->
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>

<script src="<?php echo e(url('/')); ?>/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="<?php echo e(url('/')); ?>/assets/js/sweetalert.js"></script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Products/listBuyerProducts.blade.php ENDPATH**/ ?>
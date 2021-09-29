<li class="col-md-15">
  <div class="product_data services-data" service_link="<?php echo e($service->service_link); ?>" >
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      <?php if($service->images): ?>
        <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/resized/<?php echo e($service->image); ?>" style="width:100%;">
      <?php else: ?>
        <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/no-image.png" style="width:100%;">
      <?php endif; ?>
      <div class="buy_now_hover_details one_icon">
        <ul>
         <?php /*<li><a href="{{$service->service_link}}"><i class="fa fa-search"></i></a></li>*/?>
          <li><a href="javascript:void(0);" <?php if(Auth::guard('user')->id()): ?> onclick="addToWishlistServices('<?php echo e($service->id); ?>');event.stopPropagation();" <?php else: ?> onclick="showErrorMessage('<?php echo e(trans('errors.login_buyer_required')); ?>','<?php echo e(route('frontLogin')); ?>');event.stopPropagation();" <?php endif; ?>><i class="far fa-heart"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="product_info">
      <?php 
        $category_name = $service['category_name'];
        $category_name = str_replace( array( '\'', '"', 
        ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $category_name);
        $category_name = str_replace(" ", '-', $category_name);
        $category_name = strtolower($category_name);
        $service_cat_link= url('/').'/services/'.$category_name; 
      ?>

      <?php if( Request::path() == "/"): ?>
        <a href="<?php echo e($service->service_link); ?>" title="<?php echo e($service->title); ?>"><h4><?php echo substr($service->title, 0, 50) ?></h4></a>

     <div class="star-rating" style="font-size:unset;">
          <select class='rating service_rating' id='rating_<?php echo e($service->id); ?>' data-id='rating_<?php echo e($service->id); ?>' data-rating='<?php echo e($service->rating); ?>'>
          <option value="1" >1</option>
          <option value="2" >2</option>
          <option value="3" >3</option>
          <option value="4" >4</option>
          <option value="5" >5</option>
          </select>
        </div> 

        <?php if(!empty($service->service_price)): ?>
          <h6><?php echo e($service->service_price); ?> kr</h6>
        <?php endif; ?>

        <a href="<?php echo e($service_cat_link); ?>"><h5><?php echo e($service['category_name']); ?></h5></a>
      <?php else: ?>
           <?php if(Request::segment(1) !='service'): ?>
           <a href="<?php echo e($service_cat_link); ?>"><h5><?php echo e($service['category_name']); ?></h5></a>
           <?php endif; ?>
        <a href="<?php echo e($service->service_link); ?>" title="<?php echo e($service->title); ?>"><h4><?php echo substr($service->title, 0, 50) ?></h4></a>
        <?php if(Request::segment(1) !='services' && Request::segment(1) != 'get_service_listing'): ?>
          <div class="star-rating" style="font-size:unset;">
          <select class='rating service_rating' id='rating_<?php echo e($service->id); ?>' data-id='rating_<?php echo e($service->id); ?>' data-rating='<?php echo e($service->rating); ?>'>
          <option value="1" >1</option>
          <option value="2" >2</option>
          <option value="3" >3</option>
          <option value="4" >4</option>
          <option value="5" >5</option>
          </select>
          </div> 
        <?php endif; ?>

        <?php if(!empty($service->service_price)): ?>
          <h6><?php echo e($service->service_price); ?> kr</h6>
        <?php endif; ?>

        <!-- below code is for seller name  -->
        <?php 
          $seller_name = $service->seller;
          $seller_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
          $seller_name = str_replace(" ", '-', $seller_name);
          $seller_name = strtolower($seller_name);

          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($service->user_id)."/services"; 
        ?>

        <?php if(Request::segment(1) !='services' && Request::segment(1) != 'get_service_listing'): ?>
          <a href="<?php echo e($seller_link); ?>"><h6><?php echo e($service->seller); ?></h6></a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</li>
<script type="text/javascript">
  $(".services-data").click(function(){
    var attr_val = $(this).attr('service_link');
    if(attr_val !=''){
      window.location.href = attr_val; 
    }
  });
</script><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/services_widget.blade.php ENDPATH**/ ?>
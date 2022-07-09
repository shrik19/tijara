
<?php
$class = (strpos(@$path, 'annonser') !== false || strpos(@$path, 'seller') !== false || strpos(@$path, 'services') !== false || strpos(@$path, 'products') !== false ) ? 'product_img_wrapper':'col-md-15';

$heartStyle = $iconSize = $paddingleft = '';

if(strpos(@$path, 'seller') != false){
  $heartStyle='left:11px !important';
   $iconSize = 'font-size: 13px !important';
   $paddingleft = "margin-left: 0!important; padding-left: 0!important";
}
if(strpos(@$path, 'services') != false){
            $iconSize = 'font-size: 13px !important';
          }
?>

<li class="<?php echo e($class); ?>">

  <div class="product_data services-data" service_link="<?php echo e($service->service_link); ?>" >
    <div class="product_img" style="display:inline-block;background-color: white;">
      <?php if($service->images): ?>
        <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/resized/<?php echo e($service->image); ?>" style="width:100%;">
      <?php else: ?>
        <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/no-image.png" style="width:100%;">
      <?php endif; ?>
      <div class="buy_now_hover_details one_icon">
      <ul>
         <?php /*<li><a href="{{$service->service_link}}"><i class="fa fa-search"></i></a></li>*/?>
          <li  style="<?php echo e($paddingleft); ?>"><a <?php if(Auth::guard('user')->id()): ?> onclick="addToWishlistServices('<?php echo e($service->id); ?>');event.stopPropagation();" <?php else: ?>  href="<?php echo e(route('frontLogin')); ?>" <?php endif; ?> style="<?php echo e($iconSize); ?>"><i class="far fa-heart"></i></a></li>
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
		
		      $store_name = $service->store_name;
          $store_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $store_name);
          $store_name = str_replace(" ", '-', $store_name);
          $store_name = strtolower($store_name);

          $seller_link= url('/').'/seller/'.$store_name;
		  
          $seller_name = $service->seller;
          $seller_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
          $seller_name = str_replace(" ", '-', $seller_name);
          $seller_name = strtolower($seller_name);

        ?>


      <?php if( Request::path() == "/"): ?>
        <a href="<?php echo e($service->service_link); ?>" title="<?php echo e($service->title); ?>"><h4><?php echo substr($service->title, 0, 50) ?></h4></a>

        <div class="star-rating" style="font-size:15px;">
          <select class='rating service_rating' id='rating_<?php echo e($service->id); ?>' data-id='rating_<?php echo e($service->id); ?>' data-rating='<?php echo e($service->rating); ?>'>
          <option value="1" >1</option>
          <option value="2" >2</option>
          <option value="3" >3</option>
          <option value="4" >4</option>
          <option value="5" >5</option>
          </select>
        </div> 

        <?php if(!empty($service->service_price)): ?>
          <?php             
            $service_price_tbl = swedishCurrencyFormat($service->service_price);
          ?>
          <h6><?php echo e(@$service_price_tbl); ?> kr</h6>
        <?php endif; ?>
          <a href="<?php echo e($seller_link); ?>"><h5><?php echo e($service->store_name); ?></h5></a>

        
      <?php else: ?>
          <!--  <?php if(Request::segment(1) !='service'): ?>
           <a href="<?php echo e($seller_link); ?>"><h5><?php echo e($service->store_name); ?></h5></a>
           <?php endif; ?> -->
        <a href="<?php echo e($service->service_link); ?>" title="<?php echo e($service->title); ?>"><h4><?php echo substr($service->title, 0, 50) ?></h4></a>

          <div class="star-rating" style="font-size:15px;">
          <select class='rating service_rating' id='rating_<?php echo e($service->id); ?>' data-id='rating_<?php echo e($service->id); ?>' data-rating='<?php echo e($service->rating); ?>'>
          <option value="1" >1</option>
          <option value="2" >2</option>
          <option value="3" >3</option>
          <option value="4" >4</option>
          <option value="5" >5</option>
          </select>
          </div> 


        <?php if(!empty($service->service_price)): ?>
           <?php             
            $service_price_tbl_new = swedishCurrencyFormat($service->service_price);
          ?>
          <h6 class="product_price" style="margin-top: 6px;"><?php echo e(@$service_price_tbl_new); ?> kr</h6>
        <?php endif; ?>

        <!-- below code is for seller name  -->

        <a href="<?php echo e($seller_link); ?>"><h5><?php echo e($service->store_name); ?></h5></a>

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
</script><?php /**PATH D:\wamp64\www\tijara\resources\views/Front/services_widget.blade.php ENDPATH**/ ?>
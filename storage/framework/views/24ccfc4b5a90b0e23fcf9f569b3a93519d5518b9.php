
<?php $__env->startSection('middlecontent'); ?>
<style type="text/css">
  .tijara-content {
      margin-bottom: 60px;
    }
</style>
<div class="p_155" style="min-height: 600px;">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <div class="row">
        <div class="col-md-12 tijara-content">
          <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <div class="seller_info border-none">
            <div class="card">
			     	<div class="card-header ml-0 row">							
							<div class="col-md-9 pl-0">
								<h2 class="page_heading"><?php echo e(__('users.my_order_title')); ?></h2>
								<!-- <hr class="heading_line"/> -->
							</div>
							<div  class="col-md-3 new_add text-right p-m-0 pt-4">
								<form id="filter-buyer-order" action="<?php echo e(route('frontAllBuyerOrders')); ?>" method="post">
								<?php echo csrf_field(); ?>
								<?php echo $monthYearHtml;?>
								</form>
							</div>
                        </div>
              </div>
            </div>
              <div class="clearfix"></div>
                <div class="card-body">
                    <div class="card">
                    <div class="card-body"  style="margin-top: 20px;margin-bottom: 60px;">
                    <div class="row">
                    <?php if(!empty($ordersDetails) && count($ordersDetails) > 0): ?>

                    <?php $__currentLoopData = $ordersDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php 


                    if(!empty($value->image)) {
                    $imagesParts    =   explode(',',$value->image); 
                    $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
                    }
                    else{
                    $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
                    }


                    $dated      =   date('Y-m-d',strtotime($value->created_at));

                    $productName = (!empty($value->title)) ? $value->title : '-';
                    // $price = $value['service_price'];
                    $product_price = (!empty($value->price)) ? $value->price * $value->quantity: '-';
                    $storeName = (!empty($value->store_name)) ?$value->store_name : '-';

                   // $seller_name = $value->fname." ".$value->lname;
                     $seller_name = $storeName;

                    $seller_name = str_replace( array( '\'', '"', 
                    ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                    $seller_name = str_replace(" ", '-', $seller_name);
                    $seller_name = strtolower($seller_name);


                    $seller_link= url('/').'/seller/'.$seller_name."/products"; 

                    $product_link = url('/').'/product/'.$value->product_slug.'-P-'.$value->product_code;

                    $id =  $value['id'];
                   
                    ?> 
                    <div class="col-md-15">
                    <div class="card product-card product_data_img product_link_js">

                    <img class="card-img-top buyer-product-img product_img_prd" order_id="<?php echo e(base64_encode($value->order_id)); ?>" src="<?php echo e($image); ?>" product_link="<?php echo e(route('frontShowOrderDetails', base64_encode($value->order_id))); ?>" title="<?php echo e(__('lang.txt_view')); ?>">
                    <div class="card-body product_all">
                    <h5 class="card-title"><?php echo e($dated); ?></h5>
                    <p class="card-text buyer-product-title">
                    <a class="buyer-product-img" href="javascript:void(0)" order_id="<?php echo e(base64_encode($value->order_id)); ?>" title="<?php echo e(__('lang.txt_view')); ?>" style="color: #000 !important;"><?php echo e($productName); ?></a></p>
                    <p class="card-text order-product-price">  
                    <span class="buyer-price" id="product_variant_price">
                      <?php                                 
                        $product_price = swedishCurrencyFormat($product_price);
                      ?>
                    <?php echo e($product_price); ?> kr
                    </span> 
                    </p>
                    <p class="card-text order-product-store-title"> <a href="<?php echo e($seller_link); ?>" style="color: #000 !important"><?php echo e($storeName); ?></a></p>
                    </div>
                    </div>
                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>
                    <div style="text-align: center;margin-top: 50px;margin-bottom: 50px;height: 23vh;"><?php echo e(__('lang.datatables.sEmptyTable')); ?></div>
                    <?php endif; ?>
                    </div>
                    </div>
                  </div>

                  <?php echo $ordersDetails->links(); ?>

                </div>
          </div>
        </div>
      </div>
    </div>


    <!-- order details model -->
    <div class="modal fade" id="orderDetailsmodal">
    <div class="modal-dialog modal-lg" role = "dialog">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title"><?php echo e(__('messages.txt_order_details')); ?></h4>
    <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body-wrapper">
    <div class="modal-body" id="order_details_box">

    </div>
    </div>

    </div>
    </div>
    </div>
  </div>
</div>
<!-- /container -->

<!-- General JS Scripts -->
<script src="<?php echo e(url('/')); ?>/assets/front/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(".buyer-product-img").click(function(){

    var order_id = $(this).attr('order_id');
    $.ajax({
      headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      url: "<?php echo e(url('/')); ?>"+'/order-details/'+order_id,
      type: 'get',
      // async: false,
      data:{},
      success: function(data){
        $('#orderDetailsmodal').modal('show');
        $('#order_details_box').html(data);
      }
    });

  });

  $("#monthYear").on('change', function() {
    this.form.submit();
  });

 
$(".page-link").click(function(){  
  var monthYear = $("#monthYear").val();
 
  if(monthYear != ''){
    $(this).attr('href', function() {
        return this.href + '&monthYear='+monthYear;
    });
  }
    
});

function printDiv() {		
	var product_link= $('.buyer-product-img').attr('product_link')+'?print=1';
	window.open(product_link, '_blank');
}

  function downloadPdf(DownloadLink) {
    if(DownloadLink !=''){
      window.location.href = DownloadLink; 
    } 
  }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/all_buyer_orders.blade.php ENDPATH**/ ?>
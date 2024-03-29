
<?php $__env->startSection('middlecontent'); ?>
<script src="<?php echo e(url('/')); ?>/assets/front/js/zoom-image.js"></script>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/icheck-bootstrap.min.css">

<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>

<section class="product_details_section">
    <div class="loader"></div>
    <div class="container">

        <div class="row">
            <div class="col-md-6">
              <!-- Primary carousel image -->
              <?php if(!empty($variantData)): ?>
                <?php
                $first = reset($variantData);
                ?>
              <?php endif; ?>
              <div class="small-img">
                <!-- <img src="<?php echo e(url('/')); ?>/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img"> -->
                <div class="small-container">
                  <div id="small-img-roll">
                  <?php if(isset($first['images'][0]) && !empty($first['images'][0])): ?>
                    <?php $__currentLoopData = $first['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/productIcons/<?php echo e($image); ?>" class="show-small-img" alt="">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php else: ?>
                   <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png" class="show-small-img">
                  <?php endif; ?>
                  </div>
                </div>
                <!-- <img src="<?php echo e(url('/')); ?>/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img"> -->
              </div>
              <div class="show-custom" href="<?php echo e(url('/')); ?>/uploads/ProductImages/productDetails/<?php echo e($first['images'][0]); ?>">
                <?php if(isset($first['images'][0]) && !empty($first['images'][0])): ?>
                <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/productDetails/<?php echo e($first['images'][0]); ?>" id="show-img ggg">
                <?php else: ?>
                  <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/no-image.png"  id="show-img fg" fg>
                <?php endif; ?>
              </div>
              
              <!-- Secondary carousel image thumbnail gallery -->
             
            </div>

            <div class="col-md-6">
                <div class="product_details_info">
                    <h2><?php echo e($Product->title); ?></h2>
                   <!--  <h4 class="product_price" id="product_variant_price" style="color:#03989e;"><span><?php echo e(__('lang.price_label')); ?> :</span><?php echo e(number_format($first['price'],2)); ?> kr  <?php echo e(number_format($first['discount_price'],2)); ?> kr</h4> -->

                    <div class="quantity_box">              
                      <h3><?php echo e(__('lang.price_label')); ?> : </h3>&nbsp;&nbsp;<span style="padding-top:6px;position:absolute;font-size:20px;" id="product_variant_price"><span style="<?php if(!empty($first['discount_price'])): ?> text-decoration: line-through; <?php endif; ?>"><?php echo e(number_format($first['price'],2)); ?> kr</span> <?php if(!empty($first['discount_price'])): ?> &nbsp;&nbsp;<?php echo e(number_format($first['discount_price'],2)); ?> kr <?php endif; ?> <span><?php if(!empty($Product->discount)): ?> &nbsp;&nbsp;<?php echo "(".$Product->discount."% off)"; ?> <?php endif; ?></span></span> 
                      
                    </div>
                   
                      <p>
                        <?php echo $Product->description; ?>
                      </p>
                      <div class="row">                      
                        <h3><i class="fa fa-phone-alt"></i> <?php echo e(@$buyer_product_details->user_phone_no); ?></h3>
                        <h3><i class="fa fa-envelope"></i> <?php echo e(@$buyer_product_details->user_email); ?></h3>
                      </div>
                        
                      <div class="row">
                          <div class="col-xs-12 col-md-6">    
                            <div class="quantity_box">              
                               <a href="<?php echo e(route('frontHome')); ?>"><?php echo e(__('lang.back_to_ads')); ?></a>
                            </div>
                          </div>
                      </div>
                </div>
            </div> 
        </div>
    </div> <!-- /container -->
</section>


<!-- product review section -->
<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container">

              <h2><?php echo e(__('users.review_title')); ?></h2>
             
                <?php if(!empty($productReviews)): ?>
                  <?php $__currentLoopData = $productReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-md-12">
                    <div class="col-md-1">
                    

                    <?php if(!empty($review['profile'])): ?>
                      <img src="<?php echo e(url('/')); ?>/uploads/Buyer/resized/<?php echo e($review['profile']); ?>" class="ratingUserIcon">
                    <?php else: ?> 
                      <img src="<?php echo e(url('/')); ?>/uploads/Buyer/resized/profile.png"class="ratingUserIcon">
                    <?php endif; ?>
                    </div>

                    <div class="col-md-7" style="margin-left: -20px;">
                      <p class="ratingUname"><?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>

                    <div class="star-rating" style="font-size:unset;pointer-events: none;">
                        <select class='rating product_rating' id='rating_<?php echo e($Product->id); ?>' data-rating="<?php echo e($review['product_rating']); ?>">
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                      </div>
                    <p class="ratingComment"><?php echo e($review['comments']); ?></p>
                  </div>
                  <div class="col-md-4"></div>
                </div><hr class="solid-horizontal-line">
                  
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php echo $productReviews->links(); ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
</section>

<section>
  <?php if(!empty($similarProducts)): ?>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container">
                <h2><?php echo e(__('lang.also_have_watch')); ?></h2>
                
                <ul class="product_details best_seller">

                  <?php $__currentLoopData = $similarProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php echo $__env->make('Front.similar_products_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
                  </div>


</div>
        </div>
    </div>
    <?php endif; ?>
</section>

<script type="text/javascript">
  

//Initialize product gallery

$('.show-custom').zoomImage();

$('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
$('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
$('.show-small-img').click(function () {
  var str =  $(this).attr('src');
  var customImg = str.replace("productIcons", "productDetails");
  $('#show-img').attr('src', customImg);
  $('#big-img').attr('src', customImg);
  $(this).attr('alt', 'now').siblings().removeAttr('alt')
  $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  if ($('#small-img-roll').children().length > 4) {
    if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
    } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

//Enable the next button

$('#next-img').click(function (){
  $('#show-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $(".show-small-img[alt='now']").next().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

//Enable the previous button

$('#prev-img').click(function (){
  $('#show-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $(".show-small-img[alt='now']").prev().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

$("#show-img").next('div').next('div').css('z-index','999');


function showAvailableOptions()
{
  $("#reset_option").show();
  $(".loader").show();

  $.ajax({
    url:siteUrl+"/get-product-options",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'attribute_id': attribute_id, 'attribute_value' : attribute_value, 'product_id' : '<?php echo e($Product->id); ?>'},
    success:function(data)
    {
      var responseObj = $.parseJSON(data);
      var images = responseObj.current_variant.image.split(',');
      $(images).each(function(key,image){
          $(".show-custom").attr('href',siteUrl+'/uploads/ProductImages/productDetails/'+image);
          $(".show-custom").find('img').attr('src',siteUrl+'/uploads/ProductImages/productDetails/'+image);
          return false;
      });
      var allImages = '';
      $(images).each(function(key,image){
        allImages+='<img src="'+siteUrl+'/uploads/ProductImages/productIcons/'+image+'" class="show-small-img" alt="">';
      });

      $("#small-img-roll").html(allImages);
      $('.show-custom').find('div').remove();
      $('.show-custom').zoomImage();
      $('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
      $('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
      $('.show-small-img').click(function () {
        $('#show-img').attr('src', $(this).attr('src'))
        $('#big-img').attr('src', $(this).attr('src'))
        $(this).attr('alt', 'now').siblings().removeAttr('alt')
        $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
        if ($('#small-img-roll').children().length > 4) {
          if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
            $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
          } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
            $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
          } else {
            $('#small-img-roll').css('left', '0')
          }
        }
      });
      $(".show-custom").find('div').not(':first').css('z-index','999');

      $("#product_variant_id").val(responseObj.current_variant.id);
      $("#product_variant_price").html(number_format(responseObj.current_variant.price,2)+'  kr');
      
      var optionLength = responseObj.other_option.length;
      $(responseObj.other_option).each(function(key,option)
      {
        if(option.attribute_type == 'dropdown')
        {
          $("."+option.attribute_id+" option").attr('disabled','disabled');
          $("."+option.attribute_id+" option[value='"+option.attribute_value_id+"']").removeAttr('disabled');

          if(optionLength == 1)
          {
            $("."+option.attribute_id+" option[value='"+option.attribute_value_id+"']").attr('selected','selected');
          }
          
        }
        else if(option.attribute_type == 'radio')
        {
          $(".variant_radio_"+option.attribute_id).each(function(){
              $(this).attr('disabled','disabled').removeAttr('checked');
          });

          $("#"+option.attribute_value_id).removeAttr('disabled');
          if(optionLength == 1)
          {
            $("#"+option.attribute_value_id).prop('checked',true);
          }
        }
      });

      $(".loader").hide();
    }
  });

  function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
}

$(".product_rating").each(function(){
  var currentRating = $(this).data('rating');
  $(this).barrating({
    theme: 'fontawesome-stars',
    initialRating: currentRating,
    onSelect: function(value, text, event) {

   // Get element id by data-id attribute
   var el = this;
   var el_id = el.$elem.data('id');

   // rating was selected by a user
   if (typeof(event) !== 'undefined') {
 
     $.confirm({
        title: '<?php echo e(__('lang.txt_your_comments')); ?>',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label><?php echo e(__('lang.txt_comments')); ?></label>' +
        '<textarea class="name form-control" rows="3" cols="20" placeholder="<?php echo e(__('lang.txt_comments')); ?>" required></textarea>' +
        '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    var comments = this.$content.find('.name').val();
                    if(!comments){
                      showErrorMessage('<?php echo e(__('lang.txt_comments_err')); ?>');
                      return false;
                    }
                    $(".loader").show();
                    $.ajax({
                    url:siteUrl+"/add-review",
                    headers: {
                      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    type: 'post',
                    data : {'rating': value, 'product_id' : '<?php echo e($Product->id); ?>', 'comments' : comments},
                    success:function(data)
                    {
                      $(".loader").hide();
                      var responseObj = $.parseJSON(data);
                      if(responseObj.status == 1)
                      {
                        showSuccessMessage(product_add_success,'reload');
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
                }
            },
            cancel: function () {
                //close
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
   }
  }
 });  
}); 

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/buyer_product_details.blade.php ENDPATH**/ ?>
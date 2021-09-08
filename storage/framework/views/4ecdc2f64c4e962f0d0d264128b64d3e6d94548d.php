
<?php $__env->startSection('middlecontent'); ?>

<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>
    
 <!-- Carousel Default -->
<div class="slider_cotnainer_section">
    <div class="container-fluid">
        <div class="row">
            <section class="carousel-default slider_cotnainer">
                <div id="carousel-default" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators slider_indicators">
                        <?php $__currentLoopData = $SliderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key == 0): ?>
                                <li data-target="#carousel-default" data-slide-to="<?php echo e($key); ?>" class="active"></li>
                            <?php else: ?>
                                <li data-target="#carousel-default" data-slide-to="<?php echo e($key); ?>" class=""></li>
                            <?php endif; ?>  
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                    </ol>

                    <div class="carousel-inner" role="listbox">
                        <!-- NOTE: Bootstrap v4 changes class name to carousel-item -->
                        <?php $__currentLoopData = $SliderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key == 0): ?>
                        <div class="item active slider_item">
                        <?php else: ?>
                        <div class="item slider_item">
                        <?php endif; ?>
                        <div >
                            <img class="img-fluid" src="<?php echo e(url('/')); ?>/uploads/Slider/<?php echo $data['sliderImage']; ?>" 
                            id="sliderImages" alt="First slide">

                            <div class="slider_content">
                            <!-- <h3><?php echo e($data['title']); ?></h3> -->
                                <?php echo $data['description']; ?>
                                <button type="submit" class=" btn slider_buy_btn debg_color" onclick="document.location='<?php echo e($data['link']); ?>'" ><?php echo e(__('lang.browse_now_btn')); ?></button>  
                            </div>
                        </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
 <!-- end slider section -->

<section class="product_section">
    
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <!--  <div class="col-md-3">
            <?php echo $__env->make('Front.products_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>-->
        <div class="product_view">
        <div class="col-md-12">             
                <div class="product_container">
                <div class="loader"></div>
                    <!-- <h4><?php echo e(__('lang.popular_items_in_market_head')); ?></h4> -->
             
                     <div>
                        <h2 class="heading"><?php echo e(__('lang.popular_product_head')); ?></h2> 
                            <!-- <a href="<?php echo e(url('/')); ?>/products" class="btn see-all-service-btn debg_color login_btn"><?php echo e(__('users.see_all_products')); ?></a> -->
                    </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details">
					<?php $__currentLoopData = $TrendingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('Front.products_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                       </ul>
                </div>
        </div>
    </div>
    </div>

      
    </div> <!-- /container -->  
</section>
<!-- Featured seller section start -->
 
<section class="featured-seller product_view">
    <div class="featured_seller_container">
    <div class="container-fluid">
        <h2 class="heading"><?php echo e(__('lang.featured_seller_head')); ?></h2>
    </div>
</div>
    <div class="featured-banner" >
    <div class="featured_seller_container">
    <div class="container-fluid">
        <div class="row ">               
                <?php if(!empty($FeaturedSellers)): ?>
                    <?php $__currentLoopData = $FeaturedSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fea_seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 feature_seller">
                        <div class="featured_seller_section"  >
                            <img class="img-fluid" src="<?php echo e(url('/')); ?>/uploads/Seller/resized/<?php echo $fea_seller['logo'];?>" />
                            <div class="clearfix"></div>
                           
                        </div>
                        <?php
                         $seller_name = $fea_seller->fname." ".$fea_seller->lname;
              
                          $seller_name = str_replace( array( '\'', '"', 
                          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                          $seller_name = str_replace(" ", '-', $seller_name);
                          $seller_name = strtolower($seller_name);
                                      
                          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($fea_seller->id)."/products"; 
                            
                        ?>
                        <h3><a href="<?php echo e($seller_link); ?>" title="<?php echo e($fea_seller['store_name']); ?>" style="color: #000 !important;"><?php echo e($fea_seller['store_name']); ?></a></h3><!-- 
                        <h3><?php echo e($fea_seller['store_name']); ?></h3> -->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
        </div>
    </div>
</div>
</div>
</section>
<!-- end Featured seller section start -->

<!-- popular services section start -->
<section class="product_section">
    
    <div class="container-fluid">
      <!-- Example row of columns -->

      <div class="row">
       <div class="product_view">
        <div class="col-md-12">             
                <div class="product_container">
                <div class="loader"></div>
                    <div >
                    <h2 class="heading"><?php echo e(__('lang.popular_services_head')); ?></h2>     
                    <!-- <a href="<?php echo e(url('/')); ?>/services" class="btn see-all-service-btn debg_color login_btn"><?php echo e(__('users.see_all_services')); ?></a> -->
                </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details">
                    <?php $__currentLoopData = $PopularServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('Front.services_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
        </div>
        </div>
    </div>

      
    </div> <!-- /container -->  
</section>
<!-- popular services section end -->

<!-- featured products -->

<!-- popular services section start -->
<section class="product_section">
    <div class="container-fluid">
      <!-- Example row of columns -->
    <div class="row">
    <div class="product_view">
    <div class="col-md-12">             
        <div class="product_container">
        <div class="loader"></div>
            <h2 class="heading"><?php echo e(__('lang.feature_product_head')); ?></h2>
            <!-- <hr class="heading_line"/> -->
            <ul class="product_details">
            <?php $__currentLoopData = $FeaturedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('Front.featured_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
</div>
    </div>
    </div>
    </div> <!-- /container -->  
</section>
<!-- featured services section end -->
<!-- banner section -->
<!-- <section class="sale_section">
    <div class="container-fluid">
        <div class="row">
            <div class="container-inner-section">
            <div class="col-md-6">
                <div class="sale_details">
                    <h2><?php if(!empty($banner->subtitle)): ?><?php echo e($banner->subtitle); ?><?php endif; ?></h2>
                    <h3><?php if(!empty($banner->title)): ?><?php echo e($banner->title); ?><?php endif; ?></h3>
                    <p><?php echo $data['description']; ?></p>
                    <button type="button" class="btn sale_btn" onclick="document.location='<?php echo e($banner['redirect_link']); ?>'"><?php echo e(__('lang.shop_now_btn')); ?></button>
                </div>
            </div>
            <?php if(!empty($banner->image)): ?>
            <div class="col-md-6">
              <img class="img-fluid mid_banner" src="<?php echo e(url('/')); ?>/uploads/Banner/<?php echo e($banner->image); ?>" style="height: 412px"/>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</section> -->
<!-- end banner section -->

<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
           <?php /*<div class="best_seller_container">
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.best_seller_head')}}</h2>
                <ul class="product_details best_seller">
					@foreach($PopularProducts as $product)
                    @include('Front.products_widget')
					@endforeach
				 </ul>
            </div>
			*/?>
			<div class="tijara_front_container">
				<ul class="tijara_front_box row">
					<li class="colr-1">
						<div class="tijara_front_inner ">
                        <img src="<?php echo e(url('/')); ?>/assets/img/icon1.png"  />
                        <h4>Unik<br/>
                            marknadsplats</h4>
						</div>
					</li>
					<li class="colr-2">
						<div class="tijara_front_inner">
                        <img src="<?php echo e(url('/')); ?>/assets/img/icon2.png"/>
                        <h4>Främjar<br/>
                        entreprenörskap</h4>
						</div>
					</li>
					<li class="colr-3">
						<div class="tijara_front_inner">
                        <img src="<?php echo e(url('/')); ?>/assets/img/icon3.png"/>
                        <h4>Uppmuntrar till<br/>
                            lokal handel</h4>
						</div>
					</li>                       
				</ul>
				<div class="tijara_front_read_more">
					<a href="<?php echo e(url('/')); ?>/page/om-oss" class="btn debg_color login_btn"><?php echo e(__('users.read_more_btn')); ?></a>
				</div>
            </div>			
			
            <div class="best_seller_container">
                <!--<h3><?php echo e(__('lang.follow_us_on_head')); ?></h3>-->
                <h2><?php echo e(__('lang.instagram_label')); ?></h2>
                <div class="social_img_section">
                    <ul class="instagram_imgs" id="instafeed">

                    </ul>
                </div>
            </div>

        </div>
        </div>
    </div>
</section>

<!-- <section class="artical_section">
    <div class="container-fluid">
    <div class="container-inner-section"> 

        <div class="row">
            <div class="best_seller_container">
                <h3><?php echo e(__('lang.popular_items_in_market_head')); ?></h3>
                <h2><?php echo e(__('lang.who_we_are_head')); ?></h2>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="<?php echo e(url('/')); ?>/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more"><?php echo e(__('lang.learn_more_btn')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="<?php echo e(url('/')); ?>/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more"><?php echo e(__('lang.learn_more_btn')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="<?php echo e(url('/')); ?>/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more"><?php echo e(__('lang.learn_more_btn')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


</section> -->

<script type="text/javascript">

  $(".product_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            <?php if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1): ?>
            // Get element id by data-id attribute
            var el = this;
            var el_id = el.$elem.data('id');
            
            // rating was selected by a user
            if (typeof(event) !== 'undefined') {
              
              var split_id = el_id.split("_");
              var product_id = split_id[1]; // postid

              $.confirm({
                 title: txt_your_comments,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Submit',
                         btnClass: 'btn-blue',
                         action: function () {
                             var comments = this.$content.find('.name').val();
                             if(!comments){
                               showErrorMessage(txt_comments_err);
                               return false;
                             }
                             $(".loader").show();
                             $.ajax({
                             url:siteUrl+"/add-review",
                             headers: {
                               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                             },
                             type: 'post',
                             data : {'rating': value, 'product_id' : product_id, 'comments' : comments},
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
            <?php else: ?>
                showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
            <?php endif; ?>
           }
          
         });
      });


/*service rating*/
$(".service_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            <?php if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1): ?>
            // Get element id by data-id attribute
            var el = this;
            var el_id = el.$elem.data('id');
            
            // rating was selected by a user
            if (typeof(event) !== 'undefined') {
              
              var split_id = el_id.split("_");
              var service_id = split_id[1]; // postid

              $.confirm({
                 title: txt_your_comments,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Submit',
                         btnClass: 'btn-blue',
                         action: function () {
                             var comments = this.$content.find('.name').val();
                             if(!comments){
                               showErrorMessage(txt_comments_err);
                               return false;
                             }
                             $(".loader").show();
                             $.ajax({
                             url:siteUrl+"/add-service-review",
                             headers: {
                               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                             },
                             type: 'post',
                             data : {'rating': value, 'service_id' : service_id, 'comments' : comments},
                             success:function(data)
                             {
                               $(".loader").hide();
                               var responseObj = $.parseJSON(data);
                               if(responseObj.status == 1)
                               {
                                 showSuccessMessage(review_add_success,'reload');
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
            <?php else: ?>
                showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
            <?php endif; ?>
           }
          
         });
      });
</script>

<script src="<?php echo e(url('/')); ?>/assets/front/js/instafeed/dist/instafeed.min.js"></script>

<script type="text/javascript">
    var access_token = "<?php echo env('INSTA_ACCESS_TOKEN') ?>";
    var feed = new Instafeed({
      accessToken: access_token
    });
    feed.run();


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/home.blade.php ENDPATH**/ ?>
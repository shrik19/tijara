
<?php $__env->startSection('middlecontent'); ?>

<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/main.css">
<hr class="categoryGrayLine">
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

                    <div class="carousel-inner home-slider" role="listbox">
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
							<div class="product_view">
								<div class="slider_content">
									<?php /* <h3>{{$data['title']}}</h3>*/?>
									<?php echo $data['description']; ?>
									<button type="submit" class=" btn slider_buy_btn debg_color" onclick="document.location='<?php echo e($data['link']); ?>'" ><?php echo e(__('lang.browse_now_btn')); ?></button>  
								</div>
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
    </div>
    </div>
 <!-- end slider section -->

<section class="pt-0">
    
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <!--  <div class="col-md-3">
            <?php echo $__env->make('Front.products_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>-->
        <div class="product_view">
        <div class="col-md-12 pl-w-0 pr-w-0">             
                <div class="product_container product_container-list-5">
                <div class="loader"></div>
                    <!-- <h4><?php echo e(__('lang.popular_items_in_market_head')); ?></h4> -->
             
                     <div>
                        <h2 class="heading product_heading"><?php echo e(__('lang.popular_product_head')); ?></h2> 
                            <!-- <a href="<?php echo e(url('/')); ?>/products" class="btn see-all-service-btn debg_color login_btn"><?php echo e(__('users.see_all_products')); ?></a> -->
                    </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details pl-0">
					<?php $__currentLoopData = $PopularProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
 
<section class="featured-seller">
    <div class="featured_seller_container">
    <div class="container-fluid">
        <h2 class="heading product_heading"><?php echo e(__('lang.featured_seller_head')); ?></h2>
    </div>
</div>
    <div class="featured-banner" style="margin-left: 10px;">
    <div class="featured_seller_container ">
    <div class="container-fluid">
        <div class="row logo-slider">               
                <?php if(!empty($FeaturedSellers)): ?>
                    <?php $__currentLoopData = $FeaturedSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fea_seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="feature_seller">
                        <div class="featured_seller_section"  >
                            <img class="img-fluid" src="<?php echo e(url('/')); ?>/uploads/Seller/resized/<?php echo $fea_seller['logo'];?>" />
                            <div class="clearfix"></div>
                           
                        </div>
                        <?php
                         //$seller_name = $fea_seller->fname." ".$fea_seller->lname;
						 
						 $seller_name = $fea_seller->store_name;
              
                          $seller_name = str_replace( array( '\'', '"', 
                          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                          $seller_name = str_replace(" ", '-', $seller_name);
                          $seller_name = strtolower($seller_name);
                                      
                          $seller_link= url('/').'/seller/'.$seller_name; 
                            
                        ?>
                        <h3><a href="<?php echo e($seller_link); ?>" title="<?php echo e($fea_seller['store_name']); ?>" class="Featured_shop_heading"><?php echo e($fea_seller['store_name']); ?></a></h3><!-- 
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
        <div class="col-md-12 pl-w-0 pr-w-0">             
                <div class="product_container product_container-list-5">
                <div class="loader"></div>
                    <div >
                    <h2 class="heading product_heading"><?php echo e(__('lang.popular_services_head')); ?></h2>     
                    <!-- <a href="<?php echo e(url('/')); ?>/services" class="btn see-all-service-btn debg_color login_btn"><?php echo e(__('users.see_all_services')); ?></a> -->
                </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details pl-0">
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
    <div class="col-md-12 pl-w-0 pr-w-0">             
        <div class="product_container product_container-list-5">
        <div class="loader"></div>
            <div style="display: flex;">
            <h2 class="heading product_heading"><?php echo e(__('lang.feature_product_head')); ?></h2>
            <a href="<?php echo e(url('/')); ?>/annonser" title="<?php echo e(__('users.go_to_announse_page')); ?>" class="btn btn-black btn-sm  login_btn go_to_tijara_ads_btn"><?php echo e(__('users.go_to_announse_page')); ?></a>
          </div>
            <!-- <hr class="heading_line"/> -->
            <ul class="product_details pl-0">
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
<?php /* <section class="sale_section">
    <div class="container-fluid">
        <div class="row">
            <div class="container-inner-section">
            <div class="col-md-6">
                <div class="sale_details">
                    <h2>@if(!empty($banner->subtitle)){{$banner->subtitle}}@endif</h2>
                    <h3>@if(!empty($banner->title)){{$banner->title}}@endif</h3>
                    <p><?php echo $data['description']; ?></p>
                    <button type="button" class="btn sale_btn" onclick="document.location='{{$banner['redirect_link']}}'">{{ __('lang.shop_now_btn')}}</button>
                </div>
            </div>
            @if(!empty($banner->image))
            <div class="col-md-6">
              <img class="img-fluid mid_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" style="height: 412px"/>
            </div>
            @endif
        </div>
        </div>
    </div>
</section>  */?>
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
				<ul class="tijara_front_box">
					<li >
            <div class="colr-box colr-1 text-center">
              <div class="tijara_front_inner ">
              <img src="<?php echo e(url('/')); ?>/assets/img/icon1.png"  />
              <h4><?php echo e(__('users.Unik_title')); ?><br/>
              <?php echo e(__('users.marketplace_title')); ?></h4>
              </div>
            </div>
              <h3><?php echo e(__('users.unique_marketplace_info')); ?></h3>
					</li>
					<li>
            <div class="colr-box colr-2 text-center">
              <div class="tijara_front_inner">
              <img src="<?php echo e(url('/')); ?>/assets/img/icon2.png"/>
              <h4><?php echo e(__('users.promote_title')); ?><br/>
              <?php echo e(__('users.entrepreneurship_title')); ?></h4>
              </div>
            </div>
              <h3><?php echo e(__('users.entrepreneurship_info')); ?></h3>
					</li>
					<li>
                    <div class="colr-box colr-3 text-center">
						<div class="tijara_front_inner">
                        <img src="<?php echo e(url('/')); ?>/assets/img/icon3.png"/>
                        <h4><?php echo e(__('users.encourages_to_title')); ?><br/>
                            <?php echo e(__('users.local_trade_title')); ?></h4>
						</div>
                    </div>
                    <h3><?php echo e(__('users.encourages_local_trade_info')); ?></h3>
					</li>                       
				</ul>
				<div class="tijara_front_read_more">
					<a href="<?php echo e(url('/')); ?>/page/om-oss" class="btn debg_color login_btn"><?php echo e(__('users.read_more_btn')); ?></a>
				</div>
            </div>			
			
            <div class="col-md-12 pl-w-0 pr-w-0">
				<div class="best_seller_container">
					<!--<h3><?php echo e(__('lang.follow_us_on_head')); ?></h3>-->
					<h2 class="product_heading instagram_heading" style="
    margin-left: 25px !important;"><?php echo e(__('lang.instagram_label')); ?></h2>
					<div class="social_img_section insta_social_images">
						<ul class="instagram_imgs" id="instafeed">

						</ul>
					</div>
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
                 title: txt_your_review,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Skicka', //submit
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
                                 showSuccessMessageReview(review_add_msg,'reload');
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
                     cancel: {
                        text: 'Avbryt', //cancel 
                        action: function () {
                         //close
                        }
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
              window.location.href = "<?php echo e(route('frontLogin')); ?>"; 
               // showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
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
                 title: txt_your_review,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Skicka', //submit
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
                                 showSuccessMessageReview(review_add_msg,'reload');
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
                     cancel: {
                        text: 'Avbryt', //cancel 
                        action: function () {
                         //close
                        }
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
                //showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
                window.location.href = "<?php echo e(route('frontLogin')); ?>"; 
            <?php endif; ?>
           }
          
         });
      });
</script>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/slick-theme.min.css">
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/slick.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/slick.min.js"></script>
<script type="text/javascript">
  
</script>
<script src="<?php echo e(url('/')); ?>/assets/front/js/instafeed/dist/instafeed.min.js"></script>

<script type="text/javascript">
    var access_token = "<?php echo env('INSTA_ACCESS_TOKEN') ?>";
    var feed = new Instafeed({
      accessToken: access_token
    });
    feed.run();

    $('.logo-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    autoplay: false,
    autoplaySpeed: 2000,
    infinite: true,
    // prevArrow:"<button type='button' class='slick-prev pull-left'>left</button>",
    //         nextArrow:"<button type='button' class='slick-next pull-right'>Right</button>",
    responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            adaptiveHeight: true,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
  });

  $('.left').click(function(){
  $('.slider').slick('slickPrev');
})

$('.right').click(function(){
  $('.slider').slick('slickNext');
})
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\tijara\resources\views/Front/home.blade.php ENDPATH**/ ?>
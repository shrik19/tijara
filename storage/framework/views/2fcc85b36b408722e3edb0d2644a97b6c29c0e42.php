<!-- <div class="ft_top_container container-fluid">
        <div class="container-inner-section">
        <ul class="client_logos">
          <li><img src="<?php echo e(url('/')); ?>/assets/front/img/client_logo1.png"/></li>
          <li><img src="<?php echo e(url('/')); ?>/assets/front/img/client_logo2.png"/></li>
          <li><img src="<?php echo e(url('/')); ?>/assets/front/img/client_logo3.png"/></li>
          <li><img src="<?php echo e(url('/')); ?>/assets/front/img/client_logo4.png"/></li>
          <li><img src="<?php echo e(url('/')); ?>/assets/front/img/client_logo5.png"/></li>
        </ul>
      </div>
    </div> -->
    <div class="ft_middle_container container-fluid">
      <div class="container-inner-section">
        <div class="col-md-3">
            <img class="footer_logo"  src="<?php echo e(url('/')); ?>/assets/img/logo.png"     height="50px" />
            <!-- <article class="address_container">
              <p><?php echo $siteDetails->footer_address; ?>			 
              </p>
            </article> -->
            <ul class="social_icon">
              <li><a href="#"><img src="<?php echo e(url('/')); ?>/assets/img/facebook_1.png"/></a></li>
              <!-- <li><a href="#"><img src="<?php echo e(url('/')); ?>/assets/front/img/tw_icon.png"/></a></li> -->
              <li><a href="#"><img src="<?php echo e(url('/')); ?>/assets/img/instagram_1.png"/></a></li>
              <li><a href="#"><img src="<?php echo e(url('/')); ?>/assets/img/link_1.png"/></a></li>
            </ul>
        </div>
        <?php
          $allPages = getCustomPages();
        ?>
		<div class="col-md-2">
			<div class="ft_page_links">
				<h3><?php echo e(__('users.footer_sell_label')); ?></h3>
				<ul>
					<li><a href="#"><?php echo e(__('users.footer_how_it_work_link')); ?></a></li>
					<li><a href="#"><?php echo e(__('users.footer_open_a_shop_link')); ?></a></li>
					<li><a href="#"><?php echo e(__('users.footer_my_pages_link')); ?></a></li>
				</ul>
			</div>
		</div>
        <div class="col-md-2">
          <div class="ft_page_links">
          <h3><?php echo e(__('lang.information_label')); ?></h3>
          <?php if(!empty($allPages)): ?>
          <ul>
            <?php $__currentLoopData = $allPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                  $pageName = '';
                  $pageUrl  = '';
                  if(Config::get('app.locale') == 'se')
                  {
                    $pageName = $page['title'];
                    $pageUrl  = route('frontCmsPage', array($page['slug']));
                  }
                  else
                  {
                    $pageName = $page['title_en'];
                    $pageUrl  = route('frontCmsPage', array($page['slug_en']));
                  }
              ?>
              <li><a href="<?php echo e($pageUrl); ?>"><?php echo e($pageName); ?> </a></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!-- <li><a href=""><?php echo e(__('lang.about_us_label')); ?> </a></li>
            <li><a href=""><?php echo e(__('lang.checkout_label')); ?></a></li>
            <li><a href=""><?php echo e(__('lang.contact_label')); ?></a></li>
            <li><a href=""><?php echo e(__('lang.service_label')); ?></a></li> -->
          </ul>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-2">
        <div class="ft_page_links">
        <h3><?php echo e(__('lang.my_account_title')); ?></h3>
        <ul>
          <li><a href="<?php echo e(route('frontUserProfile')); ?>"><?php echo e(__('lang.my_account_title')); ?></a></li>
          <li><a href="javascript:void(0)"><?php echo e(__('lang.contact_label')); ?> </a></li>
          <li><a href="<?php echo e(route('AllproductListing')); ?>"><?php echo e(__('lang.shopping_cart_label')); ?> </a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-3">
      <div class="ft_page_links">
        <h3><?php echo e(__('lang.join_our_newsletter_now_label')); ?></h3>
        <p><?php echo e(__('lang.get_e-mail_updates_label')); ?></p>
          <div class="sub_box">
            <div class="footer-loader"></div>
            <input type="text" id="usersSubscribed" placeholder="<?php echo e(__('lang.enter_mail_placeholder')); ?> "/>
            <button type="button" class="btn sub_btn subscribed_users"><?php echo e(__('lang.subscribe_label')); ?></button>
            <span class="subscribe_msg"></span>
         <!--    <span class="subscribe_em"></span>
            <span class="subscribe_success"></span> -->
          </div>
      </div>
    </div>
      </div>

    </div>
    <div class="ft_payment_container container-fluid">
        <div class="container-inner-section">
          <img class="img-fluid" src="<?php echo e(url('/')); ?>/assets/front/img/tijara_front/footer_payment_option.png">
        </div>
    </div>
    <div class="clearfix"></div>
      <div class="ft_copyright">
        <p>© <?php echo e(__('lang.copyright_label')); ?> <?php echo e(date('Y')); ?> <span class="de_col"><?php echo e(config('constants.PROJECT_NAME')); ?></span>. | <?php echo e(__('lang.all_right_reserved_label')); ?></p>
      </div>
    </footer>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
	<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/richtext.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript" src="<?php echo e(url('/')); ?>/assets/front/js/select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo e(url('/')); ?>/assets/front/css/select2.css">
<script>
  var siteUrl = "<?php echo e(url('/')); ?>";
  $(document).ready(function() {
    if($('#description').length>0)
    $('#description').richText();
  });
if($('#categories').length>0) {
  $('#categories').select2({
		placeholder:"select"
		});
}

var select_attribute_value="<?php echo e(__('lang.select_label')); ?>  <?php echo e(__('lang.attribute_value_label')); ?>";
var required_field_error="<?php echo e(__('lang.required_field_error')); ?>";
var attribute_saved="<?php echo e(__('lang.attribute_saved')); ?>";
var fill_in_email_err="<?php echo e(__('errors.fill_in_email_err')); ?>";
var fill_in_valid_email_err="<?php echo e(__('errors.invalid_email_err')); ?>";
var fill_in_password_err="<?php echo e(__('errors.fill_in_password_err')); ?>";
var fill_in_confirm_password_err="<?php echo e(__('errors.fill_in_confirm_password_err')); ?>";
var fill_in_first_name_err="<?php echo e(__('errors.fill_in_first_name_err')); ?>";
var fill_in_last_name_err="<?php echo e(__('errors.fill_in_last_name_err')); ?>";
var fill_in_phone_number_err="<?php echo e(__('errors.fill_in_phone_number_err')); ?>";
var fill_in_address_err="<?php echo e(__('errors.fill_in_address_err')); ?>";
var fill_in_postal_code_err="<?php echo e(__('errors.fill_in_postal_code_err')); ?>";
var password_min_6_char="<?php echo e(__('errors.password_min_6_char')); ?>";
var password_not_matched="<?php echo e(__('errors.password_not_matched')); ?>";
var are_you_sure_message="<?php echo e(__('messages.are_you_sure_message')); ?>";
var yes_delete_it_message="<?php echo e(__('messages.yes_delete_it_message')); ?>";
var no_cancel_message="<?php echo e(__('messages.no_cancel_message')); ?>";
var alert_delete_record_message="<?php echo e(__('messages.alert_delete_record_message')); ?>";
var invalid_files_err="<?php echo e(__('errors.invalid_files_err')); ?>";
var max_files_restriction_seller="<?php echo e(__('users.max_images_restriction_seller')); ?>";
var input_alphabet_err = "<?php echo e(__('errors.input_alphabet_err')); ?>";
var service_fav_succ = "<?php echo e(__('messages.service_wishlist_add_success')); ?>";
var wishlist_service_exists = "<?php echo e(__('messages.wishlist_service_exists')); ?>";
var wishlist_product_exists = "<?php echo e(__('messages.wishlist_product_exists')); ?>";
var store_name_is_verified = "<?php echo e(__('users.store_name_is_verified')); ?>";
var select_package_to_subscribe = "<?php echo e(__('errors.select_package_to_subscribe')); ?>";
var please_check_privacy_policy = "<?php echo e(__('errors.please_check_privacy_policy')); ?>";
var please_enter_store_name = "<?php echo e(__('errors.please_enter_store_name')); ?>";
var valid_email_err = "<?php echo e(__('errors.valid_email_err')); ?>";
var read_more_btn = "<?php echo e(__('users.read_more_btn')); ?>";
var read_less_btn = "<?php echo e(__('users.read_less_btn')); ?>";
var wait_while_upload = "<?php echo e(__('errors.wait_for_image_uplaod')); ?>";
var please_uplaod_service_image = "<?php echo e(__('errors.please_uplaod_service_image')); ?>";
</script>
        <!-- <script>window.jQuery || document.write('<script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/jquery-1.11.2.min.js"><\/script>')</script> -->

        <!-- <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/bootstrap.min.js"></script> -->

        <script src="<?php echo e(url('/')); ?>/assets/front/js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>

            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
        
        <?php
        $showDatetimepicker = 0;
              $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
              if (strpos($url,'-S-') !== false) 
                $showDatetimepicker = 1;
             
              ?>
        <?php if($showDatetimepicker==1): ?>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
       
      
        <script type="text/javascript">//<![CDATA[


        $(function () {
                      $('#datetimepicker1').datetimepicker({
                        format : 'DD-MM-YYYY hh:mm A' ,
                        useCurrent: false,
                        showTodayButton: true,
                        showClear: true,
                        toolbarPlacement: 'bottom',
                        sideBySide: true,
                        minDate:new Date(),
                        icons: {
                            time: "fa fa-clock-o",
                            date: "fa fa-calendar",
                            up: "fa fa-arrow-up",
                            down: "fa fa-arrow-down",
                            previous: "fa fa-chevron-left",
                            next: "fa fa-chevron-right",
                            today: "fa fa-clock-o",
                            clear: "fa fa-trash-o"
                        }
                    })
                  });


        //]]></script>
      <?php endif; ?>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/layout/footer.blade.php ENDPATH**/ ?>
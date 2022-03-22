<!-- <div class="ft_top_container container-fluid">
        <div class="container-inner-section">
        <ul class="client_logos">
          <li><img src="{{url('/')}}/assets/front/img/client_logo1.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo2.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo3.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo4.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo5.png"/></li>
        </ul>
      </div>
    </div> -->
    <div class="ft_middle_container container-fluid">
      <div class="container-inner-section">
        <div class="row" style="padding-left:8px;">
        <div class="col-md-3">
          
          @if(!empty($siteDetails->footer_logo))
            <img class="footer_logo"  src="{{url('/')}}/uploads/Images/{{$siteDetails->footer_logo}}"     height="50px" />
            @endif
            <!--  <img class="footer_logo"  src="{{url('/')}}/assets/img/logo.png"     height="50px" /> -->
            <!-- <article class="address_container">
              <p>{!!$siteDetails->footer_address!!}			 
              </p>
            </article> -->
            <ul class="social_icon">
              <li><a href="{{env('FACEBOOK_LINK')}}"><img src="{{url('/')}}/assets/img/facebook_1.png"/></a></li>
              <!-- <li><a href="#"><img src="{{url('/')}}/assets/front/img/tw_icon.png"/></a></li> -->
              <li><a href="{{env('INSTAGRAM_LINK')}}"><img src="{{url('/')}}/assets/img/instagram_1.png"/></a></li>
              <li><a href="{{env('LINKDIN_LINK')}}"><img src="{{url('/')}}/assets/img/link_1.png"/></a></li>
            </ul>
        </div>
        @php
          $allPages   = getCustomPages();
          $getCustomerServicePage = getCustomerServicePage();
          $getHowToSellPage = getHowToSellPage();
        //  echo "<pre>";print_r($getHowToSellPage);exit;
        @endphp
		<div class="col-md-2">
			<div class="ft_page_links">
				<h3>{{ __('users.footer_sell_label')}}</h3>
				<ul>
           @if(!empty($getHowToSellPage))
              @php
                  $howToSellPage = '';
                  $howToSellUrl  = '';
                  if(Config::get('app.locale') == 'se')
                  {
                    $howToSellPage = $getHowToSellPage[0]['title'];
                    $howToSellUrl  = route('frontCmsPage', array($getHowToSellPage[0]['slug']));
                  }
                  else
                  {
                    $howToSellPage = $getHowToSellPage[0]['title_en'];
                    $howToSellUrl  = route('frontCmsPage', array($getHowToSellPage[0]['slug_en']));
                  }
              @endphp
              <li><a href="{{ $howToSellUrl }}">{{ $howToSellPage }} </a></li>
           @endif
					<li><a  href="{{route('seller_register')}}" >{{ __('users.footer_open_a_shop_link')}}</a></li>
					<li><a style="cursor: pointer;" @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==2) href="{{route('frontDashboard')}}" @else href="{{ route('frontLoginSeller')}}"  @endif>{{ __('users.footer_my_pages_link')}}</a></li>

				</ul>
			</div>
		</div>
        <div class="col-md-2">
          <div class="ft_page_links">
          <h3>{{ __('lang.about_tijara_label')}}</h3>
          @if(!empty($allPages))
          <ul>
            @foreach($allPages as $page)
              @php
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
              @endphp
              <li><a href="{{ $pageUrl }}">{{ $pageName }} </a></li>
            @endforeach
            <!-- <li><a href="">{{ __('lang.about_us_label')}} </a></li>
            <li><a href="">{{ __('lang.checkout_label')}}</a></li>
            <li><a href="">{{ __('lang.contact_label')}}</a></li>
            <li><a href="">{{ __('lang.service_label')}}</a></li> -->
          </ul>
          @endif
        </div>
      </div>
      <div class="col-md-2">
        <div class="ft_page_links">
        <h3>{{ __('lang.customer_service_label')}}</h3>
        <ul>
         <!--  <li><a href="{{route('frontUserProfile')}}">{{ __('lang.my_account_title')}}</a></li> 
          <li><a href="javascript:void(0)">{{ __('lang.contact_us_label')}} </a></li>-->
        
          @if(!empty($getCustomerServicePage))
             @foreach($getCustomerServicePage as $CustomerServicePage)
            @php
                $pageFaq = '';
                $faqUrl  = '';
                if(Config::get('app.locale') == 'se')
                {
                  $pageFaq = $CustomerServicePage['title'];
                  $faqUrl  = route('frontCmsPage', array($CustomerServicePage['slug']));
                }
                else
                {
                  $pageFaq = $CustomerServicePage['title_en'];
                  $faqUrl  = route('frontCmsPage', array($CustomerServicePage['slug_en']));
                }
            @endphp
            <li><a href="{{ $faqUrl }}">{{ $pageFaq }} </a></li>
            @endforeach
          @endif
         <!--  <li><a href="{{route('AllproductListing')}}">{{ __('lang.shopping_cart_label')}} </a></li> -->
        </ul>
      </div>
    </div>
    <div class="col-md-3">
      <div class="ft_page_links">
        <h3>{{ __('lang.join_our_newsletter_now_label')}}</h3>
        <p>{{ __('lang.get_e-mail_updates_label')}}</p>
          <div class="sub_box">
            <div class="footer-loader"></div>
            <input type="text" id="usersSubscribed" placeholder="{{ __('lang.enter_mail_placeholder')}} "/>
            <button type="button" class="btn sub_btn subscribed_users">{{ __('lang.subscribe_label')}}</button>
            <span class="subscribe_msg"></span>
         <!--    <span class="subscribe_em"></span>
            <span class="subscribe_success"></span> -->
          </div>
      </div>
    </div>
      </div>
      </div>
    </div>
    <div class="ft_payment_container container-fluid">
        <div class="container-inner-section">
          <img class="img-fluid" src="{{url('/')}}/assets/front/img/tijara_front/footer_payment_option.png" style="padding-left: 10px;">
        </div>
    </div>
    <div class="clearfix"></div>
      <div class="ft_copyright">
        <?php /*<p>© {{ __('lang.copyright_label')}} {{date('Y')}} <span class="de_col">{{config('constants.PROJECT_NAME')}}</span>. | {{ __('lang.all_right_reserved_label')}}</p> */?>
        <p>{{$siteDetails->copyright_content}}  </p>
      </div>
    </footer>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
	<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript" src="{{url('/')}}/assets/front/js/select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/front/css/select2.css">
<script>
  var siteUrl = "{{url('/')}}";
  $(document).ready(function() {
    if($('#description').length>0)
    $('#description').richText();
  });
if($('#categories').length>0) {
  $('#categories').select2({
		placeholder:"select"
		});
}

var select_attribute_value="{{ __('lang.select_label')}}  {{ __('lang.attribute_value_label')}}";
var required_field_error="{{ __('lang.required_field_error')}}";
var attribute_saved="{{ __('lang.attribute_saved')}}";
var fill_in_email_err="{{ __('errors.fill_in_email_err')}}";
var fill_in_valid_email_err="{{ __('errors.invalid_email_err')}}";
var fill_in_password_err="{{ __('errors.fill_in_password_err')}}";
var fill_in_confirm_password_err="{{ __('errors.fill_in_confirm_password_err')}}";
var fill_in_first_name_err="{{ __('errors.fill_in_first_name_err')}}";
var fill_in_last_name_err="{{ __('errors.fill_in_last_name_err')}}";
var fill_in_phone_number_err="{{ __('errors.fill_in_phone_number_err')}}";
var fill_in_address_err="{{ __('errors.fill_in_address_err')}}";
var fill_in_postal_code_err="{{ __('errors.fill_in_postal_code_err')}}";
var password_min_6_char="{{ __('errors.password_min_6_char')}}";
var password_not_matched="{{ __('errors.password_not_matched')}}";
var are_you_sure_message="{{ __('messages.are_you_sure_message')}}";
var yes_delete_it_message="{{ __('messages.yes_delete_it_message')}}";
var no_cancel_message="{{ __('messages.no_cancel_message')}}";
var alert_delete_record_message="{{ __('messages.alert_delete_record_message')}}";
var invalid_files_err="{{ __('errors.invalid_files_err')}}";
var max_files_restriction_seller="{{ __('users.max_images_restriction_seller')}}";
var input_alphabet_err = "{{ __('errors.input_alphabet_err')}}";
var service_fav_succ = "{{ __('messages.service_wishlist_add_success')}}";
var wishlist_service_exists = "{{ __('messages.wishlist_service_exists')}}";
var wishlist_product_exists = "{{ __('messages.wishlist_product_exists')}}";
var store_name_is_verified = "{{ __('users.store_name_is_verified')}}";
var select_package_to_subscribe = "{{ __('errors.select_package_to_subscribe')}}";
var please_check_privacy_policy = "{{ __('errors.please_check_privacy_policy')}}";
var please_enter_store_name = "{{ __('errors.please_enter_store_name')}}";
var valid_email_err = "{{ __('errors.valid_email_err')}}";
var read_more_btn = "{{ __('users.read_more_btn')}}";
var read_less_btn = "{{ __('users.read_less_btn')}}";
var wait_while_upload = "{{ __('errors.wait_for_image_uplaod')}}";
var please_uplaod_service_image = "{{ __('errors.please_uplaod_service_image')}}";
var please_check_swish_number = "{{ __('errors.please_check_swish_checkbox')}}";
var review_add_msg = "{{ __('messages.review_add_msg')}}";
var you_need_buyer_profile = "{{ __('users.you_need_buyer_profile')}}";
var please_add_payment_details = "{{ __('errors.please_add_payment_details')}}";
</script>
        <!-- <script>window.jQuery || document.write('<script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"><\/script>')</script> -->

        <!-- <script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script> -->

        <script src="{{url('/')}}/assets/front/js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>

            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
        
        @php
        $showDatetimepicker = 0;
              $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
              if (strpos($url,'-S-') !== false) 
                $showDatetimepicker = 1;
             
              @endphp
        @if($showDatetimepicker==1)
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
      @endif
    </body>
</html>

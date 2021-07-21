    <hr>
    <footer>
      <div class="ft_top_container">
        <div class="container">
        <ul class="client_logos">
          <li><img src="{{url('/')}}/assets/front/img/client_logo1.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo2.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo3.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo4.png"/></li>
          <li><img src="{{url('/')}}/assets/front/img/client_logo5.png"/></li>
        </ul>
      </div>
    </div>
    <div class="ft_middle_container">
      <div class="container">
        <div class="col-md-3">
            <img src="{{url('/')}}/uploads/Images/{{$siteDetails->footer_logo}}" />
            <article class="address_container">
              <p>{!!$siteDetails->footer_address!!}			 <!-- <strong>Address:</strong> 60-49 Road 11378 New York <br/>
                <strong>Phone:</strong> 0704959277<br/>
                <strong>Email:</strong> info@marketplace.se-->
              </p>
            </article>
            <ul class="social_icon">
              <li><a href="#"><img src="{{url('/')}}/assets/front/img/fb_icon.png"/></a></li>
              <li><a href="#"><img src="{{url('/')}}/assets/front/img/tw_icon.png"/></a></li>
              <li><a href="#"><img src="{{url('/')}}/assets/front/img/instgram_icon.png"/></a></li>
              <li><a href="#"><img src="{{url('/')}}/assets/front/img/pi_icon.png"/></a></li>
            </ul>
        </div>
        @php
          $allPages = getCustomPages();
        @endphp
        <div class="col-md-offset-1 col-md-2">
          <div class="ft_page_links">
          <h3>{{ __('lang.information_label')}}</h3>
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
        <h3>{{ __('lang.my_account_title')}}</h3>
        <ul>
          <li><a href="{{route('frontUserProfile')}}">{{ __('lang.my_account_title')}}</a></li>
          <li><a href="javascript:void(0)">{{ __('lang.contact_label')}} </a></li>
          <li><a href="{{route('AllproductListing')}}">{{ __('lang.shopping_cart_label')}} </a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="ft_page_links">
        <h3>{{ __('lang.join_our_newsletter_now_label')}}</h3>
        <p>{{ __('lang.get_e-mail_updates_label')}}</p>
          <div class="sub_box">
            <input type="text" placeholder="{{ __('lang.enter_mail_placeholder')}} "/>
            <button type="button" class="btn sub_btn">{{ __('lang.subscribe_label')}}</button>
          </div>
      </div>
    </div>
      </div>

    </div>
    <div class="clearfix"></div>
      <div class="ft_copyright">
        <p>© {{ __('lang.copyright_label')}} {{date('Y')}} <span class="de_col">{{config('constants.PROJECT_NAME')}}</span>. | {{ __('lang.all_right_reserved_label')}}</p>
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

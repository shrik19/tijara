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
        <div class="col-md-offset-1 col-md-2">
          <div class="ft_page_links">
          <h3>{{ __('lang.information_label')}}</h3>
          <ul>
            <li><a href="">{{ __('lang.about_us_label')}} </a></li>
            <li><a href="">{{ __('lang.checkout_label')}}</a></li>
            <li><a href="">{{ __('lang.contact_label')}}</a></li>
            <li><a href="">{{ __('lang.service_label')}}</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-2">
        <div class="ft_page_links">
        <h3>{{ __('lang.my_account_title')}}</h3>
        <ul>
          <li><a href="">{{ __('lang.my_account_title')}}</a></li>
          <li><a href="">{{ __('lang.contact_label')}} </a></li>
          <li><a href="">{{ __('lang.shopping_cart_label')}} </a></li>
          <li><a href="">{{ __('lang.shop_label')}}</a></li>
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript" src="{{url('/')}}/assets/front/js/select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/front/css/select2.css"> 
<script>
  $(document).ready(function() {
    $('#description').richText();
  });

$('#categories').select2({
		placeholder:"select"
		});

</script>
        <script>window.jQuery || document.write('<script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>

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
    </body>
</html>

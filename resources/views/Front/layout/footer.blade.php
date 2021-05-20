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
          <h3>Information</h3>
          <ul>
            <li><a href="">About Us </a></li>
            <li><a href=""> Checkout </a></li>
            <li><a href="">Contact </a></li>
            <li><a href=""> Service </a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-2">
        <div class="ft_page_links">
        <h3>My Account</h3>
        <ul>
          <li><a href="">My Account</a></li>
          <li><a href=""> Contact </a></li>
          <li><a href="">Shopping Cart </a></li>
          <li><a href=""> Shop </a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="ft_page_links">
        <h3>Join Our Newsletter Now</h3>
        <p>Get E-mail updates about our latest Shop and
          special offers.</p>
          <div class="sub_box">
            <input type="text" placeholder="Enter you Mail "/>
            <button type="button" class="btn sub_btn">SUBSCRIBE</button> 
          </div>
      </div>
    </div>
      </div>
      
    </div>
    <div class="clearfix"></div>
      <div class="ft_copyright">
        <p>© Copyright 2021 <span class="de_col">Tijara</span>. | All Right Reserved</p>
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

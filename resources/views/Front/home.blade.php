@extends('Front.layout.template')
@section('middlecontent')

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/azcustom.css">
<hr class="categoryGrayLine">
 <!-- Carousel Default -->
<div class="slider_cotnainer_section">
  <div class="container-fluid">
    <div class="row">
      <section class="carousel-default slider_cotnainer">
        <div id="carousel-default" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators slider_indicators">
            @foreach($SliderDetails as $key=>$data)
                @if($key == 0)
                    <li data-target="#carousel-default" data-slide-to="{{$key}}" class="active"></li>
                @else
                    <li data-target="#carousel-default" data-slide-to="{{$key}}" class=""></li>
                @endif  
            @endforeach  
          </ol>
          <div class="carousel-inner home-slider" role="listbox">
            <!-- NOTE: Bootstrap v4 changes class name to carousel-item -->
            @foreach($SliderDetails as $key=>$data)
            @if($key == 0)
            <div class="item active slider_item">
              @else
              <div class="item slider_item">
                @endif
                <img class="img-fluid" src="{{url('/')}}/uploads/Slider/<?php echo $data['sliderImage']; ?>" id="sliderImages" alt="First slide">
                <div class="product_view">
                  <div class="slider_content">
                    <?php /* <h3>{{$data['title']}}</h3>*/?>
                    <?php echo $data['description']; ?>
                    <button type="submit" class=" btn slider_buy_btn debg_color" onclick="document.location='{{$data['link']}}'" >{{ __('lang.browse_now_btn')}}</button>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
 <!-- end slider section -->

<section class="pt-0">
    
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <!--  <div class="col-md-3">
            @include('Front.products_sidebar')
        </div>-->
        <div class="product_view">
        <div class="col-md-12">             
                <div class="product_container product_container-list-5">
                <div class="loader"></div>
                    <!-- <h4>{{ __('lang.popular_items_in_market_head')}}</h4> -->
             
                     <div>
                        <h2 class="product_heading">{{ __('lang.popular_product_head')}}</h2> 
                            <!-- <a href="{{url('/')}}/products" class="btn see-all-service-btn debg_color login_btn">{{ __('users.see_all_products')}}</a> -->
                    </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details pl-0">
                      @foreach($PopularProducts as $product)
                      @include('Front.products_widget')
                      @endforeach
                    </ul>

                    <div class="text-center"><a href="{{url('/')}}/products" class="btn sub_btn tj-loadmore login_btn visible-xs">{{ __('users.see_all_products')}}</a></div>
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
        <h2 class="heading product_heading">{{ __('lang.featured_seller_head')}}</h2>
    </div>
</div>
    <div class="featured-banner">
    <div class="featured_seller_container ">
    <div class="container-fluid">
        <div class="row logo-slider">               
                @if(!empty($FeaturedSellers))
                    @foreach($FeaturedSellers as $fea_seller)
                    <div class="feature_seller">
                        <div class="featured_seller_section"  >
                            <img class="img-fluid" src="{{url('/')}}/uploads/Seller/resized/<?php echo $fea_seller['logo'];?>" />
                            <div class="clearfix"></div>
                           
                        </div>
                        @php
                         //$seller_name = $fea_seller->fname." ".$fea_seller->lname;
						 
						 $seller_name = $fea_seller->store_name;
              
                          $seller_name = str_replace( array( '\'', '"', 
                          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                          $seller_name = str_replace(" ", '-', $seller_name);
                          $seller_name = strtolower($seller_name);
                                      
                          $seller_link= url('/').'/seller/'.$seller_name; 
                            
                        @endphp
                        <h3><a href="{{$seller_link}}" title="{{$fea_seller['store_name']}}" class="Featured_shop_heading">{{ $fea_seller['store_name'] }}</a></h3><!-- 
                        <h3>{{ $fea_seller['store_name'] }}</h3> -->
                    </div>
                    @endforeach
                @endif
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
                <div class="product_container product_container-list-5">
                <div class="loader"></div>
                    <div >
                    <h2 class="product_heading">{{ __('lang.popular_services_head')}}</h2>     
                    <!-- <a href="{{url('/')}}/services" class="btn see-all-service-btn debg_color login_btn">{{ __('users.see_all_services')}}</a> -->
                </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details pl-0">
                    @foreach($PopularServices as $service)
                        @include('Front.services_widget')
                    @endforeach
                    </ul>


                    <div class="text-center">
                      <a href="{{url('/')}}/services" class="btn sub_btn tj-loadmore login_btn visible-xs">{{ __('users.see_all_services')}}</a>
                    </div>
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
        <div class="product_container product_container-list-5">
        <div class="loader"></div>
          <div class="tj-prodhead-block">
            <h2 class="product_heading">{{ __('lang.feature_product_head')}}</h2>
            <a href="{{url('/')}}/annonser" title="{{ __('users.go_to_announse_page')}}" class="btn btn-black btn-sm  login_btn go_to_tijara_ads_btn hidden-xs">{{ __('users.go_to_announse_page')}}</a>
          </div>
            <!-- <hr class="heading_line"/> -->
            <ul class="product_details pl-0">
            @foreach($FeaturedProducts as $product)
                @include('Front.featured_product')
            @endforeach
            </ul>
            <div class="text-center visible-xs"><a href="{{url('/')}}/annonser" title="{{ __('users.go_to_announse_page')}}" class="btn btn-black btn-sm login_btn go_to_tijara_ads_btn">{{ __('users.go_to_announse_page')}}</a></div>
            
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
              <img src="{{url('/')}}/assets/img/icon1.png"  />
              <h4>{{ __('users.Unik_title')}}<br/>
              {{ __('users.marketplace_title')}}</h4>
              </div>
            </div>
              <h3>{{ __('users.unique_marketplace_info')}}</h3>
					</li>
					<li>
            <div class="colr-box colr-2 text-center">
              <div class="tijara_front_inner">
              <img src="{{url('/')}}/assets/img/icon2.png"/>
              <h4>{{ __('users.promote_title')}}<br/>
              {{ __('users.entrepreneurship_title')}}</h4>
              </div>
            </div>
              <h3>{{ __('users.entrepreneurship_info')}}</h3>
					</li>
					<li>
                    <div class="colr-box colr-3 text-center">
						<div class="tijara_front_inner">
                        <img src="{{url('/')}}/assets/img/icon3.png"/>
                        <h4>{{ __('users.encourages_to_title')}}<br/>
                            {{ __('users.local_trade_title')}}</h4>
						</div>
                    </div>
                    <h3>{{ __('users.encourages_local_trade_info')}}</h3>
					</li>                       
				</ul>
				<div class="tijara_front_read_more">
					<a href="{{url('/')}}/page/om-oss" class="btn debg_color login_btn">{{ __('users.read_more_btn')}}</a>
				</div>
            </div>			
			
            <div class="col-md-12 pl-w-0 pr-w-0">
				<div class="best_seller_container">
					<!--<h3>{{ __('lang.follow_us_on_head')}}</h3>-->
					<h2 class="product_heading instagram_heading">{{ __('lang.instagram_label')}}</h2>
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
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.who_we_are_head')}}</h2>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="{{url('/')}}/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more">{{ __('lang.learn_more_btn')}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="{{url('/')}}/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more">{{ __('lang.learn_more_btn')}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="artical_section">
                        <img class="img-fluid" src="{{url('/')}}/assets/front/img/popular_img1.png"/>
                        <div class="aritacl_details">
                            <h5>Feb 12, 2021</h5>
                            <h4>Funded R&D – Rights – 
                                Tangel Case R&D Tax Credits</h4>
                                <p>In a recent Tax Court case, Tangel v. Commissioner, T.C. Memo. 2021-1...
                                </p>
                            <a href="" class="btn learn_more">{{ __('lang.learn_more_btn')}}</a>
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

            @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1)
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
            @else
              window.location.href = "{{ route('frontLogin') }}"; 
               // showErrorMessage("{{ __('errors.login_buyer_required')}}");
            @endif
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

            @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1)
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
            @else
                //showErrorMessage("{{ __('errors.login_buyer_required')}}");
                window.location.href = "{{ route('frontLogin') }}"; 
            @endif
           }
          
         });
      });
</script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick-theme.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick.min.css">
<script src="{{url('/')}}/assets/front/js/slick.min.js"></script>
<script type="text/javascript">
  
</script>
<script src="{{url('/')}}/assets/front/js/instafeed/dist/instafeed.min.js"></script>

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
            breakpoint: 1100,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              adaptiveHeight: true,
              arrows: false,
            },
          },
          {
            breakpoint: 800,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
              arrows: false,
            },
          },
        ],
    });

    if($(window).width() < 767){
      $('.tijara_front_box').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        autoplay: false,
        autoplaySpeed: 2000,
        infinite: true
      });      
    }

  $('.left').click(function(){
  $('.slider').slick('slickPrev');
})

$('.right').click(function(){
  $('.slider').slick('slickNext');
})
var winhigh = $(window).height();
var tophigh = $('.tj-navbar').height();
var colHigh = winhigh - tophigh;
$('.navbar-toggler').on('click', function(){
   $('.navbar-collapse').css('min-height', colHigh + 8); 
});
$('.navbar-toggler').on('click',function(){
  $('body').toggleClass('sidebarActive');
});

var isMobile = {
  Android: function() {
      return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function() {
      return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function() {
      return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function() {
      return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function() {
      return navigator.userAgent.match(/IEMobile/i);
  },
  any: function() {
      return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};

$('#carousel-default').carousel ({
    interval: isMobile.any() ? false : 5000
});
</script>
@endsection

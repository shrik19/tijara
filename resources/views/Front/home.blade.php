@extends('Front.layout.template')
@section('middlecontent')

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
    
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

                    <div class="carousel-inner" role="listbox">
                        <!-- NOTE: Bootstrap v4 changes class name to carousel-item -->
                        @foreach($SliderDetails as $key=>$data)
                        @if($key == 0)
                        <div class="item active slider_item">
                        @else
                        <div class="item slider_item">
                        @endif
                        <div >
                            <img class="img-fluid" src="{{url('/')}}/uploads/Slider/test.jpg" id="sliderImages" alt="First slide">

                            <div class="slider_content">
                            <h3>{{$data['title']}}</h3>
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
 <!-- end slider section -->

<section class="product_section">
    
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <!--  <div class="col-md-3">
            @include('Front.products_sidebar')
        </div>-->
        <div class="product_view">
        <div class="col-md-12">             
                <div class="product_container">
                <div class="loader"></div>
                    <!-- <h4>{{ __('lang.popular_items_in_market_head')}}</h4> -->
             
                     <div>
                        <h2 class="heading">{{ __('lang.trending_product_head')}}</h2> 
                            <!-- <a href="{{url('/')}}/products" class="btn see-all-service-btn debg_color login_btn">{{ __('users.see_all_products')}}</a> -->
                    </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details">
					@foreach($TrendingProducts as $product)
                        @include('Front.products_widget')
						@endforeach
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
        <h2 class="heading">{{ __('lang.featured_seller_head')}}</h2>
    </div>
</div>
    <div class="featured-banner" >
    <div class="featured_seller_container">
    <div class="container-fluid">
        <div class="row ">               
                @if(!empty($FeaturedSellers))
                    @foreach($FeaturedSellers as $fea_seller)
                    <div class="col-md-3 feature_seller">
                        <div class="featured_seller_section"  >
                            <img class="img-fluid" src="{{url('/')}}/uploads/Seller/resized/<?php echo $fea_seller['logo'];?>" />
                            <div class="clearfix"></div>
                           
                        </div>
                        @php
                         $seller_name = $fea_seller->fname." ".$fea_seller->lname;
              
                          $seller_name = str_replace( array( '\'', '"', 
                          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                          $seller_name = str_replace(" ", '-', $seller_name);
                          $seller_name = strtolower($seller_name);
                                      
                          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($fea_seller->id)."/products"; 
                            
                        @endphp
                        <h3><a href="{{$seller_link}}" style="color: #000 !important;">{{ $fea_seller['store_name'] }}</a></h3><!-- 
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
                <div class="product_container">
                <div class="loader"></div>
                    <div >
                    <h2 class="heading">{{ __('lang.popular_services_head')}}</h2>     
                    <!-- <a href="{{url('/')}}/services" class="btn see-all-service-btn debg_color login_btn">{{ __('users.see_all_services')}}</a> -->
                </div>
                    <!-- <hr class="heading_line"/> -->
                    <ul class="product_details">
                    @foreach($PopularServices as $service)
                        @include('Front.services_widget')
                    @endforeach
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
            <h2 class="heading">{{ __('lang.feature_product_head')}}</h2>
            <!-- <hr class="heading_line"/> -->
            <ul class="product_details">
            @foreach($FeaturedProducts as $product)
                @include('Front.featured_product')
            @endforeach
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
					<li>
						<div class="tijara_front_inner">
							<img class="img-fluid" src="{{url('/')}}/assets/front/img/tijara_front/New Project_1.png"/>
						</div>
					</li>
					<li>
						<div class="tijara_front_inner">
							<img class="img-fluid" src="{{url('/')}}/assets/front/img/tijara_front/New Project_2.png"/>
						</div>
					</li>
					<li>
						<div class="tijara_front_inner">
							<img class="img-fluid" src="{{url('/')}}/assets/front/img/tijara_front/New Project_3.png"/>
						</div>
					</li>                       
				</ul>
				<div class="tijara_front_read_more">
					<a href="{{url('/')}}/page/om-oss" class="btn debg_color login_btn">{{ __('users.read_more_btn')}}</a>
				</div>
            </div>			
			
            <div class="best_seller_container">
                <h3>{{ __('lang.follow_us_on_head')}}</h3>
                <h2>Instagram</h2>
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
            @else
                showErrorMessage("{{ __('errors.login_buyer_required')}}");
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
            @else
                showErrorMessage("{{ __('errors.login_buyer_required')}}");
            @endif
           }
          
         });
      });
</script>

<script src="{{url('/')}}/assets/front/js/instafeed/dist/instafeed.min.js"></script>

<script type="text/javascript">
    var access_token = "<?php echo env('INSTA_ACCESS_TOKEN') ?>";
    var feed = new Instafeed({
      accessToken: access_token
    });
    feed.run();


</script>
@endsection

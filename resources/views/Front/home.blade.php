@extends('Front.layout.template')
@section('middlecontent')
         
 <!-- Carousel Default -->
<div class="slider_cotnainer_section">
    <div class="container">
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
                        <div class="col-md-6">
                            <img class="img-fluid" src="{{url('/')}}/uploads/Slider/<?php echo $data['sliderImage'];?>" id="sliderImages" alt="First slide">
                        </div>
                        <div class="col-md-6">
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
    
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-3">
            @include('Front.products_sidebar')
        </div>
        <div class="col-md-9">             
                <div class="product_container">
                <div class="loader"></div>
                    <h4>{{ __('lang.popular_items_in_market_head')}}</h4>
                    <h2>{{ __('lang.trending_product_head')}}</h2>
                    <hr class="heading_line"/>
                    <ul class="product_details">
					@foreach($TrendingProducts as $product)
                        @include('Front.products_widget')
						@endforeach
                       </ul>
                </div>
        </div>
    </div>

      
    </div> <!-- /container -->  
</section>
<!-- banner section -->
<section class="sale_section">
    <div class="container">
        <div class="row">
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
</section>
<!-- end banner section -->

<section>
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.best_seller_head')}}</h2>
                <ul class="product_details best_seller">
					@foreach($PopularProducts as $product)
                    @include('Front.products_widget')
					@endforeach
				 </ul>
            </div>
            <div class="best_seller_container">
                <h3>{{ __('lang.follow_us_on_head')}}</h3>
                <h2>Instagram</h2>
                <div class="social_img_section">
                    <ul class="instagram_imgs">
                        <li><img class="img-fluid" src="{{url('/')}}/assets/front/img/inst_img1.png"></li>
                        <li><img class="img-fluid" src="{{url('/')}}/assets/front/img/inst_img2.png"></li>
                        <li><img class="img-fluid" src="{{url('/')}}/assets/front/img/inst_img1.png"></li>
                        <li><img class="img-fluid" src="{{url('/')}}/assets/front/img/inst_img2.png"></li>
                        <li><img class="img-fluid" src="{{url('/')}}/assets/front/img/inst_img1.png"></li>
                    </ul>
                </div>
            </div>
            <div class="best_seller_container">
                <h3>{{ __('lang.3_steps_head')}}</h3>
                <h2>{{ __('lang.how_it_works_head')}}</h2>
                <div class="steps_section">
                    <div class="curve_img_1">
                        <img src="{{url('/')}}/assets/front/img/steps_bg_1.png"/>
                    </div>
                    <ul class="steps_box">
                        <li>
                            <div class="steps_details">
                                <img class="img-fluid" src="{{url('/')}}/assets/front/img/step1_icon.png"/>
                                <h3>{{ __('lang.step_1_head')}}</h3>
                                <p>
                                    Aliquam venenatis mi sit amet velit sagittis molestie. Pellentesque varius viverra libero, at congue lorem aliquet in. Sed sed quam a turpis ultrices elementum. Aenean erat sapien, suscipit consequat porta et, elementum nec enim.
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="steps_details">
                                <img class="img-fluid" src="{{url('/')}}/assets/front/img/step2_icon.png"/>
                                <h3>{{ __('lang.step_2_head')}}</h3>
                                <p>
                                    Aliquam venenatis mi sit amet velit sagittis molestie. Pellentesque varius viverra libero, at congue lorem aliquet in. Sed sed quam a turpis ultrices elementum. Aenean erat sapien, suscipit consequat porta et, elementum nec enim.
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="steps_details">
                                <img class="img-fluid" src="{{url('/')}}/assets/front/img/step3_icon.png"/>
                                <h3>{{ __('lang.step_3_head')}}</h3>
                                <p>
                                    Aliquam venenatis mi sit amet velit sagittis molestie. Pellentesque varius viverra libero, at congue lorem aliquet in. Sed sed quam a turpis ultrices elementum. Aenean erat sapien, suscipit consequat porta et, elementum nec enim.
                                </p>
                            </div>
                        </li>                       
                    </ul>
                    <div class="curve_img_2">
                        <img src="{{url('/')}}/assets/front/img/steps_bg_2.png"/>
                    </div>
                </div>
            </div>

         
        </div>
    </div>
</section>
<section class="artical_section">
    <div class="container">
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
</section>
<script type="text/javascript">
   
</script>
@endsection

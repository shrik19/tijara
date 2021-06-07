@extends('Front.layout.template')
@section('middlecontent')
         
<section class="product_details_section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div id="carousel-custom" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    @if(!empty($ProductImages))
                    @php $i=0; @endphp
                    <div class="carousel-inner" role="listbox">
                    @foreach($ProductImages as $image)
                    @php $i++; @endphp
                      <div class="item @if($i==1) active @endif">
                        <img src="{{url('/')}}/uploads/ProductImages/{{$image['image']}}" alt="..." class="img-responsive">
                      </div>
                      @endforeach
                      
                    </div>
                    @php $i=0; @endphp
                    <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block thumbnails_slider">
                    @foreach($ProductImages as $image)
                    @php $i++; @endphp
                              <li data-target="#carousel-custom" data-slide-to="@php echo $i @endphp" class=" @if($i==1) active @else img-responsive @endif">
                                <img src="{{url('/')}}/uploads/ProductImages/resized/{{$image['image']}}" alt="..." class="img-responsive">
                              </li>
                              @endforeach
                                              
                            </ol> 
                  
                  @endif
                  
                  
                  </div>
            </div>
            <div class="col-md-6">
                <div class="product_details_info">
                    <h2>{{$Product->title}}</h2>
                    <h3>$144.00</h3>
                    <div class="star-rating">
                        <input type="radio" id="5-stars" name="rating" value="5" />
                        <label for="5-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="4-stars" name="rating" value="4" />
                        <label for="4-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="3-stars" name="rating" value="3" />
                        <label for="3-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="2-stars" name="rating" value="2" />
                        <label for="2-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="1-star" name="rating" value="1" />
                        <label for="1-star" class="star"><i class="fas fa-star"></i></label>
                      </div>
                      <p>
                        <?php echo $Product->description; ?>
                      </p>
                      <div class="row">
                          <div class="col-md-6 col-xs-6">
                              <div class="quantity_box">
                                <h3>Quantity:</h3> 
                                <!-- <select class="drop_down_select pull-right">
                                      <option>1</option>
                                      <option>2</option>
                                      <option>3</option>
                                      <option>4</option>
                                  </select> -->
                                  <input class="drop_down_select " list="quantities" >
                                    <datalist id="quantities">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                        
                                    </datalist>
                              </div>
                          </div>
                          <div class="col-md-6 col-xs-6">
                            <div class="quantity_box">
                                <h3>Color : </h3> 
                                <ul class="select_product_color">
                                    <li><span class="color_1"></span></li>
                                    <li><span class="color_2"></span></li> 
                                    <li><span class="color_3"></span></li>
                                    <li><span class="color_4"></span></li>
                                </ul>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="quantity_box">
                                <h3> Size: </h3>
                                 <input class="drop_down_select" list="Size" >
                                <datalist id="Size">
                                <option value="1"></option>
                                <option value="2"></option>
                                <option value="3"></option>
                                <option value="4"></option>
                                <option value="5"></option>
                                    
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="quantity_box marging_0">
                               <button type="button" class="btn add_to_cart_btn"> Add To Cart  <i class="glyphicon glyphicon-shopping-cart cart_icon"></i></button>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>      
    </div> <!-- /container -->  
</section>

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
            
            
         
        </div>
    </div>
</section>

<script type="text/javascript">
   
</script>
@endsection

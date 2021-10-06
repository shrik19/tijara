@php
$class = (strpos(@$path, 'annonser') !== false || strpos(@$path, 'seller') !== false || strpos(@$path, 'products') !== false) ? 'col-md-3':'col-md-15';
@endphp

<li class="{{$class}}">

  <div class="product_data product_link_js" product_link="{{$product->product_link}}" @if($product->is_sold == '1') style="pointer-events: none;opacity: 0.4;"  @endif>
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($product->image)
          <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product->image}}" >
      @else
          <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" >
      @endif

      @php 

          $seller_name = $product->seller;
          $seller_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
          $seller_name = str_replace(" ", '-', $seller_name);
          $seller_name = strtolower($seller_name);
                      
          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($product->user_id)."/products"; 
        @endphp
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details one_icon">
          <ul>
              <!--<li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToCart('{{$product->variant_id}}');event.stopPropagation();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');event.stopPropagation();" @endif><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
              -->
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) 
                    onclick="addToWishlist('{{$product->variant_id}}');event.stopPropagation();" 
                    @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');event.stopPropagation();" @endif>
                    <i class="far fa-heart"></i>
                  </a>
              </li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        @php 
       $category_name = $product->category_name;
        $category_name = str_replace( array( '\'', '"', 
        ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $category_name);
        $category_name = str_replace(" ", '-', $category_name);
        $category_name = strtolower($category_name);
                    
      $product_cat_link= url('/').'/products/'.$category_name; @endphp

        @if( Request::path() == "/")
         <a href="{{$product->product_link}}" title="{{$product->title}}" style="margin-top: 8px;"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
         <div class="star-rating" style="font-size:unset;margin-top: 0px;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>

        @if(!empty($product->price))
          @if(strpos(@$path, 'annonser') !== false)
            <h6 class="product_price" style="margin-top: 6px;"><span>{{$product->price}} kr </span></h6>
          @else
            <h6 class="product_price" style="margin-top: 6px;"> @if(!empty($product->discount_price)) {{$product->discount_price}} kr @endif <span @if(!empty($product->discount_price)) class="dic_price" @endif>{{$product->price}} kr </span><span @if(!empty($product->discount)) class="dic_percent" @endif >@if(!empty($product->discount)) (<?php echo $product->discount."% off";?>) @endif</span></h6>
          @endif      
        @endif
           <a href="{{$seller_link}}" style="margin-top: 3px"><h5>{{$product->seller}}</h5></a>
          <?php /*<a href="{{$product_cat_link}}"><h5>{{$product->category_name}}</h5></a> */?>
        @else

          @if(Request::segment(1) !='product' &&  strpos(@$path, 'annonser') == false)
           <a href="{{$product_cat_link}}"><h5>{{$product->category_name}}</h5></a> 
          @endif
          <a href="{{$product->product_link}}" title="{{$product->title}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
         @if(Request::segment(1) !='products' && Request::segment(1) != 'get_product_listing')
        <div class="star-rating" style="font-size:15px;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  
        @endif
        @if(!empty($product->price))
         @if(strpos(@$path, 'annonser') !== false)
            <h6 class="product_price" style="margin-top: 6px;"><span>{{$product->price}} kr </span></h6>
          @else
            <h6 class="product_price" style="margin-top: 6px;"> @if(!empty($product->discount_price)) {{$product->discount_price}} kr @endif <span @if(!empty($product->discount_price)) class="dic_price" @endif>{{$product->price}} kr </span><span @if(!empty($product->discount)) class="dic_percent" @endif >@if(!empty($product->discount)) (<?php echo $product->discount."% off";?>) @endif</span></h6>
          @endif
        @endif

         
        @if(Request::segment(1) !='products' && Request::segment(1) != 'get_product_listing')
         <a href="{{$seller_link}}"><h5>{{$product->seller}}</h5></a>
        @endif

        @endif
        
        <!--  -->
        <input type="hidden" name="product_quantity_{{$product->variant_id}}" id="product_quantity_{{$product->variant_id}}" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('{{$product->variant_id}}');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>
</li>
<script type="text/javascript">
  $(".product_link_js").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script>
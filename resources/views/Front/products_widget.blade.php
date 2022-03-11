@php

$class = (strpos(@$path, 'annonser') !== false || strpos(@$path, 'seller') !== false || strpos(@$path, 'products') !== false) ? 'product_img_wrapper':'col-md-15';

if(strpos(@$path, 'annonser') !== false){
  $product_link = $product->product_link.'?annonser=1';
}else{
   $product_link = $product->product_link;
}

$order_product_link = url('/').'/product/'.$product->product_slug.'-P-'.$product->product_code;
@endphp

<li class="{{$class}}">

  <div class="product_data product_link_js" product_link="@if(!empty($product_link)){{$product_link}}@else{{$order_product_link}}@endif" @if($product->is_sold == '1') style="pointer-events: none;opacity: 0.4;"  @endif>
    <div class="product_img" style="display:inline-block;background-color: white;">
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
		  
		  $store_name = $product->store_name;
          $store_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $store_name);
          $store_name = str_replace(" ", '-', $store_name);
          $store_name = strtolower($store_name);
          $seller_link= url('/').'/seller/'.$store_name; 

          $heartStyle = $iconSize= $paddingleft = '';

          if(strpos(@$path, 'seller') != false){
            $heartStyle='left:18px !important';
            $iconSize = 'font-size: 13px !important';
            $paddingleft = "margin-left: 0!important; padding-left: 0!important";
          }
          if(strpos(@$path, 'products') != false){
            $iconSize = 'font-size: 13px !important';
          }
          if(strpos(@$path, 'services') != false){
            $iconSize = 'font-size: 13px !important';
          }
         
        @endphp
      <!-- <div class="buy_now_hover_details" style="height:280px !important;">  || strpos(@$path, 'products') != false-->
      @if(strpos(@$path, 'annonser') == false)
      <div class="buy_now_hover_details one_icon">
          <ul>
              <!--<li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToCart('{{$product->variant_id}}');event.stopPropagation();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');event.stopPropagation();" @endif><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
              -->
              <li style="{{$paddingleft}}"><a  @if(Auth::guard('user')->id()) 
                    onclick="addToWishlist('{{$product->variant_id}}');event.stopPropagation();" 
                    @else href="{{ route('frontLogin') }}" @endif style="{{$iconSize}}">
                    <i class="far fa-heart wishlisticon"></i>
                  </a>
              </li>
          </ul>
      </div>
      @endif
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
         <a href="{{$product_link}}" title="{{$product->title}}" style="margin-top: 8px;"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
         <div class="star-rating" style="font-size:15px;margin-top: 0px;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>

        @if(!empty($product->price))
      <h6 class="product_price" style="margin-top: 6px;"> @if(!empty($product->discount_price)) {{$product->discount_price}} kr @endif <span @if(!empty($product->discount_price)) class="dic_price" @endif>{{$product->price}} kr </span></h6>

        @endif
           <a href="{{$seller_link}}" style="margin-top: 3px"><h5>{{$product->store_name}}</h5></a>
          <?php /*<a href="{{$product_cat_link}}"><h5>{{$product->category_name}}</h5></a> */?>
        @else

          <a href="@if(!empty($product_link)){{$product_link}}@else{{$order_product_link}}@endif" title="{{$product->title}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
        <div class="star-rating" style="font-size:15px;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  
        @if(!empty($product->price))
              <h6 class="product_price" style="margin-top: 6px;"> @if(!empty($product->discount_price)) {{$product->discount_price}} kr @endif <span @if(!empty($product->discount_price)) class="dic_price" @endif>{{$product->price}} kr </span></h6>          
        @endif
        <a href="{{$seller_link}}" style="margin-top: 3px"><h5>{{$product->store_name}}</h5></a>

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
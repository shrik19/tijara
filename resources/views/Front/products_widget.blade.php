<li  class="col-xs-15">
  <div class="product_data" product_link="{{$product->product_link}}" @if($product->is_sold == '1') style="pointer-events: none;opacity: 0.4;"  @endif>
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($product->image)
          <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product->image}}" >
      @else
          <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" >
      @endif
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details">
          <ul>
              <li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToCart('{{$product->variant_id}}');" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToWishlist('{{$product->variant_id}}');" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif><i class="far fa-heart"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        @php $product_cat_link= url('/').'/products/'.strtolower($product['category_name']); @endphp
        <!-- <a href="{{$product_cat_link}}">
          <h5>{{$product['category_name']}}</h5></a> -->
          <a href="{{$product->product_link}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
        <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  
      
        @if(!empty($product->price))
        <h6 class="product_price"> @if(!empty($product->discount_price)) {{$product->discount_price}} kr @endif <span @if(!empty($product->discount_price)) class="dic_price" @endif>{{$product->price}} kr </span></h6>
        @endif

         @php 
          $seller_link= url('/').'/seller/'.$product->seller."/". base64_encode($product->user_id)."/products"; 
        @endphp
         <a href="{{$seller_link}}"><h5>{{$product->seller}}</h5></a>
        <input type="hidden" name="product_quantity_{{$product->variant_id}}" id="product_quantity_{{$product->variant_id}}" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('{{$product->variant_id}}');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>
</li>
<script type="text/javascript">
  $(".product_data").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script>
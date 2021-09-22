<li class="col-xs-15">
  <div class="product_data product_link_js" product_link="{{$product->product_link}}">
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($product->image)
          <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product->image}}" >
      @else
          <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" >
      @endif
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details one_icon">
          <ul>
             <?php /*<li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li> */?>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToWishlistproducts('{{$product->id}}');" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif><i class="far fa-heart"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        <!-- <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div>  -->
        @php $product_cat_link= url('/').'/products/'.strtolower($product['category_name']); @endphp
        <!-- <a href="{{$product_cat_link}}">
        <h5>{{$product['category_name']}}</h5></a> -->
        <a href="{{$product->product_link}}" title="{{$product->title}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
        @if(!empty($product->price))
        <h6 class="product_price">{{$product->price}} kr</h6>
        @endif
        <h6 >{{$product->seller}}</h6>
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
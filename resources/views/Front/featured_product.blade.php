<li style="min-height:500px;">
  <div class="product_data">
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($product->image)
          <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product->image}}" style="width:100%;">
      @else
          <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" style="width:100%;">
      @endif
      <div class="buy_now_hover_details" style="height:280px !important;">
          <ul>
              <li style="margin-left: 30%;"><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToWishlistproducts('{{$product->id}}');" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif><i class="far fa-heart"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        <div class="star-rating" style="font-size:unset;">
          <select class='rating product_rating' id='rating_{{$product->id}}' data-id='rating_{{$product->id}}' data-rating='{{$product->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div> 

        <h5>{{$product['category_name']}}</h5>
        <a href="{{$product->product_link}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
        @if(!empty($product->price))
        <h6>{{$product->price}} kr</h6>
        @endif
        <h6>{{$product->seller}}</h6>
    </div>
  </div>


</li>

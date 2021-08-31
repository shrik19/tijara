<li class="col-md-15">
  <div class="product_data services-data" service_link="{{$service->service_link}}" >
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($service->images)
          <img src="{{url('/')}}/uploads/ServiceImages/resized/{{$service->image}}" style="width:100%;">
      @else
          <img src="{{url('/')}}/uploads/ServiceImages/resized/no-image.png" style="width:100%;">
      @endif
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details">
          <ul>
              <li style="margin-left: 10%;"><a href="{{$service->service_link}}"><i class="fa fa-search"></i></a></li>
              <li><a href="javascript:void(0);" @if(Auth::guard('user')->id()) onclick="addToWishlistServices('{{$service->id}}');event.stopPropagation();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');event.stopPropagation();" @endif><i class="far fa-heart"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
      @if(Request::segment(1) !='service')

        <div class="star-rating" style="font-size:unset;">
          <select class='rating service_rating' id='rating_{{$service->id}}' data-id='rating_{{$service->id}}' data-rating='{{$service->rating}}'>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option value="5" >5</option>
          </select>
        </div> 
      @endif

        @php $service_cat_link= url('/').'/services/'.strtolower($service['category_name']); @endphp
        <a href="{{$service_cat_link}}"><h5>{{$service['category_name']}}</h5></a>
        <a href="{{$service->service_link}}"><h4>@php echo substr($service->title, 0, 50) @endphp</h4></a>
        @if(!empty($service->service_price))
        <h6>{{$service->service_price}} kr</h6>
        @endif
        <!-- below code is for seller name  -->
        @php 
          $seller_name = $service->seller;

          $seller_name = str_replace( array( '\'', '"', 
          ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
          $seller_name = str_replace(" ", '-', $seller_name);
          $seller_name = strtolower($seller_name);

          $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($service->user_id)."/services"; 
          
        @endphp

        @if(Request::segment(1) !='service')
          <a href="{{$seller_link}}"><h6>{{$service->seller}}</h6></a>
        @endif
    </div>
  </div>

</li>
<script type="text/javascript">
  $(".services-data").click(function(){
  var attr_val = $(this).attr('service_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script>
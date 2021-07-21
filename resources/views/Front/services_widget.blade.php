<li style="min-height:500px;">
  <div class="product_data">
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($service->images)
          <img src="{{url('/')}}/uploads/ServiceImages/resized/{{$service->image}}" style="width:100%;">
      @else
          <img src="{{url('/')}}/uploads/ServiceImages/resized/no-image.png" style="width:100%;">
      @endif
      <div class="buy_now_hover_details" style="height:280px !important;">
          <ul>
              <li style="
    margin-left: 30%;
"><a href="{{$service->service_link}}"><i class="fa fa-search"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="product_info">
        <h5>{{$service['category_name']}}</h5>
        <a href="{{$service->service_link}}"><h4>@php echo substr($service->title, 0, 50) @endphp</h4></a>
        @if(!empty($service->price))
        <h6>{{$service->price}} kr</h6>
        @endif
        <h6>{{$service->seller}}</h6>
    </div>
  </div>


</li>

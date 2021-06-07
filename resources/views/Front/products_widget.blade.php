<li>
                    
        <div class="product_img">
            @if($product['image'])
            <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product->image}}">
        @else
        <img width="240" src="{{url('/')}}/uploads/ProductImages/resized/no-image.png">
    @endif
        </div>
        <div class="product_info">
            <h5>{{$product['category_name']}}</h5>  
            <h4>{{$product->product_link}}@php echo substr($product->title, 0, 50) @endphp</h4>
            @if(!empty($product->price))
            <h6>$ {{$product->price}}</h6> 
            @endif
        </div>
            <div class="buy_now_hover_details">
            <ul>
                <li><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
                <li><a href=""><i c lass="glyphicon glyphicon-shopping-cart"></i></a></li>
                <li><a href=""><i class="far fa-heart"></i></a></li>
            </ul>
        </div>
    </li>
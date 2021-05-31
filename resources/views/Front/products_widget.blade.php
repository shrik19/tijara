<li>
                    
        <div class="product_img">
        
            <img src="{{url('/')}}/uploads/ProductImages/resized/{{$product['image']}}">
        
        </div>
        <div class="product_info">
            <h5>{{$product['category_name']}}</h5>  
            <h4>{{$product['title']}}</h4>
            @if(!empty($product['sell_price']))
            <h6>$ {{$product['sell_price']}}</h6> 
            @endif
        </div>
            <div class="buy_now_hover_details">
            <ul>
                <li><a href="{{url('/')}}/product/{{$product['product_slug']}}"><i class="fa fa-search"></i></a></li>
                <li><a href=""><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
                <li><a href=""><i class="far fa-heart"></i></a></li>
            </ul>
        </div>
    </li>
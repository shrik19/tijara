@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<section class="product_details_section">
<div class="loader"></div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-md-6">
              <h2>{{ __('lang.shopping_cart')}}</h2>
              <hr class="heading_line"/>
            </div>
            <div class="col-md-6 text-right">
           <button type="button" class="btn btn-default" onclick="location.href='{{route('frontHome')}}';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> {{ __('lang.shopping_cart_continue')}}
                        </button>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                         <th>{{ __('users.butik_btn')}}</th>
                        <th>{{ __('lang.shopping_cart_product')}}</th>
                        <th>{{ __('lang.shopping_cart_quantity')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_price')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_shipping')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_total')}}</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  @if(!empty($details))
                  @foreach($details as $orderId => $tmpOrderProduct)
                    @foreach($tmpOrderProduct['details'] as $orderProduct)
                    
                    <tr>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="{{$orderProduct['product']->product_link}}"> 
                            @if($orderProduct['sellerLogo'])
                              <img src="{{url('/')}}/uploads/Seller/resized/{{$orderProduct['sellerLogo']}}" class="media-object" style="width: 72px; height: 72px;">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            @endif
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="{{$orderProduct['product']->seller_link}}">{{ $orderProduct['product']->store_name }}</a></h4>
                                <!-- <h5 class="media-heading"> {{$orderProduct['variant_attribute_id']}} </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="{{$orderProduct['product']->product_link}}"> 
                            @if($orderProduct['product']['image'])
                              <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object" style="width: 72px; height: 72px;">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            @endif
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
                                <h5 class="media-heading"> {{$orderProduct['variant_attribute_id']}} </h5>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        <select name="quantity_{{ $orderProduct['id'] }}" id="quantity_{{ $orderProduct['id'] }}" class="form-control" onchange="updateCart('{{ $orderProduct['id'] }}')">
                            <option value="1" @if($orderProduct['quantity'] == 1) selected="selected" @endif>1</option>
                            <option value="2" @if($orderProduct['quantity'] == 2) selected="selected" @endif>2</option>
                            <option value="3" @if($orderProduct['quantity'] == 3) selected="selected" @endif>3</option>
                            <option value="4" @if($orderProduct['quantity'] == 4) selected="selected" @endif>4</option>
                            <option value="5" @if($orderProduct['quantity'] == 5) selected="selected" @endif>5</option>
                            <option value="6" @if($orderProduct['quantity'] == 6) selected="selected" @endif>6</option>
                            <option value="7" @if($orderProduct['quantity'] == 7) selected="selected" @endif>7</option>
                            <option value="8" @if($orderProduct['quantity'] == 8) selected="selected" @endif>8</option>
                            <option value="9" @if($orderProduct['quantity'] == 9) selected="selected" @endif>9</option>
                            <option value="10" @if($orderProduct['quantity'] == 10) selected="selected" @endif>10</option>
                        </select>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format($orderProduct['price'],2) }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong>{{ number_format($orderProduct['shipping_amount'],2)}} kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)}} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                        <button class="btn btn-danger" onclick="removeCartProduct('{{ $orderProduct['id'] }}')" title="Remove"><i class="fas fa-trash"></i></button>
                        <!-- <button type="button" class="btn btn-danger" onclick="removeCartProduct('{{ $orderProduct['id'] }}')">
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button> -->
                      </td>
                    </tr>
                  @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_subtotal')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format($tmpOrderProduct['subTotal'],2)}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_shipping')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format($tmpOrderProduct['shippingTotal'],2)}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>{{ __('lang.shopping_cart_total')}}</h3></td>
                        <td class="text-right"><h4><strong>{{number_format($tmpOrderProduct['Total'],2)}} kr</strong></h4></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>
                        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" @if($tmpOrderProduct['is_buyer_product']) onclick="location.href='{{route('frontShowBuyerCheckout' , ['id' => base64_encode($orderId)])}}'" @else  onclick="location.href='{{route('frontShowCheckout', ['id' => base64_encode($orderId)])}}'" @endif>
                        {{ __('lang.shopping_cart_checkout')}} <span class="glyphicon glyphicon-play"></span>
                        </button></td>
                    </tr>
                    <tr><td colspan="6" style="border:none;line-height:60px;">&nbsp;</td></tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6">{{ __('lang.shopping_cart_no_records')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>

@endsection

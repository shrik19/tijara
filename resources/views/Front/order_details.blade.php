@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<section class="product_details_section">
<div class="loader"></div>
<div class="container printdiv">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="col-md-12">
                <h2>{{ __('messages.txt_order_details')}} - #{{ $order['id'] }}</h2>
                <hr class="heading_line"/>
                </div>
            </div>
            <div class="row">
                @if(!empty($billingAddress))
                <div class="col-sm-12 col-md-6">
                    <h4><strong>{{ __('messages.txt_billing_address')}}</strong></h4>
                    <span style="font-size:16px;">{{ $billingAddress['given_name'] }} {{ $billingAddress['family_name'] }}<br />
                    {{ $billingAddress['email'] }}<br />
                    {{ $billingAddress['street_address'] }}<br />
                    {{ $billingAddress['city'] }}, {{ $billingAddress['postal_code'] }}<br />
                    {{ $billingAddress['phone'] }}</span>
                </div>
                @endif
                @if(!empty($shippingAddress))
                <div class="col-sm-12 col-md-6 text-right">
                    <h4><strong>{{ __('messages.txt_billing_address')}}</strong></h4>
                    <span style="font-size:16px;">{{ $shippingAddress['given_name'] }} {{ $shippingAddress['family_name'] }}<br />
                    {{ $shippingAddress['email'] }}<br />
                    {{ $shippingAddress['street_address'] }}<br />
                    {{ $shippingAddress['city'] }}, {{ $shippingAddress['postal_code'] }}<br />
                    {{ $shippingAddress['phone'] }}<span />
                </div>
                @endif
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th>{{ __('lang.shopping_cart_product')}}</th>
                        <th>{{ __('lang.shopping_cart_quantity')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_price')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_shipping')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_total')}}</th>
                    </tr>
                </thead>
                <tbody>
                  @if(!empty($details))
                  @foreach($details as $orderProduct)
                    <tr>
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
                        <span id="quantity_{{ $orderProduct['id'] }}" > {{ $orderProduct['quantity'] }} </span>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format($orderProduct['product']->price,2) }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong>{{ number_format($orderProduct['shipping_amount'],2)}} kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format(($orderProduct['product']->price * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)}} kr</strong></td>
                    </tr>
                  @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_subtotal')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format($subTotal,2)}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_shipping')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format($shippingTotal,2)}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td> 
                        <span style="font-size:16px;">
                            {{ __('messages.txt_seller')}} : <a href="{{$seller_link}}">{{ $seller_name }}</a><br />
                            {{ __('messages.txt_payment_status')}} : {{ $order['payment_status'] }} <br />
                            {{ __('messages.txt_order_status')}} : {{ $order['order_status'] }}
                        </span> 
                        </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>{{ __('lang.shopping_cart_total')}}</h3></td>
                        <td class="text-right"><h4><strong>{{number_format($Total,2)}} kr</strong></h4></td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="5">{{ __('lang.shopping_cart_no_records')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" onclick="printDiv();">{{ __('messages.txt_order_details_print')}} <span class="glyphicon glyphicon-print"></span></button>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
</div>

</section>
<script type="text/javascript">
    function printDiv() 
    {
        var divToPrint=jQuery(".printdiv");
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.html()+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }
</script>
@endsection

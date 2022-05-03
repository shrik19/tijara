<!DOCTYPE html>
<html>
<head>
  <title>{{ __('messages.txt_order_details')}} - #{{ $order['id'] }}</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tr>
                            <td><img class="logo" src="{{url('/')}}/uploads/Images/{{$siteDetails->header_logo}}" height="70"/></td>
                            <td><h2>{{ __('messages.txt_order_details')}} - #{{ $order['id'] }}</h2></td>
                        </tr>
                    </table>        
                    
                </div>
                <div class="col-md-12">
                <hr class="heading_line"/>
                <table class="table table-hover">
                    <tr>
                        <td>
                            @if(!empty($billingAddress))
                                <h4><strong>{{ __('messages.txt_billing_address')}}</strong></h4>
                                <span style="font-size:16px;">{{ $billingAddress['given_name'] }} {{ $billingAddress['family_name'] }}<br />
                                {{ $billingAddress['email'] }}<br />
                                {{ $billingAddress['street_address'] }}<br />
                                {{ $billingAddress['city'] }}, {{ $billingAddress['postal_code'] }}<br />
                                {{ $billingAddress['phone'] }}</span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($shippingAddress))
                                <h4><strong>{{ __('messages.txt_shipping_address')}}</strong></h4>
                                <span style="font-size:16px;">{{ $shippingAddress['given_name'] }} {{ $shippingAddress['family_name'] }}<br />
                                {{ $shippingAddress['email'] }}<br />
                                {{ $shippingAddress['street_address'] }}<br />
                                {{ $shippingAddress['city'] }}, {{ $shippingAddress['postal_code'] }}<br />
                                {{ $shippingAddress['phone'] }}</span>
                            @endif
                        </td>

                    </tr>
                </table>
                </div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <table class="table table-hover" width="100%" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
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
                        <td class="col-sm-1 col-md-1">
                            <a href="{{$orderProduct['product']->product_link}}"> 
                                @if($orderProduct['product']['image'])
                                    <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" style="max-width: none; width: 10%">
                                @else
                                    <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" style="max-width: none; width: 10%">
                                @endif
                            </a>
                        </td>
                        <td>
                            <h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
                            <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?></h5>
                        </td>    
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        {{ $orderProduct['quantity'] }}
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format(str_ireplace('.','.',$orderProduct['price']), 2, ',',' ') }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong>{{ number_format(str_ireplace('.','.',$orderProduct['shipping_amount']),2 , ',',' ')}} kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>
                        @php
                            $total_amount = ($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'];

                            $total_amount_tbl = number_format(str_ireplace('.','.',$total_amount), 2, ',',' ');
                        @endphp
                        {{ $total_amount_tbl }} kr</strong></td>
                    </tr>
                  @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_subtotal')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format(str_ireplace('.','.',$subTotal),2 , ',',' ')}} kr</strong></h5></td>
                    </tr>
                    <tr>
                    <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_shipping')}}</h5></td>
                        <td class="text-right"><h5><strong>{{number_format(str_ireplace('.','.',$shippingTotal),2, ',',' ')}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td colspan="2"> 
                        <span style="font-size:16px;">
                            {{ __('messages.txt_seller')}} : <a href="{{$seller_link}}">{{ $seller_name }}</a><br />
                            <?php //echo $order['payment_status'];exit;
                            $payment_status = "";
                            if($order['payment_status']=="Pending"){
                                 $payment_status =trans("users.pending_order_status");
                            } else if($payment_status=="PAID"){
                                $payment_status = trans("users.paid_payment_status");
                            }else if($payment_status=="CANCELLED"){
                                $payment_status = trans("users.cancelled_order_status");
                            }else{
                                $payment_status = $order['payment_status'];
                            }

               


                            if($order['order_status']=="PENDING"){
                                 $order_status =trans("users.pending_order_status");
                            }else if($order['order_status']=="SHIPPED"){
                                 $order_status = trans("users.shipped_order_status");
                            }else if($order['order_status']=="CANCELLED"){
                                 $order_status = trans("users.cancelled_order_status");
                            }else{
                                 $order_status = $order['order_status'];
                            }

                            ?>
                            {{ __('messages.txt_payment_status')}} : {{ $payment_status }} <br />
                            {{ __('messages.txt_order_status')}} : {{ $order_status }} 
                        </span> 
                        </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>{{ __('lang.shopping_cart_total')}}</h3></td>
                        <td class="text-right"><h5><strong>{{number_format(str_ireplace('.','.',$Total),2, ',',' ')}} kr</strong></h5></td>
                    </tr>
                @endif  
                </tbody>
            </table>    
        </div>
    </div>
</div>

</body>
</html>

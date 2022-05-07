  @extends('Admin.layout.template')
  @section('middlecontent')
<div class="section-body">
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
                              
                                <h5> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?></h5>
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        <span id="quantity_{{ $orderProduct['id'] }}" > {{ $orderProduct['quantity'] }} </span>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>
                            @php
                            $product_price = swedishCurrencyFormat($orderProduct['product']->price);
                            @endphp
                        {{ $product_price }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right"><strong>
                             @php
                            $shipping_amount = swedishCurrencyFormat($orderProduct['shipping_amount']);
                            @endphp
                        {{ $shipping_amount }} kr</strong></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>
                             @php
                            $total_amount = ($orderProduct['product']->price * $orderProduct['quantity']) + $orderProduct['shipping_amount'];

                             $total_amount_tbl = swedishCurrencyFormat($total_amount);
                        @endphp

                            {{ $total_amount_tbl}} kr</strong></td>
                    </tr>
                  @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_subtotal')}}</h5></td>
                        <td class="text-right"><h5><strong>
                            @php
                            $subTotal = swedishCurrencyFormat($subTotal);
                            @endphp
                        {{$subTotal}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h5>{{ __('lang.shopping_cart_shipping')}}</h5></td>
                        <td class="text-right"><h5><strong>
                            @php
                            $shippingTotal = swedishCurrencyFormat($shippingTotal);
                            @endphp
                        {{$shippingTotal}} kr</strong></h5></td>
                    </tr>
                    <tr>
                        <td> 
                        <span style="font-size:16px;">
                            {{ __('messages.txt_seller')}} : <a href="{{$seller_link}}">{{ $seller_name }}</a><br />
                         
                            <?php 
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
                        <td class="text-right"><h4><strong>
                        @php
                            $Total = swedishCurrencyFormat($Total);
                        @endphp

                        {{$Total}} kr</strong></h4></td>
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
            <button type="button" class="btn btn-danger" style="font-size:18px;" onclick="printDiv();">{{ __('messages.txt_order_details_print')}} <span class="glyphicon glyphicon-print"></span></button>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
</div>
</div>

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

    if($("#order_status").length)
    {
        $("#order_status").change(function()
        {
            var order_status = $(this).val();
            var order_id = "{{ $order['id'] }}";
            
            $.confirm({
                title: js_confirm_msg,
                content: "{{ __('lang.order_status_confirm')}}",
                type: 'orange',
                typeAnimated: true,
                columnClass: 'medium',
                icon: 'fas fa-exclamation-triangle',
                buttons: {
                    ok: function () 
                    {
                        $(".loader").show();

                        $.ajax({
                        url:siteUrl+"/change-order-status",
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        type: 'post',
                        data : {'order_status': order_status, 'order_id' : order_id},
                        success:function(data)
                        {
                            $(".loader").hide();
                            var responseObj = $.parseJSON(data);
                            if(responseObj.status == 1)
                            {
                                showSuccessMessage(responseObj.msg);
                            }
                            else
                            {
                                if(responseObj.is_login_err == 0)
                                {
                                    showErrorMessage(responseObj.msg);
                                }
                                else
                                {
                                    showErrorMessage(responseObj.msg,'/front-login');
                                }
                            }

                        }
                        });
                    },
                    Avbryt: function () {
                        
                    },
                }
            });
        });
    }
</script>
@endsection('middlecontent')

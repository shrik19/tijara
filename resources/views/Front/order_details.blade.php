<?php 
if (@$_GET['print'])  
{
?>
	@include('Front.layout.header');

<?php } ?>

    <div class="row">
        <?php /*@if($is_seller==1)
        <div class="col-md-2 tijara-sidebar">
          @include ('Front.layout.sidebar_menu')
        </div>
        <div class="col-md-10 tijara-content printdiv">
        <div class="seller_info">
          @else
          <div class="col-md-12 tijara-content printdiv">
        @endif */?>
        <div class="col-md-12 tijara-content printdiv">
            <div class="row ">
                <div class="col-md-12">

                <h2>{{ __('messages.txt_order_details')}} - #{{ $order['id'] }}</h2>
                <hr class="heading_line"/>
                </div>
            </div>
            <div class="row seller_mid_cont">
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
                    <h4><strong>{{ __('messages.txt_shipping_address')}}</strong></h4>
                    <span style="font-size:16px;">{{ $shippingAddress['given_name'] }} {{ $shippingAddress['family_name'] }}<br />
                    {{ $shippingAddress['email'] }}<br />
                    {{ $shippingAddress['street_address'] }}<br />
                    {{ $shippingAddress['city'] }}, {{ $shippingAddress['postal_code'] }}<br />
                    {{ $shippingAddress['phone'] }}</span>
                </div>
                @endif
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <table class="table table-hover shopping_cart" style="margin-bottom:60px;">
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
                            <a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->product_link}}"> 
                            @if($orderProduct['product']['image'])
                              <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object show-cart-product" style="width: 72px; height: 72px;padding: 1px;">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;padding: 1px;">
                            @endif
                              
                            </a>
                            <div class="media-body"  style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading product_sorting_filter_option">@if($orderProduct['product']->is_deleted !=1)<a href="{{$orderProduct['product']->product_link}} ">{{ $orderProduct['product']->title }}</a>@else {{ $orderProduct['product']->title }} @endif </h4>
                                <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?></h5>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                        <span id="quantity_{{ $orderProduct['id'] }}" > {{ $orderProduct['quantity'] }} </span>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right">
                            <p class="product_sorting_filter_option">
   
                        @php                          
                         $order_product_amount_tbl = swedishCurrencyFormat($orderProduct['price']);
                        @endphp
                                {{$order_product_amount_tbl}} kr
                            
                            </p>
                        </td>
                        <td class="col-sm-1 col-md-1 text-right">
                            <p class="product_sorting_filter_option">
                        <?php /* 
                                    $shipping_amount = str_split(strrev($orderProduct['shipping_amount']), 3);
                                    $shipping_tbl = strrev(implode(" ", $shipping_amount));
                                    $shipping_tbl = $shipping_tbl.",00";
                             
                                  */
                                                        
                        $shipping_tbl = swedishCurrencyFormat($orderProduct['shipping_amount']);
                        ?>
                                {{$shipping_tbl}} kr
                            </p>
                        </td>
                        <td class="col-sm-2 col-md-2 text-right">
                            <p class="product_sorting_filter_option">
                                <?php /*
                                    $total_product_amount = str_split(strrev(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount']), 3);
                                    $total_product_amount_tbl = strrev(implode(" ", $total_product_amount));
                                    $total_product_amount_tbl = $total_product_amount_tbl.",00";
                                */
    
                                   $total_product_amount = ($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'];                             
                                  $total_product_amount_tbl = swedishCurrencyFormat($total_product_amount);
                               ?>
                                {{$total_product_amount_tbl}} kr
                                
                            </p>
                        </td>
                    </tr>
                  @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><p class="product_sorting_filter_option">{{ __('lang.shopping_cart_subtotal')}}</p></td>
                        <td class="text-right">
                            <p class="product_sorting_filter_option">
                            <?php /*
                                    $subTotal_amount = str_split(strrev($subTotal), 3);
                                    $subTotal_tbl = strrev(implode(" ", $subTotal_amount));
                                    $subTotal_tbl = $subTotal_tbl.",00";
                                */
                              
                                  $subTotal_tbl = swedishCurrencyFormat($subTotal);
                               ?>
                                {{$subTotal_tbl}} kr
                            </p>
                    </td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><p class="product_sorting_filter_option">{{ __('lang.shopping_cart_shipping')}}</p></td>
                        <td class="text-right">
                            <p class="product_sorting_filter_option">
                                <?php /*
                                    $shippingTotal_amount = str_split(strrev($shippingTotal), 3);
                                    $shippingTotal_tbl = strrev(implode(" ", $shippingTotal_amount));
                                    $shippingTotal_tbl = $shippingTotal_tbl.",00";
                                */                               
                                  $shippingTotal_tbl = swedishCurrencyFormat($shippingTotal);
                                ?>
                                {{$shippingTotal_tbl}} kr
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                        <span style="font-size:16px;">
                            {{ __('messages.txt_seller')}} : <a href="{{$seller_link}}">{{ $seller_name }}</a><br />
                            <?php //echo $order['payment_status'];exit;
                            $payment_status = "";
                            if($order['payment_status']=="Pending"){
                                 $payment_status =trans("users.pending_order_status");
                            }else{
                                $payment_status=$order['payment_status'];
                            }


                              if($order['payment_status']=="Pending"){
                                   $payment_status =trans("users.pending_order_status");
                              }else if($order['payment_status']=="PAID" || $payment_status=="CAPTURED" || $order['payment_status']=="checkout_complete"){
                                   $payment_status = trans("users.paid_payment_status");
                              }else if($order['payment_status']=="CANCELLED"){
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
                            {{ __('messages.txt_payment_status')}} :{{$payment_status}} <br />
                            {{ __('messages.txt_order_status')}} : 
                            @if($is_seller || $is_buyer_order) 
                            <select name="order_status" id="order_status" onchange="change_order_status(<?php echo $order['id']; ?>)" order_id="{{$order['id']}}" class="form-control" style="width: 50%;display: inline-block;">
                                <option value="PENDING" @if($order['order_status'] == 'PENDING') selected="selected" @endif style="background-color: red;">{{ __("users.pending_order_status")}}</option>
                                <option value="SHIPPED" @if($order['order_status'] == 'SHIPPED') selected="selected" @endif style="background-color: green;">{{ __("users.shipped_order_status")}}</option>
                                <option value="CANCELLED" @if($order['order_status'] == 'CANCELLED') selected="selected" @endif style="background-color: green;">{{ __("users.cancelled_order_status")}}</option>

                            </select> 
                            @else 
                                {{ $order_status }}
                            @endif
                        </span> 
                        </td>
                        <td>   </td>
                        <td>   </td>
                        <td><p class="product_sorting_filter_option">{{ __('lang.shopping_cart_total')}}</p></td>
                        <td class="text-right">
                            <p class="product_sorting_filter_option">
                                @php 
                                    $Total_amount = str_split(strrev($Total), 3);
                                    $Total_tbl = strrev(implode(" ", $Total_amount));
                                    $Total_tbl = $Total_tbl.",00";
                                @endphp
                                {{$Total_tbl}} kr
                            </p>
                        </td>
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
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn print_btn debg_color" style="font-size:18px;" onclick="printDiv();">{{ __('messages.txt_order_details_print')}} <span class="glyphicon glyphicon-print"></span></button>
              <button type="button" class="btn download_btn debg_color" style="font-size:18px;" onclick='downloadPdf("{{route('frontDownloadOrderDetails', base64_encode($order['id']))}}");'>Download <span class="fas fa-file-download"></span></button>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
</div>

<?php if (@$_GET['print'])  {
	?>
<script type="text/javascript">
	
	window.print();
	
</script>

<?php } ?>

@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<div class="mid-section p_155" style="background: #dddddd;    margin-bottom: 0px;">
<div class="container-fluid">
<div class="container-inner-section-1">
<div class="">
<div class="row"> 
<div class="seller_info shopping_cart_page border-none">
    <div class="card">
        <div class="card-header row">
            <div class="col-md-6 p-m-0 pl-0">
            <h2 class="page_heading m-l-12">{{ __('lang.shopping_cart')}}</h2> 
            </div> 
            <div class="col-md-6 text-right">
      <!--      <button type="button" class="btn buy_now_btn debg_color" onclick="location.href='{{route('frontHome')}}';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> {{ __('lang.shopping_cart_continue')}}
                        </button> -->
            </div>     
        </div>
    </div>
<div class="seller_mid_cont"  style="margin-top: 20px;">
<section class="product_details_section-1">
<div class="loader"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 p-m-0 pl-0">
        <div class="row">
            <!-- <div class="col-md-6">
              <h2>{{ __('lang.shopping_cart')}}</h2>
              <hr class="heading_line"/>
            </div> -->
            <!-- <div class="col-md-6 text-right">
           <button type="button" class="btn buy_now_btn debg_color" onclick="location.href='{{route('frontHome')}}';">
                            <span class="glyphicon glyphicon-shopping-cart"></span> {{ __('lang.shopping_cart_continue')}}
                        </button>
            </div> -->
          </div>
            <table class="table table-hover shopping_cart" style="margin-bottom:60px;">
                <thead>
                    <tr>
                         <th>{{ __('users.butik_btn')}}</th>
                        <th>{{ __('lang.shopping_cart_product')}}</th>
                        <th>{{ __('lang.shopping_cart_quantity')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_price')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_shipping')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_total')}}</th>
                        <th class="table_blank"> </th>
                    </tr>
                </thead>
                <tbody>
                  @if(!empty($details))
                  
                  @foreach($details as $orderId => $tmpOrderProduct)
                  @if(!empty($tmpOrderProduct))
                   @php   $inc=1; @endphp
                    @foreach($tmpOrderProduct['details'] as $orderProduct)
               
                    <tr>
                        <td class="col-sm-4 col-md-4 p-m-0">
                            @if($inc==1)
                        <div class="media cart-store-sec bg-white">
                            <a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->product_link}}"> 
                            @if(isset($orderProduct['sellerLogo']) && !empty($orderProduct['sellerLogo']))
                              <img src="{{url('/')}}/uploads/Seller/resized/{{$orderProduct['sellerLogo']}}" class="media-object seller-show-icon">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object seller-show-icon">
                            @endif
                                                      
                            </a>
                             
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">

                                <h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->seller_link}}">{{ $orderProduct['product']->store_name }}</a></h4>
                                <!-- <h5 class="media-heading"> {{$orderProduct['variant_attribute_id']}} </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                            
                        </div>@endif</td>
                        <span>
                        <td class="col-sm-4 col-md-4 bg-white">
                        <div class="media">
                            <a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->product_link}}"> 
                            @if($orderProduct['product']['image'])
                              <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object show-cart-product" style="width: 72px; height: 72px;padding: 1px;">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object show-cart-product" style="width: 72px; height: 72px;padding: 1px;">
                            @endif
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
                                <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?> </h5>
                                <?php /*
                                @if($orderProduct['product']->is_pick_from_store ==1)
                                <h4  class="media-heading product_sorting_filter_option"> 
                                    {{ __('users.pick_up_address')}} : {{@$orderProduct['product']->store_pick_address}}
                                </h4>
                                @endif
                                */?>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1 bg-white tijara_quantity" style="text-align: center">
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
                        <td class="col-sm-2 col-md-2 text-right bg-white"><p class="product_sorting_filter_option">
                             @php 
                                $price_array_tbl = str_split(strrev($orderProduct['price']), 3);
                                $price_tbl = strrev(implode(" ", $price_array_tbl));
                                $price_tbl = $price_tbl.",00";
                            @endphp
                               {{ $price_tbl }} kr
                        <?php /*{{ number_format($orderProduct['price'],2) }} kr */ ?></p></td>
                        <td class="col-sm-1 col-md-1 text-right bg-white"><p  class="product_sorting_filter_option">
                            @php 
                                $shipping_array_tbl = str_split(strrev($orderProduct['shipping_amount']), 3);
                                $shipping_tbl = strrev(implode(" ", $shipping_array_tbl));
                                $shipping_tbl = $shipping_tbl.",00";
                            @endphp
                            {{$shipping_tbl}} kr
                       <?php /*  {{ number_format($orderProduct['shipping_amount'],2)}} kr */ ?></p></td>
                        <td class="col-sm-2 col-md-2 text-right bg-white">
                            <p  class="product_sorting_filter_option">
                                @php 
                                    $amt_total =(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount']);
                                    $total_tbl = str_split(strrev($amt_total), 3);
                                    $total_price_tbl = strrev(implode(" ", $total_tbl));
                                    $total_price_tbl = $total_price_tbl.",00";
                                @endphp
                               {{ $total_price_tbl }} kr
                               
                                <?php /*{{ number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)}} kr */ ?>
                            </p>
                        </td>
                        <td class="col-sm-1 col-md-1 text-right bg-white">
                        <a href="javascript:void(0);" style="color:red;" onclick="removeCartProduct('{{ $orderProduct['id'] }}')" title="Remove"><i class="fas fa-trash"></i></button>
                        <!-- <button type="button" class="btn btn-danger" onclick="removeCartProduct('{{ $orderProduct['id'] }}')">
                            <span class="glyphicon glyphicon-remove"></span> Remove
                        </button> -->
                      </td>
                    </tr>
                    @php $inc++; @endphp
                  @endforeach
                    <tr class="ttl-sec">
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white bbvbvb"><h5  class="product_sorting_filter_option">{{ __('lang.shopping_cart_subtotal')}}</h5></td>
                        <td class="text-right bg-white"><h5 class="product_sorting_filter_option">
                            @php 
                                $price_subTotal_array = str_split(strrev($tmpOrderProduct['subTotal']), 3);
                                $subTotal = strrev(implode(" ", $price_subTotal_array));
                                $subTotal = $subTotal.",00";
                            @endphp
                            {{ $subTotal }} kr
                        <?php /* {{number_format($tmpOrderProduct['subTotal'],2)}} kr */?></h5></td>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank" >   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white"><h5 class="product_sorting_filter_option">{{ __('lang.shopping_cart_shipping')}}</h5></td>
                        <td class="text-right bg-white"><h5 class="product_sorting_filter_option">

                             @php  
                                $price_shipping_array = str_split(strrev($tmpOrderProduct['shippingTotal']), 3);
                                $shippingTotal = strrev(implode(" ", $price_shipping_array));
                                $shippingTotal = $shippingTotal.",00";
                            @endphp
                            {{$shippingTotal}} kr
                            <?php /* {{number_format($tmpOrderProduct['shippingTotal'],2)}} kr */?></h5></td>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="bg-white"><h4 class="cart_total_css">{{ __('lang.shopping_cart_total')}}</h4></td>
                        <td class="text-right bg-white"><h4 class="cart_total_css">
                            @php 
                                $price_array = str_split(strrev($tmpOrderProduct['Total']), 3);
                                $price_nice = strrev(implode(" ", $price_array));
                                $price_nice = $price_nice.",00";
                            @endphp
                        {{ $price_nice }} kr</h4></td>
                        <?php /*{{number_format($tmpOrderProduct['Total'],2)}} kr */?>
						<td class="bg-white">   </td>
                    </tr>
                    <tr>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td class="table_blank">   </td>
                        <td>
                        <button type="button" class="btn buy_now_btn debg_color" style="font-size:18px;" @if($tmpOrderProduct['is_buyer_product']) onclick="location.href='{{route('frontShowBuyerCheckout' , ['id' => base64_encode($orderId)])}}'" @else  onclick="location.href='{{route('frontShowPaymentOptions', ['id' => base64_encode($orderId)])}}'" @endif>
                        {{ __('lang.shopping_cart_checkout')}} <span class="glyphicon glyphicon-play"></span>
                        </button></td>
						<td>   </td>
                    </tr>
                    <tr><td colspan="6" style="border:none;line-height:60px;">&nbsp;</td></tr>
                    @endif
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
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection

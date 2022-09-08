@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<div class="mid-section p_155" style="background: #dddddd;min-height:62vh;   margin-bottom: 0px;">
<div class="container-fluid">
<div class="container-inner-section-1">

<div>
<div> 
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
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-sm-12 col-md-12 p-m-0 pl-0">
		
			 @if(!empty($details))
                  @foreach($details as $orderId => $tmpOrderProduct)
                  @if(!empty($tmpOrderProduct))
                   @php   $inc=1; @endphp
                    @foreach($tmpOrderProduct['details'] as $orderProduct)
						
							@if($inc==1)
							<div class="tj-cart">
							<div class="tj-cart-left">
								<span class="tj-colhead">{{ __('users.butik_btn')}}</span> 
								<div class="cart-store-sec bg-white">
									<a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->seller_link}}">
										@if(isset($orderProduct['sellerLogo']) && !empty($orderProduct['sellerLogo']))
										  <img src="{{url('/')}}/uploads/Seller/resized/{{$orderProduct['sellerLogo']}}" class="media-object seller-show-icon">
										@else
										  <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object seller-show-icon">
										@endif
									</a>
									<div class="media-body" style="padding-left:10px;padding-top:10px;">
										<h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->seller_link}}">{{ $orderProduct['product']->store_name }}</a></h4>
									</div>
								</div>
							</div>
							
							<div class="tj-cart-right">
								<div class="tj-cart-row tj-cart-row-head">
									<div class="tj-cart-product"><span class="tj-colhead">{{ __('lang.shopping_cart_product')}}</span></div>
									<div class="tj-cart-qty"><span class="tj-colhead">{{ __('lang.shopping_cart_quantity')}}</span></div>
									<div class="tj-cart-pris text-right"><span class="tj-colhead">{{ __('lang.shopping_cart_price')}}</span></div>
									<div class="tj-cart-frakt text-right"><span class="tj-colhead">{{ __('lang.shopping_cart_shipping')}}</span></div>
									<div class="tj-cart-total text-right"><span class="tj-colhead">{{ __('lang.shopping_cart_total')}}</span></div>
									<div class="tj-cart-action text-right"></div>
								</div>
								@endif
								<div class="tj-cart-row">
									<div class="tj-cart-product">
										<div class="media">
											<a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->product_link}}"> 
												@if($orderProduct['product']['image'])
												  <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object show-cart-product" >
												@else
												  <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object show-cart-product" >
												@endif
																		  
											</a>
											<div class="media-body">
												<h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
												 <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?> </h5>
												 <p class="product_sorting_filter_option visible-xs"> @php                                 
													$price_tbl = swedishCurrencyFormat($orderProduct['price']);
													@endphp {{ $price_tbl }} kr
												</p>
											</div>
										</div>									
									</div>
									<div class="tj-cart-qty">
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
									</div>
									<div class="tj-cart-pris text-right hidden-xs">
										<p class="product_sorting_filter_option"> @php                                 
											$price_tbl = swedishCurrencyFormat($orderProduct['price']);
											@endphp {{ $price_tbl }} kr
										</p>
									</div>
									<div class="tj-cart-frakt text-right hidden-xs">
										<p class="product_sorting_filter_option"> @php 
											$shipping_array_tbl = str_split(strrev($orderProduct['shipping_amount']), 3);
											$shipping_tbl = strrev(implode(" ", $shipping_array_tbl));
											$shipping_tbl = $shipping_tbl.",00";
										@endphp
										{{$shipping_tbl}} kr</p>
									</div>
									<div class="tj-cart-total text-right">
										<p  class="product_sorting_filter_option p-l-8">
										@php 
											$amt_total =(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount']); $total_price_tbl = swedishCurrencyFormat($amt_total);

										@endphp
									   {{ $total_price_tbl }} kr
									   
										<?php /*
										  //$total_tbl = str_split(strrev($amt_total), 3);
											//$total_price_tbl = strrev(implode(" ", $total_tbl));
										   // $total_price_tbl = $total_price_tbl.",00";
										{{ number_format(($orderProduct['price'] * $orderProduct['quantity']) + $orderProduct['shipping_amount'],2)}} kr */ ?>
									</p>
									</div>
									<div class="tj-cart-action text-right">
										<a href="javascript:void(0);" style="color:red;" onclick="removeCartProduct('{{ $orderProduct['id'] }}')" title="Remove"><i class="fas fa-trash"></i>
											<!-- <button type="button" class="btn btn-danger" onclick="removeCartProduct('435')">
												<span class="glyphicon glyphicon-remove"></span> Remove
											</button> -->
										  </a>
									</div>
								</div>
							
						
						@php $inc++; @endphp
                  @endforeach
				  </div>
		</div>
				 <div class="tj-cart-total-section">
					<div class="tj-cart-total-row">
						<span class="tj-cart-total-caption">{{ __('lang.shopping_cart_subtotal')}}</span>
						<span class="tj-cart-total-value"> @php 
                                $subTotal = swedishCurrencyFormat($tmpOrderProduct['subTotal']);
                            @endphp
                            {{ $subTotal }} kr</span>
					</div>
					<div class="tj-cart-total-row">
						<span class="tj-cart-total-caption">{{ __('lang.shopping_cart_shipping')}}</span>
						<span class="tj-cart-total-value"> @php  
                                $price_shipping_array = str_split(strrev($tmpOrderProduct['shippingTotal']), 3);
                                $shippingTotal = strrev(implode(" ", $price_shipping_array));
                                $shippingTotal = $shippingTotal.",00";
                            @endphp
                            {{$shippingTotal}} kr</span>
					</div>
					<div class="tj-cart-total-row">
						<span class="tj-cart-total-caption">{{ __('lang.shopping_cart_total')}}</span>
						<span class="tj-cart-total-value">@php 
                                $price_nice = swedishCurrencyFormat($tmpOrderProduct['Total']);
                            @endphp
                        {{ $price_nice }} kr</span>
					</div>
				</div>

				<div class="tj-cart-button">
					<button type="button" class="buy_now_btn" @if($tmpOrderProduct['is_buyer_product']) onclick="location.href='{{route('frontShowBuyerCheckout' , ['id' => base64_encode($orderId)])}}'" @else  onclick="location.href='{{route('frontShowPaymentOptions', ['id' => base64_encode($orderId)])}}'" @endif>{{ __('lang.shopping_cart_checkout')}} <span class="glyphicon glyphicon-play"></span></button>
				</div>
				 @endif
                    @endforeach
                    @else
						{{ __('lang.shopping_cart_no_records')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a>
					
					 @endif
                
            
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


<style type="text/css">
  .invalid-feedback {
    position: relative !important;
}
.debg_color{
    background-color: #03989e;
}
.login_btn {    text-decoration: none;
    font-size: 16px;
    color: #fff;
    padding: 6px 13px;
}
.invalid-feedback{
  color: red;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/azcustom.css">
<script type="text/javascript">
    var select_payment_option="{{ __('users.select_payment_option')}}";
</script>
  <div class="checkoutContainer">
  <div class="container-inner-section container">
  <div class="">       
      <div class="">
        <div class="card">
          <div class="card-header row">
            
          </div>
          <div class="card-body">
            <form method="POST" action="" class="needs-validation tijara-form" novalidate="">
              @csrf
              <div >
                <div class="col-md-12 checkout-back">
                  <span class="arrow-border"><i class="arrow left show_cart"></i></span><a href="{{route('frontShowCart')}}">&nbsp;{{ __('users.back_to_the_cart_btn')}} </a> 
                </div>
                <div class="col-md-12 checkoutHeader">
                  <div class="col-md-3">
                    <a class="navbar-brand tj-logo" href="{{url('/')}}"><img class="logo" src="{{url('/')}}/uploads/Images/{{$siteDetails->header_logo}}"/></a>
                  </div>
                  <div class="col-md-6">
                    <h1 class="checkoutHeading text-center">{{ __('users.checkout_cart_title')}}</h1>  
                  </div>  
                  <div class="col-md-3"></div>
                </div>        
              </div>

              <div class="rowlogin_box">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                <div class="checkoutPageRightBox tj-swishbox">
                  @if(!empty($orderDetails))
                  <div class="checkoutPageRightBoxHeader">
                    <input type="hidden" name="order_id" id="order_id" value="{{$orderId}}">
                    <h4> {{count($orderDetails[$orderId]['details'])}} {{ __('users.articles_title')}}</h4>
                  <a href="{{route('frontShowCart')}}" style="float: right;color: #000 !important;">{{ __('users.change_order_title')}}</a>
                  </div>
                  <div class="clerarfix"></div>
                  <hr class="hr_line"/> 
                    @foreach($orderDetails as $orderId => $tmpOrderProduct)
                      @if(!empty($tmpOrderProduct))<?php //echo "<pre>";print_r($tmpOrderProduct);exit;?>
                        @if(!empty($tmpOrderProduct['details']))
                        @foreach($tmpOrderProduct['details'] as $key=>$orderProduct)
                        
                        <div style="display: none" class="is_pick_from_store">
                        @if(!empty($orderProduct['product']['is_pick_from_store']) && $orderProduct['product']['is_pick_from_store']==1)
                        {{$orderProduct['product']['is_pick_from_store']}}
                        @else if(!empty($seller_data['is_pick_from_store']) && $seller_data['is_pick_from_store']==1)
                        {{$seller_data['is_pick_from_store']}}
                        @endif
                        </div>

                          <div class="row checkoutProducts">
                            <div class="col-xs-3">
                              <div class="tj-cart2-thumbnail">
                                @if(!empty($orderProduct['product']['image']))
                                   <img src="{{url('/')}}/uploads/ProductImages/productIcons/{{$orderProduct['product']['image']}}" class="media-object checkoutProductImg">
                                @else
                                  <img src="{{url('/')}}/uploads/ProductImages/productIcons/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                                @endif
                              </div>
                            </div>
                            <div class="col-xs-9 checkoutProductDetails">
                              <h4>{{$orderProduct['product']['title']}}</h4>
                              <h5 class="media-heading product_attribute_css"> <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['product']['variant_attribute_id']);?> </h5>
                              <p>{{ __('lang.shopping_cart_quantity')}}:  {{$orderProduct['product']['quantity']}}</p>
                                   @php 
                                        $price_array_tbl = str_split(strrev($orderProduct['price']), 3);
                                        $price_tbl = strrev(implode(" ", $price_array_tbl));
                                        $price_tbl = $price_tbl.",00";
                                    @endphp
                              <p>{{ __('lang.shopping_cart_price')}}: @if(!empty($price_tbl)) {{@$price_tbl}} kr @endif </p>
                              <p>{{ __('messages.make_payment_on_swish_number')}}:<span style="font-weight: bold;">{{$seller_swish_number}}</span></p>
                            </div>
                          </div>
                        @endforeach
                      @endif
                      @endif
                    @endforeach
                  @endif
                  <div class="clerarfix"></div>
                  <hr class="hr_line"/> 
                
                <div class="col-md-12">
                  <div class="checkoutAmountBorder">
                    <?php
                       /* $sub_total_tbl = str_split(strrev($orderDetails[$orderId]['subTotal']), 3);
                        $sub_total_tbl = strrev(implode(" ", $sub_total_tbl));
                        $sub_total_tbl = $sub_total_tbl.",00";*/
						$sub_total = $orderDetails[$orderId]['subTotal'];
                        $sub_total_tbl = swedishCurrencyFormat($sub_total);
                    ?>
                    <span>{{ __('lang.shopping_cart_subtotal')}}:</span>
                    <span style="float: right;"><?php echo $sub_total_tbl; ?> kr</span>
                  </div>
                  <div class="checkoutAmountBorder">
                    @php 
                        $shipping_total_tbl = str_split(strrev($orderDetails[$orderId]['shippingTotal']), 3);
                        $shipping_total_tbl = strrev(implode(" ", $shipping_total_tbl));
                        $shipping_total_tbl = $shipping_total_tbl.",00";
                    @endphp
                  <span>{{ __('lang.shopping_cart_shipping')}}:</span><span style="float: right;"><?php echo $shipping_total_tbl; ?> kr</span>
                </div>
                <div class="checkoutAmountBorder">
                  @php 
                    $total_tbl = str_split(strrev($orderDetails[$orderId]['Total']), 3);
                    $total_tbl = strrev(implode(" ", $total_tbl));
                    $total_tbl = $total_tbl.",00";
					$total_tbl = swedishCurrencyFormat($orderDetails[$orderId]['Total']);
                  @endphp
                  <span>{{ __('lang.shopping_cart_total')}}:</span><span style="float: right;"><?php echo $total_tbl; ?> kr</span>
                </div>
                </div>
                <div class="col-md-12 text-center" style="margin-top: 30px;">
                  <button type="button" id="makeSwishPayment" name="next" class="btn btn-black debg_color login_btn makeSwishPayment" value="{{ __('users.finish_btn')}}"/>{{ __('users.finish_btn')}}</button>
              </div>
                </div>
                 
              </div>
           
              </form>
          </div>
        </div>
        </div>
      </div>

  </div>
</div></div>
 <!-- /container -->
<script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
<script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>
<script src="{{url('/')}}/assets/front/js/jquery-confirm.min.js"></script>

<script type="text/javascript">
  $("#makeSwishPayment").click(function(){
    var order_id = $("#order_id").val();
    var amount = $("#order_amount").val();

     $.ajax({
         /* headers: {
          'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },*/
          url: "{{url('/')}}"+'/swish-number-payment',
          type: 'post',
          data:{'order_id':order_id, 'amount':amount,"_token": "{{ csrf_token() }}"},
          success: function(data){
           // console.log(data)
            if(data.payment_status=="Pending"){
              window.location = "{{ route('SwishNumberOrderplaced') }}";
            }
            /*console.log(data.payment_status)
            order placed successfully
            if(data.payment_status=="PAID"){
              window.location = "{{ route('SwishOrderSuccess') }}";
            }
            else if(data.payment_status=="ERROR"){
              window.location = "{{ route('SwishPaymentError') }}";
            }else if(data.payment_status=="CANCELLED"){
              window.location = "{{ route('SwishPaymentError') }}";
            }else if(data.payment_status=="DECLINED"){
              window.location = "{{ route('SwishPaymentError') }}";
            }*/
          }
        });
  });
       
</script>


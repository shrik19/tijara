
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
              <div>
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
                <div class="col-md-7" >
                  <div class="checkoutPageBox">
                    <div class="col-md-12"><h4>{{ __('messages.txt_billing_address')}}</h4></div>
                    <div class="checkout_billing_info">
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" class="form-control login_input " name="billing_given_name" id="billing_given_name" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('billing_given_name')) ?  old('billing_given_name') : $details->fname}}">
                          <span class="invalid-feedback" id="err_billing_given_name"></span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" class="form-control login_input " name="billing_family_name" id="billing_family_name" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('billing_family_name')) ?  old('billing_family_name') : $details->lname}}">
                          <span class="invalid-feedback" id="err_billing_family_name"></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="email" class="form-control login_input " name="billing_email" id="billing_email" placeholder="{{ __('users.email_label')}}" value="{{ (old('billing_email')) ?  old('billing_email') : $details->email}}">
                        <span class="invalid-feedback" id="err_billing_email"></span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="address" class="form-control login_input " name="billing_address" id="billing_address" placeholder="{{ __('users.address_label')}}" value="{{ (old('billing_address')) ?  old('billing_address') : $details->address}}">
                        <span class="invalid-feedback" id="err_billing_address"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                       <input type="city" class="form-control login_input " name="billing_city" id="billing_city" placeholder="{{ __('users.city_label')}}" value="{{ (old('billing_city')) ?  old('billing_city') : $details->city}}">
                        <span class="invalid-feedback" id="err_billing_city"></span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="postcode" class="form-control login_input " name="billing_postcode" id="billing_postcode" placeholder="{{ __('users.postal_code_label')}}" value="{{ (old('billing_postcode')) ?  old('billing_postcode') : $details->postcode}}">
                        <span class="invalid-feedback" id="err_billing_postcode"></span>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="phone_number" class="form-control login_input " name="billing_phone_number" id="billing_phone_number" placeholder="{{ __('users.phone_number_label')}}" value="{{ (old('billing_phone_number')) ?  old('billing_phone_number') : $details->phone_number}}">
                        <span class="invalid-feedback" id="err_billing_phone_number"></span>
                      </div> 
                    </div>
                  </div>

                  <!-- le -->
                  <div class="checkoutPageBox m-50">
                    <div class="col-md-12">
                      <h4>{{ __('messages.txt_shipping_address')}}
                        <div class="rememberContainer p-15">
                          <input type="checkbox" name="same_as_billing" id="same_as_billing" value="">
                          <span class="remember-text-checkout">Samma som fakturering</span>
                        </div>
                      </h4>
                    </div>
                    <div class="checkout_billing_info">
                      <div class="col-md-6">
                        <div class="form-group ">
                          <input type="text" class="form-control login_input " name="shipping_given_name" id="shipping_given_name" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('shipping_given_name')) ?  old('shipping_given_name') : $details->fname}}">
                          <span class="invalid-feedback" id="err_shipping_given_name"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" class="form-control login_input " name="shipping_family_name" id="shipping_family_name" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('shipping_family_name')) ?  old('shipping_family_name') : $details->lname}}">
                          <span class="invalid-feedback" id="err_shipping_family_name"></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="email" class="form-control login_input " name="shipping_email" id="shipping_email" placeholder="{{ __('users.email_label')}}" value="{{ (old('shipping_email')) ?  old('shipping_email') : $details->email}}">
                        <span class="invalid-feedback" id="err_shipping_email"></span>
                      </div>
                    </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="address" class="form-control login_input " name="shipping_address" id="shipping_address"
                      placeholder="{{ __('users.address_label')}}" 
                      value="{{ (old('shipping_address')) ?  old('shipping_address') : $details->address}}">
                      <span class="invalid-feedback" id="err_shipping_address"></span>
                    </div>
                 
                  </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="city" class="form-control login_input " name="shipping_city" id="shipping_city"
                          placeholder="{{ __('users.city_label')}}" 
                          value="{{ (old('shipping_city')) ?  old('shipping_city') : $details->city}}">
                          <span class="invalid-feedback" id="err_shipping_city"></span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="postcode" class="form-control login_input " name="shipping_postcode" id="shipping_postcode"
                          placeholder="{{ __('users.postal_code_label')}}" 
                          value="{{ (old('shipping_postcode')) ?  old('shipping_postcode') : $details->postcode}}">
                          <span class="invalid-feedback" id="err_shipping_postcode"></span>
                        </div>
                      </div>

                    <div class="col-md-12">
                     <div class="form-group">
                      <input type="phone_number" class="form-control login_input " name="shipping_phone_number" id="shipping_phone_number"
                      placeholder="{{ __('users.phone_number_label')}}" 
                      value="{{ (old('shipping_phone_number')) ?  old('shipping_phone_number') : $details->phone_number}}">
                      <span class="invalid-feedback" id="err_shipping_phone_number"></span>
                    </div>
                      
                     </div>
                    </div>
                      <!-- 3rd -->
                      <div class="checkoutPageBox m-50">
                      <div class="col-md-12">
                         <div class="form-group">
                         
                           <h4>{{ __('messages.delivery_label')}}</h4>
                            <?php
//echo "<pre>";print_r($orderDetails[$orderId]['details']);exit;
                            $store_pick_address ='';
                             for($i=0;$i<count($orderDetails[$orderId]['details']);$i++){ 
                             /* if($orderDetails[$orderId]['details'][$i]['product']->is_pick_from_store==1){
                                      if(!empty($orderDetails[$orderId]['details'][$i]['product']->store_pick_address)){
                                        $store_pick_address = $orderDetails[$orderId]['details'][$i]['product']->store_pick_address;
                                      }
                                    }else*/
                                    if(!empty($seller_data['store_pick_address']) && $seller_data['is_pick_from_store'] == 1){
                                        $store_pick_address = $seller_data['store_pick_address']; 
                                      
                                    }else if($orderDetails[$orderId]['details'][$i]['product']->is_pick_from_store==1){
                                      if($store_pick_address==''){
                                        if(!empty($orderDetails[$orderId]['details'][$i]['product']->store_pick_address)){
                                          $store_pick_address = $orderDetails[$orderId]['details'][$i]['product']->store_pick_address;
                                        }
                                      }
                                    }

                                   /* else{
                                      $store_pick_address ='';
                                    }*/

                                   

                            }
//echo "in2". $store_pick_address;exit;
                            ?>

                            @if(!empty(@$store_pick_address) && @$store_pick_address!='')
                              <div class="pick_input" > 
                                <div class="row">
                                    <div class="col-md-6 col-xs-8">                           
                                        <input type="radio" name="shipping_amount" class="radio-button-shipping" value="0"> <span style="margin-left:10px;">{{ __('users.pick_from_store')}}</span>
                                       <!--   <p style="margin-left:24px;font-size: 12px;
    color: #999;">{{ __('users.to_delivery_address')}} --</p> -->
    <span class="store_address store-pickup" title="{{@$store_pick_address}}">{{ @$store_pick_address}} </span> 
                                    </div>
                                    <div class="col-md-6 col-xs-4">                                       
                                       <!--  <span class="store_address" title="{{@$store_pick_address}}">0.00 kr </span> -->
                                          <span class="product_shipping_charges1" class="checkout-shipping-charges">0.00 kr</span>  
                                      </div>
                                </div>
                             </div>
                            @endif



                           <div class="pick_up_fromt_store">   
                              <div class="row">
                                <div class="col-md-6">
                                   <?php  
                                    if($orderDetails[$orderId]['details'][0]['product']->is_pick_from_store==1){
                                      if(!empty($orderDetails[$orderId]['details'][0]['product']->store_pick_address)){
                                        $store_pick_address = $orderDetails[$orderId]['details'][0]['product']->store_pick_address;
                                      }
                                    }elseif(!empty($seller_data['store_pick_address']) && $seller_data['is_pick_from_store'] == 1){
                                        $store_pick_address = $seller_data['store_pick_address']; 
                                      
                                    }else{
                                      $store_pick_address ='';
                                    }
                                  ?>
                                  <input type="radio" name="pick_from_store" value="1"> <span style="margin-left:10px;font-size: 12px;color: #999;">{{$store_pick_address}}<!-- {{ __('users.pick_from_store')}} --></span>
                                 
                                  <p  style="margin-left:26px;">{{ @$store_pick_address}} </p>
                                </div>
                                <div class="col-md-6" style="margin-top: 8px;">
                                  <span>0.00kr</span> 
                                </div>
                              </div>                                   
                           </div>
               
                           <div class="pick_input"> 
                                <div class="row">
                                    <div class="col-md-6 col-xs-8">                           
                                        <input type="radio" name="shipping_amount" class="radio-button-shipping" value="1"> <span style="margin-left:10px;margin-top: 8px;">{{ __('users.shipping_btn')}}</span>
                                         <p style="margin-left:24px;font-size: 12px;color: #999;">{{ __('users.to_delivery_address')}}</p>
                                    </div>
                                    <div class="col-md-6 col-xs-4 mt-8 m-m-t-15 text-right">
                                    
                                      @php

                                      if($orderDetails[$orderId]['shippingTotal']==0 && $orderDetails[$orderId]['details'][0]['product']->shipping_charges==''){
                                         $shipping_total_tbl = "0,00";
                                         $shipping_total=0;
                                      }else{
                                        if($orderDetails[$orderId]['shippingTotal']==0){
                                        echo "out";exit;
                                          if($orderDetails[$orderId]['details'][0]['product']->shipping_charges==''){
                                           $shipping_total = $shipping_total_tbl=$shipping_total_amt=0;
                                          }else{
                                             $shipping_total = $orderDetails[$orderId]['details'][0]['product']->shipping_charges;
                                          $shipping_total_tbl = str_split(strrev(round($orderDetails[$orderId]['details'][0]['product']->shipping_charges)), 3);
                                          }
                                        
                                        }else{
                                     
                                         $shipping_total = $orderDetails[$orderId]['shippingTotal'];
                                          $shipping_total_tbl = str_split(strrev(round($orderDetails[$orderId]['shippingTotal'])), 3);

                                        }
                                        /* $shipping_total_tbl = str_split(strrev($orderDetails[$orderId]['shippingTotal']), 3); */

                                          $shipping_total_amt = strrev(implode(" ", $shipping_total_tbl));
                                          $shipping_total_tbl = $shipping_total_amt.",00";
                                      }
                                        
                                      @endphp
                                        <span class="product_shipping_charges">@if(!empty($shipping_total_tbl)) {{$shipping_total_tbl}} kr @endif</span> 
                                        
                                        <input type="hidden" name="hid_product_shipp_charges" class="hid_product_shipp_charges" value="{{@$shipping_total}}">
                                      </div>
                                </div>
                           </div>
                           <span class="invalid-feedback col-md-10" id="select_shipping" style="    margin-left: -12px;"></span>
                          </div>
                           
                        </div>
                      </div>
                     <!-- 4rd -->
                       <div class="checkoutPageBox m-50">
                      <div class="col-md-12 checkout_billing_info">
                         <div class="form-group">
                           <h4>{{ __('users.payment_btn')}}</h4>  
                           @foreach($payment_options as $p)
                             @if($p != 'swish')
                           <div class="pick_input">
                            @if($p == 'klarna')
                            <img class="pick-img" src="{{url('/')}}/uploads/Images/klarna_logo_pink.png" width="90"  style="">
                            @endif
                            @if($p != 'swish')
                             <?php /*  <img src="{{url('/')}}/uploads/Images/swish-payment-logo.png" width="90" height="70" style="float: right; margin-top: -2px;border-radius: 5px;height: 41px;"> */?>
                            @endif
                            @if($p == 'swish_number')
                              <img src="{{url('/')}}/uploads/Images/swish-payment-logo.png" width="90"  class="swish_checkout_logo">
                            @endif
                            @if($p == 'Kortbetalning')
                              <img src="{{url('/')}}/uploads/Images/stripe-payment-logo.png" width="100"  class="stripe_checkout_logo">
                            @endif
                          
                             <input type="radio" name="payment_method" class="{{$p}} payment_radio" value="{{$p}}"> <span class="shopping_pay_option">@if($p == 'swish_number') Swish @else {{ucfirst(@$p)}} @endif</span>
                           
                           </div>
                              @endif
                           @endforeach
                           <!-- <span>
                           <input type="radio" name="payment_method" class="swish_number payment_radio" value="swish_number"> <span style="margin-left:10px;"> <input type="phone_number" class="form-control login_input " name="swish_number" id="shipping_phone_number"
                          placeholder="swish number" 
                          value=""></span> -->
                         
                         <!--  <span class="invalid-feedback" id="err_shipping_phone_number"></span> -->
                          <span class="invalid-feedback" id="select_payment_option_err"></span>
                        </div>               
                      </div>
                  </div>
                    
                </div>

                <div class="col-md-5">
                <div class="checkoutPageRightBox">
                  @if(!empty($orderDetails))
                  <div class="checkoutPageRightBoxHeader">
                    <h4> {{count($orderDetails[$orderId]['details'])}} {{ __('users.articles_title')}}</h4>
                  <a href="{{route('frontShowCart')}}" style="float: right;color: #000 !important;">{{ __('users.change_order_title')}}</a>
                  </div>
                  <div class="clerarfix"></div>
                  <hr class="hr_line"/> 
                    @foreach($orderDetails as $orderId => $tmpOrderProduct)
                      @if(!empty($tmpOrderProduct))
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
                            <?php /* 
                                      $price_array_tbl = str_split(strrev($orderProduct['price']), 3);
                                      $price_tbl = strrev(implode(" ", $price_array_tbl));
                                      $price_tbl = $price_tbl.",00";
                                      */?>
                                 @php 
                                      $price_tbl = swedishCurrencyFormat($orderProduct['price']);
                                  @endphp
                            <p>{{ __('lang.shopping_cart_price')}}: @if(!empty($price_tbl)) {{@$price_tbl}} kr @endif </p>
                          </div>
                          </div>
                        @endforeach
                      @endif
                    
                    @endforeach
                  @endif
                  <div class="clerarfix"></div>
                  <hr class="hr_line"/> 
                
                <div class="col-md-12">
                  <div class="checkoutAmountBorder">
                    <?php /* 
                      $sub_total_tbl = str_split(strrev($orderDetails[$orderId]['subTotal']), 3);
                      $sub_total_amt = strrev(implode(" ", $sub_total_tbl));
                      $sub_total_tbl = $sub_total_amt.",00";
                    */?>
                    @php 
                        $sub_total = $orderDetails[$orderId]['subTotal'];
                        $sub_total_tbl = swedishCurrencyFormat($sub_total);
                    @endphp
                    <span>{{ __('lang.shopping_cart_subtotal')}}:</span>
                   
                    <span style="float: right;" class="get_subtotal">{{@$sub_total_tbl}} kr</span>
                    <input type="hidden" name="hid_get_subtotal" class="hid_get_subtotal" value="{{@$sub_total}}">
                  </div>
                  <div class="checkoutAmountBorder">

                     <?php /*   $shipping_total_tbl = str_split(strrev(round($orderDetails[$orderId]['shippingTotal'])), 3);
                        $shipping_total_tbl = strrev(implode(" ", $shipping_total_tbl));
                        $shipping_total_tbl = $shipping_total_tbl.",00"; */?>
                    @php 
                    $sub_total_tbl = swedishCurrencyFormat($orderDetails[$orderId]['shippingTotal']);
                    @endphp
                  <span>{{ __('lang.shopping_cart_shipping')}}:</span><span style="float: right;" class="decide_shipping">{{@$shipping_total_tbl}} kr</span>
                </div>
                <div class="checkoutAmountBorder">
                  <?php /*
                  $total_tbl = str_split(strrev($orderDetails[$orderId]['Total']), 3);
                    $total_tbl = strrev(implode(" ", $total_tbl));
                    $total_tbl = $total_tbl.",00"; */?>
                  @php 
                    $total_tbl = swedishCurrencyFormat($orderDetails[$orderId]['Total']);
                  @endphp
                  <span>{{ __('lang.shopping_cart_total')}}:</span><span style="float: right;" class="decide_total">{{@$total_tbl}} kr</span>
                </div>
                </div>
                <div class="col-md-12 text-center" style="margin-top: 30px;">
                  <button type="button" id="" name="next" class="btn btn-black debg_color login_btn pay_through_btn" value="{{ __('users.complete_purchase_btn')}}"/>{{ __('users.complete_purchase_btn')}}</button>
              </div>
                </div>
                 
              </div>
              
              
              <?php /* <div class="col-md-12">
                  <div class="col-md-2"></div>
                @foreach($payment_options as $p)
                  <div class="col-md-3 text-center">
                    <button id="{{$p}}" type="button" class="btn buy_now_btn debg_color pay_through_btn" style="font-size:18px;"
                    >
                      {{ __('lang.pay_through')}} {{$p}} <span class="glyphicon glyphicon-play"></span>
                    </button>
                  </div>
                  @endforeach
              </div> */?>
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
<script>
var oops_heading = "{{ __('users.oops_heading')}}";
var required_field_error="{{ __('lang.required_field_error')}}";

  $('#same_as_billing').change(function() {
        if(this.checked) {
            $('#shipping_given_name').val($('#billing_given_name').val());
            $('#shipping_family_name').val($('#billing_family_name').val());
            $('#shipping_email').val($('#billing_email').val());
            $('#shipping_address').val($('#billing_address').val());
            $('#shipping_city').val($('#billing_city').val());
            $('#shipping_postcode').val($('#billing_postcode').val());
            $('#shipping_phone_number').val($('#billing_phone_number').val());
            
        }
             
    });
$('input[type=radio][name=payment_method]').on('change', function() {  
  var payment_id = $('.pay_through_btn').attr('id',$(this).val());
});



function showErrorMessage(strContent,redirect_url = '')
{ 
  $.alert({
      title: oops_heading,
      content: strContent,
      type: 'red',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-times-circle",
      buttons: {
        Ok: function () {
            if(redirect_url != '')
            {
              if(redirect_url == 'reload')
              {
                location.reload(true);
              }
              else
              {
                window.location.href = redirect_url;
              }
            }
        },
      }
    });
}


$( ".pay_through_btn" ).click(function() {

    var error=0;
    var btnid = $(this).attr('id'); 
    $( ".login_input" ).each(function() {
      if($(this).val().trim()=='') {
        error = 1;
        $(this).next('.invalid-feedback').text('Detta fält är obligatoriskt');
      }
      else{

          $(this).next('.invalid-feedback').text('');
      }      
    });

     
    $("input:radio").each(function(){
        if($("input:radio[name=shipping_amount]:checked").length == 0){
            error = 1;
            $('#select_shipping').text('Detta fält är obligatoriskt');
        }  else {
          $('#select_shipping').text('');
        }  
    });

    var check = true;
    $("input:radio").each(function(){
        if($("input:radio[name=payment_method]:checked").length == 0){
            check = false;
        }
    });

    if(check==false){      
      $('#select_payment_option_err').text(select_payment_option);
       error = 1;
    } else {
      $('#select_payment_option_err').text('');
    } 
        
    if(error==1){
        showErrorMessage(required_field_error);
    }
    if(error==0) { 

      if(btnid=='klarna')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'klarna'])}}");
      if(btnid=='swish')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'swish'])}}");
      
      if(btnid=='Kortbetalning')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'Kortbetalning'])}}");

       if(btnid=='swish_number')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'swish_number'])}}");
      
      
      $('form').submit();
    }
      //window.location.href = "{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>$p])}}";
  });


$(".show_cart").click(function(){
   window.location = "{{ route('frontShowCart') }}";
});

$('.radio-button-shipping').click(function() {

  if($('.radio-button-shipping').is(':checked')) 
  { 
    var is_free_shipping = $(this).val();      
    var product_shipping_charges = $(".product_shipping_charges").text();
    
    var free_ship ="0,00 kr";
    var hid_get_subtotal = $(".hid_get_subtotal").val();
    var hid_product_shipp_charges = $(".hid_product_shipp_charges").val();
    var subtotal= $(".get_subtotal").text();
    if(product_shipping_charges != free_ship){
      //$(".decide_shipping").text(product_shipping_charges);
      var newSubtotal = (hid_get_subtotal)+(hid_product_shipp_charges);
      var text = newSubtotal.replaceAll('.', ',').toLocaleString("sv-SE", {style:"currency", currency:"SEK"})+' kr';
      //$(".decide_total").text(text);
    shipping_charges  = hid_product_shipp_charges;
    shipping_charges_to_show  = product_shipping_charges;
    total_amount    = newSubtotal;
    total_amount_to_show= text;
    }
         
    if(is_free_shipping==0){
          //  alert("sdh")
            
      shipping_charges  = 0;
      shipping_charges_to_show  = free_ship;
      total_amount    = hid_get_subtotal;
      total_amount_to_show= subtotal;
    }
  
    $.ajax({
      headers: {
      'X-CSRF-Token': $('input[name="_token"]').val()
      },
      url: "{{url('/')}}"+'/update-shipping-charges/',
      type: 'post',
      // async: false,
      data:{shipping_charges:shipping_charges,total_amount:total_amount,orderId:<?php echo $orderId ?>},
      success: function(data){
      $(".decide_shipping").text(shipping_charges_to_show);
            $(".decide_total").text(total_amount_to_show);
      }
    });
  }                      
});

$( document ).ready(function() {
  if($('.is_pick_from_store').text() != 1){
    $('.pick_up_fromt_store').hide();
    //console.log( "ready!" );
  }
});
  </script>

@extends('Front.layout.template')
@section('middlecontent')
<!-- <script src="{{url('/')}}/assets/front/js/zoom-image.js"></script> -->
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/icheck-bootstrap.min.css">

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>

<section class="product_details_section p_155">
    <div class="loader"></div>
    <div class="container-fluid">
        <div class="row container-inner-section">
            <div class="col-md-6">
              <!-- Primary carousel image -->

              @if(!empty($variantData))
                @php
                $first = reset($variantData);
                @endphp
              @endif
 <!-- Secondary carousel image thumbnail gallery -->
 <div class="small-img">
                <!-- <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img"> -->
                <div class="small-container">
                  <div id="small-img-roll">
                   @if(isset($first['images'][0]) && !empty($first['images'][0]))
                    @foreach($first['images'] as $image)
                      <img src="{{url('/')}}/uploads/ProductImages/productIcons/{{$image}}" class="show-small-img" alt="">
                    @endforeach
                  @else
                      <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="show-small-img">
                  @endif
                  </div>
                </div>
                <!-- <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img"> -->
              </div>
              <div class="show-custom" href="{{url('/')}}/uploads/ProductImages/productDetails/{{$first['images'][0]}}">
              @if(isset($first['images'][0]) && !empty($first['images'][0]))
                <img src="{{url('/')}}/uploads/ProductImages/productDetails/{{$first['images'][0]}}" id="show-img">
               @else
                  <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="show-small-img">
              @endif
              </div>
              
              <a href="javascript:void(0);" class="report_product" title="{{ __('users.report_product_btn')}}" user_email="{{$loginUserEmail}}" product_link="{{$product_link}}" seller_name="{{$seller_name}}" product_id="{{$product_id}}" style="font-size: 12px;">{{ __('users.report_product_btn')}} </a>
            </div>

            <div class="col-md-offset-1 col-md-5">
                <div class="product_details_info">
                    <h2 class="product_title_details">{{$Product->title}}</h2>

                    <!-- <h4 class="product_price" style="color:#03989e;"><a href="{{$seller_link}}">{{ $seller_name }}</a></h4> -->
                    <div class="row">
                          <div class="col-xs-12 col-md-12">    
                          <div class="quantity_box"> 
                          <h4 class="service_store_name"><a href="{{$seller_link}}">@if(!empty($store_name)){{$store_name}}@endif</a></h4>             
                            <span class="product_original_price" id="product_variant_price"><span style="@if(!empty($first['discount_price'])) text-decoration: line-through;font-size: 16px;font-weight: 300;color: #777; @endif">{{ number_format($first['price'],2) }} kr</span>
                            @if(!empty($first['discount_price'])) &nbsp;&nbsp;{{ number_format($first['discount_price'],2) }} kr @endif

                          <?php /*   <span>@if(!empty($Product->discount)) &nbsp;&nbsp;<?php echo "(".$Product->discount."% off)"; ?> @endif</span> */?>
                          </span> 
                            

                          </div>
                          </div>
                        </div>
                    
                    <div class="star-rating start_rating_details">
                    <select class='rating product_rating' id='rating_{{$Product->id}}' data-id='rating_{{$Product->id}}' data-rating='{{$Product->rating}}'>
                      <option value="1" >1</option>
                      <option value="2" >2</option>
                      <option value="3" >3</option>
                      <option value="4" >4</option>
                      <option value="5" >5</option>
                    </select>
                      
                      </div>
                      <div style='clear: both;'></div>
                   <!--    <div>{{ __('lang.txt_average_rating')}} : <span id='avgrating_{{$Product->id}}'>{{$Product->rating}}</span></div> -->

                      <p>
                        <?php echo strip_tags($Product->description); ?>
                      </p>
                     
                     
                         @foreach($ProductAttributes as $attribute_id => $attribute)
                         <div class="col-md-6 p-0">
                            <div class="quantity_box" style="margin-bottom:0px !important;">
                             
                                <div>
                              <h3>{{ucwords($attribute['attribute_name'])}} : </h3> &nbsp;&nbsp;
                              <div class="clearfix"></div>
                                    <input type="hidden" id="variant_type" name="variant_type" value="{{$attribute['attribute_type']}}">
                                    @if($attribute['attribute_type']=='radio')
                                      @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        @php
                                          $checked = '';
                                          if(!empty($first['attr'][$attribute['attribute_name']]) && $first['attr'][$attribute['attribute_name']] == $attribute_value)
                                          {
                                            $checked = 'checked="checked"';
                                          }
                                        @endphp
                                        <div class="radio icheck-success icheck-inline" style="margin-top:10px !important;">
                                            <input type="radio" name="optionsRadios_{{$attribute['attribute_name']}}" product_id="{{$Product->id}}" id="{{$attribute_value_id}}" value="other" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}" {{$checked}} onclick="showAvailableOptions('{{$attribute_id}}','{{$attribute_value_id}}')" class="variant_radio_{{$attribute_id}}" />
                                            <label for="{{$attribute_value_id}}">{{$attribute_value}}</label>
                                        </div>
                                      @endforeach
                                    
                                    @elseif($attribute['attribute_type']=='dropdown')
                                    <select id="select_product_variant" class="{{$attribute_id}} form-control variant_dropdown" style="width: 80%;display: inline-block;margin-top: 5px; border-radius: 22px;    border: 1px solid #03989e; height:40px" onchange="showAvailableOptions('{{$attribute_id}}', this.value)">
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                      @php
                                          $selected = '';
                                          if(!empty($first['attr'][$attribute['attribute_name']]) && $first['attr'][$attribute['attribute_name']] == $attribute_value)
                                          {
                                            $selected = 'selected="selected"';
                                          }
                                        @endphp
                                        <option value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}" {{$selected}}> {{$attribute_value}} </option>
                                        @endforeach
                                    </select>
                                    @elseif($attribute['attribute_type']=='textbox')
                                    <div class="inputattributes {{$attribute_id}}">
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <input class="form-control" type="text" value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"> {{$attribute_value}}
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                </div>
                                </div>
                            
                        @endforeach
                      
                      
                     
                        
                     
                        <div class="col-xs-6 col-md-6 p-0"  >
                              <div class="quantity_box">
                                <h3>{{ __('lang.shopping_cart_quantity') }}:</h3>&nbsp;&nbsp;
                                <div class="clearfix"></div>
                                  <input class="drop_down_select " list="quantities" id="product_quantity" style="float:none;" >
                                    <datalist id="quantities">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                    <option value="6"></option>
                                    <option value="7"></option>
                                    <option value="8"></option>
                                    <option value="9"></option>
                                    <option value="10"></option>
                                    </datalist>
                              </div>
                         
                                          
                        
                      </div>
                      <div class="col-md-12 text-right" style="padding-right: 70px; padding-top: 12px;"><a href="javascript:void(0);" onclick="location.reload();" style="display:none;color:#ff0000;" id="reset_option">{{ __('lang.reset_options')}}</a></div>
                        
                      <!-- <div class="row">
                          <div class="col-xs-12 col-md-6">    
                          <div class="quantity_box">              
                            <h3>{{ __('lang.shopping_cart_price')}} : </h3>&nbsp;&nbsp;<span style="padding-top:6px;position:absolute;font-size:20px;" id="product_variant_price"><span style="@if(!empty($first['discount_price'])) text-decoration: line-through; @endif">{{ number_format($first['price'],2) }} kr</span> @if(!empty($first['discount_price'])) &nbsp;&nbsp;{{ number_format($first['discount_price'],2) }} kr @endif</span> 
                          </div>
                          </div>
                        </div> -->
                </div>
                <div class="col-xs-6 col-md-12 p-0">
                            <div class="quantity_box">
                               <input type="hidden" name="product_variant_id" value="{{$first['id']}}" id="product_variant_id" >           
                               <button type="button" class="btn add_to_cart_btn" @if(Auth::guard('user')->id()) onclick="addtoCartFromProduct();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif>{{ __('lang.add_to_cart')}}   <i class="glyphicon glyphicon-shopping-cart cart_icon"></i></button>
                            </div>
                        </div>
            </div>

        </div>
    </div> <!-- /container -->
</section>

<!-- product review section -->
<section>
<div class="container-fluid">
  <div class="container-inner-section">
    <div class="row">
      <div class="best_seller_container">
      <div class="col-md-12" style="margin-left: -33px;">
      <div class="col-md-6">
      <h2 class="review_title">{{ __('users.review_title')}}</h2>
      <hr class="hr_product_details">
     <!--  <div class="col-md-9"> -->
      @if(!empty($productReviews))
      <?php $i = 1; ?>
      @foreach($productReviews as $review)
      <div class="row reviews-container"> 
        <div class="col-md-1">
       
          @if(!empty($review['profile']))
          <img src="{{url('/')}}/uploads/Buyer/buyerIcons/{{$review['profile']}}" class="ratingUserIcon">
          @else 
          <img src="{{url('/')}}/uploads/Buyer/buyerIcons/no-image.png" class="ratingUserIcon">
          @endif
        </div>

        <div class="col-md-5" style="margin-left: 30px;">
          <p class="ratingUname"><?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>

          <div class="star-rating" style="font-size:15px;pointer-events: none;">
            <select class='rating product_rating' id='rating_{{$Product->id}}_{{$i}}' data-id='rating_{{$Product->id}}_{{$i}}' data-rating="{{$review['product_rating']}}">
              <option value="1" >1</option>
              <option value="2" >2</option>
              <option value="3" >3</option>
              <option value="4" >4</option>
              <option value="5" >5</option>
            </select>
          </div>
          <p class="ratingComment">{{$review['comments']}}</p>
          <!-- pagination for comments -->
             
        </div>
        <div class="col-md-6"></div>
        
        </div>

        <hr>
        <?php $i++; ?>
        @endforeach
          {!! $productReviews->links() !!}
        @endif
        </div>
        <div class="col-md-6">
           <h2  class="review_title">{{ __('users.store_terms')}}</h2>
            <hr class="hr_product_details">
            <button class="tablink product_sorting_filter" onclick="openPage('PaymentPolicy', this, 'red')" id="defaultOpen" style="">{{ __('users.payment_btn')}}</button>
            <button class="tablink product_sorting_filter" onclick="openPage('ShippingPolicy', this, 'blue')">{{ __('users.shipping_btn')}}</button>
            <button class="tablink product_sorting_filter" onclick="openPage('ReturnPolicy', this, 'green')">{{ __('users.return_btn')}}</button>
           <!--  <button class="tablink" onclick="openPage('BookingPolicy', this, 'white')">{{ __('users.booking_btn')}}</button> -->


            @if(!empty($getTerms))
            <div id="PaymentPolicy" class="tabcontent">
          <!--   <h3>{{ __('users.store_policy_label')}}</h3> -->
            <p class="policies ratingComment">{{@$getTerms->payment_policy}}</p>
            </div>

            <div id="ShippingPolicy" class="tabcontent">
            <!-- <h3>{{ __('users.shipping_policy_label')}}</h3> -->
            <p class="policies ratingComment">{{@$getTerms->shipping_policy}}</p>
            </div>

            <div id="ReturnPolicy" class="tabcontent">
           <!--  <h3>{{ __('users.return_policy_label')}}</h3> -->
            <p class="policies ratingComment">{{@$getTerms->return_policy}}</p> 
            </div>

            <!-- <div id="BookingPolicy" class="tabcontent">
            <p class="policies">{{@$getTerms->booking_policy}}</p>
            </div> -->
          @endif

        </div>
       <!--  </div> -->
      </div>
    </div>
  </div>
</div>
</section>

<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container">
                <h2  class="other_watched_products">{{ __('users.other_watched_product')}}</h2>
                <ul class="product_details best_seller">

      					@foreach($PopularProducts as $key=>$product)
                 @php if($key>4){continue;}@endphp
                          @include('Front.products_widget')
      					@endforeach
      				 </ul>
            </div>
          </div>
        </div>
    </div>
</section>


<!-- add report product model Form -->
 <div class="modal fade" id="reportProductmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('users.report_product_btn')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        <div class="loader-seller" style="display: none;"></div>
        <div class="modal-body">
            <div class="container">
            <form action="{{route('FrontContactStore')}}"  enctype="multipart/form-data" method="post" class="storeContactform">
              @csrf
                  <input type="hidden" name="seller_name" class="seller_name" id="seller_name" value="">
                  <input type="hidden" name="product_link" class="product_link" id="product_link" value="">
                  <input type="hidden" name="product_id" class="product_id" id="product_id" value="">

                <div class="form-group">
                  <label>{{ __('users.email_label')}} <span class="text-danger">*</span></label>
                
                  <input type="text" name="user_email" class="form-control user_email" id="user_email" placeholder="{{ __('users.email_label')}}" value="" style="width: 500px;">
                   <span class="invalid-feedback col-md-12"  id="err_email" ></span>
                </div>

                <div class="form-group">
                  <label style="margin-top:10px;">{{ __('users.your_message_label')}} <span class="text-danger">*</span></label>
                  <textarea class="user_message form-control contact-store-message" name="user_message" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}"  placeholder="{{ __('users.subcategory_name_label')}}" id="user_message"required></textarea>
               
                </div>
            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="send_report_product btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-black gray_color login_btn" data-dismiss="modal">{{ __('lang.close_btn')}}</button>
        </div>        
      </div>
    </div>
  </div>
  
  <!-- end report product model Form -->

<script type="text/javascript">
$(".product_rating").each(function(){
  var currentRating = $(this).data('rating');
  $(this).barrating({
    theme: 'fontawesome-stars',
    initialRating: currentRating,
    onSelect: function(value, text, event) {
   // Get element id by data-id attribute
   var el = this;
   var el_id = el.$elem.data('id');
   // rating was selected by a user
   if (typeof(event) !== 'undefined') {
 
     $.confirm({
        title: '{{ __('lang.txt_your_review')}}',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>{{ __('lang.txt_comments')}}</label>' +
        '<textarea class="name form-control" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}" required></textarea>' +
        '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Skicka', //submit
                btnClass: 'btn-blue',
                action: function () {
                    var comments = this.$content.find('.name').val();
                    if(!comments){
                      showErrorMessage('{{ __('lang.txt_comments_err')}}');
                      return false;
                    }
                    $(".loader").show();
                    $.ajax({
                    url:siteUrl+"/add-review",
                    headers: {
                      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    type: 'post',
                    data : {'rating': value, 'product_id' : '{{$Product->id}}', 'comments' : comments},
                    success:function(data)
                    {
                      $(".loader").hide();
                      var responseObj = $.parseJSON(data);
                      if(responseObj.status == 1)
                      {
                        showSuccessMessage(product_add_success,'reload');
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
                }
            },
            cancel: {
              text: 'Avbryt', //cancel 
              action: function () {
                //close
              }
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
   }
  }
 });  
}); 
function addtoCartFromProduct()
{
    var product_quantity = $("#product_quantity").val();
    var variant = $("#product_variant_id").val();
    
    if(product_quantity == '')
    {
      showErrorMessage(product_qty_error);
      return false;
    }
    else if(variant === undefined) 
    {
      showErrorMessage(product_variant_error);
      return false;
    }
    $(".loader").show();
    $.ajax({
      url:siteUrl+"/add-to-cart",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'product_variant': variant, 'product_quantity' : product_quantity},
      success:function(data)
      {
        $(".loader").hide();
        var responseObj = $.parseJSON(data);
        if(responseObj.status == 1)
        {
          var currentValue = $(".add_to_cart_count").text();
          var newValue = parseInt(parseFloat(currentValue)) + 1;
          $(".add_to_cart_count").text(newValue);
          //showSuccessMessage(product_add_success,'reload');
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
}
//Initialize product gallery
//$('.show-custom').zoomImage();
$('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
$('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
$('.show-small-img').click(function () { 
  var str =  $(this).attr('src');
  var customImg = str.replace("productIcons", "productDetails");
  $('#show-img').attr('src', customImg);
  $('#big-img').attr('src', customImg);
  $(this).attr('alt', 'now').siblings().removeAttr('alt')
  $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  if ($('#small-img-roll').children().length > 4) {
    if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
    } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});
//Enable the next button
$('#next-img').click(function (){
 
  $('#show-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $(".show-small-img[alt='now']").next().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});
//Enable the previous button
$('#prev-img').click(function (){
  $('#show-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $(".show-small-img[alt='now']").prev().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});
$("#show-img").next('div').next('div').css('z-index','999');
function showAvailableOptions(attribute_id,attribute_value)
{
  $("#reset_option").show();
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/get-product-options",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'attribute_id': attribute_id, 'attribute_value' : attribute_value, 'product_id' : '{{$Product->id}}'},
    success:function(data)
    {
      var responseObj = $.parseJSON(data);
      var images = responseObj.current_variant.image.split(',');
      $(images).each(function(key,image){
          $(".show-custom").attr('href',siteUrl+'/uploads/ProductImages/productDetails/'+image);
          $(".show-custom").find('img').attr('src',siteUrl+'/uploads/ProductImages/productDetails/'+image);
          return false;
      });
      var allImages = '';
      $(images).each(function(key,image){
        allImages+='<img src="'+siteUrl+'/uploads/ProductImages/productIcons/'+image+'" class="show-small-img" alt="">';
      });
      $("#small-img-roll").html(allImages);
      $('.show-custom').find('div').remove();
      //$('.show-custom').zoomImage();
      $('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
      $('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
      $('.show-small-img').click(function () {
        $('#show-img').attr('src', $(this).attr('src'))
        $('#big-img').attr('src', $(this).attr('src'))
        $(this).attr('alt', 'now').siblings().removeAttr('alt')
        $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
        if ($('#small-img-roll').children().length > 4) {
          if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
            $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
          } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
            $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
          } else {
            $('#small-img-roll').css('left', '0')
          }
        }
      });
      $(".show-custom").find('div').not(':first').css('z-index','999');
      $("#product_variant_id").val(responseObj.current_variant.id);
      if(responseObj.current_variant.discount_price)
      {
        $("#product_variant_price").html('<span style="text-decoration: line-through;">'+number_format(responseObj.current_variant.price,2)+'  kr</span> &nbsp;&nbsp;'+ number_format(responseObj.current_variant.discount_price,2)+'  kr');
        
      }
      else
      {
        $("#product_variant_price").html(number_format(responseObj.current_variant.price,2)+'  kr');
      }
      
      
      var optionLength = responseObj.other_option.length;
      $(responseObj.other_option).each(function(key,option)
      {
        if(option.attribute_type == 'dropdown')
        {
          $("."+option.attribute_id+" option").attr('disabled','disabled');
          $("."+option.attribute_id+" option[value='"+option.attribute_value_id+"']").removeAttr('disabled');
          if(optionLength == 1)
          {
            $("."+option.attribute_id+" option[value='"+option.attribute_value_id+"']").attr('selected','selected');
          }
          
        }
        else if(option.attribute_type == 'radio')
        {
          $(".variant_radio_"+option.attribute_id).each(function(){
              $(this).attr('disabled','disabled').removeAttr('checked');
          });
          $("#"+option.attribute_value_id).removeAttr('disabled');
          if(optionLength == 1)
          {
            $("#"+option.attribute_value_id).prop('checked',true);
          }
        }
      });
      $(".loader").hide();
    }
  });
  function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
}
$(document).on("click",".report_product",function(event) {
        
        $('#reportProductmodal').find('.user_email').val($(this).attr('user_email'));
        $('#reportProductmodal').find('.product_link').val($(this).attr('product_link'));
        $('#reportProductmodal').find('.seller_name').val($(this).attr('seller_name')); 
        $('#reportProductmodal').find('.product_id').val($(this).attr('product_id'));  
       // $('#reportProductmodal').find('.message').val($(this).attr('message'));  
       
         $('#reportProductmodal').modal('show');
});
$(document).on("click",".send_report_product",function(event) {
       //storeContactform 
      let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
      let error = 0;
     if($('#reportProductmodal').find('.user_email').val()=='') {
        $("#err_email").html(fill_in_email_err).show();
        $("#err_email").parent().addClass('jt-error');
        error = 1;
     }else if(!email_pattern.test($('#reportProductmodal').find('.user_email').val())) {
      $("#err_email").html(fill_in_valid_email_err).show();
      $("#err_email").parent().addClass('jt-error');
      error = 1;
    }else{
      $("#err_email").parent().removeClass('jt-error');
      $("#err_email").html('').hide();
    }
    if($('#reportProductmodal').find('.user_message').val()==''){
       showErrorMessage(required_field_error);
       error = 1;
    }
    if(error == 1){
      return false;
    }else{
    
        let user_message   = $("#user_message").val();
        let user_email   = $("#user_email").val();
        let seller_id      = $("#seller_id").val();
        let seller_name      = $("#seller_name").val();
        let product_link      = $("#product_link").val();
        let product_id      = $("#product_id").val();
       $(".loader-seller").show();
        setTimeout(function(){
    $.ajax({
          url:"{{ route('FrontReportProduct') }}",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{user_message:user_message,user_email:user_email,product_link:product_link,product_id:product_id,seller_name:seller_name},
          success: function(output){
        
             $(".loader-seller").hide();
             $('#reportProductmodal').modal('hide');  
           
            if(output.success !=''){
              showSuccessMessage(output.success);
              let user_message   = $("#user_message").val('');
            }else{
              showErrorMessage(output.error);
            }
          }
        });}, 300);
      }   
    }); 
/*product_link product_no product_name user_email*/
</script>
@endsection
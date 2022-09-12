@extends('Front.layout.template')
@section('middlecontent')
<!-- <script src="{{url('/')}}/assets/front/js/zoom-image.js"></script> -->
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/icheck-bootstrap.min.css">

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick-theme.min.css">
<script src="{{url('/')}}/assets/front/js/slick.min.js"></script>

<section class="product_details_section p_155">
    <div class="loader"></div>
    <div class="container-fluid">

        <div class="row container-inner-section">
            <div class="col-md-6 tj-detail-imgs">
              <!-- Primary carousel image -->
              @if(!empty($variantData))
                @php
                $first = reset($variantData);
                @endphp
              @endif
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
              <div class="show-custom product_custom_img" href="{{url('/')}}/uploads/ProductImages/productDetails/{{$first['images'][0]}}">
                @if(isset($first['images'][0]) && !empty($first['images'][0]))
                <img src="{{url('/')}}/uploads/ProductImages/productDetails/{{$first['images'][0]}}" id="show-img">
                @else
                  <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png"  id="show-img">
                @endif


              </div>
              <div class="report_product_div">
               <a href="javascript:void(0);" class="report_product" title="{{ __('users.report_ad_btn')}}" user_email="{{$loginUserEmail}}" product_link="{{$product_link}}" seller_name="{{$seller_name}}" product_id="{{$product_id}}" style="font-size: 10px;">{{ __('users.report_ad_btn')}} </a>
              </div>
              <!-- Secondary carousel image thumbnail gallery -->
             
            </div>

            <div class="col-md-offset-1 col-md-5">
                <div class="product_details_info" style="min-height: 385px;position: relative;">
                    <h2 class="product_title_details">{{$Product->title}}</h2>
                   <!--  <h4 class="product_price" id="product_variant_price" style="color:#03989e;"><span>{{ __('lang.price_label')}} :</span>{{ number_format($first['price'],2) }} kr  {{ number_format($first['discount_price'],2) }} kr</h4> -->

                    <div class="quantity_box">              
                               <span  style="margin-top: -10px;"  class="product_original_price" id="product_variant_price">
                                @php   
                                  $price_tbl = swedishCurrencyFormat($first['price']);
                                @endphp
                               {{ $price_tbl }} kr</span> 
                      <div style="margin-top: 30px;"> 
                        <span class="service_time_css">{{ __('users.sold_by_title')}} : @if(!empty($product_seller_name)){{$product_seller_name}}@endif </span>
                         <span style="padding-left:13%" class="service_time_css"> <img src="{{url('/')}}/assets/img/7.png" width="40" />
                  @if(!empty($product_location)){{$product_location}}@endif</span></div>
                    </div>
                      <div class="clearfix"></div>
                      <div class="row prduct_det" style="position:absolute; bottom:-80px">  
                        <div class="col-md-12" >                    
                        <h3 class="ad_contact_details"><a href="tel: {{@$buyer_product_details->user_phone_no}}"> <img src="{{url('/')}}/assets/img/8.png" width="40" /></a> {{@$buyer_product_details->user_phone_no}}</h3>
                        <h3 class="ad_contact_details"><a href="mailto:{{@$buyer_product_details->user_email}}"><img src="{{url('/')}}/assets/img/9.png" width="40" /></a>{{@$buyer_product_details->user_email}}</h3>

                        </div>       
                      </div>
                      <div>
                        <h3 class="ad_contact_details"> {{ __('users.description_label')}} </h3>
                       @if(!empty($Product->description))  
                        <p class=""><?php echo strip_tags($Product->description);?></p> 
                       @endif
                      </div>
                        
                     
                </div>
            </div> 
            <div class="tj-announcer-action">
              <a href="{{route('AllbuyerProductListing')}}"  class="annonser_btn">{{ __('lang.back_to_ads')}}</a>
            </div>
        </div>
    </div> <!-- /container -->
</section>


<!-- product review section -->
<?php /*
<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container">

              <h2>{{ __('users.review_title')}}</h2>
             
                @if(!empty($productReviews))
                  @foreach($productReviews as $review)
                  <div class="col-md-12">
                    <div class="col-md-1">
                    

                    @if(!empty($review['profile']))
                      <img src="{{url('/')}}/uploads/Buyer/resized/{{$review['profile']}}" class="ratingUserIcon">
                    @else 
                      <img src="{{url('/')}}/uploads/Buyer/resized/profile.png"class="ratingUserIcon">
                    @endif 

                    </div>

                    <div class="col-md-7" style="margin-left: -20px;">
                      <p class="ratingUname"><?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>

                    <div class="star-rating" style="font-size:unset;pointer-events: none;">
                        <select class='rating product_rating' id='rating_{{$Product->id}}' data-rating="{{$review['product_rating']}}">
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                      </div>
                    <p class="ratingComment">{{$review['comments']}}</p>
                  </div>
                  <div class="col-md-4"></div>
                </div><hr class="solid-horizontal-line">
                  
                @endforeach
                  {!! $productReviews->links() !!}
                @endif

            </div>
        </div>
    </div>
</div>
</section>
*/?>
<section>
  @if(isset($similarProducts[0]))
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container col-md-12 product_container-list-5">
                <h2 class="other_watched_products">{{ __('lang.also_have_watch')}}</h2>
                
                <ul class="product_details best_seller pl-0 tjbestseller">

                  @foreach($similarProducts as $product)
                    @include('Front.similar_products_widget')
                  @endforeach
              </ul>
                  </div>


</div>
        </div>
    </div>
    @endif
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


function showAvailableOptions()
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
      $('.show-custom').zoomImage();
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
      $("#product_variant_price").html(number_format(responseObj.current_variant.price,2)+'  kr');
      
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
                          showErrorMessage(responseObj.msg,'/front-login/buyer');
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
              showSuccessMessageReview(output.success);
              let user_message   = $("#user_message").val('');
            }else{
              showErrorMessage(output.error);
            }
          }
        });}, 300);
      }   
    }); 
/*product_link product_no product_name user_email*/
if($(window).width() < 767){
  if(jQuery('.tjbestseller').length){
    jQuery('.tjbestseller').slick({
      speed: 250,
      arrows: false,
      autoplay: false,
      slidesToShow: 2,
      slidesToScroll: 1,
      dots: false
    });
  }
}
</script>
@endsection

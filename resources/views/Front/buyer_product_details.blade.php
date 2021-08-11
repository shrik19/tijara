@extends('Front.layout.template')
@section('middlecontent')
<script src="{{url('/')}}/assets/front/js/zoom-image.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/icheck-bootstrap.min.css">

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>

<section class="product_details_section">
    <div class="loader"></div>
    <div class="container">

        <div class="row">
            <div class="col-md-6">
              <!-- Primary carousel image -->
              @if(!empty($variantData))
                @php
                $first = reset($variantData);
                @endphp
              @endif

              <div class="show-custom" href="{{url('/')}}/uploads/ProductImages/{{$first['images'][0]}}">
                <img src="{{url('/')}}/uploads/ProductImages/{{$first['images'][0]}}" id="show-img">
              </div>
              
              <!-- Secondary carousel image thumbnail gallery -->
              <div class="small-img">
                <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img">
                <div class="small-container">
                  <div id="small-img-roll">
                    @foreach($first['images'] as $image)
                      <img src="{{url('/')}}/uploads/ProductImages/{{$image}}" class="show-small-img" alt="">
                    @endforeach
                  </div>
                </div>
                <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img">
              </div>
            </div>

            <div class="col-md-6">
                <div class="product_details_info">
                    <h2>{{$Product->title}}</h2>
                    <h4 class="product_price" id="product_variant_price" style="color:#03989e;">{{ number_format($first['price'],2) }} kr</h4>
                   
                      <p>
                        <?php echo $Product->description; ?>
                      </p>
                      <div class="row">                      
                        <h3><i class="fa fa-phone-alt"></i> {{$buyer_product_details->user_phone_no}}</h3>
                        <h3><i class="fa fa-envelope"></i> {{$buyer_product_details->user_email}}</h3>
                      </div>
                        
                      <div class="row">
                          <div class="col-xs-12 col-md-6">    
                            <div class="quantity_box">              
                               <a href="javascript:void(0);">{{ __('lang.back_to_ads')}}</a>
                            </div>
                          </div>
                      </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
</section>


<!-- product review section -->
<section>
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
              <div class="col-md-12">
              <h2>{{ __('users.review_title')}}</h2>
                <hr>
                @if(!empty($productReviews))
                  @foreach($productReviews as $review)
                  <div>
                    <p>

                    @if(!empty($review['profile']))
                      <img src="{{url('/')}}/uploads/Buyer/resized/{{$review['profile']}}" style="width:50px;height:50px;">
                    @else 
                      <img src="{{url('/')}}/uploads/Buyer/resized/profile.png" style="width:50px;height:50px;">
                    @endif
                      
                    <?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>
                    <div class="star-rating" style="font-size:unset;pointer-events: none;">
                        <select class='rating product_rating' id='rating_{{$Product->id}}' data-rating="{{$review['product_rating']}}">
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                      </div>
                    <p>{{$review['comments']}}</p>
                  </div>
                  <hr>
                  @endforeach
                @endif
              </div>
            </div>
        </div>
    </div>
</section>

<section>
  @if(!empty($similarProducts))
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
                <h3>{{ __('lang.also_have_watch')}}</h3>
                
                <ul class="product_details best_seller">
                  @foreach($similarProducts as $product)
                            @include('Front.similar_products_widget')
                  @endforeach
              </ul>
                  </div>



        </div>
    </div>
    @endif
</section>

<script type="text/javascript">
  

//Initialize product gallery

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
          $(".show-custom").attr('href',siteUrl+'/uploads/ProductImages/'+image);
          $(".show-custom").find('img').attr('src',siteUrl+'/uploads/ProductImages/'+image);
          return false;
      });
      var allImages = '';
      $(images).each(function(key,image){
        allImages+='<img src="'+siteUrl+'/uploads/ProductImages/'+image+'" class="show-small-img" alt="">';
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

</script>
@endsection

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
             
                @php
                $image='';
                if($Service->images!='')
                    $image = explode(',',$Service->images)[0];
                @endphp

               
                <div class="small-img">
                      <!-- <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img"> -->
                      <div class="small-container">
                        
                          <div id="small-img-roll">
                        @if(isset($image) && !empty($image))
                            @foreach(explode(',',$Service->images) as $image)
                              <img src="{{url('/')}}/uploads/ServiceImages/serviceIcons/{{$image}}" class="show-small-img" alt="">
                            @endforeach
                        @else
                            <img src="{{url('/')}}/uploads/ServiceImages/no-image.png" class="show-small-img">
                        @endif
                          </div>
                          
                      </div>
                      <!-- <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img"> -->
                  </div>

                 @if(isset($image) && !empty($image))
                  <div class="show-custom" href="{{url('/')}}/uploads/ServiceImages/serviceDetails/{{$image}}">
                    <img src="{{url('/')}}/uploads/ServiceImages/serviceDetails/{{$image}}" id="show-img">
                  </div>
                   @else
                  <div class="show-custom" href="{{url('/')}}/uploads/ServiceImages/no-image.png">
                    <img src="{{url('/')}}/uploads/ServiceImages/no-image.png" id="show-img">
                  </div>
                   @endif

                 
                  @if($Service->images!='')
                  <!-- Secondary carousel image thumbnail gallery -->
                
                  @endif
            </div>

            <div class="col-md-6">
                <div class="product_details_info">
                    <h2 class="product_title_details">{{$Service->title}}</h2>
                     <h4 class="service_time_css">@if(!empty($Service->session_time)){{$Service->session_time}} min @endif</h4>
                    <h4 class="service_store_name" style="margin-top: 0px;"><a href="{{$seller_link}}">@if(!empty($store_name)){{$store_name}}@endif</a></h4>
                    <!-- <h4 class="product_price product_original_price"><a href="{{$seller_link}}" class="de_col">{{ $Service->service_price }} Kr</a></h4> -->
                    <span class="product_original_price" style="padding-top: 25px;">{{ $Service->service_price }} kr</span>

                      <div class="star-rating" style="font-size:15px;">
                        <select class='rating service_rating' id='rating_{{$Service->id}}' data-id='rating_{{$Service->id}}' data-rating='{{$Service->rating}}'>
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                      </div> 
                      <div style='clear: both;'></div>
                      <p style="margin-top:50px"><?php echo $Service->description; ?></p>
                  

                     
                        <div class="row">
                          <div class="col-md-12" style="padding-right: 70px; padding-top: 12px;">
                          <!-- <a href="javascript:void(0);"  data-toggle="modal" data-target="#bookServiceModal" 
                           style="color:#ff0000;" id="reset_option">{{ __('lang.book_service')}}</a> -->
                           <a href="javascript:void(0);" data-toggle="modal" data-target="#bookServiceModal"  class="btn sub_btn book_service_button" title="{{ __('users.see_available_time_btn')}}" id="reset_option">{{ __('users.see_available_time_btn')}}<i class="far fa-calendar-alt" style="margin-left: 10px;font-size: 20px;"></i></a>
                         </div>
                        </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="bookServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border-radius: 30px;">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('lang.book_service_title')}}</h5>
                                <button type="button" class="close modal-cross-sign" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                              
                                <div class="form-group">
                                  <label>{{ __('lang.service_title')}}</label>
                                  <input type="text" value="{{$Service->title}}" id="" readonly class=" service_title form-control" 
                                  placeholder="{{ __('lang.service_title')}}">
                                </div>
                                <div class="form-group">
                                  <label>{{ __('lang.location')}}</label>
                                  <input type="text"  id="" class="location form-control" value="@if(!empty($Service->address)) {{$Service->address}} @endif" placeholder="{{ __('lang.location')}}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                  <label>{{ __('lang.service_date')}}</label>
                                  <select  class="service_date form-control">
                                    <option value="">{{ __('lang.select_label')}}</option>
                                    @php $usedDates = array(); @endphp
                                    @if(!empty($serviceAvailability))
                                      @foreach($serviceAvailability as $availability) 

                                        
                                        @if(!in_array($availability->service_date,$usedDates) && $availability->service_date >= date('Y-m-d'))
                                          <option value="{{$availability->service_date}}">{{$availability->service_date}}</option>
                                        @endif
                                        @php $usedDates[]=$availability->service_date; @endphp
                                       @endforeach

                                    @endif
                                  </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label>{{ __('lang.service_time')}}</label>
                                  <select  class="service_time form-control">
                                    <option value="">{{ __('lang.select_label')}}</option>
                                    @if(!empty($serviceAvailability))
                                      @foreach($serviceAvailability as $availability) 

                                        @php 
                                        $date = $availability->service_date.' '.$availability->start_time;
                                        $startTime  = $availability->start_time;
                                        $endTime = date("H:i", strtotime($date . "+".$Service->session_time." minutes"));
                                        @endphp
                                        @if(date('Y-m-d H:i',strtotime($availability->service_date.' '.$availability->start_time)) > date('Y-m-d H:i'))
                                          <option style="display:none;" id="{{$availability->service_date}}" value="{{$startTime}} - {{$endTime}}">{{$startTime}} - {{$endTime}} </option>
                                         @endif 
                                        @endforeach

                                    @endif
                                  </select>
                                </div>
                                <!-- <div class="form-group">
                                  <label>{{ __('lang.personal_number')}}</label>
                                  <input type="text"  id="phone_number" class="phone_number form-control" placeholder="(XXX) XXX-XXXX">
                                </div> -->
                                <div class="form-group">
                                  <label>{{ __('lang.service_total_cost')}}</label>
                                  <input type="text"  value="{{$Service->service_price}} kr" id="" readonly 
                                  class=" form-control service_price" 
                                  placeholder="{{ __('lang.service_total_cost')}}">
                                  <p style="font-size: 12px;">Betalning sker på plats</p>
                                </div>
                              
                                <div class="form-group">
                                    <button style="width: 60%;    margin-left: 18%;    height: 45px;" type="button" class="btn sub_btn" @if(Auth::guard('user')->id()) onclick="sendServiceRequest();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif> {{ __('lang.book_service_btn')}}  </button>
                                </div>
                              </div>
                             
                            </div>
                          </div>
                        </div>

                </div>
            </div>
        </div>
    </div> <!-- /container -->
</section>

<!-- service review section -->
<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container">
              <div class="col-md-12"  style="margin-left: -33px;">
              <div class="col-md-6">
              <h2  class="review_title">{{ __('users.review_title')}}</h2>
              <hr class="hr_product_details">
              <!-- </div> -->
                @if(!empty($serviceReviews))
                  @php $i=1; @endphp
                  @foreach($serviceReviews as $review)
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
                    <div class="star-rating" style="font-size:unset;pointer-events: none;">
                        <select class='rating service_rating' data-rating="{{$review['service_rating']}}" id='rating_{{$Service->id}}_{{$i}}' data-id='rating_{{$Service->id}}_{{$i}}'>
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                    </div>
                    <p class="ratingComment">{{$review['comments']}}</p>
                   
                  </div>
                   <div class="col-md-6"></div>
                  </div>
                  <hr>
                  @php $i++; @endphp
                  @endforeach
                  {!! $serviceReviews->links() !!}
                @endif
              </div>
               <div class="col-md-6">
               <h2 class="review_title">{{ __('users.store_terms')}}</h2>
               <hr class="hr_product_details">
                <button class="tablink product_sorting_filter" onclick="openPage('PaymentPolicy', this, 'red')" id="defaultOpen" style="">{{ __('users.payment_btn')}}</button>
                <!-- <button class="tablink" onclick="openPage('ShippingPolicy', this, 'blue')">{{ __('users.shipping_btn')}}</button>
                <button class="tablink" onclick="openPage('ReturnPolicy', this, 'green')">{{ __('users.return_btn')}}</button> -->
                <button class="tablink product_sorting_filter" onclick="openPage('CancelPolicy', this, 'white')">{{ __('users.cancellation_policy')}}</button>

                @if(!empty($getTerms))
                 <div id="PaymentPolicy" class="tabcontent">
                <!--   <h3>{{ __('users.store_policy_label')}}</h3> -->
                  <p class="policies ratingComment">{{@$getTerms->payment_policy}}</p>
                  </div>

                 <!--  <div id="ShippingPolicy" class="tabcontent">
                  <p class="policies">{{@$getTerms->shipping_policy}}</p>
                  </div>

                  <div id="ReturnPolicy" class="tabcontent">
                  <p class="policies">{{@$getTerms->return_policy}}</p> 
                  </div> -->

                  <div id="CancelPolicy" class="tabcontent">
                  <p class="policies ratingComment">{{@$getTerms->cancellation_policy}}</p>
                  </div>
              @endif

            </div>
            </div>
           
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
                <!-- <h3>{{ __('lang.popular_items_in_market_head')}}</h3> -->
                <h2 class="other_watched_products">{{ __('users.other_watched_product')}}</h2>
                <ul class="product_details best_seller">
          @foreach($PopularServices as $key=>$service)
           @php if($key>4){continue;} @endphp
                    @include('Front.services_widget')
					@endforeach
				 </ul>
            </div>


</div>
        </div>
    </div>
</section>

<script type="text/javascript">
function sendServiceRequest()
{
  if($('.service_title').val()=='' || $('.location').val()=='' || $('.service_date').val()==''
   || $('.service_time').val()=='' || $('.service_price').val()=='') {
    showErrorMessage("{{ __('lang.allFieldsRequired')}}");
    return false;
  }
    
    $(".loader").show();

    $.ajax({
      url:siteUrl+"/send-service-request",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'service_id': {{$Service->id}},'seller_id': {{$Service->user_id}},
      'service_title':$('.service_title').val(),'location':$('.location').val(),
      'service_date':$('.service_date').val(),'service_time':$('.service_time').val()
      ,'service_price':$('.service_price').val()},
      success:function(data)
      {
        $(".loader").hide();
        var responseObj = $.parseJSON(data);
        showSuccessMessage("{{ __('lang.serviceRequestSent')}}");
        location.reload();
      }
     });
}

//Initialize product gallery

//$('.show-custom').zoomImage();

$('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
$('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
$('.show-small-img').click(function () {

  var str =  $(this).attr('src');
  var customImg = str.replace("serviceIcons", "serviceDetails");
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




/*service rating*/
$(".service_rating").each(function(){
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
                    url:siteUrl+"/add-service-review",
                    headers: {
                      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    type: 'post',
                    data : {'rating': value, 'service_id' : '{{$Service->id}}', 'comments' : comments},
                    success:function(data)
                    {
                      $(".loader").hide();
                      var responseObj = $.parseJSON(data);
                      if(responseObj.status == 1)
                      {
                        showSuccessMessage(review_add_success,'reload');
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

</script>
@endsection

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
             
                @php
                $image='';
                if($Service->images!='')
                $image = explode(',',$Service->images)[0];
                @endphp
                @if($image!='')
                  <div class="show-custom" href="{{url('/')}}/uploads/ServiceImages/{{$image}}">
                    <img src="{{url('/')}}/uploads/ServiceImages/{{$image}}" id="show-img">
                  </div>
                  @endif
                  @if($Service->images!='')
                  <!-- Secondary carousel image thumbnail gallery -->
                  <div class="small-img">
                      <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img">
                      <div class="small-container">
                        
                          <div id="small-img-roll">
                            @foreach(explode(',',$Service->images) as $image)
                              <img src="{{url('/')}}/uploads/ServiceImages/{{$image}}" class="show-small-img" alt="">
                            @endforeach
                          </div>
                          
                      </div>
                      <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img">
                  </div>
                  @endif
            </div>

            <div class="col-md-6">
                <div class="product_details_info">
                    <h2>{{$Service->title}}</h2>
                    <h4 class="product_price" style="color:#03989e;"><a href="{{$seller_link}}">{{ $seller_name }}</a></h4>
                      <div class="star-rating" style="font-size:unset;">
                        <select class='rating service_rating' id='rating_{{$Service->id}}' data-id='rating_{{$Service->id}}' data-rating='{{$Service->rating}}'>
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                        </select>
                      </div> 
                      <div style='clear: both;'></div>
                      <div>{{ __('lang.txt_average_rating')}} : <span id='avgrating_{{$Service->id}}'>{{$Service->rating}}</span></div>
                      <p>
                        <?php echo $Service->service_price; ?> KR 
                      </p>
                      <p>
                        <?php echo $Service->description; ?>
                      </p>

                     
                        <div class="row">
                          <div class="col-md-12 text-right" style="padding-right: 70px; padding-top: 12px;">
                          <a href="javascript:void(0);"  data-toggle="modal" data-target="#bookServiceModal" 
                           style="color:#ff0000;" id="reset_option">{{ __('lang.book_service')}}</a></div>
                        </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="bookServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('lang.book_service_title')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                  <input type="text"  id="" class="location form-control" placeholder="{{ __('lang.location')}}">
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
                                <div class="form-group">
                                  <label>{{ __('lang.personal_number')}}</label>
                                  <input type="text"  id="phone_number" class="phone_number form-control" placeholder="(XXX) XXX-XXXX">
                                </div>
                                <div class="form-group">
                                  <label>{{ __('lang.service_total_cost')}}</label>
                                  <input type="text"  value="{{$Service->service_price}} kr" id="" readonly 
                                  class=" form-control service_price" 
                                  placeholder="{{ __('lang.service_total_cost')}}">
                                </div>
                              
                                <div class="form-group">
                                    <button type="button" class="btn btn-success" @if(Auth::guard('user')->id()) onclick="sendServiceRequest();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif> {{ __('lang.book_service')}}  </button>
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

<!-- product review section -->
<section>
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
              <div class="col-md-12">
              <h2>{{ __('users.review_title')}}</h2>
                <hr>
                @if(!empty($serviceReviews))
                  @foreach($serviceReviews as $review)
                  <div>
                    <p>
                      @if(!empty($review['profile']))
                      <img src="{{url('/')}}/uploads/Buyer/resized/{{$review['profile']}}" style="width:50px;height:50px;">
                    @else 
                      <img src="{{url('/')}}/uploads/Buyer/resized/profile.png" style="width:50px;height:50px;">
                    @endif

                    <?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?>
                    
                    </p>
                    <div class="star-rating" style="font-size:unset;pointer-events: none;">
                        <select class='rating product_rating' data-rating="{{$review['service_rating']}}">
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
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.best_seller_head')}}</h2>
                <ul class="product_details best_seller">
					@foreach($PopularServices as $service)
                    @include('Front.services_widget')
					@endforeach
				 </ul>
            </div>



        </div>
    </div>
</section>

<script type="text/javascript">
function sendServiceRequest()
{
  if($('.service_title').val()=='' || $('.location').val()=='' || $('.service_date').val()==''
   || $('.service_time').val()==''  || $('.phone_number').val()==''  || $('.service_price').val()=='') {
    alert("{{ __('lang.allFieldsRequired')}}");
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
      ,'phone_number':$('.phone_number').val(),'service_price':$('.service_price').val()},
      success:function(data)
      {
        $(".loader").hide();
        var responseObj = $.parseJSON(data);
        alert("{{ __('lang.serviceRequestSent')}}");
        location.reload();
      }
     });
}

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


/*service rating*/
  
  $('#rating_{{$Service->id}}').barrating({
  theme: 'fontawesome-stars',
  initialRating: '{{$Service->rating}}',
  onSelect: function(value, text, event) {

   // Get element id by data-id attribute
   var el = this;
   var el_id = el.$elem.data('id');

   // rating was selected by a user
   if (typeof(event) !== 'undefined') {
 
     $.confirm({
        title: '{{ __('lang.txt_your_comments')}}',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>{{ __('lang.txt_comments')}}</label>' +
        '<textarea class="name form-control" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}" required></textarea>' +
        '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
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
            cancel: function () {
                //close
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

</script>
@endsection

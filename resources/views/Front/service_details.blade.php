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
                        <?php echo $Service->description; ?>
                      </p>

                      <p>
                        <?php echo $Service->price_type; ?>: <?php echo $Service->service_price; ?> KR 
                      </p>
                     
                        <div class="row">
                          <div class="col-md-12 text-right" style="padding-right: 70px; padding-top: 12px;"><a href="javascript:void(0);" onclick="location.reload();" style="display:none;color:#ff0000;" id="reset_option">Reset Options</a></div>
                        </div>
                        <div class="row">
                        

                        <div class="col-xs-6 col-md-7">
                        <div class="form-group">
                            <label>{{ __('lang.service_time')}}</label>
                            <input type="text" id="datetimepicker1" class="service_time form-control" placeholder="{{ __('lang.service_time')}}">
                          </div>
                          <div class="form-group">
                            <label>{{ __('lang.message')}}</label>
                            <textarea rows="10" cols="50" class="form-control message" placeholder="{{ __('lang.message')}}"></textarea>
                            
                          </div>
                         
                          <div class="quantity_box">
                              <button type="button" class="btn btn-success" @if(Auth::guard('user')->id()) onclick="sendServiceRequest();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif> {{ __('lang.sendRequest')}}  </button>
                          </div>
                        </div>
                      </div>

                </div>
            </div>
        </div>
    </div> <!-- /container -->
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
  if($('.message').val()=='') {
    alert("{{ __('lang.messageRequired')}}");
    return false;
  }
    
    $(".loader").show();

    $.ajax({
      url:siteUrl+"/send-service-request",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'service_id': {{$Service->id}},'seller_id': {{$Service->user_id}},'message':$('.message').val(),'service_time':$('.service_time').val()},
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

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
                              <img src="{{url('/')}}/uploads/ServiceImages/serviceIcons/{{$image}}" class="show-small-img" alt="{{$image}}">
                            @endforeach
                        @else
                            <img src="{{url('/')}}/uploads/ServiceImages/no-image.png" class="show-small-img">
                        @endif
                          </div>
                          
                      </div>
                      <!-- <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img"> -->
                  </div>
                <?php 
                  $service_image = explode(',',$Service->images);
                ?>
                <div class="show-custom product_custom_img" href="{{url('/')}}/uploads/ServiceImages/serviceDetails/{{$service_image[0]}}">
                 @if(isset($image) && !empty($image))
                  <!-- <div class="show-custom" href="{{url('/')}}/uploads/ServiceImages/serviceDetails/{{$service_image[0]}}"> -->
                    <img src="{{url('/')}}/uploads/ServiceImages/serviceDetails/{{$service_image[0]}}" id="show-img">
                  <!-- </div> -->
                   @else
                <!--   <div class="show-custom" href="{{url('/')}}/uploads/ServiceImages/no-image.png"> -->
                    <img src="{{url('/')}}/uploads/ServiceImages/no-image.png" id="show-img">
                  <!-- </div> -->
                   @endif
              <div class="buy_now_hover_details product_wish_icon">
                <ul>
                 <?php /*<li><a href="{{$service->service_link}}"><i class="fa fa-search"></i></a></li>*/?>
                  <li><a @if(Auth::guard('user')->id()) onclick="addToWishlistServices('{{$Service->id}}');event.stopPropagation();" @else href="{{ route('frontLogin') }}" @endif><i class="far fa-heart"></i></a></li>
                </ul>
              </div>
            </div>
                  @if($Service->images!='')
                  <!-- Secondary carousel image thumbnail gallery -->
                
                  @endif

                  <div class="report_product_div">
                <a href="javascript:void(0);" class="report_product" title="{{ __('users.report_product_btn')}}" user_email="{{$loginUserEmail}}" service_link="{{$service_link}}" seller_name="{{$seller_name}}" service_id="{{$service_id}}">{{ __('users.report_product_btn')}} </a>
              </div>
            </div>

            <div class="col-md-offset-1 col-md-5">
                <div class="product_details_info">
                    <h2 class="product_title_details">{{$Service->title}}</h2>
                     <h4 class="service_time_css">@if(!empty($Service->session_time)){{$Service->session_time}} min @endif</h4>
                    <h4 class="service_store_name" style="margin-top: 0px;"><a href="{{$seller_link}}">@if(!empty($store_name)){{$store_name}}@endif</a></h4>
                    <!-- <h4 class="product_price product_original_price"><a href="{{$seller_link}}" class="de_col">{{ $Service->service_price }} Kr</a></h4> -->
                    <span class="service_original_price" style="padding-top: 25px;">
                      @php             
                        $service_price_tbl = swedishCurrencyFormat($Service->service_price);
                      @endphp
                      {{ $service_price_tbl }} kr</span>

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
                            <input type="hidden" name="loginUserRoleId" id="loginUserRoleId" value="{{$loginUserRoleId}}">
                          <!-- <a href="javascript:void(0);"  data-toggle="modal" data-target="#bookServiceModal" 
                           style="color:#ff0000;" id="reset_option">{{ __('lang.book_service')}}</a> -->
                           <a href="javascript:void(0);" class="btn sub_btn book_service_button" title="{{ __('users.see_available_time_btn')}}" id="reset_option">{{ __('users.see_available_time_btn')}}<i class="far fa-calendar-alt" style="margin-left: 10px;font-size: 20px;"></i></a>
                         </div>
                        </div>

                        <div class="col-xs-6 col-md-12 p-0">
                        <sapn class="productStockOut">{{ __('messages.service_not_available') }}</span> 
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
                                 <!-- <div class="form-group col-md-6">
                                  <label>{{ __('lang.product_buyer_email')}}</label>
                                  <input type="text"  id="" class="location form-control" value="@if(!empty($Service->address)) {{$Service->address}} @endif" placeholder="{{ __('lang.location')}}" readonly>
                                </div>
                                 <div class="form-group col-md-6">
                                  <label>{{ __('users.postal_code_label')}}</label>
                                  <input type="text"  id="" class="location form-control" value="@if(!empty($Service->address)) {{$Service->address}} @endif" placeholder="{{ __('lang.location')}}" readonly>
                                </div> -->


                                <div class="form-group col-md-6">
                                  <label>{{ __('lang.service_date')}}</label>
                                  <select  class="service_date form-control">
                                    <option value="">{{ __('lang.select_label')}}</option>
                                    @php $usedDates = array(); @endphp
                                    @if(!empty($serviceAvailability))
                                      @foreach($serviceAvailability as $availability) 

                                        
                                        @if(!in_array($availability->service_date,$usedDates) && $availability->service_date >= date('Y-m-d'))
                                          <option value="{{$availability->service_date}}" class="service_time_option">{{$availability->service_date}}</option>
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
                                  <p style="font-size: 12px;">Betalning sker på plats </p>
                                </div>
                            
                                <?php 
                             
                                 if(!empty($loginUserFname) && !empty($loginUserLname) && !empty($loginUserEmail) && !empty($loginUserAddress) && !empty($loginUserPostcode) && !empty($loginUserCity)){
                                      $styleDisable ="";
                                      $fillDetailsErr="";
                                  }else{
                                    $styleDisable   = "disabled";
                                    $fillDetailsErr = trans('errors.complete_buyer_profile');

                                  }
                                ?>
                                <p style="color:red;font-size: 12px;">{{$fillDetailsErr}}
                                    @if(!empty($fillDetailsErr))
                                    <a href="{{route('frontBuyerProfile')}}" class="de_col">{{ __('users.buyer_profile_update_title')}}</a> 
                                    @endif
                                </p>
                                
                                <div class="form-group">
                                    <button style="width: 60%;    margin-left: 18%;    height: 45px;" type="button" class="btn sub_btn" @if(Auth::guard('user')->id()) onclick="sendServiceRequest();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif {{$styleDisable}}> {{ __('lang.book_service_btn')}}  </button>
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
        <div>
            <div class="best_seller_container" style="margin-top:60px;margin-bottom:25px;">
              <!--<div class="col-md-12"  style="margin-left: -33px;"> - commented alvisa 21-march -->
              <div class="row" style="margin-right: 0%;">
              <div class="col-md-6">
              <h2  class="review_title"  id="show-all-review">{{ __('users.review_title')}}</h2>
              <hr class="hr_product_details">
              <!-- </div> -->
                @if(!empty($serviceReviews))
                  @php $i=1; @endphp
                  <div class="seller_loader review_loader" style="display: :none"></div>
                  @foreach($serviceReviews as $review)

                  <div class="row reviews-container"> 
                    <div class="col-md-2">
                      @if(!empty($review['profile']))
                      <img src="{{url('/')}}/uploads/Buyer/buyerIcons/{{$review['profile']}}" class="ratingUserIcon">
                    @else 
                      <img src="{{url('/')}}/uploads/Buyer/buyerIcons/no-image.png" class="ratingUserIcon">
                    @endif
                    </div>
                    <div class="col-md-5">
                       <p class="ratingUname"><?php 
                       if(!empty($review['fname']) && !empty($review['lname'])){
                        $review_name = $review['fname']." ".$review['lname'];
                      }else{
                        $review_name = 'Anonymous';
                      }
                  
                      echo $review_name.", ".date('d F, Y',strtotime($review['updated_at']));?></p>
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
                   <div class="col-md-4">
                      @if(Auth::guard('user')->id()==$review['user_id'])
                      <a href="javascript:void(0)" title="{{trans('lang.edit_label')}}" style="color:#06999F;" class="edit_service_review" review_comment="{{$review['comments']}}" user_rating_hid="{{$review['service_rating']}}" rating_id="{{$review['rating_id']}}" service_id="{{$review['service_id']}}"><i class="fas fa-edit"></i> </a>

                      <a href="javascript:void(0)" style="color:#06999F;" onclick="deleteServiceReview('<?php echo base64_encode($review['rating_id']); ?>')"  title="{{trans('lang.delete_title')}}" class=""><i class="fas fa-trash"></i></a>
                    @endif
                   </div>
                  </div>
                  <hr>
                  @php $i++; @endphp
                  @endforeach
                  {!! $serviceReviews->links() !!}
                @endif
              </div>
               <div class="col-md-offset-1 col-md-5">
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
            <div class="best_seller_container col-md-12 product_container-list-5">
                <!-- <h3>{{ __('lang.popular_items_in_market_head')}}</h3> -->
                <h2 class="other_watched_products">{{ __('users.other_watched_product')}}</h2>
                <ul class="product_details best_seller pl-0">
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
                  <input type="hidden" name="service_link" class="service_link" id="service_link" value="">
                  <input type="hidden" name="service_id" class="service_id" id="service_id" value="">

                <div class="form-group">
                  <label>{{ __('lang.sort_by_rating')}} <span class="text-danger">*</span></label>
                
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


<!-- service review edit model Form -->
 <div class="modal fade" id="editReviewmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('lang.txt_your_review')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        <div class="loader-seller loader-review" style="display: none;"></div>
        <div class="modal-body">
            <div class="container">
            <form action="{{route('FrontContactStore')}}"  enctype="multipart/form-data" method="post" class="storeContactform">
              @csrf
                <input type="hidden" name="rating_id" class="rating_id" id="rating_id" value="">
                 <input type="hidden" name="service_id" class="service_id" id="service_id" value="">
                <input type="hidden" name="user_rating_hid" class="user_rating_hid" id="user_rating_hid" value="">

                <div class="form-group">
                  <label>{{ __('lang.sort_by_rating')}} <span class="text-danger">*</span></label>
                    <div class="star-rating" style="font-size:15px;">
                      <select class='rating user_rating' id='' data-id='' data-rating="">
                        <option value="1" >1</option>
                        <option value="2" >2</option>
                        <option value="3" >3</option>
                        <option value="4" >4</option>
                        <option value="5" >5</option>
                      </select>
                    </div>
                </div>

                <div class="form-group">
                  <label style="margin-top:10px;">{{ __('lang.txt_comments')}} <span class="text-danger">*</span></label>
                  <textarea class="user_review form-control contact-store-message review_comment" name="user_review" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}" id="user_review" required></textarea>

               
                </div>
            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="update_service_review btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-black gray_color login_btn" data-dismiss="modal">{{ __('lang.close_btn')}}</button>
        </div>        
      </div>
    </div>
  </div>
   
  <!-- end service review edit  model Form -->

<script type="text/javascript">   
$( document ).ready(function() {
  var service_d = $('.service_time_option').val();

  if(service_d == '' || typeof service_d=='undefined'){
    $('.productStockOut').show();
    $('.book_service_button').attr('disabled', 'disabled');
  }

  $(document).on("click",".book_service_button",function(event) {
     var role_id =$("#loginUserRoleId").val();
     if(role_id==2){
      showErrorMessage(you_need_buyer_profile);
     }else if(role_id==1){
      $('#bookServiceModal').modal('show');
     }else{
       window.location.href = "{{ route('frontLogin') }}"; 
     }
  });
});

function deleteServiceReview(rating_id){
$.confirm({
      title: js_confirm_msg,
      content: are_you_sure_message,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
            $(".review_loader").show();
            $.ajax({
          url:"{{ route('frontDeleteServiceReview') }}",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{rating_id:rating_id},
          success: function(data){
        
             $(".review_loader").hide();
          
              var responseObj = $.parseJSON(data);

              if(responseObj.status == 1)
              {
                showSuccessMessageReview(responseObj.msg,'reload');
              }
              else
              {
                showErrorMessage(responseObj.msg);
              }
          }
        })
          },
          Avbryt: function () {
            
          },
      }
  });

}

$(".user_rating").each(function(){
  var currentRating = $(this).data('rating');
  $(this).barrating({
    theme: 'fontawesome-stars',
    initialRating: currentRating,
     onSelect: function(value, text, event) {
        $(".user_rating").attr("data-rating",value);
        $(".user_rating_hid").val(value);

     }
  })
});

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
                        showSuccessMessageReview(responseObj.msg,'reload');
                      }
                      else
                      {
                        if(responseObj.is_login_err == 0)
                        {
                          showErrorMessage(responseObj.msg);
                        }
                        else
                        {
                          window.location.href = siteUrl+"/front-login/buyer";
                          //showErrorMessage(responseObj.msg,'/front-login/buyer');
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
        $('#reportProductmodal').find('.service_link').val($(this).attr('service_link'));
        $('#reportProductmodal').find('.seller_name').val($(this).attr('seller_name')); 
        $('#reportProductmodal').find('.service_id').val($(this).attr('service_id'));  
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
        let user_email     = $("#user_email").val();
        let seller_id      = $("#seller_id").val();
        let seller_name    = $("#seller_name").val();
        let service_link   = $("#service_link").val();
        let service_id     = $("#service_id").val();
       $(".loader-seller").show();
        setTimeout(function(){
    $.ajax({
          url:"{{ route('FrontReportService') }}",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{user_message:user_message,user_email:user_email,service_link:service_link,service_id:service_id,seller_name:seller_name},
          success: function(output){
        
             $(".loader-seller").hide();
             $('#reportProductmodal').modal('hide');  
           
            if(output.success !=''){
              showSuccessMessageReview(output.success,'reload');
              let user_message   = $("#user_message").val('');
            }else{
              showErrorMessage(output.error);
            }
          }
        });}, 300);
      }   
    });

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
        showSuccessMessageReview("{{ __('lang.serviceRequestSent')}}",'reload');
        //location.reload();
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


/*edit review start*/
$(document).on("click",".edit_service_review",function(event) {
        $('#editReviewmodal').find('.rating_id').val($(this).attr('rating_id'));  
        $('#editReviewmodal').find('.user_rating_hid').val($(this).attr('user_rating_hid'));
        $('#editReviewmodal').find('.service_id').val($(this).attr('service_id'));  
        $('#editReviewmodal').find('.user_review').val($(this).attr('review_comment'));  
        $('#editReviewmodal').modal('show');
});

/*update review*/
$(document).on("click",".update_service_review",function(event) {
       //storeContactform 
      let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
      let error = 0;
     
    if($('#editReviewmodal').find('.review_comment').val()==''){
       showErrorMessage(required_field_error);
       error = 1;
    }
    if(error == 1){
      return false;
    }else{
    
        let user_rating_hid   = $("#user_rating_hid").val();
        let user_review       = $("#user_review").val();
        let rating_id         = $("#rating_id").val();
       $(".loader-review").show();
        setTimeout(function(){
    $.ajax({
          url:"{{ route('FrontUpdateServiceReview') }}",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{rating_id:rating_id,comments:user_review,rating:user_rating_hid},
          success: function(data){
        
             $(".loader-review").hide();
             $('#editReviewmodal').modal('hide');  
          
              var responseObj = $.parseJSON(data);
              if(responseObj.status == 1)
              {
                showSuccessMessageReview(responseObj.msg,'reload');
              }
              else
              {
                if(responseObj.is_login_err == 0)
                {
                  showErrorMessage(responseObj.msg);
                }
                else
                {
                  window.location.href = siteUrl+"/front-login/buyer";
                  //showErrorMessage(responseObj.msg,'/front-login/buyer');
                }
              }
         }
        });}, 300);
      }   
    }); 

let searchParams = new URLSearchParams(window.location.search)
if(searchParams.has('page')==true){
     $('html, body').animate({
          scrollTop: $('#show-all-review').offset().top
      }, 'slow');
}
</script>
@endsection

@extends('Front.layout.template')
@section('middlecontent')


<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
 <!-- Carousel Default -->
<section class="product_section">
  @if(!empty($header_image))
      <img class="seller_banner" src="{{$header_image}}" alt="Header Image" style="width:100%;"/>
    @endif
    <div class="container-fluid">
    <div class="container-inner-section">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
       <!--  @include('Front.category_breadcrumb') -->
        <div class="col-md-3">
          <div>
             @if(!empty($logo)) 
             <div class="seller_logo">
             <img class="seller_logo" src="{{$logo}}" alt="Logo" />&nbsp;&nbsp; </div>@endif
             <div class="clearfix"></div>
             <div class="seller_info">
              <h2>{{ $seller_name }}</h2>
              <p>{{ $city_name }}</p>
              <div class="star-rating">
                <select class='rating service_rating' data-rating="{{$totalRating}}">
                  <option value="1" >1</option>
                  <option value="2" >2</option>
                  <option value="3" >3</option>
                  <option value="4" >4</option>
                  <option value="5" >5</option>
                </select>
              </div>
          </div>
            </div>
            <div class="clearfix"></div>
             <h2> {{ __('users.butiks_info_title')}}</h2>
             <div class="clearfix"></div>
              <h4 style="margin-top: 10px;">{{ __('lang.category_label')}}</h4>
            <input type="text" name="seller_product_filter" id="seller_product_filter" class="form-control input-lg" placeholder="{{ __('users.search_item_placeholder')}}" />

            @include('Front.services_sidebar')
        </div>
        <div class="col-md-9">
           <div style="text-align: center">
            <a href="{{route('sellerServiceListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])}}" title="{{ __('lang.service_label')}} " class="@if(Request::segment(4)=='services') store-active-btn  @else store-inactive-btn @endif">{{ __('lang.service_label')}}  </a>
            <a href="{{route('sellerProductListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])}}" title="{{ __('lang.products_title')}}" class="@if(Request::segment(4)=='products') store-active-btn  @else store-inactive-btn @endif" >{{ __('lang.products_title')}} </a>
          </div>
            <span class="current_category" style="display:none;">{{$category_slug}}</span>
            <span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>
            <span class="current_sellers" style="display:none;">{{$seller_id}}</span>
            
            <div class="product_container">
            <div class="row">               
                  <div class="row"><div class="col-md-12">&nbsp;</div></div>
                  @if(!empty($store_information))
                  <div class="col-md-12">
                    <p class="store_info">{!! $store_information !!}</p>
                  </div>
                  @endif
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row">
                <div class="col-md-3 pull-right">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by')}} : </label>
                      <select class="form-control" name="sort_by" id="sort_by" class="sort_by_name" onchange="listService()">
                        <!--   <option value="">---- {{ __('lang.sort_by_option')}} ----</option> -->
                          <option value="name">{{ __('lang.sort_by_name')}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 pull-right">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by_order')}} : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" class="sort_by_order" onchange="listService()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="asc">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  
                </div>
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>

        <div class="col-md-12">
          <hr>

          <div class="col-md-2">
            <h2>{{ __('users.review_title')}}</h2>
          </div>

          <div class="col-md-9">
            @if(!empty($serviceReviews))
              @foreach($serviceReviews as $review)
              <div class="row">
                <div class="col-md-1">
                  @if(!empty($review['profile']))
                    <img src="{{url('/')}}/uploads/Buyer/resized/{{$review['profile']}}" class="ratingUserIcon">
                  @else 
                    <img src="{{url('/')}}/uploads/Buyer/resized/profile.png" class="ratingUserIcon">
                  @endif                   
                </div>

                <div class="col-md-5">
                  <p class="ratingUname"><?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>

                  <div class="star-rating" style="font-size:unset;pointer-events: none;">
                    <select class='rating service_rating' data-rating="{{$review['service_rating']}}">
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
              @endforeach
               {!! $serviceReviews->links() !!}
            @endif
          </div>
        </div>
        <!-- <div class="col-md-12">
          <div class="mtb-20">
          <h2>{{ __('users.store_terms_title')}}</h2>
          @if(!empty($getTerms))
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Store Policy : </p>
              <p style="margin-left: 10px;">{{$getTerms->store_policy}}</p>
            </div>
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Return Policy : </p>
              <p style="margin-left: 10px;">{{$getTerms->return_policy}}</p>
            </div>
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Shipping Policy : </p>
              <p style="margin-left: 10px;">{{$getTerms->shipping_policy}}</p>
            </div>             
          @endif
        </div>
        </div> -->
         <div class="col-md-12"> <hr>
          <div class="col-md-3">
            <h2>{{ __('users.store_terms')}}</h2>
          </div>
        <div class="col-md-9" style="margin-top: 25px;">
          <button class="tablink" onclick="openPage('PaymentPolicy', this, 'red')" id="defaultOpen" style="">{{ __('users.payment_btn')}}</button>
          <button class="tablink" onclick="openPage('ShippingPolicy', this, 'blue')">{{ __('users.shipping_btn')}}</button>
          <button class="tablink" onclick="openPage('ReturnPolicy', this, 'green')">{{ __('users.return_btn')}}</button>
          <button class="tablink" onclick="openPage('BookingPolicy', this, 'white')">{{ __('users.booking_btn')}}</button>
      
          @if(!empty($getTerms))
          <div id="PaymentPolicy" class="tabcontent">
          <!--   <h3>{{ __('users.store_policy_label')}}</h3> -->
            <p class="policies">{{@$getTerms->payment_policy}}</p>
            </div>

            <div id="ShippingPolicy" class="tabcontent">
            <!-- <h3>{{ __('users.shipping_policy_label')}}</h3> -->
            <p class="policies">{{@$getTerms->shipping_policy}}</p>
            </div>

            <div id="ReturnPolicy" class="tabcontent">
           <!--  <h3>{{ __('users.return_policy_label')}}</h3> -->
            <p class="policies">{{@$getTerms->return_policy}}</p> 
            </div>

            <div id="BookingPolicy" class="tabcontent">
            <!-- <h3>{{ __('users.shipping_policy_label')}}</h3> -->
            <p class="policies">{{@$getTerms->booking_policy}}</p>
            </div>
        @endif

      
   
        </div>
      </div>
        <!-- contact shop -->
        <div class="col-md-12">
        <div class="mtb-20">
         <a href="javascript:void(0);"  class="btn btn-icon btn-info contact-store" title="{{ __('users.contact_store')}}" id="{{$seller_id}}" seller_email="{{$seller_email}}" seller_name="{{$seller_name}}">{{ __('users.contact_store')}} </a>
</div>
        </div>
    </div>
</div>

<!-- add subcategory model Form -->
 <div class="modal fade" id="contactStoremodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('users.contact_store_head')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        <div class="loader"></div>
        <div class="modal-body">
            <div class="container">
            <form action="{{route('FrontContactStore')}}"  enctype="multipart/form-data" method="post" class="storeContactform">
              @csrf
                  <input type="hidden" class="seller_id"  name="hid" id="seller_id" value="">
                  <input type="hidden" name="seller_email" class="seller_email" id="seller_email" value="">
                  <input type="hidden" name="seller_name" class="seller_name" id="seller_name" value="">
                <div class="form-group">
                  <label>{{ __('users.your_message_label')}} <span class="text-danger">*</span></label>
                  <textarea class="user_message  form-control contact-store-message" name="user_message" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}"  placeholder="{{ __('users.subcategory_name_label')}}" id="user_message"required></textarea>
               
                </div>
            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="conact-store-save btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-black gray_color login_btn" data-dismiss="modal">{{ __('lang.close_btn')}}</button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- end contact seller model Form -->


    </div> <!-- /container -->
</section>

<script type="text/javascript">

$(document).ready(function() {
  var read_more_btn = "{{ __('users.read_more_btn')}}";
  var read_less_btn = "{{ __('users.read_less_btn')}}";

	get_service_count();

	var maxLength = 200;
	$(".store_info").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = '<span class="trim-text">'+ myStr.substring(0, maxLength) + '</span>';
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append( '<span class="more-text">' + myStr.substring(0, maxLength) + removedStr + '</span>');
			$(this).append(' <a href="javascript:void(0);" class="read-more">...'+read_more_btn+'</a>');
		}
	});	
	$(".more-text").hide();
	$(".read-more").click(function(){
		var moreLess = $(".more-text").is(':visible') ? '...'+read_more_btn : '...'+read_less_btn;
		$(this).text(moreLess);
		$(this).parent('.store_info').find(".more-text").toggle();
		$(this).parent('.store_info').find(".trim-text").toggle();
	});	
});

function get_service_count(){
  $.ajax({
    url:siteUrl+"/getServiceCatSubcatList",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'sellers' : $('.current_sellers').text(),'search_seller_product':$("#seller_product_filter").val()},
    success:function(data)
    { 
      $.each(data, function(k, v) {
        $("#user_id").val();
        //$("#serviceCount_"+k).text(v.service_count);
        if(v.service_count == 0){
            $("#serviceCount_"+k).parent().css('display','none');
        }else{
          
           $("#serviceCount_"+k).text(v.service_count);
        }

      });
    }
   });
}
$( "#seller_product_filter" ).keyup(function() {

     get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),'',$(".current_search_string").text(),$("#seller_product_filter").val()) ;
     get_service_count();
});

function listService() {
   get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$(".current_search_string").text(),$("#seller_product_filter").val()) ;
}


/*function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();
  var seller_product_filter = $("#seller_product_filter").val();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price_filter, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string,'search_seller_product':seller_product_filter },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.service_listings').html(responseObj.services);
     $('.seller_list_content').html(responseObj.sellers);
    }
   });
}
*/

function selectSellers()
{
    var Sellers = '';
    $(".sellerList").each(function(){
      if($(this).prop('checked'))
      {
        if(Sellers == '')
        {
            Sellers = $(this).val();
        }
        else {
          Sellers += ','+$(this).val();

        }
      }
    });
    $(".current_sellers").html(Sellers);
    listService();

}



$(document).on("click",".contact-store",function(event) {
        
        $('#contactStoremodal').find('.seller_id').val($(this).attr('id'));
        $('#contactStoremodal').find('.seller_email').val($(this).attr('seller_email')); 
        $('#contactStoremodal').find('.seller_name').val($(this).attr('seller_name'));      
        $('#contactStoremodal').modal('show'); 
});

$(document).on("click",".conact-store-save",function(event) {
       //storeContactform
      if($('#contactStoremodal').find('.user_message').val()!='') {
        let user_message   = $("#user_message").val();
        let seller_email   = $("#seller_email").val();
        let seller_id      = $("#seller_id").val();
        let seller_name      = $("#seller_name").val();
		
		$(".loader").show();
        
		setTimeout(function(){
        $.ajax({
          url:"{{ route('FrontContactStore') }}",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{user_message:user_message,seller_email:seller_email,seller_id:seller_id,seller_name:seller_name},
          success: function(output){
            
			$(".loader").hide();
			$('#contactStoremodal').modal('hide'); 
			
			if(output.success !=''){
              showSuccessMessage(output.success);
              let user_message   = $("#user_message").val('');
            }else{
              showErrorMessage(output.error);
            }
          }
        });}, 300);
      } else{
          showErrorMessage(please_add_your_message);
      }    
    });

/*js code for policy tabs*/
function openPage(pageName,elmnt) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
    // tablinks[i].style.backgroundColor = "";
      tablinks[i].classList.remove("tablink-active");
    }
    document.getElementById(pageName).style.display = "block";
    elmnt.classList.add("tablink-active");
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
  //   tablink-active
       var element = document.getElementById("defaultOpen");
   element.classList.add("tablink-active");

$(".page-link").click(function(){   
  var attr_val = $(this).attr('href');
  if(attr_val !='' && attr_val != undefined){
    window.location.href = attr_val; 
  }
});
</script>
@endsection

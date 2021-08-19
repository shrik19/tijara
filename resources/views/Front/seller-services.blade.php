@extends('Front.layout.template')
@section('middlecontent')


<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
 <!-- Carousel Default -->
<section class="product_section">
  @if(!empty($header_image))
      <img src="{{$header_image}}" alt="Header Image" style="width:100%;"/>
    @endif
    <div class="container">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
        @include('Front.category_breadcrumb')
        <div class="col-md-3">
          <div style="display: flex">
             @if(!empty($logo)) 
             <img src="{{$logo}}" alt="Logo" style="width:100px;height:100px;" />&nbsp;&nbsp;@endif
              <h2>{{ $seller_name }}</h2>
              <p style="margin-top: 60px;margin-left: -115px;">{{ $city_name }}</p>
              <div class="star-rating" style="font-size:unset;margin-top: 80px;margin-left: -64px;pointer-events: none;">
                <select class='rating service_rating' data-rating="{{$totalRating}}">
                  <option value="1" >1</option>
                  <option value="2" >2</option>
                  <option value="3" >3</option>
                  <option value="4" >4</option>
                  <option value="5" >5</option>
                </select>
              </div>
            
            </div>
             <h2> {{ __('users.butiks_info_title')}}</h2>

              <h4 style="margin-top: 50px;">{{ __('lang.category_label')}}</h4>
            <input type="text" name="seller_product_filter" id="seller_product_filter" class="form-control input-lg" placeholder="{{ __('users.search_item_placeholder')}}" />

            @include('Front.services_sidebar')
        </div>
        <div class="col-md-9">
           <div style="text-align: center">
            <a href="{{route('sellerProductListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])}}" title="{{ __('lang.products_title')}}" class="@if(Request::segment(4)=='products') store-active-btn  @else store-inactive-btn @endif" >{{ __('lang.products_title')}} </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{route('sellerServiceListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])}}" title="{{ __('lang.service_label')}} " class="@if(Request::segment(4)=='services') store-active-btn  @else store-inactive-btn @endif">{{ __('lang.service_label')}}  </a>
          </div>
            <span class="current_category" style="display:none;">{{$category_slug}}</span>
            <span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>
            <span class="current_sellers" style="display:none;">{{$seller_id}}</span>
            
            <div class="product_container">
                <div class="row">
                  <div class="row"><div class="col-md-12">&nbsp;</div></div>
                  @if(!empty($description))
                  <div class="col-md-12 text-center">
                    {!! $description !!}
                  </div>
                  @endif
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by_order')}} : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" class="sort_by_order" onchange="listService()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="asc">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by')}} : </label>
                      <select class="form-control" name="sort_by" id="sort_by" class="sort_by_name" onchange="listService()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="name">{{ __('lang.sort_by_name')}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>

        <div class="col-md-12">
          <hr>
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
                  <select class='rating service_rating' data-rating="{{$review['service_rating']}}">
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

        <div class="col-md-12">
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

        <!-- contact shop -->
        <div class="col-md-12">
         <a href="javascript:void(0);"  class="btn btn-icon btn-info contact-store" title="'.__('users.add_subcategory_title').'" id="{{$seller_id}}" seller_email="{{$seller_email}}" seller_name="{{$seller_name}}">contact store </a>
        </div>
    </div>


<!-- add subcategory model Form -->
 <div class="modal fade" id="contactStoremodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('users.contact_store_head')}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                  <textarea class="user_message" name="user_message" rows="3" cols="20" placeholder="{{ __('lang.txt_comments')}}"  placeholder="{{ __('users.subcategory_name_label')}}" id="user_message"required></textarea>
               
                </div>
            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="conact-store-save btn btn-icon icon-left btn-success"><i class="fas fa-check"></i>{{ __('lang.save_btn')}}</button>
        <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>{{ __('lang.close_btn')}}</button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- end contact seller model Form -->


    </div> <!-- /container -->
</section>

<script type="text/javascript">
$( document ).ready(function() {
     get_service_count();
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
        $("#serviceCount_"+k).text(v.service_count);

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
              alert(output.success);
              let user_message   = $("#user_message").val('');
            }else{
              alert(output.error);
            }
          }
        });}, 300);
      } else{
          alert("please add your message");
      }    
    });

</script>
@endsection

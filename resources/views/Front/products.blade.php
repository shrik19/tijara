@extends('Front.layout.template')
@section('middlecontent')

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>

 <!-- Carousel Default -->
<section class="product_section p_155">
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
  <div class="container-inner-section">
 
        @if(Request::segment(1) =='services' || Request::segment(1) =='products' || Request::segment(1) =='annonser')
          @include('Front.category_breadcrumb')
         
        @endif
         <div class="row">
           @if(Request::segment(1) =='annonser')
           <div class="col-md-12">
              <a href="{{route('frontProductCreate')}}" title="{{ __('lang.add_product')}}" class="btn btn-black btn-sm debg_color a_btn login_btn add_ads_btn"><span>+ {{ __('users.add_ads_btn')}}</span> </a>
           </div>
         
            <div class="col-md-12">
              <div class="annonser-image" style="text-align:center">
                <img src="{{url('/')}}/assets/img/tijara_ann.jpeg">
              </div>
            </div>
           @endif
         <div class="col-md-12"> 
          <div class="col-md-3"></div>
          @if( Request::segment(1) !='annonser')
              <div class="col-md-3">
                <label class="checkbox toggle candy" onclick=""  style="width:100px">
                  <input id="view" type="checkbox" />
                  <p>
                    <span class="product_sorting_filter" id="productSearchFilter" product_link="{{route('AllproductListing')}}">{{ __('lang.category_product_title')}}</span>
                    <span class="product_sorting_filter" id="serviceSearchFilter" service_link="{{route('AllserviceListing')}}">{{ __('lang.category_service_title')}}</span>
                  </p>                  
                  <a class="slide-button"></a>                  
                 </label>                   
              </div>
            @endif
                  <div class="col-md-3 prod-service-filter">
                  
                    <div class="form-group">
                      <label class="product_sorting_filter">{{ __('lang.sort_by_order')}} : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="getListing()">
                          <option value=""  class="product_sorting_filter_option">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option selected value="asc" class="product_sorting_filter_option">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc" class="product_sorting_filter_option">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 prod-service-filter">
                    <div class="form-group">
                      <label class="product_sorting_filter">{{ __('lang.sort_by')}} : </label>
                      <select class="form-control" name="sort_by" id="sort_by" onchange="getListing()">
                        <!--   <option value="">---- {{ __('lang.sort_by_option')}} ----</option> -->
                        <option value="popular" class="product_sorting_filter_option">{{ __('lang.sort_by_popular_product')}}</option>
                        <option value="price" class="product_sorting_filter_option">{{ __('lang.sort_by_price')}}</option>
                        <option value="discount" class="product_sorting_filter_option">{{ __('lang.sort_by_discount')}}</option>
                        <option value="name" class="product_sorting_filter_option">{{ __('lang.sort_by_name')}}</option>
                        <option value="rating" class="product_sorting_filter_option">{{ __('lang.sort_by_rating')}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                </div>

        <div class="">
			<div class="col-md-3 col-annonser-sidebar">

			  @if(Request::segment(1) =='annonser')
				@include('Front.annonser_sidebar')
			  @else
				@include('Front.products_sidebar')
			  @endif
			</div>
			<div class="col-md-9 products-page">

				<span class="current_category" style="display:none;">{{$category_slug}}</span>
				<span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>
				<span class="current_sellers" style="display:none;">{{$seller_id}}</span>
				<span class="current_search_string" style="display:none;">{{$search_string}}</span>
				<span class="current_role_id" style="display:none;">{{$current_role_id}}</span>
				<div class="product_container filter_product_list">
				   
					<span class="product_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            <span class="service_listings"><div style="text-align:center;margin-top:50px;display: none"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
				</div>
			</div>
		</div>
    </div>
    </div>

    </div> <!-- /container -->
</section>
@if(Request::segment(1) !='annonser')
<section>
    <div class="container-fluid">
        <div class="row">
          <div class="container-inner-section">
            <div class="best_seller_container">
                <!-- <h3>{{ __('lang.popular_items_in_market_head')}}</h3> -->
                <h2 class="other_watched_products">{{ __('users.other_watched_product')}}</h2>
                <ul class="product_details best_seller" id="other_watched_products" style="margin-left:4px;">
        					@foreach($PopularProducts as $product)
                            @include('Front.products_widget')
        					@endforeach
        				 </ul>

                 <ul class="product_details best_seller" id="other_watched_services" style="margin-left:4px;display: none;">                 
                  @foreach($PopularServices as $service)
                    @include('Front.services_widget')
                  @endforeach
                 
                 </ul>
            </div>


</div>
        </div>
    </div>
</section>
@endif

<script type="text/javascript">
$( document ).ready(function() {
  $("#productSearchFilter").addClass("filterActive");
  $("#serviceSearchFilter").addClass("inactiveFilter");
  $('.service_listings').hide();
});
   
 $("#productSearchFilter").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }

});

  $("#serviceSearchFilter").click(function(){
  var attr_val = $(this).attr('service_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }

});

function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var city_filter = $('#city_name').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();
  var current_role_id = $(".current_role_id").text();

  $.ajax({
    url:siteUrl+"/get_product_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 
      'sellers' : sellers, 'price_filter' : price_filter,'city_filter' : city_filter, 
       'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string
       , 'role_id' : current_role_id,'path':window.location.pathname },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.product_listings').html(responseObj.products);
     $('.seller_list_content').html(responseObj.sellers);

     $(".product_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1)
            // Get element id by data-id attribute
            var el = this;
            var el_id = el.$elem.data('id');
            
            // rating was selected by a user
            if (typeof(event) !== 'undefined') {
              
              var split_id = el_id.split("_");
              var product_id = split_id[1]; // postid

              $.confirm({
                 title: txt_your_review,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Skicka', //submit
                         btnClass: 'btn-blue',
                         action: function () {
                             var comments = this.$content.find('.name').val();
                             if(!comments){
                               showErrorMessage(txt_comments_err);
                               return false;
                             }
                             $(".loader").show();
                             $.ajax({
                             url:siteUrl+"/add-review",
                             headers: {
                               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                             },
                             type: 'post',
                             data : {'rating': value, 'product_id' : product_id, 'comments' : comments},
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
            @else
                showErrorMessage("{{ __('errors.login_buyer_required')}}");
            @endif
           }
          
         });
      });

    }
   });
}
var segment = "<?php echo Request::segment(1);?>";

if(segment !='annonser'){
  var price_filter = $("#price_filter").slider({});
  price_filter.on('slideStop',function(){
      getListing();
  });
}


$("#city_name").on("input", function() {
  getListing();
});


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
    getListing();

}

</script>
@endsection

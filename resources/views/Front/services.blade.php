@extends('Front.layout.template')
@section('middlecontent')

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/slick-theme.min.css">
<script src="{{url('/')}}/assets/front/js/slick.min.js"></script>

 <!-- Carousel Default -->
<section class="product_section p_155">
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
        <div class="container-inner-section">
        
        @if(Request::segment(1) =='services' || Request::segment(1) =='products')
          @include('Front.category_breadcrumb')
        @endif
        <div class="cat-details">
        <div class="col-md-3 col-products-sidebar desktop-view" id="tjfilter">
          <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
            @include('Front.services_sidebar')
        </div>

        <div class="col-md-9 services-page p-0 tj-pmnor">
			     <div class="row tj-filter-sec">
                  <div class="col-md-6">
                    @if( Request::segment(1) !='annonser')

                    <div class="col-md-3">
                      <label class="checkbox toggle candy label_width" onclick="">
                        <input id="view" type="checkbox" />
                        <p>
                          <span class="product_sorting_filter" id="productSearchFilter"  product_link="{{route('AllproductListing')}}" style="margin-left: 14px;cursor: pointer;">{{ __('lang.category_product_title')}}</span>
                          <span class="product_sorting_filter" id="serviceSearchFilter" service_link="{{route('AllserviceListing')}}" style="margin-left: 14px;cursor: pointer;">{{ __('lang.category_service_title')}}</span>
                        </p>                  
                        <a class="slide-button"></a>                  
                       </label>                   
                    </div>
                    @endif
                  </div>

                  <button class="tj-filter-toggle-btn" data-toggle="collapse" data-target="#tjfilter"><span>Filtrera</span><img src="{{url('/')}}/assets/img/tjfilter.png"></button>

                  <div style="margin-top: -1%;" class="col-md-3 prod-service-filter">
                    <div class="form-group">
                      <label class="product_sorting_filter">{{ __('lang.sort_by_order')}} : </label>
                      <select class="form-control tjselect" name="sort_by_order" id="sort_by_order" onchange="listServices()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option selected value="asc">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  <div style="margin-top: -1%;" class="col-md-3 prod-service-filter pr-w-0">
                    <div class="form-group">
                      <label class="product_sorting_filter">{{ __('lang.sort_by')}} : </label>
                      <select class="form-control tjselect" name="sort_by" id="sort_by" onchange="listServices()">
                         <!--  <option value="">---- {{ __('lang.sort_by_option')}} ----</option> -->
                         <!--  <option value="name">{{ __('lang.sort_by_name')}}</option> -->

                           <option value="popular" class="product_sorting_filter_option">{{ __('lang.sort_by_popular_product')}}</option>
                          <option value="price" class="product_sorting_filter_option">{{ __('lang.sort_by_price')}}</option>
                          <option value="discount" class="product_sorting_filter_option">{{ __('lang.sort_by_discount')}}</option>
                          <option value="name" class="product_sorting_filter_option">{{ __('lang.sort_by_name')}}</option>
                          <option value="rating" class="product_sorting_filter_option">{{ __('lang.sort_by_rating')}}</option>
                      </select>
                    </div>
                  </div>
                </div>
            <span class="current_category" style="display:none;">{{$category_slug}}</span>
            <span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>
            <span class="current_sellers" style="display:none;">{{$seller_id}}</span>
            <span class="current_search_string" style="display:none;">{{$search_string}}</span>
            <div class="row product_container product_container-list-4 service_page scrollElement">
                
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
             <!--    <span class="product_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span> -->
            </div>
        </div>
        </div>
    </div>

</div>
    </div> <!-- /container -->
</section>

<section>
    <div class="container-fluid">
    <div class="container-inner-section">
        <div class="row">
            <div class="best_seller_container col-md-12 product_container-list-5">
                <!-- <h3>{{ __('lang.popular_items_in_market_head')}}</h3> -->
                <!-- <h2>{{ __('lang.best_seller_head')}}</h2> -->
                <h2 class="other_watched_products">{{ __('users.other_watched_product')}}</h2>
                <ul class="product_details best_seller pl-0 tjbestseller">
                  @foreach($PopularServices as $service)
                            @include('Front.services_widget')
                  @endforeach
				 </ul>
   
        </div>


</div>
        </div>
    </div>
</section>

<script type="text/javascript">
  $( document ).ready(function() {
  $("#serviceSearchFilter").addClass("filterActive");
  $("#productSearchFilter").addClass("inactiveFilter");
 // $("#serviceSearchFilter").addClass("inactiveFilter");
});
  
$("#productSearchFilter").click(function(){
  $("#product_service_search_type").val('products');
  $('#product_service_search_from').attr('action',siteUrl+"/products");
  $('#product_service_search_from').attr('onSubmit','');
  $('#product_service_search_from').submit();

});

  $("#serviceSearchFilter").click(function(){
  $("#product_service_search_type").val('products');
    $('#product_service_search_from').attr('action',siteUrl+"/services");
    $('#product_service_search_from').attr('onSubmit','');
    $('#product_service_search_from').submit();

});
function listServices(){

  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  if($('.col-products-sidebar').hasClass("in"))
	 $('.tj-closebutton').trigger('click');
  get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),$(".current_search_string").text(),$("#seller_product_filter").val());
}

$("#city_name").on("input", function() {
	if($('.col-products-sidebar').hasClass("in"))
	 $('.tj-closebutton').trigger('click');
   get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),$(".current_search_string").text(),$("#seller_product_filter").val());
});
/*
function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();
  var city_filter = $('#service_city').val();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers,  'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string, 'city_filter':city_filter },
    success:function(data)
    {
      //$('.product_listings').html(data);
      var responseObj = $.parseJSON(data);
      $('.service_listings').html(responseObj.services);
      $('.seller_list_content').html(responseObj.sellers);
    }
   });
}*/
var price_filter = $("#price_filter").slider({});
price_filter.on('slideStop',function(){
	if($('.col-products-sidebar').hasClass("in"))
	 $('.tj-closebutton').trigger('click');
     get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),$(".current_search_string").text(),$("#seller_product_filter").val());
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
	if($('.col-products-sidebar').hasClass("in"))
	 $('.tj-closebutton').trigger('click');
     get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),'','',$(".current_search_string").text(),$("#seller_product_filter").val());

}

$(document).ready(function(){
  /*search by city */
 $('#service_city').keyup(function(){ 
        var query = $(this).val();
       
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('getCity') }}",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
           $('#cityList').fadeIn();  
                    $('#cityList').html(data);
          }
         });
        }
    });


   $(document).on('click', '.city_autocomplete', function(){  
        $('#service_city').val($(this).text());  
        $('#cityList').fadeOut();  
    }); 

   $("#service_city").on("change", function() {
	if($('.col-products-sidebar').hasClass("in"))
	 $('.tj-closebutton').trigger('click');	   
       get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),'','',$(".current_search_string").text(),$("#seller_product_filter").val());
    }); 

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
});

</script>

@endsection

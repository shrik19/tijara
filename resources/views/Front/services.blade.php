@extends('Front.layout.template')
@section('middlecontent')

<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fontawesome-stars.css">
<script src="{{url('/')}}/assets/front/js/jquery.barrating.min.js"></script>
 <!-- Carousel Default -->
<section class="product_section">
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="container-inner-section">
      <div class="row" style="margin-top:40px;">
        
       @if(Request::segment(1) =='services' || Request::segment(1) =='products')
          @include('Front.category_breadcrumb')
        @endif
        <div class="col-md-3">
            @include('Front.services_sidebar')
        </div>
        <div class="col-md-9 services-page">
            <span class="current_category" style="display:none;">{{$category_slug}}</span>
            <span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>
            <span class="current_sellers" style="display:none;">{{$seller_id}}</span>
            <span class="current_search_string" style="display:none;">{{$search_string}}</span>
            <div class="product_container">
                <div class="row">
                  <div class="col-md-6">
                    <h2>{{ __('lang.trending_service_head')}}</h2>
                    <hr class="heading_line"/>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by_order')}} : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="listServices()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="asc">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by')}} : </label>
                      <select class="form-control" name="sort_by" id="sort_by" onchange="listServices()">
                         <!--  <option value="">---- {{ __('lang.sort_by_option')}} ----</option> -->
                          <option value="name">{{ __('lang.sort_by_name')}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
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
    </div>
</section>

<script type="text/javascript">
function listServices(){

  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),$(".current_search_string").text(),$("#seller_product_filter").val());
}

$("#city_name").on("input", function() {
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

   $("#service_city").on("input", function() { 
       get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$('#service_city').val(),'','',$(".current_search_string").text(),$("#seller_product_filter").val());
    }); 

});

</script>

@endsection

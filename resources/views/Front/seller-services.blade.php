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
                      <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="getListing()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="asc">{{ __('lang.sort_by_asc')}}</option>
                          <option value="desc">{{ __('lang.sort_by_desc')}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('lang.sort_by')}} : </label>
                      <select class="form-control" name="sort_by" id="sort_by" onchange="getListing()">
                          <option value="">---- {{ __('lang.sort_by_option')}} ----</option>
                          <option value="name">{{ __('lang.sort_by_name')}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="{{url('/')}}/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>
    </div>


    </div> <!-- /container -->
</section>

<script type="text/javascript">

function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price_filter, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.service_listings').html(responseObj.services);
     $('.seller_list_content').html(responseObj.sellers);
    }
   });
}


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

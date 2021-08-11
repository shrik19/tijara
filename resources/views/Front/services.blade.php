@extends('Front.layout.template')
@section('middlecontent')


 <!-- Carousel Default -->
<section class="product_section">
    <div class="container">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
        
        @include('Front.category_breadcrumb')
        <div class="col-md-3">
            @include('Front.services_sidebar')
        </div>
        <div class="col-md-9">
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

$(document).ready(function(){
  /*search by city */
 $('#city_name').keyup(function(){ 
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


   $(document).on('click', 'li', function(){  
        $('#service_city').val($(this).text());  
        $('#cityList').fadeOut();  
    }); 

   $("#service_city").on("input", function() { 
      getListing();
    }); 

});

</script>

@endsection

 @if(Request::segment(1) =='services' || Request::segment(1) =='products' || Request::segment(1) =='annonser')
  <h2 class="all_cat_sidebar_label" id="all_cat_label">{{ __('lang.category_title')}}</h2>
  <ul class="seller_cat_list">
    <li>
      <a href="{{route('AllserviceListing')}}" title="{{ __('lang.all_category')}}"  class="all_category_bold">{{ __('lang.all_category')}}</a>
    </li>
  </ul>
@endif

 <link rel="stylesheet" href="{{url('/')}}/assets/front/js/css/bootstrap-slider.css" />
<script src="{{url('/')}}/assets/front/js/bootstrap-slider.js"></script>

@if(Request::segment(4) !='products')
<div class="category_list_box show_service_cat_sidebar" >
  
 <!--  <h2>{{ __('lang.service_categories_head')}}</h2> -->
  <ul class="seller_cat_list">
    @php $j=0; @endphp

    @foreach($ServiceCategories as $CategoryId=>$Category)
      @php
        $j++;
        $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activeservicemaincategory';
        
      @endphp

      @if(!empty($ServiceCategories[$CategoryId]['subcategory']))
        <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" @if(empty($is_seller)) href="{{url('/')}}/services/{{ $Category['category_slug']}}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/services/{{ $Category['category_slug'] }}" @endif aria-expanded="true" aria-controls="collapseOne"><a @if(empty($is_seller)) href="{{url('/')}}/services/{{ $Category['category_slug'] }} " @else href="javascript:void(0)" sub="{{url('/')}}/seller/{{ $link_seller_name }}/{{ $Category['category_slug'] }}" @endif @if(Request::segment(1) =='seller') class = 'seller_page_botton_border cat_subcat_redirect' @endif >{{$Category['category_name']}}<span style="float: right;" id="serviceCount_{{$CategoryId}}"></span></a></li>

        <ul id="servicesubcategories<?php echo $j; ?>" class="service_subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
        @foreach($ServiceCategories[$CategoryId]['subcategory'] as $subcategory)
        <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activeservicesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/services/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="javascript:void(0)" sub="{{url('/')}}/seller/{{ $link_seller_name }}/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" class="cat_subcat_redirect" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
        @endforeach
        </ul>
      @endif
    @endforeach
  </ul>
</div>
@endif
<div>

<div>&nbsp;</div>
 @if(Request::path() != "/" && Request::segment(3) !='products' && Request::segment(3) !='services' &&  Request::segment(1) != 'seller')
 
    <div style="margin-left: 4px;"> 
      <label class="price_label">{{ __('lang.sort_by_price')}}</label>

      <div>&nbsp;</div>
      <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="10000" data-slider-step="500" data-slider-value="[0,10000]"/>
    </div>
      <!-- <b>€ 1000</b> -->
      <div>&nbsp;</div>
      <div>&nbsp;</div>
   @if(Request::segment(1) != 'seller')
   <div style="margin-left: 4px;"> 
  <label class="filter_lable">{{ __('users.place_label')}}</label>
  <select class="form-control tjselect" name="city_name" id="service_city">
      <option value=""  class="product_sorting_filter_option"> {{ __('lang.whole_sweden_option')}} </option>
      @if(!empty($allCities))
        @foreach($allCities as $city)
        <option value="{{@$city->city}}" class="product_sorting_filter_option">{{@$city->city}}</option>       
        @endforeach
      @endif
    </select>
  </div>
  @endif


<!--   <div>&nbsp;</div>
 <div style="margin-left: 4px;"> 
  <div class="category_button">
  <button class="show_all_cat"  product_link="{{url('/')}}/products?all=1">{{ __('users.all_btn')}}</button>
  <button class="show_product_cat"  product_link="{{url('/')}}/products">{{ __('lang.product_label')}}</button>
  <button class="show_service_cat" service_link="{{url('/')}}/services">{{ __('lang.service_label')}}</button>
</div>
</div> -->
@endif



@if(Request::path() != "/" && Request::segment(1) !='products' && Request::segment(1) !='services')
  @if(empty($is_seller))
  <!-- Seller Listing -->
    <h2 class="de_col">{{ __('lang.sellers_head')}}</h2>
    <span class="seller_list_content"></span>
  @endif
@endif
</div>

<script type="text/javascript">
$(document).ready(function(){
    var pageURL = $(location).attr("href");
    var array = pageURL.split('/');
    var lastsegment = array[array.length-1];
    
    if(lastsegment == 'products'){
       $(".show_product_cat").addClass('active');
     }else if(lastsegment == 'services'){
        $(".show_service_cat").addClass('active');
     }else{
        $(".show_all_cat").addClass('active');
     }

   //$(".show_all_cat").addClass('active');
  $(document).on('click', '.show_all_cat', function(){  
      var attr_val = $(this).attr('product_link');
      window.location.href = attr_val;
      $(this).addClass('active');
      $('.show_product_cat').removeClass('active');
      $('.show_service_cat').removeClass('active');
      $('.show_product_cat_sidebar').show();
      $('.show_service_cat_sidebar').show();
      $('.all_cat_label').show();
  });

  $(document).on('click', '.show_product_cat', function(){  
    var attr_val = $(this).attr('product_link');
    window.location.href = attr_val;
    $(this).addClass('active');
    $('.show_all_cat').removeClass('active');
    $('.show_service_cat').removeClass('active');
    $('.show_product_cat_sidebar').show();
    $('.show_service_cat_sidebar').hide();
    $('.all_cat_label').hide();
  });

  $(document).on('click', '.show_service_cat', function(){  
    var attr_val = $(this).attr('service_link');
    window.location.href = attr_val;
    $(this).addClass('active');
    $('.show_all_cat').removeClass('active');
    $('.show_product_cat').removeClass('active');
    $('.show_service_cat_sidebar').show();
    $('.show_product_cat_sidebar').hide();
    $('.all_cat_label').hide();
  });


  var search_string = $(".current_search_string").text();
   if(search_string !=''){
    $(".all_category_bold").addClass("activeAllcategory");
  }

});
</script>
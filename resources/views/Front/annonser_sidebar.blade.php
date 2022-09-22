
  <h2 class="all_cat_sidebar_label" id="all_cat_label">{{ __('lang.category_title')}}</h2>
  <ul class="seller_cat_list">
    <li>
      <a href="{{route('AllbuyerProductListing')}}" title="{{ __('lang.all_category')}}"  class="all_category_bold">{{ __('lang.all_category')}}</a>
    </li>
  </ul>



<div class="category_list_box show_product_cat_sidebar"  id="accordion">
  
  <ul class="seller_cat_list">
 
    @php 
      $i=0; $j=0;
      $productsads =  'annonser';
    @endphp


    @foreach($Categories as $CategoryId=>$Category)
      @php $i++; 
        $cls='';
        if(!empty($Category['product_count'][$j])){
        $product_count = $Category['product_count'][$j]; 
        }else{
        $product_count = '';
        }

        if($category_slug==$Category['category_slug'])
        $cls  ='activemaincategory';
              

      @endphp
   
      @if(!empty($Categories[$CategoryId]['subcategory']))

        <li class="expandCollapseSubcategory  <?php echo $cls; ?>" @if(empty($is_seller)) 
           href="{{url('/')}}/{{$productsads}}/{{ $Category['category_slug'] }}" @else 
           href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/annonser/{{ $Category['category_slug'] }}" @endif aria-expanded="true" aria-controls="collapseOne"><a @if(empty($is_seller)) 
           href="{{url('/')}}/{{$productsads}}/{{ $Category['category_slug'] }}" @else 
           href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/annonser/{{ $Category['category_slug'] }}" @endif  id="main_cat_name<?php echo $i; ?>" @if(Request::segment(1) =='seller') class = 'seller_page_botton_border' @endif>{{$Category['category_name']}} <span style="float: right;" id="productCount_{{$CategoryId}}"></span></a></li>

        <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse  <?php if($cls!='') echo 'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">

        @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
          <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug'])
           class="activesubcategory" @endif  @if(empty($is_seller)) 
           href="{{url('/')}}/{{$productsads}}/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else 
           href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/annonser/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
        @endforeach
        </ul>
      @endif

    @php $j++; @endphp
    @endforeach
  </ul>
</div>

 

<div>
  <div>&nbsp;</div>
  <div style="margin-left: 4px;margin-bottom: 60px;"> 
    <label class="price_label">{{ __('users.place_label')}}</label>
    <select class="form-control tjselect" name="city_name" id="city_name">
      <option value=""  class="product_sorting_filter_option"> {{ __('lang.whole_sweden_option')}} </option>
      @if(!empty($allCities))
      @foreach($allCities as $city)
      <option value="{{@$city->city}}" class="product_sorting_filter_option">{{@$city->city}}</option>       
      @endforeach
      @endif
    </select>
  </div>
  <div>&nbsp;</div>
</div>

<script type="text/javascript">
 
$(document).ready(function(){ 
  var search_string = $(".current_search_string").text();
   if(search_string !=''){
    $(".all_category_bold").addClass("activeAllcategory");
   }
});
</script>
@php
  //dd(Request::path());
@endphp
@if(Request::segment(4) !='services')
<div class="category_list_box"  id="accordion">
        <h2 class="de_col">{{ __('lang.categories_head')}}</h2>
        <ul class="category_list">
        @php $i=0; @endphp
        @foreach($Categories as $CategoryId=>$Category)
        @php $i++;
        $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activemaincategory';
        else if($category_slug=='' && $i==1) $cls  =       'activemaincategory';
         @endphp
                @if(!empty($Categories[$CategoryId]['subcategory']))
                
                <li class="expandCollapseSubcategory <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}} <span style="float: right;">@if(!empty(Request::segment(4))){{count($Categories[$CategoryId]['subcategory'])}}@endif</span></a></li>

                <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
                <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
                @endforeach
                </ul>
                @endif
            @endforeach
        </ul>
        <div>&nbsp;</div>
        @if(Request::path() != "/" && Request::segment(4) !='products' && Request::segment(4) !='services')
          <link rel="stylesheet" href="{{url('/')}}/assets/front/js/css/bootstrap-slider.css" />
          <script src="{{url('/')}}/assets/front/js/bootstrap-slider.js"></script>
          <h2 class="de_col">{{ __('lang.price_filter_head')}}</h2>
          <!-- <br /><b>€ 10</b> -->
          <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="150000" data-slider-step="500" data-slider-value="[0,150000]"/>
          <!-- <b>€ 1000</b> -->
          <div>&nbsp;</div>
              <input type="text" name="city_name" id="city_name" class="form-control input-lg" placeholder="{{ __('users.enter_city_placeholder')}}" />
          <div id="cityList"></div>
          <div>&nbsp;</div>
          @if(empty($is_seller))
          <!-- Seller Listing -->
          <h2 class="de_col">{{ __('lang.sellers_head')}}</h2>
          <span class="seller_list_content"></span>
          @endif
        @endif
</div>
@endif

@if(Request::segment(4) !='products')
<div class="category_list_box"  id="accordion">
        <h2 class="de_col">{{ __('lang.service_categories_head')}}</h2>
        <ul class="category_list">
        @php $j=0;
        
        @endphp
        @foreach($ServiceCategories as $CategoryId=>$Category)
                @php $j++;
                  $cls='';
                  if($category_slug==$Category['category_slug'])
                                  $cls  =       'activeservicemaincategory';
                  else if( $j==1) $cls  =       'activeservicemaincategory';
                @endphp
                @if(!empty($ServiceCategories[$CategoryId]['subcategory']))
                  <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#servicesubcategories<?php echo $j; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}} <span style="float: right;">@if(!empty(Request::segment(4))){{count($ServiceCategories[$CategoryId]['subcategory'])}}@endif</span></a></li>

                  <ul id="servicesubcategories<?php echo $j; ?>" class="service_subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                  @foreach($ServiceCategories[$CategoryId]['subcategory'] as $subcategory)
                      <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activeservicesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/services/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/services/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
                  @endforeach
                  </ul>
                @endif
          @endforeach
        </ul>
        <div>&nbsp;</div>
        
</div>
@endif
<script type="text/javascript">
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
        $('#city_name').val($(this).text());  
        $('#cityList').fadeOut();  
    });  

});
</script>
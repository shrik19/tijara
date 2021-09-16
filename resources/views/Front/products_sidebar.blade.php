 @if(Request::segment(1) =='services' || Request::segment(1) =='products')
  <label class="all_cat_label">{{ __('lang.all_category')}}</label>
@endif

@if(Request::segment(4) !='services')
<div class="category_list_box show_product_cat_sidebar"  id="accordion">

  <h2>{{ __('lang.categories_head')}}</h2>
  <ul class="seller_cat_list">
    @php $i=0; $j=0; @endphp

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
        else if($category_slug=='' && $i==1) 
        $cls = 'activemaincategory';
        

      @endphp

      @if(!empty($Categories[$CategoryId]['subcategory']))

        <li class="expandCollapseSubcategory  <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#" id="main_cat_name<?php echo $i; ?>">{{$Category['category_name']}} <span style="float: right;" id="productCount_{{$CategoryId}}"></span></a></li>

        <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse  <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">

        @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
          <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
        @endforeach
        </ul>
      @endif

    @php $j++; @endphp
    @endforeach
  </ul>
</div>
@endif

@if(Request::segment(4) !='products')
  <div class="category_list_box show_service_cat_sidebar"  id="accordion">
  <h2 style="display:none;" class="">{{ __('lang.service_categories_head')}}</h2>
  <ul class="seller_cat_list">

  @php $j=0; @endphp

  @foreach($ServiceCategories as $CategoryId=>$Category)
    @php 
      $j++;
      $cls='';
      if($category_slug==$Category['category_slug'])
        $cls  =       'activeservicemaincategory';
      
    @endphp
<?/*else if( $j==1) $cls  =       'activeservicemaincategory';*/?>
    @if(!empty($ServiceCategories[$CategoryId]['subcategory']))
      <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#servicesubcategories<?php echo $j; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}} <span style="float: right;" id="serviceCount_{{$j}}"></span></a></li>

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
<div>

<div>&nbsp;</div>
@if(Request::path() != "/" && Request::segment(4) !='products' && Request::segment(4) !='services')
  <link rel="stylesheet" href="{{url('/')}}/assets/front/js/css/bootstrap-slider.css" />
  <script src="{{url('/')}}/assets/front/js/bootstrap-slider.js"></script>
  <hr>
  <label>{{ __('lang.sort_by_price')}}</label>

  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="150000" data-slider-step="500" data-slider-value="[0,150000]"/>
  <!-- <b>€ 1000</b> -->
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <label>{{ __('users.place_label')}}</label>
  <input type="text" name="city_name" id="city_name" class="form-control input-lg" placeholder="{{ __('users.enter_city_placeholder')}}" />
  <div id="cityList"></div>
  <div>&nbsp;</div>
  <div>&nbsp;</div>
  <label>{{ __('users.type_label')}}</label>
  <div class="category_button">
  <button class="show_all_cat">{{ __('users.all_btn')}}</button>
  <button class="show_product_cat">{{ __('lang.product_label')}}</button>
  <button class="show_service_cat">{{ __('lang.service_label')}}</button>
  </div>
  <div>&nbsp;</div>
  @if(Request::path() != "/" && Request::segment(1) !='products' && Request::segment(1) !='services')
    @if(empty($is_seller))
    <!-- Seller Listing -->
      <h2 class="de_col">{{ __('lang.sellers_head')}}</h2>
      <span class="seller_list_content"></span>
    @endif
  @endif
  @endif
</div>

<script type="text/javascript">
 
$(document).ready(function(){
   $(".show_all_cat").addClass('active');
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

   $(document).on('click', '.city_autocomplete', function(){  
        $('#city_name').val($(this).text());  
        $('#cityList').fadeOut();  
    });  

   $(document).on('click', '.show_all_cat', function(){  
       $(this).addClass('active');
       $('.show_product_cat').removeClass('active');
       $('.show_service_cat').removeClass('active');
        $('.show_product_cat_sidebar').show();
        $('.show_service_cat_sidebar').show();
        $('.all_cat_label').show();

    });

   $(document).on('click', '.show_product_cat', function(){ 
    
        $(this).addClass('active');
        $('.show_all_cat').removeClass('active');
        $('.show_service_cat').removeClass('active');
        $('.show_product_cat_sidebar').show();
        $('.show_service_cat_sidebar').hide();
        $('.all_cat_label').hide();
        
    });

   $(document).on('click', '.show_service_cat', function(){  
        $(this).addClass('active');
        $('.show_all_cat').removeClass('active');
        $('.show_product_cat').removeClass('active');
        $('.show_service_cat_sidebar').show();
        $('.show_product_cat_sidebar').hide();
        $('.all_cat_label').hide();
    });

});
</script>
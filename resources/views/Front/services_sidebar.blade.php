@php
  //dd(Request::path());
@endphp
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
                  <li class="expandCollapseServiceSubcategory <?php echo $j; ?> <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#servicesubcategories<?php echo $j; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}}</a></li>

                  <ul id="servicesubcategories<?php echo $j; ?>" class="service_subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activeservicesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                  @foreach($ServiceCategories[$CategoryId]['subcategory'] as $subcategory)
                      <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activeservicesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/services/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/services/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
                  @endforeach
                  </ul>
                @endif
          @endforeach
        </ul>
        <div>&nbsp;</div>
        
        
        
          @if(empty($is_seller))
          <!-- Seller Listing -->
          <h2 class="de_col">{{ __('lang.sellers_head')}}</h2>
          <span class="seller_list_content"></span>
          @endif
       
</div>

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
                <li class="expandCollapseSubcategory <?php echo $cls; ?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}}</a></li>

                <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse <?php if($cls!='') echo'in activesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
                <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activesubcategory" @endif  @if(empty($is_seller)) href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/{{ base64_encode($seller_id) }}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
                @endforeach
                </ul>
                @endif
            @endforeach
        </ul>
        <div>&nbsp;</div>
</div>

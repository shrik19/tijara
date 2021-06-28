@php
  //dd(Request::path());
@endphp
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
                <li style="list-style: none;" ><a @if($subcategory_slug==$subcategory['subcategory_slug']) class="activesubcategory" @endif href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}">{{ $subcategory['subcategory_name'] }}</a></li>
                @endforeach
                </ul>
                @endif
            @endforeach
        </ul>
        <div>&nbsp;</div>
        @if(Request::path() != "/" )
        <link rel="stylesheet" href="{{url('/')}}/assets/front/js/css/bootstrap-slider.css" />
        <script src="{{url('/')}}/assets/front/js/bootstrap-slider.js"></script>
        <style>
          .slider-horizontal > .bs-tooltip-top
          {
            opacity: 1 !important;
            z-index: 1000 !important;
          }
          .slider .tooltip.bs-tooltip-top
          {
            margin-top: -30px !important;
          }
        </style>
        <h2 class="de_col">{{ __('lang.price_filter_head')}}</h2>
        <!-- <br /><b>€ 10</b> -->
        <input id="price_filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="40000" data-slider-step="500" data-slider-value="[0,40000]"/>
        <!-- <b>€ 1000</b> -->
        <div>&nbsp;</div>
        <!-- Seller Listing -->
        <h2 class="de_col">{{ __('lang.sellers_head')}}</h2>
        <span class="seller_list_content"></span>
        @endif
</div>

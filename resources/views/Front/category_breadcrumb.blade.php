<ul class="breadcrumb-category">
	@if(Request::segment(1) =='annonser')
	  <li><a href="{{route('AllbuyerProductListing')}}">{{ __('lang.all_category')}}</a></li>
	@else
	  <li><a href="@if(!empty($all_cat_link)){{$all_cat_link}} @endif"> {{ __('lang.all_category')}}</a></li>
	@endif
	  <li><a href="@if(!empty($category_link)){{$category_link}} @endif" id="breadcrumb_category"> @if(!empty($category_name)) 
	  </a> <span>></span> <a href="@if(!empty($category_link)){{$category_link}} @endif" id="breadcrumb_category">{{ $category_name}}@endif
		</a>
	</li>
	  <li><a href="@if(!empty($subcategory_link)){{$subcategory_link}} @endif" id="breadcrumb_subcategory"> @if(!empty($subcategory_name)) 
	  </a>  <span> > </span><a href="@if(!empty($subcategory_link)){{$subcategory_link}} @endif" id="breadcrumb_subcategory"> {{ $subcategory_name}} @endif
		</a></li>
</ul>

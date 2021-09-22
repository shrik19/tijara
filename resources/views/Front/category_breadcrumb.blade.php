<ul class="breadcrumb-category">
	  <li><a href="@if(!empty($all_cat_link)){{$all_cat_link}} @endif">{{ __('lang.all_category')}}</a></li>
	  <li><a href="@if(!empty($category_link)){{$category_link}} @endif" id="breadcrumb_category"> @if(!empty($category_name)) > {{ $category_name}}@endif</a></li>
	  <li><a href="@if(!empty($subcategory_link)){{$subcategory_link}} @endif" id="breadcrumb_subcategory"> @if(!empty($subcategory_name)) >{{ $subcategory_name}} @endif</a></li>
</ul>

<ul class="breadcrumb-category">
	  <li><a href="#">{{ __('lang.categories_head')}}</a></li>
	  <li><a href="#" id="breadcrumb_category"> >@if(!empty($category_name)) {{ $category_name}}@endif</a></li>
	  <li><a href="#" id="breadcrumb_subcategory"> @if(!empty($subcategory_name)) >{{ $subcategory_name}} @endif</a></li>
</ul>

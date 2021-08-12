<ul class="breadcrumb-category">

	@if(!empty($category_name) && !empty($subcategory_name))
	  <li><a href="#">{{ __('lang.categories_head')}}</a></li>
	  <li><a href="#" id="breadcrumb_category"> > {{ $category_name}}</a></li>
	  <li><a href="#" id="breadcrumb_subcategory"> > {{ $subcategory_name}}</a></li>
	 @endif
<!--   <li><a href="#" id="breadcrumb_subcategory">Request::segment(3) !='services'</a></li> -->
</ul>

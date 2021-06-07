@extends('Front.layout.template')
@section('middlecontent')
         
 <!-- Carousel Default -->
<section class="product_section">
    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-3">
            @include('Front.categories_sidebar')
        </div>
        <div class="col-md-9">  
            <span class="current_category" style="display:none;">{{$category_slug}}</span>
            <span class="current_subcategory" style="display:none;">{{$subcategory_slug}}</span>           
                <div class="product_container">
                    <h4>{{ __('lang.popular_items_in_market_head')}}</h4>
                    <h2>{{ __('lang.trending_product_head')}}</h2>
                    <hr class="heading_line"/>
                   <span class="product_listings"></span>
                    
                </div>
        </div>
    </div>

      
    </div> <!-- /container -->  
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.best_seller_head')}}</h2>
                <ul class="product_details best_seller">
					@foreach($PopularProducts as $product)
                    @include('Front.products_widget')
					@endforeach
				 </ul>
            </div>
            
            
         
        </div>
    </div>
</section>

<script type="text/javascript">
   
</script>
@endsection

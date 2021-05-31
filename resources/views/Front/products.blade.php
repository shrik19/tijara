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
                <div class="product_container">
                    <h4>{{ __('lang.popular_items_in_market_head')}}</h4>
                    <h2>{{ __('lang.trending_product_head')}}</h2>
                    <hr class="heading_line"/>
                    <ul class="product_details">
					@foreach($Products as $product)
                    @include('Front.products_widget')
						@endforeach
                       </ul>
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

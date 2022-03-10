
@if(is_object($Products))
<ul class="product_details service_list">
    @foreach($Products as $product)
      @include('Front.products_widget')
    @endforeach
</ul>
<div class="pagination_div">
{!! $Products->links() !!}
</div>
@else
<div class="col-md-12" style="text-align:center;color: red;font-size: 20px;margin-top: 20px;">{{ $Products }}</div>
@endif

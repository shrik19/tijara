
@if(is_object($Products))
<ul class="product_details">
    @foreach($Products as $product)
      @include('Front.products_widget')
    @endforeach
</ul>
<div style="text-align: center">
{!! $Products->links() !!}
</div>
@else
<div style="text-align:center;color: red;font-size: 20px;margin-top: 20px;">{{ $Products }}</div>
@endif

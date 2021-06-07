
<ul class="product_details ">
    @foreach($Products as $product)
    @include('Front.products_widget')
        @endforeach
</ul>
        {!! $Products->links() !!}
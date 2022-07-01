
@if(is_object($Products))
@php 
$cssVariable = '';$annonserClass='';
if(strpos(@$path, 'annonser') !== false)
{
 	$cssVariable ="padding-left:53px";
	$annonserClass	=	'hideRatings';
}

@endphp
<ul class="product_details product_service_list {{$annonserClass}}" style="{{$cssVariable}}">
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

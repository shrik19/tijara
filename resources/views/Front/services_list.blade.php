
@if(is_object($Services))
@php 
$cssVariable = '';
if(strpos(@$path, 'services') !== false)
{
 	$cssVariable ="padding-left:38px";
}

@endphp
<ul class="product_details service_list" style="{{$cssVariable}}">
    @foreach($Services as $service)
      @include('Front.services_widget')
    @endforeach
</ul>
<div class="pagination_div">
{!! $Services->links() !!}
</div>
@else
<div class="col-md-12" style="text-align:center;color: red;font-size: 20px;margin-top: 20px;">{{ $Services }}</div>
@endif

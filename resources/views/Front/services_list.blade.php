
@if(is_object($Services))
<ul class="product_details">
    @foreach($Services as $service)
      @include('Front.services_widget')
    @endforeach
</ul>
{!! $Services->links() !!}
@else
<div style="text-align:center;color: red;font-size: 20px;margin-top: 20px;">{{ $Services }}</div>
@endif

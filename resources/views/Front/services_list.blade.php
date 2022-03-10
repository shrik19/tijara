
@if(is_object($Services))
<ul class="product_details service_list">
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

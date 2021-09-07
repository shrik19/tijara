@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>


<section class="product_details_section-1">
<div class="loader"></div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
              <h2>{{ __('lang.shopping_cart_product')}} {{ __('messages.txt_wishlist')}}</h2>
              <hr class="heading_line"/>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th>{{ __('lang.shopping_cart_product')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_price')}}</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  @if(!empty($details))
                  @foreach($details as $orderProduct)       
                    <tr>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="{{$orderProduct['product']->product_link}}"> 
                            @if($orderProduct['product']['image'])
                              <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object" style="width: 72px; height: 72px;">
                            @else
                              <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            @endif
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
                                <h5 class="media-heading"> {{$orderProduct['variant_attribute_id']}} </h5>
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format($orderProduct['product']->price,2) }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                        <button class="" style="color:#05999F;" onclick="addToCartWishlist('{{ $orderProduct['variant_id'] }}')" title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></button>&nbsp;&nbsp;
                        <button class="" style="color:red;" onclick="removeWishlistProduct('{{ $orderProduct['id'] }}')" title="Remove"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="3">{{ __('messages.wishlist_empty')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>


<!-- services wishlist -->

<section class="product_details_section-1">
<div class="loader"></div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-md-12">
              <h2>{{ __('lang.service_label')}} {{ __('messages.txt_wishlist')}}</h2>
              <hr class="heading_line"/>
            </div>
          </div>
            <table class="table table-hover" style="margin-bottom:60px;">
                <thead>
                    <tr>
                        <th>{{ __('lang.service_label')}}</th>
                        <th class="text-right">{{ __('lang.shopping_cart_price')}}</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  @if(!empty($detailsService))
                  @foreach($detailsService as $reqService)
                    <tr>
                        <td class="col-sm-4 col-md-4">
                        <div class="media">
                            <a class="thumbnail pull-left" href="{{$reqService['service']->service_link}}"> 
                            @if($reqService['service']['images'])
                              <img src="{{url('/')}}/uploads/ServiceImages/resized/{{$reqService['service']->images}}" class="media-object" style="width: 72px; height: 72px;">
                            @else
                              <img src="{{url('/')}}/uploads/ServiceImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                            @endif
                              
                            </a>
                            <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                <h4 class="media-heading"><a href="{{$reqService['service']->service_link}}">{{ $reqService['service']->title }}</a></h4>
                               <!--  <h5 class="media-heading"> {{$reqService['variant_attribute_id']}} </h5> -->
                                <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                            </div>
                        </div></td>
                        <td class="col-sm-2 col-md-2 text-right"><strong>{{ number_format((float)$reqService['service']->service_price,2) }} kr</strong></td>
                        <td class="col-sm-1 col-md-1 text-right">
                     <!--    <button class="btn btn-success" onclick="addToCartWishlist('{{ $reqService['id'] }}')" title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></button> -->
                        <a href="{{$reqService['service']->service_link}}" style="color:#05999F;" class="">{{ __('lang.book_service')}}</i></a>
                        &nbsp;&nbsp;
                        <button class=" " style="color:red;" onclick="removeWishlistProduct('{{ $reqService['id'] }}')" title="Remove"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="3">{{ __('messages.wishlist_empty')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>

@endsection

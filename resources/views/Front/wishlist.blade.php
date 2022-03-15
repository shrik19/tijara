@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
</style>

<div class="mid-section p_155">
<div class="container-fluid">
<div class="container-inner-section-1">
<div class="row">
<div class="col-md-12"> 
<div class="">
    <div class="card">
        <div class="card-header row ">
            <h2 class="page_heading service_wishlist_h2">{{ __('users.your_favourite_title')}}</h2>       
        </div>
    </div>
    <section class="product_details_section-1">
    <div class="loader"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12">
            <div class="row">
           
       
                <table class="table table-hover wishlist-table" style="margin-bottom:60px;">
                    <thead>
                        <tr>
                            <th>{{ __('lang.products_title')}}</th>
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
                                <a class="thumbnail pull-left custom_thumbnail" href="{{$orderProduct['product']->product_link}}"> 
                                @if($orderProduct['product']['image'])
                                  <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="media-object" style="width: 72px; height: 72px;padding: 1px;">
                                @else
                                  <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;padding: 1px;">
                                @endif
                                  
                                </a>
                                <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                    <h4 class="media-heading product_sorting_filter_option"><a href="{{$orderProduct['product']->product_link}}">{{ $orderProduct['product']->title }}</a></h4>
                                    <h5 class="media-heading product_attribute_css">  <?php echo str_replace(array( '[', ']' ), '', @$orderProduct['variant_attribute_id']);?></h5>
                                    <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                                </div>
                            </div></td>
                            <td class="col-sm-2 col-md-2 text-right"><h4 class="cart_total_css">

                                 @php 
                            
                                    $product_total =$orderProduct['product']->price;
                                    $total_tbl = str_split(strrev($product_total), 3);
                                    $total_price_tbl = strrev(implode(" ", $total_tbl));
                                    $total_price_tbl = $total_price_tbl.",00";
                                @endphp
                               {{ $total_price_tbl }} kr
                            </td></h4></td>
                            <td class="col-sm-1 col-md-1 text-right">
                            <a href="javascript:void(0);" class="" style="color:#05999F;" 
                            onclick="addToCartWishlist('{{ $orderProduct['variant_id'] }}')"
                             title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></a>&nbsp;&nbsp;
                            <a href="javascript:void(0);" class="" style="color:red;" 
                            onclick="removeWishlistProduct('{{ $orderProduct['id'] }}')" 
                            title="Remove"><i class="fas fa-trash"></i></a>
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
    <div class="card">
      
    </div>
    <section class="product_details_section-1">
    <div class="loader"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12">
            <div class="row">
                
              </div>
                <table class="table table-hover wishlist-table" style="margin-bottom:60px;">
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
                                <a class="thumbnail pull-left custom_thumbnail" href="{{$reqService['service']->service_link}}"> 
                                @if($reqService['service']['images'])
                                  <img src="{{url('/')}}/uploads/ServiceImages/resized/{{$reqService['service']->images}}" class="media-object" style="width: 72px; height: 72px;">
                                @else
                                  <img src="{{url('/')}}/uploads/ServiceImages/resized/no-image.png" class="media-object" style="width: 72px; height: 72px;">
                                @endif
                                  
                                </a>
                                <div class="media-body" style="padding-left:10px;padding-top:10px;">
                                    <h4 class="media-heading product_sorting_filter_option"><a href="{{$reqService['service']->service_link}}">{{ $reqService['service']->title }}</a></h4>
                                   <!--  <h5 class="media-heading"> {{$reqService['variant_attribute_id']}} </h5> -->
                                    <!-- <span>Status: </span><span class="text-success"><strong>In Stock</strong></span> -->
                                </div>
                            </div></td>
                            <td class="col-sm-2 col-md-2 text-right"><h4 class="cart_total_css">
                                  @php 
                                    $service_total =$reqService['service']->service_price;
                                    $service_total_tbl = str_split(strrev($service_total), 3);
                                    $service_total_price_tbl = strrev(implode(" ", $service_total_tbl));
                                    $service_total_price_tbl = $service_total_price_tbl.",00";
                                @endphp
                               {{ $service_total_price_tbl }} kr
                            </h4></td>
                            <td class="col-sm-1 col-md-1 text-right">
                         <!--    <button class="btn btn-success" onclick="addToCartWishlist('{{ $reqService['id'] }}')" title="Add"><i class="glyphicon glyphicon-shopping-cart"></i></button> -->
                            <a href="{{$reqService['service']->service_link}}" style="color:#05999F;" class="">{{ __('lang.book_service')}}</i></a>
                            &nbsp;&nbsp;
                            <a href="javascript:void(0);" class=" " 
                            style="color:red;" onclick="removeWishlistProduct('{{ $reqService['id'] }}')" 
                            title="Remove"><i class="fas fa-trash"></i></button>
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
    </div>
    </section>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection

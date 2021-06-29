@extends('Front.layout.template')
@section('middlecontent')

<style>

.container-fluid {
    margin-top: 30px;
    margin-bottom: 60px;
}

.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.40rem
}

.img-sm {
    width: 80px;
    height: 80px
}

.itemside .info {
    padding-left: 15px;
    padding-right: 7px
}

.table-shopping-cart .price-wrap {
    line-height: 1.2
}

.table-shopping-cart .price {
    font-weight: bold;
    margin-right: 5px;
    display: block
}

.text-muted {
    color: #969696 !important
}

a {
    text-decoration: none !important
}

.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 0px
}

.itemside {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    width: 100%
}

.dlist-align {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex
}

[class*="dlist-"] {
    margin-bottom: 5px
}

.coupon {
    border-radius: 1px
}

.price {
    font-weight: 600;
    color: #212529
}

.btn.btn-out {
    outline: 1px solid #fff;
    outline-offset: -5px
}

.btn-main {
    border-radius: 2px;
    text-transform: capitalize;
    font-size: 15px;
    padding: 10px 19px;
    cursor: pointer;
    color: #fff;
    width: 100%
}

.btn-light {
    color: #ffffff;
    background-color: #F44336;
    border-color: #f8f9fa;
    font-size: 12px
}

.btn-light:hover {
    color: #ffffff;
    background-color: #F44336;
    border-color: #F44336
}

.btn-apply {
    font-size: 11px
}
</style>
<section class="product_section">
    <div class="container">
      <div class="row" style="margin-top:40px;">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <h2>{{ __('lang.shopping_cart')}}</h2>
              <hr class="heading_line"/>
            </div>
          </div>
        <div class="container-fluid">
            <div class="row">
                <aside class="@if(!empty($details)) col-lg-9 @else col-lg-12 @endif">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-borderless table-shopping-cart">
                                <thead class="text-muted">
                                    <tr class="small text-uppercase">
                                        <th scope="col">{{ __('lang.shopping_cart_product')}}</th>
                                        <th scope="col" width="120">{{ __('lang.shopping_cart_quantity')}}</th>
                                        <th scope="col" width="120">{{ __('lang.shopping_cart_price')}}</th>
                                        <th scope="col" class="text-right d-none d-md-block" width="200"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($details))
                                    @foreach($details as $orderProduct)
                                    <tr>
                                        <td>
                                            <figure class="itemside align-items-center">
                                                <div class="aside">
                                                    @if($orderProduct['product']['image'])
                                                      <img src="{{url('/')}}/uploads/ProductImages/resized/{{$orderProduct['product']->image}}" class="img-sm">
                                                    @else
                                                      <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" class="img-sm">
                                                    @endif
                                                </div>
                                                <figcaption class="info"> <a href="{{$orderProduct['product']->product_link}}" class="title text-dark" data-abc="true">{{ $orderProduct['product']->title }}</a>
                                                    <!-- <p class="text-muted small">SIZE: L <br> Brand: MAXTRA</p> -->
                                                </figcaption>
                                            </figure>
                                        </td>
                                         <td> <!--<input type="text" value="{{ $orderProduct['quantity'] }}" /> -->
                                          <select name="quantity_{{ $orderProduct['id'] }}" id="quantity_{{ $orderProduct['id'] }}" class="form-control" onchange="updateCart('{{ $orderProduct['id'] }}')">
                                                <option value="1" @if($orderProduct['quantity'] == 1) selected="selected" @endif>1</option>
                                                <option value="2" @if($orderProduct['quantity'] == 2) selected="selected" @endif>2</option>
                                                <option value="3" @if($orderProduct['quantity'] == 3) selected="selected" @endif>3</option>
                                                <option value="4" @if($orderProduct['quantity'] == 4) selected="selected" @endif>4</option>
                                                <option value="5" @if($orderProduct['quantity'] == 5) selected="selected" @endif>5</option>
                                                <option value="6" @if($orderProduct['quantity'] == 6) selected="selected" @endif>6</option>
                                                <option value="7" @if($orderProduct['quantity'] == 7) selected="selected" @endif>7</option>
                                                <option value="8" @if($orderProduct['quantity'] == 8) selected="selected" @endif>8</option>
                                                <option value="9" @if($orderProduct['quantity'] == 9) selected="selected" @endif>9</option>
                                                <option value="10" @if($orderProduct['quantity'] == 10) selected="selected" @endif>10</option>
                                            </select></td>
                                        <td>
                                            <div class="price-wrap"> <var class="price">{{$orderProduct['product']->price}} kr</var>
                                              <!-- <small class="text-muted"> $9.20 each </small>  -->
                                            </div>
                                        </td>
                                        <td class="text-right d-none d-md-block">
                                          <!-- <a data-original-title="Save to Wishlist" title="" href="" class="btn btn-light" data-toggle="tooltip" data-abc="true"> <i class="fa fa-heart"></i></a>  -->
                                          <a href="javascript:void(0)" onclick="removeCartProduct('{{ $orderProduct['id'] }}')" class="btn btn-light" data-abc="true"> Remove</a> </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4">{{ __('lang.shopping_cart_no_records')}} <a href="{{route('frontHome')}}">{{ __('lang.shopping_cart_continue')}}</a></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </aside>
                @if(!empty($details))
                <aside class="col-lg-3">
                    <!-- <div class="card mb-3">
                        <div class="card-body">
                            <form>
                                <div class="form-group"> <label>Have coupon?</label>
                                    <div class="input-group"> <input type="text" class="form-control coupon" name="" placeholder="Coupon code"> <span class="input-group-append"> <button class="btn btn-primary btn-apply coupon">Apply</button> </span> </div>
                                </div>
                            </form>
                        </div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                              <div class="col-md-5">
                                <strong>{{ __('lang.shopping_cart_subtotal')}} :</strong>
                              </div>
                              <div class="col-md-7 text-right">
                                {{number_format($subTotal,2)}} kr
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <strong>{{ __('lang.shopping_cart_shipping')}} :</strong>
                              </div>
                              <div class="col-md-7 text-right">
                                0.00 kr
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-5">
                                <strong>{{ __('lang.shopping_cart_total')}} :</strong>
                              </div>
                              <div class="col-md-7 text-right">
                                <strong>{{number_format($Total,2)}} kr</strong>
                              </div>
                            </div>
                            <hr>
                            <a href="javascript:void(0);" class="btn btn-out btn-primary btn-square btn-main" data-abc="true"> {{ __('lang.shopping_cart_checkout')}} </a> <a href="{{route('frontHome')}}" class="btn btn-out btn-success btn-square btn-main mt-2" data-abc="true">{{ __('lang.shopping_cart_continue')}}</a>
                        </div>
                    </div>
                </aside>
                @endif
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function removeCartProduct(OrderDetailsId)
{
  var conf = confirm("{{ __('lang.shopping_cart_confirm_remove')}}");
  if(conf)
  {
    $.ajax({
      url:siteUrl+"/remove-from-cart",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'OrderDetailsId': OrderDetailsId},
      success:function(data)
      {
        var responseObj = $.parseJSON(data);
        if(responseObj.status == 1)
        {
            alert(responseObj.msg);
            location.reload();
        }
        else
        {
          alert(responseObj.msg);
          window.location.href = "/front-login";
        }

      }
     });
  }
}

function updateCart(OrderDetailsId)
{
  var quantity = $("#quantity_"+OrderDetailsId).val();
  $.ajax({
    url:siteUrl+"/update-cart",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'OrderDetailsId': OrderDetailsId, 'quantity' : quantity},
    success:function(data)
    {
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
          alert(responseObj.msg);
          location.reload();
      }
      else
      {
        alert(responseObj.msg);
        window.location.href = "/front-login";
      }

    }
   });
}
</script>
@endsection

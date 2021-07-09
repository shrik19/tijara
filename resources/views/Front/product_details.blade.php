@extends('Front.layout.template')
@section('middlecontent')

<section class="product_details_section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div id="carousel-custom" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    @if(!empty($ProductImages))
                    @php $i=0; @endphp
                    <div class="carousel-inner" role="listbox">
                    @foreach($ProductImages as $image)
                        @if($image['image']!='')
                        <div class="item @if($i==0) active @endif">

                            <img defaultUrl="{{url('/')}}/uploads/ProductImages/" src="{{url('/')}}/uploads/ProductImages/{{$image['image']}}" alt="..." class="img-responsive product-main-image">

                        </div>
                        @endif
                        @php $i++; @endphp
                      @endforeach

                    </div>
                    @php $i=0; @endphp
                    <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block thumbnails_slider">
                    @foreach($ProductImages as $image)
                   
                        @if($image['image']!='')
                              <li data-target="#carousel-custom" data-slide-to="@php echo $i @endphp" class=" @if($i==1) active @else img-responsive @endif">

                                <img  src="{{url('/')}}/uploads/ProductImages/resized/{{$image['image']}}" alt="..." class="img-responsive product-thumb-image {{$image['image']}}">

                              </li>
                            @endif
                            @php $i++; @endphp
                    @endforeach

                            </ol>

                  @endif


                  </div>
            </div>
            <div class="col-md-6">
                <div class="product_details_info">
                    <h2>{{$Product->title}}</h2>
                    <h3  class="product_price"></h3>
                    <div class="star-rating">
                        <input type="radio" id="5-stars" name="rating" value="5" />
                        <label for="5-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="4-stars" name="rating" value="4" />
                        <label for="4-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="3-stars" name="rating" value="3" />
                        <label for="3-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="2-stars" name="rating" value="2" />
                        <label for="2-stars" class="star"><i class="fas fa-star"></i></label>
                        <input type="radio" id="1-star" name="rating" value="1" />
                        <label for="1-star" class="star"><i class="fas fa-star"></i></label>
                      </div>
                      <p>
                        <?php echo $Product->description; ?>
                      </p>
                      <div class="row">

                         @foreach($ProductAttributes as $attribute_id=>$attribute)
                          <div class="col-md-6 col-xs-6">
                            <div class="quantity_box">
                                <h3>{{$attribute['attribute_name']}} : </h3>
                                    <input type="hidden" id="variant_type" name="variant_type" value="{{$attribute['attribute_type']}}">
                                    @if($attribute['attribute_type']=='radio')
                                    <ul class="select_product_color {{$attribute_id}}">
                                        @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <div class="squaredThree">
                                            <input style="background-color:{{$attribute_value}};" type="checkbox" class="product_checkbox_attribute" name="optionsRadios" product_id="{{$Product->id}}" id="{{$attribute_value_id}}" value="other" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"  >
                                            <span class="custom-control-indicator"></span>
                                            <label style="background-color:{{$attribute_value}};" for="{{$attribute_value_id}}">{{$attribute_value}} </label>
                                        </div>

                                        @endforeach
                                    </ul>
                                    @elseif($attribute['attribute_type']=='dropdown')
                                    <select id="select_product_variant" class="{{$attribute_id}}">
                                        <option value="">{{ __('lang.select_label')}}</option>
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <option value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"> {{$attribute_value}} </option>
                                        @endforeach
                                    </select>
                                    @elseif($attribute['attribute_type']=='textbox')
                                    <div class="inputattributes {{$attribute_id}}">
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <input type="text" value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"> {{$attribute_value}}
                                        @endforeach
                                    </div>
                                    @endif

                            </div>
                        </div>
                        @endforeach

                        </div>
                        <div class="row">
                        <div class="col-md-6 col-xs-6">
                              <div class="quantity_box">
                                <h3>Quantity:</h3>
                                <!-- <select class="drop_down_select pull-right">
                                      <option>1</option>
                                      <option>2</option>
                                      <option>3</option>
                                      <option>4</option>
                                  </select> -->
                                  <input class="drop_down_select " list="quantities" id="product_quantity" >
                                    <datalist id="quantities">
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                    <option value="6"></option>
                                    <option value="7"></option>
                                    <option value="8"></option>
                                    <option value="9"></option>
                                    <option value="10"></option>
                                    </datalist>
                              </div>
                          </div>

                        <div class="col-md-6 col-xs-6">
                            <div class="quantity_box marging_0">
                               <button type="button" class="btn add_to_cart_btn" @if(Auth::guard('user')->id()) onclick="addtoCartFromProduct();" @else onclick="javascript:alert('{{trans('errors.login_buyer_required')}}')" @endif> Add To Cart  <i class="glyphicon glyphicon-shopping-cart cart_icon"></i></button>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="best_seller_container">
                <h3>{{ __('lang.popular_items_in_market_head')}}</h3>
                <h2>{{ __('lang.best_seller_head')}}</h2>
                <ul class="product_details best_seller">
					@foreach($PopularProducts as $product)
                    @include('Front.products_widget')
					@endforeach
				 </ul>
            </div>



        </div>
    </div>
</section>

<script type="text/javascript">
function addtoCartFromProduct()
{
    var product_quantity = $("#product_quantity").val();
    var variant = $(".product_checkbox_attribute:checked").attr('data-variant');
    if($("#variant_type").val() == 'radio')
    {
        variant = $(".product_checkbox_attribute:checked").attr('data-variant');
    }
    else if($("#variant_type").val() == 'dropdown') {
        variant = $("#select_product_variant option:selected").attr('data-variant');
    }
    if(product_quantity == '')
    {
        alert('Please select quantity.');
        return false;
    }
    else if(variant === undefined) {
      alert('Please select Variant.');
      return false;
    }

    $.ajax({
      url:siteUrl+"/add-to-cart",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'product_variant': variant, 'product_quantity' : product_quantity},
      success:function(data)
      {
        var responseObj = $.parseJSON(data);
        if(responseObj.status == 1)
        {
            //alert(responseObj.msg);
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

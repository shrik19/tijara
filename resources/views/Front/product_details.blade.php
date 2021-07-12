@extends('Front.layout.template')
@section('middlecontent')
<script src="{{url('/')}}/assets/front/js/zoom-image.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/icheck-bootstrap.min.css">

<section class="product_details_section">
    <div class="loader"></div>
    <div class="container">

        <div class="row">
            <div class="col-md-6">
              <!-- Primary carousel image -->
              @if(!empty($variantData))
                @php
                $first = reset($variantData);
                @endphp
              @endif

              <div class="show" href="{{url('/')}}/uploads/ProductImages/{{$first['images'][0]}}">
                <img src="{{url('/')}}/uploads/ProductImages/{{$first['images'][0]}}" id="show-img">
              </div>
              
              <!-- Secondary carousel image thumbnail gallery -->
              <div class="small-img">
                <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img">
                <div class="small-container">
                  <div id="small-img-roll">
                    @foreach($first['images'] as $image)
                      <img src="{{url('/')}}/uploads/ProductImages/{{$image}}" class="show-small-img" alt="">
                    @endforeach
                  </div>
                </div>
                <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-right" alt="" id="next-img">
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
                      <!-- <div class="col-md-12">
                          <div class="radio icheck-success icheck-inline">
                              <input type="radio" id="success12" name="success" />
                              <label for="success12">success 1</label>
                          </div>
                          <div class="radio icheck-success icheck-inline">
                              <input type="radio" checked id="success22" name="success" />
                              <label for="success22">success 2</label>
                          </div>
                      </div> -->
                         @foreach($ProductAttributes as $attribute_id => $attribute)
                          <div class="col-md-12">
                            <div class="quantity_box" style="margin-bottom:0px !important;">
                              <div class="row">
                                <div class="col-xs-5 col-md-4">
                              <h3>{{ucwords($attribute['attribute_name'])}} : </h3> &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-5 col-md-8">
                                    <input type="hidden" id="variant_type" name="variant_type" value="{{$attribute['attribute_type']}}">
                                    @if($attribute['attribute_type']=='radio')
                                      @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        @php
                                          $checked = '';
                                          if(!empty($first['attr'][$attribute['attribute_name']]) && $first['attr'][$attribute['attribute_name']] == $attribute_value)
                                          {
                                            $checked = 'checked="checked"';
                                          }
                                        @endphp
                                        <div class="radio icheck-success icheck-inline" style="margin-top:10px !important;">
                                            <input type="radio" name="optionsRadios_{{$attribute['attribute_name']}}" product_id="{{$Product->id}}" id="{{$attribute_value_id}}" value="other" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}" {{$checked}} />
                                            <label for="{{$attribute_value_id}}">{{$attribute_value}}</label>
                                        </div>
                                      @endforeach
                                    
                                    @elseif($attribute['attribute_type']=='dropdown')
                                    <select id="select_product_variant" class="{{$attribute_id}} form-control" style="width: 80%;display: inline-block;margin-top: 5px;">
                                        <option value="">{{ __('lang.select_label')}}</option>
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                      @php
                                          $selected = '';
                                          if(!empty($first['attr'][$attribute['attribute_name']]) && $first['attr'][$attribute['attribute_name']] == $attribute_value)
                                          {
                                            $selected = 'selected="selected"';
                                          }
                                        @endphp
                                        <option value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}" {{$selected}}> {{$attribute_value}} </option>
                                        @endforeach
                                    </select>
                                    @elseif($attribute['attribute_type']=='textbox')
                                    <div class="inputattributes {{$attribute_id}}">
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <input class="form-control" type="text" value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"> {{$attribute_value}}
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                              </div>    

                            </div>
                        </div>
                        @endforeach

                        </div>
                        <div class="row">
                        <div class="col-xs-6 col-md-5"  >
                              <div class="quantity_box" style="margin-top:28px;">
                                <h3>Quantity:</h3>&nbsp;&nbsp;
                                <!-- <select class="drop_down_select pull-right">
                                      <option>1</option>
                                      <option>2</option>
                                      <option>3</option>
                                      <option>4</option>
                                  </select> -->
                                  <input class="drop_down_select " list="quantities" id="product_quantity" style="float:none;" >
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

                        <div class="col-xs-6 col-md-7">
                            <div class="quantity_box">
                               <input type="hidden" name="product_variant_id" value="{{$first['id']}}" id="product_variant_id" >           
                               <button type="button" class="btn add_to_cart_btn" @if(Auth::guard('user')->id()) onclick="addtoCartFromProduct();" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif> Add To Cart  <i class="glyphicon glyphicon-shopping-cart cart_icon"></i></button>
                            </div>
                        </div>
                      </div>

                      <div class="row">
                          <div class="col-xs-12 col-md-6">    
                          <div class="quantity_box">              
                            <h3>{{ __('lang.shopping_cart_price')}} : </h3>&nbsp;&nbsp;<span style="padding-top:6px;position:absolute;font-size:20px;">{{ number_format($first['price'],2) }} kr</span>
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
    var variant = $("#product_variant_id").val();
    
    if(product_quantity == '')
    {
      showErrorMessage(product_qty_error);
      return false;
    }
    else if(variant === undefined) 
    {
      showErrorMessage(product_variant_error);
      return false;
    }

    $(".loader").show();

    $.ajax({
      url:siteUrl+"/add-to-cart",
      headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      type: 'post',
      data : {'product_variant': variant, 'product_quantity' : product_quantity},
      success:function(data)
      {
        $(".loader").hide();
        var responseObj = $.parseJSON(data);
        if(responseObj.status == 1)
        {
          showSuccessMessage(product_add_success,'reload');
        }
        else
        {
          if(responseObj.is_login_err == 0)
          {
            showErrorMessage(responseObj.msg);
          }
          else
          {
            showErrorMessage(responseObj.msg,'/front-login');
          }
        }

      }
     });
}

//Initialize product gallery

$('.show').zoomImage();

$('.show-small-img:first-of-type').css({'border': 'solid 1px #951b25', 'padding': '2px'});
$('.show-small-img:first-of-type').attr('alt', 'now').siblings().removeAttr('alt');
$('.show-small-img').click(function () {
  $('#show-img').attr('src', $(this).attr('src'))
  $('#big-img').attr('src', $(this).attr('src'))
  $(this).attr('alt', 'now').siblings().removeAttr('alt')
  $(this).css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  if ($('#small-img-roll').children().length > 4) {
    if ($(this).index() >= 3 && $(this).index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(this).index() - 2) * 76 + 'px')
    } else if ($(this).index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

//Enable the next button

$('#next-img').click(function (){
  $('#show-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").next().attr('src'))
  $(".show-small-img[alt='now']").next().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").next().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

//Enable the previous button

$('#prev-img').click(function (){
  $('#show-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $('#big-img').attr('src', $(".show-small-img[alt='now']").prev().attr('src'))
  $(".show-small-img[alt='now']").prev().css({'border': 'solid 1px #951b25', 'padding': '2px'}).siblings().css({'border': 'none', 'padding': '0'})
  $(".show-small-img[alt='now']").prev().attr('alt', 'now').siblings().removeAttr('alt')
  if ($('#small-img-roll').children().length > 4) {
    if ($(".show-small-img[alt='now']").index() >= 3 && $(".show-small-img[alt='now']").index() < $('#small-img-roll').children().length - 1){
      $('#small-img-roll').css('left', -($(".show-small-img[alt='now']").index() - 2) * 76 + 'px')
    } else if ($(".show-small-img[alt='now']").index() == $('#small-img-roll').children().length - 1) {
      $('#small-img-roll').css('left', -($('#small-img-roll').children().length - 4) * 76 + 'px')
    } else {
      $('#small-img-roll').css('left', '0')
    }
  }
});

$("#show-img").next('div').next('div').css('z-index','999');

</script>
@endsection

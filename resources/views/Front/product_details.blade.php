@extends('Front.layout.template')
@section('middlecontent')

<style>
.show {
  width: 400px;
  height: 400px;
}

.small-img {
  width: 350px;
  height: 70px;
  margin-top: 10px;
  position: relative;
  left: 25px;
}

.small-img .icon-left, .small-img .icon-right {
  width: 12px;
  height: 24px;
  cursor: pointer;
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto 0;
}

.small-img .icon-left { transform: rotate(180deg) }

.small-img .icon-right { right: 0; }

.small-img .icon-left:hover, .small-img .icon-right:hover { opacity: .5; }

.small-container {
  width: 310px;
  height: 70px;
  overflow: hidden;
  position: absolute;
  left: 0;
  right: 0;
  margin: 0 auto;
}

.small-container div {
  width: 800%;
  position: relative;
}

.small-container .show-small-img {
  width: 70px;
  height: 70px;
  margin-right: 6px;
  cursor: pointer;
  float: left;
}

.small-container .show-small-img:last-of-type { margin-right: 0; }

.btn span.glyphicon {    			
	opacity: 0;				
}
.btn.active span.glyphicon {				
	opacity: 1;				
}


.funkyradio div {
  clear: both;
  overflow: hidden;
}

.funkyradio label {
  width: 100%;
  border-radius: 3px;
  border: 1px solid #D1D3D4;
  font-weight: normal;
}

.funkyradio input[type="radio"]:empty,
.funkyradio input[type="checkbox"]:empty {
  display: none;
}

.funkyradio input[type="radio"]:empty ~ label,
.funkyradio input[type="checkbox"]:empty ~ label {
  position: relative;
  line-height: 2.5em;
  text-indent: 3.25em;
  /*margin-top: 2em;*/
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.funkyradio input[type="radio"]:empty ~ label:before,
.funkyradio input[type="checkbox"]:empty ~ label:before {
  position: absolute;
  display: block;
  top: 0;
  bottom: 0;
  left: 0;
  content: '';
  width: 2.5em;
  background: #D1D3D4;
  border-radius: 3px 0 0 3px;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
  color: #888;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #C2C2C2;
}

.funkyradio input[type="radio"]:checked ~ label,
.funkyradio input[type="checkbox"]:checked ~ label {
  color: #777;
}

.funkyradio input[type="radio"]:checked ~ label:before,
.funkyradio input[type="checkbox"]:checked ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #333;
  background-color: #ccc;
}

.funkyradio input[type="radio"]:focus ~ label:before,
.funkyradio input[type="checkbox"]:focus ~ label:before {
  box-shadow: 0 0 0 3px #999;
}

.funkyradio-default input[type="radio"]:checked ~ label:before,
.funkyradio-default input[type="checkbox"]:checked ~ label:before {
  color: #333;
  background-color: #ccc;
}

.funkyradio-primary input[type="radio"]:checked ~ label:before,
.funkyradio-primary input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #337ab7;
}

.funkyradio-success input[type="radio"]:checked ~ label:before,
.funkyradio-success input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5cb85c;
}

.funkyradio-danger input[type="radio"]:checked ~ label:before,
.funkyradio-danger input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #d9534f;
}

.funkyradio-warning input[type="radio"]:checked ~ label:before,
.funkyradio-warning input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #f0ad4e;
}

.funkyradio-info input[type="radio"]:checked ~ label:before,
.funkyradio-info input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5bc0de;
}

</style>

<script src="{{url('/')}}/assets/front/js/zoom-image.js"></script>

<section class="product_details_section">
    <div class="container">

        <div class="row">
            <div class="col-md-6">
            <!-- Primary carousel image -->
            @if(!empty($ProductImages))
                @foreach($ProductImages as $image)
            <div class="show" href="{{url('/')}}/uploads/ProductImages/{{$image['image']}}">
              
                  <img src="{{url('/')}}/uploads/ProductImages/{{$image['image']}}" id="show-img">
                
            </div>
            @php break; @endphp
            @endforeach
            @endif 

            <!-- Secondary carousel image thumbnail gallery -->

            <div class="small-img">
              <img src="{{url('/')}}/assets/front/img/next-icon.png" class="icon-left" alt="" id="prev-img">
              <div class="small-container">
                <div id="small-img-roll">
                @if(!empty($ProductImages))
                  @foreach($ProductImages as $image)
                    <img src="{{url('/')}}/uploads/ProductImages/{{$image['image']}}" class="show-small-img" alt="">
                  @endforeach
                @endif
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

                         @foreach($ProductAttributes as $attribute_id=>$attribute)
                          <div class="col-md-6 col-xs-6">
                            <div class="quantity_box">
                                <h3>{{$attribute['attribute_name']}} : </h3>
                                    <input type="hidden" id="variant_type" name="variant_type" value="{{$attribute['attribute_type']}}">
                                    @if($attribute['attribute_type']=='radio')
                                    <ul class="select_product_color {{$attribute_id}}">
                                       
                                        <div class="funkyradio">
                                        @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                          <div class="squaredThree funkyradio-success">
                                              <input style="background-color:{{$attribute_value}};" class="product_checkbox_attribute" type="radio" name="optionsRadios_{{$attribute['attribute_name']}}" product_id="{{$Product->id}}" id="{{$attribute_value_id}}" value="other" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}" />
                                              <span class="custom-control-indicator"></span>
                                              <label for="{{$attribute_value_id}}" style="background-color:{{$attribute_value}};"> {{$attribute_value}} </label>
                                          </div>
                                          @endforeach
                                        </div>  
                                    </ul>
                                    @elseif($attribute['attribute_type']=='dropdown')
                                    <select id="select_product_variant" class="{{$attribute_id}} form-control">
                                        <option value="">{{ __('lang.select_label')}}</option>
                                    @foreach($attribute['attribute_values'] as $attribute_value_id=>$attribute_value)
                                        <option value="{{$attribute_value_id}}" data-variant="{{$attribute['variant_values'][$attribute_value_id]}}"> {{$attribute_value}} </option>
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

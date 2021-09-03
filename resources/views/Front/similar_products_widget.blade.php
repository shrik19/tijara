<li style="max-height:500px;">
  <?php 


        $product_link = url('/').'/product';
        /*if($category_slug!='')
            {
                  $product_link .=  '/'.$category_slug;
            }
            else {
              $product_link .=  '/'.$productCategories[0]['category_slug'];
            }
            if($subcategory_slug!='')
            {
                  $product_link .=  '/'.$subcategory_slug;
            }
            else {
              $product_link .=  '/'.$productCategories[0]['subcategory_slug'];
            }*/

        $product_link .=  '/'.$product->product_slug.'-P-'.$product->product_code;

       // $SellerData = UserMain::select('users.id','users.fname','users.lname','users.email')->where('users.id','=',$Product->user_id)->first()->toArray();
       // $Product->seller  = $SellerData['fname'].' '.$SellerData['lname'];

        $product_link  = $product_link;
          ?>
  <div  product_link="{{$product_link}}" class="product_data" @if($product->is_sold == '1') style="pointer-events: none;opacity: 0.4;"  @endif >
    <div class="product_img" style="min-height:280px;margin-bottom:20px;display:inline-block;background-color: white;">
      @if($product->image)
      @php 
        $productImage = explode(",",$product->image);
        $img =$productImage[0];
       @endphp
          <img src="{{url('/')}}/uploads/ProductImages/resized/{{$img}}" >
      @else
          <img src="{{url('/')}}/uploads/ProductImages/resized/no-image.png" >
      @endif
      <!-- <div class="buy_now_hover_details" style="height:280px !important;"> -->
      <div class="buy_now_hover_details">
          <ul>
              <li style="margin-left: 30%;"><a href="{{$product->product_link}}"><i class="fa fa-search"></i></a></li>
            </ul>
      </div>
    </div>

    <div class="product_info">
        <h5>{{$product['category_name']}}</h5>
         
        <a href="{{$product_link}}"><h4>@php echo substr($product->title, 0, 50) @endphp</h4></a>
        @if(!empty($product->price))
        <h6>{{$product->price}} kr</h6>
        @endif
        <h6>{{$product->seller}}</h6>
        <input type="hidden" name="product_quantity_{{$product->variant_id}}" id="product_quantity_{{$product->variant_id}}" value="1">
        <!-- <a href="javascript:void(0);" onclick="addToCart('{{$product->variant_id}}');"><i class="glyphicon glyphicon-shopping-cart"></i></a> -->
    </div>
  </div>


</li>
<script type="text/javascript">
  $(".product_data").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});
</script>
@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  
  <div class="row">
    @if($is_seller==1)
      <div class="col-md-2 tijara-sidebar">
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10 tijara-content">
        @else
        <div class="col-md-12 tijara-content">
      @endif
      
		 
	  @include('Front.alert_messages')
    <div class="seller_info">
	  <div class="card">
		<div class="card-header row seller_header">
      <h2 class="page_heading">{{ __('users.my_order_title')}}</h2>
      </div>
    </div>
    <div class="seller_mid_cont">
      
    <div class="col-md-12" style="margin-top: 20px;">
      <div class="tijara-info-section">
        <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
        <p  class="buyer-prod-content">{{__('messages.my_order_info')}}</p>
      </div>
    </div>

		<div class="col-md-12">
		    
		  
		<div class="card-body">

       <div class="card">
                              <div class="col-md-12" style="margin-bottom: 40px;margin-top: -45px;">
                                <div class="col-md-9"></div>
                                    <div  class="col-md-3">
                                        <form id="filter-buyer-order" action="{{route('frontAllBuyerOrders')}}" method="post">
                                        @csrf
                                        <?php echo $monthYearHtml;?>
                                        </form>
                                    </div>
                              </div>
                            <div class="card-body"  style="margin-top: 20px;">
                                <div class="row">
                                    @if(!empty($ordersDetails) && count($ordersDetails) > 0)

                                    @foreach($ordersDetails as $key => $value)

                                    <?php 

                              
                                  if(!empty($value->image) && count($value->image)>0) {
                                    $imagesParts    =   explode(',',$value->image); 
                                    $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
                                    }
                                    else{
                                    $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
                                    }
                                   
                                   
                                    $dated      =   date('Y-m-d',strtotime($value->created_at));

                                    $productName = (!empty($value->title)) ? $value->title : '-';
                                    // $price = $value['service_price'];
                                    $product_price = (!empty($value->price)) ? $value->price * $value->quantity: '-';
                                    $storeName = (!empty($value->store_name)) ?$value->store_name : '-';
                                  
                                  $seller_name = $value->fname." ".$value->lname;
                
                                  $seller_name = str_replace( array( '\'', '"', 
                                  ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                                  $seller_name = str_replace(" ", '-', $seller_name);
                                  $seller_name = strtolower($seller_name);
                                              
                         
                                $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($value->seller_id)."/products"; 

                               $product_link = url('/').'/product/'.$value->product_slug.'-P-'.$value->product_code;

                                $id =  $value['id'];

                                    /* $discount_price =0;
                                    if(!empty($value['discount'])) {
                                    $discount = number_format((($price * $value['discount']) / 100),2,'.','');
                                    $discount_price = $price - $discount;
                                    }
                                    */
                                    ?> 
                                    <div class="col-sm-3">
                                        <div class="card product-card">
                                      
                                            <img class="card-img-top buyer-product-img" src="{{$image}}" product_link="{{$product_link}}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{$dated}}</h5>
                                                <p class="card-text buyer-product-title"><a href="{{$product_link}}"  style="color: #000 !important">{{$productName}}</a></p>
                                                <p class="card-text order-product-price">  
                                                <span class="buyer-price" id="product_variant_price">
                                                {{number_format($product_price,2) }} kr
                                                </span> 
                                                </p>
                                                <p class="card-text order-product-title">{{ __('users.butik_btn')}} : <a href="{{$seller_link}}" style="color: #000 !important">{{$storeName}}</a></p>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach

                                    @else
                                    <div style="text-align: center;margin-top: 50px;">{{__('lang.datatables.sEmptyTable')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

  {!! $ordersDetails->links() !!}
		</div>
    </div>
	  </div>
		</div>
    </div>
 
  
</div>
  </div>
</div>
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="{{url('/')}}/assets/front/js/dataTables.bootstrap4.min.js"></script>

<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
$(".buyer-product-img").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});

$("#monthYear").on('change', function() {
  //alert( this.value );
  this.form.submit();
});
</script>
@endsection
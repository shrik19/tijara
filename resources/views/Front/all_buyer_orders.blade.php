@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <div class="row">
        <div class="col-md-12 tijara-content">
          @include('Front.alert_messages')
          <div class="seller_info border-none">
            <div class="card">
              <div class="card-header row">
                <h2 class="page_heading" style="margin-left: 27px;">{{ __('users.my_order_title')}}</h2>
              </div>
            </div>

            <div class="seller_mid_cont">
              <div class="col-md-12">
                <div class="card-body">
                    <div class="card">
                    <div class="col-md-12" >
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


                    if(!empty($value->image)) {
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

                   // $seller_name = $value->fname." ".$value->lname;
                     $seller_name = $storeName;

                    $seller_name = str_replace( array( '\'', '"', 
                    ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                    $seller_name = str_replace(" ", '-', $seller_name);
                    $seller_name = strtolower($seller_name);


                    $seller_link= url('/').'/seller/'.$seller_name."/products"; 

                    $product_link = url('/').'/product/'.$value->product_slug.'-P-'.$value->product_code;

                    $id =  $value['id'];
                   
                    ?> 
                    <div class="col-sm-3">
                    <div class="card product-card">

                    <img class="card-img-top buyer-product-img" order_id="{{base64_encode($value->order_id)}}" src="{{$image}}" product_link="{{route('frontShowOrderDetails', base64_encode($value->order_id))}}" title="{{ __('lang.txt_view')}}">
                    <div class="card-body product_all">
                    <h5 class="card-title">{{$dated}}</h5>
                    <p class="card-text buyer-product-title">
                    <a href="{{route('frontShowOrderDetails', base64_encode($value->order_id))}}" title="{{ __('lang.txt_view')}}" style="color: #000 !important;">{{$productName}}</a></p>
                    <p class="card-text order-product-price">  
                    <span class="buyer-price" id="product_variant_price">
                    {{number_format($product_price,2) }} kr
                    </span> 
                    </p>
                    <p class="card-text order-product-store-title"> <a href="{{$seller_link}}" style="color: #000 !important">{{$storeName}}</a></p>
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


    <!-- order details model -->
    <div class="modal fade" id="orderDetailsmodal">
    <div class="modal-dialog modal-lg" role = "dialog">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">{{ __('messages.txt_order_details')}}</h4>
    <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body-wrapper">
    <div class="modal-body" id="order_details_box">

    </div>
    </div>

    </div>
    </div>
    </div>
  </div>
</div>
<!-- /container -->

<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/front/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(".buyer-product-img").click(function(){

    var order_id = $(this).attr('order_id');
    $.ajax({
      headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },
      url: "{{url('/')}}"+'/order-details/'+order_id,
      type: 'get',
      // async: false,
      data:{},
      success: function(data){
        $('#orderDetailsmodal').modal('show');
        $('#order_details_box').html(data);
      }
    });

  });

  $("#monthYear").on('change', function() {
    this.form.submit();
  });

 
$(".page-link").click(function(){  
  var monthYear = $("#monthYear").val();
 
  if(monthYear != ''){
    $(this).attr('href', function() {
        return this.href + '&monthYear='+monthYear;
    });
  }
    
});

function printDiv() {		
	var product_link= $('.buyer-product-img').attr('product_link')+'?print=1';
	window.open(product_link, '_blank');
}

  function downloadPdf(DownloadLink) {
    if(DownloadLink !=''){
      window.location.href = DownloadLink; 
    } 
  }
</script>

@endsection
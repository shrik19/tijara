@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
    <div class="container-fluid">
        <div class="container-inner-section-1">
        <!-- Example row of columns -->

        <div class="row">
            <div class="col-md-12 tijara-content">
                <?php /* @if($subscribedError)
                <div class="alert alert-danger">{{$subscribedError}}</div>
                @endif */ ?>
                @include('Front.alert_messages')
                <div class="seller_info">

                    <div class="card">
                        <div class="card-header row seller_header">
                        <h2 class="page_heading">{{ __('users.my_booking_title')}}</h2>
                        <!-- <hr class="heading_line"/> -->
                        </div>
                    </div>
                    <div class="seller_mid_cont"  style="margin-top: 20px;">
                    
                        <div class="col-md-12">
                            <div class="tijara-info-section">
                                <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
                                <p  class="buyer-prod-content">{{__('messages.service_booking_msg')}}</p>
                            </div>
                        </div>
                       <!--  <div class="col-md-12">
                        <div class="col-md-9"></div>
                            <div  class="col-md-3"><?php echo $monthYearHtml;?></div>
                        </div> -->
                        <div class="card">
                              <div class="col-md-12" style="margin-bottom: 40px;margin-top: -45px;">
                                <div class="col-md-9"></div>
                                    <div  class="col-md-3">
                                        <form id="filter-service-booking" action="{{route('frontAllServiceRequest')}}" method="post">
                                        @csrf
                                        <?php echo $monthYearHtml;?>
                                        </form>
                                    </div>
                              </div>
                            <div class="card-body"  style="margin-top: 20px;">
                                <div class="row">
                                    @if(!empty($buyerBookingRequest) && count($buyerBookingRequest) > 0)

                                    @foreach($buyerBookingRequest as $key => $value)

                                    <?php 


                                    $getStoreName = DB::table('users')
                                    ->where('users.is_deleted','!=',1)
                                    ->where('id','=',$value['seller_id'])
                                    ->select('users.store_name','users.fname','users.lname')
                                    ->get();


                                 if(!empty($value['images'])) {
                                   $imagesParts    =   explode(',',$value['images']); 
                    
                                   $image  =   url('/').'/uploads/ServiceImages/resized/'.$imagesParts[0];
                                    }
                                    else{
                                    $image  =     url('/').'/uploads/ServiceImages/resized/no-image.png';
                                    }
                                   
                                    $dated      =   date('Y-m-d',strtotime($value['service_date']));

                                    $serviceName = (!empty($value['title'])) ? $value['title'] : '-';
                                    // $price = $value['service_price'];
                                    $service_price = (!empty($value->price)) ? $value->price : '-';
                                    $storeName = (!empty($getStoreName[0]->store_name)) ?$getStoreName[0]->store_name : '-';

                                    $id =  $value['id'];

                                $seller_name = $getStoreName[0]->fname." ".$getStoreName[0]->lname;
                 
                                $seller_name = str_replace( array( '\'', '"', 
                                  ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                                $seller_name = str_replace(" ", '-', $seller_name);
                                $seller_name = strtolower($seller_name);

                                $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($value->seller_id)."/services"; 

                                $service_link = url('/').'/service/'.$value->service_slug.'-S-'.$value->service_code;
                                   
                                    ?> 
                                    <div class="col-sm-3">
                                        <div class="card product-card">
                                            <img class="card-img-top buyer-product-img ServiceImgCard" src="{{$image}}" service_link="{{$service_link}}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{$dated}}</h5>
                                                <p class="card-text buyer-product-title"><a href="{{$service_link}}"  style="color: #000 !important">{{$serviceName}}</a></p>
                                                <p class="card-text" style="margin-bottom: 50px;margin-top: -36px;">  
                                                <span class="buyer-price  booking-service-price" id="product_variant_price">
                                                {{number_format($service_price,2) }} kr
                                                </span> 
                                                </p>
                                                <p class="card-text buyer-service-store">{{ __('users.butik_btn')}} :<a href="{{$seller_link}}" style="color: #000 !important">{{$storeName}}</a></p>


                                                <div class="buyer-button">
                                                <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("{{route("frontServiceRequestDel", base64_encode($id))}}");'  title="{{ __('lang.remove_title')}}" class="btn btn-black btn-sm login_btn remove-btn-col">{{ __('users.cancel_service')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach

                                    @else
                                    <div style="text-align: center;margin-top: 50px;">{{__('lang.datatables.sEmptyTable')}}</div>
                                    @endif
                                </div>
                            </div>
                              {!! $buyerBookingRequest->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        </div>       
    </div>
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
$(".ServiceImgCard").click(function(){
  var attr_val = $(this).attr('service_link');
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
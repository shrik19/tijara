@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  .tijara-content {
      margin-bottom: 60px;
    }
</style>
<div class="mid-section p_155">
    <div class="container-fluid">
        <div class="container-inner-section-1 tjd-sellcontainer">
        <!-- Example row of columns -->

        <div class="row">
            <div class="col-md-12 tijara-content">
                <?php /* @if($subscribedError)
                <div class="alert alert-danger">{{$subscribedError}}</div>
                @endif */ ?>
                @include('Front.alert_messages')
                <div class="seller_info border-none">

                    <div class="card">
                        <div class="card-header ml-0 row">
							
							<div class="col-md-9 pl-0">
								<h2 class="page_heading pl-0">{{ __('users.my_booking_title')}}</h2>
								<!-- <hr class="heading_line"/> -->
							</div>
							<div  class="col-md-3 new_add text-right">
								<form id="filter-service-booking" action="{{route('frontAllServiceRequest')}}" method="post">
								@csrf
								<?php echo $monthYearHtml;?>
								</form>
							</div>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                    
                        <!-- <div class="col-md-12">
                            <div class="tijara-info-section">
                                <h1 class="buyer-prod-head">{{__('messages.info_head')}}</h1>
                                <p  class="buyer-prod-content">{{__('messages.service_booking_msg')}}</p>
                            </div>
                        </div> -->
                       <!--  <div class="col-md-12">
                        <div class="col-md-9"></div>
                            <div  class="col-md-3"><?php echo $monthYearHtml;?></div>
                        </div> -->
                        <div class="card">
                            <div class="card-body"  style="margin-top: 20px;">
                                <div class="row tj-orderlist tj-order-product">
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
                    
                                   /* $service_price = (!empty($value->price)) ? number_format($value->price,2)." Kr" : '-';*/
                                    if(!empty($value->price)){
                                        $service_price_array = str_split(strrev($value->price), 3);
                                        $service_price = strrev(implode(" ", $service_price_array));
                                        $service_price = $service_price.",00 kr";
                                    
                                        
                                    } else{
                                        $service_price = '-';
                                    }
                                   

                                    $storeName = (!empty($getStoreName[0]->store_name)) ?$getStoreName[0]->store_name : '-';

                                    $id =  $value['id'];

                                $seller_name = @$getStoreName[0]->store_name;
                 
                                $seller_name = str_replace( array( '\'', '"', 
                                  ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                                $seller_name = str_replace(" ", '-', $seller_name);
                                $seller_name = strtolower($seller_name);

                                $seller_link= url('/').'/seller/'.$seller_name; 

                                $service_link = url('/').'/service/'.$value->service_slug.'-S-'.$value->service_code;
                                 //echo "<pre>";print_r($value);exit;
                                   $description=strip_tags($value->description);

                                   $location = $value['location'];
                                   $service_time=$value['service_time'];
                                   $telephone_number = $value['telephone_number'];

                                    ?> 
                                     <div class="col-md-15 buyer-ht">
                                     <div class="card product-card product_data_img product_link_js">
                                            <img class="card-img-top buyer-product-img ServiceImgCard serviceReqDetails product_img_prd tj-imgnewsize" src="{{$image}}"user_name="'.$user.'" serviceName="{{$serviceName}}" dated="{{$dated}}" id="{{$id}}" title="{{$serviceName}}" description="{{$description}}" service_time="{{$service_time}}" service_price="{{$service_price}}" location="{{$location}}" telephone_number="{{$telephone_number}}">



                                            <div class="card-body product_all">
                                                <h5 class="card-title">{{$dated}}</h5>
                                                <p class="card-text buyer-product-title serviceReqDetails"  dated="{{$dated}}" id="{{$id}}" title="{{$serviceName}}" description="{{$description}}" service_time="{{$service_time}}" service_price="{{$service_price}}" location="{{$location}}" telephone_number="{{$telephone_number}}"><a style="color: #000 !important">{{$serviceName}}</a></p>
                                                <p class="card-text" >  
                                                <span class="buyer-price  booking-service-price" id="product_variant_price">
                                                {{$service_price}}
                                                </span> 
                                                </p>
                                                <p class="card-text order-product-store-title" style="margin-bottom: 20px"><?php /* {{ __('users.butik_btn')}} : */?><a href="{{$seller_link}}" style="color: #000 !important">{{$storeName}}</a></p>



                                                <div class="buyer-button">
                                                <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("{{route("frontServiceRequestDel", base64_encode($id))}}");'  title="{{ __('lang.remove_title')}}" class="btn btn-black btn-sm login_btn remove-btn-col" @if($dated < date("Y-m-d")) disabled @endif>{{ __('users.cancel_service')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach

                                    @else
                                    <div style="text-align: center;margin-top: 50px;margin-bottom: 50px;height: 23vh;">{{__('lang.datatables.sEmptyTable')}}</div>
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

<!-- add subcategory model Form -->
 <div class="modal fade" id="serviceReqDetailsmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('lang.service_req_details')}}</h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <table>
           <!--  <tr><td style="font-weight: 700px;"></td>:<td></td></tr> -->
           @if(session('role_id')==2)  
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.cust_label')}} {{ __('lang.txt_name')}} :</td><td class="user_name" style="padding-left: 10px;"></td></tr>
            @endif
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.sevice_name_head')}} :</td><td class="title" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;"> {{ __('lang.product_description_label')}} :</td><td class="description" style="padding-left: 10px;"></td></tr>
            
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_time')}} :</td><td class="service_time" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_total_cost')}} :</td><td class="service_price" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.location')}} :</td><td class="location" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.product_buyer_phone_no')}} :</td><td class="telephone_number" style="padding-left: 10px;"></td></tr>

          </table>
        </div>
              
      </div>
    </div>
  </div>

<!-- General JS Scripts -->

<script type="text/javascript">
    //jconfirm-buttons
/*$(".ServiceImgCard").click(function(){
  var attr_val = $(this).attr('service_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});*/
jQuery("#monthYear").on('change', function() {
  //alert( this.value );
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
jQuery(document).on("click",".serviceReqDetails",function(event) {      
  
    jQuery.noConflict();
        jQuery('#serviceReqDetailsmodal').find('.id').text(jQuery(this).attr('id'));
        jQuery('#serviceReqDetailsmodal').find('.user_name').text(jQuery(this).attr('user_name'));
        jQuery('#serviceReqDetailsmodal').find('.title').text(jQuery(this).attr('title'));
        jQuery('#serviceReqDetailsmodal').find('.description').text(jQuery(this).attr('description'));
        jQuery('#serviceReqDetailsmodal').find('.location').text(jQuery(this).attr('location'));
        jQuery('#serviceReqDetailsmodal').find('.service_time').text(jQuery(this).attr('service_time'));
        jQuery('#serviceReqDetailsmodal').find('.service_price').text(jQuery(this).attr('service_price'));
        jQuery('#serviceReqDetailsmodal').find('.telephone_number').text(jQuery(this).attr('telephone_number'));
        jQuery('#serviceReqDetailsmodal').modal('show');
        //$('.modal-backdrop').attr('style','position: relative;');
    }); 


</script>
@endsection
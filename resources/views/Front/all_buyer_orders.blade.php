@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  @media print {
   a[href]:after {
      display: none;
      visibility: hidden;
   }
   .modal-header{
    display: none;
      visibility: hidden;
   }
 .ft_middle_container, .container-inner-section, .ft_copyright,.container{
  display: none;
      visibility: hidden;
 }
 .modal-body-wrapper{
  margin-top :0px !important;
  padding-top: 0px !important;
 }
}
</style>
<div class="mid-section p_155">
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
                                  
                                  $seller_name = $value->fname." ".$value->lname;
                
                                  $seller_name = str_replace( array( '\'', '"', 
                                  ',' , ';', '<', '>', '(', ')','$','.','!','@','#','%','^','&','*','+','\\' ), '', $seller_name);
                                  $seller_name = str_replace(" ", '-', $seller_name);
                                  $seller_name = strtolower($seller_name);
                                              
                         
                                $seller_link= url('/').'/seller/'.$seller_name."/". base64_encode($value->seller_id)."/products"; 

                               $product_link = url('/').'/product/'.$value->product_slug.'-P-'.$value->product_code;

                                $id =  $value['id'];
//echo "<pre>";print_r($value->order_id);exit;
                               /*    $action = '<a href="'.route('frontShowOrderDetails', base64_encode($id)).'" title="'. trans('lang.txt_view').'"><i style="color:#2EA8AB;" class="fas fa-eye"></i> </a>&nbsp;&nbsp;
                  <a href="'.route('frontDownloadOrderDetails', base64_encode($id)).'" title="Download"><i style="color:gray;" class="fas fa-file-download"></i> </a>';*/

                                    /* $discount_price =0;
                                    if(!empty($value['discount'])) {
                                    $discount = number_format((($price * $value['discount']) / 100),2,'.','');
                                    $discount_price = $price - $discount;
                                    }
                                    */
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
</div> <!-- /container -->



<!-- General JS Scripts -->


<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="{{url('/')}}/assets/front/js/bootstrap.min.js"></script>
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>
<script type="text/javascript">
$(".buyer-product-img").click(function(){
 /* var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }*/
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
            console.log(data)
             $('#orderDetailsmodal').modal('show');
           $('#order_details_box').html(data);
               //$(".loader").hide();
            /*if(data.success=="package subscribed"){
                console.log(data.success);
                console.log("second step complete");  
                $(".package-html").hide();
                $(".klarna_html").html(data.html_snippet).show();
                //$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            }*/
        }
    });

});

$("#monthYear").on('change', function() {
  //alert( this.value );
  this.form.submit();
});


 function printDiv() 
    {
      //$('#orderDetailsmodal').modal('hide');
       // var divToPrint=jQuery(".printdiv");
        /*var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.html()+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);*/

      const section = $(".mid-section");
      const modalBody = $(".modal-body").detach();

      const content = $(".tijara-content").detach();
      section.append(modalBody);
      window.print();
      section.empty();
      section.append(content);
      $(".modal-body-wrapper").append(modalBody);

    }
            

    if($("#order_status").length)
    {
        $("#order_status").change(function()
        {
          var order_status = $(this).val();
          var order_id = $(this).attr('order_id');
            
            $.confirm({
                title: 'Confirm!',
                content: "{{ __('lang.order_status_confirm')}}",
                type: 'orange',
                typeAnimated: true,
                columnClass: 'medium',
                icon: 'fas fa-exclamation-triangle',
                buttons: {
                    okay: function () 
                    {
                        $(".loader").show();

                        $.ajax({
                        url:siteUrl+"/change-order-status",
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        type: 'post',
                        data : {'order_status': order_status, 'order_id' : order_id},
                        success:function(data)
                        {
                            $(".loader").hide();
                            var responseObj = $.parseJSON(data);
                            if(responseObj.status == 1)
                            {
                                showSuccessMessage(responseObj.msg);
                            }
                            else
                            {
                                if(responseObj.is_login_err == 0)
                                {
                                    showErrorMessage(responseObj.msg);
                                }
                                else
                                {
                                    showErrorMessage(responseObj.msg,'/front-login/buyer');
                                }
                            }

                        }
                        });
                    },
                    cancel: function () {
                        
                    },
                }
            });
        });
    }
     function downloadPdf(DownloadLink) 
    {

      if(DownloadLink !=''){
        window.location.href = DownloadLink; 
      } 
    }
</script>

@endsection
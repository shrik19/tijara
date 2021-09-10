@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <!-- Example row of columns -->

      <div class="row">
        <div class="col-md-12">

          <div class="col-md-12 tijara-content">
            @if($subscribedError)
              <div class="alert alert-danger">{{$subscribedError}}</div>
            @endif
            @include('Front.alert_messages')
            <div class="seller_info">
            <div class="card">
            <div class="card-header row seller_header">
            <div class="col-md-10">

            <h2>{{ __('users.buyer_product_list_title')}}</h2>
            <hr class="heading_line"/>
            </div>
            <div class="col-md-1" style="margin-left: -61px;">
            <a href="{{route('frontProductCreate')}}" title="{{ __('lang.add_product')}}" class="btn btn-black btn-sm debg_color a_btn login_btn" ><span>+ {{ __('users.add_ads_btn')}}</span> </a>
            </div>
            </div>
            </div>
            <div class="seller_mid_cont"  style="margin-top: 20px;">

            <div class="card">


            <div class="card-body">
            <div class="row">
            @if(!empty($buyerProducts) && count($buyerProducts) > 0)

            @foreach($buyerProducts as $key => $value)

            <?php 
            if(!empty($value['image'])) {
            $imagesParts    =   explode(',',$value['image']); 
            $image  =   url('/').'/uploads/ProductImages/resized/'.$imagesParts[0];
            }
            else{
            $image  =     url('/').'/uploads/ProductImages/resized/no-image.png';
            }
            $dated      =   date('Y-m-d',strtotime($value['updated_at']));
            $title = (!empty($value['title'])) ? substr($value['title'], 0, 50) : '-';
            $price = $value['price'];
            $id =  $value['id'];

            $discount_price =0;
            if(!empty($value['discount'])) {
            $discount = number_format((($price * $value['discount']) / 100),2,'.','');
            $discount_price = $price - $discount;
            }

            ?> 
            <div class="col-sm-3">
            <div class="card product-card">
            <img class="card-img-top buyer-product-img" src="{{$image}}" >
            <div class="card-body">
            <h5 class="card-title">{{$dated}}</h5>
            <p class="card-text buyer-product-title">{{$title}}</p>
            <p class="card-text" style="margin-bottom: 50px;">  
            <span class="buyer-price buyer-product-price" id="product_variant_price">
            <span style="@if(!empty($discount_price)) text-decoration: line-through; @endif">{{ number_format($value['price'],2) }} kr
            </span>
            @if(!empty($discount_price)) &nbsp;&nbsp;{{ number_format($discount_price,2) }} kr @endif 
            &nbsp;&nbsp;
            @if(!empty($value->discount))
            <?php echo "(".$value->discount."% off)";?> @endif</span> 
            </p>
            <!--     <div class="quantity_box">              
            <span style="padding-top:6px;position:absolute;font-size:20px;" id="product_variant_price"><span style="@if(!empty($value['discount_price'])) text-decoration: line-through; @endif">{{ number_format($value['price'],2) }} kr</span> @if(!empty($value['discount_price'])) &nbsp;&nbsp;{{ number_format($value['discount_price'],2) }} kr @endif</span> 
            </div> -->

            <div class="buyer-button">
            <a href="{{route('frontProductEdit', base64_encode($id))}}" class="btn btn-black btn-sm debg_color login_btn a_btn" title="{{ __('lang.edit_label')}}">{{ __('lang.edit_label')}}</a>


            <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("{{route("frontProductDelete", base64_encode($id))}}");'  title="{{ __('lang.remove_title')}}" class="btn btn-black btn-sm login_btn remove-btn-col">{{ __('lang.remove_title')}}</a>
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

              {!! $buyerProducts->links() !!}
            </div>
            </div>

           
            <div class="col-md-12">
            <div class="buyer-prod-msg tijara-info-section">
            <h1 class="buyer-prod-head">{{__('messages.Obs_head')}}</h1>
            <p  class="buyer-prod-content">{{__('messages.buyer_product_msg')}}</p>
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
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>


@endsection
@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  
</div>

<div class="container">
  <!-- Example row of columns -->
  
  <div class="row">
    <div class="">
      <div class="col-md-12">
    @if($subscribedError)
      <div class="alert alert-danger">{{$subscribedError}}</div>
      @endif
    @include('Front.alert_messages')
     
    <div class="card">
    <div class="card-header row">
    <div class="col-md-10">
        
      <h2>{{ __('lang.your_products_label')}}</h2>
      <hr class="heading_line"/>
      </div>
      <div class="col-md-1">
      <a href="{{route('frontProductCreate')}}" title="{{ __('lang.add_product')}}" class="btn btn-black btn-sm debg_color login_btn" ><span>{{ __('lang.add_product')}}</span> </a>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
          @if(!empty($buyerProducts))
          <?php 
              

           
           
          ?> 
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
                  $price = $value['price'].' Kr';
                  $id =  $value['id'];

                
          ?> 
            <div class="col-sm-3">
              <div class="card">
              <img class="card-img-top buyer-product-img" src="{{$image}}" >
              <div class="card-body">
                <h5 class="card-title">{{$dated}}</h5>
                <p class="card-text buyer-product-title">{{$title}}</p>
                 <p class="card-text buyer-price">{{$price}}</p>
                 <div class="buyer-button">
                  <a href="{{route('frontProductEdit', base64_encode($id))}}" class="btn btn-black btn-sm debg_color login_btn" title="{{ __('lang.edit_label')}}">{{ __('lang.edit_label')}}</a>
                  

                  <a href="javascript:void(0)" onclick='return ConfirmDeleteFunction("{{route("frontProductDelete", base64_encode($id))}}");'  title="{{ __('lang.remove_title')}}" class="btn btn-black btn-sm login_btn remove-btn-col">{{ __('lang.remove_title')}}</a>
                  </div>
              </div>
              </div>
            </div>

          @endforeach

          @else
          <div>No product found</div>
          @endif
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
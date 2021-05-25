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
    <div class="col-md-12">
    @include ('Front.alert_messages')
     
        <h2> {{ __('users.seller_personal_form_label')}}</h2>
        <hr class="heading_line"/>
        <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data">
            @csrf
            <div class="col-md-3"></div> 
       <div class="col-md-6">
        <h2>&nbsp;</h2>
        <br>
        
        <div class="login_box">
          

          <div class="form-group increment cloned">
            <label>{{ __('users.seller_header_img_label')}}</label>
            @php
            if(!empty($details->header_img))
            {
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->header_img.'" style="width:200px;height:200px;"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
            }
            @endphp

            <input type="file" name="header_img" class="form-control" value="">
            
            <div class="text-danger">{{$errors->first('filename')}}</div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

          <div class="form-group increment cloned">
            <label>{{ __('users.seller_logo_label')}}</label>
            @php
            if(!empty($details->logo))
            {
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->logo.'" style="width:200px;height:200px;"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
            }
            @endphp

            <input type="file" name="logo" class="form-control" value="">
            
            <div class="text-danger">{{$errors->first('filename')}}</div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

          
            <button class="btn btn-black debg_color login_btn update-buyer-profile">{{ __('lang.update_btn')}}</button>
            <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('lang.cancel_btn')}}</a>
                 
        </div>
      </div>
      </form>    
    </div>
  </div>
</div> <!-- /container -->


@endsection
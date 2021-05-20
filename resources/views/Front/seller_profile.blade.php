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
      <div class="col-md-6">
        <h2>{{$registertype}} {{ __('users.profile_update_title')}}</h2>
        <hr class="heading_line"/>
         
        <div class="login_box">
          
          <form id="seller-profile-form" action="{{route('frontSellerProfileUpdate')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="role_id" value="{{$role_id}}">
            <div class="form-group">
              <label>{{ __('users.first_name_label')}}<span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="fname" id="fname" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('fname')) ?  old('fname') : $sellerDetails[0]->fname}}">
              <span class="invalid-feedback" id="err_fname">@if($errors->has('fname')) {{ $errors->first('fname') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('users.last_name_label')}}<span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="lname" id="lname" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('lname')) ?  old('lname') : $sellerDetails[0]->lname}}">
              <span class="invalid-feedback" id="err_lname">@if($errors->has('lname')) {{ $errors->first('lname') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.email_label')}}<span class="de_col">*</span></label>
              <input type="email" class="form-control login_input" name="email" id="email" placeholder="{{ __('users.email_label')}}" value="{{ (old('email')) ? old('email') : $sellerDetails[0]->email}}">
              <span class="invalid-feedback" id="err_email">@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>
            </div>          

            <div class="form-group">
               <label>{{ __('users.description_label')}}</label>
              <textarea class="form-control" name="description" id="description" placeholder="{{ __('users.description_label')}}" value="">{{ (old('description')) ?  old('description') : $sellerDetails[0]->description}}</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
            </div>

            <div class="form-group increment cloned">
              <label>{{ __('users.seller_image(s)_label')}}</label>
              @php
              if(!empty($imagedetails['getImages']))
              {
                echo '<div class="row">';

                foreach($imagedetails['getImages'] as $image)
                {
                  $path = public_path().'/uploads/SellerImages/'.$image['image'];
                  if (file_exists($path)) {
                  echo '<div style="margin-left:20px;" class="col-md-4 existing-images"><img src="'.url('/').'/uploads/SellerImages/'.$image['image'].'" style="width:140px;height:140px;"><div style="margin-top:10px;margin-bottom:20px;"><a href="javascript:void(0)" onclick="return ConfirmDeleteFunction1(\''.route('SellerImageDelete', base64_encode($image['id'])).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a></div></div>';
                  }
                }
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              @endphp
              @if (count($imagedetails['getImages']) < 5)
              <input type="file" name="sellerimages[]" class="form-control" multiple="multiple">
              <div class="input-group-btn text-right"> 
                <button class="btn gray_color add-image" type="button"><i class="fas fa-plus"></i> Add More</button>
              </div>
              @endif
            </div>

            <div class="clone hide" style="display:none;" >
              <div class="form-group cloned" style="margin-top:10px">
              <input type="file" name="sellerimages[]" class="form-control" multiple="multiple">
              <div class="input-group-btn text-right"> 
                <button class="btn btn-danger remove-image" type="button"><i class="fas fa-trash-alt"></i> </button>
              </div>
              </div>
            </div>

            <div class="text-danger cloned-danger">{{$errors->first('sellerimages')}}</div>
          
        </div>
      </div>
      <div class="col-md-6">
        <h2>&nbsp;</h2>
        <br>
        <div class="login_box">
            <div class="form-group">
              <label>{{ __('users.phone_number_label')}}</label>
              <!-- <span style="margin-top: 10px;" class="col-md-2">+46</span> -->
              <input type="text" class="form-control login_input" name="phone_number" id="phone_number" placeholder="{{ __('users.phone_number_label')}}" value="{{ (old('phone_number')) ? old('phone_number') : $sellerDetails[0]->phone_number}}">
              <span class="invalid-feedback" id="err_phone_number">@if($errors->has('phone_number')) {{ $errors->first('phone_number') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.address_label')}}</label>
                <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5"><?php if(!empty($sellerDetails[0]->address)){ echo $sellerDetails[0]->address; }?></textarea>
              <span class="invalid-feedback" id="err_address">@if($errors->has('address')) {{ $errors->first('address') }}@endif</span>
            </div> 
             <div class="form-group">
              <label>{{ __('users.postal_code_label')}}</label>
              <input type="text" class="form-control login_input" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="{{ (old('postcode')) ? old('postcode') : $sellerDetails[0]->postcode}}">
              <span class="invalid-feedback" id="err_address">@if($errors->has('postcode')) {{ $errors->first('postcode') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.city_label')}}</label>
              <input type="text" class="form-control login_input" name="city" id="city" placeholder="{{ __('users.city_label')}}" value="{{ (old('city')) ? old('city') : $sellerDetails[0]->city}}">
              <span class="invalid-feedback" id="err_city">@if($errors->has('city')) {{ $errors->first('city') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.swish_number_label')}}</label>
              <input type="text" class="form-control login_input" name="swish_number" id="swish_number" placeholder="{{ __('users.swish_number_label')}}" value="{{ (old('swish_number')) ? old('swish_number') : $sellerDetails[0]->swish_number}}">
              <span class="invalid-feedback" id="err_swish_number">@if($errors->has('swish_number')) {{ $errors->first('swish_number') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.where_did_you_find_us_label')}}</label>
              <input type="text" class="form-control login_input" name="find_us" id="find_us" placeholder="{{ __('users.where_did_you_find_us_label')}}" value="{{ (old('where_find_us')) ? old('where_find_us') : $sellerDetails[0]->where_find_us}}">
              <span class="invalid-feedback" id="err_find_us">@if($errors->has('find_us')) {{ $errors->first('find_us') }}@endif</span>
            </div>

           <div class="form-group">
              <label>{{ __('users.store_name_label')}}</label>
              <input type="text" class="form-control login_input" name="store_name" id="store_name" placeholder="{{ __('users.store_name_label')}}" value="{{ (old('store_name')) ? old('store_name') : $sellerDetails[0]->store_name}}" onblur="checkStoreName(this)">
              <span class="invalid-feedback" id="err_store_name">@if($errors->has('store_name')) {{ $errors->first('store_name') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.paypal_email_address_label')}}</label>
              <input type="text" class="form-control login_input" name="paypal_email" id="paypal_email" placeholder="{{ __('users.paypal_email_address_label')}}" value="{{ (old('paypal_email')) ? old('paypal_email') : $sellerDetails[0]->paypal_email}}">
              <span class="invalid-feedback" id="err_paypal_email">@if($errors->has('paypal_email')) {{ $errors->first('paypal_email') }}@endif</span>
            </div>

            <button class="btn btn-black debg_color seller-profile-update login_btn">{{ __('lang.update_btn')}}</button>
             <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16">{{ __('lang.cancel_btn')}}</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->


<script src="{{url('/')}}/assets/admin/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script>


  /*function to check unique store name
* @param : store name
*/
  function checkStoreName(inputText){

    var store_name= inputText.value;
     $.ajax({
      url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+$('#hid').val(),
      type: 'get',
      data: { },
      success: function(output){
        if(output !='')
         alert(output);
        }
    });
  }


  $(document).ready(function () {
    $('#phone_number').mask('00 000 00000');
  });

</script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script><!-- 
<script src="{{url('/')}}/assets/js/jquery.mask.min.js"></script> -->
@endsection
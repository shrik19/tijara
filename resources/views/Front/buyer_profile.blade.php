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
    <form id="buyer-update-form" action="{{route('frontBuyerProfileUpdate')}}" method="post"  enctype="multipart/form-data">
            @csrf
      <div class="col-md-6">
        <h2>{{$registertype}} {{ __('users.profile_update_title')}}</h2>
        <hr class="heading_line"/>
        
        <div class="login_box">
          
            <input type="hidden" name="role_id" value="{{$role_id}}">
            <div class="form-group">
              <label>{{ __('users.first_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="fname" id="fname" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('fname')) ?  old('fname') : $buyerDetails[0]->fname}}">
              <span class="invalid-feedback" id="err_fname" >@if($errors->has('fname')) {{ $errors->first('fname') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('users.last_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="lname" id="lname" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('lname')) ?  old('lname') : $buyerDetails[0]->lname}}">
              <span class="invalid-feedback" id="err_lname" >@if($errors->has('lname')) {{ $errors->first('lname') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.email_label')}} <span class="de_col">*</span></label>
              <input type="email" class="form-control login_input" name="email" id="email" placeholder="{{ __('users.email_label')}}" value="{{ (old('email')) ? old('email') : $buyerDetails[0]->email}}">
              <span class="invalid-feedback" id="err_email" >@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.phone_number_label')}}</label>
              <!-- <span style="margin-top: 10px;" class="col-md-2">+46</span> -->
              <input type="text" class="form-control login_input" name="phone_number" id="phone_number" placeholder="{{ __('users.phone_number_label')}}" value="{{ (old('phone_number')) ? old('phone_number') : $buyerDetails[0]->phone_number}}">
              <span class="invalid-feedback" id="err_phone_number" >@if($errors->has('phone_number')) {{ $errors->first('phone_number') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.address_label')}} </label>
                <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5" placeholder="{{ __('users.address_label')}}"><?php if(!empty($buyerDetails[0]->address)){ echo $buyerDetails[0]->address; }?></textarea>
              <span class="invalid-feedback" id="err_address">@if($errors->has('address')) {{ $errors->first('address') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.postal_code_label')}} </label>
              <input type="text" class="form-control login_input" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="{{ (old('postcode')) ? old('postcode') : $buyerDetails[0]->postcode}}">
              <span class="invalid-feedback" id="err_address">@if($errors->has('postcode')) {{ $errors->first('postcode') }}@endif</span>
            </div>

            <div class="form-group">
              <label>{{ __('users.city_label')}} </label>
              <input type="text" class="form-control login_input" name="city" id="city" placeholder="{{ __('users.city_label')}}" value="{{ (old('city')) ? old('city') : $buyerDetails[0]->city}}">
              <span class="invalid-feedback" id="err_city">@if($errors->has('city')) {{ $errors->first('city') }}@endif</span>
            </div>
           
        </div>
      </div>
      

       <div class="col-md-6">
        <h2>&nbsp;</h2>
        <br>
        
        <div class="login_box">
          <div class="form-group">
            <label>{{ __('users.swish_number_label')}} </label>
            <input type="text" class="form-control login_input" name="swish_number" id="swish_number" placeholder="{{ __('users.swish_number_label')}}" value="{{ (old('swish_number')) ? old('swish_number') : $buyerDetails[0]->swish_number}}">
            <span class="invalid-feedback" id="err_swish_number">@if($errors->has('swish_number')) {{ $errors->first('swish_number') }}@endif</span>
          </div>

          <div class="form-group increment cloned">
            <label>{{ __('users.profile_label')}}</label>
            @php
            if(!empty($buyerDetails[0]->profile))
            {
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Buyer/resized/'.$buyerDetails[0]->profile.'" style="width:200px;height:200px;"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
            }
            @endphp

            <!-- <input type="file" name="profile" class="form-control" value="{{old('profile')}}"> -->
            <div class="upload-btn-wrapper">
            <button class="uploadbtn"><i class="fa fa-upload" aria-hidden="true"></i> {{ __('users.upload_file_input')}}</button>
            <input type="file" name="profile" class="form-control" value="{{old('profile')}}" />
            </div>
            
            <div class="text-danger">{{$errors->first('filename')}}</div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

            <div class="form-group">
              <label>{{ __('users.where_did_you_find_us_label')}} </label>
              <input type="text" class="form-control login_input" name="find_us" id="find_us" placeholder="{{ __('users.where_did_you_find_us_label')}}" value="{{ (old('where_find_us')) ? old('where_find_us') : $buyerDetails[0]->where_find_us}}">
              <span class="invalid-feedback" id="err_find_us">@if($errors->has('find_us')) {{ $errors->first('find_us') }}@endif</span>
            </div>
            <button class="btn btn-black debg_color login_btn update-buyer-profile">{{ __('lang.update_btn')}}</button>
            <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('lang.cancel_btn')}}</a>
                    
        </div>
      
      </div>
      
   <!--  </div> -->
    </form>
  </div>
</div> <!-- /container -->

<script>
  $(document).ready(function() {
    $('.description').richText();
  });

</script>
@endsection
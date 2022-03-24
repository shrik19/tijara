@extends('Front.layout.template')
@section('middlecontent')

<style type="text/css">
  .invalid-feedback {
    position: relative !important;
    color: red;
}
</style>
<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">

    <div class="col-md-12 tijara-content">
      @include ('Front.alert_messages')
       <div class="seller_info border-none">
        <div class="card">
		<div class="card-header ml-0 row">							
			<div class="col-md-9 pl-0">
				<h2 class="page_heading">{{ __('users.buyer_profile_update_title')}}</h2>
				<!-- <hr class="heading_line"/> -->
			</div>
          </div>
        </div>
        <div style="margin-top: 20px;">
    
      <form id="buyer-update-form" action="{{route('frontBuyerProfileUpdate')}}" method="post"  enctype="multipart/form-data">
            @csrf
      <div class="col-md-6 pl-0">
        <!-- <h2> {{ __('users.buyer_profile_update_title')}}</h2>
        <hr class="heading_line"/> -->
        
        <div class="login_box">
          
            <input type="hidden" name="role_id" value="{{$role_id}}">
            <div class="form-group">
              <label class="label_css">{{ __('users.first_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input input_box_css" name="fname" id="fname" placeholder="{{ __('users.first_name_label')}}" value="{{ (old('fname')) ?  old('fname') : $buyerDetails[0]->fname}}">
              <span class="invalid-feedback" id="err_fname" >@if($errors->has('fname')) {{ $errors->first('fname') }}@endif </span>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <label  class="label_css">{{ __('users.last_name_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input input_box_css" name="lname" id="lname" placeholder="{{ __('users.last_name_label')}}" value="{{ (old('lname')) ?  old('lname') : $buyerDetails[0]->lname}}">
              <span class="invalid-feedback" id="err_lname" >@if($errors->has('lname')) {{ $errors->first('lname') }}@endif</span>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <label  class="label_css">{{ __('users.email_label')}} <span class="de_col">*</span></label>
              <input type="email" class="form-control login_input input_box_css" name="email" id="email" placeholder="{{ __('users.email_label')}}" value="{{ (old('email')) ? old('email') : $buyerDetails[0]->email}}">
              <span class="invalid-feedback" id="err_email" >@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>
            </div>
            <?php /*
            <div class="form-group">
              <label>{{ __('users.phone_number_label')}}</label>
              <!-- <span style="margin-top: 10px;" class="col-md-2">+46</span> -->
              <input type="text" class="form-control login_input" name="phone_number" id="phone_number" placeholder="{{ __('users.phone_number_label')}}" value="{{ (old('phone_number')) ? old('phone_number') : $buyerDetails[0]->phone_number}}">
              <span class="invalid-feedback" id="err_phone_number" >@if($errors->has('phone_number')) {{ $errors->first('phone_number') }}@endif</span>
            </div>
            */?>
            <div class="form-group">
              <label  class="label_css">{{ __('users.address_label')}} <span class="de_col">*</span></label>
                <textarea class="form-control input_box_css" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5" placeholder="{{ __('users.address_label')}}"><?php if(!empty($buyerDetails[0]->address)){ echo $buyerDetails[0]->address; }?></textarea>
              <span class="invalid-feedback" id="err_address">@if($errors->has('address')) {{ $errors->first('address') }}@endif</span>
            </div>

            <div class="form-group">
              <label  class="label_css">{{ __('users.postal_code_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input input_box_css" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="{{ (old('postcode')) ? old('postcode') : $buyerDetails[0]->postcode}}">
              <span class="invalid-feedback" id="err_address">@if($errors->has('postcode')) {{ $errors->first('postcode') }}@endif</span>
            </div>

            <div class="form-group">
              <label  class="label_css">{{ __('users.location_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input input_box_css" name="city" id="city" placeholder="{{ __('users.location_label')}}" value="{{ (old('city')) ? old('city') : $buyerDetails[0]->city}}">
              <span class="invalid-feedback" id="err_city">@if($errors->has('city')) {{ $errors->first('city') }}@endif</span>
            </div>
           
        </div>
      </div>
      

       <div class="col-md-6 pr-0">
       
        
        <div class="login_box">
          <!-- <div class="form-group">
            <label>{{ __('users.swish_number_label')}} </label>
            <input type="text" class="form-control login_input" name="swish_number" id="swish_number" placeholder="{{ __('users.swish_number_label')}}" value="{{ (old('swish_number')) ? old('swish_number') : $buyerDetails[0]->swish_number}}">
            <span class="invalid-feedback" id="err_swish_number">@if($errors->has('swish_number')) {{ $errors->first('swish_number') }}@endif</span>
          </div> -->
          <div class="loader"></div>
          <div class="form-group increment cloned">
            <label  class="label_css">{{ __('users.select_profile_picture')}}</label>
       <!--      @php
            if(!empty($buyerDetails[0]->profile))
            {
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Buyer/resized/'.$buyerDetails[0]->profile.'" class="buyer_profile_update_img"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
            }else{
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Buyer/no_image_circle.png" class="buyer_profile_update_img"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
            }
            @endphp -->
            <div class="row">
          <div class="col-md-4 existing-images">
             @php
            if(!empty($buyerDetails[0]->profile))
            {
                echo '<div><img src="'.url('/').'/uploads/Buyer/resized/'.$buyerDetails[0]->profile.'" class="buyer_profile_update_img"><a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div>';
              
            }else{
                  echo '<div><img src="'.url('/').'/uploads/Buyer/no_image_circle.png" class="buyer_profile_update_img"></div>';
            }             
            @endphp 
          </div>
        </div>
            <!-- <input type="file" name="profile" class="form-control" value="{{old('profile')}}"> -->
            <div class="upload-btn-wrapper">
            <button class="uploadbtn buyer_profile_update_btn"><i class="fa fa-upload" aria-hidden="true" style=""></i> {{ __('users.upload_file_input')}}
            <input type="file" name="profile" class="form-control input_box_css" id="buyer_profile_image" value="{{old('profile')}}"  /></button>
            </div>
            
            <div class="text-danger">{{$errors->first('filename')}}</div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

            <div class="form-group">
              <label  class="label_css">{{ __('users.where_did_you_find_us_label')}} </label>
              <input type="text" class="form-control login_input input_box_css" name="find_us" id="find_us" placeholder="{{ __('users.where_did_you_find_us_label')}}" value="{{ (old('where_find_us')) ? old('where_find_us') : $buyerDetails[0]->where_find_us}}">
              <span class="invalid-feedback" id="err_find_us">@if($errors->has('find_us')) {{ $errors->first('find_us') }}@endif</span>
            </div>
            <div>
            <button class="btn btn-black debg_color login_btn update-buyer-profile">{{ __('lang.update_btn')}}</button>
            <a href="{{route('frontHome')}}" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('lang.cancel_btn')}}</a>
          </div>
                    
        </div>
      
      </div>
      
   <!--  </div> -->
    </form>
  </div>
  </div>
  </div>
  </div><!-- row -->
</div> <!-- /container -->
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });

$('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').parent("div").remove();
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});

</script>
@endsection
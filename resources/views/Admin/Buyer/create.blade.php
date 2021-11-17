@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{$info}}</p>
  <form method="POST" action="{{route('adminBuyersStoreUpdate')}}"
  enctype="multipart/form-data" class="needs-validation" novalidate="">

  @csrf
  <div class="row">
    <div class="col-12 col-md-6 col-lg-6">
      <div class="card">
        
        <input type="hidden" class="form-control" name="hid" id="hid" value="{{ !empty($buyerDetails[0]->id) ?  $buyerDetails[0]->id : '' }}">

        <div class="card-body">
          <div class="form-group">
            <label>{{ __('users.first_name_label')}}  <span class="text-danger">*</span></label> 
            <input type="text" class="form-control" name="fname" id="fname" tabindex="1" value="{{ !empty($buyerDetails[0]->fname) ?  $buyerDetails[0]->fname :  old('fname') }}" onblur="allLetter(this)">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_first_name_err')}}
            </div>
            <div class="text-danger err-letter">{{$errors->first('fname')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.last_name_label')}} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="lname" id="lname" tabindex="2" value="{{ !empty($buyerDetails[0]->lname) ?  $buyerDetails[0]->lname :  old('lname') }}" onblur="allLetterLname(this)">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_last_name_err')}}
            </div>
            <div class="text-danger errlast_name">{{$errors->first('lname')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.email_label')}} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="email" id="email" tabindex="3" value="{{ !empty($buyerDetails[0]->email) ? $buyerDetails[0]->email : old('email')}}">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_email_err')}}
            </div>
            <div class="text-danger">{{$errors->first('email')}}</div>
          </div>


          <div class="form-group">
            <label>{{ __('users.phone_number_label')}} </label>
            <div class="input-group">
              <span style="margin-top: 10px;" class="col-md-2">+46</span><input type="text" class="col-md-10 form-control phone-number" name="phone_number" id="phone_number" tabindex="4" value="{{ !empty($buyerDetails[0]->phone_number) ? $buyerDetails[0]->phone_number : old('phone_number')}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_phone_number_err')}}
              </div>
            </div>
            <div class="text-danger">{{$errors->first('phone_number')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.address_label')}}</label>
            <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5" value="{{old('address')}}"><?php if(!empty($buyerDetails[0]->address)){ echo $buyerDetails[0]->address; }?></textarea>
            <div class="invalid-feedback">
              {{ __('errors.fill_in_address_err')}}
            </div>
            <div class="text-danger">{{$errors->first('address')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.postal_code_label')}}</label>
            <input type="text" class="form-control" name="postcode" id="postcode" tabindex="6" value="{{ !empty($buyerDetails[0]->postcode) ?  $buyerDetails[0]->postcode : old('postcode') }}">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_postal_code_err')}}
            </div>
            <div class="text-danger">{{$errors->first('address')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.city_label')}}</label>
            <input type="text" class="form-control" name="city" id="city" tabindex="6" value="{{ !empty($buyerDetails[0]->city) ?  $buyerDetails[0]->city : old('city') }}">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_city_err')}}
            </div>
            <div class="text-danger">{{$errors->first('city')}}</div>
          </div>

          <?php /* <div class="form-group">
            <label>{{ __('users.swish_number_label')}} </label>
            <input type="text" class="form-control" name="swish_number" id="swish_number" tabindex="8" value="{{ !empty($buyerDetails[0]->swish_number) ?  $buyerDetails[0]->swish_number : old('swish_number')}}">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_swish_number_err')}}
            </div>
            <div class="text-danger">{{$errors->first('swish_number')}}</div>
          </div> */?>

        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="form-group increment cloned">
            <label>{{ __('users.profile_label')}}</label><br>
            @php
            if(!empty($buyerDetails[0]->profile))
            {
              echo '<div class="row">';
              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Buyer/resized/'.$buyerDetails[0]->profile.'" style="width:200px;height:200px;"></div>';
              echo '</div>';
              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              echo '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>';
            }
            @endphp

            <input type="file" name="profile" class="form-control" value="{{old('profile')}}">
           <!--  <div class="upload-btn-wrapper">
            <button class="uploadbtn"><i class="fa fa-upload" aria-hidden="true"></i> {{ __('users.upload_file_input')}}</button>
            <input type="file" name="profile" class="form-control" value="{{old('profile')}}" />
           </div> -->
            <div class="text-danger">{{$errors->first('filename')}}</div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

            <!-- <div class="form-group">
              <label>Where did you find us?</label>
              <input type="text" class="form-control" name="find_us" id="find_us" tabindex="9" value="{{ !empty($buyerDetails[0]->where_find_us) ?  $buyerDetails[0]->where_find_us : old('find_us')}}">
              <div class="invalid-feedback">
                Please fill in Where did you find us.
              </div>
              <div class="text-danger">{{$errors->first('find_us')}}</div>
            </div> -->

        </div>
        
        <div class="col-12 text-right">
        <?php if(!empty($buyerDetails[0]->id)){ ?>
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
        <?php  }else{ ?>

          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
        <?php } ?>
          <a href="{{route('adminBuyers')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#phone_number').mask('00 000 000');
  });

$('body').on('click', '.remove_image', function () {
    $('.existing-images').parent("div").remove();
    $(this).remove();
});



  /*function to validate letters for fname*/
  function allLetter(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      $('.err-letter').text("{{ __('errors.input_alphabet_err')}}");
      return false;
    }
  }
  
 /*function to validate letters for lname*/
  function allLetterLname(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
       $('.errlast_name').text('');
      return true;
    }
    else {
      $('.errlast_name').text("{{ __('errors.input_alphabet_err')}}");
      return false;
    }
  }
</script>
@endsection('middlecontent')
@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{ __('users.edit_seller_details_title')}}</p>
  <form method="POST" action="{{route('adminSellerUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.contact_first_name_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="fname" id="fname" required tabindex="1" value="{{ (old('fname')) ?  old('fname') : $sellerDetails[0]->fname}}">
              <input type="hidden"  name="hid" id="hid"  value="{{$sellerDetails[0]->id}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_first_name_err')}}
              </div>
              <div class="text-danger">{{$errors->first('fname')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.contact_last_name_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="lname" id="lname" required tabindex="1" value="{{ (old('lname')) ?  old('lname') : $sellerDetails[0]->lname}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_last_name_err')}}
              </div>
              <div class="text-danger">{{$errors->first('lname')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.contact_email_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="email" id="email" required tabindex="3" value="{{ (old('email')) ? old('email') : $sellerDetails[0]->email}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_email_err')}}
              </div>
              <div class="text-danger">{{$errors->first('email')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.contact_phone_number_label')}} </label>
              <div class="input-group">
                <span style="margin-top: 10px;" class="col-md-2">+46</span> <input type="text" class="col-md-10 form-control phone-number" name="phone_number" id="phone_number" tabindex="7" value="{{ (old('phone_number')) ? old('phone_number') : $sellerDetails[0]->phone_number}}">
                <div class="invalid-feedback">
                  {{ __('errors.fill_in_phone_number_err')}}
                </div>
              </div>
              <div class="text-danger">{{$errors->first('phone_number')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.address_label')}}</label>
              <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5"><?php if(!empty($sellerDetails[0]->address)){ echo $sellerDetails[0]->address; }?></textarea>
              <div class="invalid-feedback">
                {{ __('errors.fill_in_address_err')}}
              </div>
              <div class="text-danger">{{$errors->first('address')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.postal_code_label')}}</label>
              <input type="text" class="form-control" name="postcode" id="postcode" tabindex="6" value="{{ (old('postcode')) ? old('postcode') : $sellerDetails[0]->postcode}}">
              <div class="invalid-feedback">
              {{ __('errors.fill_in_postal_code_err')}}
              </div>
              <div class="text-danger">{{$errors->first('postcode')}}</div>
            </div>

            
          <div class="form-group">
            <label>{{ __('users.city_label')}}</label>
            <input type="text" class="form-control" name="city" id="city" tabindex="6" value="{{ (old('city')) ? old('city') : $sellerDetails[0]->city}}">
            <div class="invalid-feedback">
              {{ __('errors.fill_in_city_err')}}
            </div>
            <div class="text-danger">{{$errors->first('city')}}</div>
          </div>

            <!-- <div class="form-group">
              <label>{{ __('users.swish_number_label')}} </label>
              <input type="text" class="form-control" name="swish_number" id="swish_number" tabindex="8" value="{{ (old('swish_number')) ? old('swish_number') : $sellerDetails[0]->swish_number}}">
              <div class="invalid-feedback">
               {{ __('errors.fill_in_swish_number_err')}}
              </div>
              <div class="text-danger">{{$errors->first('swish_number')}}</div>
            </div> -->


          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.store_name_label')}}</label>
              <input type="text" class="form-control" name="store_name" id="store_name" tabindex="9" value="{{ (old('store_name')) ? old('store_name') : $sellerDetails[0]->store_name}}" onblur="checkStoreName(this)">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_store_name_err')}}
              </div>
              <div class="text-danger">{{$errors->first('store_name')}}</div>
            </div>

            <!-- <div class="form-group">
              <label>{{ __('users.paypal_email_address_label')}}</label>
              <input type="text" class="form-control" name="paypal_email" id="paypal_email" tabindex="10" value="{{ (old('paypal_email')) ? old('paypal_email') : $sellerDetails[0]->paypal_email}}">
              <div class="invalid-feedback">
                {{ __('errors.paypal_email_address_err')}}
              </div>
              <div class="text-danger">{{$errors->first('paypal_email')}}</div>
            </div> -->

            <!-- <div class="form-group">
              <label>Where did you find us?</label>
              <input type="text" class="form-control" name="find_us" id="find_us" tabindex="9"  value="{{ (old('find_us')) ? old('find_us') : $sellerDetails[0]->where_find_us}}">
              <div class="invalid-feedback">
                Please fill in Where did you find us.
              </div>
              <div class="text-danger">{{$errors->first('find_us')}}</div>
            </div> -->

            <div class="form-group">
              <label>{{ __('users.description_label')}}</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"> <?php if(!empty($sellerDetails[0]->description)){ echo $sellerDetails[0]->description; }?></textarea>
              <div class="text-danger">{{ ($errors->has('description')) ? $errors->first('description') : '' }}</div>
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
                  echo '<div style="margin-left:20px;" class="col-md-4 existing-images"><img src="'.url('/').'/uploads/SellerImages/'.$image['image'].'" style="width:140px;height:140px;"><div style="margin-top:10px;margin-bottom:20px;"><a href="javascript:void(0)" onclick="return ConfirmDeleteFunction1(\''.url('/').'/admin/seller/delete-image/', base64_encode($image['id']).'\');"  title="'. __('lang.delete_title').'" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a></div></div>';
                  }
                }
              

                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              @endphp
              @if (count($imagedetails['getImages']) < 5)
              <input type="file" name="sellerimages[]" class="form-control">
              <div class="input-group-btn text-right"> 
              <button class="btn btn-success add-image" type="button"><i class="fas fa-plus"></i> {{ __('users.add_more_btn')}}</button>
              </div>
              @endif
            </div>

            <div class="clone hide" style="display:none;" >
              <div class="form-group cloned" style="margin-top:10px">
              <input type="file" name="sellerimages[]" class="form-control">
              <div class="input-group-btn text-right"> 
                <button class="btn btn-danger remove-image" type="button"><i class="fas fa-trash-alt"></i> {{ __('users.remove_btn')}}</button>
              </div>
              </div>
            </div>

            <div class="text-danger cloned-danger">{{$errors->first('sellerimages')}}</div>

         
            <?php /*
            <div class="form-group">
              <label>{{ __('users.is_verified_thead')}}</label>
              <select class="form-control" id="is_verified" name="is_verified">
              <option value="">{{ __('lang.select_label')}}</option> 
              <option value="1"  @if(isset( $sellerDetails[0]->is_verified) && ( $sellerDetails[0]->is_verified=='1')) {{ 'selected' }} @endif>{{ __('users.yes')}}</option>  
              <option value="0"  @if(isset( $sellerDetails[0]->is_verified) && ( $sellerDetails[0]->is_verified=='0')) {{ 'selected' }} @endif>{{ __('users.no')}}</option>
            </select>
              <div class="invalid-feedback">
                {{ __('errors.select_is_verified')}}
              </div>
              <div class="text-danger">{{$errors->first('is_verified')}}</div>
            </div>
              */
            ?>

            <div class="form-group">
            <label>
             {{ __('users.is_featured')}} </label>
              <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php if($sellerDetails[0]->is_featured ==  "1"){ echo "checked"; } ?>>
           </div>


          </div>
        </div>
        
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
          <a href="{{route('adminSeller')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('#phone_number').mask('00 000 00000');
  });

$(document).ready(function() {
  $(".add-image").click(function(){ 
    var existing_images = $(".existing-images").length;
    var cloned_images = $(".cloned:visible").length;

    if((existing_images + cloned_images) >= 5) {
      $(".cloned-danger").html("{{ __('users.max_images_restriction_seller')}}");
      return false;
    }
    var html = $(".clone").html();
    $(".increment").after(html);
  });

  $("body").on("click",".remove-image",function(){ 
  $(this).parents(".form-group").remove();
  $(".cloned-danger").html('')
  });
});

</script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });


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
</script>
@endsection('middlecontent')
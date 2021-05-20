@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">Edit Seller Details</p>
  <form method="POST" action="{{route('adminSellerUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Contact First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="fname" id="fname" required tabindex="1" value="{{ (old('fname')) ?  old('fname') : $sellerDetails[0]->fname}}">
              <input type="hidden"  name="hid" id="hid"  value="{{$sellerDetails[0]->id}}">
              <div class="invalid-feedback">
                Please fill in First Name
              </div>
              <div class="text-danger">{{$errors->first('fname')}}</div>
            </div>

            <div class="form-group">
              <label>Contact Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="lname" id="lname" required tabindex="1" value="{{ (old('lname')) ?  old('lname') : $sellerDetails[0]->lname}}">
              <div class="invalid-feedback">
                Please fill in Last Name
              </div>
              <div class="text-danger">{{$errors->first('lname')}}</div>
            </div>

            <div class="form-group">
              <label>Contact Email <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="email" id="email" required tabindex="3" value="{{ (old('email')) ? old('email') : $sellerDetails[0]->email}}">
              <div class="invalid-feedback">
                Please fill in Email
              </div>
              <div class="text-danger">{{$errors->first('email')}}</div>
            </div>

            <div class="form-group">
              <label>Phone Number </label>
              <div class="input-group">
                <span style="margin-top: 10px;" class="col-md-2">+46</span> <input type="text" class="col-md-10 form-control phone-number" name="phone_number" id="phone_number" tabindex="7" value="{{ (old('phone_number')) ? old('phone_number') : $sellerDetails[0]->phone_number}}">
                <div class="invalid-feedback">
                  Please fill in Phone Number
                </div>
              </div>
              <div class="text-danger">{{$errors->first('phone_number')}}</div>
            </div>

            <div class="form-group">
              <label>Address</label>
              <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5"><?php if(!empty($sellerDetails[0]->address)){ echo $sellerDetails[0]->address; }?></textarea>
              <div class="invalid-feedback">
                Please fill in your Address
              </div>
              <div class="text-danger">{{$errors->first('address')}}</div>
            </div>

            <div class="form-group">
              <label>Postal Code</label>
              <input type="text" class="form-control" name="postcode" id="postcode" tabindex="6" value="{{ (old('postcode')) ? old('postcode') : $sellerDetails[0]->postcode}}">
              <div class="invalid-feedback">
              Please fill in your Address
              </div>
              <div class="text-danger">{{$errors->first('postcode')}}</div>
            </div>

            
          <div class="form-group">
            <label>City</label>
            <input type="text" class="form-control" name="city" id="city" tabindex="6" value="{{ (old('city')) ? old('city') : $sellerDetails[0]->city}}">
            <div class="invalid-feedback">
              Please fill in your City
            </div>
            <div class="text-danger">{{$errors->first('city')}}</div>
          </div>

            <div class="form-group">
              <label>Swish Number </label>
              <input type="text" class="form-control" name="swish_number" id="swish_number" tabindex="8" value="{{ (old('swish_number')) ? old('swish_number') : $sellerDetails[0]->swish_number}}">
              <div class="invalid-feedback">
               Please fill in your Swish Number
              </div>
              <div class="text-danger">{{$errors->first('swish_number')}}</div>
            </div>


          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Store Name</label>
              <input type="text" class="form-control" name="store_name" id="store_name" tabindex="9" value="{{ (old('store_name')) ? old('store_name') : $sellerDetails[0]->store_name}}" onblur="checkStoreName(this)">
              <div class="invalid-feedback">
                Please fill in your Store Name
              </div>
              <div class="text-danger">{{$errors->first('store_name')}}</div>
            </div>

            <div class="form-group">
              <label>Paypal Email Address</label>
              <input type="text" class="form-control" name="paypal_email" id="paypal_email" tabindex="10" value="{{ (old('paypal_email')) ? old('paypal_email') : $sellerDetails[0]->paypal_email}}">
              <div class="invalid-feedback">
                Please fill in your Paypal Email
              </div>
              <div class="text-danger">{{$errors->first('paypal_email')}}</div>
            </div>

            <!-- <div class="form-group">
              <label>Where did you find us?</label>
              <input type="text" class="form-control" name="find_us" id="find_us" tabindex="9"  value="{{ (old('find_us')) ? old('find_us') : $sellerDetails[0]->where_find_us}}">
              <div class="invalid-feedback">
                Please fill in Where did you find us.
              </div>
              <div class="text-danger">{{$errors->first('find_us')}}</div>
            </div> -->

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"> <?php if(!empty($sellerDetails[0]->description)){ echo $sellerDetails[0]->description; }?></textarea>
              <div class="text-danger">{{ ($errors->has('description')) ? $errors->first('description') : '' }}</div>
            </div>

            <div class="form-group increment cloned">
              <label>Seller Image(s)</label>
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
              <input type="file" name="sellerimages[]" class="form-control">
              <div class="input-group-btn text-right"> 
              <button class="btn btn-success add-image" type="button"><i class="fas fa-plus"></i> Add More</button>
              </div>
              @endif
            </div>

            <div class="clone hide" style="display:none;" >
              <div class="form-group cloned" style="margin-top:10px">
              <input type="file" name="sellerimages[]" class="form-control">
              <div class="input-group-btn text-right"> 
                <button class="btn btn-danger remove-image" type="button"><i class="fas fa-trash-alt"></i> Remove</button>
              </div>
              </div>
            </div>

            <div class="text-danger cloned-danger">{{$errors->first('sellerimages')}}</div>

         

            <div class="form-group">
              <label>Is Verified</label>
              <select class="form-control" id="is_verified" name="is_verified">
              <option value="">Select</option> 
              <option value="1"  @if(isset( $sellerDetails[0]->is_verified) && ( $sellerDetails[0]->is_verified=='1')) {{ 'selected' }} @endif>Yes</option>  
              <option value="0"  @if(isset( $sellerDetails[0]->is_verified) && ( $sellerDetails[0]->is_verified=='0')) {{ 'selected' }} @endif>No</option>
            </select>
              <div class="invalid-feedback">
                Please Select Is Verified
              </div>
              <div class="text-danger">{{$errors->first('is_verified')}}</div>
            </div>

          </div>
        </div>
        
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
          <a href="{{route('adminSeller')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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
      $(".cloned-danger").html('Max 5 images are allowed for Seller.');
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
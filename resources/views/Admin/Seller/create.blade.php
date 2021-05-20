@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">Add Seller Details</p>
  <form method="POST" action="{{route('adminSellerStore')}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Contact First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="fname" id="fname" required tabindex="1" value="{{ old('fname')}}" >
              <div class="invalid-feedback">
                Please fill in First Name
              </div>
              <div class="text-danger">{{$errors->first('fname')}}</div>
            </div>

            <div class="form-group">
              <label>Contact Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="lname" id="lname" required tabindex="2" value="{{ old('lname')}}">
              <div class="invalid-feedback">
                Please fill in Last Name
              </div>
              <div class="text-danger">{{$errors->first('lname')}}</div>
            </div>

            <div class="form-group">
              <label>Contact Email <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="email" id="email" required tabindex="3" value="{{ old('email')}}">
              <div class="invalid-feedback">
              Please fill in Email
              </div>
              <div class="text-danger">{{$errors->first('email')}}</div>
            </div>

            <div class="form-group">
              <label>Contact Phone Number </label>
              <div class="input-group">
              <span style="margin-top: 10px;" class="col-md-2">+46</span> <input type="text" class="col-md-10 form-control phone-number" name="phone_number" id="phone_number" tabindex="4" value="{{ old('phone_number')}}">
              <div class="invalid-feedback">
                 Please fill in Phone Number
              </div>
              </div>
              <div class="text-danger">{{$errors->first('phone_number')}}</div>
            </div>

            <div class="form-group">
              <label>Address</label>
              <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5" value="{{ old('address')}}"></textarea>
              <div class="invalid-feedback">
                Please fill in your Address
              </div>
              <div class="text-danger">{{$errors->first('address')}}</div>
            </div>

            <div class="form-group">
              <label>Postal Code</label>
              <input type="text" class="form-control" name="postcode" id="postcode" tabindex="6" value="{{ old('postcode')}}">
              <div class="invalid-feedback">
                Please fill in your Address
              </div>
              <div class="text-danger">{{$errors->first('postcode')}}</div>
            </div>

            <div class="form-group">
              <label>City </label>
              <input type="text" class="form-control" name="city" id="city" tabindex="7" value="{{ old('city')}}">
              <div class="invalid-feedback">
                Please fill in your City
              </div>
              <div class="text-danger">{{$errors->first('city')}}</div>
            </div>

            <div class="form-group">
              <label>Swish Number </label>
              <input type="text" class="form-control" name="swish_number" id="swish_number" tabindex="8" value="{{ old('swish_number')}}">
              <div class="invalid-feedback">
                Please fill in your Swish Number
              </div>
              <div class="text-danger">{{$errors->first('swish_number')}}</div>
            </div>

            <div class="form-group">
              <label>Store Name</label>
              <input type="text" class="form-control" name="store_name" id="store_name" tabindex="9" value="{{ old('store_name') }}" onblur="checkStoreName(this)">
              <div class="invalid-feedback">
                Please fill in your Store Name
              </div>
              <div class="text-danger">{{$errors->first('store_name')}}</div>
            </div>
            
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">

            <div class="form-group">
              <label>Paypal Email Address</label>
              <input type="text" class="form-control" name="paypal_email" id="paypal_email" tabindex="10" value="{{ old('paypal_email')}}">
              <div class="invalid-feedback">
                Please fill in your Email
              </div>
              <div class="text-danger">{{$errors->first('email')}}</div>
            </div>

            <!-- <div class="form-group">
              <label>Where did you find us?</label>
              <input type="text" class="form-control" name="find_us" id="find_us" tabindex="9" value="{{ old('find_us') }}">
              <div class="invalid-feedback">
                Please fill in Where did you find us.
              </div>
              <div class="text-danger">{{$errors->first('find_us')}}</div>
            </div> -->

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"></textarea>
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
                  $path = public_path().'/uploads/agentWork/'.$image['image'];
                  if (file_exists($path)) {
                  echo '<div style="margin-left:20px;" class="col-md-4 existing-images"><img src="'.url('/').'/uploads/agentWork/'.$image['image'].'" style="width:140px;height:140px;"><div style="margin-top:10px;margin-bottom:20px;"><a href="javascript:void(0)" onclick="return ConfirmDeleteFunction1(\''.route('agentImageDelete', base64_encode($image['id'])).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a></div></div>';
                  }
                }
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              @endphp

              <input type="file" name="sellerimages[]"  tabindex="11" class="form-control">

              <div class="input-group-btn text-right"> 
                <button class="btn btn-success add-image" type="button"><i class="fas fa-plus"></i> Add More</button>
              </div>
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
          </div>
        </div>
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
          <a href="{{route('adminSeller')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('#phone_number').mask('00 000 000');
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
      url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name,
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
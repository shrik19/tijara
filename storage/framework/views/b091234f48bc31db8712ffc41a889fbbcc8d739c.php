
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">

    <div class="col-md-12 tijara-content">
      <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
       <div class="seller_info border-none">
        <div class="card">
        <div class="card-header row">
          <h2 class="page_heading"><?php echo e(__('users.buyer_profile_update_title')); ?></h2>
          <!-- <hr class="heading_line"/> -->
          </div>
        </div>
        <div class="seller_mid_cont"  style="margin-top: 20px;">
    
      <form id="buyer-update-form" action="<?php echo e(route('frontBuyerProfileUpdate')); ?>" method="post"  enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
      <div class="col-md-6">
        <!-- <h2> <?php echo e(__('users.buyer_profile_update_title')); ?></h2>
        <hr class="heading_line"/> -->
        
        <div class="login_box">
          
            <input type="hidden" name="role_id" value="<?php echo e($role_id); ?>">
            <div class="form-group">
              <label><?php echo e(__('users.first_name_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="fname" id="fname" placeholder="<?php echo e(__('users.first_name_label')); ?>" value="<?php echo e((old('fname')) ?  old('fname') : $buyerDetails[0]->fname); ?>">
              <span class="invalid-feedback" id="err_fname" ><?php if($errors->has('fname')): ?> <?php echo e($errors->first('fname')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <label><?php echo e(__('users.last_name_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="lname" id="lname" placeholder="<?php echo e(__('users.last_name_label')); ?>" value="<?php echo e((old('lname')) ?  old('lname') : $buyerDetails[0]->lname); ?>">
              <span class="invalid-feedback" id="err_lname" ><?php if($errors->has('lname')): ?> <?php echo e($errors->first('lname')); ?><?php endif; ?></span>
            </div>

            <div class="form-group" style="margin-top: 25px;">
              <label><?php echo e(__('users.email_label')); ?> <span class="de_col">*</span></label>
              <input type="email" class="form-control login_input" name="email" id="email" placeholder="<?php echo e(__('users.email_label')); ?>" value="<?php echo e((old('email')) ? old('email') : $buyerDetails[0]->email); ?>">
              <span class="invalid-feedback" id="err_email" ><?php if($errors->has('email')): ?> <?php echo e($errors->first('email')); ?><?php endif; ?></span>
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
              <label><?php echo e(__('users.address_label')); ?> </label>
                <textarea class="form-control" id="address" name="address" rows="5" cols="30" style="height:auto" tabindex="5" placeholder="<?php echo e(__('users.address_label')); ?>"><?php if(!empty($buyerDetails[0]->address)){ echo $buyerDetails[0]->address; }?></textarea>
              <span class="invalid-feedback" id="err_address"><?php if($errors->has('address')): ?> <?php echo e($errors->first('address')); ?><?php endif; ?></span>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.postal_code_label')); ?> </label>
              <input type="text" class="form-control login_input" name="postcode" id="postcode" placeholder="<?php echo e(__('users.postal_code_label')); ?>" value="<?php echo e((old('postcode')) ? old('postcode') : $buyerDetails[0]->postcode); ?>">
              <span class="invalid-feedback" id="err_address"><?php if($errors->has('postcode')): ?> <?php echo e($errors->first('postcode')); ?><?php endif; ?></span>
            </div>

            <div class="form-group">
              <label><?php echo e(__('users.location_label')); ?> </label>
              <input type="text" class="form-control login_input" name="city" id="city" placeholder="<?php echo e(__('users.location_label')); ?>" value="<?php echo e((old('city')) ? old('city') : $buyerDetails[0]->city); ?>">
              <span class="invalid-feedback" id="err_city"><?php if($errors->has('city')): ?> <?php echo e($errors->first('city')); ?><?php endif; ?></span>
            </div>
           
        </div>
      </div>
      

       <div class="col-md-6">
       
        
        <div class="login_box">
          <!-- <div class="form-group">
            <label><?php echo e(__('users.swish_number_label')); ?> </label>
            <input type="text" class="form-control login_input" name="swish_number" id="swish_number" placeholder="<?php echo e(__('users.swish_number_label')); ?>" value="<?php echo e((old('swish_number')) ? old('swish_number') : $buyerDetails[0]->swish_number); ?>">
            <span class="invalid-feedback" id="err_swish_number"><?php if($errors->has('swish_number')): ?> <?php echo e($errors->first('swish_number')); ?><?php endif; ?></span>
          </div> -->

          <div class="form-group increment cloned">
            <label><?php echo e(__('users.select_profile_picture')); ?></label>
            <?php
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
            ?>

            <!-- <input type="file" name="profile" class="form-control" value="<?php echo e(old('profile')); ?>"> -->
            <div class="upload-btn-wrapper">
            <button class="uploadbtn buyer_profile_update_btn"><i class="fa fa-upload" aria-hidden="true" style=""></i> <?php echo e(__('users.upload_file_input')); ?></button>
            <input type="file" name="profile" class="form-control" value="<?php echo e(old('profile')); ?>" />
            </div>
            
            <div class="text-danger"><?php echo e($errors->first('filename')); ?></div>
            <div class="input-group-btn text-right"> 
            </div>
          </div>

            <div class="form-group">
              <label><?php echo e(__('users.where_did_you_find_us_label')); ?> </label>
              <input type="text" class="form-control login_input" name="find_us" id="find_us" placeholder="<?php echo e(__('users.where_did_you_find_us_label')); ?>" value="<?php echo e((old('where_find_us')) ? old('where_find_us') : $buyerDetails[0]->where_find_us); ?>">
              <span class="invalid-feedback" id="err_find_us"><?php if($errors->has('find_us')): ?> <?php echo e($errors->first('find_us')); ?><?php endif; ?></span>
            </div>
            <button class="btn btn-black debg_color login_btn update-buyer-profile"><?php echo e(__('lang.update_btn')); ?></button>
            <a href="<?php echo e(route('frontHome')); ?>" class="btn btn-black gray_color login_btn" tabindex="16"> <?php echo e(__('lang.cancel_btn')); ?></a>
                    
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

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/buyer_profile.blade.php ENDPATH**/ ?>
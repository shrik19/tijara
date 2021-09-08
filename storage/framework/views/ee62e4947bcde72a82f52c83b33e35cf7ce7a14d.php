
<?php $__env->startSection('middlecontent'); ?>
<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-2 tijara-sidebar ">
      <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="col-md-10 tijara-content ">
      <?php if(!empty($package_exp_msg)): ?>
          <div class="alert alert-danger" role="alert">
            <a href="<?php echo e(route('frontSellerPackages')); ?>" style="color: #a94442"><?php echo e($package_exp_msg); ?></a>
          </div>
      <?php endif; ?>
    <form id="seller-profile-form" action="<?php echo e(route('frontSellerProfileUpdate')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
      <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="col-md-12">
        <div class="seller_info">
	    <div class="card-header row seller_header">
			  <h2><?php echo e(__('users.profile_update_title')); ?></h2>
			  <!-- <hr class="heading_line"> -->
		 </div>  
        <div class="login_box seller_mid_cont">
          
            <h2 class="col-md-12 contact-info seller_mid_header"><?php echo e(__('users.contact_person')); ?></h2>
          
            <input type="hidden" name="role_id" value="<?php echo e($role_id); ?>">
            <div class="form-group col-md-6">
              <label><?php echo e(__('users.first_name_label')); ?><span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="fname" id="fname" placeholder="<?php echo e(__('users.first_name_label')); ?>" value="<?php echo e((old('fname')) ?  old('fname') : $sellerDetails[0]->fname); ?>">
              <span class="invalid-feedback" id="err_fname"><?php if($errors->has('fname')): ?> <?php echo e($errors->first('fname')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group  col-md-6">
              <label><?php echo e(__('users.last_name_label')); ?><span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="lname" id="lname" placeholder="<?php echo e(__('users.last_name_label')); ?>" value="<?php echo e((old('lname')) ?  old('lname') : $sellerDetails[0]->lname); ?>">
              <span class="invalid-feedback" id="err_lname"><?php if($errors->has('lname')): ?> <?php echo e($errors->first('lname')); ?><?php endif; ?></span>
            </div>

            <div class="form-group  col-md-6">
              <label><?php echo e(__('users.email_label')); ?><span class="de_col">*</span></label>
              <input type="email" class="form-control login_input" name="email" id="email" placeholder="<?php echo e(__('users.email_label')); ?>" value="<?php echo e((old('email')) ? old('email') : $sellerDetails[0]->email); ?>">
              <span class="invalid-feedback" id="err_email"><?php if($errors->has('email')): ?> <?php echo e($errors->first('email')); ?><?php endif; ?></span>
            </div>          

            <div class="form-group  col-md-6">
              <label><?php echo e(__('users.phone_number_label')); ?></label>
              <!-- <span style="margin-top: 10px;" class="col-md-2">+46</span> -->
              <input type="text" class="form-control login_input" name="phone_number" id="phone_number" placeholder="<?php echo e(__('users.phone_number_label')); ?>" value="<?php echo e((old('phone_number')) ? old('phone_number') : $sellerDetails[0]->phone_number); ?>">
              <span class="invalid-feedback" id="err_phone_number"><?php if($errors->has('phone_number')): ?> <?php echo e($errors->first('phone_number')); ?><?php endif; ?></span>
            </div>

           
            <div class="form-group col-md-6">
              <label><?php echo e(__('users.address_label')); ?></label>
              <input type="text" class="form-control login_input" name="address" id="address" placeholder="<?php echo e(__('users.address')); ?>" value="<?php echo e((old('address')) ? old('address') : $sellerDetails[0]->address); ?>">
              
              <span class="invalid-feedback" id="err_address"><?php if($errors->has('address')): ?> <?php echo e($errors->first('address')); ?><?php endif; ?></span>
            </div> 

            <div class="form-group  col-md-6">
              <label><?php echo e(__('users.city_label')); ?></label>
              <input type="text" class="form-control login_input" name="city" id="city" placeholder="<?php echo e(__('users.city_label')); ?>" value="<?php echo e((old('city')) ? old('city') : $sellerDetails[0]->city); ?>">
              <span class="invalid-feedback" id="err_city"><?php if($errors->has('city')): ?> <?php echo e($errors->first('city')); ?><?php endif; ?></span>
            </div>
            <div class="form-group col-md-6">
              <label><?php echo e(__('users.postal_code_label')); ?></label>
              <input type="text" class="form-control login_input" name="postcode" id="postcode" placeholder="<?php echo e(__('users.postal_code_label')); ?>" value="<?php echo e((old('postcode')) ? old('postcode') : $sellerDetails[0]->postcode); ?>">
              <span class="invalid-feedback" id="err_address"><?php if($errors->has('postcode')): ?> <?php echo e($errors->first('postcode')); ?><?php endif; ?></span>
            </div>

            <h2 class="col-md-12"><?php echo e(__('users.shipping_setting')); ?></h2>
          
            <div class="form-group col-md-6" id="shipping_method_ddl_div">
              <label><?php echo e(__('users.shipping_method_label')); ?></label>
             <select class="form-control login_input" name="shipping_method_ddl" id="shipping_method_ddl">
               <option value=""><?php echo e(__('users.select_shipping_method')); ?></option>
               <option  <?php if($sellerDetails[0]->shipping_method ==  trans('users.flat_shipping_charges')){ echo "selected"; } ?>><?php echo e(__('users.flat_shipping_charges')); ?></option>
               <option <?php if($sellerDetails[0]->shipping_method ==  trans('users.prcentage_shipping_charges')){ echo "selected"; } ?>><?php echo e(__('users.prcentage_shipping_charges')); ?></option>
             </select>
            </div>

            <div class="form-group col-md-6" id="shipping_charges_div">
              <label><?php echo e(__('users.shipping_charges_label')); ?></label>
              <input type="text" class="form-control login_input" name="shipping_charges" id="shipping_charges" placeholder="<?php echo e(__('users.shipping_charges_label')); ?>" value="<?php echo e((old('shipping_charges')) ? old('shipping_charges') : $sellerDetails[0]->shipping_charges); ?>">
            </div>

            <label class="col-md-12">
             <?php echo e(__('users.free_shipping_label')); ?>

              <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($sellerDetails[0]->free_shipping ==  "free_shipping"){ echo "checked"; } ?>>
            </label>

            <h2 class="col-md-12"><?php echo e(__('users.payment_setting')); ?></h2>
          
            <div class="form-group col-md-6">
              <label><?php echo e(__('users.klarna_username_label')); ?></label>
              <input type="text" class="form-control login_input" name="klarna_username" id="klarna_username" placeholder="<?php echo e(__('users.klarna_username_label')); ?>" value="<?php echo e((old('klarna_username')) ? old('klarna_username') : $sellerDetails[0]->klarna_username); ?>">
              <span class="invalid-feedback"><?php if($errors->has('klarna_username')): ?> <?php echo e($errors->first('klarna_username')); ?><?php endif; ?></span>
            </div>

            <div class="form-group col-md-6">
              <label><?php echo e(__('users.klarna_password_label')); ?></label>
              <input type="password" class="form-control login_input" name="klarna_password" id="klarna_password" placeholder="<?php echo e(__('users.klarna_password_label')); ?>" value="<?php echo e((old('klarna_password')) ? old('klarna_password') : $sellerDetails[0]->klarna_password); ?>">
              <span class="invalid-feedback"><?php if($errors->has('klarna_password')): ?> <?php echo e($errors->first('klarna_password')); ?><?php endif; ?></span>
            </div>
        </div>

      </div>
      
        <div style="text-align: center">
          <button class="btn btn-black debg_color seller-profile-update login_btn"><?php echo e(__('lang.update_btn')); ?></button>
          <a href="<?php echo e(route('frontHome')); ?>" class="btn btn-black gray_color login_btn" tabindex="16"><?php echo e(__('lang.cancel_btn')); ?></a>
        </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div> <!-- /container -->
</div>

<script>


  /*function to check unique store name
* @param  : store name
*/
  function checkStoreName(inputText){

    var store_name= inputText.value;
     $.ajax({
      url: "<?php echo e(url('/')); ?>"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+$('#hid').val(),
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
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="<?php echo e(url('/')); ?>/assets/js/sweetalert.js"></script><!-- 
<script src="<?php echo e(url('/')); ?>/assets/js/jquery.mask.min.js"></script> -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/seller_profile.blade.php ENDPATH**/ ?>
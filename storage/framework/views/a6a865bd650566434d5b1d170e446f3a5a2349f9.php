
<?php $__env->startSection('middlecontent'); ?>
<style type="text/css">
   ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
   .invalid-feedback {
    position: relative;
  }
  .seller_info{
  	margin-bottom: 60px;
  }
</style>
<div class="mid-section <?php if($is_seller==1): ?> sellers_top_padding  <?php else: ?> p_155 <?php endif; ?>">
	<div class="container-fluid">
		<div class="container-inner-section-1">
			<!-- Example row of columns -->
			<div class="row">
					<input type="hidden" name="is_seller" class="is_seller" value="<?php echo e($is_seller); ?>">
					<?php if($is_seller==1): ?>
						<div class="col-md-2 tijara-sidebar">
							<?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
						</div>
						<div class="col-md-10 tijara-content">
							<?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
							<div class="seller_info">
								<div class="card-header row seller_header">
									<h2 <?php if($is_seller==1): ?> class="seller_page_heading" <?php endif; ?>><?php echo e(__('users.change_password_title')); ?></h2>					  
								</div>

					<?php else: ?>
						<div class="col-md-12 tijara-content">
							<?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
							<div class="seller_info border-none">
								<div class="card">
									<div class="card-header ml-0 row">
											<div class="col-md-9 pl-0">
										<!-- <div class="col-md-3"></div>
										<div class="col-md-5"> -->
										<h2 class="page_heading"><?php echo e(__('users.change_password_title')); ?> </h2>
									</div>
									</div>
								</div>
							<?php endif; ?>
					<!-- 			<div class="col-md-12"> -->
									<div>
										<!--   <div class="col-md-2"></div> -->
										<div class="col-md-5 <?php if($is_seller!=1): ?> pl-0 tjsp0 <?php endif; ?>" >
											<?php if($is_seller==1): ?>
												<div class="login_box seller_mid_cont" style="margin-top: 20px;">
											<?php else: ?>
												<div class="login_box" style="margin-top: 20px;">
											<?php endif; ?>	
												<form method="POST" action="<?php echo e(route('frontChangePasswordStore')); ?>" class="needs-validation seller-chng-pass" id="seller-change-pass" novalidate="">
													<?php echo csrf_field(); ?>
													<?php if($is_seller==1): ?>
													<div class="form-group">
														<label class="label_css"><?php echo e(__('users.old_password_label')); ?></label>
														<input type="password" class="form-control ge_input" id="old_password" name="old_password" required tabindex="1" placeholder="<?php echo e(__('users.old_password_label')); ?>">
														<span class="invalid-feedback" id="err_old_password"><?php if($errors->has('old_password')): ?> <?php echo e($errors->first('old_password')); ?><?php endif; ?> </span>
													</div>
													<?php endif; ?>

													<div class="form-group">
														<label class="label_css"><?php echo e(__('users.new_password_label')); ?></label>
														<input type="password" class="form-control ge_input" name="password" id="password" required tabindex="1" placeholder="<?php echo e(__('users.new_password_label')); ?>">
														<span class="invalid-feedback" id="err_password"><?php if($errors->has('password')): ?> <?php echo e($errors->first('password')); ?><?php endif; ?> </span>
													</div>

													<div class="form-group" style="margin-top: 25px;">
														<label class="label_css"><?php echo e(__('users.password_confirmation_label')); ?></label>
														<input type="password" class="form-control ge_input" name="password_confirmation" required tabindex="2" id="password_confirmation" placeholder="<?php echo e(__('users.password_confirmation_label')); ?>">
														<span class="invalid-feedback" id="err_confirm_password"><?php if($errors->has('password_confirmation')): ?> <?php echo e($errors->first('password_confirmation')); ?><?php endif; ?> </span>
													</div>

													<?php if($is_seller !=1): ?> 
														<div class="tj-change-password">
													<?php else: ?>
														<div style="margin-top: 30px;">
													<?php endif; ?>
															<button type="submit" name="btnCountryCreate" id="changePasswordBtn" class="btn btn-black debg_color login_btn"><?php echo e(__('lang.save_btn')); ?></button>
															<a href="<?php echo e(url()->previous()); ?>" class="btn btn-black gray_color login_btn"> <?php echo e(__('lang.cancel_btn')); ?></a>
														</div>
												</form>
											</div>
										</div>
										<?php if($is_seller !=1): ?>
										<div class="col-md-7"></div>
										<?php endif; ?>									
									</div>
								</div>
					<!-- 		</div> -->
						</div>
				</div>
			</div>
		</div>

</div> <!-- /container -->

<script type="text/javascript">

 $("#changePasswordBtn").click(function(){
 	var is_seller = $(".is_seller").val(); 	
 	var password = $("#password").val();
 	var password_confirmation = $("#password_confirmation").val();
 	var error = 0; 	 
	if(is_seller==1){
 		var old_password = $("#old_password").val();
 	
	 	if(old_password==''){
	 		$("#err_old_password").html(required_field_error).show();
		    $("#err_old_password").parent().addClass('jt-error');
		    error = 1;
	 	}else{
	 		$("#err_old_password").html('');
	 		 //checkPassword(old_password)
		  
	 	}

	 	if(old_password !=''){
	 		 $.ajax({
		    url: siteUrl+'/check-old-password',
		    type: 'get',
		    async:false,
		    data: { old_password:old_password},
		    success: function(output){
		      if(output.error !=''){
		        showErrorMessage(output.error);
		        error = 1;
		      }
		    }
		  });
	 
	 	}
	}
	
	if(password == '')
	{
		$("#err_password").html(required_field_error).show();
		$("#err_password").parent().addClass('jt-error');
		error = 1;
	}
	else
	{
		$("#err_password").html('');
	}

   if(password_confirmation == '')
  {
    $("#err_confirm_password").html(required_field_error).show();
    $("#err_confirm_password").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_confirm_password").html('');
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#seller-change-pass').submit();
    return true;
  }
});
/*function convertToSlug by its name*/
/*function checkPassword(inputtxt){
  var password = inputtxt.value;

   $.ajax({
    url: siteUrl+'/check-old-password',
    type: 'get',
    data: { old_password:password},
    success: function(output){
      if(output.error !=''){
        showErrorMessage(output.error);
      }
    }
  });

}*/
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/change_password.blade.php ENDPATH**/ ?>
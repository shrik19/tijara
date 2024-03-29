<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo e($siteDetails->site_title); ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="<?php echo e(csrf_token()); ?>">
  <link rel="shortcut icon" href="<?php echo e(url('/')); ?>/assets/img/favicon.png" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?php echo e(url('/')); ?>/assets/front/apple-touch-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css" integrity="sha512-OdEXQYCOldjqUEsuMKsZRj93Ht23QRlhIb8E/X0sbwZhme8eUw6g8q7AdxGJKakcBbv7+/PX0Gc2btf7Ru8cZA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/bootstrap.min.css">

  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/main.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/jquery-confirm.min.css">
  <!-- added custom css for custom chnages -->
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/custom.css">
 <style type="text/css">
 	.invalid-feedback {
   	 position: relative;
	}
 </style>
   <!-- end custom css for custom chnages -->
  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	<script type="text/javascript">
    var siteUrl="<?php echo e(url('/')); ?>";
    var fill_in_email_err="<?php echo e(__('errors.fill_in_email_err')); ?>";
    var fill_in_password_err="<?php echo e(__('errors.fill_in_password_err')); ?>";
    var valid_email_err = "<?php echo e(__('errors.valid_email_err')); ?>";
    var password_min_6_char="<?php echo e(__('errors.password_min_6_char')); ?>";
    var password_not_matched="<?php echo e(__('errors.password_not_matched')); ?>";
    var fill_in_first_name_err="<?php echo e(__('errors.fill_in_first_name_err')); ?>";
    var fill_in_last_name_err="<?php echo e(__('errors.fill_in_last_name_err')); ?>";
    var please_enter_store_name = "<?php echo e(__('errors.please_enter_store_name')); ?>";
    var store_name_is_verified = "<?php echo e(__('users.store_name_is_verified')); ?>";
    var select_package_to_subscribe = "<?php echo e(__('errors.select_package_to_subscribe')); ?>";
    var please_check_privacy_policy = "<?php echo e(__('errors.please_check_privacy_policy')); ?>";
    var verify_store = "<?php echo e(__('users.verify_store')); ?>";
    var please_add_payment_details = "<?php echo e(__('errors.please_add_payment_details')); ?>";
    var please_select_one_payment_method = "<?php echo e(__('errors.please_select_one_payment_method')); ?>";
</script>
  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/bootstrap.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-confirm.min.js"></script>

</head>
<body>

<!-- multistep seller registration wizard start here -->

<!-- MultiStep Form -->
<div class="container-fluid">
	<div class="row">
		<div class="seller_register_container">
			<div class="col-md-4 left-section">
                <?php if(!empty($banner->image)): ?>
				<div class="register_banner" style="background-image: url(<?php echo e(url('/')); ?>/uploads/Banner/resized/<?php echo e($banner->image); ?>);">
					<div class="register_banner-header">
						<h1>Sveriges första kulturella marknadsplats</h1>
					</div>
					<div class="register_banner-footer text-right">
						<h2>Säljare</h2>
						<img src="<?php echo e(url('/')); ?>/uploads/Images/<?php echo e($siteDetails->header_logo); ?>" class="tijara-login-logo"/>						
					</div> 
				</div>
                <?php endif; ?>
			</div>
			<div class="col-md-8 right-section">
				<div class="row mt-0">
					<div class="col-md-12">
						<h2 class="text-center sell_with_tijara_head">
						<?php if(!empty(Session::get('StepsHeadingTitle'))): ?><?php echo e(Session::get('StepsHeadingTitle')); ?> <?php else: ?> <?php echo e($headingTitle); ?> <?php endif; ?>
						</h2>

						<div id="msform">
							<div class="loader-seller" style="display:none;"></div>
							<!-- progressbar -->
							<ul id="progressbar">
								<li class="active" id="account"><span class="step_1">1</span></spa><?php echo e(__('users.step_one_head')); ?></li>
								<li id="personal"><span class="step_2">2</span><?php echo e(__('users.step_two_head')); ?></li>
								<li id="payment"><span class="step_2">3</span><?php echo e(__('users.step_three_head')); ?></li>
								<li id="confirm"><span class="step_2">4</span><?php echo e(__('users.step_four_head')); ?></li>
							</ul> 
							<?php
								$email = $password= $role_id = $cpassword ='';
								$email=Session::get('new_seller_email');
								$password=Session::get('new_seller_password');
								$role_id =Session::get('new_seller_role_id');
								$cpassword =Session::get('new_seller_cpassword');

								if(!empty(Session::get('next_step'))){
									$next_step =Session::get('next_step');
								}else{
									$next_step = $next_step;
								}
								if(!isset($_COOKIE['tijara_register_current_step']))
									$_COOKIE['tijara_register_current_step']='seller_register_first';
								$current_step=$_COOKIE['tijara_register_current_step'];
								
							 ?>
								  
							<input type="hidden" name="" id="current_step" value="<?php echo e($current_step); ?>">
							<input type="hidden" name="" id="current_step_button" value="<?php echo e($next_step); ?>">
							<!-- fieldsets -->
							<fieldset class="seller_register_first">
								<form id="sellerRegisterForm" action="<?php echo e(route('frontNewSellerRegister')); ?>" method="post">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="role_id" id="role_id" value="<?php echo e($role_id); ?>">
									<!-- <label><?php echo e(__('users.email_label')); ?><span class="de_col">*</span></label> -->
									<input type="email" name="email" id="email" class="form-control" placeholder="<?php echo e(__('users.email_label')); ?> *" value="<?php echo e($email); ?>"/> 
									<span class="invalid-feedback" id="err_email"></span><br>

									<!-- <label><?php echo e(__('users.password_label')); ?><span class="de_col">*</span></label> -->
									<input type="password" name="password" id="password" class="form-control" placeholder="<?php echo e(__('users.password_label')); ?> *"  value="<?php echo e($password); ?>" style="margin-top:10px;" />
									<span class="invalid-feedback" id="err_password" style=""></span><br>

									<!-- <label><?php echo e(__('users.password_confirmation_label')); ?><span class="de_col">*</span></label> -->
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="<?php echo e(__('users.password_confirmation_label')); ?> *" value="<?php echo e($cpassword); ?>" style="margin-top:10px;" />
									<span class="invalid-feedback" id="err_cpassword"></span>
								</form>
								<input type="button" name="next" class="next btn debg_color action-button 2" value="<?php echo e(__('users.next_step_btn')); ?>" id="first-step"  />
							</fieldset>
					  
						 
							<fieldset class="seller_register_second" style="">
								<div class="form-card">
									<?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                                    <?php if(!empty($packageDetails)): ?>           
									  <div class="col-md-offset-2 col-md-10 package-html package-wrapper">
									  	<input type="hidden" name="selected_package_name" id="selected_package_name" class="form-control" value="" >
										<input type="hidden" name="session_package_name" id="session_package_name" class="form-control" value="<?php echo e(Session::get('new_seller_package_name')); ?>" >
										<?php 
											$i=1;											
										?>
											<?php $__currentLoopData = $packageDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php
											$packageClass="";
											if(!empty($data['title']) && $data['title'] == Session::get('new_seller_package_name')){
												$packageClass = Session::get('new_seller_package_name');
											}
											?>
											<div class="col-md-offset-1 col-md-4 packages-section">
												<div package_name="<?php echo e($data['title']); ?>">
													<div class="packages-heading hand-icon packages-subscribe <?php echo e($packageClass); ?>" onclick='subscribe_package("<?php echo e($i); ?>",this)'>
														<h3><?php echo e($data['title']); ?></h3>
														<div class="packages-price"><?php echo e($data['amount']); ?> kr/<?php echo e($data['validity_days']); ?> Days</div>
													</div>
													<div class="packages-body">
														<div class="packages-description">
														 <?php echo $data->description; ?></p>
														</div>
														<form  action="" class="needs-validation text-right" novalidate="" id="klarna_form">
															<?php echo e(csrf_field()); ?>

															<input type="hidden" name="user_id" value="" id="user_id" class="user_id">
															<input type="hidden" name="p_id" value="<?php echo e($data['id']); ?>" id="p_id_<?php echo e($i); ?>" class="p_id">
															<input type="hidden" name="p_name" value="<?php echo e($data['title']); ?>" id="p_name_<?php echo e($i); ?>" class="p_name">
															<input type="hidden" name="validity_days" value="<?php echo e($data['validity_days']); ?>" id="validity_days_<?php echo e($i); ?>" class="validity_days">
															<input type="hidden" name="amount" value="<?php echo e($data['amount']); ?>" id="amount_<?php echo e($i); ?>" class="amount">     
														
														</form>
													</div>
												</div>
											</div>
											<?php $i++; ?>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									   </div>
                                       <?php else: ?>
                                        <div><?php echo e(__('users.noPackagesFound')); ?></div>  
                                       <?php endif; ?>

								<!-- 	<div id="html_snippet" class="klarna_html"></div>   -->
								</div> 
									
								<!-- <div class="pull-right register_second_btn">	 -->
								<input type="button" name="previous" class="previous btn gray_color action-button-previous package-previous second-step-previous" value="<?php echo e(__('users.prev_step_btn')); ?>" /> 
								<input type="button" name="next" class="next btn debg_color action-button 3 package-html" value="<?php echo e(__('users.next_step_btn')); ?>" id="second-step" />
								<!-- </div> -->
							</fieldset> 
										
								   
							<fieldset class="seller_register_third">
								<div class="form-card"> 
									<div class="pull-right payment_info">
              						<div class="info payment_info_card"><?php echo e(__('users.payment_method_info')); ?></div>
          							</div>
									<form method="POST" action="<?php echo e(route('frontThirdStepSellerRegister')); ?>" class="needs-validation" novalidate="" id="third-step-form">
										                 
										<!-- <input type="text" name="fname" id="fname" class="form-control" value="<?php echo e(old('fname')); ?>" placeholder="<?php echo e(__('users.first_name_label')); ?> *">
										<span class="invalid-feedback" id="err_fname" style="margin-top: -28px;margin-left: 10px;"></span>

										<input type="text" name="lname" id="lname" class="form-control" value="<?php echo e(old('lname')); ?>"  placeholder="<?php echo e(__('users.last_name_label')); ?> *">
										<span class="invalid-feedback" id="err_lname" style="margin-top: -27px;margin-left: 10px;"></span>
										<textarea  id="address" class="form-control" name="address" placeholder="<?php echo e(__('users.address_label')); ?> " rows="5" cols="30"  tabindex="5"></textarea>
										<input type="text" name="postcode" id="postcode" class="form-control" placeholder="<?php echo e(__('users.postal_code_label')); ?>" value=""> -->

										<p class="payment_method_title"><?php echo e(__('users.klarna_pament_label')); ?></p>
							            <div class="login_box payment_detail_box klarna_payment" style="margin-top: 20px;">
							                <div class="payment-lock-icon lock_klarna"><i class="fa fa-lock payment_lock klarna_payment_lock" aria-hidden="true"></i></div>

							              <p><img src="<?php echo e(url('/')); ?>/uploads/Images/klarna-payment-logo.png" class="register_klarna_logo"></p>
							              <div class="form-group">
								              <input type="text" class="form-control" name="klarna_username" id="klarna_username" placeholder="<?php echo e(__('users.klarna_username_label')); ?>" value="<?php echo e(Session::get('new_seller_klarna_username')); ?>">
								              <span class="register_credentials_ex">T.ex. K1234567_dc0a9aclc532</span>
								              <span class="invalid-feedback" style="position: relative;"><?php if($errors->has('klarna_username')): ?> <?php echo e($errors->first('klarna_username')); ?><?php endif; ?></span>
							              </div>
							    
							              <div class="form-group">
								              <input type="password" class="form-control" name="klarna_password" id="klarna_password" placeholder="<?php echo e(__('users.klarna_password_label')); ?>" value="<?php echo e(Session::get('new_seller_klarna_password')); ?>">
								              <span  class="register_credentials_ex">T.ex. abcDEF123ghij567</span>
								              <span class="invalid-feedback"><?php if($errors->has('klarna_password')): ?> <?php echo e($errors->first('klarna_password')); ?><?php endif; ?></span>
							              </div>

											<div class="payment_explanation_text"><?php echo e(__('messages.klarna_description_step_1')); ?> <a target="_blank" href="https://auth.eu.portal.klarna.com/auth/realms/merchants/protocol/openid-connect/auth?client_id=merchant-portal&redirect_uri=https%3A%2F%2Fportal.klarna.com%2F%3F_ga%3D2.246934660.126610679.1646642154-1734669118.1646642154&state=a4c4cce6-9787-49a3-9131-ca7362164c5c&response_mode=fragment&response_type=code&scope=openid&nonce=f23358b1-1fb6-4f06-87e3-3ca8281be740&code_challenge=HCL30L02B40414ZxJeU5kOw8XqhetiyzuAgBZeqnaX0&code_challenge_method=S256">Här</a></br><?php echo e(__('messages.klarna_description_step_2')); ?></br><?php echo e(__('messages.klarna_description_step_3')); ?></br><?php echo e(__('messages.klarna_description_step_4')); ?></br></br>
											<?php echo e(__('messages.contact_klarna_support_description')); ?>

											</div>
							            </div>
							            <p class="payment_method_title" style="margin-top: 20px;"><?php echo e(__('users.easy_peyment_title')); ?></p>
							            <div class="login_box payment_detail_box swish_payment" style="margin-top: 20px;">
                
							                <div class="payment-lock-icon lock_swish_number"><i class="fa fa-lock payment_lock swish_payment_lock" aria-hidden="true"></i></div>

							              <!--  <div class="payment-lock-icon unlock_swish_number"><input type="checkbox"name="is_swish_number" id="is_swish_number" class=" payment_lock is_swish_number" value=""></div> -->
							            <p><img src="<?php echo e(url('/')); ?>/uploads/Images/swish-payment-logo.png" class="register_swish_logo"></p>
							              <?php /*
							              <div class="form-group">
								              <input type="text" class="form-control" name="swish_api_key" id="swish_api_key" placeholder="{{ __('users.swish_api_key_label')}}" value="{{ Session::get('new_seller_swish_api_key')}}">
								              <span class="invalid-feedback" style="position: relative;">@if($errors->has('swish_api_key')) {{ $errors->first('swish_api_key') }}@endif</span>
							              </div>

							              <div class="form-group">
								              <input type="text" class="form-control" name="swish_merchant_account" id="swish_merchant_account" placeholder="{{ __('users.swish_merchant_account_label')}}" value="{{ Session::get('new_seller_swish_merchant_account')}}">
								              <span class="invalid-feedback">@if($errors->has('swish_merchant_account')) {{ $errors->first('swish_merchant_account') }}@endif</span>
							              </div>

							              <div class="form-group">
								              <input type="text" class="form-control" name="swish_client_key" id="swish_client_key" placeholder="{{ __('users.swish_client_key_label')}}" value="{{ Session::get('new_seller_swish_client_key')}}">
								              <span class="invalid-feedback">@if($errors->has('swish_client_key')) {{ $errors->first('swish_client_key') }}@endif</span>
							              </div>
							              */?>
							              <div class="form-group" style="display: flex;">
							            <!--    <input type="checkbox" name="is_swish_number" class="is_swish_number" id="is_swish_number" value="1" style="margin-top: 30px;">  -->
							               <input type="phone_number" class="form-control login_input" name="swish_number" id="swish_number" placeholder="Swish-nummer" value="<?php echo e(Session::get('new_seller_swish_number')); ?>">
							              </div>

							              <div class="payment_explanation_text">
							              	<!-- <?php echo e(__('messages.swish_number_step_description')); ?>	
							              	 -->
							              	 <div class="payment_explanation_text"> <?php echo e(__('messages.swish_description_step_1')); ?></br> <?php echo e(__('messages.swish_description_step_2')); ?></div> 
							            </div>
							        </div>
							            <p class="payment_method_title" style="margin-top: 20px;"><?php echo e(__('users.stripe_pament_label')); ?></p>
							            <div class="login_box payment_detail_box stripe_payment" style="margin-top: 20px;">
							               <!--  <div class="payment-lock-icon"><i class="fa fa-lock payment_lock stripe_payment_lock" aria-hidden="true"></i></div> -->


							                <div class="payment-lock-icon lock_stripe_payment"><i class="fa fa-lock payment_lock stripe_payment_lock" aria-hidden="true"></i></div>

							         <!--       <div class="payment-lock-icon unlock_stripe_payment"><input type="checkbox"name="is_stripe_payment" id="is_stripe_payment" class="payment_lock is_stripe_payment" value=""></div> -->

							              <p><img src="<?php echo e(url('/')); ?>/uploads/Images/stripe-payment-logo.png" class="register_stripe_logo"></p>
							              <div class="form-group">
								              <input type="text" class="form-control" name="strip_api_key" id="strip_api_key" placeholder="<?php echo e(__('users.stripe_api_key_label')); ?>" value="<?php echo e(Session::get('new_seller_strip_api_key')); ?>">
								              <span class="register_credentials_ex">Hemlig nyckel</span>								               
								              <span class="invalid-feedback" style="position: relative;"><?php if($errors->has('strip_api_key')): ?> <?php echo e($errors->first('strip_api_key')); ?><?php endif; ?></span>
							              </div>

							              <div class="form-group">
								              <input type="text" class="form-control" name="strip_secret" id="strip_secret" placeholder="<?php echo e(__('users.stripe_secret_label')); ?>" value="<?php echo e(Session::get('new_seller_strip_secret')); ?>">
								               <span class="register_credentials_ex">Publicerbar nyckel</span>
								              <span class="invalid-feedback"><?php if($errors->has('strip_secret')): ?> <?php echo e($errors->first('strip_secret')); ?><?php endif; ?></span>
							              </div>

							              <div class="payment_explanation_text"><?php echo e(__('messages.strip_description_step_1')); ?> <a target="_blank" href=" https://dashboard.stripe.com/login">Här</a></br><?php echo e(__('messages.strip_description_step_2')); ?></br><?php echo e(__('messages.strip_description_step_3')); ?></br></br><?php echo e(__('messages.register_stripe_description')); ?></div>  
							             
							            </div>
							             
									</form>                          
								</div> 
								<input type="button" name="next" class="next btn debg_color action-button 4" value="<?php echo e(__('users.next_step_btn')); ?>" id="third-step"/>	
								<input type="button" name="previous" class="previous btn gray_color action-button-previous step-third-previous" value="<?php echo e(__('users.prev_step_btn')); ?>" /> 								 
								
							</fieldset>

							<fieldset class="seller_register_fourth">
								<div class="form-card">
									<form id="seller-personal-form" action="<?php echo e(route('frontSellerPersonalPage')); ?>" method="post"  enctype="multipart/form-data" id="seller_personal_info">
										<?php echo csrf_field(); ?>
										<div class="form-group" style="display: flex;">
                                            <!-- <label><?php echo e(__('users.store_name_label')); ?><span class="de_col">*</span></label> -->
                                            
                                            <input type="hidden" class="form-control login_input" name="verify_btn_click" id="verify_btn_click" value="">
											<input type="text" class="form-control login_input" name="store_name" id="store_name" placeholder="<?php echo e(__('users.store_name_label')); ?> *">
											<input type="button" name="check-store-unique" class="btn debg_color register_store_verify"onclick="checkStoreName()" value="<?php echo e(__('users.verify_btn')); ?>" /> 

										</div> <span class="invalid-feedback" id="err_store_name"></span>

										<div class="form-group">
                                            <label class="placeholder"><?php echo e(__('users.city_label')); ?></label>                      
											<input type="text" class="form-control login_input" name="city_name" id="city_name" placeholder="<?php echo e(__('users.city_label')); ?> *">
										</div> 
										<span class="invalid-feedback" id="err_city_name"></span>

										<div class="form-group">
                                            <label class="placeholder"><?php echo e(__('users.country_label')); ?></label>
                                         	<input type="text" class="form-control login_input" name="country_name" id="country_name" placeholder="<?php echo e(__('users.country_label')); ?> *">
										</div> 
										<span class="invalid-feedback" id="err_country_name"></span>

										<div class="form-group increment cloned">
											<label class="placeholder"><?php echo e(__('users.seller_header_img_label')); ?></label>
											<div class="seller_banner_upload" style="margin-top: 20px;"></div>
											
                                            
											<input type="file" name="header_img" id="seller_banner_img" class="form-control seller_banner_img" value="">
											
											<span class="invalid-feedback" id="err_seller_banner_img"></span>
											<div class="input-group-btn text-right"> 
											</div>
                                            
										</div>

										<div class="form-group increment cloned">
											<label class="placeholder"><?php echo e(__('users.seller_logo_label')); ?></label>
											<div class="seller_logo_upload" style="margin-top: 20px;"></div>
											
											<input type="file" name="logo" id="seller_logo_img" class="form-control" value="">
											
											<span class="invalid-feedback" id="err_seller_logo_img"></span>	
											<div class="input-group-btn text-right"> 
											</div>
									   </div>
										<div class="remember-section row">
											<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value=""><span class="remember-text"><?php echo e(__('users.read_and_approve_chk')); ?><a href="<?php echo e(url('/')); ?>/page/villkor" class="product_table_heading " style=" font-size: 12px !important;">&nbsp;<?php echo e(__('users.terms_of_use')); ?> </a> <a href="<?php echo e(url('/')); ?>/page/villkor" class="product_table_heading " style=" font-size: 12px !important;"><?php echo e(__('users.privacy_policy')); ?></a> <?php echo e(__('users.and_chk')); ?> <a href="<?php echo e(url('/')); ?>/page/villkor" class="product_table_heading " style=" font-size: 12px !important;"><?php echo e(__('users.store_terms')); ?></a></span>	
										</div>
									</form>
									</div>
									<!-- <div class="pull-right" style="display: flex;"> -->
									<input type="submit" name="next" class="next btn debg_color action-button 5" value="<?php echo e(__('users.finish_btn')); ?>" id="last-step"/>
									<input type="button" name="previous" class="previous btn gray_color action-button-previous forth-step-previous" value="<?php echo e(__('users.prev_step_btn')); ?>" /> 
									
							<!-- 	</div> -->
								
							</fieldset>
							
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	/*window.onbeforeunload = function() {
		return "Dude, are you sure you want to leave? Think of the kittens!";
	}
	function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };

	$(document).on("keydown", disableF5);*/

	var oops_heading        = "<?php echo e(__('users.oops_heading')); ?>";
    var success_heading     = "<?php echo e(__('users.success_heading')); ?>";
    var fill_in_city_err    = "<?php echo e(__('errors.fill_in_city_err')); ?>";
    var fill_in_country_err = "<?php echo e(__('errors.fill_in_country_err')); ?>";
    var invalid_files_err="<?php echo e(__('errors.invalid_files_err')); ?>";
    var store_name_characters_len_err ="<?php echo e(__('errors.store_name_characters_len_err')); ?>";
	/* second step */
    function subscribe_package(i,val){
    	$(".packages-subscribe").each(function() {
       $(this).removeClass("selectedActivePackage");
     });
    	/*var package_name = $($(val)).attr("package_name");
    	alert($($(val)).attr("package_name"))
    	if()*/
//if($($(val)).parent('div').hasClass('.active')){
	//$($(val)).parent('div').removeClass("active");

	
	//packages-subscribe
	//$(".packages-subscribe").removeClass("active");
//}

    $($(val)).addClass("selectedActivePackage");
    //let user_id  = $("#user_id").val();
    let p_id     = $("#p_id_"+i).val();
    let p_name  = $("#p_name_"+i).val();
    let validity_days = $("#validity_days_"+i).val(); 
    let amount = $("#amount_"+i).val();
	$('#selected_package_name').val(p_name);

	if(p_name == 'Tijara Bas'){
			$('.klarna_payment :input').attr('disabled', true);
			$('.stripe_payment :input').attr('disabled', true);
			//$(".swish_payment_lock").hide();
			$('.lock_klarna').show();
			//$('.unlock_klarna').hide();
			$('.lock_swish_number').hide();
			//$('.unlock_swish_number').show();
			$('.lock_stripe_payment').show();
			//$('.unlock_stripe_payment').hide();
			//$(".swish_payment_lock").removeClass("fa-lock");
			//$(".klarna_payment_lock").addClass("fa-lock");
			//$(".stripe_payment_lock").addClass("fa-lock");
			$('#klarna_username').val('');
			$('#klarna_password').val('');
			$('#strip_api_key').val('');
			$('#strip_secret').val('');
	}

	if(p_name=="Tijara pro"){
		$('.klarna_payment :input').attr('disabled', false);
		$('.stripe_payment :input').attr('disabled', false);
		$('.lock_klarna').hide();
		//$('.unlock_klarna').show();
		$('.lock_swish_number').hide();
		//$('.unlock_swish_number').show();
		$('.lock_stripe_payment').hide();
		//$('.unlock_stripe_payment').show();
	    //$(".klarna_payment_lock").removeClass("fa-lock");
	   // $(".swish_payment_lock").removeClass("fa-lock");
	    //$(".stripe_payment_lock").removeClass("fa-lock");
  	}
        let err = 0;
        if(p_id == ''){

        }
        else if(p_name=='')
        {
            showErrorMessage("something wrong try again");
            err = 1;
        }
        else if(validity_days==''){
            showErrorMessage("something wrong try again");
            err = 1;
        }
        else if(amount == '')
        {
            showErrorMessage("something wrong try again");
            err = 1;
        }else{
            err=0;
        }

        if(err == 0){
   
             $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "<?php echo e(url('/')); ?>"+'/seller-register-second-step',
                type: 'post',
                async: false,
                data:{amount:amount, validity_days:validity_days, p_id:p_id,p_name:p_name},
                success: function(data){

                       $(".loader").hide();
                    if(data.success=="second step success"){
                        console.log(data.success);    
                        console.log(data.package_name);
                        $('#selected_package_name').val(data.package_name);
                       // console.log("second step complete");  
                       var selected_package_name =$('#selected_package_name').val();
						/*if(selected_package_name == "Tijara Bas"){
							
							$('.klarna_payment :input').attr('disabled', true);
							$('.stripe_payment :input').attr('disabled', true);
							$(".swish_payment_lock").removeClass("fa-lock");
						}else{
						    $(".klarna_payment_lock").removeClass("fa-lock");
						    $(".swish_payment_lock").removeClass("fa-lock");
						    $(".stripe_payment_lock").removeClass("fa-lock");
					  	}*/
                        err = 0;
                        //$(".klarna_html").html(data.html_snippet).show();
                       //$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                    }else{
                        showErrorMessage(data.error_msg);
                        err=1;
                    }
                }
            });

            ////$('#klarna_form').submit();
            if(err==0){
            	var current_fs, next_fs, previous_fs; //fieldsets
	            var opacity;

	            $(".next").click(function(){
	            current_fs = $(this).parent();
	            next_fs = $(this).parent().next();

	            //Add Class Active
	            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

	            //show the next fieldset
	            next_fs.show();
	            //hide the current fieldset with style
	            current_fs.animate({opacity: 0}, {
	            step: function(now) {
	            // for making fielset appear animation
	            opacity = 0.6 - now;

	            current_fs.css({
	            'display': 'none',
	            'position': 'relative'
	            });
	            next_fs.css({'opacity': opacity});
	            },
	            duration: 600
	            });
	            });
            }
            
        }
    }

$(document).ready(function(){
	/*$( ".packages-subscribe" ).hasClass(session_package_name)
	var session_package_name =$('#session_package_name').val();
	if(session_package_name !=''){
		alert(session_package_name)
	}
*/
$(".Bas").addClass("selectedActivePackage");
$(".pro").addClass("selectedActivePackage");
 var session_package_name =$('#session_package_name').val();
	if(session_package_name == 'Tijara Bas'){
			$('.klarna_payment :input').attr('disabled', true);
			$('.stripe_payment :input').attr('disabled', true);
			//$(".swish_payment_lock").removeClass("fa-lock");
			$('.lock_klarna').show();
			//$('.unlock_klarna').hide();
			$('.lock_swish_number').hide();
			//$('.unlock_swish_number').show();
			$('.lock_stripe_payment').show();
			//$('.unlock_stripe_payment').hide();
			//$(".klarna_payment_lock").addClass("fa-lock");
			//$(".stripe_payment_lock").addClass("fa-lock");
			$('#klarna_username').val('');
			$('#klarna_password').val('');
			$('#strip_api_key').val('');
			$('#strip_secret').val('');
	}

	if(session_package_name=="Tijara pro"){
		$('.klarna_payment :input').attr('disabled', false);
		$('.stripe_payment :input').attr('disabled', false);
		$('.lock_klarna').hide();
		//$('.unlock_klarna').show();
		$('.lock_swish_number').hide();
		//$('.unlock_swish_number').show();
		$('.lock_stripe_payment').hide();
		//$('.unlock_stripe_payment').show();
	   // $(".klarna_payment_lock").removeClass("fa-lock");
	   // $(".swish_payment_lock").removeClass("fa-lock");
	    //$(".stripe_payment_lock").removeClass("fa-lock");
  	}

/*
if($('#current_step_button').val() != 1){
	setTimeout(function(){
		var curr_step=  $('input#current_step_button').val();
		$( "fieldset" ).each(function() {
		  $( this ).css({
					'display': 'none',
					'position': 'relative'
					});
		});
	   

		current_fs = $('input.'+curr_step).parent();
		next_fs = $('input.'+curr_step).parent().next();

		//Add Class Active
		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	 
		if(curr_step==3){
			 $("#progressbar li").eq($("fieldset").index(current_fs)).addClass("active");
		}
		if(curr_step==4){

			 current_fs_prev = $('input.'+curr_step).parent().prev();
			$("#progressbar li").eq($("fieldset").index(current_fs_prev)).addClass("active");
		}
		//show the next fieldset
		next_fs.show();
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
		step: function(now) {
		// for making fielset appear animation
		opacity = 0.6 - now;

		current_fs.css({
		'display': 'none',
		'position': 'relative'
		});
		next_fs.css({'opacity': opacity});
		},
		duration: 600
		});
	}, 1000);
}
*/
/*function to save first form data and validate it before save*/
    $('#first-step').click(function(e) {  

        e.preventDefault();
    
        let email     = $("#email").val();
        let password  = $("#password").val();
        let password_confirmation = $("#password_confirmation").val();
        let role_id = $("#role_id").val();
        let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        let error = 0;

        if(email == '')
        {
            $("#err_email").html(fill_in_email_err).show();
            $("#err_email").parent().addClass('jt-error');
            error = 1;
        }
        else if(!email_pattern.test(email))
        {
            $("#err_email").html(valid_email_err).show();
            $("#err_email").parent().addClass('jt-error');
            error = 1;
        }
        else
        {
            $("#err_email").parent().removeClass('jt-error');
            $("#err_email").html('');
        }

        if(password == '')
        {
            $("#err_password").html(fill_in_password_err).show();
            $("#err_password").parent().addClass('jt-error');
        error = 1;
        }
        else if((password).length<6)
        {
            $("#err_password").html(password_min_6_char).show();
            $("#err_password").parent().addClass('jt-error');
            error = 1;
        }
        else
        {
            $("#err_password").parent().removeClass('jt-error');
            $("#err_password").html('');
        }
        if(password!=password_confirmation) {
            $("#err_cpassword").html(password_not_matched).show();
            $("#err_cpassword").parent().addClass('jt-error');
            error = 1;
        }
        else
        {
            $("#err_cpassword").html('');
        }
        if(error == 1)
        {
            return false;
        }
        else
        {
            $(".loader-seller").css("display","block");
            //save form data on validation success
            $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "<?php echo e(url('/')); ?>"+'/new-seller-register',
                type: 'post',
                async: false,
                data:{email:email, password:password, password_confirmation:password_confirmation,role_id:role_id},
                success: function(data){
                    $(".loader-seller").css("display","none");
                    if(data.success=="Got Simple Ajax Request"){
                        console.log(data.success);
                        console.log("first step complete");    
						createCookie("tijara_register_current_step", 'seller_register_second', 20);
                    }else{
                        showErrorMessage(data.error_msg.email);
                        error=1;
                    }
                }
            });
        }

        //show next step
        if(error==0){
            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();

            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
            step: function(now) {
            	//console.log("next-->"+now)
                // for making fielset appear animation
                opacity = 0.5 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                    next_fs.css({'opacity': opacity});
                },
                duration: 600
            }); 
        } 
});

/*$('.second-step-previous').click(function(e) { 
	$('.seller_register_first').show();
}); 

$('.step-third-previous').click(function(e) { 
	$('.seller_register_second').show();
}); 

$('.forth-step-previous').click(function(e) { 
	    e.preventDefault();
	$('.seller_register_third').show();
}); */
   // var session_package_name =$('#session_package_name').val();
/*second step*/

$('#second-step').click(function(e) { 
    var session_package_name =$('#session_package_name').val();
     var selected_package_name =$('#selected_package_name').val();
    //alert(selected_package_name)
    
    var err = 0
    if(session_package_name ==''){
    	if(selected_package_name==''){
	        showErrorMessage(select_package_to_subscribe);
	        err = 1;
    	}
    }
   
    
        if(err == 0){ 
             //show next step
            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
			createCookie("tijara_register_current_step", 'seller_register_third', 20);
            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();

            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 0.6 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                    next_fs.css({'opacity': opacity});
                },
                duration: 600
            }); 
	        
        }else{ 
            return false
        }
});

/*third step*/
$('#third-step').click(function(e) {  
     
    e.preventDefault();

    let klarna_username      	= $("#klarna_username").val();
    let klarna_password  	 	= $("#klarna_password").val();  
    
    /*let swish_api_key        	= $("#swish_api_key").val();
    let swish_merchant_account  = $("#swish_merchant_account").val(); 
    let swish_client_key        = $("#swish_client_key").val();*/

   // let is_swish_number  		= $("#is_swish_number").val(); 
    let swish_number            = $("#swish_number").val();

    let strip_api_key           = $("#strip_api_key").val(); 
    let strip_secret  			= $("#strip_secret").val();   
    var selected_package_name   = $('#selected_package_name').val(); 
	

    let third_step_err = 0;

	if ((klarna_username != '' && klarna_password != '') || (swish_number != '') || (strip_api_key != '' && strip_secret != '')){
		 third_step_err = 0;
	 	
    }else
    {
       third_step_err = 1;
       showErrorMessage(please_add_payment_details);
    }
    	
	//alert(third_step_err);return
	if(third_step_err == 0){
	
	    $(".loader-seller").css("display","block");
	    $.ajax({
	                headers: {
	                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
	                         },
	                url: "<?php echo e(url('/')); ?>"+'/third-step-seller-register',
	                type: 'post',
	               
	                data:{klarna_username:klarna_username, klarna_password:klarna_password,strip_api_key:strip_api_key,strip_secret:strip_secret,swish_number:swish_number},
	                success: function(data){
	                    if(data.success=="third step success"){
	                        $(".loader-seller").css("display","none");
							createCookie("tijara_register_current_step", 'seller_register_fourth', 20);
	                        console.log(data.success);
	                        console.log("third step complete"); 
	                        third_step_err = 0;  
							//show next step
							var current_fs, next_fs, previous_fs; //fieldsets
							var opacity;
							current_fs = $('#third-step').parent();
							next_fs = $('#third-step').parent().next();

							//Add Class Active
							$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

							//show the next fieldset
							next_fs.show();

							//hide the current fieldset with style
							current_fs.animate({opacity: 0}, {
							step: function(now) {
								// for making fielset appear animation
								opacity = 0.6 - now;

								current_fs.css({
									'display': 'none',
									'position': 'relative'
								});
									next_fs.css({'opacity': opacity});
								},
								duration: 600
							});
	                    }else{
	                       $(".loader-seller").css("display","none");
	                        showErrorMessage(data.error);
							setTimeout(function(){
							$('.seller_register_third .form-card').attr('style','display: block;position: relative;opacity: 1;');
							
							return false;
							}, 1000);
	                    }
	                }
	            });

	    
	  }
	else  { 
	
	setTimeout(function(){
		$('#progressbar .confirm').removeClass('active');
		$('.seller_register_third .form-card').attr('style','display: block;position: relative;opacity: 1;');
		
	 	return false;
		}, 1500);
    }

    

});

/*last step*/
$('#last-step').click(function(e) {  

    e.preventDefault();
    let store_name       = $("#store_name").val();
    let verify_btn_click = $("#verify_btn_click").val();
    var city_name        =  $("#city_name").val();
    var country_name     = $("#country_name").val();
    var maxLength = 21;
    let last_step_err = 0;
 	var is_verfied = $("#verify_btn_click").val();
 	if(is_verfied!=1){
	 	if(store_name!=''){
	        $.ajax({
	          url: "<?php echo e(url('/')); ?>"+'/admin/seller/checkstore/?store_name='+store_name,
	          type: 'get',
	          data: { },
	          success: function(output){
	            if(output !=''){
	             showErrorMessage(output);
	             last_step_err=1;
	            }else{
	                showSuccessMessage(store_name_is_verified);
	                $("#verify_btn_click").val("1");
	            }
	            }
	        });
	    }else{
	     showErrorMessage(please_enter_store_name);
	     last_step_err=1;
	    }
 	}
    
    if(store_name==''){
        $("#err_store_name").html(please_enter_store_name).show();
       // $("#err_store_name").parent().addClass('jt-error');
         last_step_err = 1; 
      // showErrorMessage(please_enter_store_name)
    } else if(store_name.length>=maxLength){
		$("#err_store_name").html(store_name_characters_len_err).show();
		last_step_err = 1; 
    } else  {
           // $("#err_store_name").parent().removeClass('jt-error');
            $("#err_store_name").html('');
    }

    if(city_name==''){
        $("#err_city_name").html(fill_in_city_err).show();
         last_step_err = 1; 
    }  else  {
            $("#err_city_name").html('');
    }

    if(country_name==''){
        $("#err_country_name").html(fill_in_country_err).show();
         last_step_err = 1; 
    }  else  {
            $("#err_country_name").html('');
    }

    if(verify_btn_click==''){
      //showErrorMessage(verify_store)
       last_step_err = 1;
    }
    else if(!$("#chk_privacy_policy").is(':checked')){
       showErrorMessage(please_check_privacy_policy);
       	last_step_err = 1; 
         
    } else{
    	if(last_step_err !=1){
    		last_step_err = 0;
    	}else{
    		last_step_err = 1;
    	}
    }
    /*else {
        showErrorMessage(please_check_privacy_policy);
        last_step_err = 1;

    }*/
	//alert(last_step_err)
    
   if(last_step_err == 1)
    {
    	
		setTimeout(function(){
		$('.seller_register_fourth').attr('style','display: block;position: relative;');
		
	 	return false;
		}, 900);
    }else{
    	 let logo_image   = $("#logo_image").val();
	    let banner_image = $("#banner_image").val();
	    let store_name   = $("#store_name").val();
	    let chk_privacy_policy   = $("#chk_privacy_policy").val();

	            $(".loader-seller").css("display","block");
	            $.ajax({
	                headers: {
	                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
	                         },
	                url: "<?php echo e(url('/')); ?>"+'/seller-info-page',
	                type: 'post',
	        
	                data:{banner_image:banner_image,logo_image:logo_image,store_name:store_name,city_name:city_name,country_name:country_name},
	            
	                success: function(data){
	                    $(".loader-seller").css("display","none");
	                    if(data.success=="last step success"){
	                        console.log(data.success);
	                        console.log("last step success"); 
	                        window.location = "<?php echo e(route('frontRegisterSuccess')); ?>";        

	                    }
	                }
	            });
    }  
});

function createCookie(name,value,minutes) {
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime()+(minutes*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else {
        var expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}
if($('#current_step').val()!='') {
	setTimeout(function(){
		$( "fieldset" ).each(function() {
		  $( this ).css({
					'display': 'none',
					'position': 'relative'
					});
		});
		$("#progressbar li").each(function() {
		   $(this).removeClass("active");
		 });
		 if($('#current_step').val()=='seller_register_first') 
			 $("#account").addClass('active');
		 if($('#current_step').val()=='seller_register_second')  {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
		 }
		 if($('#current_step').val()=='seller_register_third') {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
			 $("#payment").addClass('active');
		 }
		 if($('#current_step').val()=='seller_register_fourth') {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
			 $("#payment").addClass('active');
			 $("#confirm").addClass('active');
		 }
		//if($('#current_step').val()=='seller_register_fourth') 
		{
			$('.'+$('#current_step').val()).attr('style','display: block;position: relative;opacity: 0.6;');
			$('.'+$('#current_step').val()+' .form-card').attr('style','display: block;position: relative;opacity: 1;');
		}
	}, 1000);
}
/*function to upload seller banner image*/
$('body').on('change', '#seller_banner_img', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();

    if (validExtensions.indexOf(file) == -1) {
          showErrorMessage(invalid_files_err);
          $(this).val('');
          return false;

    }else{

        var formData = new FormData();
        if (fileUpload.files.length > 0) {

               formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

                $.ajax({
                    headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                      url: siteUrl+'/upload-seller-banner-image',
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,

                      success: function(data) {
                       $('.seller_banner_upload').html('<input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="banner_image" id="banner_image">'+
                          '<img src="'+siteUrl+'/uploads/Seller/resized/'+data+'" style="width:200px;height:200px;">'+
                                            '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>'); 
                      }
                });
        }
    }
});

/*function to upload seller logo image*/
$('body').on('change', '#seller_logo_img', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();
  if (validExtensions.indexOf(file) == -1) {
          showErrorMessage(invalid_files_err);
          $(this).val('');
          return false;
}else{

    var formData = new FormData();

        if (fileUpload.files.length > 0) {

               formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

                $.ajax({
                    headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                      url: siteUrl+'/upload-seller-logo-image',
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,

                      success: function(data) {
                       $('.seller_logo_upload').html('<input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="logo_image" id="logo_image">'+
                          '<img src="'+siteUrl+'/uploads/Seller/resized/'+data+'" style="width:200px;height:200px;">'+
                                            '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>'); 
                                        
                      }

                });
        }
}
});


/*function for previous button click*/
$(".previous").click(function(){

	/*if($(this).hasClass('step-third-previous')) {
		current_fs = $(this).parent().parent();
		previous_fs = $(this).parent().parent().prev();
	}
	else {
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();
	}*/
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	//Remove class active
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

	//show the previous fieldset
	previous_fs.show();
	if($(this).hasClass('forth-step-previous')) {
		setTimeout(function(){
		previous_fs.find('.form-card').attr('style','display: block;position: relative;opacity: 1;');
		
		}, 1000);
	}
	$("#progressbar li").each(function() {
		   $(this).removeClass("active");
		 });
		 if(previous_fs.attr('class')=='seller_register_first') 
			 $("#account").addClass('active');
		 if(previous_fs.attr('class')=='seller_register_second')  {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
		 }
		 if(previous_fs.attr('class')=='seller_register_third') {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
			 $("#payment").addClass('active');
		 }
		 if(previous_fs.attr('class')=='seller_register_fourth') {
			 $("#account").addClass('active');
			 $("#personal").addClass('active');
			 $("#payment").addClass('active');
			 $("#confirm").addClass('active');
		 }
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
	step: function(now) {
	//console.log(now)
	// for making fielset appear animation
	opacity = 0.6 - now;

	current_fs.css({
	'display': 'none',
	'position': 'relative'
	});
	previous_fs.css({'opacity': opacity});
	
	},
	duration: 600
	});
});


$('.radio-group .radio').click(function(){
	$(this).parent().find('.radio').removeClass('selected');
	$(this).addClass('selected');
});

$(".submit").click(function(){
	//alert("ewjh")
	return false;
})

});


/*function to check unique store name
* @param  : store name
*/
function checkStoreName(){
  
    var store_name= $("#store_name").val();
    if(store_name!=''){
        $.ajax({
          url: "<?php echo e(url('/')); ?>"+'/admin/seller/checkstore/?store_name='+store_name,
          type: 'get',
          data: { },
          success: function(output){
            if(output !=''){
             showErrorMessage(output);
            }else{
            	$("#verify_btn_click").val('1');
                showSuccessMessage(store_name_is_verified);
            }
            }
        });
    }else{

     showErrorMessage(please_enter_store_name)
    }
}

$(document).ready(function(){
	if ( $("#progressbar li#confirm").hasClass("active") ) { 
		$("#progressbar li#payment").addClass("active");
	}
});



function showErrorMessage(strContent,redirect_url = '')
{
  $.alert({
      title: oops_heading,
      content: strContent,
      type: 'red',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-times-circle",
      buttons: {
        Ok: function () {
            if(redirect_url != '')
            {
              if(redirect_url == 'reload')
              {
                location.reload(true);
              }
              else
              {
                window.location.href = redirect_url;
              }
            }
        },
      }
    });
}


function showSuccessMessage(strContent,redirect_url = '')
{
    
  $.alert({
      title: 'Klart!',
      content: strContent,
      type: 'green',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-check-circle",
      buttons: {
        ok: function () {
          if(redirect_url != '')
          {
            if(redirect_url == 'reload')
            {
              location.reload(true);
            }
            else
            {
              window.location.href = redirect_url;
            }
          }
        },
      }
    });
}

 

</script>
</body>
</html><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/seller_register.blade.php ENDPATH**/ ?>
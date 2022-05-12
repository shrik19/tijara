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
  <style>
   .loader{
    position: absolute;
    top:0px;
    right:0px;
    width:100%;
    height:100%;
    background-color:#eceaea;
    background-image:url('<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif');
    background-size: 50px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40);
    display:none;
}


   .modal-title{ 
    margin-left: 10px;
    line-height: 1.8;
    color: #03989e;
    margin-bottom: 0;
    font-weight: 700;
    display: block;
  }

  .modal-header .close {
    padding: 1rem 1rem;
    margin-top: -36px;
    font-size: 30px;
}
</style>
   <!-- end custom css for custom chnages -->
  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/front/js/vendor/bootstrap.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-confirm.min.js"></script>

</head>
<body>


<div class="container-fluid">
	<!-- Example row of columns -->
	<div class="row">
		<div class="login_container">
			<div class="col-md-4 left-section" >
				<?php if(Request::segment(2)=='buyer'): ?>
					<div class="login_banner" style="background: #03989e;">
					<div class="login_banner-header">
						<h1>Sveriges första kulturella marknadsplats</h1>
					</div>
					<div class="login_banner-footer text-right">
          <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('/')); ?>/uploads/Images/<?php echo e($siteDetails->header_logo); ?>" class="tijara-login-logo"/></a>
					</div> 
					</div>
				<?php endif; ?>
				<?php if(Request::segment(2)=='seller'): ?>
					<div class="login_banner" style="background-image: url(<?php echo e(url('/')); ?>/uploads/Banner/resized/<?php echo e($banner->image); ?>);">
						<div class="login_banner-header">
							<h1>Sveriges första kulturella marknadsplats</h1>
						</div>
						<div class="login_banner-footer text-right">
							<h2>Säljare</h2>
							<a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('/')); ?>/uploads/Images/<?php echo e($siteDetails->header_logo); ?>" class="tijara-login-logo" /></a>
						</div> 
					</div>
				<?php endif; ?>
			</div>

			<div class="col-md-8 right-section">
				<div class="col-md-3"></div>
				<div class="col-md-6 form-section">
					<h2 class="de_col"><?php echo e(__('users.login_label')); ?></h2>

					<?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

					<form id="sign-in-form" action="<?php echo e(url('/')); ?>/validate-login" method="post">
						<?php echo csrf_field(); ?>

						<?php if(Request::segment(2)=='buyer'): ?>
						<input type="hidden" name="role_id" value="1">
						<?php endif; ?>

						<?php if(Request::segment(2)=='seller'): ?>
						<input type="hidden" name="role_id" value="2">
						<?php endif; ?>
						<div class="form-group">
							<label><?php echo e(__('users.email_label')); ?> <span class="de_col">*</span></label>
							<input id="email-address" type="text" value="<?php echo e($tijara_front_login); ?>" class="form-control login_input" name="email" placeholder="<?php echo e(__('users.email_label')); ?>">
							<span class="invalid-feedback" id="err_email" style="display: none;"></span>
						</div>

						<div class="form-group">
							<label><?php echo e(__('users.password_label')); ?></label>
							<input id="password" type="password" value="<?php echo e($tijara_front_password); ?>" class="form-control login_input" name="password" placeholder="<?php echo e(__('users.password_label')); ?>">
							<span class="invalid-feedback" id="err_password" style="display: none;"></span>
						</div>
						<div class="remember-section row">
							<div class="col-md-6 text-left">
								<a href="javascript:void(0);" data-toggle="modal" data-target="#jt-forgot-modal" class="input-group-text de_col"><?php echo e(__('users.lost_your_password_label')); ?></a>
							</div>
							<div class="col-md-6 text-right">
								<input type="checkbox" checked="checked" name="remember"> <?php echo e(__('users.remember_label')); ?>

							</div>
						</div>						
						<div class="button-section">
							<div class="tijara-login-button">
								<button  class="btn btn-black debg_color login_btn frontloginbtn" ><?php echo e(__('lang.login_btn')); ?></button>
							</div>
							<div class="tijara-signup-button">
								<a href="<?php if(Request::segment(2)=='buyer'): ?><?php echo e(route('buyer_register')); ?> <?php else: ?> <?php echo e(route('seller_register')); ?> <?php endif; ?>" title="<?php echo e(__('users.create_account_btn')); ?>" class="btn btn-black gray_color login_btn" ><span><?php echo e(__('users.create_account_btn')); ?></span> </a>
							</div>
						</div>					
					</form>
					
					<?php if(Request::segment(2)=='buyer'): ?>
						<div class="seller-link-section">
							<a href="<?php echo e(route('seller_register')); ?>" title="<?php echo e(__('users.register_as_seller')); ?>" class="" ><span><?php echo e(__('users.register_as_seller')); ?></span> </a><br>
							<a href="<?php echo e(route('frontLoginSeller')); ?>" title="<?php echo e(__('users.login_as_seller')); ?>" class="" ><span><?php echo e(__('users.login_as_seller')); ?></span> </a>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</div>
</div> <!-- /container -->


<!-- forgot password Modal -->
<div class="modal fade" id="jt-forgot-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo e(__('lang.forgot_password_title')); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="container">
          <form class="jt-forgot-form" id="jt-forgot-form" action="<?php echo e(route('frontForgotPassword')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
              <label><?php echo e(__('users.email_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="form-control" id="forgot_email" name="forgot_email" placeholder="<?php echo e(__('users.email_label')); ?>" style="width: 47%;">
              <div class="invalid-feedback" id="err_forgot_email"><?php echo e(($errors->has('forgot_email')) ? $errors->first('forgot_email') : ''); ?></div>
            </div>
          </form>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="checkForgotForm();" class="btn btn-black debg_color login_btn"><?php echo e(__('lang.submit_btn')); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- End forgot password modal -->
<script type="text/javascript">
function checkForgotForm(event)
{
  
  let email       = $("#forgot_email").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error = 0;

  if(email == '')
  {
    $("#err_forgot_email").html("<?php echo e(__('errors.fill_in_email_err')); ?>").show();
    $("#err_forgot_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_forgot_email").html("<?php echo e(__('errors.valid_email_err')); ?>").show();
    $("#err_forgot_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_forgot_email").parent().removeClass('jt-error');
    $("#err_forgot_email").html('').hide();
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#jt-forgot-form').submit();
    return true;
  }
}
</script>
</body>
</html><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/login.blade.php ENDPATH**/ ?>
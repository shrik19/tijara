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
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/azcustom.css">
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
		<div class="register_container">
			<div class="col-md-4 left-section" >
				<div class="register_banner register_banner-1" style="background: #03989e;">
				<div class="register_banner-header">
					<h1>Sveriges första kulturella marknadsplats</h1>
				</div>
				<div class="register_banner-footer text-right">
					<img src="<?php echo e(url('/')); ?>/uploads/Images/<?php echo e($siteDetails->header_logo); ?>" class="tijara-login-logo"/>
				</div> 
				</div>
			</div>

			<div class="col-md-8 right-section">
				<div class="col-md-3"></div>
				<div class="col-md-6 form-section">
					<?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<h2 class="de_col"><?php echo e(__('users.create_account_btn')); ?></h2>

					<p><?php echo e(__('users.already_have_account')); ?>

					  <a href="<?php echo e(url('/')); ?>/front-login/buyer" class="de_col"><?php echo e(__('users.login_label')); ?></a>
					</p>					

					<form id="sign-up-form" action="<?php echo e(url('/')); ?>/do-register" method="post">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="role_id" value="<?php echo e($role_id); ?>">

						<div class="form-group">
						  <label><?php echo e(__('users.email_label')); ?> <span class="de_col">*</span></label>
						  <input type="email" class="form-control login_input" name="email" id="email" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__('users.email_label')); ?>">
						  <span class="invalid-feedback" id="err_email" style=""><?php if($errors->has('email')): ?> <?php echo e($errors->first('email')); ?><?php endif; ?></span>
						</div>

						<div class="form-group" style="margin-top:25px;">
						  <label><?php echo e(__('users.password_label')); ?><span class="de_col">*</span></label>
						  <input type="password" class="form-control login_input" name="password" id="password" value="<?php echo e(old('password')); ?>" placeholder="<?php echo e(__('users.password_label')); ?>">
						  <span class="invalid-feedback" id="err_password" style=""><?php if($errors->has('password')): ?> <?php echo e($errors->first('password')); ?><?php endif; ?></span>
						</div>

						<div class="form-group" style="margin-top:25px;">
						  <label><?php echo e(__('users.password_confirmation_label')); ?><span class="de_col">*</span></label>
						  <input type="password" class="form-control login_input" name="password_confirmation" value="<?php echo e(old('password_confirmation')); ?>" id="cpassword" placeholder="<?php echo e(__('users.password_confirmation_label')); ?>">
						  <span class="invalid-feedback" id="err_cpassword" style=""><?php if($errors->has('password_confirmation')): ?> <?php echo e($errors->first('password_confirmation')); ?><?php endif; ?></span>
						</div>

						<div class="remember-section row" style="margin-top:25px;">
							<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value="">
							<span class="remember-text"><?php echo e(__('users.read_and_approve_chk')); ?><a href="<?php echo e(url('/')); ?>/page/villkor">&nbsp;<?php echo e(__('users.terms_of_use')); ?> &nbsp;</a> <?php echo e(__('users.and_chk')); ?> <a href="<?php echo e(url('/')); ?>/page/villkor"><?php echo e(__('users.privacy_policy')); ?></a></span>		
						</div>
						
						<div class="button-section">
							<div class="tijara-register-button">
								<button class="btn btn-black login_btn gray_color frontregisterbtn"><?php echo e(__('users.create_account_btn')); ?></button>
							</div>
						</div>						
					  </form>
					
					<div class="seller-link-section">
						<a href="<?php echo e(route('seller_register')); ?>" title="<?php echo e(__('users.register_as_seller')); ?>" class="" ><span><?php echo e(__('users.register_as_seller')); ?></span> </a><br>
						<a href="<?php echo e(route('frontLoginSeller')); ?>" title="<?php echo e(__('users.login_as_seller')); ?>" class="" ><span><?php echo e(__('users.login_as_seller')); ?></span> </a>
					</div>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</div>
</div> <!-- /container -->
</body>
<script type="text/javascript">
var fill_in_email_err="<?php echo e(__('errors.fill_in_email_err')); ?>";
var fill_in_valid_email_err="<?php echo e(__('errors.invalid_email_err')); ?>";
var fill_in_password_err="<?php echo e(__('errors.fill_in_password_err')); ?>";
var fill_in_confirm_password_err="<?php echo e(__('errors.fill_in_confirm_password_err')); ?>";
var password_not_matched="<?php echo e(__('errors.password_not_matched')); ?>";
var password_min_6_char="<?php echo e(__('errors.password_min_6_char')); ?>";
var password_not_matched="<?php echo e(__('errors.password_not_matched')); ?>";
var please_check_privacy_policy = "<?php echo e(__('errors.please_check_privacy_policy')); ?>";
var oops_heading = "<?php echo e(__('users.oops_heading')); ?>";

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
      title: success_heading,
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

$(document).on('click','.frontregisterbtn',function(e) {

  e.preventDefault();
 // let fname   = $("#fname").val();
 // let lname   = $("#lname").val();

  let email     = $("#email").val();
  let password  = $("#password").val();
  let cpassword = $("#cpassword").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let chk_privacy_policy   = $("#chk_privacy_policy").val();
  let error = 0;

  /*if(fname == '')
  {
    $("#err_fname").html(fill_in_first_name_err).show();
    $("#err_fname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_fname").html('').show();

  }
  if(lname == '')
  {
    $("#err_lname").html(fill_in_last_name_err).show();
    $("#err_lname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_lname").html('');
  }*/

  if(email == '')
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_email").parent().removeClass('jt-error');
    $("#err_email").html('').hide();

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
    $("#err_password").html('').hide();
  }
  if(password!=cpassword) {
    $("#err_cpassword").html(password_not_matched).show();
      $("#err_cpassword").parent().addClass('jt-error');
     error = 1;
  }
  else
  {
    $("#err_cpassword").html('');
  }

  if($("#chk_privacy_policy").is(':checked')){
    // error = 0;
  } else {

      showErrorMessage(please_check_privacy_policy);
      error = 1;

  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#sign-up-form').submit();
    return true;
  }
});
</script>
</html><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/register.blade.php ENDPATH**/ ?>
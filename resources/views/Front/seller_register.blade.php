<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>{{$siteDetails->site_title}}</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <link rel="apple-touch-icon" href="{{url('/')}}/assets/front/apple-touch-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css" integrity="sha512-OdEXQYCOldjqUEsuMKsZRj93Ht23QRlhIb8E/X0sbwZhme8eUw6g8q7AdxGJKakcBbv7+/PX0Gc2btf7Ru8cZA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap.min.css">

  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">
  <!-- added custom css for custom chnages -->
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/custom.css">
  <style>
   .loader{
    position: absolute;
    top:0px;
    right:0px;
    width:100%;
    height:100%;
    background-color:#eceaea;
    background-image:url('{{url('/')}}/assets/front/img/ajax-loader.gif');
    background-size: 50px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40);
    display:none;
}

</style>
   <!-- end custom css for custom chnages -->
  <script src="{{url('/')}}/assets/front/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript">
    var siteUrl="{{url('/')}}";
    var fill_in_email_err="{{ __('errors.fill_in_email_err')}}";
    var fill_in_password_err="{{ __('errors.fill_in_password_err')}}";
    var valid_email_err = "{{ __('errors.valid_email_err')}}";
    var password_min_6_char="{{ __('errors.password_min_6_char')}}";
    var password_not_matched="{{ __('errors.password_not_matched')}}";
    var fill_in_first_name_err="{{ __('errors.fill_in_first_name_err')}}";
    var fill_in_last_name_err="{{ __('errors.fill_in_last_name_err')}}";
    var please_enter_store_name = "{{ __('errors.please_enter_store_name')}}";
    var store_name_is_verified = "{{ __('users.store_name_is_verified')}}";
    var select_package_to_subscribe = "{{ __('errors.select_package_to_subscribe')}}";
    var please_check_privacy_policy = "{{ __('errors.please_check_privacy_policy')}}";
    var verify_store = "{{ __('users.verify_store')}}";
</script>
  <script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/jquery-confirm.min.js"></script>

</head>
<body>

<!-- multistep seller registration wizard start here -->

<!-- MultiStep Form -->
<div class="container-fluid">
	<div class="row">
		<div class="seller_register_container">
			<div class="col-md-4 left-section">
                @if(!empty($banner->image))
				<div class="register_banner" style="background-image: url({{url('/')}}/uploads/Banner/{{$banner->image}});">
					<div class="register_banner-header">
						<h1>Sveriges första kulturella marknadsplats</h1>
					</div>
					<div class="register_banner-footer text-right">
						<h2>Säljare</h2>
						<img src="{{url('/')}}/uploads/Images/tijara-image.png" />
					</div> 
				</div>
                @endif
			</div>
			<div class="col-md-8 right-section">
				<div class="row mt-0">
					<div class="col-md-12">
						<h2 class="text-center"><strong>
						@if(!empty(Session::get('StepsHeadingTitle'))){{Session::get('StepsHeadingTitle')}} @else {{$headingTitle}} @endif
						</strong></h2>

						<div id="msform">
							<div class="loader-seller" style="display:none;"></div>
							<!-- progressbar -->
							<ul id="progressbar">
								<li class="active" id="account"><strong>{{ __('users.step_one_head')}}</strong></li>
								<li id="personal"><strong>{{ __('users.step_two_head')}}</strong></li>
								<li id="payment"><strong>{{ __('users.step_three_head')}}</strong></li>
								<li id="confirm"><strong>{{ __('users.step_four_head')}}</strong></li>
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
								
							 ?>
								  
							<input type="hidden" name="" id="current_step_button" value="{{$next_step}}">
							<!-- fieldsets -->
							<fieldset class="seller_register_first">
								<form id="sellerRegisterForm" action="{{route('frontNewSellerRegister')}}" method="post">
									@csrf
									<input type="hidden" name="role_id" id="role_id" value="{{$role_id}}">
									<!-- <label>{{ __('users.email_label')}}<span class="de_col">*</span></label> -->
									<input type="email" name="email" id="email" class="form-control" placeholder="{{ __('users.email_label')}} *" value="{{$email}}"/> 
									<span class="invalid-feedback" id="err_email"></span><br>

									<!-- <label>{{ __('users.password_label')}}<span class="de_col">*</span></label> -->
									<input type="password" name="password" id="password" class="form-control" placeholder="{{ __('users.password_label')}} *"  value="{{$password}}" style="margin-top:10px;" />
									<span class="invalid-feedback" id="err_password" style=""></span><br>

									<!-- <label>{{ __('users.password_confirmation_label')}}<span class="de_col">*</span></label> -->
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('users.password_confirmation_label')}} *" value="{{$cpassword}}" style="margin-top:10px;" />
									<span class="invalid-feedback" id="err_cpassword"></span>
								</form>
								<input type="button" name="next" class="next btn debg_color action-button 2" value="{{ __('users.next_step_btn')}}" id="first-step"  />
							</fieldset>
					  
						 
							<fieldset class="seller_register_second">
								<div class="form-card">
									@include ('Front.alert_messages')   
                                    @if(!empty($packageDetails))           
									  <div class="col-md-offset-2 col-md-10 package-html">
										<?php $i=1; ?>
											@foreach($packageDetails as $data)
											
											<div class="col-md-offset-1 col-md-4 packages-section">
												<div class="packages-subscribe">
													<div class="packages-heading">
														<h3>{{$data['title']}}</h3>
														<div class="packages-price">{{$data['amount']}} kr/{{$data['validity_days']}} Days</div>
													</div>
													<div class="packages-body">
														<div class="packages-description">
															<p>{{ __('users.description_label')}} : <?php echo $data->description; ?></p>
														</div>
														<form  action="" class="needs-validation text-right" novalidate="" id="klarna_form">
															{{ csrf_field() }}
															<input type="hidden" name="user_id" value="" id="user_id" class="user_id">
															<input type="hidden" name="p_id" value="{{$data['id']}}" id="p_id_{{$i}}" class="p_id">
															<input type="hidden" name="p_name" value="{{$data['title']}}" id="p_name_{{$i}}" class="p_name">
															<input type="hidden" name="validity_days" value="{{$data['validity_days']}}" id="validity_days_{{$i}}" class="validity_days">
															<input type="hidden" name="amount" value="{{$data['amount']}}" id="amount_{{$i}}" class="amount">     
															<button type="button" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn btnsubscribePackage" onclick='subscribe_package("{{$i}}")'>{{ __('users.subscribe_btn')}}</button>
														</form>
													</div>
												</div>
											</div>
											<?php $i++; ?>
											@endforeach
									   </div>
                                       @else
                                        <div>{{ __('users.noPackagesFound')}}</div>  
                                       @endif

									<div id="html_snippet" class="klarna_html"></div>  
								</div> 
									
								<input type="button" name="previous" class="previous btn gray_color action-button-previous package-previous" value="{{ __('users.prev_step_btn')}}" /> 
								<input type="button" name="next" class="next btn debg_color action-button 3 package-html" value="{{ __('users.next_step_btn')}}" id="second-step" />

							</fieldset> 
										
								   
							<fieldset class="seller_register_third">
								<div class="form-card">
									<form method="POST" action="{{route('frontThirdStepSellerRegister')}}" class="needs-validation" novalidate="" id="third-step-form">
                                        <!-- <label>{{ __('users.first_name_label')}}<span class="de_col">*</span></label> -->
										<input type="text" name="fname" id="fname" class="form-control" value="{{ old('fname')}}" placeholder="{{ __('users.first_name_label')}} *">
										<span class="invalid-feedback" id="err_fname" style="margin-top: -28px;margin-left: 10px;"></span>

                                        <!-- <label>{{ __('users.last_name_label')}}<span class="de_col">*</span></label> -->
										<input type="text" name="lname" id="lname" class="form-control" value="{{ old('lname')}}"  placeholder="{{ __('users.last_name_label')}} *">
										<span class="invalid-feedback" id="err_lname" style="margin-top: -27px;margin-left: 10px;"></span>

                                        <!-- <label>{{ __('users.address_label')}}</label> -->
										<textarea  id="address" class="form-control" name="address" placeholder="{{ __('users.address_label')}} " rows="5" cols="30"  tabindex="5"></textarea>

                                        <!-- <label>{{ __('users.postal_code_label')}}</label>  -->
										<input type="text" name="postcode" id="postcode" class="form-control" placeholder="{{ __('users.postal_code_label')}}" value="">
										
									</form>                          
								</div> 
								<!--<input type="button" name="previous" class="previous action-button-previous" value="{{ __('users.prev_step_btn')}}" /> -->
								<input type="button" name="next" class="next btn debg_color action-button 4" value="{{ __('users.next_step_btn')}}" id="third-step"/>
							</fieldset>

			   
							<fieldset class="seller_register_fourth">
								<div class="form-card">
									<form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data" id="seller_personal_info">
										@csrf
										<div class="form-group" style="display: flex;">
                                            <!-- <label>{{ __('users.store_name_label')}}<span class="de_col">*</span></label> -->
                                            
                                            <input type="hidden" class="form-control login_input" name="verify_btn_click" id="verify_btn_click" value="">
											<input type="text" class="form-control login_input" name="store_name" id="store_name" placeholder="{{ __('users.store_name_label')}} *">
											<input type="button" name="check-store-unique" class="btn debg_color"onclick="checkStoreName()" value="{{ __('users.verify_btn')}}" style="margin-top: 11px;" /> 
										</div> <span class="invalid-feedback" id="err_store_name"></span>

										<div class="form-group increment cloned">
											<div class="col-md-4 seller_banner_upload" style="margin-top: 20px;"></div>
											<label>{{ __('users.seller_header_img_label')}}</label>
                                            
											<input type="file" name="header_img" id="seller_banner_img" class="form-control seller_banner_img" value="">
											
											<span class="invalid-feedback" id="err_seller_banner_img"></span>
											<div class="input-group-btn text-right"> 
											</div>
                                            
										</div>

										<div class="form-group increment cloned">
											<div class="col-md-4 seller_logo_upload" style="margin-top: 20px;"></div>
											<label>{{ __('users.seller_logo_label')}}</label>
											<input type="file" name="logo" id="seller_logo_img" class="form-control" value="">
											
											<span class="invalid-feedback" id="err_seller_logo_img"></span>	
											<div class="input-group-btn text-right"> 
											</div>
									   </div>
										<div class="remember-section row">
											<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value=""><span class="remember-text">{{ __('users.read_and_approve_chk')}}<a href="javascript:void(0)">&nbsp;{{ __('users.terms_of_use')}} &nbsp;</a> <a href="javascript:void(0)">{{ __('users.privacy_policy')}}</a> {{ __('users.and_chk')}} <a href="javascript:void(0)">{{ __('users.store_terms')}}</a></span>	
										</div>
									</form>
									<input type="submit" name="next" class="next btn debg_color action-button 5" value="{{ __('users.finish_btn')}}" id="last-step"/>
								</div>
							</fieldset>
							
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function subscribe_package(i){
      
         
    
    let user_id  = $("#user_id").val();
    let p_id     = $("#p_id_"+i).val();
    let p_name  = $("#p_name_"+i).val();
    let validity_days = $("#validity_days_"+i).val(); 
    let amount = $("#amount_"+i).val();


        let err = 0;
        if(p_id == ''){

        }
        else if(p_name=='')
        {
            alert("something wrong try again");
            err = 1;
        }
        else if(validity_days==''){
            alert("something wrong try again");
            err = 1;
        }
        else if(amount == '')
        {
            alert("something wrong try again");
            err = 1;
        }else{
            err=0;
        }

        if(err == 0){
   
             $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "{{url('/')}}"+'/klarna-payment',
                type: 'post',
                async: false,
                data:{amount:amount, validity_days:validity_days, p_id:p_id,p_name:p_name},
                success: function(data){

                       $(".loader").hide();
                    if(data.success=="package subscribed"){
                        console.log(data.success);
                        console.log("second step complete");  
                        $(".package-html").hide();
                        $(".klarna_html").html(data.html_snippet).show();
                        //$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                    }else{
                        alert(data.error_msg);
                        error=1;
                    }
                }
            });

            ////$('#klarna_form').submit();
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
            opacity = 1 - now;

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

$(document).ready(function(){

    if($('#current_step_button').val() != 1){
        var curr_step=  $('input#current_step_button').val();

        $( "fieldset" ).each(function() {
          $( this ).css({
                    'display': 'none',
                    'position': 'relative'
                    });
        });
        //$( "li" ).each(function() {
       //  $( this ).removeClass('active');
      // });
 
        //setTimeout(function(){ $('input.'+curr_step).trigger('click')}, 500);
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
        opacity = 1 - now;

        current_fs.css({
        'display': 'none',
        'position': 'relative'
        });
        next_fs.css({'opacity': opacity});
        },
        duration: 600
        });
    }

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
                url: "{{url('/')}}"+'/new-seller-register',
                type: 'post',
                async: false,
                data:{email:email, password:password, password_confirmation:password_confirmation,role_id:role_id},
                success: function(data){
                    $(".loader-seller").css("display","none");
                    if(data.success=="Got Simple Ajax Request"){
                        console.log(data.success);
                        console.log("first step complete");              
                    }else{
                        alert(data.error_msg.email);
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
                // for making fielset appear animation
                opacity = 1 - now;

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


/*second step*/

$('#second-step').click(function(e) { 

    let user_id  = $("#user_id").val();
    var err = 0
    if(user_id == ''){
        alert(select_package_to_subscribe);
        err = 1;
    }
    
        if(err == 0){
            return true
        }else{
            return false
        }
});

/*third step*/
$('#third-step').click(function(e) {  
     
    e.preventDefault();

    let fname     = $("#fname").val();
    let lname  = $("#lname").val();   
    let address     = $("#address").val();
    let postcode  = $("#postcode").val();   
    let third_step_err = 0;

    if(fname == '')
    {
        $("#err_fname").html(fill_in_first_name_err).show();
        $("#err_fname").parent().addClass('jt-error');
        third_step_err = 1;
    }
    else
    {
        $("#err_fname").html('').show();
    }
    if(lname == '')
    {
        $("#err_lname").html(fill_in_last_name_err).show();
        $("#err_lname").parent().addClass('jt-error');
        third_step_err = 1;
    }
    else
    {
        $("#err_lname").html('');
    }
 
  if(third_step_err == 1)
  {
    return false;
  }
  else
  {
    $(".loader-seller").css("display","block");
    $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "{{url('/')}}"+'/third-step-seller-register',
                type: 'post',
                async: false,
                data:{fname:fname, lname:lname, address:address,postcode:postcode},
                success: function(data){
                    if(data.success=="third step success"){
                        $(".loader-seller").css("display","none");
                        console.log(data.success);
                        console.log("third step complete"); 
                        third_step_err = 0;                   
                    }else{
                       
                        third_step_err=1;
                    }
                }
            });
   
  }


        //show next step
        if(third_step_err==0){
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
                // for making fielset appear animation
                opacity = 1 - now;

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

/*last step*/
$('#last-step').click(function(e) {  

    e.preventDefault();
    let store_name     = $("#store_name").val();
    let verify_btn_click = $("#verify_btn_click").val();
    
    if(store_name==''){
       last_step_err = 1;
       alert(please_enter_store_name)
    } 

    if(verify_btn_click==''){
       last_step_err = 1;
       alert(verify_store)
    }

    if($("#chk_privacy_policy").is(':checked')){
       last_step_err = 0;
    } else {
        alert(please_check_privacy_policy);
        last_step_err = 1;

    }


    if(last_step_err == 1){
            return false;
    }
    else 
    {
    let logo_image   = $("#logo_image").val();
    let banner_image = $("#banner_image").val();
    let store_name   = $("#store_name").val();

    let chk_privacy_policy   = $("#chk_privacy_policy").val();
            $(".loader-seller").css("display","block");
            $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "{{url('/')}}"+'/seller-info-page',
                type: 'post',
                async: false,
                data:{banner_image:banner_image,logo_image:logo_image,store_name:store_name},
            
                success: function(data){
                    $(".loader-seller").css("display","none");
                    if(data.success=="last step success"){
                        console.log(data.success);
                        console.log("last step success"); 
                        window.location = "{{ route('frontRegisterSuccess') }}";

                           

                    }
                }
            });
        }  
});

/*function to upload seller banner image*/
$('body').on('change', '#seller_banner_img', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();

    if (validExtensions.indexOf(file) == -1) {
          alert(invalid_files_err);
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
          alert(invalid_files_err);
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
    if($(this).hasClass("package-previous")){
         if($(".klarna_html").is(":visible")){
            $(".package-html").show();
            $(".klarna_html").hide();
            return 1;
         }
    }
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            // for making fielset appear animation
            opacity = 1 - now;

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
return false;
})

});


/*function to check unique store name
* @param : store name
*/
function checkStoreName(){
    var is_verfied = $("#verify_btn_click").val('1');
    var store_name= $("#store_name").val();
    if(store_name!=''){
        $.ajax({
          url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name,
          type: 'get',
          data: { },
          success: function(output){
            if(output !=''){
             alert(output);
            }else{
                alert(store_name_is_verified);
            }
            }
        });
    }else{

     alert(please_enter_store_name)
    }
}

$(document).ready(function(){
	if ( $("#progressbar li#confirm").hasClass("active") ) { 
		$("#progressbar li#payment").addClass("active");
	}
});
</script>
</body>
</html>
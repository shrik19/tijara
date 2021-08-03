@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  @if(!empty($banner->image))
  <img class="login_banner" src="{{url('/')}}/uploads/Banner/{{$banner->image}}" />
@endif
</div>
<!-- multistep seller registration wizard start here -->

<!-- MultiStep Form -->
<div class="container">
<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
    <h2><strong>
    @if(!empty(Session::get('StepsHeadingTitle'))){{Session::get('StepsHeadingTitle')}} @else {{$headingTitle}} @endif
    </strong></h2>

        <div class="row"> 
        <div class="col-md-12 mx-0">
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
                <fieldset>
                    <div class="form-card">
                        <form id="sellerRegisterForm" action="{{route('frontNewSellerRegister')}}" method="post">
                            @csrf
                    	<input type="hidden" name="role_id" id="role_id" value="{{$role_id}}">
                        <label>{{ __('users.email_label')}}<span class="de_col">*</span></label>
                        <input type="email" name="email" id="email" placeholder="{{ __('users.email_label')}}" value="{{$email}}"/> 
                        <span class="invalid-feedback" id="err_email"></span><br>

                        <label>{{ __('users.password_label')}}<span class="de_col">*</span></label>
                        <input type="password" name="password" id="password" placeholder="{{ __('users.password_label')}}"  value="{{$password}}"/>
                        <span class="invalid-feedback" id="err_password" style=""></span><br>

                        <label>{{ __('users.password_confirmation_label')}}<span class="de_col">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ __('users.password_confirmation_label')}}" value="{{$cpassword}}" />
                        <span class="invalid-feedback" id="err_cpassword"></span>
                        </form>
                    </div> 

                    <input type="button" name="next" class="next action-button 2" value="{{ __('users.next_step_btn')}}" id="first-step"  />
                </fieldset>
          
             
                <fieldset>
                    <div class="form-card">
                        @include ('Front.alert_messages')              
                          <div class="col-md-12 package-html">
                            <h2>{{ __('users.subscribe_package_label')}} </h2>
                            <hr class="heading_line"/>
                                @foreach($packageDetails as $data)
                                <div class="col-md-4">
                                    <div class="panel panel-default subscribe-packages">
                                    <div class="panel-heading">{{$data['title']}}</div>
                                    <div class="panel-body" style="max-height: 215px;overflow: auto;">
                                        <p>{{ __('users.description_label')}} : <?php echo $data->description; ?></p>
                                        <p>{{ __('users.amount_label')}} : {{$data['amount']}} kr</p>
                                        <p>{{ __('users.validity_label')}} : {{$data['validity_days']}} Days</p>
                                        <form  action="" class="needs-validation" novalidate="" id="klarna_form">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="" id="user_id">
                                            <input type="hidden" name="p_id" value="{{$data['id']}}" id="p_id">
                                            <input type="hidden" name="p_name" value="{{$data['title']}}" id="p_name">
                                            <input type="hidden" name="validity_days" value="{{$data['validity_days']}}" id="validity_days">
                                            <input type="hidden" name="amount" value="{{$data['amount']}}" id="amount">     
                                            <button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn btnsubscribePackage">{{ __('users.subscribe_btn')}}</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                                @endforeach
                           </div>

                        <div id="html_snippet" class="klarna_html"></div>  
                    </div> 
                        
                    <input type="button" name="previous" class="previous action-button-previous package-previous" value="{{ __('users.prev_step_btn')}}" /> 
                    <input type="button" name="next" class="next action-button 3 package-html" value="{{ __('users.next_step_btn')}}" id="second-step" />
                </fieldset> 
                            
                       
                <fieldset>
                    <div class="form-card">
                        <form method="POST" action="{{route('frontThirdStepSellerRegister')}}" class="needs-validation" novalidate="" id="third-step-form">
                            <input type="text" name="fname" id="fname" value="{{ old('fname')}}" placeholder="{{ __('users.first_name_label')}}">
                            <span class="invalid-feedback" id="err_fname"></span>

                            <input type="text" name="lname" id="lname" value="{{ old('lname')}}"  placeholder="{{ __('users.last_name_label')}}">
                            <span class="invalid-feedback" id="err_lname"></span>

                            <textarea  id="address" name="address" rows="5" cols="30"  tabindex="5"></textarea> 
                            <input type="text" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="">
                            
                        </form>                          
                    </div> 
                    <!--<input type="button" name="previous" class="previous action-button-previous" value="{{ __('users.prev_step_btn')}}" /> -->
                    <input type="button" name="next" class="next action-button 4" value="{{ __('users.next_step_btn')}}" id="third-step"/>
                </fieldset>

   
                <fieldset>
                    <div class="form-card">
                        <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data" id="seller_personal_info">
                            @csrf
                        	<div style="display: flex;">
                                <input type="text" class="form-control login_input" name="store_name" id="store_name" placeholder="{{ __('users.store_name_label')}}">
                        	    <input type="button" name="check-store-unique" onclick="checkStoreName()" value="Verify" /> 
                        	</div> <span class="invalid-feedback" id="err_store_name"></span>

                        	<div class="form-group increment cloned">
        			            <label>{{ __('users.seller_header_img_label')}}</label>
                                 <div class="col-md-4 seller_banner_upload">
                                 
                                    </div>
                               
                                <div class="row"><div class="col-md-12">&nbsp;</div></div>
        			           

        			            <input type="file" name="header_img" id="seller_banner_img" class="form-control seller_banner_img" value="">
        			              
        			            <span class="invalid-feedback" id="err_seller_banner_img"></span>
        			            <div class="input-group-btn text-right"> 
        			            </div>
    			            </div>

        			        <div class="form-group increment cloned">
        			            <label>{{ __('users.seller_logo_label')}}</label>
                                <div class="col-md-4 seller_logo_upload"></div>
                                <div class="row"><div class="col-md-12">&nbsp;</div></div>
        			            <input type="file" name="logo" id="seller_logo_img" class="form-control" value="">
                                <span class="invalid-feedback" id="err_seller_logo_img"></span>
        			            
        			            <div class="input-group-btn text-right"> 
        			            </div>
        			       </div>
    			            <div  style="display: flex;">
    			          		<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value="">{{ __('users.read_and_approve_chk')}}<a href="javascript:void(0)">&nbsp;{{ __('users.terms_of_use')}} &nbsp;</a> <a href="javascript:void(0)">{{ __('users.privacy_policy')}}</a> {{ __('users.and_chk')}} <a href="javascript:void(0)">{{ __('users.store_terms')}}</a>
    			      		</div>

                        	<input type="submit" name="next" class="next action-button 5" value="{{ __('users.finish_btn')}}" id="last-step"/>
                    </form>
                    </div>
                </fieldset>
                
            </div>
        </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
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
            $("#err_email").html(fill_in_email_err).show();
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

$('.btnsubscribePackage').click(function(e) { 
    e.preventDefault();
    
    let user_id  = $("#user_id").val();
    let p_id     = $("#p_id").val();
    let p_name  = $("#p_name").val();
    let validity_days = $("#validity_days").val(); 
    let amount = $("#amount").val();


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
                        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
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
   
    if($("#chk_privacy_policy").is(':checked')){
       last_step_err = 0;
    } else {
        alert("please check privacy policy");
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

     alert("please enter store name")
    }
}

</script>
@endsection
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
<style type="text/css">
	* {
    margin: 0;
    padding: 0
}

html {
    height: 100%
}



#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}

#msform input,
#msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: skyblue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 25%;
    float: left;
    position: relative
}

#progressbar #account:before {
    font-family: FontAwesome;
    content: "\f023"
}

#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f09d"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: skyblue
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>
<!-- MultiStep Form -->
<div class="container">
		<div class="container-fluid" id="grad1">
    		<div class="row justify-content-center mt-0">
                <h2><strong>Sälj med Tijara</strong></h2>
                
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Registrering</strong></li>
                                <li id="personal"><strong>Välj paket</strong></li>
                                <li id="payment"><strong>Hur du får betalt</strong></li>
                                <li id="confirm"><strong>Din butikssida</strong></li>
                            </ul> <!-- fieldsets -->
                 
                            <?php
                                $session_user=Session::get('seller_register_form_id');
                             ?>
                             @if(empty($session_user))
                            <fieldset>
                                <div class="form-card">
                                    <form id="sellerRegisterForm" action="{{route('frontNewSellerRegister')}}" method="post">
                                        @csrf
                                	<input type="hidden" name="role_id" id="role_id" value="{{$role_id}}">
                                    <input type="email" name="email" id="email" placeholder="Email Id" /> 
                                    <span class="invalid-feedback" id="err_email" style="">@if($errors->has('email')) {{ $errors->first('email') }}@endif</span>

                                    <input type="password" name="password" id="password" placeholder="Password" />
                                    <span class="invalid-feedback" id="err_password" style="">@if($errors->has('password')) {{ $errors->first('password') }}@endif</span>

                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" />
                                    <span class="invalid-feedback" id="err_cpassword" style="">@if($errors->has('password_confirmation')) {{ $errors->first('password_confirmation') }}@endif</span>
                                    </form>
                                </div> 

                                <input type="button" name="next" class="next action-button" value="Next Step" id="first-step"  />
                            </fieldset>
                        
                            <fieldset>
                                <div class="form-card">
                                    @include ('Front.alert_messages')

                                    @if(count($subscribedPackage) != 0 && !empty($subscribedPackage))
                                    <div class="col-md-12">
                                        
                                        
                                        
                                            <h2>{{ __('users.your_active_package')}}</h2>
                                            <hr class="heading_line"/>
                                            @foreach($subscribedPackage as $row)
                                            
                                             <div class="col-md-4">
                                                <div class="panel panel-default subscribe-packages">
                                                <div class="panel-heading package-tbl">{{$row->title}}</div>
                                                <div class="panel-body"  style="">
                                                    <table class="table" style="border: 0px;min-height: 315px;overflow: auto;">
                                                      <tbody>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('users.description_label')}}</td>
                                                            <td><?php echo $row->description; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('users.amount_label')}}</td>
                                                            <td> {{$row->amount}} kr</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('users.validity_label')}}</td>
                                                            <td>{{$row->validity_days}} Days.</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('users.purchased_date_label')}}</td>
                                                            @if($row->start_date >= date('Y-m-d H:i:s'))
                                                                <td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
                                                                
                                                            @else
                                                                <td>{{date('l, d F Y',strtotime($row->start_date))}}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('users.expiry_date_label')}}</td>
                                                            <td>{{date('l, d F Y',strtotime($row->end_date))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="package-tbl">{{ __('lang.status_label')}}</td>
                                                            @if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=="CAPTURED" )
                                                                <td><a href="javascript:void(0)" class="btn btn-warning "> {{ __('users.not_activated_label')}}</a></td>
                                                            @elseif($row->payment_status=="checkout_incomplete")
                                                            <td><a href="javascript:void(0)" class="btn btn-danger"> {{ __('lang.pending_label')}}</a>
                                                                <p style="font-weight: bold;margin-top: 20px;margin-left:-108px;color: green"> {{ __('messages.payment_in_process')}}</p>
                                                                <a href="" class="btn btn-info" style="margin-left: 114px;margin-top: -60px"> Reload</a>
                                                            </td>
                                                            @elseif($row->status=="active")
                                                                <td><a href="javascript:void(0)" class="btn btn-success "> {{ __('lang.active_label')}} </a></td>
                                                            @endif
                                                        </tr>
                                                        
                                                      </tbody>
                                                    </table>
                                                </div>
                                                </div>
                                            </div>
                                            @endforeach
                                     
                                    </div>
                                         @else 
              
                                      <div class="col-md-12">
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
                                                    <form method="POST" action="{{route('frontklarnaPayment')}}" class="needs-validation" novalidate="" id="klarna_form">
                                                    {{ csrf_field() }}
                                                        <input type="hidden" name="user_id" value="" id="user_id">
                                                        <input type="hidden" name="p_id" value="{{$data['id']}}" id="p_id">
                                                        <input type="hidden" name="p_name" value="{{$data['title']}}" id="p_name">
                                                        <input type="hidden" name="validity_days" value="{{$data['validity_days']}}" id="validity_days">
                                                        <input type="hidden" name="amount" value="{{$data['amount']}}" id="amount">     
                                                        <button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn">{{ __('users.subscribe_btn')}}</button>
                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                            @endforeach
                                       </div>
                                       @endif
                                </div> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                <input type="button" name="next" class="next action-button" value="Next Step" id="second-step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Tijara Bas</h2> 
                                    <form method="POST" action="{{route('frontThirdStepSellerRegister')}}" class="needs-validation" novalidate="" id="third-step-form">
                                        <input type="text" name="fname" id="fname" value="{{ old('fname')}}" placeholder="{{ __('users.first_name_label')}}">
                                        <span class="invalid-feedback" id="err_fname"></span>

                                        <input type="text" name="lname" id="lname" value="{{ old('lname')}}"  placeholder="{{ __('users.last_name_label')}}">
                                        <span class="invalid-feedback" id="err_lname"></span>

                                        <textarea  id="address" name="address" rows="5" cols="30"  tabindex="5"></textarea> 
                                        <input type="text" name="postcode" id="postcode" placeholder="{{ __('users.postal_code_label')}}" value="">
                                      <!--   <input type="text" name="swish_number" id="swish_number" placeholder="{{ __('users.swish_number_label')}}" value="">
                                        <input type="text" name="paypal_email" id="paypal_email" placeholder="{{ __('users.paypal_email_address_label')}}" value="">      -->    
                                    </form>                          
                                </div> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                <input type="button" name="next" class="next action-button" value="Next Step" id="third-step"/>
                            </fieldset>
                                @endif
                            <fieldset>
                                <div class="form-card">
                                    <form id="seller-personal-form" action="{{route('frontSellerPersonalPage')}}" method="post"  enctype="multipart/form-data" id="seller_personal_info">
                                     @csrf
                                	<div style="display: flex;"><input type="text" class="form-control login_input" name="store_name" id="store_name" placeholder="{{ __('users.store_name_label')}}">
                                	  <input type="button" name="check-store-unique" onclick="checkStoreName()" value="Verify" /> 
                                	</div> <span class="invalid-feedback" id="err_store_name"></span>

                                	  <div class="form-group increment cloned">
						            <label>{{ __('users.seller_header_img_label')}}</label>
						            @php
						            if(!empty($details->header_img))
						            {
						              echo '<div class="row">';
						              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->header_img.'" style="width:200px;height:200px;"></div>';
						              echo '</div>';
						              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
						            }
						            @endphp

						            <input type="file" name="header_img" id="seller_banner_img" class="form-control" value="">
						              
						            <span class="invalid-feedback" id="err_seller_banner_img"></span>
						            <div class="input-group-btn text-right"> 
						            </div>
						          </div>
           
						          <div class="form-group increment cloned">
						            <label>{{ __('users.seller_logo_label')}}</label>
						            @php
						            if(!empty($details->logo))
						            {
						              echo '<div class="row">';
						              echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Seller/resized/'.$details->logo.'" style="width:200px;height:200px;"></div>';
						              echo '</div>';
						              echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
						            }
						            @endphp

						            <input type="file" name="logo" id="seller_logo_img" class="form-control" value="">
                                     <span class="invalid-feedback" id="err_seller_logo_img"></span>
						            
						            <div class="input-group-btn text-right"> 
						            </div>
						          </div>
						          <div  style="display: flex;">
						          		<input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value="">Jag har tagit del av och godkänner Tijaras <a href="javascript:void(0)">Användarvillkor</a> <a href="javascript:void(0)">Integritetspolicy</a> samt <a href="javascript:void(0)">Butiksvillkor</a>
						      		</div>

                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                	<input type="submit" name="next" class="next action-button" value="Slutför" id="last-step"/>
                                </form>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Signed Up</h5>
                                        </div>
                                    </div>
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


/*function to save first form data and validate it before save*/
/*    $('#first-step').click(function(e) {  

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
            //save form data on validation success
            $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "{{url('/')}}"+'/new-seller-register',
                type: 'post',
                async: false,
                data:{email:email, password:password, cpassword:password_confirmation,role_id:role_id},
                success: function(data){
                    if(data.success=="Got Simple Ajax Request"){
                        console.log(data.success);
                        console.log("first step complete"); 
                        $("#user_id").val(data.user_id);                   
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
*/

/*second step*/
/*
$('#second-step').click(function(e) { 
    e.preventDefault();
    
    let user_id  = $("#user_id").val();
    let p_id     = $("#p_id").val();
    let p_name  = $("#p_name").val();
    let validity_days = $("#validity_days").val(); 
    let amount = $("#amount").val();
    let err = 0;
        if(user_id == '')
        {
            alert("something wrong try again");
            err = 1;
        }else if(p_id == ''){

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
            $('#klarna_form').submit();
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
});*/

/*third step*/
/*$('#third-step').click(function(e) {  
     
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
});*/

/*last step*/
$('#last-step').click(function(e) {  
     
    e.preventDefault();
    let store_name     = $("#store_name").val();
    let header_img  = $("#seller_banner_img").val();   
    let seller_logo     = $("#seller_logo_img").val();
    let last_step_err = 0;
    if(store_name == '')
    {
        $("#err_store_name").html(fill_in_first_name_err).show();
        $("#err_store_name").parent().addClass('jt-error');
        last_step_err = 1;
    }
    else
    {
        $("#err_store_name").html('');
    }

    if(header_img == '')
    {
        $("#err_seller_banner_img").html("please upload banner image").show();
        $("#err_seller_banner_img").parent().addClass('jt-error');
     
        last_step_err = 1;
    }
    else
    {
        $("#err_seller_banner_img").html('');
    }
    
    if(seller_logo == '')
    {
        $("#err_seller_logo_img").html("please upload your business logo").show();
        $("#err_seller_logo_img").parent().addClass('jt-error');
        last_step_err = 1;
    }
    else
    {
        $("#err_seller_logo_img").html('');
    }

    
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
/*          var file_data = $('#seller_banner_img').prop('file')[0]; */  
          //alert(file_data);return
  /*  var form_data = new FormData();                  
    form_data.append('file', file_data);
    console.log(form_data);return*/
        //save form data on validation success
          // var formData = new FormData();
        /*  formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);*/
//         var formData = new FormData($('#seller-personal-form')[0]);
// formData.append('store_name', store_name);
// formData.append('header_img', $('input[type=file]')[0].files[0]);

// formData.append('header_img', $('input[type=file]')[1].files[1]);
var formData = new FormData(this);
console.log(formData);
//console.log(formData);return
            $.ajax({
                headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                         },
                url: "{{url('/')}}"+'/seller-info-page',
                type: 'post',
                async: false,
                data:{formData},
                contentType: false,
                processData: false,
                cache:false,
                success: function(data){
                    if(data.success=="last step success"){
                        console.log(data.success);
                        console.log("last step success"); 
                        console.log(data.details);                  
                    }
                }
            });
        }
  
    if(last_step_err == 0){
        // alert("in");
        // $(this).attr('type','submit').trigger('click');
 /**/
     
    }
  
});

$('body').on('change', '#seller_banner_img', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();
  if (validExtensions.indexOf(file) == -1) {
          alert(invalid_files_err);
          $(this).val('');
          return false;
}
});

$('body').on('change', '#seller_logo_img', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();
  if (validExtensions.indexOf(file) == -1) {
          alert(invalid_files_err);
          $(this).val('');
          return false;
}
});
/*
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
*/
$(".previous").click(function(){

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
/*first step validation and save*/
	


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
                alert("store name is verified");
            }
            }
        });
    }else{

     alert("please enter store name")
    }
}

</script>
@endsection
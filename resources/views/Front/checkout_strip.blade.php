<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
<!-- new design start -->       
<div class="checkoutContainer">
    <div class="container-inner-section container">
        <div class="">  
            <div class="card">
                <div class="card-header row">

                </div>
                <div class="card-body">
                     <div class="col-md-12 checkout-back">
                      <span class="arrow-border"><i class="arrow left show_cart"></i></span><a href="{{route('frontShowCart')}}">&nbsp;{{ __('users.back_to_the_cart_btn')}} </a> 
                    </div>

                    <div class="col-md-12 checkoutHeader">
                      <div class="col-md-3">
                        <a class="navbar-brand tj-logo" href="{{url('/')}}"><img class="logo" src="{{url('/')}}/uploads/Images/{{$siteDetails->header_logo}}"/></a>
                      </div>
                      <div class="col-md-6">
                        <h1 class="checkoutHeading text-center">{{ __('users.checkout_cart_title')}}</h1>  
                      </div>  
                      <div class="col-md-3"></div>
                    </div>

                    <section class=""> 
                    <div class="loader"></div>
                    <div class="row">
                          <div class="col-md-10 col-md-offset-1">
                            <div id="payment-page">
                              <div class="container container-fluid p_155">
                              <div class="row">

                                <div class="col-md-6 col-md-offset-2">

                                    <div class="panel panel-default credit-card-box">

                                        <div class="panel-heading display-table" >

                                            <div class="row display-tr" >
                                                 <div class="col-md-6" >                            
                                                    <h3 class="panel-title display-td checkout_stripe_info_title" >{{ __('lang.strip_payment_details')}}</h3>
                                                </div>
                                                <div class="display-td col-md-6" >                          
                                                    <img class="img-responsive pull-right" src="{{url('/')}}/uploads/Images/stripe-payment-logo.png">

                                                </div>

                                            </div>                    

                                        </div>

                                        <div class="panel-body">


                                            <form role="form" action="{{ route('checkoutStripProcess') }}" method="post" class="require-validation"

                                                                            data-cc-on-file="false"

                                                                            data-stripe-publishable-key="{{$strip_secret}}"

                                                                            id="payment-form">

                                                @csrf
                                                <input type="hidden" name="order_id" value="{{$order_id}}">


                                                <div class='form-row row'>

                                                    <div class='col-xs-12 form-group required'>

                                                        <label class='control-label strip_html_label'>{{ __('lang.name_on_card')}}</label> <input

                                                            class='form-control' size='4' type='text'>

                                                    </div>

                                                </div>



                                                <div class='form-row row'>

                                                    <div class='col-xs-12 form-group card required'>

                                                        <label class='control-label strip_html_label'>{{ __('lang.card_number')}}</label> <input

                                                            autocomplete='off' class='form-control card-number' size='20'

                                                            type='text'>

                                                    </div>

                                                </div>



                                                <div class='form-row row'>

                                                    <div class='col-xs-12 col-md-4 form-group cvc required'>

                                                        <label class='control-label strip_html_label'>{{ __('lang.cvc')}}</label> <input autocomplete='off'

                                                            class='form-control card-cvc' placeholder='ex. 311' size='4'

                                                            type='text'>

                                                    </div>

                                                    <div class='col-xs-12 col-md-4 form-group expiration required'>

                                                        <label class='control-label strip_html_label'>{{ __('lang.expiration_month')}}</label> <input

                                                            class='form-control card-expiry-month' placeholder='MM' size='2'

                                                            type='text'>

                                                    </div>

                                                    <div class='col-xs-12 col-md-4 form-group expiration required'>

                                                        <label class='control-label strip_html_label'>{{ __('lang.expiration_year')}} </label> <input

                                                            class='form-control card-expiry-year' placeholder='YY' size='4'

                                                            type='text'>

                                                    </div>

                                                </div>



                                                <div class='form-row row'>

                                                    <div class='col-md-12 error form-group hide'>

                                                        <div class='alert-danger alert'>{{ __('lang.strip_error_message')}}</div>

                                                    </div>

                                                </div>



                                                <div class="row">

                                                    <div class="col-xs-12">

                                                        <button class="btn btn-primary btn-lg btn-block" type="submit">{{ __('lang.pay_now')}} ({{$Total}} kr)</button>

                                                    </div>

                                                </div>

                                                  

                                            </form>

                                        </div>

                                    </div>        

                                </div>

                                </div>


                              </div>
                            </div>
                          </div>
                        </div>
                    </section> 
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- /container -->

<!-- end new design -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>


<script type="text/javascript">

$(function() {

    var $form         = $(".require-validation");

  $('form.require-validation').bind('submit', function(e) {

    var $form         = $(".require-validation"),

        inputSelector = ['input[type=email]', 'input[type=password]',

                         'input[type=text]', 'input[type=file]',

                         'textarea'].join(', '),

        $inputs       = $form.find('.required').find(inputSelector),

        $errorMessage = $form.find('div.error'),

        valid         = true;

        $errorMessage.addClass('hide');

 

        $('.has-error').removeClass('has-error');

    $inputs.each(function(i, el) {

      var $input = $(el);

      if ($input.val() === '') {

        $input.parent().addClass('has-error');

        $errorMessage.removeClass('hide');

        e.preventDefault();

      }

    });

  

    if (!$form.data('cc-on-file')) {

      e.preventDefault();

      Stripe.setPublishableKey($form.data('stripe-publishable-key'));

      Stripe.createToken({

        number: $('.card-number').val(),

        cvc: $('.card-cvc').val(),

        exp_month: $('.card-expiry-month').val(),

        exp_year: $('.card-expiry-year').val()

      }, stripeResponseHandler);

    }

  

  });

  

  function stripeResponseHandler(status, response) {

        if (response.error) {

            $('.error')

                .removeClass('hide')

                .find('.alert')

                .text(response.error.message);

        } else {

            // token contains id, last4, and card type

            var token = response['id'];

            // insert the token into the form so it gets submitted to the server

            $form.find('input[type=text]').empty();

            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

            $form.get(0).submit();

        }

    }

  

});

</script>
<script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
<script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>
<script src="{{url('/')}}/assets/front/js/jquery-confirm.min.js"></script>

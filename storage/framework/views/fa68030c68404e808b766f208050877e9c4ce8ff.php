
<?php $__env->startSection('middlecontent'); ?>


<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container">
          <div class="row">

            <div class="col-md-6 col-md-offset-3">

                <div class="panel panel-default credit-card-box">

                    <div class="panel-heading display-table" >

                        <div class="row display-tr" >

                            <h3 class="panel-title display-td" ><?php echo e(__('lang.strip_payment_details')); ?></h3>

                            <div class="display-td" >                            

                                <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">

                            </div>

                        </div>                    

                    </div>

                    <div class="panel-body">


                        <form role="form" action="<?php echo e(route('checkoutStripProcess')); ?>" method="post" class="require-validation"

                                                        data-cc-on-file="false"

                                                        data-stripe-publishable-key="<?php echo e($strip_api_key); ?>"

                                                        id="payment-form">

                            <?php echo csrf_field(); ?>
                            <input type="text" name="order_id" value="<?php echo e($order_id); ?>">


                            <div class='form-row row'>

                                <div class='col-xs-12 form-group required'>

                                    <label class='control-label'><?php echo e(__('lang.name_on_card')); ?></label> <input

                                        class='form-control' size='4' type='text'>

                                </div>

                            </div>



                            <div class='form-row row'>

                                <div class='col-xs-12 form-group card required'>

                                    <label class='control-label'><?php echo e(__('lang.card_number')); ?></label> <input

                                        autocomplete='off' class='form-control card-number' size='20'

                                        type='text'>

                                </div>

                            </div>



                            <div class='form-row row'>

                                <div class='col-xs-12 col-md-4 form-group cvc required'>

                                    <label class='control-label'><?php echo e(__('lang.cvc')); ?></label> <input autocomplete='off'

                                        class='form-control card-cvc' placeholder='ex. 311' size='4'

                                        type='text'>

                                </div>

                                <div class='col-xs-12 col-md-4 form-group expiration required'>

                                    <label class='control-label'><?php echo e(__('lang.expiration_month')); ?></label> <input

                                        class='form-control card-expiry-month' placeholder='MM' size='2'

                                        type='text'>

                                </div>

                                <div class='col-xs-12 col-md-4 form-group expiration required'>

                                    <label class='control-label'><?php echo e(__('lang.expiration_year')); ?> </label> <input

                                        class='form-control card-expiry-year' placeholder='YYYY' size='4'

                                        type='text'>

                                </div>

                            </div>



                            <div class='form-row row'>

                                <div class='col-md-12 error form-group hide'>

                                    <div class='alert-danger alert'><?php echo e(__('lang.strip_error_message')); ?></div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-xs-12">

                                    <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo e(__('lang.pay_now')); ?> (<?php echo e($Total); ?> kr)</button>

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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/checkout_strip.blade.php ENDPATH**/ ?>
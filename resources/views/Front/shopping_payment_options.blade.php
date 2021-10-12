@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  .invalid-feedback {
    position: relative !important;
}
</style>
<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section">
  <div class="row">
    <div class="">
        
      <div class="col-md-12">
        <div class="card">
          <div class="card-header row">
            
          </div>
          <div class="card-body">
            <form method="POST" action="" class="needs-validation tijara-form" novalidate="">
              @csrf
              <div class="col-md-12">

                <h2>Orderdetaljer - #{{$orderId}}</h2>
                <hr class="heading_line">
              </div>
              <div class="col-md-12 login_box">
                <div class="col-md-6">
                  <h4><strong>{{ __('messages.txt_billing_address')}}</strong></h4>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.first_name_label')}}</label>
                    <input type="text" class="form-control login_input " name="billing_given_name" id="billing_given_name" 
                    placeholder="{{ __('users.first_name_label')}}" 
                    value="{{ (old('billing_given_name')) ?  old('billing_given_name') : $details->fname}}">
                    <span class="invalid-feedback" id="err_billing_given_name"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.last_name_label')}}</label>
                    <input type="text" class="form-control login_input " name="billing_family_name" id="billing_family_name"
                    placeholder="{{ __('users.last_name_label')}}" 
                    value="{{ (old('billing_family_name')) ?  old('billing_family_name') : $details->lname}}">
                    <span class="invalid-feedback" id="err_billing_family_name"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.email_label')}}</label>
                    <input type="email" class="form-control login_input " name="billing_email" id="billing_email"
                    placeholder="{{ __('users.email_label')}}" 
                    value="{{ (old('billing_email')) ?  old('billing_email') : $details->email}}">
                    <span class="invalid-feedback" id="err_billing_email"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.address_label')}}</label>
                    <input type="address" class="form-control login_input " name="billing_address" id="billing_address"
                    placeholder="{{ __('users.address_label')}}" 
                    value="{{ (old('billing_address')) ?  old('billing_address') : $details->address}}">
                    <span class="invalid-feedback" id="err_billing_address"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.city_label')}}</label>
                    <input type="city" class="form-control login_input " name="billing_city" id="billing_city"
                    placeholder="{{ __('users.city_label')}}" 
                    value="{{ (old('billing_city')) ?  old('billing_city') : $details->city}}">
                    <span class="invalid-feedback" id="err_billing_city"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.postal_code_label')}}</label>
                    <input type="postcode" class="form-control login_input " name="billing_postcode" id="billing_postcode"
                    placeholder="{{ __('users.postal_code_label')}}" 
                    value="{{ (old('billing_postcode')) ?  old('billing_postcode') : $details->postcode}}">
                    <span class="invalid-feedback" id="err_billing_postcode"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.phone_number_label')}}</label>
                    <input type="phone_number" class="form-control login_input " name="billing_phone_number" id="billing_phone_number"
                    placeholder="{{ __('users.phone_number_label')}}" 
                    value="{{ (old('billing_phone_number')) ?  old('billing_phone_number') : $details->phone_number}}">
                    <span class="invalid-feedback" id="err_billing_phone_number"></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <h4><strong>{{ __('messages.txt_shipping_address')}}</strong>
                  <div style="float:right;">
                          <input type="checkbox" name="same_as_billing" 
                          id="same_as_billing" value=""><span class="remember-text">
                          Samma som fakturering
                        </span>  
                      </div>
                  </h4>
                  
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.first_name_label')}}</label>
                    <input type="text" class="form-control login_input " name="shipping_given_name" id="shipping_given_name" 
                    placeholder="{{ __('users.first_name_label')}}" 
                    value="{{ (old('shipping_given_name')) ?  old('shipping_given_name') : $details->fname}}">
                    <span class="invalid-feedback" id="err_shipping_given_name"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.last_name_label')}}</label>
                    <input type="text" class="form-control login_input " name="shipping_family_name" id="shipping_family_name"
                    placeholder="{{ __('users.last_name_label')}}" 
                    value="{{ (old('shipping_family_name')) ?  old('shipping_family_name') : $details->lname}}">
                    <span class="invalid-feedback" id="err_shipping_family_name"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.email_label')}}</label>
                    <input type="email" class="form-control login_input " name="shipping_email" id="shipping_email"
                    placeholder="{{ __('users.email_label')}}" 
                    value="{{ (old('shipping_email')) ?  old('shipping_email') : $details->email}}">
                    <span class="invalid-feedback" id="err_shipping_email"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.address_label')}}</label>
                    <input type="address" class="form-control login_input " name="shipping_address" id="shipping_address"
                    placeholder="{{ __('users.address_label')}}" 
                    value="{{ (old('shipping_address')) ?  old('shipping_address') : $details->address}}">
                    <span class="invalid-feedback" id="err_shipping_address"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.city_label')}}</label>
                    <input type="city" class="form-control login_input " name="shipping_city" id="shipping_city"
                    placeholder="{{ __('users.city_label')}}" 
                    value="{{ (old('shipping_city')) ?  old('shipping_city') : $details->city}}">
                    <span class="invalid-feedback" id="err_shipping_city"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.postal_code_label')}}</label>
                    <input type="postcode" class="form-control login_input " name="shipping_postcode" id="shipping_postcode"
                    placeholder="{{ __('users.postal_code_label')}}" 
                    value="{{ (old('shipping_postcode')) ?  old('shipping_postcode') : $details->postcode}}">
                    <span class="invalid-feedback" id="err_shipping_postcode"></span>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">{{ __('users.phone_number_label')}}</label>
                    <input type="phone_number" class="form-control login_input " name="shipping_phone_number" id="shipping_phone_number"
                    placeholder="{{ __('users.phone_number_label')}}" 
                    value="{{ (old('shipping_phone_number')) ?  old('shipping_phone_number') : $details->phone_number}}">
                    <span class="invalid-feedback" id="err_shipping_phone_number"></span>
                  </div>
                </div>  
              </div>
              <div class="col-md-12">
                  <div class="col-md-2"></div>
                @foreach($payment_options as $p)
                  <div class="col-md-3 text-center">
                    <button id="{{$p}}" type="button" class="btn buy_now_btn debg_color pay_through_btn" style="font-size:18px;"
                    >
                      {{ __('lang.pay_through')}} {{$p}} <span class="glyphicon glyphicon-play"></span>
                    </button>
                  </div>
                  @endforeach
              </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
</div> <!-- /container -->
<script>
  $('#same_as_billing').change(function() {
        if(this.checked) {
            $('#shipping_given_name').val($('#billing_given_name').val());
            $('#shipping_family_name').val($('#billing_family_name').val());
            $('#shipping_email').val($('#billing_email').val());
            $('#shipping_address').val($('#billing_address').val());
            $('#shipping_city').val($('#billing_city').val());
            $('#shipping_postcode').val($('#billing_postcode').val());
            $('#shipping_phone_number').val($('#billing_phone_number').val());
            
        }
             
    });
  $( ".pay_through_btn" ).click(function() {
    var error=0;
    var btnid = $(this).attr('id');
    $( ".login_input" ).each(function() {
      if($(this).val().trim()=='') {
        error = 1;
        $(this).next('.invalid-feedback').html('Detta fält är obligatoriskt');
      }
      else
      $(this).next('.invalid-feedback').html('');
      
    });
    if(error==0) {
      if(btnid=='klarna')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'klarna'])}}");
      if(btnid=='swish')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'swish'])}}");
      
      if(btnid=='strip')
      $('form').attr('action',"{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>'strip'])}}");
      
      $('form').submit();
    }
      //window.location.href = "{{route('frontShowCheckout', ['id' => base64_encode($orderId),'paymentoption'=>$p])}}";
  });
  </script>
@endsection
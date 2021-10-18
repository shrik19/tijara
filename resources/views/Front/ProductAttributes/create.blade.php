@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
  <div class="container-inner-section">
  <!-- Example row of columns -->
  <div class="row">
      <div class="col-md-12">
  <div class="col-md-2 tijara-sidebar">
        @include ('Front.layout.sidebar_menu')
      </div>
        @if($subscribedError)
	    <div class="alert alert-danger">{{$subscribedError}}</div>
	    @endif
        <div class="col-md-10 tijara-content">
          <div class="seller_info">
            <div class="card">
        <div class="card-header row seller_header">
           <h2 class="page_heading">{{ __('lang.attribute_form_label')}}</h2>
        </div>
         <div class="seller_mid_cont"  style="margin-top: 40px;">
    
      <div class="col-md-6">
        <div class="login_box">
           <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('frontAttributeStore')}}" >
             {{ csrf_field() }}

            <div class="form-group col-md-12">
              <label>{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input col-md-12" id="name" name="name" placeholder="{{ __('lang.attribute_label')}} " value="{{ old('name') }}" />
              <span class="invalid-feedback" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </span>
            </div>

            <div class="form-group col-md-12">
              <label>{{ __('lang.attribute_value_label')}} <span class="de_col">*</span></label>
              <select class="form-control login_input col-md-12" id="type" name="type">
                <option value="">{{ __('lang.select_label')}} </option>
              <!--   <option value="radio">{{ __('lang.radio_label')}}</option>  --> 
                <option value="dropdown">{{ __('lang.dropdown_label')}}</option>
                <!-- <option value="textbox">{{ __('lang.textbox_label')}}</option> -->
              </select>
              <span class="invalid-feedback" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
            </div>
            <div style="margin-top: 30px;">
            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
           
            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>
            </div>
          </form>
    
        </div>
</div></div></div>
    </div>
</div>
</div>
  </div></div></div>
</div> <!-- /container -->

@endsection
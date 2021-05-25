@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="">
        @if($subscribedError)
	    <div class="alert alert-danger">{{$subscribedError}}</div>
	    @endif
      <div class="col-md-3"></div> 
      <div class="col-md-6">
        <h2>{{ __('lang.attribute_form_label')}}</h2>
        <hr class="heading_line"/>
        <div class="login_box">
           <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('frontAttributeStore')}}" >
             {{ csrf_field() }}

            <div class="form-group">
              <label>{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" id="name" name="name" placeholder="{{ __('lang.attribute_label')}} " value="{{ old('name') }}" />
              <span class="invalid-feedback" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('lang.attribute_value_label')}} <span class="de_col">*</span></label>
              <select class="form-control login_input" id="type" name="type">
                <option value="">{{ __('lang.select_label')}} </option>
                <option value="radio">{{ __('lang.radio_label')}}</option>  
                <option value="dropdown">{{ __('lang.dropdown_label')}}</option>
                <option value="textbox">{{ __('lang.textbox_label')}}</option>
              </select>
              <span class="invalid-feedback" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
            </div>

            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
           
            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>
          </form>
    
        </div>
      </div>

    </div>
  </div>
</div> <!-- /container -->

@endsection
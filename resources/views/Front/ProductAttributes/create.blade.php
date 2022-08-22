@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
   ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }

  .invalid-feedback {
    position: relative;
  }
</style>
<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
        @if($subscribedError)
	    <div class="alert alert-danger update-alert-css">{{$subscribedError}}</div>
	    @endif
        <div class="col-md-10 tijara-content">
          <div class="seller_info">
            <div class="card">
        <div class="card-header row seller_header">
           <h2 class="seller_page_heading"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.attribute_form_label')}}</h2>
        </div>
         <div class="seller_mid_cont">
    
      <div class="col-md-6">
        <div class="login_box">
           <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('frontAttributeStore')}}" >
             {{ csrf_field() }}

            <div class="form-group col-md-12">

              <label class="product_table_heading">{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" id="name" name="name" placeholder="{{ __('lang.attribute_label')}} " value="{{ old('name') }}" />
              <span class="invalid-feedback" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </span>
            </div>

            <div class="form-group col-md-12">
              <label class="product_table_heading">{{ __('lang.attribute_value_label')}} <span class="de_col">*</span></label>
              <select class="form-control login_input" id="type" name="type">
                <option disabled="disabled">{{ __('lang.select_label')}} </option>
              <!--   <option value="radio">{{ __('lang.radio_label')}}</option>  --> 
                <option value="dropdown">{{ __('lang.dropdown_label')}}</option>
                <!-- <option value="textbox">{{ __('lang.textbox_label')}}</option> -->
              </select>
              <span class="invalid-feedback" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
       
            </div>
            <div style="margin-top: 30px; margin-bottom: 40px;" class="tijara-content tj-personal-action">
            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">{{ __('lang.save_btn')}}</button>
           
            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn"> {{ __('lang.cancel_btn')}}</a>
            </div>
          </form>
    
        </div>
</div></div></div>
    </div>
</div>

  </div></div></div>
</div> <!-- /container -->

@endsection
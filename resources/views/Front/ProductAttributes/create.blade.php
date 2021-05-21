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
        <h2>Create Product Attributes</h2>
        <hr class="heading_line"/>
        <div class="login_box">
           <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('frontAttributeStore')}}" >
             {{ csrf_field() }}

            <div class="form-group">
              <label>Attribute Name <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" id="name" name="name" placeholder="Name" value="{{ old('name') }}" />
              <span class="invalid-feedback" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </span>
            </div>

            <div class="form-group">
              <label>Type <span class="de_col">*</span></label>
              <select class="form-control login_input" id="type" name="type">
                <option value="">Select Type</option>
                <option value="radio">Radio</option>  
                <option value="dropdown">Drop-Down</option>
                <option value="textbox">Textbox</option>
              </select>
              <span class="invalid-feedback" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
            </div>

            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn">Save</button>
           
            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn"> Cancel</a>
          </form>
    
        </div>
      </div>

    </div>
  </div>
</div> <!-- /container -->

@endsection
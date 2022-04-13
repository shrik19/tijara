@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('lang.add_attribute')}}</p>
   <form method="POST" action="{{route('adminAttributeStore')}}" class="needs-validation"    enctype="multipart/form-data" novalidate="">
   @csrf

   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group"> 
                  <label>{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('lang.attribute_label')}} " value="{{ old('name') }}" />
               <div class="text-danger" id="err_fname">
                  @if($errors->has('name')) {{ $errors->first('name') }}@endif
               </div>
               
             </div>
               <div class="form-group">
                  <label>{{ __('lang.attribute_value_label')}} <span class="de_col">*</span></label>
                  <select class="form-control login_input" id="type" name="type">
                  <option value="">{{ __('lang.select_label')}} </option>
                  <option value="radio">{{ __('lang.radio_label')}}</option>  
                  <option value="dropdown">{{ __('lang.dropdown_label')}}</option>
                  <option value="textbox">{{ __('lang.textbox_label')}}</option>
                  </select>
                  <div class="text-danger" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </div>
               </div> 
               
              </div>


         <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminBanner')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
@endsection('middlecontent')
@extends('Front.layout.template')
@section('middlecontent')
<style>
  .login_box
  {
    width:100% !important;
  }
</style>
<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
   @if($subscribedError)
      <div class="alert alert-danger">{{$subscribedError}}</div>
      @endif
  <form id="service-form" action="{{route('frontServiceStore')}}" method="post" enctype="multipart/form-data">
            @csrf
  <div class="row">


  <div class="col-md-10">

      <h2>{{ __('servicelang.service_form_label')}}</h2>
      <hr class="heading_line"/>
      </div>
      <div class="col-md-2 text-right" style="margin-top:30px;">
      <a href="{{route('manageFrontServices')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
      </div>

         @include ('Front.alert_messages')
      <div class="col-md-6">

        <div class="login_box">


            <input type="hidden" name="service_id" value="{{$service_id}}">

            <div class="form-group">
              <label>{{ __('servicelang.service_title_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="title" id="title" placeholder="{{ __('servicelang.service_title_label')}} " value="{{old('title')}}" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('servicelang.service_slug_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input slug-name" name="service_slug" id="service_slug" placeholder="{{ __('servicelang.service_slug_label')}} " value="{{old('service_slug')}}" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('service_slug')) {{ $errors->first('service_slug') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('lang.category_label')}}</label>
              <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="Select" tabindex="3">
                <option></option>
                @foreach($categories as $cat_id=>$category)
                <optgroup label="{{$category['maincategory']}}">
                <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
                @foreach($category['subcategories'] as $subcat_id=>$subcategory)
                  <option value="{{$subcat_id}}">{{$subcategory}}</option>
                @endforeach
                </optgroup>
                @endforeach
              </select>
              <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>


          <div class="form-group">
              <label>{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
              <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $max_seq_no}}" tabindex="7">
              <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.status_label')}} </label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="Select" tabindex="8" >
              <option value="active">{{ __('lang.active_label')}}</option>
              <option value="block">{{ __('lang.block_label')}}</option>
              </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
          </div>


        </div>
      </div>
      <div class="col-md-6">
        <div class="login_box">
            <div class="form-group">
              <label>{{ __('servicelang.service_description_label')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="description" id="description" placeholder="{{ __('lang.service_description_label')}}" value="" tabindex="2">{{old('description')}}</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
            </div>

        </div>
      </div>

  </div>
        <div class="col-md-12 text-center">&nbsp;</div>
      <div class="col-md-12 text-center">
          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveservice" tabindex="9">{{ __('lang.save_btn')}}</button>

          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
          </div>

  </form>
</div> <!-- /container -->
<script>var siteUrl="{{url('/')}}";</script>
<script type="text/javascript">
  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){

    var slug_name= inputText;
    var slug;
    $.ajax({
      url: "{{url('/')}}"+'/manage-services/check-slugname/?slug_name='+slug_name,
      type: 'get',
      async: false,
      data: { },
      success: function(output){
        slug = output;
      }
    });

    return slug;
  }

</script>
@endsection

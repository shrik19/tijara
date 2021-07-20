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
  <div class="row">
      @if($subscribedError)
      <div class="alert alert-danger">{{$subscribedError}}</div>
      @endif
      <form id="service-form" action="{{route('frontServiceStore')}}" method="post" enctype="multipart/form-data">
          @csrf

      <div class="col-md-10">

      <h2>{{ __('servicelang.service_form_label')}}</h2>
      <hr class="heading_line"/>
      </div>
      <div class="col-md-2 text-right" style="margin-top:30px;">
      <a href="{{route('manageFrontServices')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
      </div>
      <hr class="heading_line"/>
      @include ('Front.alert_messages')
      <div class="col-md-6">
        <div class="login_box">

          <input type="hidden" name="service_id" value="{{$service_id}}" id="service_id">
          <div class="form-group">
          <label>{{ __('servicelang.service_title_label')}} <span class="de_col">*</span></label>
          <input type="text" class="form-control login_input" name="title" id="title" placeholder="{{ __('servicelang.service_title_label')}}" value="{{ (old('title')) ?  old('title') : $service->title}}" onblur="convertToSlug(this)">
          <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
          </div>

          <div class="form-group">
          <label>{{ __('servicelang.service_slug_label')}} <span class="de_col">*</span></label>
          <input type="text" class="form-control login_input slug-name" name="service_slug" id="service_slug" placeholder="{{ __('servicelang.service_slug_label')}}" value="{{ (old('service_slug')) ?  old('service_slug') : $service->service_slug}}" onblur="checkUniqueSlugName()">
          <span class="invalid-feedback  slug-name-err" id="err_title" >@if($errors->has('service_slug')) {{ $errors->first('service_slug') }}@endif </span>
          </div>

            <div class="form-group">
            <label>{{ __('lang.category_label')}}</label>
            <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="Select" >
              <option></option>
            @foreach($categories as $cat_id=>$category)
              <optgroup label="{{$category['maincategory']}}">
              <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
              @foreach($category['subcategories'] as $subcat_id=>$subcategory)
              @if(in_array($subcat_id,$selectedCategories))
              <option selected="selected" value="{{$subcat_id}}">{{$subcategory}}</option>
              @else
              <option value="{{$subcat_id}}">{{$subcategory}}</option>
              @endif
              @endforeach
              </optgroup>
            @endforeach
            </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>



          <div class="form-group">
            <label>{{ __('lang.sort_order_label')}}<span class="de_col"></span></label>
            <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $service->sort_order}}">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.status_label')}}</label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="{{ __('lang.status_label')}}" >
              <option @if($service->status=='active') selected="selected" @endif value="active">Active</option>
              <option @if($service->status=='block') selected="selected" @endif value="block">Block</option>
            </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
          </div>
          <div class="form-group">
            <label>{{ __('lang.images')}} </label>
            <input type="file" class="form-control login_input image service_image" >
            <div class="images">
            @php
            $images = explode(',',$service->images);
            @endphp
            @if(!empty($images))
              @foreach($images as $image)
                @if($image!='')
                  <input type="hidden" class="form-control login_input hidden_images" value="{{$image}}"  name="hidden_images[]">
                  <img src="{{url('/')}}/uploads/ServiceImages/{{$image}}" width="40" height="40">
                  <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                @endif
              @endforeach
            @endif
            </div>
          </div>

        </div>
      </div>

      <div class="col-md-6">
        <div class="login_box">


          <div class="form-group">
              <label>{{ __('servicelang.service_description_label')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="description" id="description" placeholder="{{ __('servicelang.service_description_label')}} " value="">{{ (old('description')) ?  old('description') : $service->description}}</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
          </div>
        </div>
      </div>
    </div>

      <div class="col-md-12">&nbsp;</div>
      <div class="col-md-12 text-center">


          <button type="submit"  class="btn btn-black debg_color login_btn saveservice" tabindex="9">{{ __('lang.save_btn')}}</button>

          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>


          </div>

</form>
  </div>

  </div> <!-- /container -->


<script type="text/javascript">

  var siteUrl="{{url('/')}}";
</script>
<script type="text/javascript">
  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){

    var slug_name= inputText;
    var slug;
    var id = $("#service_id").val();
    $.ajax({
      url: "{{url('/')}}"+'/manage-services/check-slugname/?slug_name='+slug_name,
      type: 'get',
      async: false,
      data: {id:id },
      success: function(output){
        slug = output;
      }
    });

    return slug;
  }

</script>
@endsection

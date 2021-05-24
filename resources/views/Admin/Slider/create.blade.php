@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$current_module_name}}</h2>
  <p class="section-lead">{{ __('users.add_slider_details_title')}}</p>

  <div class="row">
    <div class="col-12 col-md-8 col-lg-8">
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('adminSliderStore')}}" enctype="multipart/form-data" >
            {{ csrf_field() }}

            <div class="form-group">
              <label>{{ __('users.title_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" placeholder="Slider Title" value="{{ old('title')}}" tabindex="1"/>
              <div class="text-danger">{{ ($errors->has('title')) ? $errors->first('title') : '' }}</div>
            </div>
            
            <div class="form-group">
              <label>{{ __('users.image_thead')}} <span class="text-danger">*</span></label>
              <input type="file" id="slider_image" name="slider_image" value="{{ old('slider_image')}}" tabindex="2"/><!-- style="color: #424a50;" -->
              <p class="slider-note" style="color:#000;font-size: 12px;">({{ __('users.image_upload_info')}})</p>
              <div class="text-danger">{{ ($errors->has('slider_image')) ? $errors->first('slider_image') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.link_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="link" name="link" placeholder="{{ __('users.link_thead')}}" value="{{ old('link')}}" tabindex="3"/>
              <div class="text-danger">{{ ($errors->has('link')) ? $errors->first('link') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.sequence_number_label')}} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder="{{ __('users.sequence_number_label')}}" value="{{ old('sequence_no')}}" tabindex="3"/>
              <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.description_label')}}</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"></textarea>
              <div class="text-danger">{{ ($errors->has('description')) ? $errors->first('description') : '' }}</div>
            </div>

            <div class="box-footer">
               <span class="pull-right">
                  <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
                  <a href="{{$module_url}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
               </span>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });
</script>
@endsection('middlecontent')
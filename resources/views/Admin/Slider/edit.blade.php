@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('users.edit_slider_details_title')}}</p>
   <form method="POST" action="{{route('adminSliderUpdate', $id)}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
   <!-- class="needs-validation" novalidate="" -->
   @csrf
   <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                  <label>{{ __('users.title_thead')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" placeholder="Slider Title" required tabindex="1" value="{{ (old('title')) ?  old('title') : $sliderDetails['title']}}">
                  <div class="invalid-feedback">
                    {{ __('errors.fill_in_slider_title_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div>

               <div class="form-group">
                 <label>{{ __('users.image_thead')}} </label>
                 <input type="file" id="slider_image" name="slider_image" value="" />
                 <p class="slider-note">({{ __('users.image_upload_info')}})</p>
                 @php
                 if(!empty($sliderDetails['sliderImage'])){
                     echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Slider/'.$sliderDetails['sliderImage'].'" style="width: 180px;height: 150px;padding-top: 20px;"></div>';
                  }
                  @endphp
               </div>

               <div class="form-group">
                  <label>{{ __('users.link_thead')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="link" id="link" placeholder="link" required tabindex="3" value="{{ (old('link')) ?  old('link') : $sliderDetails['link']}}">
                  <div class="invalid-feedback">
                   {{ __('errors.fill_in_slider_link_err')}}
                  </div>
                  <div class="text-danger">{{$errors->first('link')}}</div>
               </div>

              <div class="form-group">
                <label>{{ __('users.sequence_number_label')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder=" Sequence Number" value="{{ (old('sequence_no')) ?  old('sequence_no') : $sliderDetails['sequence_no']}}" tabindex="3"/>
                <div class="invalid-feedback">{{ __('errors.fill_in_slider_seq_no_err')}}
                  </div>
                <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
              </div>

               <div class="form-group">
                  <label>{{ __('users.description_label')}}</label>
                  <textarea class="form-control description" name="description" id="description" spellcheck="true" > <?php if(!empty($sliderDetails['description'])){ echo $sliderDetails['description']; }?></textarea>
                  <div class="invalid-feedback">{{ __('errors.fill_in_slider_description_err')}}
                  </div>
               </div>
            </div>
         </div>
      </div>
	 
	  <div class="col-12">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminSlider')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
   </div>
   </form>
</div>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });
</script>
@endsection('middlecontent')
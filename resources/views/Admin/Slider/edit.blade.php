@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">Edit Slider details.</p>
   <form method="POST" action="{{route('adminSliderUpdate', $id)}}" class="needs-validation" novalidate="" enctype="multipart/form-data">
   <!-- class="needs-validation" novalidate="" -->
   @csrf
   <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                  <label>Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" placeholder="Slider Title" required tabindex="1" value="{{ (old('title')) ?  old('title') : $sliderDetails['title']}}">
                  <div class="invalid-feedback">
                     Please fill in your Slider Title
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div>

               <div class="form-group">
                 <label>Slider Image </label>
                 <input type="file" id="slider_image" name="slider_image" value="" />
                 <p class="slider-note">Please uplaod slider image less than 2mb and Only jpeg,png,jpg,gif,svg file types allowed.</p>
                 @php
                 if(!empty($sliderDetails['sliderImage'])){
                     echo '<div class="col-md-4 existing-images"><img src="'.url('/').'/uploads/Slider/'.$sliderDetails['sliderImage'].'" style="width: 180px;height: 150px;padding-top: 20px;"></div>';
                  }
                  @endphp
               </div>

               <div class="form-group">
                  <label>Link <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="link" id="link" placeholder="link" required tabindex="3" value="{{ (old('link')) ?  old('link') : $sliderDetails['link']}}">
                  <div class="invalid-feedback">
                     Please fill in your Slider Link
                  </div>
                  <div class="text-danger">{{$errors->first('link')}}</div>
               </div>

              <div class="form-group">
                <label>Sequence Number <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder=" Sequence Number" value="{{ (old('sequence_no')) ?  old('sequence_no') : $sliderDetails['sequence_no']}}" tabindex="3"/>
                <div class="invalid-feedback">
                     Please fill in Slider Sequence Number
                  </div>
                <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
              </div>

               <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control description" name="description" id="description" spellcheck="true" > <?php if(!empty($sliderDetails['description'])){ echo $sliderDetails['description']; }?></textarea>
                  <div class="invalid-feedback">
                     Please fill in your Description
                  </div>
               </div>
            </div>
         </div>
      </div>
	 
	  <div class="col-12">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
            <a href="{{route('adminSlider')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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
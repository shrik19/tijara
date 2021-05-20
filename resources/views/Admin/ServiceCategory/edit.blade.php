@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">Edit Service Category details.</p>
   <form method="POST" action="{{route('adminServiceCatUpdate', $id)}}" class="needs-validation" novalidate="">
   @csrf
   <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
         <div class="card">
           
            <div class="card-body">
               <div class="form-group">
                  <label>Category Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" id="name" required tabindex="1" value="{{ (old('name')) ?  old('name') : $categoryDetails['category_name']}}" onblur="allLetter(this)">
                  <div class="invalid-feedback">
                     Please fill in your Category Name
                  </div>
                  <div class="text-danger err-letter">{{$errors->first('name')}}</div>
               </div>
               
               <div class="form-group">
                <label>Sequence Number <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder=" Sequence Number" value="{{ (old('sequence_no')) ?  old('sequence_no') : $categoryDetails['sequence_no']}}" tabindex="3"/>
                <div class="invalid-feedback">
                     Please fill in Slider Sequence Number
                  </div>
                <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
              </div>

              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control description" name="description" id="description" spellcheck="true" > <?php if(!empty($categoryDetails['description'])){ echo $categoryDetails['description']; }?></textarea>
              </div>
            </div>
         </div>
      </div>
	 
	    <div class="col-12">
         <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
         <a href="{{route('adminServiceCat')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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

  /*function to validate letters for category name*/
  function allLetter(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      $('.err-letter').text('Please input alphabet characters only');
      return false;
    }
  }
</script>
@endsection('middlecontent')
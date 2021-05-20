@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$current_module_name}}</h2>
  <p class="section-lead">Add Service Category details.</p>

  <div class="row">
    <div class="col-12 col-md-8 col-lg-8">
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('adminServiceCatStore')}}" >
            {{ csrf_field() }}

            <div class="form-group">
              <label>Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Category Name" value="{{ old('name') }}" onblur="allLetter(this)" />
              <div class="text-danger err-letter">{{ ($errors->has('name')) ? $errors->first('name') : '' }}</div>
            </div>

            <div class="form-group">
              <label>Sequence Number <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder=" Sequence Number" value="{{ old('sequence_no')}}" tabindex="3"/>
              <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
            </div>
            
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" > <?php if(!empty($categoryDetails['description'])){ echo $categoryDetails['description']; }?></textarea>
              <div class="text-danger">{{ ($errors->has('description')) ? $errors->first('description') : '' }}</div>
            </div>

            <div class="box-footer">
               <span class="pull-right">
                 <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
                  <a href="{{$module_url}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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
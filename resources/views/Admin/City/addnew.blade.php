@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p>Add City Details</p>
   <form method="POST" action="{{route('adminCityStore')}}" name="form" class="needs-validation">      
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                  <label>City Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="cityName" id="cityName" required tabindex="1" value="" onblur="allLetter(this)">
                  <div class="invalid-feedback">
                     Please fill in City Name
                  </div>
                  <div class="text-danger err-letter">{{$errors->first('cityName')}}</div>
               </div> 
            </div>
            <div class="col-12 col-md-12 col-lg-12">
            <div class="card">

            </div>
            <div class="col-12 ">
               <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="2"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
               <a href="{{route('adminCity')}}" class="btn btn-icon icon-left btn-danger" tabindex="3"><i class="fas fa-times"></i> Cancel</a>
            </div>
            </div>
         </div>
      </div>
   </div>
   </form>
</div>
<script type="text/javascript">

   /*function to validate letters for city*/
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
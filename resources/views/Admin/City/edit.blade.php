@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p>Edit City Details</p>
   <form method="POST" action="{{route('adminCityUpdate', $id)}}" class="needs-validation" novalidate="">

   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                  <label>City Name</label>
                  <input type="text" class="form-control" name="city" id="city" required tabindex="1" value="{{ (old('city')) ?  old('city') : $cityData[0]->name}}" onblur="allLetter(this)">
                  <div class="invalid-feedback">
                     Please fill in City Name
                  </div>
                  <div class="text-danger err-letter">{{$errors->first('city')}}</div>
               </div> 
            </div>
			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
            <a href="{{route('adminCity')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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
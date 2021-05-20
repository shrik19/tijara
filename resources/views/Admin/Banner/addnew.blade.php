@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">Add Banner Details.</p>
   <form method="POST" action="{{route('adminBannerAddnew')}}" class="needs-validation"    enctype="multipart/form-data" novalidate="">
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group"> 
                  <label>Select Page  <span class="text-danger">*</span></label>
                  <select class="form-control" id="display_on_page" name="display_on_page" tabindex="1" required>
                     <option value="">Display On Page</option>
                     <option value="Home">Home</option>
                     <option value="Login">Login</option>
                     <option value="Register">Register</option>
                     <option value="My Account">My Account</option>
                     <option value="Product List">Product List</option>
                     <option value="Shopping Cart">Shopping Cart</option>
                     <option value="About Us">About Us</option>
                     <option value="Contact Us">Contact Us</option>
                  </select>
               <div class="invalid-feedback">
                  Please select page
               </div>
               <div class="text-danger">{{$errors->first('display_on_page')}}</div>
             </div>
               <div class="form-group">
                  <label>Title  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" required tabindex="2" value="{{ old('title')}}">
                  <div class="invalid-feedback">
                     Please fill in Title 
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div> 
               
               <div class="form-group">
                  <label>Link  <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="redirect_link" id="redirect_link" required tabindex="3" value="{{ old('redirect_link')}}">
                  <div class="invalid-feedback">
                     Please fill Link 
                  </div>
                  <div class="text-danger">{{$errors->first('redirect_link')}}</div>
               </div>
              
               <div class="form-group">
                  <label>Image  <span class="text-danger">*</span></label>
                   <input type="file" name="image" class="form-control" tabindex="4" value="{{ old('image')}}" required>
                  <p>Please uplaod slider image less than 2mb and Only jpeg,png,jpg,gif,svg file types allowed.</p>
                  <div class="invalid-feedback">
                     Please Upload Image
                  </div>
                  <div class="text-danger">{{$errors->first('image')}}</div>
               </div> </div>


			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
            <a href="{{route('adminBanner')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
@endsection('middlecontent')
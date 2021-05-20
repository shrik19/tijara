@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">Edit Banner Details.</p>
   <form method="POST" action="{{route('adminBannerUpdate', $id)}}" class="needs-validation" enctype="multipart/form-data"  novalidate="">
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                   <label>Select Page  <span class="text-danger">*</span></label>
                  <select class="form-control" id="display_on_page" name="display_on_page" tabindex="1">
                     <option value="" >Display On Page</option>                 
                     <option value="Home" <?php if($sliderData[0]->display_on_page=="Home"){ echo "selected"; } ?>>Home</option>
                     <option value="Login" <?php if($sliderData[0]->display_on_page=="Login"){ echo "selected"; } ?>>Login</option>
                     <option value="Register" <?php if($sliderData[0]->display_on_page=="Register"){ echo "selected"; } ?>>Register</option>
                     <option value="My Account" <?php if($sliderData[0]->display_on_page=="My Account"){ echo "selected"; } ?>>My Account</option>
                     <option value="Product List" <?php if($sliderData[0]->display_on_page=="Product List"){ echo "selected"; } ?>>Product List</option>
                     <option value="Shopping Cart" <?php if($sliderData[0]->display_on_page=="Shopping Cart"){ echo "selected"; } ?>>Shopping Cart</option>
                     <option value="About Us" <?php if($sliderData[0]->display_on_page=="About Us"){ echo "selected"; } ?>>About Us</option>
                     <option value="Contact Us" <?php if($sliderData[0]->display_on_page=="Contact Us"){ echo "selected"; } ?>>Contact Us</option>
                  </select>
                </div>
               <div class="form-group">
                  <label>Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="title" id="title" required tabindex="2" value="{{ (old('title')) ?  old('title') : $sliderData[0]->title}}">
                  <div class="invalid-feedback">
                     Please fill Title
                  </div>
                  <div class="text-danger">{{$errors->first('title')}}</div>
               </div> 

                  <div class="form-group">
                  <label>Link <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="redirect_link" id="redirect_link" required tabindex="3" value="{{ (old('redirect_link')) ?  old('redirect_link') : $sliderData[0]->redirect_link}}">
                  <div class="invalid-feedback">
                     Please fill Link 
                  </div>
                  <div class="text-danger">{{$errors->first('redirect_link')}}</div>
               </div>
                <div class="form-group incrementerr clonedprofile">
                  <label>Image <span class="text-danger">*</span></label>
                  @php
                     if(!empty($sliderData[0]->image))
                     {
                        echo '<div class="row">';
                        
                           echo '<div class="col-md-4 existing-imagesprofile"><img src="'.url('/').'/uploads/Banner/resized/'.$sliderData[0]->image.'" style="width:200px;height:200px;"></div>';
                        echo '</div>';
                        echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
                     }
                  @endphp
                 
                  <input type="file" name="image" class="form-control" tabindex="4">
                   <p>Please uplaod slider image less than 2mb and Only jpeg,png,jpg,gif,svg file types allowed.</p>
                  <div class="input-group-btn text-right"> 
                  </div>
               </div>
            </div>
			   <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
            <a href="{{route('adminBanner')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
@endsection('middlecontent')
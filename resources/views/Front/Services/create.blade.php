@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="">
	<h2>Save Product Details</h2>
        <hr class="heading_line"/>
         @include ('Front.alert_messages')
      <div class="col-md-6">
        
        <div class="login_box">
         
          <form id="product-form" action="{{route('frontServiceStore')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{$product_id}}">

            <div class="form-group">
              <label>Title <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="title" id="title" placeholder="Title" value="{{old('title')}}" tabindex="1">
              <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
            </div>

      			<div class="form-group">
              <label>Description <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="description" id="description" placeholder="Description" value="" tabindex="2">{{old('description')}}</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
            </div>
        </div>
      </div>
      <div class="col-md-6">  
        <div class="login_box">       
          <div class="form-group">
              <label>Category</label>
              <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="Select" tabindex="3">
                <option></option>
                @foreach($categories as $cat_id=>$category)
                <optgroup label="{{$category['maincategory']}}">
                <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
                @foreach($category['subcategories'] as $subcat_id=>$subcategory)			  
                  <option value="{{$subcat_id}}">{{$subcategory}}</option>
                @endforeach
                </optgroup>
                @endforeach
              </select>
              <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>
			   
          <div class="form-group">
            <label>Meta Title <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_title" id="meta_title" placeholder="Meta title" value="{{old('meta_title')}}" tabindex="4">
            <span class="invalid-feedback" id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
          </div>
			
    			<div class="form-group">
            <label>Meta Description <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_description" id="meta_description" placeholder="Meta description" value="{{old('meta_description')}}" tabindex="5">
            <span class="invalid-feedback" id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
          </div>
			
    			<div class="form-group">
            <label>Meta keyword (Comma Separated) <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_keyword" id="meta_keyword" placeholder="Meta keyword" value="{{old('meta_keyword')}}" tabindex="6">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
          </div>
           
    		  <div class="form-group">
              <label>Sort Order<span class="de_col"></span></label>
              <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="Sort order" value="{{old('sort_order')}}" tabindex="7">
              <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>
			
          <div class="form-group">
            <label>Status</label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="Select" tabindex="8" >
              <option value="active">Active</option>
              <option value="block">Block</option>
              </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
          </div>

          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">Save</button>
           
          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection
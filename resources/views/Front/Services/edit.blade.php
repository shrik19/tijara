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
          <form id="product-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="product_id" value="{{$product_id}}">
          <div class="form-group">
          <label>Title <span class="de_col">*</span></label>
          <input type="text" class="form-control login_input" name="title" id="title" placeholder="Title" value="{{ (old('title')) ?  old('title') : $product->title}}">
          <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
          </div>

          <div class="form-group">
          <label>Description <span class="de_col"></span></label>
          <textarea class="form-control login_input" name="description" id="description" placeholder="Description" value="">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
          <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="login_box">
          <div class="form-group">
            <label>Category</label>
            <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="Select" >
              <option></option>
            @foreach($categories as $cat_id=>$category)
              <optgroup label="{{$category['maincategory']}}">
              <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
              @foreach($category['subcategories'] as $subcat_id=>$subcategory)	
              @if(in_array($subcat_id,$selectedCategories))
              <option selected="selected" value="{{$subcat_id}}">{{$subcategory}}</option>
              @else
              <option value="{{$subcat_id}}">{{$subcategory}}</option>
              @endif
              @endforeach
              </optgroup>
            @endforeach
            </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>

          <div class="form-group">
            <label>Meta Title <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_title" id="meta_title" placeholder="Meta title" value="{{(old('meta_title')) ?  old('meta_title') : $product->meta_title}}">
            <span class="invalid-feedback" id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
          </div>

          <div class="form-group">
            <label>Meta Description <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_description" id="meta_description" placeholder="Meta description" value="{{(old('meta_description')) ?  old('meta_description') : $product->meta_description}}">
            <span class="invalid-feedback" id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
          </div>

          <div class="form-group">
            <label>Meta keyword (Comma Separated) <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_keyword" id="meta_keyword" placeholder="Meta keyword" value="{{(old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword}}">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
          </div>

          <div class="form-group">
            <label>Sort Order<span class="de_col"></span></label>
            <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="Sort order" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>

          <div class="form-group">
            <label>Status</label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="Select" >
              <option @if($product->status=='active') selected="selected" @endif value="active">Active</option>
              <option @if($product->status=='block') selected="selected" @endif value="block">Block</option>
            </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
          </div>
          <button class="btn btn-black debg_color login_btn saveproduct">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="">
      <h2>Product Attribute Details</h2>
      <hr class="heading_line"/>
      @include ('Front.alert_messages')
      <div class="col-md-12">
        <div class="login_box">

           <!-- <div class="form-group">
            <label>Attributes Name</label>
            <div class="field_wrapper">
              <input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%">
            </div>
            <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top: 20px;margin-left: 20px;font-size: 20px;">+</button>
          </div> -->
          <div class="table-responsive">
            <table class="table table-striped" id="productTable">
            <thead>
              <tr>
                <th>Attribute Name</th>         
                <th>Attribute Value</th>
                <th>SKU</th>
                <th>Weight</th>         
                <th>Price</th>
                <th>Image</th>
                <th data-orderable="false">Action</th>
              </tr>
            </thead>
            <tbody class="field_wrapper">
              <!-- <div > -->
              <tr>
                <td>
               <!--    <div class="form-group"> -->
                <select class="form-control" name="product_attribute[]">
                  <option>Select Attribute</option>
                    @foreach ($attribute as $attr)
                      <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                    @endforeach
                </select>
                    <!-- <input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value=""> -->
               <!--    </div> -->
                  
                

                </td>
                <td>a</td>
                <td>b</td>
                <td>c</td>
                <td>d</td>
                <td>e</td>
                <td>
                  <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top: 20px;margin-left: 20px;font-size: 20px;">+</button>
                </td>
              </tr>
<!-- </div> -->
            </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
   var addButton = $('.add_button'); //Add button selector
   var wrapper = $('.field_wrapper'); //Input field wrapper
   var valueHTML = '<tr><td><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" ></td><td>a</td><td>b</td><td>c</td><td>d</td><td>e</td><td><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></td></tr>'; //New input field html 

   var x = 1; //Initial field counter is 1
   //Once add button is clicked
   $(document).on("click", ".add_button", function () {
   // $(addButton).click(function(){
       var valueHTML = '<tr><td><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value=""></td><td>a</td><td>b</td><td>c</td><td>d</td><td>e</td><td><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></td></tr>'; //New input field html 
         x++; //Increment field counter
            $(wrapper).append(valueHTML); //Add field html
       
    });
   $(wrapper).on('click', '.remove_button', function(e){

      var textbox_value = $(this).prev().prev().attr("id");  
      if(textbox_value){
         
         var split = textbox_value.split("_");
         id = split[2];
         if(id !== null && id !== '') {
        
         $.ajax({
            url: "{{url('/')}}"+'/product-attributes/deleteAttributeValue',
            type: 'POST',
            data: {
               "_token": "{{ csrf_token() }}",
               "id":id
            },
            success: function(output){
               console.log(output);
               $("#attribute_values_" + id).fadeOut('slow');
               $("#remove_button_"+id).fadeOut('slow');


            } 
          });
       }
      }else{
          e.preventDefault();
         $(this).parent('div').remove(); //Remove field html
         x--; //Decrement field counter
      }

    });
  
</script>

@endsection
@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">Save Product Details</p>
  <form method="POST" action="{{route('adminProductStore')}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="{{ old('title')}}" >
              <div class="invalid-feedback">
                Please fill in Title
              </div>
              <div class="text-danger">{{$errors->first('title')}}</div>
            </div>
			
			

            <div class="form-group">
              <label>Sell Price <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="sell_price" id="sell_price" required tabindex="2" value="{{ old('sell_price')}}">
              <div class="invalid-feedback">
                Please fill in Sell Price
              </div>
              <div class="text-danger">{{$errors->first('sell_price')}}</div>
            </div>
			<div class="form-group">
              <label>List Price <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="list_price" id="list_price"  tabindex="2" value="{{ old('list_price')}}">
              <div class="invalid-feedback">
                Please fill in List Price
              </div>
              <div class="text-danger">{{$errors->first('list_price')}}</div>
            </div>

			<div class="form-group">
              <label>User <span class="text-danger">*</span></label>
              <select id="user_id" name="user_id" class="form-control select2" >
			  
			  <option value="">Select</option>
			  @foreach($users as $user)
				<option value="{{$user->id}}">{{$user->fname}} {{$user->lname}}</option>
			  @endforeach
			  </select>
              <div class="invalid-feedback">
                Please fill in User
              </div>
              <div class="text-danger">{{$errors->first('user_id')}}</div>
            </div>
			
			<div class="form-group">
              <label>Categories <span class="text-danger">*</span></label>
              <select multiple id="categories" name="categories[]" class="form-control select2" >
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
              <div class="invalid-feedback">
                Please fill in User
              </div>
              
            </div>
			
			
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">

           
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" tabindex="4"></textarea>
              <div class="text-danger">{{ ($errors->has('description')) ? $errors->first('description') : '' }}</div>
            </div>

            <div class="form-group increment cloned">
              <label>Product Image(s)</label>
              @php
              if(!empty($imagedetails['getImages']))
              {
                echo '<div class="row">';

                foreach($imagedetails['getImages'] as $image)
                {
                  $path = public_path().'/uploads/ProductsImages/'.$image['image'];
                  if (file_exists($path)) {
                  echo '<div style="margin-left:20px;" class="col-md-4 existing-images"><img src="'.url('/').'/uploads/ProductsImages/'.$image['image'].'" style="width:140px;height:140px;"><div style="margin-top:10px;margin-bottom:20px;"><a href="javascript:void(0)" onclick="return ConfirmDeleteFunction1(\''.route('agentImageDelete', base64_encode($image['id'])).'\');"  title="Delete" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a></div></div>';
                  }
                }
                echo '</div>';
                echo '<div class="row"><div class="col-md-12">&nbsp;</div></div>';
              }
              @endphp

              <input type="file" name="productimages[]"  tabindex="11" class="form-control">

              <div class="input-group-btn text-right"> 
                <button class="btn btn-success add-image" type="button"><i class="fas fa-plus"></i> Add More</button>
              </div>
            </div>

            <div class="clone hide" style="display:none;" >
              <div class="form-group cloned" style="margin-top:10px">
                <input type="file" name="productimages[]" class="form-control">
                <div class="input-group-btn text-right"> 
                  <button class="btn btn-danger remove-image" type="button"><i class="fas fa-trash-alt"></i> Remove</button>
                </div>
              </div>
            </div>
            <div class="text-danger cloned-danger">{{$errors->first('productimages')}}</div>
          </div>
        </div>
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
          <a href="{{route('adminProduct')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript" src="{{url('/')}}/assets/admin/js/select2.full.min.js"></script>
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/admin/css/select2.css">


<script type="text/javascript">
  
  $(document).ready(function() {
	  
	  $('#user_id').select2({
		
		});
		$('#categories').select2({
		placeholder:"select"
		});
		
    $(".add-image").click(function(){ 
      var existing_images = $(".existing-images").length;
      var cloned_images = $(".cloned:visible").length;

      if((existing_images + cloned_images) >= 5) {
        $(".cloned-danger").html('Max 5 images are allowed for Product.');
        return false;
      }

      var html = $(".clone").html();
      $(".increment").after(html);
    });

    $("body").on("click",".remove-image",function(){ 
      $(this).parents(".form-group").remove();
      $(".cloned-danger").html('')
    });
  });
</script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });


/*function to check unique store name
* @param : store name
*/

</script>
@endsection('middlecontent')
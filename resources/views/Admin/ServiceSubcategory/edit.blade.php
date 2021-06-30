@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('users.edit_service_subcat_info')}}</p>

   <form method="POST" action="{{route('adminServiceSubcatUpdate', $id)}}" class="needs-validation" novalidate="">
   @csrf
   <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
         <div class="card">
            <div class="card-body">
               <div class="form-group">
                  <label>{{ __('users.category_name_thead') }} </label>
                  <input type="text" class="form-control" name="name" id="name" tabindex="1" value="{{ (old('name')) ?  old('name') : $categoryDetails['category_name']}}" readonly>
                  <input type="hidden" class="form-control" name="hid_subCategory" id="hid_subCategory" tabindex="1" value="{{ (old('hid_subCategory')) ?  old('hid_subCategory') : $categoryDetails['id']}}">

                  <div class="invalid-feedback">
                    {{ __('errors.category_name_req')}}
                  </div>
                  <div class="text-danger">{{$errors->first('name')}}</div>
               </div>

               <div class="form-group">
                  <label>{{ __('users.subcategory_name_label')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="subcategory_name" id="subcategory_name" tabindex="1" value="{{ (old('subcategory_name')) ?  old('subcategory_name') : $categoryDetails['subcategory_name']}}" onblur="convertToSlug(this)" >
                   <input type="hidden" name="hid" id="hid"  value="{{ $id }}">
                  <div class="invalid-feedback">
                     {{ __('errors.subcategory_name_req')}}
                  </div>
                  <div class="text-danger err-letter">{{$errors->first('subcategory_name')}}</div>
               </div>

               <div class="form-group">
                <label>{{ __('users.sequence_number_label')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sequence_no" name="sequence_no" value="{{ (old('sequence_no')) ?  old('sequence_no') : $categoryDetails['sequence_no']}}" tabindex="3"/>
                <div class="invalid-feedback">
                     {{ __('errors.sequence_number_err')}}
                  </div>
                <div class="text-danger">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
              </div>

              <div class="form-group">
                <label>{{ __('users.subcategory_slug_label')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control slug-name subcategory_slug" id="subcategory_slug" name="subcategory_slug" value="{{ (old('subcategory_slug')) ?  old('subcategory_slug') : $categoryDetails['subcategory_slug']}}" tabindex="3" readonly="readonly" />
                <div class="invalid-feedback">
                     {{ __('errors.subcategory_slug_req')}}
                  </div>
                <div class="text-danger slug-name-err">{{ ($errors->has('subcategory_slug')) ? $errors->first('subcategory_slug') : '' }}</div>
              </div>

            </div>
         </div>
      </div>
	 
	  <div class="col-12">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminServiceCat')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
   </div>
   </form>
</div>
<script type="text/javascript">
    
  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){
    var slug_name= inputText;   
    var id = $("#hid").val();
    var slug; 
    
     $.ajax({
      url: "{{url('/')}}"+'/admin/ServiceSubcategory/check-slugname/?slug_name='+slug_name,
      type: 'get',
      async: false,
      data: { id:id},
      success: function(output){
        slug = output;
      }
    });

     return slug;
  }
  
</script>
@endsection('middlecontent')
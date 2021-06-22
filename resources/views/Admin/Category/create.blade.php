@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$current_module_name}}</h2>
  <p class="section-lead">{{ __('users.add_product_category_details_info')}}</p>

  <div class="row">
    <div class="col-12 col-md-8 col-lg-8">
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="product_cat_form" action="{{route('adminCategoryStore')}}" >
            {{ csrf_field() }}

            <div class="form-group">
              <label>{{ __('users.name_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('users.category_name_thead')}}" value="{{ old('name') }}" onblur="convertToSlug(this)" />
              <div class="text-danger err-letter">{{ ($errors->has('name')) ? $errors->first('name') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.sequence_number_label')}} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="sequence_no" name="sequence_no" placeholder="{{ __('users.sequence_number_label')}}" value="{{ ($max_seq_no ?  $max_seq_no : '')}}" tabindex="3"/>
              <div class="text-danger err-seqno">{{ ($errors->has('sequence_no')) ? $errors->first('sequence_no') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.category_slug_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control slug-name" id="category_slug" name="category_slug" placeholder="{{ __('users.category_slug_label')}}" value="{{ old('category_slug')}}" tabindex="3" onblur="checkUniqueSlugName()"/>
              <div class="text-danger slug-name-err">{{ ($errors->has('category_slug')) ? $errors->first('category_slug') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.description_label')}}</label>
              <textarea class="form-control description" name="description" id="description" spellcheck="true" value="{{old('address')}}"></textarea>
            </div>
            
            <div class="box-footer">
               <span class="pull-right">
                <button type="submit" class="btn btn-icon icon-left btn-success product_cat_btn" tabindex="15"><i class="fas fa-check"></i>{{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
                <a href="{{$module_url}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i>{{ __('lang.cancel_btn')}}</a>
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

  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){

    if(inputText == undefined){
      var slug_name = $("#category_slug").val()
    }else{
      var slug_name= inputText;
    }
    
     $.ajax({
      url: "{{url('/')}}"+'/admin/category/check-slugname/?slug_name='+slug_name,
      type: 'get',
      data: { },
      success: function(output){
        if(output !=''){
          $('.slug-name-err').text(output);
        }else{
           $('.slug-name-err').text('');
        }
      }
    });
  }
</script>
@endsection('middlecontent')
  @extends('Admin.layout.template')
  @section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{ __('users.add_page_details_title')}}</p>

  <form method="POST" action="{{route('adminPageStore')}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <label>{{ __('users.title_thead')}}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="">
            <div class="invalid-feedback">
             {{ __('errors.fill_in_title')}}
            </div>
            <div class="text-danger">{{$errors->first('title')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.description_label')}}<span class="text-danger">*</span></label>
             <textarea class="form-control description" name="description" id="description" spellcheck="true" required ></textarea>
             <div class="invalid-feedback">
             {{ __('errors.fill_in_page_content')}}
            </div>
            <div class="text-danger">{{$errors->first('description')}}</div>
          </div>


          <div class="form-group">
            <label>{{ __('users.page_title_en')}}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title_en" id="title_en" required tabindex="1" value="">
            <div class="invalid-feedback">
             {{ __('errors.fill_in_title_en')}}
            </div>
            <div class="text-danger">{{$errors->first('title_en')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.description_label_en')}}<span class="text-danger">*</span></label>
             <textarea class="form-control description_en" name="description_en" id="description_en" spellcheck="true" required ></textarea>
             <div class="invalid-feedback">
             {{ __('errors.fill_in_page_content_en')}}
            </div>
            <div class="text-danger">{{$errors->first('description_en')}}</div>
          </div>

           <div class="form-group"> 
              <label>{{ __('users.select_section_label')}}  <span class="text-danger">*</span></label>
              <select class="form-control" id="display_in_section" name="display_in_section" tabindex="1" >
                 <option value="">{{ __('users.select_section_label')}}</option>
                 <option value="Sell">{{ __('users.footer_sell_label')}}</option>
                 <option value="About Tijara">{{ __('lang.about_tijara_label')}}</option>
                 <option value="Customer service">{{ __('lang.customer_service_label')}}</option>
              </select>
           <div class="invalid-feedback">
              {{ __('errors.display_in_section')}}
           </div>
           <div class="text-danger">{{$errors->first('display_in_section')}}</div>
         </div>

          <div class="form-group">
            <label>{{ __('lang.status_thead')}}</label>
            <select class="form-control" name="status">
              <option value="active">{{ __('lang.active_label')}}</option>
              <option value="block" selected="selected">{{ __('lang.inactive_label')}}</option>
            </select>
            <div class="invalid-feedback">
            {{ __('errors.select_status_err')}}
            </div>
            <div class="text-danger">{{$errors->first('status')}}</div>
          </div>

          <div class="col-12 text-right">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i>{{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminPage')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i>{{ __('lang.cancel_btn')}}</a>
          </div>
        </div>
      </div>
      </div>
    </div>
  </form>
</div>
<!-- <link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.description').richText();
      $('.description_en').richText();
  });
</script> -->
<script src="{{url('/')}}/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  
  if($("#faq").val()!="vanliga-fragor"){
    CKEDITOR.replace('description', {
        filebrowserUploadUrl: '{{ route('adminCkupload') }}',
        filebrowserUploadMethod: 'form',
        allowedContent : true,
        extraAllowedContent: 'main; summary;' 


    });

    CKEDITOR.replace('description_en', {
        filebrowserUploadUrl: '{{ route('adminCkupload') }}',
        filebrowserUploadMethod: 'form',
        allowedContent : true,
        extraAllowedContent: 'main; summary;' 


    });
  }else{
    $("#description").css("height","400");
    $("#description_en").css("height","400");
  }
  
</script>
@endsection('middlecontent')

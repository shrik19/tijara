@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{ __('users.edit_page_details_title')}}</p>
  <form method="POST" action="{{route('adminPageUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.title_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="{{ (old('title')) ?  old('title') : $PageDetails['title']}}">
              <div class="invalid-feedback">
               {{ __('errors.fill_in_title')}}
              </div>
              <div class="text-danger">{{$errors->first('title')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.description_label')}}</label>
           <!--    <textarea class="form-control" id="description" name="description" rows="2" cols="30" style="height:auto" tabindex="2" required><?php if(!empty($PageDetails['description'])){ echo $PageDetails['description']; }?></textarea> -->
               <textarea class="form-control description" name="description" id="description" spellcheck="true" required><?php if(!empty($PageDetails['contents'])){ echo $PageDetails['contents']; }?></textarea>
               <div class="invalid-feedback">
               {{ __('errors.fill_in_page_content')}}
              </div>
              <div class="text-danger">{{$errors->first('description')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.page_title_en')}}<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title_en" id="title_en" required tabindex="1" value="{{ (old('title_en')) ?  old('title_en') : $PageDetails['title_en']}}">
              <div class="invalid-feedback">
               {{ __('errors.fill_in_title_en')}}
              </div>
              <div class="text-danger">{{$errors->first('title_en')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.description_label_en')}}<span class="text-danger">*</span></label>
               <textarea class="form-control description_en" name="description_en" id="description_en"spellcheck="true" required > <?php if(!empty($PageDetails['contents_en'])){ echo $PageDetails['contents_en']; }?></textarea>
               <div class="invalid-feedback">
               {{ __('errors.fill_in_page_content_en')}}
              </div>
              <div class="text-danger">{{$errors->first('description_en')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('lang.status_thead')}}</label>
              <select class="form-control" name="status">
                <option value="active"  <?php if($PageDetails['status'] == 'active'){ echo 'selected'; } ?>>{{ __('lang.active_label')}}</option>
                <option value="block" <?php if($PageDetails['status'] == 'block'){ echo 'selected'; } ?>>{{ __('lang.inactive_label')}}</option>
              </select>
              <div class="invalid-feedback">
                {{ __('errors.select_status_err')}}
              </div>
              <div class="text-danger">{{$errors->first('status')}}</div>
            </div>

            <div class="col-12 text-right">
              <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
              <a href="{{route('adminPage')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="{{url('/')}}/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	
	CKEDITOR.replace('description', {
        filebrowserUploadUrl: '{{ route('adminCkupload') }}',
        filebrowserUploadMethod: 'form'
    });
	
	CKEDITOR.replace('description_en', {
        filebrowserUploadUrl: '{{ route('adminCkupload') }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@endsection('middlecontent')


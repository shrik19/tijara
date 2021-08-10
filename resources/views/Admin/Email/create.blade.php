  @extends('Admin.layout.template')
  @section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{ __('users.add_email_details_title')}}</p>

  <form method="POST" action="{{route('adminEmailStore')}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
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
            <label>{{ __('users.email_subject')}}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="subject" id="subject" required tabindex="1" value="">
            <div class="invalid-feedback">
             {{ __('errors.fill_in_subject')}}
            </div>
            <div class="text-danger">{{$errors->first('subject')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.content_label')}}<span class="text-danger">*</span></label>
             <textarea class="form-control description" name="contents" id="contents" spellcheck="true" required ></textarea>
             <div class="invalid-feedback">
             {{ __('errors.fill_in_email_content')}}
            </div>
            <div class="text-danger">{{$errors->first('contents')}}</div>
          </div>


          <div class="form-group">
            <label>{{ __('users.email_subject_en')}}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="subject_en" id="subject_en" required tabindex="1" value="">
            <div class="invalid-feedback">
             {{ __('errors.fill_in_subject_en')}}
            </div>
            <div class="text-danger">{{$errors->first('subject_en')}}</div>
          </div>

          <div class="form-group">
            <label>{{ __('users.content_label_en')}}<span class="text-danger">*</span></label>
             <textarea class="form-control description_en" name="contents_en" id="contents_en" spellcheck="true" required ></textarea>
             <div class="invalid-feedback">
             {{ __('errors.fill_in_email_content_en')}}
            </div>
            <div class="text-danger">{{$errors->first('contents_en')}}</div>
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
            <a href="{{route('adminEmail')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i>{{ __('lang.cancel_btn')}}</a>
          </div>
        </div>
      </div>
      </div>
    </div>
  </form>
</div>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.description').richText();
    $('.description_en').richText();
  });
</script>
@endsection('middlecontent')

@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$current_module_name}}</h2>
  <p class="section-lead">{{ __('users.add_site_content_info')}}</p>

  <div class="row">

    <div class="col-12 col-md-8 col-lg-8">
           @include('Admin.alert_messages')
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('adminSettingStore')}}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
              <label> {{ __('users.site_title_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="site_title" name="site_title" placeholder="Site Title" value="{{ (old('site_title')) ?  old('site_title') : $sitedata[0]->site_title}}" />
              <div class="text-danger">{{ ($errors->has('site_title')) ? $errors->first('site_title') : '' }}</div>
            </div>

            <?php /*
            <div class="form-group" style="display: none">
              <label>{{ __('users.footer_details_label')}}  <span class="text-danger">*</span></label>
              <textarea class="form-control description" name="footer_address" id="footer_address" spellcheck="true" >{{ (old('footer_address')) ?  old('footer_address') : $sitedata[0]->footer_address}}</textarea>
              <div class="text-danger">{{ ($errors->has('footer_address')) ? $errors->first('footer_address') : '' }}</div>
            </div>
            */?>
            <div class="form-group">
              <label>{{ __('users.upload_header_logo_label')}}  <span class="text-danger"></span></label>				<img src="{{url('/')}}/uploads/Images/{{$sitedata[0]->header_logo}}" height="50">
               <input type="file" name="header_logo" class="form-control" value="{{old('header_logo')}}">
               ( {{ __('users.header_logo_size_info')}} )
              <div class="text-danger err-letter">{{ ($errors->has('header_logo')) ? $errors->first('header_logo') : '' }}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.upload_footer_logo_label')}}  <span class="text-danger"></span></label>				<img src="{{url('/')}}/uploads/Images/{{$sitedata[0]->footer_logo}}" height="50">
               <input type="file" name="footer_logo" class="form-control" value="{{old('footer_logo')}}">
               <p>( {{ __('users.footer_logo_size_info')}} ) </p>
              <div class="text-danger err-letter">{{ ($errors->has('footer_logo')) ? $errors->first('footer_logo') : '' }}</div>
            </div>

            <div class="form-group">
              <label> {{ __('users.copyright_content_label')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="copyright_content" name="copyright_content" placeholder="Ccopyright Content" value="{{ (old('copyright_content')) ?  old('copyright_content') : $sitedata[0]->copyright_content}}" />
              <div class="text-danger">{{ ($errors->has('copyright_content')) ? $errors->first('copyright_content') : '' }}</div>
            </div>

            <div class="box-footer">
               <span class="pull-right">
                <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i>{{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
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

</script>
@endsection('middlecontent')
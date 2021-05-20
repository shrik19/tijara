@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$current_module_name}}</h2>
  <p class="section-lead">Add Site Content.</p>

  <div class="row">

    <div class="col-12 col-md-8 col-lg-8">
           @include('Admin.alert_messages')
      <div class="card">
        <div class="card-body">
          <!-- form start -->
          <form class="form-horizontal" method="post" name="frmCreateActivity" id="frmCreateActivity" action="{{route('adminSettingStore')}}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
              <label>Site Title  <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="site_title" name="site_title" placeholder="Site Title" value="{{ (old('site_title')) ?  old('site_title') : $sitedata[0]->site_title}}" />
              <div class="text-danger">{{ ($errors->has('site_title')) ? $errors->first('site_title') : '' }}</div>
            </div>

            <div class="form-group">
              <label>Footer Details  <span class="text-danger">*</span></label>
              <textarea class="form-control description" name="footer_address" id="footer_address" spellcheck="true" >{{ (old('footer_address')) ?  old('footer_address') : $sitedata[0]->footer_address}}</textarea>
              <div class="text-danger">{{ ($errors->has('footer_address')) ? $errors->first('footer_address') : '' }}</div>
            </div>
            
            <div class="form-group">
              <label>Upload Header Logo  <span class="text-danger"></span></label>				<img src="{{url('/')}}/uploads/Images/{{$sitedata[0]->header_logo}}" height="50">
               <input type="file" name="header_logo" class="form-control" value="{{old('header_logo')}}">
              <div class="text-danger err-letter">{{ ($errors->has('header_logo')) ? $errors->first('header_logo') : '' }}</div>
            </div>

            <div class="form-group">
              <label>Upload Footer Logo  <span class="text-danger"></span></label>				<img src="{{url('/')}}/uploads/Images/{{$sitedata[0]->footer_logo}}" height="50">
               <input type="file" name="footer_logo" class="form-control" value="{{old('footer_logo')}}">
              <div class="text-danger err-letter">{{ ($errors->has('footer_logo')) ? $errors->first('footer_logo') : '' }}</div>
            </div>

            <div class="box-footer">
               <span class="pull-right">
                <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Save</button>&nbsp;&nbsp;
                <a href="{{$module_url}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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

   /*function to validate letters for category*/
  function allLetter(inputtxt){ 
    var letters = /^[A-Za-z ]+$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      $('.err-letter').text('Please input alphabet characters only');
      return false;
    }
  }
</script>
@endsection('middlecontent')
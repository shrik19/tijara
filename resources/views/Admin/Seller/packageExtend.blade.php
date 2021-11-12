@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
 <!--  <p class="section-lead">{{ __('users.edit_seller_details_title')}}</p> -->
  <form method="POST" action="{{route('adminPackageExtendUpdate', base64_encode($id))}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.package_name_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="package_title" id="package_title" required tabindex="1" value="{{ ($packageDetails[0]->is_trial == 1) ?  'Tijara Trial' : $packageDetails[0]->title}}" readonly="readonly">
              <input type="hidden"  name="hid" id="hid"  value="{{$packageDetails[0]->id}}">
            </div>

            <div class="form-group">
              <label>{{ __('users.start_date_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="start_date" id="start_date" required tabindex="1" value="{{ ($packageDetails[0]->is_trial == 1) ?  $packageDetails[0]->trial_start_date : $packageDetails[0]->start_date}}" readonly="readonly">
              <div class="text-danger">{{$errors->first('start_date')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.end_date_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="end_date" id="datetimepicker" id="datetimepicker1" required tabindex="3" value="{{($packageDetails[0]->is_trial == 1) ? $packageDetails[0]->trial_end_date : $packageDetails[0]->end_date}}">
        <!--  <input type="text" id="datetimepicker"/> -->
               <div class="text-danger">{{$errors->first('end_date')}}</div>
            </div>

            
          <div>
          <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
          <a href="{{route('adminSellerShowPackages',base64_encode($packageDetails[0]->user_id))}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
        </div>
          </div>
        </div>
      </div>


    </div>
  </form>
</div>
<!-- datetime picker -->
<link rel="stylesheet" href="{{url('/')}}/assets/admin/css/jquery.datetimepicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 
<script src="{{url('/')}}/assets/admin/js/jquery-ui.min.js">
</script><script src="{{url('/')}}/assets/admin/js/jquery.datetimepicker.full.js"></script>
<script type="text/javascript">


$(document).ready(function () {
  var start_date = $('#start_date').val();
   $('#datetimepicker').datetimepicker({
    format:'Y-m-d H:i:s',
     minDate:start_date,
    /*inline:true*/
});
/*    jQuery.datetimepicker.setLocale('SE');

jQuery('#datetimepicker1').datetimepicker({
 i18n:{
  de:{
   months:[
    'Januar','Februar','März','April',
    'Mai','Juni','Juli','August',
    'September','Oktober','November','Dezember',
   ],
   dayOfWeek:[
    "So.", "Mo", "Di", "Mi", 
    "Do", "Fr", "Sa.",
   ]
  }
 },
 timepicker:false,
 format:'d.m.Y'
});*/
});
</script>


@endsection('middlecontent')
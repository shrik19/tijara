l.@extends('Front.layout.template')
@section('middlecontent')
<style>
     ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
.login_box
{
width:100% !important;
}

 .images {
   background-image: url(../../uploads/Images/multiple_no_images.png);
    background-repeat: no-repeat;
    min-height: 85px;
    padding-left: 0;
    margin-bottom: 10px;
}

.images>div {
    float: left;
    border: 2px solid #ccc;
    margin: 0 !important;
    position: relative;
}

.images a.remove_image {
    position: absolute;
    bottom: 0px;
    left: 3px;
}
th.fc-week-number.fc-widget-header {
    display: none;
}
td.fc-week-number {
    display: none;
}

.fc-content-skeleton td {
    text-align: center;
    padding: 30px;
}

.product_add_h2{
    padding: 40px 0px !important;
}

.invalid-feedback {
    position: relative !important;
}
</style>

<div class="mid-section" style="padding-top: 96px !important;">
  <div>
  <div class="container-inner-section-1"  style="margin-bottom: 60px;">
  <!-- Example row of columns -->
  @if($subscribedError)
  <div class="alert alert-danger">{{$subscribedError}}</div>
  @endif
  <form id="service-form service-add-form" class="tijara-form" action="{{route('frontServiceStore')}}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="row">

  <div class="col-md-2 tijara-sidebar">
  @include ('Front.layout.sidebar_menu')
  </div> 
  <div class="col-md-10 tijara-content">


  @include ('Front.alert_messages')
  <div class="seller_info">
  <div class="seller_header">
      <h2 class="seller_page_heading">{{ __('servicelang.service_form_label')}}</h2>
   </div>

  <div class="col-md-12">
      <div class="col-md-12 text-right" style="margin-top:30px;">
            <a href="{{route('manageFrontServices')}}" title="" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
         </div>

  <div class="login_box">
  <h2 class="col-md-12 product_add_h2">{{ __('servicelang.step_1')}}</h2>

  <input type="hidden" name="service_id" value="{{$service_id}}">

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('servicelang.service_title_label')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input type="text" class="col-md-8 login_input form-control" name="title" id="title" 
  placeholder="{{ __('servicelang.service_title_label')}} " value="{{ (old('title')) ?  old('title') : $service->title}}" tabindex="1" onblur="checkServiceUniqueSlugName();">
  <span class="invalid-feedback col-md-8" id="err_title">@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
  </div>
  </div>

  <div class="form-group row" style="display:none;">
  <label class="col-md-3 product_table_heading">{{ __('servicelang.service_slug_label')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input type="text" class="col-md-8 login_input slug-name form-control" name="service_slug" id="service_slug" placeholder="{{ __('servicelang.service_slug_label')}} " value="{{ (old('service_slug')) ?  old('service_slug') : $service->service_slug}}" tabindex="1" readonly="readonly">
  <span class="invalid-feedback col-md-8 slug-name-err" id="err_title" >@if($errors->has('service_slug')) {{ $errors->first('service_slug') }}@endif </span>
  </div>
  </div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('servicelang.session_time_label')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input maxlength="3" type="text" class="col-md-8 login_input session_time number form-control" name="session_time" id="session_time" 
  placeholder="{{ __('servicelang.session_time_placeholder')}} " value="{{ (old('session_time')) ?  old('session_time') : $service->session_time}}" 
  tabindex="1" >
  <span class="invalid-feedback col-md-8 session_time-err" id="session_time_err">@if($errors->has('session_time')) {{ $errors->first('session_time') }}@endif </span>
</div>
  </div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('users.address_label')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input type="text" class="col-md-8 login_input address form-control" name="address" id="address" 
  placeholder="{{ __('users.service_address_placeholder')}} " value="{{ (old('address')) ?  old('address') : $service->address}}" 
  tabindex="1" >
  <span class="invalid-feedback col-md-8address-err" id="address_err">@if($errors->has('address')) {{ $errors->first('address') }}@endif </span>
</div>
  </div>

<div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('lang.product_buyer_phone_no')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input type="text" class="col-md-8 login_input telephone_number form-control" name="telephone_number" id="telephone_number" 
  placeholder="{{ __('lang.product_buyer_phone_no')}} " value="{{ (old('telephone_number')) ?  old('telephone_number') : $service->telephone_number}}" 
  tabindex="1" >
  <span class="invalid-feedback col-md-8 telephone_number-err" id="telephone_number_err">@if($errors->has('telephone_number')) {{ $errors->first('telephone_number') }}@endif </span>
  </div>
</div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('servicelang.service_description_label')}}<span class="de_col">*</span>  <span class="de_col"></span></label>

   <div class="col-md-8">
  <textarea class="col-md-12 login_input form-control description" name="description" rows="5" cols="5" placeholder="{{ __('users.service_description_placeholder')}}" value="" 
  tabindex="2">{{ (old('description')) ?  old('description') : $service->description}}</textarea>
  <span class="invalid-feedback col-md-8" id="err_description">@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
  </div>
  </div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('lang.category_label')}}<span class="de_col">*</span></label>
  <div class="col-md-8">
  <select class="select2 col-md-8 login_input form-control" name="categories[]" id="categories" multiple placeholder="{{__('lang.category_label')}}" tabindex="3">
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
  <span class="invalid-feedback col-md-8" id="err_categories" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
  </div>
  </div>


  <div class="form-group row" style="display:none;">
  <label class="col-md-3 product_table_heading">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
  <div class="col-md-8">
  <input type="tel" class="col-md-8 login_input form-control" name="sort_order" id="sort_order"
  placeholder="{{ __('lang.sort_order_label')}}" 
  value="{{(old('sort_order')) ?  old('sort_order') : $service->sort_order}}" tabindex="7">
  <span class="invalid-feedback col-md-8" id="err_meta_keyword">@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
  </div>
  </div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('lang.status_label')}}<span class="de_col">*</span> </label>
  <div class="col-md-8">
  <select class="select2 col-md-8 login_input form-control" name="status" id="status"  placeholder="Select" tabindex="8" >
  <option @if($service->status=='active') selected="selected" @endif value="active">Active</option>
  <option @if($service->status=='block') selected="selected" @endif value="block">Block</option>
  </select>
  <span class="invalid-feedback col-md-8" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
  </div>
  </div>


  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('lang.service_price')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <input type="tel" class="number col-md-8 service_price form-control" name="service_price" id="service_price"
  placeholder="{{ __('users.service_price_placeholder')}}" 
  value="{{(old('service_price')) ?  old('service_price') : $service->service_price}}" tabindex="7">
  <span class="invalid-feedback col-md-12" id="err_service_price" >@if($errors->has('service_price')) {{ $errors->first('service_price') }}@endif </span>
  </div>
  </div>

  <div class="form-group row">
  <label class="col-md-3 product_table_heading">{{ __('lang.images')}} <span class="de_col">*</span></label>
  <div class="col-md-8">
  <div class="images col-md-12">
     
  @php
  $images = explode(',',$service->images);
  @endphp
  @if(!empty($images))
  @foreach($images as $image)
  @if($image!='')
  <div>
  <input type="hidden" class="form-control login_input hidden_images" value="{{$image}}"  name="hidden_images[]">
  <img src="{{url('/')}}/uploads/ServiceImages/resized/{{$image}}" width="78" height="80">
  <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
</div>
  @endif
  @endforeach
  @endif
  </div>
  <input type="file" class="col-md-8 login_input image service_image form-control">
  <span class="invalid-feedback col-md-12 productErr" id="err_service_image"></span>  
  <span class="invalid-feedback col-md-12 productErr" id="err_service_hid_image"></span> 
  <p class="seller-logo-info col-md-12" style="font-size: 12px;">{{ __('messages.product_img_upload_info')}}</p>
  </div>
  </div>

<!-- 
  <hr class="solid-horizontal-line"> -->
  <h2 class="col-md-12 product_add_h2">{{ __('servicelang.step_2')}}</h2>
  <div class="col-md-12" style="margin-left: -32px;">
  <div class="col-md-9">
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.from_service_year')}}</label>

  <select class="col-md-12 service_year form-control" name="service_year" id="service_year" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for($i=date('Y'); $i<'2050';$i++) {
  ?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php
  }
  ?>
  </select>
  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_year" >@if($errors->has('service_year')) {{ $errors->first('service_year') }}@endif </span>
  </div>
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.from_service_month')}} </label>
  <select class="col-md-12 service_month form-control" name="service_month" id="service_month" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for ($i = 1; $i <= 12; $i++) {
  $timestamp = date('01-'.$i.'-'.date('Y'));
  ?>
  <option value="<?php echo date('m', strtotime($timestamp)); ?>"><?php echo date('F', strtotime($timestamp)); ?></option>
  <?php
  }
  ?>
  </select><span style="text-align: center;" class="invalid-feedback col-md-12" id="service_month" >@if($errors->has('service_month')) {{ $errors->first('service_month') }}@endif </span>
  </div>
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.from_service_date')}} </label>
  <select class="col-md-12 service_date form-control" name="service_date" id="service_date" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for ($i = 1; $i <=31; $i++) {

  ?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php
  }
  ?>
  </select>
  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_date" >@if($errors->has('service_availability')) {{ $errors->first('service_availability') }}@endif </span>
  </div>
  </div>
  <div class="col-md-3" style="display: flex;">
  <div class="form-group col-md-2" style="width: 150px;margin-left: -180px;margin-top: 65px;">
  <label class="col-md-12 product_table_heading">{{ __('lang.start_time')}} </label>
  <input type="tel" class="col-md-12 start_time form-control" name="start_time" id="start_time" placeholder="00:00" value="{{(old('start_time')) ?  old('start_time') :''}}" tabindex="7">
  <span style="margin-top: 75px;margin-left: 20px;" class="invalid-feedback col-md-12" id="start_time" >@if($errors->has('service_availability')) {{ $errors->first('service_availability') }}@endif </span>
  </div>

  <div class="col-md-2 text-center" style="margin-top: 65px !important;">
  <label class="col-md-12 product_table_heading"></label>
  <input type="hidden" name="del_start_time" id="del_start_time">
 <!-- <select name="del_start_time" id="del_start_time" style="margin-top: 25px;width: 100px" class="form-control">
  <option value="" >{{ __('lang.select_label')}}</option>
  <option value="insert">{{ __('lang.save_btn')}}</option>
  <option value="delete">{{ __('lang.delete_title')}}</option>
  </select> -->
  </div>
  </div>
  </div>

  <!-- to date block start -->
  <div class="col-md-12" style="margin-left: -32px;margin-top: -85px;">
  <div class="col-md-9">
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.to_service_year')}}<!-- <span class="de_col">*</span> --></label>

  <select class="col-md-12 to_service_year form-control" name="to_service_year" id="to_service_year" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for($i=date('Y'); $i<'2050';$i++) {
  ?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php
  }
  ?>
  </select>
  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_year" >@if($errors->has('service_year')) {{ $errors->first('service_year') }}@endif </span>
  </div>
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.to_service_month')}}<!-- <span class="de_col">*</span> --></label>
  <select class="col-md-12 to_service_month form-control" name="to_service_month" id="to_service_month" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for ($i = 1; $i <= 12; $i++) {
  $timestamp = date('01-'.$i.'-'.date('Y'));
  ?>
  <option value="<?php echo date('m', strtotime($timestamp)); ?>"><?php echo date('F', strtotime($timestamp)); ?></option>
  <?php
  }
  ?>
  </select><span style="text-align: center;" class="invalid-feedback col-md-12" id="service_month" >@if($errors->has('service_month')) {{ $errors->first('service_month') }}@endif </span>
  </div>
  <div class="form-group col-md-3">
  <label class="col-md-12 product_table_heading">{{ __('lang.to_service_date')}}<!-- <span class="de_col">*</span> --></label>
  <select class="col-md-12 to_service_date form-control" name="to_service_date" id="to_service_date" >
  <option value="">{{ __('lang.select_label')}}</option>
  <?php
  for ($i = 1; $i <=31; $i++) {

  ?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php
  }
  ?>
  </select>
  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_date" >@if($errors->has('service_availability')) {{ $errors->first('service_availability') }}@endif </span>
  </div>
  </div>

  </div>
  <!-- to date block end -->
  <div class="col-md-12">
  <div class="col-md-6"></div>
  <div class="col-md-6">
  <div class="col-md-3" style="margin-left: 200px !important;display: flex;">
    <a href="javascript:void(0);" name="remove_service_date" id="remove_service_date" class="btn btn-danger login_btn save_service_date" tabindex="9" val="delete">{{ __('lang.remove_title')}}</a>
&nbsp;&nbsp;
  <a href="javascript:void(0);" name="save_service_date" id="save_service_date" class="btn btn-success login_btn save_service_date" tabindex="9" val="insert">{{ __('lang.save_service_date_btn')}}</a>

  </div>
  <div class="col-md-3"></div>
  </div>
  </div>
  <div class="added_service_times" style="display:none;">

  @if(!empty($serviceAvailability))
  @foreach($serviceAvailability as $availability)
  @php $service_time  = $availability['service_date'].' '.$availability['start_time']; @endphp
  <input type="hidden" id="{{$availability['id']}}" class="form-control service_availability " value="{{$service_time}}"  name="service_availability[]">                      

  @endforeach
  @endif
  </div>
  <div  class="col-md-12 service-add-calender" id="calendar" style="padding: 20px;margin-left: -12px;"></div>
  </div>
  </div>
  <div class="col-md-12 text-center">&nbsp;</div>
  <div class="col-md-12 text-center">
  <button type="submit" name="btnCountryCreate" id="saveservicebtn" class="btn btn-black debg_color login_btn saveservice" tabindex="9">{{ __('lang.save_btn')}}</button>

  <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
  </div>
  
  </div>
  </form>
  </div>
  </div>
</div>
<!-- </div>
</div>  --><!-- /container -->


<script>var siteUrl="{{url('/')}}";</script>
<script type="text/javascript">

  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkServiceUniqueSlugName(){
    var slug_name= $('#title').val();
    var slug;
    $.ajax({
    url: "{{url('/')}}"+'/manage-services/check-slugname/?slug_name='+slug_name,
    type: 'get',
    async: false,
    data: { },
    success: function(output){
    $('#service_slug').val(output);
    }
    });

    return slug;
  }

</script>

<script src="{{url('/')}}/assets/front/js/jquery.inputmask.bundle.js"></script>

<script>
$(function(){

$('#start_time').inputmask(
"hh:mm", {
placeholder: "00:00", 
insertMode: false, 
showMaskOnHover: false,
hourFormat: "24"
}
);


});
</script>


<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fullcalendar.min.css">
<script src="{{url('/')}}/assets/front/js/moment.min.js"></script>
<script src="{{url('/')}}/assets/front/js/fullcalendar.min.js"></script>

<script type="text/javascript">//<![CDATA[

  $(document).ready(function() {

    //check click on add time button or not
  

$('#saveservicebtn').click(function(){

  let title            = $("#title").val();
  let session_time     = $("#session_time").val();
  let address          = $("#address").val();
  let telephone_number = $("#telephone_number").val();
  let description      = $(".description").val();
  let categories       = $("#categories").val();
  let service_price    = $("#service_price").val();
  let service_image = $(".service_image").val();
  let hidden_images = $(".hidden_images").val();
  let error = 0;

  if(title=='' || session_time=='' || address == '' || telephone_number == '' || description == ''  || categories == null || service_price==''){
    showErrorMessage(enter_all_fields_err);
    error = 1;
  }


  if(title == '')
  {
    $("#err_title").html(required_field_error).show();
    $("#err_title").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#err_title").html('').show();

  }

  if(session_time == '')
  {
    $("#session_time_err").html(required_field_error).show();
    $("#session_time_err").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#session_time_err").html('').show();

  }

  if(address == '')
  {
    $("#address_err").html(required_field_error).show();
    $("#address_err").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#address_err").html('').show();

  }

  if(telephone_number == '')
  {
    $("#telephone_number_err").html(required_field_error).show();
    $("#telephone_number_err").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#telephone_number_err").html('').show();

  }

  if(description == '')
  {
    $("#err_description").html(required_field_error).show();
    $("#err_description").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#err_description").html('').show();

  }

  if(categories == null)
  {
    $("#err_categories").html(required_field_error).show();
    $("#err_categories").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#err_categories").html('').show();

  }

  if(service_price == '')
  {
    $("#err_service_price").html(required_field_error).show();
    $("#err_service_price").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#err_service_price").html('').show();

  }


  if(service_image == '')
  {
    $("#err_service_image").html(please_uplaod_service_image).show();
    $("#err_service_image").parent().addClass('jt-error');
    error = 1;
  } else
  {
    $("#err_service_image").html('').show();

  }

  if(hidden_images == '')
  {
    $("#err_service_hid_image").html(wait_while_upload).show();
    $("#err_service_hid_image").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_service_hid_image").html('').show();

  }

   
  if(error == 1)
  {
    return false;
  }
  else
  {
    $('.service-add-form').submit();
    return true;
  }
     
});

    var events_array = [];

    if($('.service_availability').length>0) {
    var events_array=[];
    $( ".service_availability" ).each(function() {

    var service_time  = $(this).val().split(" "); //alert(new Date(service_time[0]));
    events_array.push({
    title: service_time[1],
    start: new Date(service_time[0]),
    id: $(this).attr('id'),

    });
    });
    //console.log(events_array);
    // $('#calendar').fullCalendar('addEventSource', events_array);

    }
    $('#calendar').fullCalendar({
    monthNames: ['Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober'
    ,'November','December'],
    monthNamesShort: ['Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober'
    ,'November','December'],
    dayNames: ['söndag','måndag','tisdag','onsdag','torsdag','fredag','lördag'],

    dayNamesShort: ['Sön','Mån','Tis','Ons','Tors','Fre','Lör'],

    columnFormat: 'ddd',
    views: {
    sevenDays: {
    type: 'month',
    duration: {
    weeks: 1
    },
    fixedWeekCount: false,
    }
    },
    allDayDefault: true,
    defaultView: 'sevenDays',
    editable: true,
    header: {
    center: "title",
    left: "",
    right: " prev today next ",//"prevYear prev today next nextYear",
    },
    height: 250,
    timezoneParam: 'local',
    titleFormat: "MMMM YYYY",
    weekNumbers: true,
    events: events_array,
    viewRender: function () {
    var i = 0;
    var viewStart = $('#calendar').fullCalendar('getView').intervalStart;
    $("#calendar").find('.fc-content-skeleton thead td:not(:nth-child(1))').empty().each( function(){
    $(this).append(moment(viewStart).add(i, 'days').format("D"));
    i = i + 1;
    });

    /* window.setTimeout(function(){
    var viewMth = $('#calendar').fullCalendar('getDate');

    $("#calendar").find('.fc-toolbar > div > h2').empty().append(
    "<span>"+viewMth.format('MMMM')+"&nbsp;</span>"+
    "<span>"+viewMth.format('YYYY')+"</span>"
    );
    },0);*/
    },
    eventClick: function(calEvent, jsEvent, view) 
    {

    // var dateselect = calEvent.start.format('Y-M-D');
    var result = ConfirmDeleteTime("{{ __('lang.areYouSureToDeleteServiceTime')}}",calEvent.id);
    //console.log(result);
    if (result) {
    $('.service_availability#'+calEvent.id).remove();
    $('#calendar').fullCalendar('removeEvents',calEvent.id);
    }
    //show_details(calEvent,calEvent.salonoragent_id,dateselect);
    //alert(info.salonoragent_id);
    },
    });
  });

  var service_time_counter  = 10000;

  $('.save_service_date').click(function(){
    service_time_counter  = service_time_counter+1;
    var button = $(this).attr('val');
    $('#del_start_time').val(button);
       
    if($('#service_month').val()=='' || $('#service_year').val()=='' || $('#service_date').val()=='' || $('#to_service_month').val()=='' || $('#to_service_year').val()=='' || $('#to_service_date').val()=='' || $('#start_time').val()=='00:00' || $('#start_time').val()=='') {
    showErrorMessage("{{ __('lang.service_time_required')}}");
    return false;
    }
    var service_date  = new Date($('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val());
    var service_date_to_use  = $('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val();
    //alert(service_date);
    if(service_date < new Date()) {
    showErrorMessage("{{ __('lang.select_future_date')}}");
    return false;
    }

    if(to_service_date < new Date()) {
    showErrorMessage("{{ __('lang.select_future_to_date')}}");
    return false;
    }
    var start_date = $('#service_year').val()+'-'+$('#service_month').val()+'-'+$('#service_date').val();
    var end_date =  $('#to_service_year').val()+'-'+$('#to_service_month').val()+'-'+$('#to_service_date').val()
    var start = new Date(start_date);
    var end = new Date(end_date);
    var loop = new Date(start);
    var allDates = [];
    while(loop <= end){ 
    var date = new Date(loop),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    var tosendDate = [date.getFullYear(), mnth, day].join("-");
    var sendDate =tosendDate+' '+$('#start_time').val();
    if($('#del_start_time').val()=='delete'){
    // $('input[value="'+sendDate+'"]').remove();

    $('.added_service_times').append('<input id="'+service_time_counter+'" type="text" name="del_service_availability[]" class="service_availability" value="'+sendDate+'">');
    }else{
    $('.added_service_times').append('<input id="'+service_time_counter+'" type="text" name="service_availability[]" class="service_availability" value="'+sendDate+'">');
    }

    allDates.push(new Date(loop));
    var newDate = loop.setDate(loop.getDate() + 1);
    loop = new Date(newDate);
    }

    var events_array = [];  
    $(allDates).each(function (k, v) {
    var temp = {
    id: service_time_counter,
    title: $('#start_time').val(),
    start: v,
    //tip: 'Sup dog.'
    };
    events_array.push(temp);
    });

    $('#calendar').fullCalendar('addEventSource', events_array);
    $('#service_year').val('');
    $('#service_month').val('');
    $('#service_date').val('');
    $('#start_time').val('');
    $('#to_service_year').val('');
    $('#to_service_month').val('');
    $('#to_service_date').val('');
    // $('.added_service_times').append('<input type="text" id="'+service_time_counter+'"  name="service_availability[]" value="'+service_date_to_use+'">');
  });

function ConfirmDeleteTime(msg,cal_id)
{  
  //$.noConflict(); 
  $.confirm({
      title: js_confirm_msg,
      content: msg,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
             // showSuccessMessage(msg);
              $('.service_availability#'+cal_id).remove();
              $('#calendar').fullCalendar('removeEvents',cal_id);
          },
          Avbryt: function () {
          },
      }
  });

}

</script>
<script type="text/javascript">
  $('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').parent("div").remove();
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});

</script>
@endsection

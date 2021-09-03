@extends('Front.layout.template')
@section('middlecontent')
<style>
  .login_box
  {
    width:100% !important;
  }
</style>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
  <!-- Example row of columns -->
   @if($subscribedError)
      <div class="alert alert-danger">{{$subscribedError}}</div>
      @endif
  <form id="service-form" class="tijara-form" action="{{route('frontServiceStore')}}" method="post" enctype="multipart/form-data">
            @csrf
  <div class="row">

    <div class="col-md-2 tijara-sidebar">
      @include ('Front.layout.sidebar_menu')
    </div>
    <div class="col-md-10 tijara-content">
        <div class="col-md-10">
            <h2>{{ __('servicelang.service_form_label')}}</h2>
            <hr class="heading_line"/>
        </div>
        <div class="col-md-2 text-right" style="margin-top:30px;">
            <a href="{{route('manageFrontServices')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
        </div>

         @include ('Front.alert_messages')
        <div class="col-md-12">

          <div class="login_box">
              <h2 class="col-md-12">{{ __('servicelang.step_1')}}</h2>

              <input type="hidden" name="service_id" value="{{$service_id}}">

              <div class="form-group col-md-12">
                <label class="col-md-3">{{ __('servicelang.service_title_label')}} <span class="de_col">*</span></label>
                <input type="text" class="col-md-8 login_input form-control" name="title" id="title" placeholder="{{ __('servicelang.service_title_label')}} " value="{{old('title')}}" tabindex="1" onblur="checkServiceUniqueSlugName();">
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
              </div>

              <div class="form-group col-md-12" style="display:none;">
                <label class="col-md-3">{{ __('servicelang.service_slug_label')}} <span class="de_col">*</span></label>
                <input type="text" class="col-md-8 login_input slug-name form-control" name="service_slug" id="service_slug" placeholder="{{ __('servicelang.service_slug_label')}} " value="{{old('service_slug')}}" tabindex="1" readonly="readonly">
                <span style="text-align: center;" class="invalid-feedback col-md-12 slug-name-err" id="err_title" >@if($errors->has('service_slug')) {{ $errors->first('service_slug') }}@endif </span>
              </div>

              <div class="form-group col-md-12" >
                <label class="col-md-3">{{ __('servicelang.session_time_label')}} <span class="de_col">*</span></label>
                <input maxlength="3" type="text" class="col-md-8 login_input session_time number form-control" name="session_time" id="session_time" 
                placeholder="{{ __('servicelang.session_time_placeholder')}} " value="{{old('session_time')}}" 
                tabindex="1" >
                <span style="text-align: center;" class="invalid-feedback col-md-12 session_time-err" id="session_time" >@if($errors->has('session_time')) {{ $errors->first('session_time') }}@endif </span>
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3">{{ __('lang.category_label')}}</label>
                <select class="select2 col-md-8 login_input form-control" name="categories[]" id="categories" multiple placeholder="Select" tabindex="3">
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
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
              </div>


              <div class="form-group col-md-12" style="display:none;">
                  <label class="col-md-3">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
                  <input type="tel" class="col-md-8 login_input form-control" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $max_seq_no}}" tabindex="7">
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
              </div>

              <div class="form-group col-md-12">
              <label class="col-md-3">{{ __('servicelang.service_description_label')}}  <span class="de_col"></span></label>
                  
             <!--  <div class="form-group col-md-8"> -->
                  <textarea class="col-md-12 login_input form-control" name="description" rows="10" cols="20" placeholder="{{ __('servicelang.service_description_label')}}" value="" tabindex="2">{{old('description')}}</textarea>
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
              <!-- </div> -->
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3">{{ __('lang.status_label')}} </label>
                <select class="select2 col-md-8 login_input form-control" name="status" id="status"  placeholder="Select" tabindex="8" >
                  <option value="active">{{ __('lang.active_label')}}</option>
                  <option value="block">{{ __('lang.block_label')}}</option>
                  </select>
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
              </div>

            
              <div class="form-group col-md-12">
                  <label class="col-md-3">{{ __('lang.service_price')}} <span class="de_col">*</span></label>
                  <input type="tel" class="number col-md-8 service_price form-control" name="service_price" id="service_price" placeholder="{{ __('lang.service_price')}}" value="{{(old('service_price')) ?  old('service_price') :''}}" tabindex="7">
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_price" >@if($errors->has('service_price')) {{ $errors->first('service_price') }}@endif </span>
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3">{{ __('lang.images')}} </label>
                <input type="file" class="col-md-8 login_input image service_image form-control" >
                <div class="images col-md-12"></div>
              </div>


        
              <h2  class="col-md-12">{{ __('servicelang.step_2')}}</h2>
               <div class="col-md-12">
                 <div class="col-md-9">
              <div class="form-group col-md-3">
                    <label class="col-md-12">{{ __('lang.from_service_year')}}<!-- <span class="de_col">*</span> --></label>
                    
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
                    <label class="col-md-12">{{ __('lang.from_service_month')}}<!-- <span class="de_col">*</span> --></label>
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
                  <label class="col-md-12">{{ __('lang.from_service_date')}}<!-- <span class="de_col">*</span> --></label>
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
                  <span style="text-align: center;margin-top: 90px;margin-left: 15px;" class="invalid-feedback col-md-12" id="service_date" >@if($errors->has('start_date_time') || $errors->has('to_date_time')) {{ $errors->first('to_date_time') }}@endif </span>
              </div>
            </div>
            <div class="col-md-3" style="display: flex;">
              <div class="form-group col-md-2" style="width: 150px;margin-left: -180px;margin-top: 65px;">
                <label class="col-md-12">{{ __('lang.start_time')}}<!--  <span class="de_col">*</span> --></label>
                <input type="tel" class="col-md-12 start_time form-control" name="start_time" id="start_time" placeholder="00:00" value="{{(old('start_time')) ?  old('start_time') :''}}" tabindex="7" >
            <!--     <span style="text-align: center;" class="invalid-feedback col-md-12" id="start_time" >@if($errors->has('start_date_time') || $errors->has('to_date_time')) {{ $errors->first('to_date_time') }}@endif </span> -->
              </div>

              <div class="col-md-1 text-center" style="margin-top: 65px !important;">
                <label class="col-md-12">Action</label>
             <!--    <a href="javascript:void(0);" name="save_service_date" id="save_service_date" class="btn btn-black debg_color login_btn " tabindex="9">{{ __('lang.save_service_date_btn')}}</a> -->
                <select name="del_start_time" id="del_start_time" class="form-control" style="margin-top: 25px;width: 100px;">
                  <option value="" >Select</option>
                  <option value="insert">Insert</option>
                    <option value="delete">Delete</option>
                </select>
                 <span style="text-align: center;" class="invalid-feedback col-md-12" id="start_time" >@if($errors->has('del_start_time')) {{ $errors->first('del_start_time') }}@endif </span>
              </div>
            </div>
          </div>
          <!-- to date block start -->
            <div class="col-md-12">
                 <div class="col-md-9">
              <div class="form-group col-md-3">
                    <label class="col-md-12">{{ __('lang.to_service_year')}}<!-- <span class="de_col">*</span> --></label>
                    
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
                    <label class="col-md-12">{{ __('lang.to_service_month')}}<!-- <span class="de_col">*</span> --></label>
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
                  <label class="col-md-12">{{ __('lang.to_service_date')}}<!-- <span class="de_col">*</span> --></label>
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
              <div class="col-md-3" style="margin-left: 200px !important;">
              <a href="javascript:void(0);" name="save_service_date" id="save_service_date" class="btn btn-black debg_color login_btn " tabindex="9">{{ __('lang.save_service_date_btn')}}</a>
            </div>
            <div class="col-md-3"></div>
            </div>
          </div>
              <div class="added_service_times" style="display:none;"></div>
              <div  class="col-md-12" id="calendar" style="padding: 20px;"></div>
          </div>
        </div>
          <div class="col-md-12 text-center">&nbsp;</div>
          <div class="col-md-12 text-center">
            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveservice" tabindex="9">{{ __('lang.save_btn')}}</button>

            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
          </div>
        </div>
      </div>
  </form>
                    </div>
                    </div>
</div> <!-- /container -->
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
</body>
</html>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fullcalendar.min.css">
<script src="{{url('/')}}/assets/front/js/moment.min.js"></script>
<script src="{{url('/')}}/assets/front/js/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2/moment.min.js"></script>

<script type="text/javascript">//<![CDATA[


$(document).ready(function() {
  var events_array = [];

  $('#calendar').fullCalendar({
    monthNames: ['Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober'
    ,'November','December'],
    monthNamesShort: ['Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober'
    ,'November','December'],
    dayNames: ['måndag','tisdag','onsdag','torsdag','fredag','lördag','söndag'],

    dayNamesShort: ['Mån','Tis','Ons','Tors','Fre','Lör','Sön'],

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
         var result = confirm("{{ __('lang.areYouSureToDeleteServiceTime')}}");
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
  $('#save_service_date').click(function(){

    service_time_counter  = service_time_counter+1;
    if($('#service_month').val()=='' || $('#service_year').val()=='' || $('#service_date').val()=='' || $('#to_service_month').val()=='' || $('#to_service_year').val()=='' || $('#to_service_date').val()==''
    || $('#start_time').val()=='00:00' || $('#start_time').val()=='') {
        alert("{{ __('lang.service_time_required')}}");
        return false;
    }
    var service_date  = new Date($('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val());
    var service_date_to_use  = $('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val();


    var to_service_date  = new Date($('#to_service_year').val()+'-'+$('#to_service_month').val()+'-'+$('#to_service_date').val()+' '+$('#start_time').val());
    var to_service_date_to_use  = $('#to_service_year').val()+'-'+$('#to_service_month').val()+
    '-'+$('#to_service_date').val()+' '+$('#start_time').val();
   
    if(service_date < new Date()) {
      alert("{{ __('lang.select_future_date')}}");
        return false;
    }
    if(to_service_date < new Date()) {
      alert("{{ __('lang.select_future_to_date')}}");
        return false;
    }
    var start_date = $('#service_year').val()+'-'+$('#service_month').val()+'-'+$('#service_date').val();
    var end_date =  $('#to_service_year').val()+'-'+$('#to_service_month').val()+'-'+$('#to_service_date').val()
    var start = new Date(start_date);
    var end = new Date(end_date);

       /*code to add all dates in service_availability*/
      var date = new Date(start_date);
      mnth = ("0" + (date.getMonth() + 1)).slice(-2),
      day = ("0" + date.getDate()).slice(-2);
      var fromsendDate = [date.getFullYear(), mnth, day].join("-");
      var sendFromDate = fromsendDate+' '+$('#start_time').val();

      //enddate
      var endDate = new Date(end);
      mnthEnd = ("0" + (endDate.getMonth() + 1)).slice(-2),
      dayEnd = ("0" + endDate.getDate()).slice(-2);
      var tosendDate = [endDate.getFullYear(), mnthEnd, dayEnd].join("-");
      var sendToDate = tosendDate+' '+$('#start_time').val();

     $('.added_service_times').append('<input type="text" name="start_date_time" class="service_availability" value="'+sendFromDate+'"><input type="text" name="to_date_time" class="service_availability" value="'+sendToDate+'">'); 

    var loop = new Date(start);
    var allDates = [];
    while(loop <= end){ 

      /* var date = new Date(loop),
      mnth = ("0" + (date.getMonth() + 1)).slice(-2),
      day = ("0" + date.getDate()).slice(-2);
      var tosendDate = [date.getFullYear(), mnth, day].join("-");
     var sendDate =tosendDate+' '+$('#start_time').val();
    $('.added_service_times').append('<input id="'+service_time_counter+'" type="text" name="service_availability[]" class="service_availability" value="'+sendDate+'">');   */       
        allDates.push(new Date(loop));
       var newDate = loop.setDate(loop.getDate() + 1);
       loop = new Date(newDate);
    }


  var events_array = [];  
  $(allDates).each(function (k, v) {
    //alert(v);
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
    $('#to_service_year').val('');
    $('#to_service_month').val('');
    $('#to_service_date').val('');
    $('#start_time').val('');
    





  });
  </script>
@endsection

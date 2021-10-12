@extends('Front.layout.template')
@section('middlecontent')

<div class="mid-section p_155">
<div class="container-fluid">
<div class="container-inner-section-1">
<!-- Example row of columns -->

<div class="row">
  @if($is_seller==1)
  <div class="col-md-2 tijara-sidebar">
    @include ('Front.layout.sidebar_menu')
  </div>
  <div class="col-md-10 tijara-content">
    @else
  <div class="col-md-12 tijara-content">
    @endif

    @include('Front.alert_messages')
    <div class="seller_info">
    <div class="card">
      <div class="card-header row seller_header">
        <div class="col-md-11">
        <h2>{{ __('users.booking_request_label')}}</h2>
        <!-- <hr class="heading_line"/> -->
        </div>
      </div>

      <div class="card-body bookingRequestCalender">
        <div  class="col-md-12" id="calendar" style="padding: 20px;"></div>
        <div class="added_service_times" style="display:none;">
          <div class="added_service_times">         
            @if(!empty($bookingRequest))
            @foreach($bookingRequest as $availability)
              @php
                $service_booking_time = explode('-', $availability['service_time']);
                $service_time  = $availability['service_date'].' '.$service_booking_time[0];
                $user_name =$availability['fname']." ".$availability['lname'];
                $description = strip_tags($availability['description']);
              @endphp
              <input type="hidden" id="{{$availability['id']}}" class="form-control service_availability " value="{{$service_time}}"  name="service_availability[]" user_name="{{$user_name}}" title="{{$availability['service_title']}}" description="{{$description}}" location="{{$availability['location']}}" service_time="{{$service_time}}" service_price="{{$availability['service_price']}}">

            @endforeach
            @endif
          </div> 
        </div>
      </div>

    </div>
    </div>
</div>
  </div>
</div> <!-- /container -->
</div>
<!-- add subcategory model Form -->
<div class="modal fade" id="serviceReqDetailsmodal">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
  <h4 class="modal-title">{{ __('lang.service_req_details')}}</h4>
  <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
  </div>

  <div class="modal-body">
  <table>
  <!--  <tr><td style="font-weight: 700px;"></td>:<td></td></tr> -->
  @if(session('role_id')==2)  
  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.cust_label')}} {{ __('lang.txt_name')}} :</td><td class="user_name" style="padding-left: 10px;"></td></tr>
  @endif
  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.sevice_name_head')}} :</td><td class="title" style="padding-left: 10px;"></td></tr>
  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_label')}} {{ __('lang.product_description_label')}} :</td><td class="description" style="padding-left: 10px;"></td></tr>

  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_time')}} :</td><td class="service_time" style="padding-left: 10px;"></td></tr>
  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.service_total_cost')}} :</td><td class="service_price" style="padding-left: 10px;"></td></tr>
  <tr><td style="font-weight: bold;padding: 5px;">{{ __('lang.location')}} :</td><td class="location" style="padding-left: 10px;"></td></tr>

  </table>
  </div>

  </div>
  </div>
</div>
<script src="{{url('/')}}/assets/front/js/jquery.inputmask.bundle.js"></script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/fullcalendar.min.css">
<script src="{{url('/')}}/assets/front/js/moment.min.js"></script>
<script src="{{url('/')}}/assets/front/js/fullcalendar.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="{{url('/')}}/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="{{url('/')}}/assets/js/sweetalert.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var events_array = [];

    if($('.service_availability').length>0) {
      var events_array=[];
      $( ".service_availability" ).each(function() {
        var service_time  = $(this).val().split(" "); //alert(new Date(service_time[0]));
        events_array.push({
        title: service_time[1],
        start: new Date(service_time[0]),
        id: $(this).attr('id'),
        user_name :$(this).attr('user_name'),
        service_name :$(this).attr('title'),
        description :$(this).attr('description'),
        location :$(this).attr('location'),
        service_time :$(this).attr('service_time'),
        service_price :$(this).attr('service_price'),
        });
      });
    }

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
      },
      eventClick: function(calEvent, jsEvent, view) 
      {
        var id = calEvent.id;
        var user_name = calEvent.user_name;
        var service_name = calEvent.service_name;
        var description = calEvent.description;
        var location = calEvent.location;
        var service_time = calEvent.service_time;
        var service_price = calEvent.service_price;

        $('#serviceReqDetailsmodal').find('.id').text(id);
        $('#serviceReqDetailsmodal').find('.user_name').text(user_name);
        $('#serviceReqDetailsmodal').find('.title').text(service_name);
        $('#serviceReqDetailsmodal').find('.description').text(description);
        $('#serviceReqDetailsmodal').find('.location').text(location);
        $('#serviceReqDetailsmodal').find('.service_time').text(service_time);
        $('#serviceReqDetailsmodal').find('.service_price').text(service_price);

        $('#serviceReqDetailsmodal').modal('show');
      },
    });
  });

</script>

@endsection

<?php $__env->startSection('middlecontent'); ?>
<style>
  .login_box
  {
    width:100% !important;
  }
</style>
<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
   <?php if($subscribedError): ?>
      <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
      <?php endif; ?>
  <form id="service-form" action="<?php echo e(route('frontServiceStore')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
  <div class="row">

    <div class="col-md-2">
      <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="col-md-10">
        <div class="col-md-10">

              <h2><?php echo e(__('servicelang.service_form_label')); ?></h2>
              <hr class="heading_line"/>
        </div>
        <div class="col-md-2 text-right" style="margin-top:30px;">
            <a href="<?php echo e(route('manageFrontServices')); ?>" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;<?php echo e(__('lang.back_to_list_label')); ?></span> </a>
        </div>

         <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-12">

          <div class="login_box">
              <h2 class="col-md-12"><?php echo e(__('servicelang.step_1')); ?></h2>

              <input type="hidden" name="service_id" value="<?php echo e($service_id); ?>">

              <div class="form-group col-md-12">
                <label class="col-md-3"><?php echo e(__('servicelang.service_title_label')); ?> <span class="de_col">*</span></label>
                <input type="text" class="col-md-8 login_input" name="title" id="title" 
                placeholder="<?php echo e(__('servicelang.service_title_label')); ?> " value="<?php echo e((old('title')) ?  old('title') : $service->title); ?>" tabindex="1" onblur="checkServiceUniqueSlugName();">
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_title" ><?php if($errors->has('title')): ?> <?php echo e($errors->first('title')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-12" style="display:none;">
                <label class="col-md-3"><?php echo e(__('servicelang.service_slug_label')); ?> <span class="de_col">*</span></label>
                <input type="text" class="col-md-8 login_input slug-name" name="service_slug" id="service_slug" placeholder="<?php echo e(__('servicelang.service_slug_label')); ?> " value="<?php echo e((old('service_slug')) ?  old('service_slug') : $service->service_slug); ?>" tabindex="1" readonly="readonly">
                <span style="text-align: center;" class="invalid-feedback col-md-12 slug-name-err" id="err_title" ><?php if($errors->has('service_slug')): ?> <?php echo e($errors->first('service_slug')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-12" >
                <label class="col-md-3"><?php echo e(__('servicelang.session_time_label')); ?> <span class="de_col">*</span></label>
                <input maxlength="3" type="text" class="col-md-8 login_input session_time number" name="session_time" id="session_time" 
                placeholder="<?php echo e(__('servicelang.session_time_placeholder')); ?> " value="<?php echo e((old('session_time')) ?  old('session_time') : $service->session_time); ?>" 
                tabindex="1" >
                <span style="text-align: center;" class="invalid-feedback col-md-12 session_time-err" id="session_time" ><?php if($errors->has('session_time')): ?> <?php echo e($errors->first('session_time')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3"><?php echo e(__('lang.category_label')); ?></label>
                <select class="select2 col-md-8 login_input" name="categories[]" id="categories" multiple placeholder="Select" tabindex="3">
                  <option></option>
                  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_id=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($category['maincategory']); ?>">
                    <!--<option value="<?php echo e($cat_id); ?>"><?php echo e($category['maincategory']); ?></option>-->
                    <?php $__currentLoopData = $category['subcategories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat_id=>$subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(in_array($subcat_id,$selectedCategories)): ?>
                    <option selected="selected" value="<?php echo e($subcat_id); ?>"><?php echo e($subcategory); ?></option>
                    <?php else: ?>
                    <option value="<?php echo e($subcat_id); ?>"><?php echo e($subcategory); ?></option>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_find_us" ><?php if($errors->has('categories')): ?> <?php echo e($errors->first('categories')); ?><?php endif; ?></span>
              </div>


              <div class="form-group col-md-12" style="display:none;">
                  <label class="col-md-3"><?php echo e(__('lang.sort_order_label')); ?> <span class="de_col"></span></label>
                  <input type="tel" class="col-md-8 login_input" name="sort_order" id="sort_order"
                   placeholder="<?php echo e(__('lang.sort_order_label')); ?>" 
                   value="<?php echo e((old('sort_order')) ?  old('sort_order') : $service->sort_order); ?>" tabindex="7">
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_meta_keyword" ><?php if($errors->has('sort_order')): ?> <?php echo e($errors->first('sort_order')); ?><?php endif; ?> </span>
              </div>

              <label class="col-md-3"><?php echo e(__('servicelang.service_description_label')); ?>  <span class="de_col"></span></label>
                  
              <div class="form-group col-md-8">
                  <textarea class="col-md-12 login_input" name="description" id="description"
                   placeholder="<?php echo e(__('lang.service_description_label')); ?>" value="" 
                   tabindex="2"><?php echo e((old('description')) ?  old('description') : $service->description); ?></textarea>
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_description" ><?php if($errors->has('description')): ?> <?php echo e($errors->first('description')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3"><?php echo e(__('lang.status_label')); ?> </label>
                <select class="select2 col-md-8 login_input" name="status" id="status"  placeholder="Select" tabindex="8" >
                    <option <?php if($service->status=='active'): ?> selected="selected" <?php endif; ?> value="active">Active</option>
                    <option <?php if($service->status=='block'): ?> selected="selected" <?php endif; ?> value="block">Block</option>
                </select>
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="err_find_us" ><?php if($errors->has('status')): ?> <?php echo e($errors->first('status')); ?><?php endif; ?></span>
              </div>

            
              <div class="form-group col-md-12">
                  <label class="col-md-3"><?php echo e(__('lang.service_price')); ?> <span class="de_col">*</span></label>
                  <input type="tel" class="number col-md-8 service_price" name="service_price" id="service_price"
                   placeholder="<?php echo e(__('lang.service_price')); ?>" 
                   value="<?php echo e((old('service_price')) ?  old('service_price') : $service->service_price); ?>" tabindex="7">
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_price" ><?php if($errors->has('service_price')): ?> <?php echo e($errors->first('service_price')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-12">
                <label class="col-md-3"><?php echo e(__('lang.images')); ?> </label>
                <input type="file" class="col-md-8 login_input image service_image" >
                <div class="images col-md-12">
                  <?php
                    $images = explode(',',$service->images);
                    ?>
                    <?php if(!empty($images)): ?>
                      <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($image!=''): ?>
                          <input type="hidden" class="form-control login_input hidden_images" value="<?php echo e($image); ?>"  name="hidden_images[]">
                          <img src="<?php echo e(url('/')); ?>/uploads/ServiceImages/<?php echo e($image); ?>" width="70" height="70">
                          <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
              </div>


        
              <h2  class="col-md-12"><?php echo e(__('servicelang.step_2')); ?></h2>
              <div class="form-group col-md-2">
                    <label class="col-md-12"><?php echo e(__('lang.service_year')); ?> <span class="de_col">*</span></label>
                    
                    <select class="col-md-12 service_year" name="service_year" id="service_year" >
                      <option value=""><?php echo e(__('lang.select_label')); ?></option>
                      <?php
                        for($i=date('Y'); $i<'2050';$i++) {
                          ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                    <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_year" ><?php if($errors->has('service_year')): ?> <?php echo e($errors->first('service_year')); ?><?php endif; ?> </span>
              </div>
              <div class="form-group col-md-3">
                    <label class="col-md-12"><?php echo e(__('lang.service_month')); ?> <span class="de_col">*</span></label>
                    <select class="col-md-12 service_month" name="service_month" id="service_month" >
                      <option value=""><?php echo e(__('lang.select_label')); ?></option>
                      <?php
                        for ($i = 1; $i <= 12; $i++) {
                          $timestamp = date('01-'.$i.'-'.date('Y'));
                          ?>
                          <option value="<?php echo date('m', strtotime($timestamp)); ?>"><?php echo date('F', strtotime($timestamp)); ?></option>
                          <?php
                        }
                      ?>
                    </select><span style="text-align: center;" class="invalid-feedback col-md-12" id="service_month" ><?php if($errors->has('service_month')): ?> <?php echo e($errors->first('service_month')); ?><?php endif; ?> </span>
              </div>
              <div class="form-group col-md-2">
                  <label class="col-md-12"><?php echo e(__('lang.service_date')); ?> <span class="de_col">*</span></label>
                  <select class="col-md-12 service_date" name="service_date" id="service_date" >
                    <option value=""><?php echo e(__('lang.select_label')); ?></option>
                    <?php
                      for ($i = 1; $i <=31; $i++) {
                        
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                  <span style="text-align: center;" class="invalid-feedback col-md-12" id="service_date" ><?php if($errors->has('service_date')): ?> <?php echo e($errors->first('service_date')); ?><?php endif; ?> </span>
              </div>

              <div class="form-group col-md-3">
                <label class="col-md-12"><?php echo e(__('lang.start_time')); ?> <span class="de_col">*</span></label>
                <input type="tel" class="col-md-12 start_time" name="start_time" id="start_time" placeholder="00:00" value="<?php echo e((old('start_time')) ?  old('start_time') :''); ?>" tabindex="7">
                <span style="text-align: center;" class="invalid-feedback col-md-12" id="start_time" ><?php if($errors->has('start_time')): ?> <?php echo e($errors->first('start_time')); ?><?php endif; ?> </span>
              </div>

              <div class="col-md-2 text-center">
                <label class="col-md-12">&nbsp;</label>
                <a href="javascript:void(0);" name="save_service_date" id="save_service_date" class="btn btn-black debg_color login_btn " tabindex="9"><?php echo e(__('lang.save_service_date_btn')); ?></a>
              </div>

              <div class="added_service_times" style="display:none;">
                
                  <?php if(!empty($serviceAvailability)): ?>
                    <?php $__currentLoopData = $serviceAvailability; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php $service_time  = $availability['service_date'].' '.$availability['start_time']; ?>
                        <input type="hidden" id="<?php echo e($availability['id']); ?>" class="form-control service_availability " value="<?php echo e($service_time); ?>"  name="service_availability[]">
                        
                      
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
                </div>
              <div  class="col-md-12" id="calendar" style="padding: 20px;"></div>
          </div>
        </div>
          <div class="col-md-12 text-center">&nbsp;</div>
          <div class="col-md-12 text-center">
            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveservice" tabindex="9"><?php echo e(__('lang.save_btn')); ?></button>

            <a href="<?php echo e($module_url); ?>" class="btn btn-black gray_color login_btn" tabindex="10"> <?php echo e(__('lang.cancel_btn')); ?></a>
          </div>
        </div>
      </div>
  </form>
</div> <!-- /container -->
<script>var siteUrl="<?php echo e(url('/')); ?>";</script>
<script type="text/javascript">
  /*function to check unique Slug name
  * @param  : Slug name
  */
  function checkServiceUniqueSlugName(){

    var slug_name= $('#title').val();
    var slug;
    $.ajax({
      url: "<?php echo e(url('/')); ?>"+'/manage-services/check-slugname/?slug_name='+slug_name,
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

<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.inputmask.bundle.js"></script>
  
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
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fullcalendar.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/moment.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/front/js/fullcalendar.min.js"></script>

<script type="text/javascript">//<![CDATA[


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
        
      });
    });
    console.log(events_array);
   // $('#calendar').fullCalendar('addEventSource', events_array);
    
  }
  $('#calendar').fullCalendar({
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
         var result = confirm("<?php echo e(__('lang.areYouSureToDeleteServiceTime')); ?>");
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
    if($('#service_month').val()=='' || $('#service_year').val()=='' || $('#service_date').val()==''
    || $('#start_time').val()=='00:00' || $('#start_time').val()=='') {
        alert("<?php echo e(__('lang.service_time_required')); ?>");
        return false;
    }
    var service_date  = new Date($('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val());
    var service_date_to_use  = $('#service_year').val()+'-'+$('#service_month').val()+
    '-'+$('#service_date').val()+' '+$('#start_time').val();
    //alert(service_date);
    if(service_date < new Date()) {
      alert("<?php echo e(__('lang.select_future_date')); ?>");
        return false;
    }
    var events_array = [{
        id: service_time_counter,
        title: $('#start_time').val(),
        start: new Date($('#service_year').val()+'-'+$('#service_month').val()+'-'+$('#service_date').val()),
        //tip: 'Sup dog.'
      }, ];
    $('#calendar').fullCalendar('addEventSource', events_array);
    $('#service_year').val('');
    $('#service_month').val('');
    $('#service_date').val('');
    $('#start_time').val('');
    $('.added_service_times').append('<input type="text" id="'+service_time_counter+'"  name="service_availability[]" value="'+service_date_to_use+'">');
  });
  
  
  //]]></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Services/edit.blade.php ENDPATH**/ ?>
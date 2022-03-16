@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
   ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
</style>
<div class="mid-section sellers_top_padding">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <div class="row">
        <div class="col-md-12"> 
          <div class="col-md-2 tijara-sidebar">
            @include ('Front.layout.sidebar_menu')
          </div>

          @if($subscribedError)
            <div class="alert alert-danger">{{$subscribedError}}</div>
          @endif

          <div class="col-md-10 tijara-content">
            <div class="seller_info">
              <div class="card">
            <div class="card-header row seller_header">
               <h2>{{ __('lang.attribute_form_label')}}</h2>
            </div>

            <div class="col-md-12 text-right" style="margin-top:30px;">
              <a href="{{route('frontProductAttributes')}}" title="{{ __('lang.back_to_list_label')}}" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
            </div>
            <div class="col-md-6">
              <div class="login_box">

                <form method="POST" id="product_attribute_form" action="{{route('frontAttributeUpdate', $id)}}" class="needs-validation" novalidate="">
                <!-- class="needs-validation" novalidate="" -->
                @csrf

                <div class="form-group">
                  <label class="product_table_heading">{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
                  <input type="text" class="form-control login_input" name="name" id="name" required tabindex="1" value="{{ (old('name')) ?  old('name') : $attributesDetails['name']}}">
                  <span class="invalid-feedback" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </span>
                </div>

                <div class="form-group">
                  <label class="product_table_heading">{{ __('lang.type_label')}} <span class="de_col">*</span></label>
                  <select class="form-control login_input" id="type" name="type">
                    <option value="">{{ __('lang.select_label')}}</option>
                    <?php /*<option value="radio"  @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='radio')) {{ 'selected' }} @endif>{{ __('lang.radio_label')}}</option>  */?>
                    <option value="dropdown"  @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='dropdown')) {{ 'selected' }} @endif>{{ __('lang.dropdown_label')}}</option>
                    <?php /*<option value="textbox" @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='textbox')) {{ 'selected' }} @endif>{{ __('lang.textbox_label')}}</option>*/?>
                  </select>
                  <span class="invalid-feedback" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
                </div>

                <!--  edit values -->
                @if(!empty($segment) && $segment=='edit')
                <div class="form-group">
                  <label class="product_table_heading">{{ __('lang.type_label')}} <span class="de_col">*</span></label>
                  <div class="field_wrapper">
                  @if(!empty($attributesValues) && count($attributesValues) !=0)
                  @foreach ($attributesValues as $key=>$values)
                  <div>
                  <input type="text" class="form-control login_input attribute_values" name="attribute_values[]" id="attribute_values_{{$values->id}}" required  value="{{ (old('attribute_values')) ?  old('attribute_values') : $values->attribute_values}}" style="width:80%;margin-top:25px;">
                  <span class="invalid-feedback" id="err_fname" style="float:left;margin-left: 15px;margin-top:10px;"></span>
                  </div>
                  <input type="hidden" name="attribute_id[]" id="attribute_id_{{$key+1}}" value="{{ (old('id')) ?  old('id') : $values->id}}">

                  <a href="javascript:void(0);" class="btn btn-black gray_color login_btn remove_button" id="remove_button_{{$values->id}}"
                    title="Remove Values"  style="float:right;margin-top: -35px;">X</a>
                  
                  @endforeach
                  @endif
                  </div>
                </div> 
                <a href="javascript:void(0);" class="btn btn-black debg_color login_btn  add_button " title="Add field"  
                style="float:right;margin-top:5%;margin-left: 10%;font-size: 20px;
                "><i class="fas fa-plus"></i></a>

                <!--  end edit values -->

                <!--  add values -->
                @else
                <div class="form-group">
                  <label class="product_table_heading">Attribute Values <span class="de_col">*</span></label>
                  <div class="field_wrapper">
                  <input type="text" class="form-control login_input attribute_values" name="attribute_values[]" id="attribute_values" required  value="" style="width:80%;margin-top:25px;">
                  <span class="invalid-feedback" id="err_fname" style="float:left;margin-left: 15px;margin-top:10px;"></span>
                  </div>
                </div> 

                <button type="button" class="btn btn-black debg_color login_btn add_button" title="Add field"  style="float:right;margin-top: 7px;margin-left: 20px;font-size: 20px;"><i class="fas fa-plus"></i></button>
                @endif
                
              </div>
            </div>
          </div>
        </div>
          </div>
        </div><!-- col 12 end -->
        <div class="col-md-12 text-center attribute-btn" style="margin:30px;">
          <button class="btn btn-black debg_color login_btn save_att_val">{{ __('lang.save_btn')}}</button>
                <a href="{{route('frontProductAttributes')}}" class="btn btn-black gray_color login_btn" tabindex="16"> {{ __('lang.cancel_btn')}}</a>

                </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /container -->
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
  var addButton = $('.add_button'); //Add button selector
  var wrapper = $('.field_wrapper'); //Input field wrapper
  var valueHTML = '<div class="form-group"><input type="text" class="form-control login_input attribute_values" name="attribute_values[]" id="attribute_values" required  value="" style="width:80%;margin-top:25px;"><span class="invalid-feedback" id="err_fname" style="float:left;margin-left: 15px;margin-top:10px;"></span><a href="javascript:void(0);" class="btn btn-black gray_color login_btn remove_button" title="Remove Values"  style="float:right;margin-top:-35px;">X</a></div>'; //New input field html 

  var x = 1; //Initial field counter is 1
  //Once add button is clicked
  $(document).on("click", ".add_button", function () {
  // $(addButton).click(function(){
  var valueHTML = '<div><input type="text" class="form-control login_input attribute_values" name="attribute_values[]" id="attribute_values" required  value="" style="width:80%;margin-top:37px;"><span class="invalid-feedback" id="err_att_val" style="float:left;margin-left: 15px;margin-top:10px;"></span><a href="javascript:void(0);" class="btn btn-black gray_color login_btn remove_button" title="Remove Values"  style="float:right;margin-top:-35px;"><span style="color:#fff;">X</span></a></div>'; //New input field html 
  x++; //Increment field counter
  $(wrapper).append(valueHTML); //Add field html

  });
  $(wrapper).on('click', '.remove_button', function(e){

  var textbox_value = $(this).prev().prev().find("input").attr("id");  
  if(textbox_value){

  var split = textbox_value.split("_");
  id = split[2];
  if(id !== null && id !== '') {

  $.ajax({
  url: "{{url('/')}}"+'/product-attributes/deleteAttributeValue',
  type: 'POST',
  data: {
  "_token": "{{ csrf_token() }}",
  "id":id
  },
  success: function(output){
  console.log(output);
  $("#attribute_values_" + id).fadeOut('slow');
  $("#remove_button_"+id).fadeOut('slow');
  } 
  });
  }
  }else{
  e.preventDefault();
  $(this).parent('div').remove(); //Remove field html
  x--; //Decrement field counter
  }
  });

 $(".save_att_val").click(function(e){
    var error = 0;
     
    
    $( ".attribute_values:visible" ).each(function() {
      if($(this).val()=='') {
          $(this).next('.invalid-feedback').html(required_field_error);
          error = 1;
      }
      else{
        $(this).next('.invalid-feedback').html('');
        error = 0;
      }
      
  });
 /* $( ".add_attribute_group_td" ).each(function() {
    if($(this).find('.added_attributes_each_div').length<=0) {
        $(this).find('.added_attributes').html('<span style="color:red;">'+required_field_error+'</span>');
        error = 1;
    }
});*/

  if(error == 1)
  {
    return false;
  }
  else
  {
     return true;
   // $('#product_attribute_form').submit();
   
  }

  });

</script>

@endsection
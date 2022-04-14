@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
   <h2 class="section-title">{{$pageTitle}}</h2>
   <p class="section-lead">{{ __('users.edit_banner_details_title')}}</p>
   <form method="POST" action="{{route('adminAttributeUpdate', $id)}}" class="needs-validation" enctype="multipart/form-data"  novalidate="">
   @csrf
   <div class="row">
      <div class=" col-md-6 col-lg-6 ">
         <div class="card">
            <div class="card-body">
              <div class="form-group">
                  <label>{{ __('lang.attribute_label')}} <span class="de_col">*</span></label>
                  <input type="text" class="form-control" name="name" id="name" required tabindex="1" value="{{ (old('name')) ?  old('name') : $attributesDetails['name']}}">
                  <div class="text-danger" id="err_fname">@if($errors->has('name')) {{ $errors->first('name') }}@endif </div>
              </div>
               <div class="form-group">
                  <label>{{ __('lang.type_label')}} <span class="de_col">*</span></label>
                  <select class="form-control login_input" id="type" name="type">
                    <option value="">{{ __('lang.select_label')}}</option>
                    <option value="radio"  @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='radio')) {{ 'selected' }} @endif>{{ __('lang.radio_label')}}</option>  
                    <option value="dropdown"  @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='dropdown')) {{ 'selected' }} @endif>{{ __('lang.dropdown_label')}}</option>
                    <option value="textbox" @if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='textbox')) {{ 'selected' }} @endif>{{ __('lang.textbox_label')}}</option>
                  </select>
                   <span class="text-danger" id="err_fname">@if($errors->has('type')) {{ $errors->first('type') }}@endif </span>
               </div> 


                  <!--  edit values -->
                  @if(!empty($segment) && $segment=='edit')
                    <div class="form-group">
                      <label>{{ __('lang.type_label')}} <span class="de_col">*</span></label>
                      <div class="field_wrapper">
                      @if(!empty($attributesValues) && count($attributesValues) !=0)
                      @foreach ($attributesValues as $key=>$values)
                      <div>
                      <input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values_{{$values->id}}" required  value="{{ (old('attribute_values')) ?  old('attribute_values') : $values->attribute_values}}" style="float:left;width:80%;margin-top:10px;">

                      <input type="hidden" name="attribute_id[]" id="attribute_id_{{$key+1}}" value="{{ (old('id')) ?  old('id') : $values->id}}">

                      <button type="button" class="btn btn-danger remove_button" id="remove_button_{{$values->id}}" title="Remove Values d"  style="margin-left:15px;margin-top: 15px;">X</button>
                      </div>
                      @endforeach
                      @endif
                      </div>
                    </div>

                     <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top:5%;margin-left: 10%;font-size: 20px;">+</button>

                      <!--  end edit values -->
                      <!--  add values -->
                  @else
                     <div class="form-group">
                      <label>Attribute Values <span class="de_col">*</span></label>
                      <div class="field_wrapper">
                      <input type="text" class="form-control" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%">
                      <div class="text-danger" id="err_fname">@if($errors->has('attribute_values')) {{ $errors->first('attribute_values') }}@endif </div>
                      </div>
                           
                    </div> 
                    <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top: 20px;margin-left: 20px;font-size: 20px;">+</button>
                  @endif
                  
                
            </div>
         <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
          
         </div>
         <div class="col-12 ">
            <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
            <a href="{{route('adminProductAttributes')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
         </div>
      </div>
         </div>
      </div>
      
   </div>
   </form>
</div>
<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
   var addButton = $('.add_button'); //Add button selector
   var wrapper = $('.field_wrapper'); //Input field wrapper
   var valueHTML = '<div><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%;margin-top:10px;"><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></div>'; //New input field html 
   var x = 1; //Initial field counter is 1
   //Once add button is clicked
   $(document).on("click", ".add_button", function () {
   // $(addButton).click(function(){
       var valueHTML = '<div><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%;margin-top:10px;"><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:15px;margin-right:82px;">X</button></div>'; //New input field html 
         x++; //Increment field counter
            $(wrapper).append(valueHTML); //Add field html
       
    });
   $(wrapper).on('click', '.remove_button', function(e){
      var textbox_value = $(this).prev().prev().attr("id");  
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
  
</script>
@endsection('middlecontent')
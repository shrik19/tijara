
<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
  <div class="col-md-2">
        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
        <?php if($subscribedError): ?>
	    <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
	    <?php endif; ?>
      <div class="col-md-1"></div> 
      <div class="col-md-6">
        <h2><?php echo e(__('lang.attribute_form_label')); ?></h2>
        <hr class="heading_line"/>
        <div class="login_box">
        <form method="POST" action="<?php echo e(route('frontAttributeUpdate', $id)); ?>" class="needs-validation" novalidate="">
          <!-- class="needs-validation" novalidate="" -->
          <?php echo csrf_field(); ?>

          <div class="form-group">
            <label><?php echo e(__('lang.attribute_label')); ?> <span class="de_col">*</span></label>
            <input type="text" class="form-control login_input" name="name" id="name" required tabindex="1" value="<?php echo e((old('name')) ?  old('name') : $attributesDetails['name']); ?>">
            <span class="invalid-feedback" id="err_fname"><?php if($errors->has('name')): ?> <?php echo e($errors->first('name')); ?><?php endif; ?> </span>
          </div>

          <div class="form-group">
            <label><?php echo e(__('lang.type_label')); ?> <span class="de_col">*</span></label>
            <select class="form-control login_input" id="type" name="type">
              <option value=""><?php echo e(__('lang.select_label')); ?></option>
              <option value="radio"  <?php if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='radio')): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo e(__('lang.radio_label')); ?></option>  
              <option value="dropdown"  <?php if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='dropdown')): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo e(__('lang.dropdown_label')); ?></option>
              <option value="textbox" <?php if(isset( $attributesDetails['type']) && ( $attributesDetails['type']=='textbox')): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo e(__('lang.textbox_label')); ?></option>
            </select>
            <span class="invalid-feedback" id="err_fname"><?php if($errors->has('type')): ?> <?php echo e($errors->first('type')); ?><?php endif; ?> </span>
          </div>

          <!--  edit values -->
          <?php if(!empty($segment) && $segment=='edit'): ?>
          <div class="form-group">
            <label><?php echo e(__('lang.type_label')); ?> <span class="de_col">*</span></label>
            <div class="field_wrapper">
              <?php if(!empty($attributesValues) && count($attributesValues) !=0): ?>
                <?php $__currentLoopData = $attributesValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                  <input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values_<?php echo e($values->id); ?>" required  value="<?php echo e((old('attribute_values')) ?  old('attribute_values') : $values->attribute_values); ?>" style="float:left;width:80%;margin-top:10px;">

                  <input type="hidden" name="attribute_id[]" id="attribute_id_<?php echo e($key+1); ?>" value="<?php echo e((old('id')) ?  old('id') : $values->id); ?>">

                  <button type="button" class="btn btn-danger remove_button" id="remove_button_<?php echo e($values->id); ?>" title="Remove Values"  style="float:right;margin-top: 16px;">X</button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
            </div>
          </div> 
          <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top:5%;margin-left: 10%;font-size: 20px;">+</button>

          <!--  end edit values -->

          <!--  add values -->
          <?php else: ?>
            <div class="form-group">
              <label>Attribute Values <span class="de_col">*</span></label>
              <div class="field_wrapper">
              <input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%">
              </div>
            </div> 
            <button type="button" class="btn btn-success add_button" title="Add field"  style="float:right;margin-top: 20px;margin-left: 20px;font-size: 20px;">+</button>
          <?php endif; ?>
          <button class="btn btn-black debg_color login_btn"><?php echo e(__('lang.save_btn')); ?></button>
          <a href="<?php echo e(route('frontProductAttributes')); ?>" class="btn btn-black gray_color login_btn" tabindex="16"> <?php echo e(__('lang.cancel_btn')); ?></a>

        </form>
        </div>
     
    </div>
  </div>
</div> <!-- /container -->
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
   var addButton = $('.add_button'); //Add button selector
   var wrapper = $('.field_wrapper'); //Input field wrapper
   var valueHTML = '<div><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%;margin-top:10px;"><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></div>'; //New input field html 

   var x = 1; //Initial field counter is 1
   //Once add button is clicked
   $(document).on("click", ".add_button", function () {
   // $(addButton).click(function(){
       var valueHTML = '<div><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" style="float:left;width:80%;margin-top:10px;"><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></div>'; //New input field html 
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
            url: "<?php echo e(url('/')); ?>"+'/product-attributes/deleteAttributeValue',
            type: 'POST',
            data: {
               "_token": "<?php echo e(csrf_token()); ?>",
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/ProductAttributes/edit.blade.php ENDPATH**/ ?>
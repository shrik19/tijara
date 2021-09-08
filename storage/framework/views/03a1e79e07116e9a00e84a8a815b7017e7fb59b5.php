
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
      <div class="col-md-2">
        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <div class="col-md-10">
  <?php if($is_seller): ?>    
  <form id="product-form" action="<?php echo e(route('frontProductStore')); ?>" method="post" enctype="multipart/form-data">
  <?php else: ?>
  <form id="product-form" action="<?php echo e(route('frontProductShowCheckout')); ?>" method="post" enctype="multipart/form-data">
  <?php endif; ?>
            <?php echo csrf_field(); ?>
  <div class="row">


	<div class="col-md-10">

		  <h2><?php echo e(__('lang.product_form_label')); ?></h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-2 text-right" style="margin-top:30px;">
		  <a href="<?php echo e(route('manageFrontProducts')); ?>" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;<?php echo e(__('lang.back_to_list_label')); ?></span> </a>
      </div>

         <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="col-md-12">

        <div class="login_box">

            <h2 class="col-md-12"><?php echo e(__('lang.product_form_step1')); ?></h2>
            <input type="hidden" name="product_id" value="<?php echo e($product_id); ?>">

            <div class="form-group col-md-12">
              <label class="col-md-3"><?php echo e(__('lang.product_title_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="col-md-8 login_input" name="title" id="title" placeholder="<?php echo e(__('lang.product_title_label')); ?> " value="<?php echo e(old('title')); ?>" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('title')): ?> <?php echo e($errors->first('title')); ?><?php endif; ?> </span>
            </div>

            
            <div class="form-group col-md-12">
              <label class="col-md-3" ><?php echo e(__('lang.category_label')); ?></label>
              <select class="select2 col-md-8 login_input" name="categories[]" id="categories" multiple placeholder="<?php echo e(__('lang.category_label')); ?>" tabindex="3">
                <option></option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_id=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <optgroup label="<?php echo e($category['maincategory']); ?>">
                <!--<option value="<?php echo e($cat_id); ?>"><?php echo e($category['maincategory']); ?></option>-->
                <?php $__currentLoopData = $category['subcategories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat_id=>$subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($subcat_id); ?>"><?php echo e($subcategory); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" ><?php if($errors->has('categories')): ?> <?php echo e($errors->first('categories')); ?><?php endif; ?></span>
            </div>

            <div class="form-group col-md-12" style="display:none;">
              <label class="col-md-6"><?php echo e(__('lang.product_slug_label')); ?> <span class="de_col">*</span></label>
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="col-md-6 form-control login_input slug-name" name="product_slug" id="product_slug" placeholder="<?php echo e(__('lang.product_slug_label')); ?> " value="<?php echo e(old('product_slug')); ?>" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" ><?php if($errors->has('product_slug')): ?> <?php echo e($errors->first('product_slug')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-12" >
              <label class="col-md-3"><?php echo e(__('lang.meta_title_label')); ?> <span class="de_col"></span></label>
              <input type="text" class="col-md-8 login_input" name="meta_title" id="meta_title" placeholder="<?php echo e(__('lang.meta_title_label')); ?>" value="<?php echo e(old('meta_title')); ?>" tabindex="4">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_title" ><?php if($errors->has('meta_title')): ?> <?php echo e($errors->first('meta_title')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-12">
              <label class="col-md-3"><?php echo e(__('lang.meta_desc_label')); ?> <span class="de_col"></span></label>
              <input type="text" class="col-md-8 login_input" name="meta_description" id="meta_description" placeholder="<?php echo e(__('lang.meta_desc_label')); ?>" value="<?php echo e(old('meta_description')); ?>" tabindex="5">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_description" ><?php if($errors->has('meta_description')): ?> <?php echo e($errors->first('meta_description')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-12">
              <label class="col-md-3"><?php echo e(__('lang.meta_keyword_label')); ?>  <span class="de_col"></span></label>
              <input type="text" class="col-md-8 login_input" name="meta_keyword" id="meta_keyword" placeholder="<?php echo e(__('lang.meta_keyword_label')); ?>" value="<?php echo e(old('meta_keyword')); ?>" tabindex="6">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" ><?php if($errors->has('meta_keyword')): ?> <?php echo e($errors->first('meta_keyword')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group  col-md-12">
              <label class="col-md-3"><?php echo e(__('lang.status_label')); ?> </label>
              <select class="select2 col-md-8 login_input" name="status" id="status"  placeholder="" tabindex="8" >
                <option value="active"><?php echo e(__('lang.active_label')); ?></option>
                <option value="block"><?php echo e(__('lang.block_label')); ?></option>
                </select>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" ><?php if($errors->has('status')): ?> <?php echo e($errors->first('status')); ?><?php endif; ?></span>
            </div>

            <div class="form-group col-md-12">
              <label class="col-md-3"><?php echo e(__('lang.product_discount_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="col-md-8 login_input number" name="discount" id="discount" placeholder="<?php echo e(__('lang.product_discount_label')); ?> " value="<?php echo e(old('discount')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_discount" ><?php if($errors->has('discount')): ?> <?php echo e($errors->first('discount')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group  col-md-12"  style="display:none;">
              <label class="col-md-3"><?php echo e(__('lang.sort_order_label')); ?> <span class="de_col"></span></label>
              <input type="tel" class="col-md-8 login_input" name="sort_order" id="sort_order" placeholder="<?php echo e(__('lang.sort_order_label')); ?>" value="<?php echo e((old('sort_order')) ?  old('sort_order') : $max_seq_no); ?>" tabindex="7">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" ><?php if($errors->has('sort_order')): ?> <?php echo e($errors->first('sort_order')); ?><?php endif; ?> </span>
            </div>
            <label class="col-md-3"><?php echo e(__('lang.product_description_label')); ?>  <span class="de_col"></span></label>
              
      			<div class="form-group col-md-8">
              <textarea class="col-md-8 login_input" name="description" id="description" placeholder="<?php echo e(__('lang.product_description_label')); ?>" value="" tabindex="2"><?php echo e(old('description')); ?></textarea>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_description" ><?php if($errors->has('description')): ?> <?php echo e($errors->first('description')); ?><?php endif; ?> </span>
            </div>

            <h2 class="col-md-12"><?php echo e(__('lang.product_form_step2')); ?></h2>
            <div  class="col-md-12" id="variant_table">
              <?php

              $i  = 0; ?>

            
                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                 
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.sku_label')); ?> <span class="de_col"></span></label>
                    <input type="text" class="col-md-8 login_input sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.sku_placeholder')); ?>" value='<?php echo e(old("sku.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.weight_label')); ?> <span class="de_col"></span></label>
                    <input type="text" class="col-md-8 login_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.weight_placeholder')); ?>" value='<?php echo e(old("weight.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.price_label')); ?> <span class="de_col"></span></label>
                    <input type="tel" class="col-md-8 login_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.price_placeholder')); ?>" value='<?php echo e(old("price.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.qty_label')); ?> <span class="de_col"></span></label>
                    <input type="tel" class="col-md-8 login_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.qty_label')); ?>" value='<?php echo e(old("quantity.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.select_attribute_label')); ?> <span class="de_col"></span></label>
                    <select class="col-md-4 login_input select_attribute variant_field" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                      <option value=""><?php echo e(__('lang.select_label')); ?> <?php echo e(__('lang.attribute_label')); ?></option>

                        <?php $__currentLoopData = $attributesToSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($attr->id); ?>"  ><?php echo e($attr->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select style="margin-left: 10px;" selected_attribute_value="" class="variant_field col-md-4 login_input select_attribute_value variant_field" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                      <option value=""><?php echo e(__('lang.select_label')); ?> <?php echo e(__('lang.attribute_value_label')); ?></option>

                    </select>
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  
                  <div class="form-group  col-md-12" >
                    <label class="col-md-3"><?php echo e(__('lang.image_label')); ?> <span class="de_col"></span></label>
                    <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 login_input image  variant_image" name="image[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.image_label')); ?>" value='<?php echo e(old("image.$i")); ?>' tabindex="7">
                    
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="selected_images col-md-12"></div>
                  <div class="remove_variant_div"></div>
                  <hr class="separator" style="
                      border: 1px solid darkseagreen;
                      
                  ">
                </div>
              
                
               <div class="col-md-4 text-right">
                  <a title="<?php echo e(__('lang.add_variant_btn')); ?>" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" ><span><i class="fa fa-plus"></i><?php echo e(__('lang.add_variant_btn')); ?></span> </a>
               </div>
              <div class="all_saved_attributes" ></div>
            </div>
            <h2 class="col-md-12"><?php echo e(__('lang.product_form_step3')); ?></h2>

            <div class="form-group col-md-12" id="shipping_method_ddl_div">
              <label class="col-md-3"><?php echo e(__('users.shipping_method_label')); ?></label>
              <select class="col-md-8 login_input" name="shipping_method_ddl" id="shipping_method_ddl">
                <option value=""><?php echo e(__('users.select_shipping_method')); ?></option>
                <option value="Platta fraktkostnader"><?php echo e(__('users.flat_shipping_charges')); ?></option>
                <option value="Andel fraktkostnader"><?php echo e(__('users.prcentage_shipping_charges')); ?></option>
              </select>
            </div>

            <div class="form-group col-md-12" id="shipping_charges_div">
              <label class="col-md-3"><?php echo e(__('users.shipping_charges_label')); ?></label>
              <input type="text" class="col-md-8 login_input" name="shipping_charges" id="shipping_charges" placeholder="<?php echo e(__('users.shipping_charges_label')); ?>" value="<?php echo e((old('shipping_charges'))); ?>">
            </div>
        </div>
      </div>
      
  </div>
  <div class="row">

      
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12 text-center">
              <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9"><?php echo e(__('lang.save_btn')); ?></button>

              <a href="<?php echo e($module_url); ?>" class="btn btn-black gray_color login_btn" tabindex="10"> <?php echo e(__('lang.cancel_btn')); ?></a>
            </div>


  </div>

  </form>
            </div>
</div> <!-- /container -->
<script>var siteUrl="<?php echo e(url('/')); ?>";</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Products/create.blade.php ENDPATH**/ ?>
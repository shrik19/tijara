
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
  
  
  <form id="product-form" action="<?php echo e(route('frontProductStore')); ?>" method="post" enctype="multipart/form-data">
  
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

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_buyer_name')); ?> <span class="de_col">*</span></label>
              <input type="text" required class="login_input form-control" name="user_name" id="user_name" placeholder="<?php echo e(__('lang.product_buyer_name')); ?> " value="<?php echo e(old('user_name')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('user_name')): ?> <?php echo e($errors->first('user_name')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_buyer_email')); ?> <span class="de_col">*</span></label>
              <input type="email" required class="login_input form-control" name="user_email" id="user_email" placeholder="<?php echo e(__('lang.product_buyer_email')); ?> " value="<?php echo e(old('user_email')); ?>" tabindex="1" >
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('user_email')): ?> <?php echo e($errors->first('user_email')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_buyer_phone_no')); ?> <span class="de_col">*</span></label>
              <input type="tel" required class="login_input form-control" name="user_phone_no" id="user_phone_no" placeholder="<?php echo e(__('lang.product_buyer_phone_no')); ?> " value="<?php echo e(old('user_phone_no')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('user_phone_no')): ?> <?php echo e($errors->first('user_phone_no')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_country')); ?> <span class="de_col">*</span></label>
              <input type="text" class="login_input form-control" name="country" id="country" placeholder="<?php echo e(__('lang.product_country')); ?> " value="<?php echo e(old('country')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('country')): ?> <?php echo e($errors->first('country')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_location')); ?> <span class="de_col">*</span></label>
              <input type="text" class="login_input form-control" name="location" id="location" placeholder="<?php echo e(__('lang.product_location')); ?> " value="<?php echo e(old('location')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('location')): ?> <?php echo e($errors->first('location')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.product_title_label')); ?> <span class="de_col">*</span></label>
              <input type="text" class="login_input form-control" name="title" id="title" placeholder="<?php echo e(__('lang.product_title_label')); ?> " value="<?php echo e(old('title')); ?>" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" ><?php if($errors->has('title')): ?> <?php echo e($errors->first('title')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6" style="display:none;">
              <label class="col-md-12" class="col-md-6"><?php echo e(__('lang.product_slug_label')); ?> <span class="de_col">*</span></label>
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="col-md-6 form-control login_input form-control slug-name" name="product_slug" id="product_slug" placeholder="<?php echo e(__('lang.product_slug_label')); ?> " value="<?php echo e(old('product_slug')); ?>" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" ><?php if($errors->has('product_slug')): ?> <?php echo e($errors->first('product_slug')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6" >
              <label class="col-md-12" ><?php echo e(__('lang.meta_title_label')); ?> <span class="de_col"></span></label>
              <input type="text" class="login_input form-control" name="meta_title" id="meta_title" placeholder="<?php echo e(__('lang.meta_title_label')); ?>" value="<?php echo e(old('meta_title')); ?>" tabindex="4">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_title" ><?php if($errors->has('meta_title')): ?> <?php echo e($errors->first('meta_title')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.meta_desc_label')); ?> <span class="de_col"></span></label>
              <input type="text" class="login_input form-control" name="meta_description" id="meta_description" placeholder="<?php echo e(__('lang.meta_desc_label')); ?>" value="<?php echo e(old('meta_description')); ?>" tabindex="5">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_description" ><?php if($errors->has('meta_description')): ?> <?php echo e($errors->first('meta_description')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.meta_keyword_label')); ?>  <span class="de_col"></span></label>
              <input type="text" class="login_input form-control" name="meta_keyword" id="meta_keyword" placeholder="<?php echo e(__('lang.meta_keyword_label')); ?>" value="<?php echo e(old('meta_keyword')); ?>" tabindex="6">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" ><?php if($errors->has('meta_keyword')): ?> <?php echo e($errors->first('meta_keyword')); ?><?php endif; ?> </span>
            </div>
            <div class="form-group  col-md-6">
              <label class="col-md-12" ><?php echo e(__('lang.status_label')); ?> </label>
              <select class="select2 login_input form-control" name="status" id="status"  placeholder="" tabindex="8" >
                <option value="active"><?php echo e(__('lang.active_label')); ?></option>
                <option value="block"><?php echo e(__('lang.block_label')); ?></option>
                </select>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" ><?php if($errors->has('status')): ?> <?php echo e($errors->first('status')); ?><?php endif; ?></span>
            </div>

           
            <div class="form-group  col-md-6"  style="display:none;">
              <label class="col-md-12" ><?php echo e(__('lang.sort_order_label')); ?> <span class="de_col"></span></label>
              <input type="tel" class="login_input form-control" name="sort_order" id="sort_order" placeholder="<?php echo e(__('lang.sort_order_label')); ?>" value="<?php echo e((old('sort_order')) ?  old('sort_order') : $max_seq_no); ?>" tabindex="7">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" ><?php if($errors->has('sort_order')): ?> <?php echo e($errors->first('sort_order')); ?><?php endif; ?> </span>
            </div>
            
            <div class="form-group col-md-6">
              <label class="col-md-12"  ><?php echo e(__('lang.category_label')); ?></label>
              <select class="select2 login_input form-control" name="categories[]" id="categories" multiple placeholder="<?php echo e(__('lang.category_label')); ?>" tabindex="3">
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
            

            <div  class="col-md-12" id="variant_table">
              <?php

              $i  = 0; ?>

            
                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                 
                  <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12" ><?php echo e(__('lang.sku_label')); ?> <span class="de_col"></span></label>
                    <input type="text" class="login_input form-control sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.sku_placeholder')); ?>" value='123' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12" ><?php echo e(__('lang.weight_label')); ?> <span class="de_col"></span></label>
                    <input type="text" class="login_input form-control weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.weight_placeholder')); ?>" value='10' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="form-group  col-md-6" >
                    <label class="col-md-12" ><?php echo e(__('lang.price_label')); ?> <span class="de_col"></span></label>
                    <input type="tel" class="login_input form-control price number variant_field" name="price[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.price_placeholder')); ?>" value='<?php echo e(old("price.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12" ><?php echo e(__('lang.qty_label')); ?> <span class="de_col"></span></label>
                    <input type="tel" class="login_input form-control quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.qty_label')); ?>" value='1' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div style="display:none;" class="form-group  col-md-12" >
                    <label class="col-md-12" ><?php echo e(__('lang.select_attribute_label')); ?> <span class="de_col"></span></label>
                    <select id="0" class="preselected_attribute col-md-6 login_input form-control select_attribute variant_field" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                      
                        <?php $__currentLoopData = $attributesToSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($attr->id); ?>"  ><?php echo e($attr->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select style="margin-left: 10px;" selected_attribute_value="" class="buyer-product 0 variant_field col-md-6 login_input form-control select_attribute_value variant_field" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                      <option value=""><?php echo e(__('lang.select_label')); ?> <?php echo e(__('lang.attribute_value_label')); ?></option>

                    </select>
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  
                  <div class="form-group  col-md-6" >
                    <label class="col-md-12" ><?php echo e(__('lang.image_label')); ?> <span class="de_col"></span></label>
                    <input type="file" variant_id="<?php echo $i; ?>" class="login_input form-control image  variant_image" name="image[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.image_label')); ?>" value='<?php echo e(old("image.$i")); ?>' tabindex="7">
                    
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                  </div>
                  <div class="selected_images col-md-12"></div>
                  <div class="remove_variant_div"></div>
                  
                </div>
              
                
              
              <div class="all_saved_attributes" ></div>
            </div>
            <label class="col-md-12" ><?php echo e(__('lang.product_description_label')); ?>  <span class="de_col"></span></label>
              
      			
            <div class="form-group col-md-12">
              <textarea class="login_input form-control" name="description" id="description" placeholder="<?php echo e(__('lang.product_description_label')); ?>" value="" tabindex="2"><?php echo e(old('description')); ?></textarea>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_description" ><?php if($errors->has('description')): ?> <?php echo e($errors->first('description')); ?><?php endif; ?> </span>
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

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Products/buyer-create.blade.php ENDPATH**/ ?>
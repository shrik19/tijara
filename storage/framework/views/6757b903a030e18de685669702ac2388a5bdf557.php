
<?php $__env->startSection('middlecontent'); ?>
<style>
  ::placeholder{
    font-weight: 300 !important;
    color: #999 !important;
  }
  .login_box
  {
    width:100% !important;
  }
.invalid-feedback {
    position: relative !important;
}
.selected_images {
    background-image: url(../../uploads/Images/multiple_no_images.png);
    background-repeat: no-repeat;
    min-height: 85px;
    padding-left: 0;
    margin-bottom: 10px;
}

.selected_images>div {
     float: left;
    border: 2px solid #ccc;
    margin: 0 !important;
    position: relative;
  }

.selected_images a.remove_image {
    position: absolute;
    bottom: 0px;
    left: 3px;
}

</style>

<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1">
    <div class="row">
  <!-- Example row of columns -->
   <?php if($subscribedError): ?>
	    <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
	    <?php endif; ?>
      <div class="col-md-2 tijara-sidebar">
        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <div class="col-md-10">
      <div class="seller_info">
      <div class="seller_header">

<h2 class="seller_page_heading"><?php echo e(__('lang.product_form_label')); ?></h2>
<!-- <hr class="heading_line"/> -->
</div>
  <?php if($is_seller): ?>    
  <form id="product-form" class="tijara-form" action="<?php echo e(route('frontProductStore')); ?>" method="post" enctype="multipart/form-data">
  <?php else: ?>
  <form id="product-form" class="tijara-form" action="<?php echo e(route('frontProductShowCheckout')); ?>" method="post" enctype="multipart/form-data">
  <?php endif; ?>
            <?php echo csrf_field(); ?>

  

		  <div class="col-md-12 text-right" style="margin-top:30px;">
		  <a href="<?php echo e(route('manageFrontProducts')); ?>" title="<?php echo e(__('lang.back_to_list_label')); ?>" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;<?php echo e(__('lang.back_to_list_label')); ?></span> </a>
      </div>

         <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="col-md-12">

        <div class="login_box">

            <h2 class="col-md-12 product_add_h2 steps_no_css" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step1')); ?></h2>
            <input type="hidden" name="product_id" value="<?php echo e($product_id); ?>">

            <div class="form-group col-md-12">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_title_label')); ?> <span class="de_col">*</span></label>
              <div class="col-md-8">
              <input type="text" class="col-md-8 ge_input " name="title" id="title" placeholder="<?php echo e(__('lang.product_title_label')); ?> " value="<?php echo e(old('title')); ?>" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback col-md-8"  id="err_title"><?php if($errors->has('title')): ?> <?php echo e($errors->first('title')); ?><?php endif; ?> </span>
            </div>
            </div>

            <div class="form-group col-md-12">
            <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_description_label')); ?> <span class="de_col">*</span></label>
            <div class="col-md-8">
      			<div class="form-group  producterrDiv col-md-8 p-0">
              <textarea class="ge_input product_description col-md-8 " style="width:100%; height: 175px;" name="description"  placeholder="<?php echo e(__('users.service_description_placeholder')); ?>" value="" tabindex="2"><?php echo e(old('description')); ?></textarea>
              <span class="invalid-feedback  col-md-8"  id="err_description" ><?php if($errors->has('description')): ?> <?php echo e($errors->first('description')); ?><?php endif; ?> </span>
            </div>
          </div>
        </div>
            
            <div class="form-group col-md-12 producterrDiv">
              <label class="col-md-3 product_table_heading" ><?php echo e(__('lang.category_label')); ?> <span class="de_col">*</span></label>
              <div class="col-md-8">
              <select class="select2 col-md-8 ge_input" name="categories[]" id="categories" multiple placeholder="<?php echo e(__('lang.category_label')); ?>" tabindex="3">
                <option><?php echo e(__('lang.select_label')); ?></option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_id=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <optgroup label="<?php echo e($category['maincategory']); ?>">
                <!--<option value="<?php echo e($cat_id); ?>"><?php echo e($category['maincategory']); ?></option>-->
                <?php $__currentLoopData = $category['subcategories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat_id=>$subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($subcat_id); ?>"><?php echo e($subcategory); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span class="invalid-feedback  col-md-8" id="err_category" ><?php if($errors->has('categories')): ?> <?php echo e($errors->first('categories')); ?><?php endif; ?></span>
            </div>
            </div>

            <div class="form-group  col-md-12 producterrDiv">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.status_label')); ?> <span class="de_col">*</span></label>
              <div class="col-md-8">
              <select class="select2 col-md-8 ge_input" name="status" id="status"  placeholder="" tabindex="8" >
                <option value="active"><?php echo e(__('lang.active_label')); ?></option>
                <option value="block"><?php echo e(__('lang.block_label')); ?></option>
                </select>
              <span class="invalid-feedback col-md-12"  id="err_find_us" ><?php if($errors->has('status')): ?> <?php echo e($errors->first('status')); ?><?php endif; ?></span>
            </div>
            </div>
            <div class="form-group col-md-12" style="display:none;">
              <label class="col-md-6 product_table_heading"><?php echo e(__('lang.product_slug_label')); ?> <span class="de_col">*</span></label>
              <div class="col-md-8">
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="col-md-6 form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="<?php echo e(__('lang.product_slug_label')); ?> " value="<?php echo e(old('product_slug')); ?>" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" ><?php if($errors->has('product_slug')): ?> <?php echo e($errors->first('product_slug')); ?><?php endif; ?> </span>
            </div>
            </div>

            <div class="form-group col-md-12" style="display:none;">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_title_label')); ?> <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( <?php echo e(__('users.meta_title_info')); ?> )</p>
               <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_title" id="meta_title" placeholder="<?php echo e(__('lang.meta_title_label')); ?>" value="<?php echo e(old('meta_title')); ?>" tabindex="4">
              <span class="invalid-feedback col-md-8"  id="err_meta_title" ><?php if($errors->has('meta_title')): ?> <?php echo e($errors->first('meta_title')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-12" style="display:none;">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_desc_label')); ?> <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( <?php echo e(__('users.meta_desciption_info')); ?> )</p>
              <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_description" id="meta_description" placeholder="<?php echo e(__('lang.meta_desc_label')); ?>" value="<?php echo e(old('meta_description')); ?>" tabindex="5">
              <span class="invalid-feedback col-md-12"  id="err_meta_description" ><?php if($errors->has('meta_description')): ?> <?php echo e($errors->first('meta_description')); ?><?php endif; ?> </span>
            </div>

            <div class="form-group col-md-12" style="display:none;">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_keyword_label')); ?>  <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( <?php echo e(__('users.meta_keyword_info')); ?> )</p>
              <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_keyword" id="meta_keyword" placeholder="<?php echo e(__('lang.meta_keyword_label')); ?>" value="<?php echo e(old('meta_keyword')); ?>" tabindex="6">
              <span class="invalid-feedback col-md-8" id="err_meta_keyword" ><?php if($errors->has('meta_keyword')): ?> <?php echo e($errors->first('meta_keyword')); ?><?php endif; ?> </span>
            </div>
            

            <div class="form-group col-md-12">
              <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_discount_label')); ?></label>
              <div class="col-md-8">
              <input type="text" class="col-md-8 ge_input number" name="discount" id="discount" placeholder="<?php echo e(__('lang.product_discount_label')); ?> " value="<?php echo e(old('discount')); ?>" tabindex="1">
              <span class="invalid-feedback col-md-12" id="err_discount" ><?php if($errors->has('discount')): ?> <?php echo e($errors->first('discount')); ?><?php endif; ?> </span>
            </div>
            </div>
            <div class="form-group  col-md-12"  style="display:none;">
              <label class="col-md-3"><?php echo e(__('lang.sort_order_label')); ?> <span class="de_col"></span></label>
              <input type="tel" class="col-md-8 ge_input" name="sort_order" id="sort_order" placeholder="<?php echo e(__('lang.sort_order_label')); ?>" value="<?php echo e((old('sort_order')) ?  old('sort_order') : $max_seq_no); ?>" tabindex="7">
              <span class="invalid-feedback col-md-8"  id="err_meta_keyword" ><?php if($errors->has('sort_order')): ?> <?php echo e($errors->first('sort_order')); ?><?php endif; ?> </span>
            </div>
          

            <h2 class="col-md-12 product_add_h2 steps_no_css" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step2')); ?></h2>
            <div  class="col-md-12" id="variant_table">
              <?php

              $i  = 0; ?>

            
                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                 
                  <div class="form-group" >
                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.sku_label')); ?> <span class="de_col"></span></label>
                    <div class="col-md-8">
                    <input type="text" class="col-md-8 ge_input sku" name="sku[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.sku_placeholder')); ?>" value='<?php echo e(old("sku.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback  col-md-8"  id="err_sku" ></span>
                   </div>
                  </div>
                  <?php /*
                  <div class="form-group producterrDiv" >
                    <label class="col-md-3 product_table_heading">{{ __('lang.weight_label')}} <span class="de_col">*</span></label>
                    <div class="col-md-8">
                    <input type="text" class="col-md-8 ge_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value='{{ old("weight.$i")}}' tabindex="7">
                    <span class="invalid-feedback  col-md-8"  id="err_sku" ></span>
                    </div>
                  </div>
                  */?>
                  <div class="form-group producterrDiv" >
                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.price_label')); ?> <span class="de_col">*</span></label>
                    <div class="col-md-8">
                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.price_placeholder')); ?>" value='<?php echo e(old("price.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                    </div>
                  </div>
                  <div class="form-group producterrDiv" >
                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.qty_label')); ?> <span class="de_col">*</span></label>
                    <div class="col-md-8">
                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.qty_label')); ?>" value='<?php echo e(old("quantity.$i")); ?>' tabindex="7">
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                  </div>
                  </div>
                  <div class="form-group producterrDiv" >
                    <?php for($ii=0;$ii<2;$ii++){
                      if($ii==0){?>
                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.select_attribute_label')); ?> <span class="de_col"></span></label>
                  <?php }else{?>
                    <div class="col-md-3"></div>
                 <?php }?>
                    <div class="col-md-8">
                    <select style="width: 32%;" class="col-md-4 ge_input select_attribute" name="attribute[<?php echo $i;?>][]" variant_id="<?php echo $i;?>" >
                      <option value=""> <?php echo e(__('lang.attribute_label')); ?> (ex färg)</option>

                        <?php $__currentLoopData = $attributesToSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($attr->id); ?>"  ><?php echo e($attr->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    
                    <select style="margin-left: 10px;width: 34%;" selected_attribute_value="" 
                    class="col-md-4 ge_input select_attribute_value" name="attribute_value[<?php echo $i;?>][]" variant_id="<?php echo $i;?>">
                      <option value=""><?php echo e(__('lang.attribute_value_label')); ?> (ex röd)</option>

                    </select>
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                    <?php  if($ii!=0){?>
                    <p class="seller-logo-info col-md-8" style="font-size: 13px;">Ändra eller lägg till nya egenskaper till vänster under Attribut</p>
                  <?php } ?>
                  </div>
                <?php } ?>
<?php /*?>
                  <!-- new start -->
                   <!-- <div class="col-md-3"></div>
                   <div class="col-md-8">
                    <select style="width: 32%;" class="col-md-4 ge_input select_attribute variant_field" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                      <option value=""> {{ __('lang.attribute_label')}} (ex färg)</option>

                        @foreach ($attributesToSelect as $attr)
                          <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                        @endforeach
                    </select>
                    
                    <select style="margin-left: 10px;width: 34%;" selected_attribute_value="" 
                    class=" variant_field  col-md-4 ge_input select_attribute_value variant_field" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>">
                      <option value="">{{ __('lang.attribute_value_label')}} (ex röd)</option>

                    </select>
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                    <p class="seller-logo-info col-md-8" style="font-size: 13px;">Ändra eller lägg till nya egenskaper till vänster under Attribut</p>
                  </div>
 */?>
                  <!-- new end -->

                  </div>
				  <?php /*
				  <div class="form-group producterrDiv" >
					<label class="col-md-3 product_table_heading">{{ __('lang.select_attribute_label')}} </label>
					<div class="col-md-8" >
					@foreach ($attributesToSelect as $attr)
						<div class="row form-group producterrDiv" >
							<label class="col-md-4 product_table_heading">{{ $attr->name }}</label>
							<select attribute_id="{{ $attr->id }}" style="width: 34%;" selected_attribute_value="" 
							class="col-md-4 ge_input select_attribute_value" name="attribute_value[<?php echo $i;?>][{{ $attr->id }}]">
							</select>
						</div>	
					@endforeach
           <p class="seller-logo-info col-md-8" style="font-size: 12px;">{{ __('messages.add_attribute_info')}}</p>
					</div>
				  </div>
          */?>
                  
                  <div class="form-group producterrDiv var_img_div" >
                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.image_label')); ?> <span class="de_col">*</span></label>
                    <div class="col-md-8">
                      <div class="selected_images col-md-12"></div>
                      <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image variant_field" name="image[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.image_label')); ?>" value='<?php echo e(old("image.$i")); ?>' tabindex="7">
                      <span class="invalid-feedback col-md-8" id="err_variant_image" style="margin-left:-1px;"></span>  
                      <span class="invalid-feedback col-md-8" id="err_variant_hid_image"></span> 
                      <p class="seller-logo-info col-md-12" style="font-size: 12px;"><?php echo e(__('messages.product_img_upload_info')); ?></p>  
                    </div>
                    
                                  
                  </div>

                  <!-- <div class="col-md-3"></div> -->
                  <div class="remove_variant_div"></div>
                  <div class="loader"></div>
                </div>
              

               <div class="col-md-12 text-right add-varinat-btn" style="margin-bottom: 10px;">
                  <a title="<?php echo e(__('lang.add_variant_btn')); ?>" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" ><span><i class="fa fa-plus"></i><?php echo e(__('lang.add_variant_btn')); ?></span> </a>
               </div>
               
              <div class="all_saved_attributes" ></div>
              <div class="col-md-9">&nbsp;</div>
               <div class="info col-md-3 seller_product_varint_info"><?php echo e(__('messages.seller_product_varint_info')); ?></div>
               <span class="solid-horizontal-line"></span>
            </div>

            <h2 class="col-md-12 product_add_h2" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step3')); ?></h2>
            <div class="form-group col-md-12" id="shipping_method_ddl_div">
              <label class="col-md-3 product_table_heading"><?php echo e(__('users.shipping_method_label')); ?></label>
              <div class="col-md-8">
              <select class="col-md-8 ge_input" name="shipping_method_ddl" id="shipping_method_ddl" >
                <option value=""><?php echo e(__('users.select_shipping_method')); ?></option>
                <option value="Platta fraktkostnader" <?php if(@$users_details->shipping_method=="Platta fraktkostnader"): ?> selected="selected" <?php endif; ?>><?php echo e(__('users.flat_shipping_charges')); ?></option>
                <option value="Andel fraktkostnader"  <?php if(@$users_details->shipping_method=="Andel fraktkostnader"): ?> selected="selected" <?php endif; ?>><?php echo e(__('users.prcentage_shipping_charges')); ?></option>
              </select>
               <span class="invalid-feedback col-md-8"  id="err_shipping_method_ddl"> </span>
              </div>
            </div>

            <div class="form-group col-md-12" id="shipping_charges_div">
              <label class="col-md-3 product_table_heading"><?php echo e(__('users.shipping_charges_label')); ?></label>
              <div class="col-md-8">
              <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="<?php echo e(__('users.shipping_charges_label')); ?>" value="<?php if(@$users_details->shipping_charges!=''): ?><?php echo e($users_details->shipping_charges); ?><?php endif; ?>" >
              <span class="invalid-feedback col-md-8"  id="err_shipping_charges"></span>
            </div>
            </div>

            <div class="form-group col-md-12">
            <label class="col-md-3 product_table_heading">
             <?php echo e(__('users.free_shipping_label')); ?></label>
            <div class="col-md-8">  <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if(@$users_details->free_shipping=='free_shipping'): ?> checked="checked" <?php endif; ?>></div>
            
            </div>
            <div class="form-group col-md-12">
              <label  class="col-md-3 product_table_heading" style="margin-top: 15px;"> <?php echo e(__('users.pick_from_store')); ?> </label>
              <div class="col-md-8">
                <div class="row">
                <div class="col-md-1"  class="is_pick_from_store">
                 <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  style="margin-top: 15px;" <?php if(@$users_details->is_pick_from_store=='1'): ?> checked="checked" <?php endif; ?>>
               </div>
               <div class="col-md-8">
                  <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="<?php echo e(__('users.pick_up_address')); ?>" value="<?php if(@$users_details->store_pick_address!=''): ?><?php echo e(@$users_details->store_pick_address); ?><?php endif; ?>">
                  <span class="invalid-feedback col-md-8"  id="err_pick_up_address"> </span>
               </div>
                </div>
              </div>              
            </div>
        </div>
      </div>
      

  <div class="row  tijara-content">  
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12 text-center">
      <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9"><?php echo e(__('lang.save_btn')); ?></button>
      <a href="<?php echo e($module_url); ?>" class="btn btn-black gray_color login_btn" tabindex="10"> <?php echo e(__('lang.cancel_btn')); ?></a>
    </div>
  </div>

  </form>
            </div>
              </div>
</div>
</div> <!-- /container -->
</div>
</div>


<script>var siteUrl="<?php echo e(url('/')); ?>";</script>
<script type="text/javascript">
  $('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').parent("div").remove();
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});

$( document ).ready(function() {
  $('.select2-search__field').attr("placeholder", select_placeholder);

/*  var var_id = $(".variant_image").attr('variant_id');
  if(var_id > 0)
  { 
     $(this).parent().parent().parent();hide();
  }*/
  
  $('.select_attribute_value').each(function(){
	var attr_id = $(this).attr('attribute_id');
	get_attribute_values(attr_id, $(this));
  });
  
});

function get_attribute_values(select_attribute, element) {

	$.ajax({
		  url: siteUrl+'/product-attributes/getattributevaluebyattributeid',
		   data: {attribute_id: select_attribute},
		   type: 'get',
		   success: function(output) {
						//console.log(output);
						element.html(output);
						//elm.parent('div').find('.select_attribute_value').html(output);
					}
	});

}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/Products/seller-create.blade.php ENDPATH**/ ?>

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
    <div class="col-md-2 tijara-sidebar">

        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    
      <div class="col-md-10">
          <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="seller_info">
      <div class="seller_header">
                    <h2 class="seller_page_heading"><?php echo e(__('lang.product_form_label')); ?></h2>
                    <!-- <hr class="heading_line"/> -->
                </div>
         <!-- Example row of columns -->
          <?php if($subscribedError): ?>
              <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
              <?php endif; ?>
      
          
           
            <form id="product-form" class="tijara-form" action="<?php echo e(route('frontProductStore')); ?>" method="post" enctype="multipart/form-data">
           
                <?php echo csrf_field(); ?>
            
                  <div class="col-md-10">
              
                  </div>
                  <div class="col-md-2 text-right" style="margin-top:30px;">
                    <a href="<?php echo e(route('manageFrontProducts')); ?>" title="" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;<?php echo e(__('lang.back_to_list_label')); ?></span> </a>
                  </div>

                  
                  <div class="col-md-12" style="margin-left: -18px;">

                    <div class="login_box">

                        <h2 class="col-md-12 product_add_h2" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step1')); ?></h2>
                        <input type="hidden" id="product_id" name="product_id" value="<?php echo e($product_id); ?>">

                        <div class="form-group col-md-12">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_title_label')); ?> <span class="de_col">*</span></label>
                          <div class="col-md-8">
                          <input type="text" class="col-md-8 ge_input" name="title" id="title" placeholder="<?php echo e(__('lang.product_title_label')); ?> " value="<?php echo e((old('title')) ?  old('title') : $product->title); ?>" tabindex="1" onblur="convertToSlug(this)">
                          <span class="invalid-feedback col-md-12" id="err_title" ><?php if($errors->has('title')): ?> <?php echo e($errors->first('title')); ?><?php endif; ?> </span>
                        </div>
                        </div>

                        <div class="form-group  col-md-12">
                        <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_description_label')); ?>  <span class="de_col">*</span></label>
                          
                        <div class="col-md-8">
                          <textarea class="ge_input product_description col-md-8 " style="width: 67%; height: 175px;"
                           name="description" id="" placeholder="<?php echo e(__('lang.product_description_label')); ?>" value="" tabindex="2"><?php echo e((old('description')) ?  old('description') : $product->description); ?></textarea>
                          <span class="invalid-feedback col-md-8" id="err_description" ><?php if($errors->has('description')): ?> <?php echo e($errors->first('description')); ?><?php endif; ?> </span>
                        </div>
                      </div>
                        <div class="form-group  col-md-12">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.status_label')); ?> <span class="de_col">*</span></label>
                          <div class="col-md-8">
                          <select class="select2 col-md-8 ge_input" name="status" id="status"  placeholder="" tabindex="8" >
                            <option <?php if($product->status=='active'): ?> selected="selected" <?php endif; ?> value="active"><?php echo e(__('lang.active_label')); ?></option>
                            <option <?php if($product->status=='block'): ?> selected="selected" <?php endif; ?> value="block"><?php echo e(__('lang.block_label')); ?></option>
                          </select>
                          <span class="invalid-feedback col-md-12"  id="err_find_us" ><?php if($errors->has('status')): ?> <?php echo e($errors->first('status')); ?><?php endif; ?></span>
                        </div>
                        </div>

                        <div class="form-group col-md-12">
                          <label class="col-md-3 product_table_heading" ><?php echo e(__('lang.category_label')); ?><span class="de_col">*</span></label>
                          <div class="col-md-8">
                          <select class="select2 col-md-8 ge_input" name="categories[]" id="categories" multiple placeholder="<?php echo e(__('lang.category_label')); ?>" tabindex="3">
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
                          <span class="invalid-feedback col-md-8"  id="err_category" ><?php if($errors->has('categories')): ?> <?php echo e($errors->first('categories')); ?><?php endif; ?></span>
                        </div>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-6 product_table_heading"><?php echo e(__('lang.product_slug_label')); ?> <span class="de_col">*</span></label>
                          <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
                          <input type="text" class="col-md-6 form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="<?php echo e(__('lang.product_slug_label')); ?> " value="<?php echo e((old('product_slug')) ?  old('product_slug') : $product->product_slug); ?>" tabindex="1" readonly="readonly">
                          <span class="invalid-feedback slug-name-err" id="err_title" ><?php if($errors->has('product_slug')): ?> <?php echo e($errors->first('product_slug')); ?><?php endif; ?> </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_title_label')); ?> <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( <?php echo e(__('users.meta_title_info')); ?> )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_title" id="meta_title" placeholder="<?php echo e(__('lang.meta_title_label')); ?>" value="<?php echo e((old('meta_title')) ?  old('meta_title') : $product->meta_title); ?>" tabindex="4">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_title" ><?php if($errors->has('meta_title')): ?> <?php echo e($errors->first('meta_title')); ?><?php endif; ?> </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_desc_label')); ?> <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( <?php echo e(__('users.meta_desciption_info')); ?> )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_description" id="meta_description" placeholder="<?php echo e(__('lang.meta_desc_label')); ?>" value="<?php echo e((old('meta_description')) ?  old('meta_description') : $product->meta_description); ?>" tabindex="5">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_description" ><?php if($errors->has('meta_description')): ?> <?php echo e($errors->first('meta_description')); ?><?php endif; ?> </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.meta_keyword_label')); ?>  <span class="de_col"></span></label>
                          <p class="meta-data">( <?php echo e(__('users.meta_keyword_info')); ?> )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_keyword" id="meta_keyword" placeholder="<?php echo e(__('lang.meta_keyword_label')); ?>" value="<?php echo e((old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword); ?>" tabindex="6">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" ><?php if($errors->has('meta_keyword')): ?> <?php echo e($errors->first('meta_keyword')); ?><?php endif; ?> </span>
                        </div>
                        
                        <div class="form-group col-md-12">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.product_discount_label')); ?></label>
                          <div class="col-md-8">
                          <input type="text" class="col-md-8 ge_input number" name="discount" id="discount" placeholder="<?php echo e(__('lang.product_discount_label')); ?> " value="<?php echo e((old('discount')) ?  old('discount') : $product->discount); ?>" tabindex="1">
                          <span class="invalid-feedback col-md-12"  id="err_discount" ><?php if($errors->has('discount')): ?> <?php echo e($errors->first('discount')); ?><?php endif; ?> </span>
                         </div>
                        </div>

                        <div class="form-group  col-md-12"  style="display:none;">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('lang.sort_order_label')); ?> <span class="de_col"></span></label>
                          <div class="col-md-8">
                          <input type="tel" class="col-md-8 ge_input" name="sort_order" id="sort_order" placeholder="<?php echo e(__('lang.sort_order_label')); ?>" value="<?php echo e((old('sort_order')) ?  old('sort_order') : $product->sort_order); ?>" tabindex="7">
                          <span class="invalid-feedback col-md-12"  id="err_meta_keyword" ><?php if($errors->has('sort_order')): ?> <?php echo e($errors->first('sort_order')); ?><?php endif; ?> </span>
                         </div>
                        </div>
        

                        <h2 class="col-md-12 product_add_h2" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step2')); ?></h2>
                        <div  class="col-md-12" id="variant_table">
                          <?php $v=0; $i=0; ?>
                          <?php if(count($VariantProductAttributeArr)>0): ?>
                            
                            <?php $__currentLoopData = $VariantProductAttributeArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant_key1=>$variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php $v++; ?>

                                <?php $attribute  = $variant['attributes'][0]; ?>
                                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                                
                                  <input type="hidden" class="variant_id form-control ge_input" value="<?php echo e($variant_key1); ?>" name="variant_id[<?php echo e($i); ?>]" >
                                  <div class="form-group">
                                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.sku_label')); ?> <span class="de_col"></span></label>
                                    <div class="col-md-8">
                                    <input type="text" class="col-md-8 ge_input sku" name="sku[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.sku_placeholder')); ?>" value="<?php echo e($variant['sku']); ?>" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                   </div>
                                  </div>
                                  <?php /*
                                  <div class="form-group">
                                    <label class="col-md-3 product_table_heading">{{ __('lang.weight_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="text" class="col-md-8 ge_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value="{{$variant['weight']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8"  id="err_sku" ></span>
                                  </div>
                                  </div>
                                  */?>
                                  <div class="form-group">
                                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.price_label')); ?> <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.price_placeholder')); ?>" value="<?php echo e($variant['price']); ?>" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.qty_label')); ?> <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.qty_label')); ?>" value="<?php echo e($variant['quantity']); ?>" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                  </div>
                                  </div> 
                                  <div class="form-group" >
                              <?php //echo "<pre>--";print_r($variant['attributes']);exit;
                                  if(count($variant['attributes'])==1){
                                     $variant['attributes'][1]=$variant['attributes'][0];
                                  }
                                   
                              ?>
                                       <?php $__currentLoopData = $variant['attributes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <input type="hidden" name="variant_attribute_id[<?php echo $i;?>][]" value="<?php echo e($value['id']); ?>" class="variant_attribute_id">
                                    <?php

                                       if($key==0){ ?>

                                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.select_attribute_label')); ?> <span class="de_col"></span></label>
                                  <?php }else{?>
                                    <div class="col-md-3"></div>
                                 <?php }?>
                                
                                    <div class="col-md-8">
                             
                                    <select id="<?php echo e($attribute['id']); ?>" style="  width: 34%;"  class="col-md-4 ge_input select_attribute preselected_attribute" name="attribute[<?php echo $i;?>][]" variant_id="<?php echo $i;?>" >
                                      <option value=""><?php echo e(__('lang.select_label')); ?> <?php echo e(__('lang.attribute_label')); ?></option>

                                        <?php $__currentLoopData = $attributesToSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <?php if($value['attribute_id']==$attr->id): ?>
                                          <?php $disabled_attr[] = $attr->id;?>
                                            <option selected="selected" value="<?php echo e($attr->id); ?>"><?php echo e($attr->name); ?></option>
                                          <?php else: ?>
                                          
                                          <option value="<?php echo e($attr->id); ?>"  ><?php echo e($attr->name); ?></option>
                                          <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>


                                    <select style="margin-left: 10px; width: 32%;" attribute_id="<?php echo e($value['attribute_id']); ?>" selected_attribute_value="<?php echo e($value['attribute_value_id']); ?>" class="<?php echo e($value['id']); ?> col-md-4 ge_input select_attribute_value" name="attribute_value[<?php echo $i;?>][]">
                                      <option value=""><?php echo e(__('lang.select_label')); ?> <?php echo e(__('lang.attribute_value_label')); ?></option>

                                    </select>
                                    <span class="invalid-feedback col-md-8"  id="err_sku" ></span>
                                    <?php  if($key!=0){?>
                                      <p class="seller-logo-info col-md-8" style="font-size: 12px;"><?php echo e(__('messages.add_attribute_info')); ?>t</p>
                                    <?php } ?>
                                      </div>
                                     
                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </div>
								  <?php /*?>
				 			  <div class="form-group producterrDiv" >
									<label class="col-md-3 product_table_heading">{{ __('lang.select_attribute_label')}} </label>
									<div class="col-md-8" >
									@foreach ($attributesToSelect as $attr)
										<div class="row form-group producterrDiv" >
											<label class="col-md-4 product_table_heading">{{ $attr->name }}</label>
											<?php
											$selected_attr_value ="";
											foreach ($variant['attributes'] as $attr1){	
												if($attr1['attribute_id']==$attr->id)
													$selected_attr_value =  $attr1['attribute_value_id'];
											}
											?>
											<select attribute_id="{{ $attr->id }}" style="width: 34%;" selected_attribute_value="{{ $selected_attr_value }}" 
											class="col-md-4 ge_input select_attribute_value " name="attribute_value[<?php echo $i;?>][{{ $attr->id }}]">
											</select>
										</div>	
									@endforeach
                  <p class="seller-logo-info col-md-8" style="font-size: 12px;">{{ __('messages.add_attribute_info')}}</p>
									</div>
								  </div> 
                 */?>      
                                  <div class="form-group">
                                    <label class="col-md-3 product_table_heading"><?php echo e(__('lang.image_label')); ?> <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <div class="selected_images col-md-12">
                                    <?php if($variant['image']!=''): ?>
                                      <?php $images  = explode(',',$variant['image']);
                                      ?>
                                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <div>
                                            <input type="hidden" class="form-control ge_input hidden_images" value="<?php echo e($image); ?>"  name="hidden_images[<?php echo e($i); ?>][]" placeholder="<?php echo e(__('lang.image_label')); ?>">
                                            <img src="<?php echo e(url('/')); ?>/uploads/ProductImages/resized/<?php echo e($image); ?>" width="78" height="80">
                                            <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                                          </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                  </div>
                                    <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image" name="image[<?php echo $i;?>]"  placeholder="<?php echo e(__('lang.image_label')); ?>" value='<?php echo e(old("image.$i")); ?>' tabindex="7">
                                    
                                    <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="float: right;"></span>  
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="float: right;"></span>
                                     <p class="seller-logo-info col-md-12" style="font-size: 12px;margin-top:20px;"><?php echo e(__('messages.product_img_upload_info')); ?></p>  

                                     </div>  
                                  </div>
                              <!--  <div class=" col-md-12"><a href="javascript:void(0);" variant_id="" class="btn btn-danger btn-xs remove_variant_btn" title="Remove Variant"><i class="fas fa-trash"></i></a></div>
                               -->
                                  <!-- <div class="selected_images col-md-12"></div> -->
                                  <?php $css="";
                                  if($i == 0) { 
                                    $css="display:none";
                                  }?>
                                  <div class="remove_variant_div col-md-12" style="<?php echo e($css); ?>"><a href='javascript:void(0);' variant_id='<?php echo $i; ?>' class='btn btn-danger btn-xs remove_variant_btn' remove_variant_id="<?php echo e($variant_key1); ?>" title='Remove Variant'><i class='fas fa-trash'></i></a></div>
                            
                                  <div class="loader"></div>
                                 
                                </div>
                              
                              <?php $i++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                          <?php endif; ?>
  
                          <div class="col-md-12 text-right add-varinat-btn" style="margin-bottom: 10px;">
                              <a title="<?php echo e(__('lang.add_variant_btn')); ?>" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn"><span><i class="fa fa-plus"></i><?php echo e(__('lang.add_variant_btn')); ?></span> </a>
                          </div>
                          <div class="all_saved_attributes" ></div>
                          <div class="col-md-9">&nbsp;</div>
                            <div class="info col-md-3 seller_product_varint_info"><?php echo e(__('messages.seller_product_varint_info')); ?>

                            </div>
                    
                        </div>
                        <h2 class="col-md-12 product_add_h2" style="margin-left: -12px;"><?php echo e(__('lang.product_form_step3')); ?></h2>
                 
                        <div class="form-group col-md-12" id="shipping_method_ddl_div">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('users.shipping_method_label')); ?></label>
                          <div class="col-md-8">
                          <select class="col-md-8 ge_input" name="shipping_method_ddl" id="shipping_method_ddl">
                          <option value=""><?php echo e(__('users.select_shipping_method')); ?></option>
                          <option  value="Platta fraktkostnader" <?php if($product->shipping_method ==  "Platta fraktkostnader"){ echo "selected"; } ?>><?php echo e(__('users.flat_shipping_charges')); ?></option>
                          <option value="Andel fraktkostnader" <?php if($product->shipping_method ==  "Andel fraktkostnader"){ echo "selected"; } ?>><?php echo e(__('users.prcentage_shipping_charges')); ?></option>
                          </select>
                           <span class="invalid-feedback col-md-8"  id="err_shipping_method_ddl"> </span>
                        </div>
                        </div>

                        <div class="form-group col-md-12" id="shipping_charges_div">
                          <label class="col-md-3 product_table_heading"><?php echo e(__('users.shipping_charges_label')); ?></label>
                          <div class="col-md-8">
                          <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="<?php echo e(__('users.shipping_charges_label')); ?>" value="<?php echo e((old('shipping_charges')) ? old('shipping_charges') : $product->shipping_charges); ?>">
                          <span class="invalid-feedback col-md-8"  id="err_shipping_charges"> </span>
                        </div>
                        </div>
                        <div class="col-md-12">
                         <label class="col-md-3 product_table_heading"> <?php echo e(__('users.free_shipping_label')); ?></label>
                          <div class="col-md-8">
                            <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($product->free_shipping == "free_shipping"){ echo "checked"; } ?>>
                          </div>
                          </div>


                      <div class="form-group col-md-12">
                      <label  class="col-md-3 product_table_heading" style="margin-top: 15px;"> <?php echo e(__('users.pick_from_store')); ?> </label>
                      <div class="col-md-8">
                        <div class="row">
                        <div class="col-md-1"  class="is_pick_from_store">
                           <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  style="margin-top: 15px;" <?php if($product->is_pick_from_store ==  "1"){ echo "checked"; } ?>>
                       </div>
                       <div class="col-md-8">
                         <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="<?php echo e(__('users.pick_up_address')); ?>" value="<?php echo e((old('store_pick_address')) ? old('store_pick_address') : $product->store_pick_address); ?>">
                          <span class="invalid-feedback col-md-8"  id="err_pick_up_address"> </span>
                       </div>
                        </div>
                      </div>              
                    </div>
                    </div>
                  </div>
                  <div class="row tijara-content">

                  
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12 text-center" style="margin-bottom : 60px;">
                          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9"><?php echo e(__('lang.save_btn')); ?></button>

                          <a href="<?php echo e($module_url); ?>" class="btn btn-black gray_color login_btn" tabindex="10"> <?php echo e(__('lang.cancel_btn')); ?></a>
                        </div>


                  </div>
             
            </form>
          
      </div> <!-- /col-10 -->

</div>
</div>
</div>
 </div>
</div> <!-- /container -->
<script>var siteUrl="<?php echo e(url('/')); ?>";</script>
<script type="text/javascript">
  if($('#free_shipping_chk').is(":checked"))  {
  if($("#free_shipping_chk").val()=="free_shipping"){
    $("#shipping_method_ddl_div").hide();
    $("#shipping_charges_div").hide();
    $("#shipping_method_ddl").val('');
    $("#shipping_charges").val('');
  }
}

function hideShippingMethod(){
  if($('#free_shipping_chk').is(":checked"))  {
    $("#shipping_method_ddl_div").hide();
    $("#shipping_charges_div").hide();
    $("#shipping_method_ddl").val('');
    $("#shipping_charges").val('');
  } 
  else{
    $("#shipping_method_ddl_div").show();
    $("#shipping_charges_div").show();
  }
}

  $("input:checkbox#free_shipping_chk").click(function() {
        if(!$(this).is(":checked")){
           $("#shipping_method_ddl_div").show();
         $("#shipping_charges_div").show();
        }
        
    }); 
 /* $(" input:checkbox").change(function() {
    alert("jhhghg");
    var ischecked= $(this).is(':checked');
    if(!ischecked)
    alert('uncheckd ' + $(this).val());
}); */

   $('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').parent("div").remove();
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});

$( document ).ready(function() {
  $('.select2-search__field').attr("placeholder", select_placeholder);
  
  $('.select_attribute_value').each(function(){
	var attr_id = $(this).attr('attribute_id');
	var selected_attr_id = $(this).attr('selected_attribute_value');
	get_attribute_values(attr_id, $(this), selected_attr_id);	
  });

    /*code to select maximum two attributes*/
    // $('.select_attribute').each(function(){
    //   var attrName = $(this).val();
    //   if(attrName!=''){
    //    $(".select_attribute option").attr('disabled', true);
    //   }   
    // });

    // var data = "<?php echo json_encode(@$disabled_attr);?>";
    // //console.log(data);
    // if( data !== 'null'){ 
    //   var attr_array = JSON.parse(data);

    //   attr_array.forEach(entry => {
    //    $(".select_attribute option[value='"+ entry + "']").attr('disabled', false);
    //   });
    // }
      
      var firstAttribute = $("#variant_table").find('.variant_tr:first').find('.select_attribute:eq(0)').val();
      var secondAttribute = $("#variant_table").find('.variant_tr:first').find('.select_attribute:eq(1)').val();

     $("#variant_table").find('.variant_tr').not(':first').each(function(){
        $(this).find('.select_attribute:eq(0)').find('option').each(function(){
          if($(this).val() != firstAttribute)
          {
             $(this).attr('disabled','disabled');
          }
          else
          {
            $(this).removeAttr('disabled','disabled'); 
          }
        });

        $(this).find('.select_attribute:eq(1)').find('option').each(function(){
          if($(this).val() != secondAttribute)
          {
             $(this).attr('disabled','disabled');
          }
          else
          {
            $(this).removeAttr('disabled','disabled'); 
          }
        });
    });
  
});

function get_attribute_values(select_attribute, element, selected_attr_id) {

	$.ajax({
		  url: siteUrl+'/product-attributes/getattributevaluebyattributeid',
		   data: {attribute_id: select_attribute},
		   type: 'get',
		   success: function(output) {
						console.log(selected_attr_id);
            element.html(output);
            if(selected_attr_id !=0){ 

						  element.val(selected_attr_id).change();
            }else{
              element.prop("selectedIndex", 0).val();
            }
						//elm.parent('div').find('.select_attribute_value').html(output);
					}
	});

}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/Products/seller-edit.blade.php ENDPATH**/ ?>
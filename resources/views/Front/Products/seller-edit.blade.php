@extends('Front.layout.template')
@section('middlecontent')
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
.tj-selectbox-cotnainer.full .select2-container{
    width:67% !important;
}
@media(max-width:767px){
  .mbnml0{
    margin-left:0 !important;
  }
  .seller_info h2:not(.pl-0) {
    padding-left: 0px !important;
  }
  .login_box {
    margin-left: -15px;
    margin-right: -15px;
    width: auto !important;
  }
  #product-form {
    margin-left: -15px;
    margin-right: -15px;
  }
  .seller_info .login_box h2:not(.pl-0) {
    padding: 10px 15px !important;
    margin-left: 0 !important;
  }
  .mbnml0 .login_box > .form-group, .mbnml0 .login_box > .col-md-12 {
      padding: 0;
  }
  .tijara-content .btn{
    display: inline-block;
  }
  .row.tijara-content{
    margin:0;
  }
  .product_description{
    width: 100% !important;
  }
  .store_pick_address{
    width: 100% !important;
  }
  .tj-selectbox-cotnainer.full .select2-container{
    width:100% !important;
  }
}
</style>

<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1 tjd-sellcontainer">
      <div class="row">
    <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>

        @include ('Front.layout.sidebar_menu')
    </div>
    
      <div class="col-md-10">
          @include ('Front.alert_messages')
      <div class="seller_info">
      <div class="seller_header">
                    <h2 class="seller_page_heading"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.product_form_label')}}</h2>
                    <!-- <hr class="heading_line"/> -->
                </div>
         <!-- Example row of columns -->
          @if($subscribedError)
              <div class="alert alert-danger">{{$subscribedError}}</div>
              @endif
      
          
           
            <form id="product-form" class="tijara-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
           
                @csrf
                  <div class="col-md-12 text-right" style="margin-top:30px;">
                    <a href="{{route('manageFrontProducts')}}" title="" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
                  </div>

                  
                  <div class="row tj-nodpad tjd-pad6">
					<div class="col-md-12">

                    <div class="login_box">

                        <h2 class="col-md-12 product_add_h2 steps_no_css tj-savepr-head">{{ __('lang.product_form_step1')}}</h2>
                        <input type="hidden" id="product_id" name="product_id" value="{{$product_id}}">

                        <div class="form-group ">
                          <label class="col-md-3 product_table_heading">{{ __('lang.product_title_label')}} <span class="de_col">*</span></label>
                          <div class="col-md-8">
							  <input type="text" class="col-md-8 ge_input" name="title" id="title" placeholder="{{ __('lang.product_title_label')}} " value="{{ (old('title')) ?  old('title') : $product->title}}" tabindex="1" onblur="convertToSlug(this)">
							  
							  <span class="invalid-feedback col-md-8" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
							</div>
                        </div>

                        <div class="form-group">
								<label class="col-md-3 product_table_heading">{{ __('lang.product_description_label')}}  <span class="de_col">*</span></label>
							  
								<div class="col-md-8">
								  <textarea class="ge_input product_description col-md-8 " style="width: 67%; height: 175px;"
								   name="description" id="" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
								  <span class="invalid-feedback col-md-8" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
								</div>
						  </div>
						  
                        <div class="form-group">
                          <label class="col-md-3 product_table_heading">{{ __('lang.status_label')}} <span class="de_col">*</span></label>
                          <div class="col-md-8 tj-selectbox-cotnainer full">
							  <select class="select2 col-md-8 ge_input tjselect" name="status" id="status"  placeholder="" tabindex="8" >
								<option @if($product->status=='active') selected="selected" @endif value="active">{{ __('lang.active_label')}}</option>
								<option @if($product->status=='block') selected="selected" @endif value="block">{{ __('lang.block_label')}}</option>
							  </select>
							  <span class="invalid-feedback col-md-8"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
							</div>
                        </div>

                        <div class="form-group">
							  <label class="col-md-3 product_table_heading" >{{ __('lang.category_label')}}<span class="de_col">*</span></label>
							  <div class="col-md-8 tj-selectbox-cotnainer full">
							  <select class="select2 col-md-8 ge_input tjselect" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
							  <option></option>
								@foreach($categories as $cat_id=>$category)
								  <optgroup label="{{$category['maincategory']}}">
								  <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
								  @foreach($category['subcategories'] as $subcat_id=>$subcategory)
								  @if(in_array($subcat_id,$selectedCategories))
								  <option selected="selected" value="{{$subcat_id}}">{{$subcategory}}</option>
								  @else
								  <option value="{{$subcat_id}}">{{$subcategory}}</option>
								  @endif
								  @endforeach
								  </optgroup>
								@endforeach
							  </select>
							  <span class="invalid-feedback col-md-8"  id="err_category" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
							</div>
                        </div>

                        <div class="form-group" style="display:none;">
                          <label class="col-md-6 product_table_heading">{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
                          <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
                          <input type="text" class="col-md-6 form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{ (old('product_slug')) ?  old('product_slug') : $product->product_slug}}" tabindex="1" readonly="readonly">
                          <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
                        </div>

                        <div class="form-group " style="display:none;">
                          <label class="col-md-3 product_table_heading">{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( {{ __('users.meta_title_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{(old('meta_title')) ?  old('meta_title') : $product->meta_title}}" tabindex="4">
                          <span class="invalid-feedback col-md-8" style="text-align: center;"  id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
                        </div>

                        <div class="form-group" style="display:none;">
                          <label class="col-md-3 product_table_heading">{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( {{ __('users.meta_desciption_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{(old('meta_description')) ?  old('meta_description') : $product->meta_description}}" tabindex="5">
                          <span class="invalid-feedback col-md-8" style="text-align: center;"  id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
                        </div>

                        <div class="form-group " style="display:none;">
                          <label class="col-md-3 product_table_heading">{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
                          <p class="meta-data">( {{ __('users.meta_keyword_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{(old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword}}" tabindex="6">
                          <span class="invalid-feedback col-md-8" style="text-align: center;"  id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
                        </div>
                        
                        <div class="form-group">
                          <label class="col-md-3 product_table_heading">{{ __('lang.product_discount_label')}}</label>
                          <div class="col-md-8">
                          <input type="text" class="col-md-8 ge_input number" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{ (old('discount')) ?  old('discount') : $product->discount}}" tabindex="1">
                          <span class="invalid-feedback col-md-8"  id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </span>
                         </div>
                        </div>

                        <div class="form-group"  style="display:none;">
                          <label class="col-md-3 product_table_heading">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
                          <div class="col-md-8">
                          <input type="tel" class="col-md-8 ge_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}" tabindex="7">
                          <span class="invalid-feedback col-md-8"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
                         </div>
                        </div>
        

                        <h2 class="col-md-12 product_add_h2 steps_no_css tj-savepr-head">{{ __('lang.product_form_step2')}}</h2>
                        <div  class="col-md-12" id="variant_table">
                          @php $v=0; $i=0; @endphp
                          @if(count($VariantProductAttributeArr)>0)
                            
                            @foreach($VariantProductAttributeArr as $variant_key1=>$variant)
                              @php $v++; @endphp

                                <?php $attribute  = $variant['attributes'][0]; ?>
                                <div class="variant_tr tjsellercol2" id="variant_tr" variant_id="<?php echo $i;?>">
                                
                                  <input type="hidden" class="variant_id form-control ge_input" value="{{$variant_key1}}" name="variant_id[{{$i}}]" >
                                  <div class="form-group row">
                                    <label class="col-md-3 product_table_heading">{{ __('lang.sku_label')}} <span class="de_col"></span></label>
                                    <div class="col-md-8">
                                    <input type="text" class="col-md-8 ge_input sku" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value="{{$variant['sku']}}" tabindex="7">
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
                                  <div class="form-group producterrDiv row">
                                    <label class="col-md-3 product_table_heading">{{ __('lang.price_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value="{{$variant['price']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                    </div>
                                  </div>
                                  <div class="form-group producterrDiv row">
                                    <label class="col-md-3 product_table_heading">{{ __('lang.qty_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value="{{$variant['quantity']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                  </div>
                                  </div> 
                                  <div class="form-group producterrDiv row" >
                              <?php //echo "<pre>--";print_r($variant['attributes']);exit;
                                  if(count($variant['attributes'])==1){
                                     $variant['attributes'][1]=$variant['attributes'][0];
                                  }
                                   
                              ?>
                                       @foreach($variant['attributes'] as $key=>$value)

                                        <input type="hidden" name="variant_attribute_id[<?php echo $i;?>][]" value="{{$value['id']}}" class="variant_attribute_id">
                                    <?php

                                       if($key==0){ ?>

                                    <label class="col-md-3 product_table_heading">{{ __('lang.select_attribute_label')}} <span class="de_col"></span></label>
                                  <?php }else{?>
                                    <div class="col-md-3"></div>
                                 <?php }?>
                                
                                    <div class="col-md-8 tj-selectbox-cotnainer">
                             
                                    <select id="{{$attribute['id']}}" style="  width: 34%;"  class="col-md-4 ge_input select_attribute preselected_attribute tjselect" name="attribute[<?php echo $i;?>][]" variant_id="<?php echo $i;?>" >
                                      <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>

                                        @foreach ($attributesToSelect as $attr)
                                          @if($value['attribute_id']==$attr->id)
                                          <?php $disabled_attr[] = $attr->id;?>
                                            <option selected="selected" value="{{ $attr->id }}">{{ $attr->name }}</option>
                                          @else
                                          
                                          <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                          @endif
                                        @endforeach
                                    </select>

                                    <select style="margin-left: 10px; width: 32%;" attribute_id="{{ $value['attribute_id'] }}" selected_attribute_value="{{$value['attribute_value_id']}}" class="{{$value['id']}} col-md-4 ge_input select_attribute_value tjselect" name="attribute_value[<?php echo $i;?>][]">
                                      <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}  (ex röd)</option>

                                    </select>
                                    <span class="invalid-feedback col-md-8"  id="err_sku" ></span>
                                    <?php  if($key!=0){?>
                                      <p class="seller-logo-info col-md-8" style="font-size: 12px;">{{ __('messages.add_attribute_info')}}</p>
                                    <?php } ?>
                                      </div>
                                     
                                         @endforeach
                                  </div>
								    
                                  <div class="form-group producterrDiv row">
                                    <label class="col-md-3 product_table_heading">{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <div class="selected_images col-md-12">
                                    @if($variant['image']!='')
                                      @php $images  = explode(',',$variant['image']);
                                      @endphp
                                        @foreach($images as $image)
                                          <div>
                                            <input type="hidden" class="form-control ge_input hidden_images" value="{{$image}}"  name="hidden_images[{{$i}}][]" placeholder="{{ __('lang.image_label')}}">
                                            <img src="{{url('/')}}/uploads/ProductImages/resized/{{$image}}" width="78" height="80">
                                            <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                                          </div>
                                        @endforeach
                                    @endif
                                  </div>
                                    <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image" name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">
                                    
                                    <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="float: right;"></span>  
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="float: right;"></span>
                                     <p class="seller-logo-info col-md-12" style="font-size: 12px;margin-top:20px;">{{ __('messages.product_img_upload_info')}}</p>  

                                     </div>  
                                  </div>
                              <!--  <div class=" col-md-12"><a href="javascript:void(0);" variant_id="" class="btn btn-danger btn-xs remove_variant_btn" title="Remove Variant"><i class="fas fa-trash"></i></a></div>
                               -->
                                  <!-- <div class="selected_images col-md-12"></div> -->
                                  <?php $css="";
                                  if($i == 0) { 
                                    $css="display:none";
                                  }?>
                                  <div class="remove_variant_div col-md-12" style="{{$css}}"><a href='javascript:void(0);' variant_id='<?php echo $i; ?>' class='btn btn-danger btn-xs remove_variant_btn' remove_variant_id="{{$variant_key1}}" title='Remove Variant'><i class='fas fa-trash'></i></a></div>
                            
                                  <div class="loader"></div>
                                 
                                </div>
                              
                              @php $i++; @endphp
                            @endforeach  
                          @endif
  
                          <div class="col-md-12 text-right add-varinat-btn" style="margin-bottom: 10px;">
                              <a title="{{ __('lang.add_variant_btn')}}" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn"><span><i class="fa fa-plus"></i>{{ __('lang.add_variant_btn')}}</span> </a>
                          </div>
                          <div class="all_saved_attributes" ></div>
                          <div class="col-md-9">&nbsp;</div>
                            <div class="info col-md-3 seller_product_varint_info">{{ __('messages.seller_product_varint_info')}}
                            </div>
                    
                        </div>
                        <h2 class="col-md-12 product_add_h2 tj-savepr-head" >{{ __('lang.product_form_step3')}}</h2>
                 
                        <div class="form-group producterrDiv" id="shipping_method_ddl_div">
                          <label class="col-md-3 product_table_heading">{{ __('users.shipping_method_label')}}</label>
                          <div class="col-md-8">
                          <select class="col-md-8 ge_input" name="shipping_method_ddl" id="shipping_method_ddl">
                          <option value="">{{ __('users.select_shipping_method')}}</option>
                          <option  value="Platta fraktkostnader" <?php if($product->shipping_method ==  "Platta fraktkostnader"){ echo "selected"; } ?>>{{ __('users.flat_shipping_charges')}}</option>
                          <option value="Andel fraktkostnader" <?php if($product->shipping_method ==  "Andel fraktkostnader"){ echo "selected"; } ?>>{{ __('users.prcentage_shipping_charges')}}</option>
                          </select>
                           <span class="invalid-feedback col-md-8"  id="err_shipping_method_ddl"> </span>
                        </div>
                        </div>

                        <div class="form-group " id="shipping_charges_div">
                          <label class="col-md-3 product_table_heading">{{ __('users.shipping_charges_label')}}</label>
                          <div class="col-md-8">
                          <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="{{ (old('shipping_charges')) ? old('shipping_charges') : $product->shipping_charges}}">
                          <span class="invalid-feedback col-md-8"  id="err_shipping_charges"> </span>
                        </div>
                        </div>
                        <div class="form-group tj-svcheck">
							 <label class="col-md-3 product_table_heading"> {{ __('users.free_shipping_label')}}</label>
							  <div class="col-md-8">
								<input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($product->free_shipping == "free_shipping"){ echo "checked"; } ?>>
							  </div>
                         </div>


                      <div class="form-group tj-svcheck">
                      <label  class="col-md-3 product_table_heading" style="margin-top: 15px;"> {{ __('users.pick_from_store')}} </label>
                      <div class="col-md-8">
                        <div class="row">
                        <div class="col-md-1"  class="is_pick_from_store">
                           <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  style="margin-top: 15px;" <?php if($product->is_pick_from_store ==  "1"){ echo "checked"; } ?>>
                       </div>
                       <div class="col-md-8">
                         <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="{{ __('users.pick_up_address')}}" value="{{ (old('store_pick_address')) ? old('store_pick_address') : $product->store_pick_address}}">
                          <span class="invalid-feedback col-md-8"  id="err_pick_up_address"> </span>
                       </div>
                        </div>
                      </div>              
                    </div>
                    </div>
                  </div>
				  </div>
                  <div class="row tijara-content">

                  
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12 text-center" style="margin-bottom : 60px;">
                          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>

                          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
                        </div>


                  </div>
             
            </form>
          
      </div> <!-- /col-10 -->

</div>
</div>
</div>
 </div>
</div> <!-- /container -->
<script>var siteUrl="{{url('/')}}";</script>
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

@endsection

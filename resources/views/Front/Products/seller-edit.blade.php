@extends('Front.layout.template')
@section('middlecontent')
<style>
  .login_box
  {
    width:100% !important;
  }
</style>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
    
    <div class="col-md-2 tijara-sidebar">

        @include ('Front.layout.sidebar_menu')
    </div>
    
      <div class="col-md-10">
      <div class="seller_info">
      <div class="seller_header">
                    <h2>{{ __('lang.product_form_label')}}</h2>
                    <!-- <hr class="heading_line"/> -->
                </div>
         <!-- Example row of columns -->
          @if($subscribedError)
              <div class="alert alert-danger">{{$subscribedError}}</div>
              @endif
      
          
           
            <form id="product-form" class="tijara-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
           
                @csrf
              <div class="row">
                  <div class="col-md-10">
                
                  </div>
                  <div class="col-md-2 text-right" style="margin-top:30px;">
                    <a href="{{route('manageFrontProducts')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
                  </div>

                    @include ('Front.alert_messages')
                  <div class="col-md-12">

                    <div class="login_box">

                        <h2 class="col-md-12">{{ __('lang.product_form_step1')}}</h2>
                        <input type="hidden" name="product_id" value="{{$product_id}}">

                        <div class="form-group col-md-12">
                          <label class="col-md-3">{{ __('lang.product_title_label')}} <span class="de_col">*</span></label>
                          <input type="text" class="col-md-8 ge_input" name="title" id="title" placeholder="{{ __('lang.product_title_label')}} " value="{{ (old('title')) ?  old('title') : $product->title}}" tabindex="1" onblur="convertToSlug(this)">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
                        </div>

                        <label class="col-md-3">{{ __('lang.product_description_label')}}  <span class="de_col"></span></label>
                          
                        <div class="form-group col-md-8">
                          <textarea class="ge_input ge_input" style="width: 100%; height: 175px;"
                           name="description" id="" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
                        </div>
                        <div class="form-group  col-md-12">
                          <label class="col-md-3">{{ __('lang.status_label')}} </label>
                          <select class="select2 col-md-8 ge_input" name="status" id="status"  placeholder="" tabindex="8" >
                            <option @if($product->status=='active') selected="selected" @endif value="active">{{ __('lang.active_label')}}</option>
                            <option @if($product->status=='block') selected="selected" @endif value="block">{{ __('lang.block_label')}}</option>
                          </select>
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
                        </div>

                        <div class="form-group col-md-12">
                          <label class="col-md-3" >{{ __('lang.category_label')}}</label>
                          <select class="select2 col-md-8 ge_input" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
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
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-6">{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
                          <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
                          <input type="text" class="col-md-6 form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{ (old('product_slug')) ?  old('product_slug') : $product->product_slug}}" tabindex="1" readonly="readonly">
                          <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3">{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( {{ __('users.meta_title_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{(old('meta_title')) ?  old('meta_title') : $product->meta_title}}" tabindex="4">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3">{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
                          <p class="meta-data col-md-8">( {{ __('users.meta_desciption_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{(old('meta_description')) ?  old('meta_description') : $product->meta_description}}" tabindex="5">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
                        </div>

                        <div class="form-group col-md-12" style="display:none;">
                          <label class="col-md-3">{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
                          <p class="meta-data">( {{ __('users.meta_keyword_info')}} )</p>
                          <div class="col-md-3"></div>
                          <input type="text" class="col-md-8 ge_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{(old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword}}" tabindex="6">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
                        </div>
                        
                        <div class="form-group col-md-12">
                          <label class="col-md-3">{{ __('lang.product_discount_label')}}</label>
                          <input type="text" class="col-md-8 ge_input number" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{ (old('discount')) ?  old('discount') : $product->discount}}" tabindex="1">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </span>
                        </div>

                        <div class="form-group  col-md-12"  style="display:none;">
                          <label class="col-md-3">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
                          <input type="tel" class="col-md-8 ge_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}" tabindex="7">
                          <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
                        </div>
                        <hr class="solid-horizontal-line">

                        <h2 class="col-md-12">{{ __('lang.product_form_step2')}}</h2>
                        <div  class="col-md-12" id="variant_table">
                          @php $v=0; $i=0; @endphp
                          @if(count($VariantProductAttributeArr)>0)
                            
                            @foreach($VariantProductAttributeArr as $variant_key1=>$variant)
                              @php $v++; @endphp

                                <?php $attribute  = $variant['attributes'][0]; ?>
                                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                                
                                  <input type="hidden" class="variant_id form-control ge_input variant_field" value="{{$variant_key1}}" name="variant_id[{{$i}}]" >
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.sku_label')}} <span class="de_col">*</span></label>
                                    <input type="text" class="col-md-8 ge_input sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value="{{$variant['sku']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.weight_label')}} <span class="de_col">*</span></label>
                                    <input type="text" class="col-md-8 ge_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value="{{$variant['weight']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.price_label')}} <span class="de_col">*</span></label>
                                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value="{{$variant['price']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.qty_label')}} <span class="de_col">*</span></label>
                                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value="{{$variant['quantity']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.select_attribute_label')}} <span class="de_col">*</span></label>
                                    <select id="{{$attribute['id']}}" style="  width: 34%;"  class="col-md-4 variant_field ge_input select_attribute preselected_attribute" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                                      <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>

                                        @foreach ($attributesToSelect as $attr)
                                          @if($attribute['attribute_id']==$attr->id)
                                            <option selected="selected" value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                          @else
                                          
                                          <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                          @endif
                                        @endforeach
                                    </select>
                                    <select style="margin-left: 10px; width: 32%;" selected_attribute_value="{{$attribute['attribute_value_id']}}" class="variant_field {{$attribute['id']}} col-md-4 ge_input select_attribute_value" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                                      <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}</option>

                                    </select>
                                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                                  </div>
                                  
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                                    <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image " name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">
                                    
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="margin-top:40px;"></span>  
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="margin-top:40px;"></span>   
                                  </div>
                                  <div class="selected_images col-md-12">
                                    @if($variant['image']!='')
                                      @php $images  = explode(',',$variant['image']);
                                      @endphp
                                        @foreach($images as $image)
                                          <div>
                                              <input type="hidden" class="form-control ge_input hidden_images" value="{{$image}}"  name="hidden_images[{{$i}}][]" placeholder="{{ __('lang.image_label')}}">
                                                  
                                                  
                                            <img src="{{url('/')}}/uploads/ProductImages/{{$image}}" width="40" height="40">
                                            <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                                          </div>
                                        @endforeach
                                    @endif
                                  </div>
                                  <div class="remove_variant_div"></div>
                                  <div class="loader"></div>
                                 
                                </div>
                              
                              @php $i++; @endphp
                            @endforeach  
                          @endif

                          <div class="col-md-4 text-right add-varinat-btn" style="margin-bottom: 10px;">
                              <a title="{{ __('lang.add_variant_btn')}}" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" style="margin-left: 760px !important;" ><span><i class="fa fa-plus"></i>{{ __('lang.add_variant_btn')}}</span> </a>
                          </div>
                          <div class="all_saved_attributes" ></div>
                          <div class="col-md-9">&nbsp;</div>
               <div class="info col-md-3" style="background-color: #e6f2ff;padding: 20px;
               border-radius: 15px;margin-bottom: 10px;width: 22%;">Lägg till andra varianter
                  av din produkt, så som
                  färg eller storlek etc.</div>
                          <span class="solid-horizontal-line"></span>
                        </div>
                        <h2 class="col-md-12">{{ __('lang.product_form_step3')}}</h2>

                        <div class="form-group col-md-12" id="shipping_method_ddl_div">
                          <label class="col-md-3">{{ __('users.shipping_method_label')}}</label>
                          <select class="col-md-8 ge_input" name="shipping_method_ddl" id="shipping_method_ddl">
                          <option value="">{{ __('users.select_shipping_method')}}</option>
                          <option  value="Platta fraktkostnader" <?php if($product->shipping_method ==  "Platta fraktkostnader"){ echo "selected"; } ?>>{{ __('users.flat_shipping_charges')}}</option>
                          <option value="Andel fraktkostnader" <?php if($product->shipping_method ==  "Andel fraktkostnader"){ echo "selected"; } ?>>{{ __('users.prcentage_shipping_charges')}}</option>
                          </select>
                        </div>

                        <div class="form-group col-md-12" id="shipping_charges_div">
                          <label class="col-md-3">{{ __('users.shipping_charges_label')}}</label>
                          <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="{{ (old('shipping_charges')) ? old('shipping_charges') : $product->shipping_charges}}">
                        </div>
                        <label class="col-md-12">
                          {{ __('users.free_shipping_label')}}
                            <input style="margin-left: 17%;" type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($product->free_shipping ==  "free_shipping"){ echo "checked"; } ?>>
                          </label>
                    </div>
                  </div>
                  <div class="row tijara-content">

                  
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12 text-center">
                          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>

                          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
                        </div>


                  </div>
              </div>
            </form>
          
      </div> <!-- /col-10 -->

</div>
</div>
</div>
</div> <!-- /container -->
<script>var siteUrl="{{url('/')}}";</script>


@endsection

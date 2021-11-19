@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <style>
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
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">Edit Seller Details</p>
  <form method="POST" action="{{route('adminSellerUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
            
            <h2 class="col-md-12 product_add_h2" style="margin-left: -12px;">{{ __('lang.product_form_step1')}}</h2>
              <input type="hidden" name="product_id" value="{{$product_id}}">

              <div class="form-group">
                <label>{{ __('lang.product_title_label')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" id="title" placeholder="{{ __('lang.product_title_label')}} " value="{{ (old('title')) ?  old('title') : $product->title}}" tabindex="1" onblur="convertToSlug(this)">
                <div class="text-danger" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </div>            
              </div>

             <div class="form-group">
              <label>{{ __('lang.product_description_label')}}  <span class="text-danger">*</span></label>
                <textarea class="form-control product_description" style="width: 67%; height: 175px;"
                 name="description" id="" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
                <div class="text-danger" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </div>
            </div>

             <div class="form-group">
              <label>{{ __('lang.status_label')}} <span class="text-danger">*</span></label>
              <select class="select2 form-control" name="status" id="status"  placeholder="" tabindex="8" >
                <option @if($product->status=='active') selected="selected" @endif value="active">{{ __('lang.active_label')}}</option>
                <option @if($product->status=='block') selected="selected" @endif value="block">{{ __('lang.block_label')}}</option>
              </select>
              <div class="text-danger"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</div>
            </div>

            <div class="form-group">
              <label>{{ __('lang.category_label')}}<span class="text-danger">*</span></label>
              <select class="select2 form-control" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
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
              <div class="text-danger"  id="err_category" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</div>
            </div>

            <div class="form-group" style="display:none;">
              <label>{{ __('lang.product_slug_label')}} <span class="text-danger">*</span></label>
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{ (old('product_slug')) ?  old('product_slug') : $product->product_slug}}" tabindex="1" readonly="readonly">
              <span class="text-danger slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
            </div>
           
            <div class="form-group">
            <label>{{ __('lang.product_discount_label')}}</label>
            <input type="text" class="form-control number" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{ (old('discount')) ?  old('discount') : $product->discount}}" tabindex="1">
            <div class="text-danger"  id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </div>
          </div>

          <div class="form-group"  style="display:none;">
            <label>{{ __('lang.sort_order_label')}} <span class="text-danger"></span></label>        
            <input type="tel" class="form-control" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}" tabindex="7">
            <div class="text-danger"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </div>         
          </div>

            <h2 class="col-md-12 product_add_h2">{{ __('lang.product_form_step2')}}</h2>

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
                                    <div class="col-md-8">
                                    <input type="text" class="col-md-8 ge_input sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value="{{$variant['sku']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                   </div>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.weight_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="text" class="col-md-8 ge_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value="{{$variant['weight']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8"  id="err_sku" ></span>
                                  </div>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.price_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value="{{$variant['price']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                    </div>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.qty_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value="{{$variant['quantity']}}" tabindex="7">
                                    <span class="invalid-feedback col-md-8" id="err_sku" ></span>
                                  </div>
                                  </div>
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.select_attribute_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
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
                                    <span class="invalid-feedback col-md-8"  id="err_sku" ></span>

                                  </div>
                                  </div>
                                  
                                  <div class="form-group  col-md-12" >
                                    <label class="col-md-3">{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                                    <div class="col-md-8">
                                    <div class="selected_images col-md-12">
                                    @if($variant['image']!='')
                                      @php $images  = explode(',',$variant['image']);
                                      @endphp
                                        @foreach($images as $image)
                                          <div>
                                            <input type="hidden" class="form-control ge_input hidden_images" value="{{$image}}"  name="hidden_images[{{$i}}][]" placeholder="{{ __('lang.image_label')}}">
                                            <img src="{{url('/')}}/uploads/ProductImages/{{$image}}" width="78" height="80">
                                            <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                                          </div>
                                        @endforeach
                                    @endif
                                  </div>
                                    <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image " name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">
                                    
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="margin-top:40px;"></span>  
                                     <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="margin-top:40px;"></span> 
                                     </div>  
                                  </div>
                               
                                  
                                  <!-- <div class="selected_images col-md-12"></div> -->
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
            </div>

          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
             <h2 class="col-md-12 product_add_h2">{{ __('lang.product_form_step3')}}</h2>
              <div class="form-group col-md-12" id="shipping_method_ddl_div">
                  <label class="col-md-3">{{ __('users.shipping_method_label')}}</label>
                  <div class="col-md-8">
                  <select class="col-md-8 ge_input" name="shipping_method_ddl" id="shipping_method_ddl">
                  <option value="">{{ __('users.select_shipping_method')}}</option>
                  <option  value="Platta fraktkostnader" <?php if($product->shipping_method ==  "Platta fraktkostnader"){ echo "selected"; } ?>>{{ __('users.flat_shipping_charges')}}</option>
                  <option value="Andel fraktkostnader" <?php if($product->shipping_method ==  "Andel fraktkostnader"){ echo "selected"; } ?>>{{ __('users.prcentage_shipping_charges')}}</option>
                  </select>
                </div>
                </div>

             <div class="form-group col-md-12" id="shipping_charges_div">
                <label class="col-md-3">{{ __('users.shipping_charges_label')}}</label>
                <div class="col-md-8">
                <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="{{ (old('shipping_charges')) ? old('shipping_charges') : $product->shipping_charges}}">
              </div>
              </div>

               <div class="col-md-12">
                 <label class="col-md-3"> {{ __('users.free_shipping_label')}}</label>
                  <div class="col-md-8">
                    <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" <?php if($product->free_shipping == "free_shipping"){ echo "checked"; } ?>>
                  </div>
                  </div>

            <div class="form-group col-md-12">
                      <label  class="col-md-3"> {{ __('users.pick_from_store')}} </label>
                      <div class="col-md-8">
                        <div class="row">
                        <div class="col-md-1"  class="is_pick_from_store">
                           <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  <?php if($product->is_pick_from_store ==  "1"){ echo "checked"; } ?>>
                       </div>
                       <div class="col-md-8">
                         <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="{{ __('users.pick_up_address')}}" value="{{ (old('store_pick_address')) ? old('store_pick_address') : $product->store_pick_address}}">
                       </div>
                        </div>
                      </div>              
                    </div>

      

          

          
          </div>
        </div>
        
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success saveproduct" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
          <a href="{{route('adminSeller')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('#phone_number').mask('00 000 00000');
  });

$(document).ready(function() {
  $(".add-image").click(function(){ 
    var existing_images = $(".existing-images").length;
    var cloned_images = $(".cloned:visible").length;

    if((existing_images + cloned_images) >= 5) {
      $(".cloned-danger").html('Max 5 images are allowed for Agent.');
      return false;
    }
    var html = $(".clone").html();
    $(".increment").after(html);
  });

  $("body").on("click",".remove-image",function(){ 
  $(this).parents(".form-group").remove();
  $(".cloned-danger").html('')
  });
});

</script>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script>
  $(document).ready(function() {
    $('.description').richText();
  });


/*function to check unique store name
* @param : store name
*/
  function checkStoreName(inputText){

    var store_name= inputText.value;
     $.ajax({
      url: "{{url('/')}}"+'/admin/seller/checkstore/?store_name='+store_name+'&id='+$('#hid').val(),
      type: 'get',
      data: { },
      success: function(output){
        if(output !='')
         alert(output);
        }
    });
  }
</script>
@endsection('middlecontent')
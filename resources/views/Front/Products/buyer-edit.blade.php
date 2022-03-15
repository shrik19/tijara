@extends('Front.layout.template')
@section('middlecontent')
<style>
.login_box
{
width:100% !important;
}

.selected_images {
    background-image: url(../../uploads/Images/multiple_no_images.png);
    background-repeat: no-repeat;
    height: 85px;
    padding-left: 0;
  margin-bottom: 10px;
}

.selected_images>div {
    float: left;
  border: 2px solid #ccc;
  margin: 0 !important;
  }

.selected_images a.remove_image {
    position: absolute;
    bottom: 3px;
    margin-left: -75px;
}

.invalid-feedback {
    position: relative !important;
}
</style>

<div class="mid-section p_155">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <div class="row">
       <div class="col-md-12"> 
     
        <!-- Example row of columns -->
        @if($subscribedError)
          <div class="alert alert-danger">{{$subscribedError}}</div>
        @endif
        <div class="tijara-content">
         <div class="seller_info border-none">
          <div class="card">
          <div class="card-header ml-0 row">
                <div class="col-md-9 pl-0">
                  <h2 class="page_heading new_add_heading" style="margin-left: 20px;">{{ __('users.buyer_product_form_label')}}</h2>
              <!--  <hr class="heading_line"/> -->
               </div>
               <div class="col-md-3 new_add text-right" style="margin-top:30px;margin-left: -14px;">
               <a href="{{route('manageFrontProducts')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
          </div>
          <!-- <hr class="heading_line"/> -->
          </div>
          </div>
          <div class="row" style="margin-top: 20px;">

      
          <form id="product-form" class="tijara-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
          @csrf
        


        

          @include ('Front.alert_messages')
          <div class="col-md-12">

          <div class="login_box">
            <div class="col-md-6">
                 <input type="hidden" name="product_id" value="{{$product_id}}">
                <div class="form-group">
                <label class="col-md-12" >{{ __('users.sellers_title')}} <span class="de_col">*</span></label>
                <input type="text" required class="login_input form-control" name="user_name" id="user_name" placeholder="{{__('lang.product_buyer_name')}} *" value="@if(isset($buyerProduct->user_name)){{$buyerProduct->user_name}} @else {{old('user_name')}} @endif" tabindex="1">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_seller_name" >@if($errors->has('user_name')) {{ $errors->first('user_name') }}@endif </span>
                </div>

                <div class="form-group">
                <input type="email" required class="login_input form-control email" name="user_email" id="user_email" placeholder="{{ __('lang.product_buyer_email')}} *" value="@if(isset($buyerProduct->user_email)) {{$buyerProduct->user_email}} @else {{old('user_email')}} @endif" tabindex="1" >
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_seller_email" >@if($errors->has('user_email')) {{ $errors->first('user_email') }}@endif </span>
                </div>

                <div class="form-group">
                <input type="tel" required class="login_input form-control telphone" name="user_phone_no" id="user_phone_no" placeholder="{{ __('lang.product_buyer_phone_no')}} *" value="@if(isset($buyerProduct->user_phone_no)) {{$buyerProduct->user_phone_no}} @else {{old('user_phone_no')}} @endif" tabindex="1">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_user_phone_no" >@if($errors->has('user_phone_no')) {{ $errors->first('user_phone_no') }}@endif </span>
                </div>

                <div class="form-group">
                <label class="col-md-12" >{{ __('lang.category_label')}}</label>
                <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
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

                <div class="form-group">
                <label class="col-md-12" >{{ __('lang.product_country')}} <span class="de_col">*</span></label>
                <input type="text" class="login_input form-control" name="country" id="country" placeholder="{{ __('lang.product_country')}} " value="@if(isset($buyerProduct->country)) {{$buyerProduct->country}} @else {{old('country')}} @endif" tabindex="1">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_seller_county" >@if($errors->has('country')) {{ $errors->first('country') }}@endif </span>
                </div>

                <div class="form-group">
                <label class="col-md-12" >{{ __('lang.product_location')}} <span class="de_col">*</span></label>
                <input type="text" class="login_input form-control" name="location" id="location" placeholder="{{ __('lang.product_location')}} " value="@if(isset($buyerProduct->location)) {{$buyerProduct->location}} @else {{old('location')}} @endif" tabindex="1">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_location" >@if($errors->has('location')) {{ $errors->first('location') }}@endif </span>
                </div>


            </div>
            <div class="col-md-6">
              <div class="form-group">
              <label class="col-md-12">{{ __('users.buyer_product_title')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="title" id="title" placeholder="{{ __('users.buyer_product_title')}} *" value="{{ (old('title')) ?  old('title') : $product->title}}" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
              </div>

              <div class="form-group">
              <label class="col-md-12">{{ __('lang.product_description_label')}}</label>
              <textarea class="form-control login_input" name="description" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2" rows="5" cols="5">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
              <span class="invalid-feedback col-md-12" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
              </div>

              <div id="variant_table">
                @php $v=0; $i=0; @endphp
                @if(count($VariantProductAttributeArr)>0)

                @foreach($VariantProductAttributeArr as $variant_key1=>$variant)
                @php $v++; @endphp

                <?php $attribute  = $variant['attributes'][0]; ?>
                <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">

                <input type="hidden" class="variant_id form-control login_input variant_field" value="{{$variant_key1}}" name="variant_id[{{$i}}]" >
                <div style="display:none;" class="form-group  col-md-6 ptb-15 " >
                <label class="col-md-12">{{ __('lang.sku_label')}} <span class="de_col"></span></label>
                <input type="text" class="form-control login_input sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value="{{$variant['sku']}}" tabindex="7">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_sku" ></span>
                </div>
                <div style="display:none;" class="form-group  col-md-6 ptb-15" >
                <label class="col-md-12">{{ __('lang.weight_label')}} <span class="de_col"></span></label>
                <input type="text" class="form-control login_input weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value="{{$variant['weight']}}" tabindex="7">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_sku" ></span>
                </div>
                <div class="form-group" style="margin-top: 50px;">
                <label class="col-md-12">{{ __('lang.price_label')}} <span class="de_col">*</span></label>
                <input type="tel" class="form-control login_input price number variant_field" id="price" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value="{{$variant['price']}}" tabindex="7">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_price" ></span>
                </div>
                <div style="display:none;" class="form-group  col-md-6 ptb-15" >
                <label class="col-md-12">{{ __('lang.qty_label')}} <span class="de_col"></span></label>
                <input type="tel" class="form-control login_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value="{{$variant['quantity']}}" tabindex="7">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_sku" ></span>
                </div>
                <div style="display:none;" class="form-group  col-md-6 ptb-15" >
                <label class="col-md-12">{{ __('lang.select_attribute_label')}} <span class="de_col"></span></label>
                <select id="{{$attribute['id']}}" class="col-md-4 variant_field login_input select_attribute preselected_attribute" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>

                @foreach ($attributesToSelect as $attr)
                @if($attribute['attribute_id']==$attr->id)
                <option selected="selected" value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                @else

                <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                @endif
                @endforeach
                </select>
                <select style="margin-left: 10px;" selected_attribute_value="{{$attribute['attribute_value_id']}}" class="variant_field {{$attribute['id']}} col-md-4 login_input select_attribute_value" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}</option>

                </select>
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_sku" ></span>
                </div>

                <div class="form-group">
                <label class="col-md-12">{{ __('lang.image_label')}} <span class="de_col">*</span></label>

                <div class="selected_images col-md-12">
                     @if($variant['image']!='')
                     @php $images  = explode(',',$variant['image']);
                     @endphp
                     @foreach($images as $image)
                     <div>
                     <input type="hidden" class="form-control login_input hidden_images" value="{{$image}}"  name="hidden_images[{{$i}}][]" placeholder="{{ __('lang.image_label')}}">
                     <img src="{{url('/')}}/uploads/ProductImages/{{$image}}" width="78" height="80">
                     <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                     </div>
                     @endforeach
                     @endif

               </div>
                <input type="file" variant_id="<?php echo $i; ?>" class="form-control login_input image  variant_image " name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">

                <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="margin-top: 3px;margin-left: 2px;"></span>  
                <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="margin-top: 3px;margin-left: 2px;"></span>   

                </div>

                
               
                </div>
                <div class="remove_variant_div"></div>
                <div class="loader"></div>
                </div>

                @php $i++; @endphp
                @endforeach  
                @endif

                <div class="all_saved_attributes" ></div>
                



                <div class="form-group col-md-6 ptb-15" style="display:none;">
                <label class="col-md-6">{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
                <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
                <input type="text" class="col-md-6 form-control login_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{ (old('product_slug')) ?  old('product_slug') : $product->product_slug}}" tabindex="1" readonly="readonly">
                <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
                </div>

                <div class="form-group col-md-6 ptb-15"  style="display:none;">
                <label class="col-md-12">{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
                <p class="meta-data">( {{ __('users.meta_title_info')}} )</p>
                <input type="text" class="form-control login_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{(old('meta_title')) ?  old('meta_title') : $product->meta_title}}" tabindex="4">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
                </div>

                <div class="form-group col-md-6 ptb-15 " style="display:none;">
                <label class="col-md-12">{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
                <p class="meta-data">( {{ __('users.meta_desciption_info')}} )</p>
                <input type="text" class="form-control login_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{(old('meta_description')) ?  old('meta_description') : $product->meta_description}}" tabindex="5">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
                </div>

                <div class="form-group col-md-6 ptb-15" style="display:none;">
                <label class="col-md-12">{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
                <p class="meta-data">( {{ __('users.meta_keyword_info')}} )</p>
                <input type="text" class="form-control login_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{(old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword}}" tabindex="6">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
                </div>

                <div class="form-group col-md-6" style="display:none;">
                <label class="col-md-12">{{ __('lang.status_label')}} </label>
                <select class="select2 form-control login_input" name="status" id="status"  placeholder="" tabindex="8" >
                <option @if($product->status=='active') selected="selected" @endif value="active">{{ __('lang.active_label')}}</option>
                <option @if($product->status=='block') selected="selected" @endif value="block">{{ __('lang.block_label')}}</option>
                </select>
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
                </div>

                <div class="form-group col-md-6" style="display:none;">
                <label class="col-md-12">{{ __('lang.product_discount_label')}}</label>
                <input type="text" class="form-control login_input number" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{ (old('discount')) ?  old('discount') : $product->discount}}" tabindex="1">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </span>
                </div>

                <div class="form-group col-md-6"  style="display:none;">
                <label class="col-md-12">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
                <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}" tabindex="7">
                <span class="invalid-feedback col-md-12" style="text-align: left;"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
                </div>

                 <div class="form-group">
                   <input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value=""><span class="remember-text">{{ __('users.read_and_approve_chk')}}<a href="javascript:void(0)"> &nbsp;{{ __('users.terms_of_use')}} &nbsp;</a>  {{ __('users.and_label')}}  &nbsp;<a href="javascript:void(0)">{{ __('users.privacy_policy')}}</a> <a href="javascript:void(0)">{{ __('users.store_terms')}}</a></span>  
               </div>

               </div>

            </div>
          
          </div>
      
          <div class="row">


          <div class="col-md-12">&nbsp;</div>
          <div class="col-md-12 text-center">
          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveBuyerProduct" tabindex="9">{{ __('lang.save_btn')}}</button>

          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
          </div>


          </div>
          </div>
          </form>
        <!-- </div> -->

    </div> <!-- /col-10 -->
</div>
</div>
  </div>
  </div>
</div>
  </div>
</div> <!-- /container -->
<script>var siteUrl="{{url('/')}}";</script>
<script type="text/javascript">
$('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').parent("div").remove();
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});

$( document ).ready(function() {
  $('.select2-search__field').attr("placeholder", select_placeholder);
});
</script>

@endsection

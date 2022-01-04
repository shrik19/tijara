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

.invalid-feedback {
    position: relative !important;
}
</style>

<div class="mid-section p_155">
  <div class="container-fluid">
    <div class="container-inner-section-1">
      <div class="row">

          <!-- Example row of columns -->
          @if($subscribedError)
          <div class="alert alert-danger">{{$subscribedError}}</div>
          @endif

          <div class="col-md-12">
              @include ('Front.alert_messages')
           <!--      <div class="col-md-12"> -->
            <!--  <div class="col-md-10"> -->
            <div class="card">
              <div class="card-header  row seller_header">
                <div class="col-md-10">
                  <h2 class="page_heading" style="margin-left: 20px;">{{ __('users.buyer_product_form_label')}}</h2>
                  <!-- <hr class="heading_line"/> -->
                </div>

                <div class="col-md-2 text-right" style="margin-top:30px;">
                  <a href="{{route('manageFrontProducts')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
                </div>
              </div>
            </div>
          <div class="seller_mid_cont"  style="margin-top: 20px;">

          <form id="product-form" class="tijara-form" action="{{route('frontProductShowCheckoutNumber','swish')}}" method="post" enctype="multipart/form-data">
 
          @csrf


      <!--     <div class="col-md-10">

          <h2>{{ __('users.buyer_product_form_label')}}</h2>
          <hr class="heading_line"/>
          </div>
           -->

        


              <!-- </div> -->
           <!--  </div> -->
<!--               <div class="seller_mid_cont"> -->
          <div class="col-md-12">
            <div class="login_box">
               <div class="col-md-6">
                <input type="hidden" name="product_id" value="{{$product_id}}">
                  <div class="form-group">
                  <label class="col-md-12 label_css" >{{ __('users.sellers_title')}} <span class="de_col">*</span></label>
                  <input type="text" required class="login_input form-control" name="user_name" id="user_name" placeholder="{{ __('lang.product_buyer_name')}}" value="{{old('user_name')}}" tabindex="1">
                  <span class="invalid-feedback col-md-12"  id="err_seller_name" >@if($errors->has('user_name')) {{ $errors->first('user_name') }}@endif </span>
                  </div>


                <div class="form-group">
                <input type="email" required class="login_input form-control" name="user_email" id="user_email" placeholder="{{ __('lang.product_buyer_email')}}" value="{{old('user_email')}}" tabindex="1" >
                <span class="invalid-feedback col-md-12"  id="err_seller_email" >@if($errors->has('user_email')) {{ $errors->first('user_email') }}@endif </span>
                </div>
                 <div class="form-group">
                <!--   <label class="col-md-12" >{{ __('lang.product_buyer_phone_no')}} <span class="de_col">*</span></label> -->
                  <input type="tel" required class="login_input form-control" name="user_phone_no" id="user_phone_no" placeholder="{{ __('lang.product_buyer_phone_no')}}" value="{{old('user_phone_no')}}" tabindex="1">
                  <span class="invalid-feedback col-md-12" id="err_user_phone_no" >@if($errors->has('user_phone_no')) {{ $errors->first('user_phone_no') }}@endif </span>
                  </div>

                  <div class="form-group buyer-productCat-select">
                  <label class="col-md-12 label_css"  >{{ __('lang.category_label')}}</label>
                  <select class="select2 login_input form-control" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
                  <option></option>
                  @foreach($categories as $cat_id=>$category)
                  <optgroup label="{{$category['maincategory']}}">
                  <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
                  @foreach($category['subcategories'] as $subcat_id=>$subcategory)
                  <option value="{{$subcat_id}}">{{$subcategory}}</option>
                  @endforeach
                  </optgroup>
                  @endforeach
                  </select>
                  <span class="invalid-feedback col-md-12"  id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12 label_css" >{{ __('lang.product_country')}} <span class="de_col">*</span></label>
                    <input type="text" class="login_input form-control" name="country" id="country" placeholder="{{ __('lang.product_country')}} " value="{{old('country')}}" tabindex="1">
                    <span class="invalid-feedback col-md-12"  id="err_seller_county" >@if($errors->has('country')) {{ $errors->first('country') }}@endif </span>
                    </div>

                     <div class="form-group" style="margin-top: 7px;">
                    <label class="col-md-12 label_css" >{{ __('lang.product_location')}} <span class="de_col">*</span></label>
                    <input type="text" class="login_input form-control" name="location" id="location" placeholder="{{ __('lang.product_location')}} " value="{{old('location')}}" tabindex="1">
                    <span class="invalid-feedback col-md-12"  id="err_location" >@if($errors->has('location')) {{ $errors->first('location') }}@endif </span>
                    </div>



               </div>
               <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-md-12 label_css" >{{ __('users.buyer_product_title')}} <span class="de_col">*</span></label>
                    <input type="text" class="login_input form-control" name="title" id="title" placeholder="{{ __('users.buyer_product_title')}} " value="{{old('title')}}" tabindex="1" onblur="convertToSlug(this)">
                    <span class="invalid-feedback col-md-12"  id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                    <label class="col-md-12 label_css" >{{ __('lang.product_description_label')}}</label>
                   <textarea class="login_input form-control" name="description" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2" rows="5" cols="20">{{old('description')}}</textarea>
                     <span class="invalid-feedback col-md-12" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
                    </div>

                    <div class="loader"></div>
                    <div id="variant_table">
                    <?php

                    $i  = 0; ?>

                    <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">

                    <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12 label_css" >{{ __('lang.sku_label')}} <span class="de_col"></span></label>
                    <input type="text" class="login_input form-control sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value='123' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                    </div>
                    <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12 label_css" >{{ __('lang.weight_label')}} <span class="de_col"></span></label>
                    <input type="text" class="login_input form-control weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value='10' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                    </div>
                    <div class="form-group" style="margin-top: 50px;">
                    <label class="col-md-12 label_css" >{{ __('lang.price_label')}} <span class="de_col"></span>*</label>
                    <input type="tel" class="login_input form-control price number variant_field" id="price" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value='{{ old("price.$i")}}' tabindex="7">
                    <span class="invalid-feedback col-md-12"  id="err_price" ></span>
                    </div>
                    <div style="display:none;" class="form-group  col-md-6" >
                    <label class="col-md-12 label_css" >{{ __('lang.qty_label')}} <span class="de_col"></span></label>
                    <input type="tel" class="login_input form-control quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value='1' tabindex="7">
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                    </div>
                    <div style="display:none;" class="form-group  col-md-12" >
                    <label class="col-md-12 label_css" >{{ __('lang.select_attribute_label')}} <span class="de_col"></span></label>
                    <select id="0" class="preselected_attribute col-md-6 login_input form-control select_attribute variant_field" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >

                    @foreach ($attributesToSelect as $attr)
                    <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                    @endforeach
                    </select>
                    <select style="margin-left: 10px;" selected_attribute_value="" class="buyer-product 0 variant_field col-md-6 login_input form-control select_attribute_value variant_field" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                    <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}</option>

                    </select>
                    <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_sku" ></span>
                    </div>
                    
                    <div class="form-group">
                    <label class="col-md-12 label_css" >{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                    <div class="selected_images col-md-12"></div>
                    <input type="file" variant_id="<?php echo $i; ?>" class="login_input form-control image  variant_image" name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7" style="margin-top: 90px">

                    <span class="invalid-feedback col-md-12 productErr" id="err_variant_image" style="margin-top: 3px;margin-left: 2px;"></span>  
                    <span class="invalid-feedback col-md-12 productErr" id="err_variant_hid_image" style="margin-top: 3px;margin-left: 2px;"></span>
                    </div>

          
                    

                    <div class="remove_variant_div"></div>
                   
                    </div>



                    <div class="all_saved_attributes" ></div>
                    </div>

                    <div class="form-group"  style="display:none;">
              <label class="col-md-12 label_css" class="col-md-6">{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="col-md-6 form-control login_input form-control slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{old('product_slug')}}" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
              </div>

              <div class="form-group col-md-6" style="display:none;">
              <label class="col-md-12 label_css" >{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
              <p class="meta-data">( {{ __('users.meta_title_info')}} )</p>
              <input type="text" class="login_input form-control" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{old('meta_title')}}" tabindex="4">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
              </div>

              <div class="form-group col-md-6" style="margin-top: 10px;display:none;">
              <label class="col-md-12 label_css" >{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
              <p class="meta-data">( {{ __('users.meta_desciption_info')}} )</p>
              <input type="text" class="login_input form-control" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{old('meta_description')}}" tabindex="5">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
              </div>

              <div class="form-group col-md-6" style="display:none;">
              <label class="col-md-12 label_css" >{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
              <p class="meta-data">( {{ __('users.meta_keyword_info')}} )</p>
              <input type="text" class="login_input form-control" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{old('meta_keyword')}}" tabindex="6">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
              </div>
              <div class="form-group  col-md-6" style="display:none;">
              <label class="col-md-12 label_css" style="margin-bottom: 15px;">{{ __('lang.status_label')}} </label>
              <select class="select2 login_input form-control" name="status" id="status"  placeholder="" tabindex="8" >
              <option value="active">{{ __('lang.active_label')}}</option>
              <option value="block">{{ __('lang.block_label')}}</option>
              </select>
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
              </div>


              <div class="form-group  col-md-6"  style="display:none;">
              <label class="col-md-12 label_css" >{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
              <input type="tel" class="login_input form-control" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $max_seq_no}}" tabindex="7">
              <span class="invalid-feedback col-md-12" style="text-align: center;"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
              </div>

           

              <div class="form-group" style="margin-top: 40px;">
                  <input type="checkbox" name="chk-appoved" id="chk_privacy_policy" value=""><span class="remember-text">{{ __('users.read_and_approve_chk')}}<a href="{{url('/')}}/page/villkor" class="de_col">&nbsp;{{ __('users.terms_of_use')}} </a> <a href="{{url('/')}}/page/villkor" class="de_col">{{ __('users.privacy_policy')}}</a></span>  
              </div>
              
              <div class="form-group swish_payment_ad_img">
                <img src="{{url('/')}}/uploads/Images/swish-payment-logo.png" width="90" height="65"><span>({{env('PRODUCT_POST_AMOUNT')}} kr)</span>
              </div>
                    
               </div>

              </div>
            </div>

      <!--     </div> -->
          <div class="row">
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12 text-center">
            <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" 
            class="btn btn-black debg_color login_btn saveBuyerProduct" tabindex="9">{{ __('lang.continue_to_swish')}}</button>

            <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
            </div>
          </div>

          </form>
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

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
  <p class="section-lead">Save Product Details</p>
  <form method="POST" action="{{route('adminProductStore')}}" class="needs-validation" id="product-form"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">
             <h2 class="col-md-12 product_add_h2 steps_no_css" style="margin-left: -12px;">{{ __('lang.product_form_step1')}}</h2>
            <div class="form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" tabindex="1" value="{{ old('title')}}" >
             
              <div class="text-danger" id="err_title">{{$errors->first('title')}}</div>
            </div>
			       
          <div class="form-group">
            <label>User <span class="text-danger">*</span></label>
            <select id="user_id" name="user_id" class="form-control select2" >
            <option value="">{{ __('lang.select_label')}}</option>
            @foreach($users as $user)
            <option value="{{$user->id}}">{{$user->fname}} {{$user->lname}}</option>
            @endforeach
            </select>            
            <div class="text-danger" id="err_seller_name">{{$errors->first('user_id')}}</div>
          </div>

    			  <div class="form-group">
                <label>{{ __('lang.product_description_label')}} <span class="text-danger">*</span></label>                
                  <textarea class="form-control product_description " style="width:100%; height: 175px;" name="description"  placeholder="{{ __('users.service_description_placeholder')}}" value="" tabindex="2">{{old('description')}}</textarea>                
                  <div class="text-danger" id="err_description">@if($errors->has('description')) {{ $errors->first('description') }}@endif</div>
            </div>

            <div class="form-group">
              <label>{{ __('lang.category_label')}}  <span class="text-danger">*</span></label>
              <select multiple id="categories" name="categories[]" class="form-control select2" >
                <option></option>
                @foreach($categories as $cat_id=>$category)
                <optgroup label="{{$category['maincategory']}}">
                  @foreach($category['subcategories'] as $subcat_id=>$subcategory)        
                  <option value="{{$subcat_id}}">{{$subcategory}}</option>
                  @endforeach
                </optgroup>
                @endforeach
              </select>
              <div class="text-danger" id="err_category">
              @if($errors->has('categories')) {{ $errors->first('categories') }}@endif
              </div>
            </div>

            <div class="form-group">
              <label>{{ __('lang.status_label')}} <span class="text-danger">*</span></label>
              <select class="form-control" name="status" id="status"  placeholder="" tabindex="8" >
                <option value="active">{{ __('lang.active_label')}}</option>
                <option value="block">{{ __('lang.block_label')}}</option>
                </select>
              <div class="text-danger"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</div>
            </div>
            <!-- display none fields -->
            <div class="form-group" style="display:none;">
              <label>{{ __('lang.product_slug_label')}} <span class="text-danger">*</span></label>
         
              <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
              <input type="text" class="form-control slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{old('product_slug')}}" tabindex="1" readonly="readonly">
              <div class="text-danger slug-name-err">@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </div>
            </div>

            
            <div class="form-group">
              <label>{{ __('lang.product_discount_label')}}</label>
              <input type="text" class="form-control" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{old('discount')}}" tabindex="1">
              <div class="text-danger" id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </div>
            </div>

            <div class="form-group"  style="display:none;">
              <label>{{ __('lang.sort_order_label')}} <span class="text-danger"></span></label>
              <input type="tel" class="" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : @$max_seq_no}}" tabindex="7">
              <div class="text-danger">@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </div>
            </div>
              <h2 class="col-md-12 product_add_h2 steps_no_css">{{ __('lang.product_form_step2')}}</h2>
            <!-- start -->
            <div  class="col-md-12" id="variant_table">
              <?php

              $i  = 0; ?>
              <div class="variant_tr" id="variant_tr" variant_id="<?php echo $i;?>">
                <div class="form-group" >
                  <label>{{ __('lang.sku_label')}} <span class="text-danger">*</span></label>
                  <input type="text" class="form-control sku variant_field" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value='{{ old("sku.$i")}}' tabindex="7">
                  <div class="variant_field_err text-danger"></div>
                 
                </div>
                <div class="form-group producterrDiv" >
                    <label>{{ __('lang.weight_label')}} <span class="text-danger">*</span></label>
                   
                    <input type="text" class="form-control weight variant_field" name="weight[<?php echo $i;?>]"  placeholder="{{ __('lang.weight_placeholder')}}" value='{{ old("weight.$i")}}' tabindex="7">
                    <div class="text-danger variant_field_err"  id="err_sku" ></div>
                </div>
                 <div class="form-group producterrDiv" >
                    <label>{{ __('lang.price_label')}} <span class="text-danger">*</span></label>
              
                    <input type="tel" class="form-control price number variant_field" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value='{{ old("price.$i")}}' tabindex="7">
                    <div class="text-danger variant_field_err" id="err_sku" ></div>
                 
                  </div>

                  <div class="form-group producterrDiv" >
                    <label>{{ __('lang.qty_label')}} <span class="text-danger">*</span></label>
 
                    <input type="tel" class="quantity form-control number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value='{{ old("quantity.$i")}}' tabindex="7">
                    <div class="text-danger variant_field_err" id="err_sku" ></div>
                  </div>

                   <div class="form-group producterrDiv" >
                    <label>{{ __('lang.select_attribute_label')}} <span class="text-danger">*</span></label>
                    <!-- <div class="col-md-12" style="display: flex;">
                      <div class="col-md-6"> -->
                    <select class="form-control select_attribute variant_field" name="attribute[<?php echo $i;?>][<?php echo $i;?>]" variant_id="<?php echo $i;?>" >
                      <option value=""> {{ __('lang.attribute_label')}} (ex färg)</option>

                        @foreach ($attributesToSelect as $attr)
                          <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                        @endforeach
                    </select>
    <!--                     <div class="text-danger variant_field_err" id="err_sku" ></div> -->
                   <!--  </div> -->
                   <!--  <div class="col-md-6"> -->
                    <select selected_attribute_value="" 
                    class=" variant_field form-control select_attribute_value variant_field" name="attribute_value[<?php echo $i;?>][<?php echo $i;?>]">
                      <option value="">{{ __('lang.attribute_value_label')}} (ex röd)</option>

                    </select>
                 <!--  </div> </div> -->
                
                    <p class="seller-logo-info" style="font-size: 13px;">Ändra eller lägg till nya egenskaper till vänster under Attribut</p>
                 
                  </div>

                     <div class="form-group producterrDiv var_img_div" >
                    <label>{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                 
                      <div class="selected_images"></div>
                      <input type="file" variant_id="<?php echo $i; ?>" class="form-control ge_input image  variant_image variant_field" name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">
                      <div class="variant_field_err text-danger" id="err_variant_image" style="margin-left:-1px;"></div>  
                      <div class="variant_field_err text-danger" id="err_variant_hid_image"></div> 
                      <p class="seller-logo-info col-md-8" style="font-size: 13px;">Lägg till en bild i storlek (1080x1080px)</p>  
               
                    
                                  
                  </div>
                   <div class="remove_variant_div"></div>
                  <div class="loader"></div>
              </div>
              <div class="col-md-12 text-right add-varinat-btn" style="margin-bottom: 10px;">
                  <a title="{{ __('lang.add_variant_btn')}}" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" ><span><i class="fa fa-plus"></i>{{ __('lang.add_variant_btn')}}</span> </a>
               </div>


              <div class="all_saved_attributes" ></div>
              <div class="col-md-9">&nbsp;</div>
               <div class="info col-md-3" style="background-color: #e6f2ff;padding: 20px;
               border-radius: 15px;margin-bottom: 10px;">Lägg till andra varianter
                  av din produkt, så som
                  färg eller storlek etc.</div>
               <span class="solid-horizontal-line"></span>

            </div>
            <!-- end -->			
			
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-body">

           
            <h2 class="col-md-12 product_add_h2">{{ __('lang.product_form_step3')}}</h2>

            <div class="form-group" id="shipping_method_ddl_div">
              <label>{{ __('users.shipping_method_label')}}</label>
              <select class="form-control" name="shipping_method_ddl" id="shipping_method_ddl">
                <option value="">{{ __('users.select_shipping_method')}}</option>
                <option value="Platta fraktkostnader">{{ __('users.flat_shipping_charges')}}</option>
                <option value="Andel fraktkostnader">{{ __('users.prcentage_shipping_charges')}}</option>
              </select>
            </div>

            <div class="form-group" id="shipping_charges_div">
              <label>{{ __('users.shipping_charges_label')}}</label>
              <input type="text" class="form-control" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="{{ (old('shipping_charges')) }}">
            </div>


            <div class="form-group">
            <label>
             {{ __('users.free_shipping_label')}}</label>
              <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()">
            </div>

            <div class="form-group">
              <label> {{ __('users.pick_from_store')}} </label>
                <div class="row">
                <div class="col-md-1"  class="is_pick_from_store">
                 <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1">
               </div>
               <div class="col-md-8">
                  <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="{{ __('users.pick_up_address')}}" value="">
               </div>
                </div>  
            </div>

          </div>
        </div>
        <div class="col-12 text-right">
          <button type="submit" class="btn btn-icon icon-left btn-success saveproduct" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.save_btn')}}</button>&nbsp;&nbsp;
          <a href="{{route('adminProduct')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript" src="{{url('/')}}/assets/admin/js/select2.full.min.js"></script>
      <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/admin/css/select2.css">


<script type="text/javascript">
  
  $(document).ready(function() {
	  
	  $('#user_id').select2({
		
		});
		$('#categories').select2({
		placeholder:"select"
		});
    
  });
</script>

<script>

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
@endsection('middlecontent')
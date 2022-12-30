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
.tj-wid67{
  width: 72%;
}
.tj-svselect.tj-newslect .tj-wid67 .select2-container{
  width: 100% !important;
}

</style>

<div class="mid-section sellers_top_padding">
<div class="container-fluid">
  <div class="container-inner-section-1 tjd-sellcontainer">
    <div class="row">
  <!-- Example row of columns -->
   @if($subscribedError)
	    <div class="alert alert-danger">{{$subscribedError}}</div>
	    @endif
      <div class="col-md-2 tijara-sidebar" id="tjfilter">
      <button class="tj-closebutton" data-toggle="collapse" data-target="#tjfilter"><i class="fa fa-times"></i></button>
        @include ('Front.layout.sidebar_menu')
      </div>
      <div class="col-md-10 tijara-content margin_bottom_class tj-alignment">
           @include ('Front.alert_messages')
      <div class="seller_info">
      <div class="seller_header">

<h2 class="seller_page_heading"><button class="tj-filter-toggle-btn menu" data-toggle="collapse" data-target="#tjfilter"><i class="fas fa-bars"></i></button>{{ __('lang.product_form_label')}}</h2>
<!-- <hr class="heading_line"/> -->
</div>
  @if($is_seller)    
  <form id="product-form" class="tijara-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
  @else
  <form id="product-form" class="tijara-form" action="{{route('frontProductShowCheckout')}}" method="post" enctype="multipart/form-data">
  @endif
            @csrf

  

		  <div class="col-md-12 text-right" style="margin-top:30px;">
		  <a href="{{route('manageFrontProducts')}}" title="{{ __('lang.back_to_list_label')}}" class="de_col" ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
      </div>

      
      <div class="row tj-nodpad tjd-pad6">
      <div class="col-md-12 tjd-serviceform">

        <div class="login_box">

            <h2 class="col-md-12 product_add_h2 steps_no_css tj-savepr-head">{{ __('lang.product_form_step1')}}</h2>
            <input type="hidden" id="product_id" name="product_id" value="{{$product_id}}">

            <div class="form-group ">
              <label class="col-md-3 product_table_heading">{{ __('lang.product_title_label')}} <span class="de_col">*</span></label>
              <div class="col-md-8">
                <input type="text" class="col-md-8 ge_input " name="title" id="title" placeholder="{{ __('lang.product_title_label')}} " value="{{old('title')}}" tabindex="1" onblur="convertToSlug(this)">
                <span class="invalid-feedback col-md-8"  id="err_title">@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
              </div>
            </div>

            <div class="form-group ">
              <label class="col-md-3 product_table_heading">{{ __('lang.product_description_label')}} <span class="de_col">*</span></label>
              <div class="col-md-8">
          			<div class="form-group  producterrDiv col-md-8 p-0">
                  <textarea class="ge_input product_description col-md-8 " style="width:100%; height: 175px;" name="description"  placeholder="{{ __('users.service_description_placeholder')}}" value="" tabindex="2">{{old('description')}}</textarea>
                  <span class="invalid-feedback  col-md-8"  id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
                </div>
              </div>
            </div>
            
            <div class="form-group  producterrDiv">
              <label class="col-md-3 product_table_heading" >{{ __('lang.category_label')}} <span class="de_col">*</span></label>
              <div class="col-md-8 tjd-slectlimit  tjd-slectlimitcat">
                <select class="select2 col-md-8 ge_input tjselect" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
                  <option>{{ __('lang.select_label')}}</option>
                  @foreach($categories as $cat_id=>$category)
                  <optgroup label="{{$category['maincategory']}}">
                  <!--<option value="{{$cat_id}}">{{$category['maincategory']}}</option>-->
                  @foreach($category['subcategories'] as $subcat_id=>$subcategory)
                    <option value="{{$subcat_id}}">{{$subcategory}}</option>
                  @endforeach
                  </optgroup>
                  @endforeach
                </select>
                <span class="invalid-feedback  col-md-8" id="err_category" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
              </div>
            </div>

            <div class="form-group  producterrDiv">
              <label class="col-md-3 product_table_heading">{{ __('lang.status_label')}} <span class="de_col">*</span></label>
              <div class="col-md-8 tjd-slectlimit">
                <select class="select2 col-md-8 ge_input tjselect" name="status" id="status"  placeholder="" tabindex="8" >
                  <option value="active">{{ __('lang.active_label')}}</option>
                  <option value="block">{{ __('lang.block_label')}}</option>
                  </select>
                <span class="invalid-feedback col-md-12"  id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
              </div>
            </div>
            <div class="form-group " style="display:none;">
              <label class="col-md-6 product_table_heading">{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
              <div class="col-md-8">
                <p style="color:#000;font-size: 12px;">(This is the part of a URL which identifies a product on a website in an easy to read form)</p>
                <input type="text" class="col-md-6 form-control ge_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{old('product_slug')}}" tabindex="1" readonly="readonly">
                <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
              </div>
            </div>

            <div class="form-group " style="display:none;">
              <label class="col-md-3 product_table_heading">{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( {{ __('users.meta_title_info')}} )</p>
               <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{old('meta_title')}}" tabindex="4">
              <span class="invalid-feedback col-md-8"  id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
            </div>

            <div class="form-group " style="display:none;">
              <label class="col-md-3 product_table_heading">{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( {{ __('users.meta_desciption_info')}} )</p>
              <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{old('meta_description')}}" tabindex="5">
              <span class="invalid-feedback col-md-12"  id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
            </div>

            <div class="form-group " style="display:none;">
              <label class="col-md-3 product_table_heading">{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
              <p class="meta-data col-md-8">( {{ __('users.meta_keyword_info')}} )</p>
              <div class="col-md-3"></div>
              <input type="text" class="col-md-8 ge_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{old('meta_keyword')}}" tabindex="6">
              <span class="invalid-feedback col-md-8" id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
            </div>
            

            <div class="form-group ">
              <label class="col-md-3 product_table_heading">{{ __('lang.product_discount_label')}}</label>
              <div class="col-md-8">
              <input type="text" class="col-md-8 ge_input number" name="discount" id="discount" placeholder="{{ __('lang.product_discount_label')}} " value="{{old('discount')}}" tabindex="1">
              <span class="invalid-feedback col-md-12" id="err_discount" >@if($errors->has('discount')) {{ $errors->first('discount') }}@endif </span>
            </div>
            </div>
            <div class="form-group  col-md-12"  style="display:none;">
              <label class="col-md-3">{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
              <input type="tel" class="col-md-8 ge_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $max_seq_no}}" tabindex="7">
              <span class="invalid-feedback col-md-8"  id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
            </div>
          

            <h2 class="col-md-12 product_add_h2 steps_no_css tj-savepr-head">{{ __('lang.product_form_step2')}}</h2>
            <div  class="col-md-12" id="variant_table">
              <?php

              $i  = 0; ?>

            
                <div class="variant_tr tjsellercol2" id="variant_tr" variant_id="<?php echo $i;?>">
                 
                  <div class="form-group row" >
                    <label class="col-md-3 product_table_heading">{{ __('lang.sku_label')}} <span class="de_col"></span></label>
                    <div class="col-md-8">
                    <input type="text" class="col-md-8 ge_input sku" name="sku[<?php echo $i;?>]"  placeholder="{{ __('lang.sku_placeholder')}}" value='{{ old("sku.$i")}}' tabindex="7">
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
                  <div class="form-group producterrDiv row" >
                    <label class="col-md-3 product_table_heading">{{ __('lang.price_label')}} <span class="de_col">*</span></label>
                    <div class="col-md-8">
                    <input type="tel" class="col-md-8 ge_input price number variant_field" name="price[<?php echo $i;?>]"  placeholder="{{ __('lang.price_placeholder')}}" value='{{ old("price.$i")}}' tabindex="7">
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                    </div>
                  </div>
                  <div class="form-group producterrDiv row" >
                    <label class="col-md-3 product_table_heading">{{ __('lang.qty_label')}} <span class="de_col">*</span></label>
                    <div class="col-md-8">
                    <input type="tel" class="col-md-8 ge_input quantity number variant_field" name="quantity[<?php echo $i;?>]"  placeholder="{{ __('lang.qty_label')}}" value='{{ old("quantity.$i")}}' tabindex="7">
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                  </div>
                  </div>
                  <div class="form-group producterrDiv row" >
                    <?php for($ii=0;$ii<2;$ii++){
                      if($ii==0){?>
                    <label class="col-md-3 product_table_heading">{{ __('lang.select_attribute_label')}} <span class="de_col"></span></label>
                  <?php }else{?>
                    <div class="col-md-3"></div>
                 <?php }?>
                    <div class="col-md-8 tj-svselect tj-newslect">
                      <div class="row tj-wid67">
                        <div class="col-sm-6">
                          <select style="width: 32%;" class="col-md-4 ge_input select_attribute tjselect" name="attribute[<?php echo $i;?>][]"  variant_id="<?php echo $i;?>" id="<?php echo $ii?>">
                            <option value=""> {{ __('lang.attribute_label')}} (ex färg)</option>

                              @foreach ($attributesToSelect as $attr)
                                <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                              @endforeach
                          </select>
                          
                        </div>
                        <div class="col-sm-6">                    
                          <select style="margin-left: 10px;width: 34%;" selected_attribute_value="" 
                          class="variant2 col-md-4 ge_input select_attribute_value tjselect" name="attribute_value[<?php echo $i;?>][]" variant_id="<?php echo $i;?>" id="attribute_value<?php echo $ii?>">
                            <option value="">{{ __('lang.attribute_value_label')}} (ex röd)</option>

                          </select>                          
                        </div>
                      </div>
                    <span class="invalid-feedback  col-md-8" id="err_sku" ></span>
                    <?php  if($ii!=0){?>
                    <span class="seller-logo-info col-md-10" style="font-size: 13px;padding-left:0px">Ändra eller lägg till nya egenskaper till vänster under Attribut</span>
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
                    <span class="seller-logo-info col-md-8" style="font-size: 13px; padding-right:0px">Ändra eller lägg till nya egenskaper till vänster under Attribut</span>
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
                  
                  <div class="form-group producterrDiv var_img_div row" >
                    <label class="col-md-3 product_table_heading">{{ __('lang.image_label')}} <span class="de_col">*</span></label>
                    <div class="col-md-8">
                      <div class="selected_images col-md-12"></div>
                      <input type="file" variant_id="<?php echo $i; ?>" class="col-md-8 ge_input image  variant_image variant_field" name="image[<?php echo $i;?>]"  placeholder="{{ __('lang.image_label')}}" value='{{ old("image.$i")}}' tabindex="7">
                      <span class="invalid-feedback col-md-8" id="err_variant_image" style="margin-left:-1px;"></span>  
                      <span class="invalid-feedback col-md-8" id="err_variant_hid_image"></span> 

                      <p class="seller-logo-info col-md-12" style="font-size: 13px;">{{ __('messages.product_img_upload_info')}}</p>  
                    </div>
                    
                                  
                  </div>

                  <!-- <div class="col-md-3"></div> -->
                  <div class="remove_variant_div"></div>
                  <div class="loader"></div>
                </div>
              

               <div class="text-right add-varinat-btn" style="margin-bottom: 10px;">
                  <a title="{{ __('lang.add_variant_btn')}}" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" ><span><i class="fa fa-plus"></i>{{ __('lang.add_variant_btn')}}</span> </a>
               </div>
               
              <div class="all_saved_attributes" ></div>
              <div class="col-md-9">&nbsp;</div>
               <div class="info col-md-3 seller_product_varint_info">{{ __('messages.seller_product_varint_info')}}</div>
               <span class="solid-horizontal-line"></span>
            </div>

            <h2 class="col-md-12 product_add_h2 tj-savepr-head">{{ __('lang.product_form_step3')}}</h2>
			
            <div class="form-group producterrDiv" id="shipping_method_ddl_div">
              <label class="col-md-3 product_table_heading">{{ __('users.shipping_method_label')}}</label>
              <div class="col-md-8 tj-svselect tj-newslect">
              <select class="col-md-8 ge_input tjselect" name="shipping_method_ddl" id="shipping_method_ddl" >
                <option value="">{{ __('users.select_shipping_method')}}</option>
                <option value="Platta fraktkostnader" @if(@$users_details->shipping_method=="Platta fraktkostnader") selected="selected" @endif>{{ __('users.flat_shipping_charges')}}</option>
                <option value="Andel fraktkostnader"  @if(@$users_details->shipping_method=="Andel fraktkostnader") selected="selected" @endif>{{ __('users.prcentage_shipping_charges')}}</option>
              </select>
               <span class="invalid-feedback col-md-8"  id="err_shipping_method_ddl"> </span>
              </div>
            </div>

            <div class="form-group " id="shipping_charges_div">
              <label class="col-sm-3 product_table_heading">{{ __('users.shipping_charges_label')}}</label>
              <div class="col-sm-8">
              <input type="text" class="col-md-8 ge_input" name="shipping_charges" id="shipping_charges" placeholder="{{ __('users.shipping_charges_label')}}" value="@if(@$users_details->shipping_charges!=''){{ $users_details->shipping_charges }}@endif" >
              <span class="invalid-feedback col-md-8"  id="err_shipping_charges"></span>
            </div>
            </div>

            <div class="form-group tj-svcheck">
            <label class="col-sm-3 col-xs-6 product_table_heading">
             {{ __('users.free_shipping_label')}}</label>
            <div class="col-sm-8 col-xs-6">  <input type="checkbox" name="free_shipping" id="free_shipping_chk" value="free_shipping" onchange="hideShippingMethod()" @if(@$users_details->free_shipping=='free_shipping') checked="checked" @endif></div>
            
            </div>
            <div class="form-group tj-svcheck">
              <label  class="col-sm-3 col-xs-6 product_table_heading" style="margin-top: 15px;"> {{ __('users.pick_from_store')}} </label>
              <div class="col-sm-8">
                <div class="row">
                  <div class="col-sm-1 col-xs-6 tj-pl0">
                    <div class="row">
                      <div class="col-sm-1"  class="is_pick_from_store">
                       <input type="checkbox" name="is_pick_from_store" id="is_pick_from_store" value="1"  style="margin-top: 15px;" @if(@$users_details->is_pick_from_store=='1') checked="checked" @endif>
                      </div>
                      
                    </div>
                  </div> 
                  <div class="col-sm-7">
                        <input type="text" class="form-control store_pick_address" name="store_pick_address" id="store_pick_address" placeholder="{{ __('users.pick_up_address')}}" value="@if(@$users_details->store_pick_address!=''){{ @$users_details->store_pick_address }}@endif">
                        <span class="invalid-feedback col-md-8"  id="err_pick_up_address"> </span>
                  </div>
                </div>
              </div>      
            </div>
        </div>
      </div>
      </div>
      

  <div class="row  tijara-content tj-personal-action">  
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12 text-center"  style="margin-bottom : 60px;">
      <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>
      <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
    </div>
  </div>

  </form>
            </div>
              </div>
</div>
</div> <!-- /container -->
</div>
</div>


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

@endsection

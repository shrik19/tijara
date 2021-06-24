@extends('Front.layout.template')
@section('middlecontent')
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
   @if($subscribedError)
	    <div class="alert alert-danger">{{$subscribedError}}</div>
	    @endif
  <form id="product-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
            @csrf
  <div class="row">


	<div class="col-md-10">

		  <h2>{{ __('lang.product_form_label')}}</h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-2 text-right" style="margin-top:30px;">
		  <a href="{{route('manageFrontProducts')}}" title="" class=" " ><span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;{{ __('lang.back_to_list_label')}}</span> </a>
      </div>

         @include ('Front.alert_messages')
      <div class="col-md-6">

        <div class="login_box">


            <input type="hidden" name="product_id" value="{{$product_id}}">

            <div class="form-group">
              <label>{{ __('lang.product_title_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input" name="title" id="title" placeholder="{{ __('lang.product_title_label')}} " value="{{old('title')}}" tabindex="1" onblur="convertToSlug(this)">
              <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
            </div>

            <div class="form-group">
              <label>{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
              <input type="text" class="form-control login_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}} " value="{{old('product_slug')}}" tabindex="1" readonly="readonly">
              <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
            </div>

      			<div class="form-group">
              <label>{{ __('lang.product_description_label')}}  <span class="de_col"></span></label>
              <textarea class="form-control login_input" name="description" id="description" placeholder="{{ __('lang.product_description_label')}}" value="" tabindex="2">{{old('description')}}</textarea>
              <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
            </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="login_box">
          <div class="form-group">
              <label>{{ __('lang.category_label')}}</label>
              <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" tabindex="3">
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
              <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{old('meta_title')}}" tabindex="4">
            <span class="invalid-feedback" id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
          </div>

    			<div class="form-group">
            <label>{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{old('meta_description')}}" tabindex="5">
            <span class="invalid-feedback" id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
          </div>

    			<div class="form-group">
            <label>{{ __('lang.meta_keyword_label')}}  <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{old('meta_keyword')}}" tabindex="6">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
          </div>

    		  <div class="form-group">
              <label>{{ __('lang.sort_order_label')}} <span class="de_col"></span></label>
              <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{old('sort_order')}}" tabindex="7">
              <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.status_label')}} </label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="" tabindex="8" >
              <option value="active">{{ __('lang.active_label')}}</option>
              <option value="block">{{ __('lang.block_label')}}</option>
              </select>
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('status')) {{ $errors->first('status') }}@endif</span>
          </div>


        </div>
      </div>

  </div>
  <div class="row">

      <div class="col-md-8">
		  <h2>{{ __('lang.product_variant_title')}}</h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-4 text-right">
		  <a title="{{ __('lang.add_variant_btn')}}" class="btn btn-black btn-sm debg_color login_btn add_new_variant_btn" ><span><i class="fa fa-plus"></i>{{ __('lang.add_variant_btn')}}</span> </a>
			</div>

      <div class="col-md-12">
        <div class="login_box">


          <div class="table-responsive">
            <table class="table table-striped" id="variant_table">
            <thead>
              <tr>
                <th>{{ __('lang.sku_label')}}</th>
                <th>{{ __('lang.weight_label')}}</th>
                <th>{{ __('lang.price_label')}}</th>
                <th>{{ __('lang.qty_label')}}</th>
                <th>{{ __('lang.image_label')}}</th>
                <th>{{ __('lang.action_label')}}</th>
              </tr>
            </thead>
            <tbody class="field_wrapper">
              <!-- <div > -->

              <tr class="variant_tr" id="variant_tr" variant_id="0" >
                <!--<td>


                    <input type="text" class="form-control login_input variant_name" name="variant_name[0]" placeholder="Variant name">
                </td>
               -->
                <td> <input type="text" class="form-control login_input sku variant_field" name="sku[0]" placeholder="{{ __('lang.sku_label')}}" value="{{ old('sku.0')}}">
                <span class="invalid-feedback" id="err_sku" ></span></td>
                <td><input type="text" class="form-control login_input weight number variant_field" name="weight[0]" placeholder="{{ __('lang.weight_label')}}" value="{{ old('weight.0')}}">
                <span class="invalid-feedback" id="err_weight" ></span></td>
                <td><input type="text" class="form-control login_input price number variant_field" name="price[0]" placeholder="{{ __('lang.price_label')}}" value="{{ old('price.0')}}">
                <span class="invalid-feedback" id="err_price" ></span></td>
                <td><input type="text" class="form-control login_input quantity number variant_field" name="quantity[0]" placeholder="{{ __('lang.qty_label')}}" value="{{ old('quantity.0')}}">
                <span class="invalid-feedback" id="err_quantity" ></span>
                </td>
                <td><input type="file" class="form-control login_input image variant_image" name="image[0]"   placeholder="{{ __('lang.image_label')}}" value="">
                <input type="hidden" class="form-control login_input previous_image"  name="previous_image[0]" placeholder="{{ __('lang.action_label')}}">

                <span class="invalid-feedback" id="err_image" ></span></td>
                <td class="add_attribute_group_td" id="add_attribute_group_td_0" style="vertical-align:middle;">

                  <!-- <button type="button" variant_id="0"  class="fa fa_plus add_attribute_group_btn" title="{{ __('lang.add_attr_group_label')}}"  >+</button> -->
                  <span class="variantButtonGroup">
                  <button type="button" variant_id="0" class="btn btn-success btn-xs add_attribute_group_btn" title="{{ __('lang.add_attr_group_label')}}"><i class="fa fa-plus"></i></button>
                </span>
                  <div class="added_attributes" variant_id="0"></div>
                    <!-- attribute Modal -->
                        <div class="modal fade " variant_id="0"  id="attribute-modal">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">{{ __('lang.add_attr_group_label')}}</h4>

                              </div>

                              <div class="modal-body">
                                <table class="table table-striped" id="attribute_table">
                                    <thead>
                                      <tr>
                                        <th>{{ __('lang.attribute_label')}}</th>

                                        <th>{{ __('lang.attribute_value_label')}}</th>
                                        <th>+</th>
                                      </tr>
                                    </thead>
                                    <tbody class="field_wrapper">
                                      <!-- <div > -->
                                      <tr class="attribute_tr" id="attribute_tr_0" attribute_number="0">
                                        <td>

                                            <select class="form-control select_attribute" name="attribute[0][0]">
                                              <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>

                                                @foreach ($attributesToSelect as $attr)
                                                  <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                       <td>

                                            <select selected_attribute_value="" class="form-control select_attribute_value" name="attribute_value[0][0]">
                                              <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}</option>

                                            </select>

                                        </td>
                                        <td   class="plus_attribute_tr">
                                            <button variant_id="0" type="button" id="plus_attribute" class="btn btn-success btn-xs plus_attribute" title="{{ __('lang.add_label')}} {{ __('lang.attribute_label')}}"><i class="fa fa_plus"></i></button>
                                          </td>
                                      </tr>
                        <!-- </div> -->
                                    </tbody>
                                </table>
                              </div>

                              <div class="modal-footer">
                                <button type="button" variant_id="0"   class="btn btn-black debg_color login_btn save_attribute_group">{{ __('lang.save_btn')}}</button>
                                <button type="button" variant_id="0"   class="close_modal">{{ __('lang.reset_btn')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- End attribute modal -->

                </td>
              </tr>

<!-- </div> -->
            </tbody>
            </table>
          <div class="all_saved_attributes" ></div>
          </div>


        </div>
      </div>
      <!-- <div class="login_box">
          <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>

          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
          </div> -->
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12 text-center">
              <button type="submit" name="btnCountryCreate" id="btnAttributeCreate" class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>

              <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
            </div>


  </div>

  </form>
</div> <!-- /container -->
<script>var siteUrl="{{url('/')}}";</script>

<script type="text/javascript">
  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){
    var slug_name= inputText;
    var slug;
     $.ajax({
      url: "{{url('/')}}"+'/manage-products/check-slugname/?slug_name='+slug_name,
      type: 'get',
      async: false,
      data: { },
      success: function(output){
        slug = output;
      }
    });
    return slug;
  }

</script>
@endsection

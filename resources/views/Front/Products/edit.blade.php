@extends('Front.layout.template')
@section('middlecontent')

<div class="containerfluid">
<div class="col-md-6 hor_strip debg_color">
</div>
<div class="col-md-6 hor_strip gray_bg_color">
</div>

</div>
<div class="container">
  <!-- Example row of columns -->
  <div class="row">
      @if($subscribedError)
	    <div class="alert alert-danger">{{$subscribedError}}</div>
	    @endif
      <form id="product-form" action="{{route('frontProductStore')}}" method="post" enctype="multipart/form-data">
          @csrf
    <div class="">
      <div class="col-md-10">
		    
		  <h2>{{ __('lang.product_edit_form_label')}}</h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-1">
		  <a href="{{route('manageFrontProducts')}}" title="" class=" " ><span>{{ __('lang.back_to_list_label')}}</span> </a>
			</div>
      <hr class="heading_line"/>
      @include ('Front.alert_messages')
      <div class="col-md-6">
        <div class="login_box">
          
          <input type="hidden" name="product_id" value="{{$product_id}}">
          <div class="form-group">
          <label>{{ __('lang.product_title_label')}} <span class="de_col">*</span></label>
          <input type="text" class="form-control login_input" name="title" id="title" placeholder="{{ __('lang.product_title_label')}}" value="{{ (old('title')) ?  old('title') : $product->title}}" onblur="convertToSlug(this)">
          <span class="invalid-feedback" id="err_title" >@if($errors->has('title')) {{ $errors->first('title') }}@endif </span>
          </div>

          <div class="form-group">
          <label>{{ __('lang.product_slug_label')}} <span class="de_col">*</span></label>
          <input type="text" class="form-control login_input slug-name" name="product_slug" id="product_slug" placeholder="{{ __('lang.product_slug_label')}}" value="{{ (old('product_slug')) ?  old('product_slug') : $product->product_slug}}" onblur="checkUniqueSlugName()">
          <span class="invalid-feedback slug-name-err" id="err_title" >@if($errors->has('product_slug')) {{ $errors->first('product_slug') }}@endif </span>
          </div>

          <div class="form-group">
          <label>{{ __('lang.product_description_label')}} <span class="de_col"></span></label>
          <textarea class="form-control login_input" name="description" id="description" placeholder="{{ __('lang.product_description_label')}}" value="">{{ (old('description')) ?  old('description') : $product->description}}</textarea>
          <span class="invalid-feedback" id="err_description" >@if($errors->has('description')) {{ $errors->first('description') }}@endif </span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="login_box">
          <div class="form-group">
            <label>{{ __('lang.category_label')}}</label>
            <select class="select2 form-control login_input" name="categories[]" id="categories" multiple placeholder="{{ __('lang.category_label')}}" >
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
            <span class="invalid-feedback" id="err_find_us" >@if($errors->has('categories')) {{ $errors->first('categories') }}@endif</span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.meta_title_label')}} <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_title" id="meta_title" placeholder="{{ __('lang.meta_title_label')}}" value="{{(old('meta_title')) ?  old('meta_title') : $product->meta_title}}">
            <span class="invalid-feedback" id="err_meta_title" >@if($errors->has('meta_title')) {{ $errors->first('meta_title') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.meta_desc_label')}} <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_description" id="meta_description" placeholder="{{ __('lang.meta_desc_label')}}" value="{{(old('meta_description')) ?  old('meta_description') : $product->meta_description}}">
            <span class="invalid-feedback" id="err_meta_description" >@if($errors->has('meta_description')) {{ $errors->first('meta_description') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.meta_keyword_label')}} <span class="de_col"></span></label>
            <input type="text" class="form-control login_input" name="meta_keyword" id="meta_keyword" placeholder="{{ __('lang.meta_keyword_label')}}" value="{{(old('meta_keyword')) ?  old('meta_keyword') : $product->meta_keyword}}">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('meta_keyword')) {{ $errors->first('meta_keyword') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.sort_order_label')}}<span class="de_col"></span></label>
            <input type="tel" class="form-control login_input" name="sort_order" id="sort_order" placeholder="{{ __('lang.sort_order_label')}}" value="{{(old('sort_order')) ?  old('sort_order') : $product->sort_order}}">
            <span class="invalid-feedback" id="err_meta_keyword" >@if($errors->has('sort_order')) {{ $errors->first('sort_order') }}@endif </span>
          </div>

          <div class="form-group">
            <label>{{ __('lang.status_label')}}</label>
            <select class="select2 form-control login_input" name="status" id="status"  placeholder="{{ __('lang.select_status_ddl')}}" >
              <option @if($product->status=='active') selected="selected" @endif value="active">{{ __('lang.active_label')}}</option>
              <option @if($product->status=='block') selected="selected" @endif value="block">{{ __('lang.block_label')}}</option>
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
		  <div class="col-md-1">
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
              @if(count($VariantProductAttributeArr)>0)
              @php $v=0; $variant_key=0; @endphp
              @foreach($VariantProductAttributeArr as $variant_key1=>$variant)
              @php $v++; $variant_key++; @endphp
              <tr class="variant_tr" id="variant_tr" variant_id="{{$variant_key}}" >
               <!-- <td>
               
               
                    <input type="hidden" class="form-control login_input variant_name" name="variant_name[{{$variant_key}}]" value="" placeholder="Variant name">
                </td>
               -->
                <td> <input type="text" class="form-control login_input sku variant_field" value="{{$variant['sku']}}" name="sku[{{$variant_key}}]" placeholder="{{ __('lang.sku_label')}}">
                <span class="invalid-feedback" id="err_sku" ></span></td>
                <td><input type="text" class="form-control login_input weight number variant_field" value="{{$variant['weight']}}" name="weight[{{$variant_key}}]" placeholder="{{ __('lang.weight_label')}}">
                <span class="invalid-feedback" id="err_weight" ></span></td>
                <td><input type="text" class="form-control login_input price number variant_field" value="{{$variant['price']}}" name="price[{{$variant_key}}]" placeholder="{{ __('lang.price_label')}}">
                <span class="invalid-feedback" id="err_price" ></span></td>
                <td><input type="text" class="form-control login_input quantity number variant_field" value="{{$variant['quantity']}}" name="quantity[{{$variant_key}}]" placeholder="{{ __('lang.qty_label')}}">
                <span class="invalid-feedback" id="err_quantity" ></span></td>
                <td><input type="file" class="form-control login_input image variant_image"   name="image[{{$variant_key}}]" placeholder="{{ __('lang.image_label')}}">
                <span class="invalid-feedback" id="err_image" ></span>
                    <input type="hidden" class="form-control login_input previous_image" value="{{$variant['image']}}"  name="previous_image[{{$variant_key}}]" placeholder="{{ __('lang.image_label')}}">
                
                @if($variant['image']!='')
                <img src="{{url('/')}}/uploads/ProductImages/{{$variant['image']}}" width="40" height="40">
                <a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>
                @endif
                </td>
                <td class="add_attribute_group_td" id="add_attribute_group_td_{{$variant_key}}">
                    
                  <button type="button" variant_id="{{$variant_key}}"  class="fa fa_plus add_attribute_group_btn" title="{{ __('lang.add_attr_group_label')}}"  >+</button>
                  <div class="added_attributes" variant_id="{{$variant_key}}">
                      
                      @foreach($variant['attributes'] as $attribute)
                        <div class="added_attributes_each_div">
                            
                            <b>{{$attribute['name']}}</b>:{{$attribute['attribute_values']}}
                            
                            </div>
                      @endforeach
                  </div>
                    <!-- attribute Modal -->
                        <div class="modal fade " variant_id="{{$variant_key}}"  id="attribute-modal">
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
                                        @php $i=0; @endphp
                                        @foreach($variant['attributes'] as $attr_key=>$attribute)
                                        
                                        @php $i++;  @endphp
                                      <!-- <div > -->
                                      
                                      <tr class="attribute_tr" id="attribute_tr_{{$variant_key}}" attribute_number="{{$attr_key}}">
                                        <td>
                                       
                                            <select class="form-control select_attribute preselected_attribute" id="{{$attribute['id']}}" name="attribute[{{$variant_key}}][{{$attr_key}}]">
                                              <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>
                                          
                                                @foreach ($attributesToSelect as $attr)
                                                    @if($attribute['attribute_id']==$attr->id)
                                                  <option selected="selected" value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                                  @else
                                                  <option style="display:none;" value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                                  @endif
                                                @endforeach
                                            </select>
                                           
                                        </td>
                                       <td>
                                       
                                            <select selected_attribute_value="{{$attribute['attribute_value_id']}}" class="{{$attribute['id']}} form-control select_attribute_value" name="attribute_value[{{$variant_key}}][{{$attr_key}}]">
                                              <option value="">{{ __('lang.select_label')}}  {{ __('lang.attribute_value_label')}}</option>
                                                
                                            </select>
                                           
                                        </td>
                                        <td   class="plus_attribute_tr">
                                           @if($i==1)
                                            <button variant_id="{{$variant_key}}" type="button" id="plus_attribute" class="fa fa_plus plus_attribute" title="{{ __('lang.add_label')}} {{ __('lang.attribute_label')}}"  >+</button>
                                          
                                          @endif
                                          @if($i>1) 
                                          <a href="javascript:void(0);"  variant_id="{{$variant_key}}"  class="fas fa-trash remove_attribute_btn" title="{{ __('lang.remove_label')}}  {{ __('lang.attribute_label')}}"  ></a>
                                            @endif
                                          </td>
                                      </tr>
                                      
                                      
                                     
                                    <!-- </div> -->
                                    @endforeach
                                     @if($i==0) 
                                      
                                        <tr class="attribute_tr" id="attribute_tr_0" attribute_number="0">
                                        <td>
                                       
                                            <select class="form-control select_attribute" name="attribute[{{$variant_key}}][0]">
                                              <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_label')}}</option>
                                          
                                                @foreach ($attributesToSelect as $attr)
                                                  <option value="{{ $attr->id }}"  >{{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                           
                                        </td>
                                       <td>
                                       
                                            <select selected_attribute_value="" class="form-control select_attribute_value" name="attribute_value[{{$variant_key}}][0]">
                                              <option value="">{{ __('lang.select_label')}}  {{ __('lang.attribute_value_label')}}</option>
                                                
                                            </select>
                                           
                                        </td>
                                        <td   class="plus_attribute_tr">
                                            <button variant_id="{{$variant_key}}" type="button" id="plus_attribute" class="fa fa_plus plus_attribute" title=" {{ __('lang.add_label')}} {{ __('lang.attribute_label')}}"  >+</button>
                                          </td>
                                      </tr>
                                      @endif
                                    </tbody>
                                </table>
                              </div>
                        
                              <div class="modal-footer">
                                <button type="button" variant_id="{{$variant_key}}"   class="btn btn-black debg_color login_btn save_attribute_group">{{ __('lang.save_btn')}}</button>
                                <button type="button" variant_id="{{$variant_key}}"   class="close_modal">{{ __('lang.cancel_btn')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- End attribute modal -->
                    @if($v>1) <a href="javascript:void(0);"  variant_id="{{$variant_key}}"  class="fas fa-trash remove_variant_btn" title="{{ __('lang.remove_label')}} {{ __('lang.variant')}}"  ></a>
                    @endif
                </td>
              </tr>
              @endforeach
              @else
              <tr class="variant_tr" id="variant_tr" variant_id="0" >
                <!--<td>
               
               
                    <input type="text" class="form-control login_input variant_name" name="variant_name[0]" placeholder="Variant name">
                </td>
               -->
                <td> <input type="text" class="form-control login_input sku variant_field" name="sku[0]" placeholder="{{ __('lang.sku_label')}}">
                <span class="invalid-feedback" id="err_sku" ></span></td>
                <td><input type="text" class="form-control login_input weight number variant_field" name="weight[0]" placeholder="{{ __('lang.weight_label')}}">
                <span class="invalid-feedback" id="err_weight" ></span></td>
                <td><input type="text" class="form-control login_input price number variant_field" name="price[0]" placeholder="{{ __('lang.price_label')}}">
                <span class="invalid-feedback" id="err_price" ></span></td>
                <td><input type="text" class="form-control login_input quantity number variant_field" name="quantity[0]" placeholder="{{ __('lang.qty_label')}}">
                <span class="invalid-feedback" id="err_quantity" ></span>
                </td>
                <td><input type="file" class="form-control login_input image variant_image" name="image[0]" placeholder="{{ __('lang.image_label')}}">
                <input type="hidden" class="form-control login_input previous_image"  name="previous_image[0]" placeholder="{{ __('lang.image_label')}}">
                
                <span class="invalid-feedback" id="err_image" ></span></td>
                <td class="add_attribute_group_td" id="add_attribute_group_td_0">
                    
                  <button type="button" variant_id="0"  class="fa fa_plus add_attribute_group_btn" title="{{ __('lang.add_attr_group_label')}}"  >+</button>
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
                                              <option value="">{{ __('lang.select_label')}} {{ __('lang.attribute_value_label')}}e</option>
                                                
                                            </select>
                                           
                                        </td>
                                        <td   class="plus_attribute_tr">
                                            <button variant_id="0" type="button" id="plus_attribute" class="fa fa_plus plus_attribute" title="{{ __('lang.add_label')}} {{ __('lang.attribute_label')}}"  >+</button>
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
              @endif
<!-- </div> -->
            </tbody>
            </table>
          <div class="all_saved_attributes" ></div>
          </div>
        </div>
      
      <div class="login_box">
          <button type="submit"  class="btn btn-black debg_color login_btn saveproduct" tabindex="9">{{ __('lang.save_btn')}}</button>
           
          <a href="{{$module_url}}" class="btn btn-black gray_color login_btn" tabindex="10"> {{ __('lang.cancel_btn')}}</a>
          </div>
          
          </div>
  </div>
</form>
  </div>

  </div> <!-- /container -->

<script src="{{url('/')}}/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
   var addButton = $('.add_button'); //Add button selector
   var wrapper = $('.field_wrapper'); //Input field wrapper
   var valueHTML = '<tr><td><input type="text" class="form-control login_input" name="attribute_values[]" id="attribute_values" required  value="" ></td><td>a</td><td>b</td><td>c</td><td>d</td><td>e</td><td><button type="button" class="btn btn-danger remove_button" title="Remove Values"  style="float:right;margin-top:-40px;">X</button></td></tr>'; //New input field html 

   var x = 1; //Initial field counter is 1
   //Once add button is clicked
  
   $(wrapper).on('click', '.remove_button', function(e){

      var textbox_value = $(this).prev().prev().attr("id");  
      if(textbox_value){
         
         var split = textbox_value.split("_");
         id = split[2];
         if(id !== null && id !== '') {
        
         $.ajax({
            url: "{{url('/')}}"+'/product-attributes/deleteAttributeValue',
            type: 'POST',
            data: {
               "_token": "{{ csrf_token() }}",
               "id":id
            },
            success: function(output){
               console.log(output);
               $("#attribute_values_" + id).fadeOut('slow');
               $("#remove_button_"+id).fadeOut('slow');


            } 
          });
       }
      }else{
          e.preventDefault();
         $(this).parent('div').remove(); //Remove field html
         x--; //Decrement field counter
      }

    });
  var siteUrl="{{url('/')}}";
</script>
<script type="text/javascript">
  /*function to check unique Slug name
  * @param : Slug name
  */
  function checkUniqueSlugName(inputText){
    if(inputText == undefined){
      var slug_name = $("#product_slug").val()
    }else{
      var slug_name= inputText;
    }
    
     $.ajax({
      url: "{{url('/')}}"+'/manage-products/check-slugname/?slug_name='+slug_name,
      type: 'get',
      data: { },
      success: function(output){
        if(output !=''){
          $('.slug-name-err').text(output);
        }else{
           $('.slug-name-err').text('');
        }
      }
    });
  }

</script>
@endsection
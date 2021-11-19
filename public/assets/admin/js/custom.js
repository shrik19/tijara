/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
/*function convertToSlug by its name*/
function convertToSlug(inputtxt){ 
	  //allLetterNumber(inputtxt);
    var slug = inputtxt.value;
    //replace space with hypen
    slug = inputtxt.value.toLowerCase();
    slug = slug.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    // slug  = slug.trim();
    //function to check slug name is unique or not
    slug = checkUniqueSlugName(slug);
    $('.slug-name').val(slug);
}

/*function to validate letters for category
  function allLetterNumber(inputtxt){ 
    var letters = /^[0-9a-zA-ZäöåÄÖÅ& ]*$/;
    var error=0;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
    }
    else {
      $('.err-letter').text(input_letter_no_err);
      error=1;
    }

    if(error == 1)
    {
      return false;
    }
    else
    {
      $('#product_cat_form').submit();
      return true;
    }
  }

*/

//product category form validation
/*$(".product_cat_btn").click(function(e){
//  e.preventDefault();
  var error = 0
  var cat_name     = $("#name").val();
  var sequence_no  = $("#sequence_no").val();
 
  var letters = /^[0-9a-zA-Z ]+$/;
  if(cat_name == '')
  {
    $(".err-letter").text(category_name_req)
    return true;
    error = 1;
  }
  else if(!cat_name.match(letters)){
    $('.err-letter').text(input_letter_no_err);
    error = 1;
    return true;
  }
  else
  {
    $('.err-letter').text('');
    error = 1;
    return false;
   
  }
alert(error)
  if (errors == 1) {
    alert("here")
        e.preventDefault();
        return false;
    } else {
      alert("out")
        return true;
        }
});  */
/*
$(".add_new_variant_btn").click(function(){
   
    var $trLast = $("div.variant_tr:last");
    var variant_id=$trLast.attr('variant_id');
    variant_id++;
    //
    var $trNew = $trLast.clone();
    $trNew.attr('variant_id',variant_id);
    var divCount = $('.var_img_div').length
    var select_attribute_value = $('.select_attribute_value').val();
    

    $trNew.find(':text').val('');
    $trNew.find('.number').val('');
    $trNew.find('.hidden_images').val('');
    $trNew.find('.variant_image').val('');
    $trNew.find('.variant_id').val('');
    $trNew.find('.variant_name').attr('name','variant_name['+variant_id+']');
    $trNew.find('.variant_id').attr('name','variant_id['+variant_id+']');
    $trNew.find('.sku').attr('name','sku['+variant_id+']');
    $trNew.find('.weight').attr('name','weight['+variant_id+']');
    $trNew.find('.price').attr('name','price['+variant_id+']');
    $trNew.find('.quantity').attr('name','quantity['+variant_id+']');
    $trNew.find('.image').attr('name','image['+variant_id+']');
    $trNew.find('.hidden_images').attr('name','hidden_images['+variant_id+']');
    $trNew.find('.add_attribute_group_btn').attr('variant_id',variant_id);
    $trNew.find('.added_attributes').attr('variant_id',variant_id);
    $trNew.find('.variant_image').attr('variant_id',variant_id);
    $trNew.find('.added_attributes').html('');
    $trNew.find('.selected_images').html('');
    $trNew.find('.select_attribute').attr('variant_id',variant_id);
    $trNew.find('.clone_tr').remove();
    $trNew.find('.select_attribute').val('');
    $trNew.find('.select_attribute_value').html('<option value="">'+select_attribute_value+'</option>');
    $trNew.find('tr.attribute_tr:gt(0)').remove();
    $trNew.find('img').remove();
    $trNew.find('.remove_image').remove();
    if($trNew.find('.add_attribute_group_td').find('.remove_variant_btn').length<=0)
    $trNew.find('.remove_variant_div').html("<a href='javascript:void(0);' variant_id='"+variant_id+"' class='btn btn-danger btn-xs remove_variant_btn' title='Remove Variant'><i class='fas fa-trash'></i></a>");
    $trNew.find('.variant_attribute_id').attr('value','').attr('name','variant_attribute_id['+variant_id+'][0]');
    $trNew.find('.select_attribute').removeClass('preselected_attribute').attr('id','0').attr('name','attribute['+variant_id+'][0]');
    $trNew.find('.select_attribute_value').removeClass('preselected_attribute').attr('name','attribute_value['+variant_id+'][0]');

    $trLast.after($trNew);


});
$('#variant_table').on('change', '.select_attribute', function () {

      var  select_attribute =$(this).val();
      var  elm              = $(this);
      var val = $(this).val();
        var thisSelect  = $(this);
        $( ".select_attribute" ).each(function() {
          $(this).removeClass('active');
        });

        thisSelect.addClass('active');

        var error = 0;
      
      if(error == 0){
        $.ajax({
            headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
               url: siteUrl+'/product/getattributevaluebyattributeid',
               data: {attribute_id: select_attribute},
               type: 'get',
               success: function(output) {
                console.log(output);
          console.log(elm.parent('div').find('.select_attribute_value').html());
                            elm.parent('div').find('.select_attribute_value').html(output);
                        }
        });
      }
    });

$( ".preselected_attribute" ).each(function() {

        select_attribute =$(this).val();
        var id           = $(this).attr('id');
        elm              =$(this);
        $.ajax({
            url: siteUrl+'/product/getattributevaluebyattributeid',
             data: {attribute_id: select_attribute},
             type: 'get',
             success: function(output) {

                          $('.select_attribute_value.'+id).html(output);

                          if($('.select_attribute_value.'+id).hasClass('buyer-product')) {
                            $('.select_attribute_value.'+id).attr('selected_attribute_value',$('.select_attribute_value.'+id).find('option:eq(1)').val());
                            //$('.select_attribute_value.'+id).val($('.select_attribute_value.'+id).find('option:eq(1)').val());
                          }
                          $('.select_attribute_value.'+id).val($('.select_attribute_value.'+id).attr('selected_attribute_value'));
                           //alert( $('.select_attribute_value.'+id).attr('selected_attribute_value'));

             }
            });

    //
});
*/
/*$( document ).ready(function() {
    hideShippingMethod();
});*/
/*seller profile free shipping checkbox selected then hide payment method option*/
/*function hideShippingMethod(){
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

$('#shipping_charges').on('input',function(e){
  if($("#shipping_method_ddl").val() == ''){
    showErrorMessage("Plase Select shipping Method");
  }
});

const regex = /[^\d.]|\.(?=.*\.)/g;
const subst='';

$('#shipping_charges').keyup(function(){
  const str=this.value;
  const result = str.replace(regex, subst);
  this.value=result;
});
*/
/*$(".saveproduct").click(function(e){
  e.preventDefault();

  let title               = $("#title").val();
  let sort_order          = $("#sort_order").val();
  let description         = $(".product_description").val();
  let category            = $("#categories").val();
  let variant_image       = $(".variant_image").val();
  let hidden_images       = $(".hidden_images").val();
  let user_id               = $("#user_id").val();
  let error               = 0;


  if(title == '')
  {
    $("#err_title").html(required_field_error).show();
    $("#err_title").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_title").html('').show();

  }


  if(user_id == '')
  {
    $("#err_seller_name").html(required_field_error).show();
    $("#err_seller_name").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_seller_name").html('').show();

  }

  if(description == '')
  {
    $("#err_description").html(required_field_error).show();
    $("#err_description").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_description").html('').show();

  }

  if(category == null)
  {
    $("#err_category").html(required_field_error).show();
    $("#err_category").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_category").html('').show();

  }


  if(hidden_images == '')
  {
    $("#err_variant_hid_image").html(wait_while_upload).show();
    $("#err_variant_hid_image").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_variant_hid_image").html('').show();

  }


  $( ".variant_field:visible" ).each(function() {

      if($(this).val()=='') {
          $(this).next('.variant_field_err').html(required_field_error);
          error = 1;
      }
      else
      $(this).next('.variant_field_err').html('');
  });
  $( ".add_attribute_group_td" ).each(function() {
    if($(this).find('.added_attributes_each_div').length<=0) {
        $(this).find('.added_attributes').html('<span style="color:red;">'+required_field_error+'</span>');
        error = 1;
    }
});
  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#product-form').submit();
    return true;
  }

});

 $('#variant_table').on('click', '.remove_variant_btn', function () {
     if (!confirm('Are you sure you want to remove Variant?')) return false;
     $(this).parent('div').parent('div').remove();
 });

 $('#variant_table').on('change', '.variant_image', function () {
        var fileUpload  = $(this)[0];
        var elm         =   $(this);
        var variant_id  = $(this).attr('variant_id');
        
        var validExtensions = ["jpg","jpeg","gif","png"];
        var file = $(this).val().split('.').pop();
        if (validExtensions.indexOf(file) == -1) {
                showErrorMessage(invalid_files_err);
                $(this).val('');
                return false;
        }

        var formData = new FormData();
      
        if (fileUpload.files.length > 0) {

               formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

                $(".loader").show();
                $.ajax({
                    headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                      url: siteUrl+'/product/upload-variant-image',
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,

                      success: function(data) {
                        $(".loader").hide();
                         elm.prev('div.selected_images').append('<div><input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images['+variant_id+'][]">'+
                          '<img src="'+siteUrl+'/uploads/ProductImages/'+data+'" width="78" height="80">'+
                                            '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div>');          
                      }

                });
        }

});
*/
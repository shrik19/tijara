
//$.noConflict(); 

/*function convertToSlug by its name*/
function convertToSlug(inputtxt){
   // allLetterNumber(inputtxt);
    var slug = inputtxt.value;
    //replace space with hypen
    slug = inputtxt.value.toLowerCase();
    //replace special character with -
    slug = slug.replace(/[`~!@#$%^*&()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        //slug = slug.replace(/[^\w\s]/gi, '')
    //function to check slug name is unique or not
    slug = checkUniqueSlugName(slug);
    $('.slug-name').val(slug);

}

function checkUniqueSlugName(inputText){
  var slug_name= inputText;
  var slug;
   $.ajax({
    url: siteUrl+'/manage-products/check-slugname/?slug_name='+slug_name,
    type: 'get',
    async: false,
    data: { },
    success: function(output){
      slug = output;
    }
  });
  return slug;
}




var acc = document.getElementsByClassName("accordion-faq");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active-faq");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}


  /*search by seller name */
 $('#search_string').keyup(function(){ 
    if( this.value.length > 1 ) {
      var query = $(this).val();

        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url: siteUrl+'/get-seller-list',
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
            if(data != '' ){
              $('#sellerListFilter').fadeIn();  
              $('#sellerListFilter').html('<ul class="dropdown-menu" style="display:block; position:relative;width:100%">'+data+'</ul>');
            }
           
          }
         });
        }
    }
        
  });

  $(document).on('click', '.seller_autocomplete_search', function(){  
    $('#search_string').val($(this).text());  
    $('#sellerListFilter').fadeOut();  
  });  

  $(window).click(function() {
    jQuery('#sellerListFilter').fadeOut(); 
  });

/*js code for policy tabs*/
function openPage(pageName,elmnt) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
    // tablinks[i].style.backgroundColor = "";
      tablinks[i].classList.remove("tablink-active");
    }
    document.getElementById(pageName).style.display = "block";
    elmnt.classList.add("tablink-active");
}
var defaultOpen = document.getElementById('defaultOpen');
if (defaultOpen) {
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
  //   tablink-active
       var element = document.getElementById("defaultOpen");
   element.classList.add("tablink-active");
}
/*onclick of product div redirect to details page*/

/*$(".product_data").click(function(){
  var attr_val = $(this).attr('product_link');
  if(attr_val !=''){
    window.location.href = attr_val; 
  }
});*/
$(".add_new_variant_btn").click(function(){
  $(".remove_variant_div").addClass("col-md-12");
    $trLast = $("div.variant_tr:last");
    variant_id=$trLast.attr('variant_id');
    variant_id++;
    //
    $trNew = $trLast.clone();
    $trNew.attr('variant_id',variant_id);
    var divCount = $('.var_img_div').length
    $('.select_attribute').each(function(){
      var attrName = $(this).val();
      if(attrName!=''){
       $trNew.find(".select_attribute option").attr('disabled', true);
      }
   
    });
  
   
    $('.select_attribute').each(function(){
      var attrName = $(this).val();
      $trNew.find(".select_attribute option[value='"+ attrName + "']").attr('disabled', false);
    });
  

  
    /*code to remove variant images from second variant
    while (divCount >0) 
    {
                $trNew.find('.var_img_div').not('variant_tr:first').remove();
                divCount--;
    }*/

    $trNew.find(':text').val('');
    $trNew.find('.number').val('');
    $trNew.find('.hidden_images').val('');
    $trNew.find('.variant_image').val('');
    $trNew.find('.variant_id').val('');
    $trNew.find('.variant_name').attr('name','variant_name['+variant_id+']');
    $trNew.find('.variant_id').attr('name','variant_id['+variant_id+']');
    $trNew.find('.sku').attr('name','sku['+variant_id+']');
   // $trNew.find('.weight').attr('name','weight['+variant_id+']');
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
     $trNew.find('.select_attribute').attr('name','attribute['+variant_id+'][]');
    $trNew.find('.clone_tr').remove();
    $trNew.find('.select_attribute').val('');
    $trNew.find('.variant_attribute_id').attr('name','variant_attribute_id['+variant_id+'][]').val('');
    //$trNew.find('.select_attribute_value').html('<option value="">'+select_attribute_value+'</option>');
    $trNew.find('tr.attribute_tr:gt(0)').remove();
    $trNew.find('img').remove();
    $trNew.find('.remove_image').remove();
    if($trNew.find('.add_attribute_group_td').find('.remove_variant_btn').length<=0)
    $trNew.find('.remove_variant_div').html("<a href='javascript:void(0);' variant_id='"+variant_id+"' class='btn btn-danger btn-xs remove_variant_btn' title='Remove Variant'><i class='fas fa-trash'></i></a>");

  $trNew.find('.select_attribute_value').each(function() { 
         //$(this).attr('name','attribute_value['+variant_id+']['+$(this).attr('attribute_id')+']');
         $(this).attr('name','attribute_value['+variant_id+'][]');
     });
	$trNew.find('span.invalid-feedback').each(function() { 
         $(this).html('');
     });
$trNew.find('.variant_image').addClass('variant_field');
    //$trNew.find('.variant_attribute_id').attr('name','variant_attribute_id['+variant_id+'][0]');
    //$trNew.find('.select_attribute').removeClass('preselected_attribute').attr('id','0').attr('name','attribute['+variant_id+'][0]');
    //$trNew.find('.select_attribute_value').removeClass('preselected_attribute').attr('name','attribute_value['+variant_id+'][0]');

    $trLast.after($trNew);


});
$( ".number" ).each(function() {
    $(this).keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));
    });
});

$('#variant_table').on('change', '.variant_image', function () {
        var fileUpload  = $(this)[0];
        var elm         =   $(this);
        var variant_id  = $(this).attr('variant_id');
        
        var validExtensions = ["jpg","jpeg","gif","png"];
        var minwidth  = 600;
        var minheight = 600;
       

        var file = $(this).val().split('.').pop();
        if (validExtensions.indexOf(file) == -1) {
                showErrorMessage(invalid_files_err);
                $(this).val('');
                return false;  
        }
         var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
                    if (height < minwidth || width < minheight) {
 
                       //show width and height to user
                        showErrorMessage(image_upload_height_width);
                        $(this).val('');
                        return false;

                    }
                 
                      var formData = new FormData();
      
                      if (fileUpload.files.length > 0) {

                             formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

                              $(".loader").show();
                              $.ajax({
                                  headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                                    url: siteUrl+'/manage-products/upload-variant-image',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,

                                    success: function(data) {
                                      $(".loader").hide();
                                    /* elm.parent('div').next().next('div.selected_images').append('<div><input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images['+variant_id+'][]">'+
                                        '<img src="'+siteUrl+'/uploads/ProductImages/'+data+'" width="50" height="50">'+
                                                          '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div>');*/

                                    elm.prev('div.selected_images').append('<div><input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images['+variant_id+'][]">'+
                                        '<img src="'+siteUrl+'/uploads/ProductImages/resized/'+data+'" width="78" height="80">'+
                                                          '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div>');          
                                    }

                              });
                      }
                };
 
            }

      

});


$('#buyer_profile_image').on("change", function(){ 

        var fileUpload  = $(this)[0];
        var elm         =   $(this);
        //var variant_id  = $(this).attr('variant_id');
         
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
                      url: siteUrl+'/upload-profile-image',
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,

                      success: function(data) {
                        $(".loader").hide();
                  
                        $(".existing-images").replaceWith('<div class="col-md-4 existing-images"><div><input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images">'+
                          '<img src="'+siteUrl+'/uploads/Buyer/'+data+'" class="buyer_profile_update_img">'+
                                            '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div></div>');
                      }

                });
        }

});


$('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});


 $('#variant_table').on('click', '.remove_variant_btn', function () {
     //if (!confirm('Are you sure you want to remove Variant?')) return false;
     var redirect_url='';
     var rem =$(this);
      $.alert({
      title: "Klart!",
      content: del_variant_confirm_box,
      type: '#03989e',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-check-circle",
      buttons: {
        ok: function () {
          if(redirect_url == '')
          {
            rem.parent('div').parent('div').remove();
          }
        },
      }
    });
      //showSuccessMessage(del_variant_confirm_box);
    // $(this).parent('div').parent('div').remove();
 });
//$( ".select_attribute" ).each(function() {
    $('#variant_table').on('change', '.select_attribute', function () {
        select_attribute =$(this).val();
        elm              =$(this);
        var val = $(this).val();

        var thisSelect  = $(this);
        $( ".select_attribute" ).each(function() {
          $(this).removeClass('active');
        });

        thisSelect.addClass('active');

        var error = 0;
        /*if(val!='') {
          var variant_id  =   $(this).attr('variant_id');
          $('div[variant_id="'+variant_id+'"]').find( "select.select_attribute:not(.active)" ).each(function() {

            if($(this).val()==val)
            {
              alert('attribute selected');
              error=1;
              thisSelect.val('').trigger('change');
              thisSelect.parent('td').next('td').find('select').html('<option value="">'+select_attribute_value+'</option>');
              thisSelect.val('');
            }
          });
        }
*/
      if(error == 0){
        $.ajax({
              url: siteUrl+'/product-attributes/getattributevaluebyattributeid',
               data: {attribute_id: select_attribute},
               type: 'get',
               success: function(output) {
               // alert("dchj"+variant_id)
               /* var variant_id =$('.select_attribute_value').attr('variant_id');
                 alert("dchj"+variant_id)
                 attribute_id*/
                          /*new code for attribute 21 april*/
                         /*  var variant_id =$('.select_attribute').attr('variant_id');
                           alert(variant_id)
                          elm.parent('div').find('.select_attribute_value').attr('attribute_id',select_attribute)
                         
                              elm.parent('div').find('.select_attribute_value').attr('name','attribute_value['+variant_id+']['+select_attribute+']');
                         */
                                     /*end*/
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
            url: siteUrl+'/product-attributes/getattributevaluebyattributeid',
             data: {attribute_id: select_attribute},
             type: 'get',
             success: function(output) {
                if(id !=''){
                          $('.select_attribute_value.'+id).html(output);

                          if($('.select_attribute_value.'+id).hasClass('buyer-product')) {
                            $('.select_attribute_value.'+id).attr('selected_attribute_value',$('.select_attribute_value.'+id).find('option:eq(1)').val());
                            //$('.select_attribute_value.'+id).val($('.select_attribute_value.'+id).find('option:eq(1)').val());
                          }
                          $('.select_attribute_value.'+id).val($('.select_attribute_value.'+id).attr('selected_attribute_value'));
                           //alert( $('.select_attribute_value.'+id).attr('selected_attribute_value'));

                }
             }
            });

    //
});



$(".expandCollapseSubcategory").click(function(){
  $(this).toggleClass("activemaincategory");
  id=$(this).attr('href');
  $(id).toggleClass("activesubcategories");
});
$(".expandCollapseServiceSubcategory").click(function(){
  $(this).toggleClass("activeservicemaincategory");
  id=$(this).attr('href');
  $(id).toggleClass("activeservicesubcategories");
});
//$('.activemaincategory').trigger('click');
$(".frontloginbtn").click(function(e){
  e.preventDefault();
  let email     = $("#email-address").val();
  let password  = $("#password").val();

  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error = 0;

  if(email == '')
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_email").parent().removeClass('jt-error');
    $("#err_email").html('').hide();
  }

  if(password == '')
  {
    $("#err_password").html(fill_in_password_err).show();
    $("#err_password").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_password").parent().removeClass('jt-error');
    $("#err_password").html('').hide();
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#sign-in-form').submit();
    return true;
  }
});

$('input#sort_order').keyup(function(e)
                                {
  if (/\D/g.test(this.value))
  {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
  }
});
$(".saveproduct").click(function(e){
  e.preventDefault();

  let title               = $("#title").val();
  let sort_order          = $("#sort_order").val();
  let description         = $(".product_description").val();
  let category            = $("#categories").val();
  let variant_image       = $(".variant_image").val();
  let hidden_images       = $(".hidden_images").val();
  let pick_up_address     = $("#store_pick_address").val();
  let shipping_method_ddl = $("#shipping_method_ddl").val();
  var shipping_charges    = $("#shipping_charges").val();
  let error               = 0;
	$('span.invalid-feedback').html('');
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

/*  if(variant_image == '')
  {
    $("#err_variant_image").html(required_field_error).show();
    $("#err_variant_image").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_variant_image").html('').show();

  }*/

  if(hidden_images == '')
  {
    $("#err_variant_hid_image").html(wait_while_upload).show();
    $("#err_variant_hid_image").parent().addClass('jt-error');
    error = 1;
  }/*else if(hidden_images =='' && variant_image == ''){

     $("#err_variant_image").html(required_field_error).show();
    $("#err_variant_image").parent().addClass('jt-error');
    error = 1;
  }*/
  else
  {
    $("#err_variant_hid_image").html('').show();
  }


 /*  $( ".variant_image:visible" ).each(function() {
 
       alert($(this).parent('div').hasClass('.hidden_images'));
    
        if ( $(".variant_image").hasClass("hidden_images") == false){

          $(this).next('#err_variant_image').html(required_field_error);
          error = 1;
      }
      else
      $(this).next('#err_variant_image').html('');
  });
*/

  

  $( ".variant_field" ).each(function() {

      if($(this).val()=='') {
          $(this).next('.invalid-feedback').html(required_field_error);
          error = 1;
      }
      
  });
  $( ".add_attribute_group_td" ).each(function() {
  
    if($(this).find('.added_attributes_each_div').length<=0) {
        $(this).find('.added_attributes').html('<span style="color:red;">'+required_field_error+'</span>');
        error = 1;
    }
  });
  if($("#free_shipping_chk").is(':checked')){
    //error = 0;
  }
  else{
	  
	
   if(shipping_method_ddl == '')
    {
      $("#err_shipping_method_ddl").html(required_field_error).show();
      $("#err_shipping_method_ddl").parent().addClass('jt-error');
      error = 1;
    }
    else
    {
      $("#err_shipping_method_ddl").html('').show();
    }
  }
  
  if( shipping_method_ddl !=''){
    if(shipping_charges==''){
      $("#err_shipping_charges").html(required_field_error).show();
      $("#err_shipping_charges").parent().addClass('jt-error');
      error = 1;
    }
    else
    {
      $("#err_shipping_charges").html('').show();

    }
  }

if($("#is_pick_from_store").is(':checked')){

    if(pick_up_address==''){
      $("#err_pick_up_address").html(required_field_error).show();
      $("#err_pick_up_address").parent().addClass('jt-error');
      error = 1;
    } else {
      $("#err_pick_up_address").html('').show();
    }
  }else if(pick_up_address!='' && !$("#is_pick_from_store").is(':checked')){
    $("#err_pick_up_address").html(required_field_error).show();
      $("#err_pick_up_address").parent().addClass('jt-error');
      error = 1;
  }else {
      $("#err_pick_up_address").html('').show();
  }
  

  if(error == 1)
  {
    showErrorMessage(required_field_error);
    return false;
  }
  else
  {
    $('#product-form').submit();
    return true;
  }

});

$('#store_pick_address').on('input',function(e){
  if( !$("#is_pick_from_store").is(':checked')){
    showErrorMessage(check_checkbox_first_err);
  }
});

$(".saveBuyerProduct").click(function(e){

  e.preventDefault();
  let title               = $("#title").val();
  let sort_order          = $("#sort_order").val();
  let seller_name         = $("#user_name").val();
  let seller_email        = $("#user_email").val();
  let seller_phone        = $("#user_phone_no").val();
  let seller_county       = $("#country").val();
  let seller_municipality = $("#location").val();
  let price               = $("#price").val();
  let variant_image               = $(".variant_image").val();
  let hidden_images       = $(".hidden_images").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error               = 0;


  if($("#chk_privacy_policy").is(':checked')){
       error = 0;
    } else {
        showErrorMessage(please_check_privacy_policy);
        error = 1;

    }

  if(title.trim() == '')
  {
    $("#err_title").html(required_field_error).show();
    $("#err_title").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_title").html('').show();

  }

  if(seller_name.trim() == '')
  {
    $("#err_seller_name").html(required_field_error).show();
    $("#err_seller_name").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_seller_name").html('').show();

  }
 
  if(seller_email.trim() == '')
  {
    $("#err_seller_email").html(fill_in_email_err).show();
    $("#err_seller_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(seller_email))
  {
    $("#err_seller_email").html(fill_in_email_err).show();
    $("#err_seller_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_seller_email").parent().removeClass('jt-error');
    $("#err_seller_email").html('').hide();
  }


  if(seller_phone.trim() == '')
  {
    $("#err_user_phone_no").html(required_field_error).show();
    $("#err_user_phone_no").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_user_phone_no").html('').show();

  }

   if(seller_county.trim() == '')
  {
    $("#err_seller_county").html(required_field_error).show();
    $("#err_seller_county").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
   
    $("#err_seller_county").html('').show();

  }

   if(seller_municipality.trim() == '')
  {
    $("#err_location").html(required_field_error).show();
    $("#err_location").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_location").html('').show();

  }

  if(price.trim() == '')
  {
    $("#err_price").html(required_field_error).show();
    $("#err_price").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_price").html('').show();

  }

  /* if(variant_image == '')
  {
    $("#err_variant_image").html(required_field_error).show();
    $("#err_variant_image").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_variant_image").html('').show();

  }*/

  if(hidden_images == '' || typeof hidden_images=='undefined')
  {

    $("#err_variant_hid_image").html(wait_while_upload).show();
    $("#err_variant_hid_image").parent().addClass('jt-error');
    error = 1;
  }else if(hidden_images =='' && variant_image == ''){
   
    $("#err_variant_image").html(required_field_error).show();
    $("#err_variant_image").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_variant_hid_image").html('').show();
  }

 

  $( ".variant_field:visible" ).each(function() {
      if($(this).val()=='') {
          $(this).next('.invalid-feedback').html(required_field_error);
          error = 1;
      }
      else
      $(this).next('.invalid-feedback').html('');
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


/* function to validate reset password from*/
$(".ResetPasswordBtn").click(function(e){
  e.preventDefault();

  let password  = $("#password").val();
  let cpassword = $("#password_confirmation").val();
  let error = 0;

  if(password == '')
  {
    $("#err_password").html(fill_in_password_err).show();
    $("#err_password").parent().addClass('jt-error');
    error = 1;
  }
  else if((password).length<6)
  {
    $("#err_password").html(password_min_6_char).show();
    $("#err_password").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_password").parent().removeClass('jt-error');
    $("#err_password").html('').hide();
  }
  if(password!=cpassword) {
    $("#err_cpassword").html(password_not_matched).show();
      $("#err_cpassword").parent().addClass('jt-error');
     error = 1;
  }
  else
  {
    $("#err_cpassword").html('');
  }
  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#resetPasswordForm').submit();
    return true;
  }
});

/* function to validate buyer profile update*/
$(".update-buyer-profile").click(function(e){
  e.preventDefault();
  let fname   = $("#fname").val();
  let lname   = $("#lname").val();

  let email     = $("#email").val();

  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error = 0;
  let letters = /^[A-Za-z ]+$/;

/*
  if(fname == '')
  {
    $("#err_fname").html(fill_in_first_name_err).show();
    $("#err_fname").parent().addClass('jt-error');
    error = 1;
  }
  else if(!fname.match(letters))
  {
    $("#err_fname").html(input_alphabet_err).show();
    $("#err_fname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_fname").html('').show();

  }

  if(lname == '')
  {
    $("#err_lname").html(fill_in_last_name_err).show();
    $("#err_lname").parent().addClass('jt-error');
    error = 1;
  }
  else if(!lname.match(letters))
  {
    $("#err_lname").html(input_alphabet_err).show();
    $("#err_lname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_lname").html('');
  }
  */
  if(email == '')
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_email").html(fill_in_valid_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_email").parent().removeClass('jt-error');
    $("#err_email").html('').hide();
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#buyer-update-form').submit();
    return true;
  }
});


/* function to validate seller profile update*/
$(".seller-profile-update").click(function(e){

  e.preventDefault();
  let fname   = $("#fname").val();
  let lname   = $("#lname").val();
  let city   = $("#city").val();
  let country   = $("#country").val();
  let email     = $("#email").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let pick_up_address     = $("#store_pick_address").val();
  let shipping_method_ddl = $("#shipping_method_ddl").val();
  var shipping_charges    = $("#shipping_charges").val();
  let error = 0;

  if(fname == '')
  {
    $("#err_fname").html(fill_in_first_name_err).show();
    $("#err_fname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_fname").html('').show();

  }
  if(lname == '')
  {
    $("#err_lname").html(fill_in_last_name_err).show();
    $("#err_lname").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_lname").html('');
  }
  if(email == '')
  {
    $("#err_email").html(fill_in_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else if(!email_pattern.test(email))
  {
    $("#err_email").html(fill_in_valid_email_err).show();
    $("#err_email").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_email").parent().removeClass('jt-error');
    $("#err_email").html('').hide();
  }

    if(city == '')
  {
    $("#err_city").html(required_field_error).show();
    $("#err_city").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_city").html('');
  }

    if(country == '')
  {
    $("#err_country").html(required_field_error).show();
    $("#err_country").parent().addClass('jt-error');
    error = 1;
  }
  else
  {
    $("#err_country").html('');
  }

  if($("#is_pick_from_store").is(':checked')){

    if(pick_up_address==''){
      $("#err_pick_up_address").html(required_field_error).show();
      $("#err_pick_up_address").parent().addClass('jt-error');
      error = 1;
    } else {
      $("#err_pick_up_address").html('').show();
    }
  }
//validation to check atlease on shipping methi=od
 /* if($("#free_shipping_chk").is(':checked')){
 
  }
  else{
    
  if($("#is_pick_from_store").is(':checked')){

    if(pick_up_address==''){
      alert("sdh");
      $("#err_pick_up_address").html(required_field_error).show();
      $("#err_pick_up_address").parent().addClass('jt-error');
      error = 1;
    } else {
      $("#err_pick_up_address").html('').show();
    }
  }
    else if(shipping_method_ddl == '')
    {
      showErrorMessage(select_one_shipping_option);
      error = 1;
    }
    else
    {
      $("#err_shipping_method_ddl").html('').show();
    }
  }*/
  
  if( shipping_method_ddl !=''){
    if(shipping_charges==''){
      $("#err_shipping_charges").html(required_field_error).show();
      $("#err_shipping_charges").parent().addClass('jt-error');
      error = 1;
    }
    else
    {
      $("#err_shipping_charges").html('').show();

    }
  }


  if(fname=='' || lname=='' || email == '' || city == '' || country == ''){
    showErrorMessage(enter_all_fields_err);
    error = 1;
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
    $('#seller-profile-form').submit();
    return true;
  }
});

// mask phone numer
//$('#phone_number').mask('00 000 00000');



$(".add-image").click(function(){

  var existing_images = $(".existing-images").length;
  var cloned_images = $(".cloned:visible").length;

  if((existing_images + cloned_images) >= 5) {
    $(".cloned-danger").html(max_files_restriction_seller);
    return false;
  }
  var html = $(".clone").html();
  $(".increment").after(html);
});

$("body").on("click",".remove-image",function(){
$(this).parents(".form-group").remove();
$(".cloned-danger").html('')
});

/*function ConfirmDeleteFunction(url, id = false) {
    var message = alert_delete_record_message;

  swal({
      title: are_you_sure_message,
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: yes_delete_it_message,
        cancelButtonText: no_cancel_message,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        location.href=url;
        return true;
      } else {
        return false;
      }
    });
}
*/

function ConfirmDeleteFunction(url, id = false)
{  
  //$.noConflict(); 
  $.confirm({
      title: js_confirm_msg,
      content: are_you_sure_message,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
            $(".loader").show();
            $.ajax({
              url:url,
              headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
              },
              type: 'get',
              data : {},
              success:function(data)
              {
                $(".loader").hide();
                // var responseObj = $.parseJSON(data);
                // alert(responseObj);return
                /*if(responseObj.status == 'success')
                {
                    showSuccessMessage(responseObj.msg,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'reload');
                }*/
                 if(data.success)
                {
                    showSuccessMessageReview(data.success,'reload');
                }
                else
                {
                    showErrorMessage(data.error,'/front-login/buyer');
                }
        
              }
            });
          },
          Avbryt: function () {
            
          },
      }
  });

}

function ConfirmDeleteFunction1(url, id = false) {
    var message = alert_delete_record_message;

  swal({
      title: are_you_sure_message,
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: yes_delete_it_message,
        cancelButtonText: no_cancel_message,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        location.href=url;
        return true;
      } else {
        return false;
      }
    });
}

function ConfirmCloseStoreFunction(url, id = false) {
    var message = close_store_confirm_msg;

  $.confirm({
      title: js_confirm_msg,
      content: close_store_confirm_msg,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
            $(".loader").show();
            $.ajax({
              url:url,
              headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
              },
              type: 'get',
              data : {},
              success:function(data)
              {
            
                $(".loader").hide();
                // var responseObj = $.parseJSON(data);
                // alert(responseObj);return
                /*if(responseObj.status == 'success')
                {
                    showSuccessMessage(responseObj.msg,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'reload');
                }*/
                 if(data.success)
                {
                    showSuccessMessage(data.success,'reload');
                }
                else
                {
                    showErrorMessage(data.error,'/front-login/seller');
                }
        
              }
            });
          },
          Avbryt: function () {
            
          },
      }
  });
}
function showProductsServices()
{
  var a = $.confirm({
      title: '',
      content: '<h1>'+select_what_to_search+'</h1>',
      type: 'white',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-check',
      buttons: {
          Produkter: function () {
            $("#product_service_search_type").val('products');
            $('#product_service_search_from').attr('action',siteUrl+"/products");
            $('#product_service_search_from').attr('onSubmit','');
            $('#product_service_search_from').submit();
            
          },
          Tjänster: function () {
          //  alert("service");return
           $("#product_service_search_type").val('services');
           $('#product_service_search_from').attr('action',siteUrl+"/services");
           $('#product_service_search_from').attr('onSubmit','');
           $('#product_service_search_from').submit();
          },
      }
  });
  
  a.open();
  $('.jconfirm').addClass('productServicePopup');

}

$(".search_icon_btn").click(function(){
  var serach_val = $("#search_string").val();
  if(serach_val==''){
    $("#product_service_search_type").val('products');
    $('#product_service_search_from').attr('action',siteUrl+"/products");
    $('#product_service_search_from').attr('onSubmit','');
    $('#product_service_search_from').submit();
  }else{
    showProductsServices();
  }
  
});

if($('.product_listings').length>0) {
  var page = 1;
//function showSuccessMessage(strContent,redirect_url = '')
  //showProductsServices("sjhdh",'/front-login/buyer');
  get_product_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
  $(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), 
  $(".current_search_string").text(),'',$(".current_role_id").text());
  
  $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      get_product_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
      $(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), 
      $(".current_search_string").text(),'',$(".current_role_id").text());
   });
}



function get_product_listing(page,category_slug='',subcategory_slug='',
  sellers ='',price='',city='', search_string='',search_seller_product='',current_role_id='') {

 var city = $('#city_name').val();

  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  $.ajax({
    url:siteUrl+"/get_product_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': page, 'category_slug' : category_slug, 
    'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 
    'price_filter' : price,'city_filter' : city, 'sort_order' : sort_by_order, 
    'sort_by' : sort_by, 'search_string' : search_string,search_seller_product:search_seller_product ,
    'role_id':current_role_id,'path':window.location.pathname},
    success:function(data)
    {
      var responseObj = $.parseJSON(data);
      $('.product_listings').html(responseObj.products);
      $('.seller_list_content').html(responseObj.sellers);

      if($("#search_string").length && search_string != '')
      {
        $("#search_string").val(search_string);
      }

      
      $(".product_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            console.log(is_login);
            if(is_login == 1)
            {
            // Get element id by data-id attribute
            var el = this;
            var el_id = el.$elem.data('id');
            
            // rating was selected by a user
            if (typeof(event) !== 'undefined') {
              
              var split_id = el_id.split("_");
              var product_id = split_id[1]; // postid

              $.confirm({
                 title: txt_your_review,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Skicka', //submit
                         btnClass: 'btn-blue',
                         action: function () {
                             var comments = this.$content.find('.name').val();
                             if(!comments){
                               showErrorMessage(txt_comments_err);
                               return false;
                             }
                             $(".loader").show();
                             $.ajax({
                             url:siteUrl+"/add-review",
                             headers: {
                               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                             },
                             type: 'post',
                             data : {'rating': value, 'product_id' : product_id, 'comments' : comments},
                             success:function(data)
                             {
                               $(".loader").hide();
                               var responseObj = $.parseJSON(data);
                               if(responseObj.status == 1)
                               {
                                 showSuccessMessageReview(review_add_msg,'reload');
                               }
                               else
                               {
                                 if(responseObj.is_login_err == 0)
                                 {
                                   showErrorMessage(responseObj.msg);
                                 }
                                 else
                                 {
                                   showErrorMessage(responseObj.msg,'/front-login/buyer');
                                 }
                               }
         
                             }
                           });
                         }
                     },
                     cancel: {
                      text: 'Avbryt', //cancel 
                      action: function () {
                       //close
                      }
                     },
                 },
                 onContentReady: function () {
                     // bind to events
                     var jc = this;
                     this.$content.find('form').on('submit', function (e) {
                         // if the user submits the form by pressing enter in the field.
                         e.preventDefault();
                         jc.$$formSubmit.trigger('click'); // reference the button and click it
                     });
                 }
             });
            }
            }
            else
            {
              window.location.href = siteUrl+"/front-login/buyer"; 
            }
           }
          
         });
      });
    }
   });
}

if($('.service_listings').length>0) {
  var page = 1;
  get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),$(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), $(".current_search_string").text());
  $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),$(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(),$(".current_search_string").text());
   });
}

/*$(document).on('click', '#productSearchFilter', function(event){
      event.preventDefault();
      $('.product_listings').show();
      $('#other_watched_products').show();
      $('.service_listings').hide();
      $('#other_watched_services').hide();
      $(this).addClass("filterActive");
      $("#serviceSearchFilter").removeClass("filterActive");
     get_product_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
      $(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), 
      $(".current_search_string").text(),'',$(".current_role_id").text());
});

$(document).on('click', '#serviceSearchFilter', function(event){
      event.preventDefault();
      $('.product_listings').hide();
      $('.service_listings').show();
      $('#other_watched_services').show();
      $('#other_watched_products').hide();
      $(this).addClass("filterActive");
      $("#productSearchFilter").removeClass("filterActive");
      $("#productSearchFilter").addClass("inactiveFilter");
      get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),$(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(),$(".current_search_string").text());

});*/
// function get_service_listing(page,category_slug='',subcategory_slug='',sellers ='',price='', search_string='') {
//   var sort_by_order = $("#sort_by_order").val();
//   var sort_by = $("#sort_by").val();

//   $.ajax({
//     url:siteUrl+"/get_service_listing",
//     headers: {
//       'X-CSRF-Token': $('meta[name="_token"]').attr('content')
//     },
//     type: 'post',
//     data : {'page': page, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string },
//     success:function(data)
//     {
//       var responseObj = $.parseJSON(data);
      
//       $('.service_listings').html(responseObj.services);
//       $('.seller_list_content').html(responseObj.sellers);

//       if($("#search_string").length && search_string != '')
//       {
//         $("#search_string").val(search_string);
//       }
//     }
//    });
// }



function get_service_listing(page,category_slug='',subcategory_slug='',sellers ='',price='',city='', search_string='',search_seller_product='') {
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': page, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price,'city_filter' : city, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string,'search_seller_product':search_seller_product,'path':window.location.pathname  },
    success:function(data)
    {
      var responseObj = $.parseJSON(data);
      
      $('.service_listings').html(responseObj.services);
      $('.seller_list_content').html(responseObj.sellers);

      if($("#search_string").length && search_string != '')
      {
        $("#search_string").val(search_string);
      }

      /*service rating*/
       $(".service_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            console.log(is_login);
            if(is_login == 1)
            {
            // Get element id by data-id attribute
            var el = this;
            var el_id = el.$elem.data('id');
            
            // rating was selected by a user
            if (typeof(event) !== 'undefined') {
              
              var split_id = el_id.split("_");
              var service_id = split_id[1]; // postid

              $.confirm({
                 title: txt_your_review,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Skicka', //submit
                         btnClass: 'btn-blue',
                         action: function () {
                             var comments = this.$content.find('.name').val();
                             if(!comments){
                               showErrorMessage(txt_comments_err);
                               return false;
                             }
                             $(".loader").show();
                             $.ajax({
                             url:siteUrl+"/add-service-review",
                             headers: {
                               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                             },
                             type: 'post',
                             data : {'rating': value, 'service_id' : service_id, 'comments' : comments},
                             success:function(data)
                             {
                               $(".loader").hide();
                               var responseObj = $.parseJSON(data);
                               if(responseObj.status == 1)
                               {
                                 showSuccessMessageReview(responseObj.msg,'reload');
                               }
                               else
                               {
                                 if(responseObj.is_login_err == 0)
                                 {
                                   showErrorMessage(responseObj.msg);
                                 }
                                 else
                                 {
                                   showErrorMessage(responseObj.msg,'/front-login/buyer');
                                 }
                               }
         
                             }
                           });
                         }
                     },
                     cancel: {
                      text: 'Avbryt', //cancel 
                      action: function () {
                       //close
                      }
                     },
                 },
                 onContentReady: function () {
                     // bind to events
                     var jc = this;
                     this.$content.find('form').on('submit', function (e) {
                         // if the user submits the form by pressing enter in the field.
                         e.preventDefault();
                         jc.$$formSubmit.trigger('click'); // reference the button and click it
                     });
                 }
             });
            }
            }
            else
            {
              window.location.href = siteUrl+"/front-login/buyer"; 
            }
           }
          
         });
      });


    }
   });
}


function addToCart(product_variant)
{
  //return 1;
  var product_quantity = $("#product_quantity_"+product_variant).val();
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/add-to-cart",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'product_variant': product_variant, 'product_quantity' : product_quantity},
    success:function(data)
    {
      $(".loader").hide();
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
          var currentValue = $(".add_to_cart_count").text();
          var newValue = parseInt(parseFloat(currentValue)) + 1;
          $(".bag_cart_count").show();
          $(".add_to_cart_count").text(newValue);
          //showSuccessMessage(product_add_success,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login/buyer');
        }
      }

    }
   });
}


function addToWishlist(product_variant)
{
  var product_quantity = $("#product_quantity_"+product_variant).val();
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/add-to-wishlist",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'product_variant': product_variant, 'product_quantity' : product_quantity},
    success:function(data)
    {
      $(".loader").hide();
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
        var currentValue = $(".wishlist_count").text();
        
        if(currentValue==0){
          $(".count_wishlist").css("display","block");
        }
          var newValue = parseInt(parseFloat(currentValue)) + 1;
        
        
        $(".wishlist_count").text(newValue);
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login/buyer');
        }
      }

    }
   });
}

function addToWishlistServices(service_id)
{
 
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/add-to-wishlist",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'service_id': service_id},
    success:function(data)
    {
      $(".loader").hide();
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
        var currentValue = $(".wishlist_count").text();
        if(currentValue==0){
          $(".count_wishlist").css("display","block");
        }
        var newValue = parseInt(parseFloat(currentValue)) + 1;
        $(".wishlist_count").text(newValue);
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login/buyer');
        }
      }

    }
   });
}



function addToCartWishlist(product_variant)
{
  var product_quantity = 1;
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/add-to-cart",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'product_variant': product_variant, 'product_quantity' : product_quantity, 'from_wishlist' : 1},
    success:function(data)
    {
      $(".loader").hide();
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
          showSuccessMessageReview(product_add_success,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login/buyer');
        }
      }

    }
   });
}


//product details jquery start
$('input.product_checkbox_attribute').on('change', function() {
  $(this).parent().parent().find('input.product_checkbox_attribute').not(this).prop('checked', false);
  if ($(this).is(':checked')) {
    var attribute_value_id  = $(this).attr('id');
    $.ajax({
      url: siteUrl+'/get_product_attribute_details',
       data: {attribute_value_id: attribute_value_id},
       type: 'get',
       success: function(output) {
        var arrayval = jQuery.parseJSON(output);
        $('img.product-thumb-image.'+arrayval.image).trigger('click');
        //$('product-main-price')
        $.each(arrayval.attributes, function(key,value) {
          //console.log(value);
        });
       }
  });
  }
});
// $( document ).ready(function() {
//     hideShippingMethod();
// });
/*seller profile free shipping checkbox selected then hide payment method option*/


$('#shipping_charges').on('input',function(e){
  if($("#shipping_method_ddl").val() == ''){
    showErrorMessage(select_shopping_method_err);
  }
});

const regex = /[^\d.]|\.(?=.*\.)/g;
const subst='';

$('#shipping_charges').keyup(function(){
  const str=this.value;
  const result = str.replace(regex, subst);
  this.value=result;
});


function removeCartProduct(OrderDetailsId)
{
  $.confirm({
      title: js_confirm_msg,
      content: product_remove_confirm,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
            $(".loader").show();
            $.ajax({
              url:siteUrl+"/remove-from-cart",
              headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
              },
              type: 'post',
              data : {'OrderDetailsId': OrderDetailsId},
              success:function(data)
              {
                $(".loader").hide();
                var responseObj = $.parseJSON(data);
                if(responseObj.status == 1)
                {
                    showSuccessMessageReview(responseObj.msg,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'/front-login/buyer');
                }
        
              }
            });
          },
          Avbryt: function () {
            
          },
      }
  });

}

function removeWishlistProduct(OrderDetailsId)
{
  $.confirm({
      title: js_confirm_msg,
      content: wishlist_product_remove,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          ok: function () {
            $(".loader").show();
            $.ajax({
              url:siteUrl+"/remove-from-wishlist",
              headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
              },
              type: 'post',
              data : {'OrderDetailsId': OrderDetailsId},
              success:function(data)
              {
                $(".loader").hide();
                var responseObj = $.parseJSON(data);
                if(responseObj.status == 1)
                {
                    showSuccessMessageReview(responseObj.wishlist_remove,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'/front-login/buyer');
                }
        
              }
            });
          },
          Avbryt: function () {
            
          },
      }
  });

}

function updateCart(OrderDetailsId)
{
  var quantity = $("#quantity_"+OrderDetailsId).val();
  $(".loader").show();
  $.ajax({
    url:siteUrl+"/update-cart",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'OrderDetailsId': OrderDetailsId, 'quantity' : quantity},
    success:function(data)
    {
      $(".loader").hide();
      var responseObj = $.parseJSON(data);
      if(responseObj.status == 1)
      {
          //showSuccessMessage(responseObj.msg,'reload');
          location.reload();
      }
      else
      {
        if(responseObj.is_login_err)
        {
          showErrorMessage(responseObj.msg,'/front-login/buyer');
        }
        else
        {
          showErrorMessage(responseObj.msg,'reload');
        }
      }

    }
   });
}

function showErrorMessage(strContent,redirect_url = '')
{ 
  $.alert({
      title: oops_heading,
      content: strContent,
      type: 'red',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-times-circle",
      buttons: {
        Ok: function () {
            if(redirect_url != '')
            {
              if(redirect_url == 'reload')
              {
                location.reload(true);
              }
              else
              {
                window.location.href = redirect_url;
              }
            }
        },
      }
    });
}


function showSuccessMessage(strContent,redirect_url = '')
{
    
  $.alert({
      title: success_heading,
      content: strContent,
      type: '#03989e',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-check-circle",
      buttons: {
        ok: function () {
          if(redirect_url != '')
          {
            if(redirect_url == 'reload')
            {
              location.reload(true);
            }
            else
            {
              window.location.href = redirect_url;
            }
          }
        },
      }
    });
}

$('body').on('change', '.service_date', function () {
  var service_date  = $(this).val();
  
  $( ".service_time" ).find('option').each(function() {
      
    $(this).hide();
    });
    $( ".service_time" ).find('option#'+service_date).show();
 
}); 
$('body').on('change', '.service_image', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  var variant_id  = $(this).attr('variant_id');
        
  var validExtensions = ["jpg","jpeg","gif","png"];
  var minwidth  = 600;
  var minheight = 600;
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();
  if (validExtensions.indexOf(file) == -1) {
          showErrorMessage(invalid_files_err);
          $(this).val('');
          return false;
  }
  
  var reader = new FileReader();
  //Read the contents of Image File.
  reader.readAsDataURL(fileUpload.files[0]);
  reader.onload = function (e) {
    //Initiate the JavaScript Image object.
    var image = new Image();

    //Set the Base64 string return from FileReader as source.
    image.src = e.target.result;
         
    //Validate the File Height and Width.
    image.onload = function () {
      var height = this.height;
      var width = this.width;
      if (height < minwidth || width < minheight) {

         //show width and height to user
        showErrorMessage(image_upload_height_width);
        $(this).val('');
        return false;

      }
      var formData = new FormData();

      if (fileUpload.files.length > 0) {

        formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);
        $(".loader").show();
        $.ajax({
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url: siteUrl+'/manage-services/upload-image',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,

          success: function(data) {
            $(".loader").hide();   
              
            elm.prev('div.images').append('<div><input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images[]">'+
            '<img src="'+siteUrl+'/uploads/ServiceImages/resized/'+data+'" width="78" height="80">'+
                      '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a></div>');     
          }
        });
      }

    };
 
  }

});

function showSuccessMessageReview(strContent,redirect_url = '')
{
  $.alert({
      title: "Klart!",
      content: strContent,
      type: '#03989e',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-check-circle",
      buttons: {
        ok: function () {
          if(redirect_url != '')
          {
            if(redirect_url == 'reload')
            {
              location.reload(true);
            }
            else
            {
              window.location.href = redirect_url;
            }
          }
        },
      }
    });
}


$('#phone_number').keydown(function (e) {
    var key = e.which || e.charCode || e.keyCode || 0;
    $phone = $(this);

    // Don't let them remove the starting '('
    if ($phone.val().length === 1 && (key === 8 || key === 46)) {
      $phone.val('('); 
      return false;
    } 
    // Reset if they highlight and type over first char.
    else if ($phone.val().charAt(0) !== '(') {
      $phone.val('('+String.fromCharCode(e.keyCode)+''); 
    }

    // Auto-format- do not expose the mask as the user begins to type
    if (key !== 8 && key !== 9) {
      if ($phone.val().length === 4) {
        $phone.val($phone.val() + ')');
      }
      if ($phone.val().length === 5) {
        $phone.val($phone.val() + ' ');
      }     
      if ($phone.val().length === 9) {
        $phone.val($phone.val() + '-');
      }
    }

    // Allow numeric (and tab, backspace, delete) keys only
    return (key == 8 || 
        key == 9 ||
        key == 46 ||
        (key >= 48 && key <= 57) ||
        (key >= 96 && key <= 105)); 
  })
  
  .bind('focus click', function () {
    $phone = $(this);
    
    if ($phone.val().length === 0) {
      $phone.val('(');
    }
    else {
      var val = $phone.val();
      $phone.val('').val(val); // Ensure cursor remains at the end
    }
  })
  
  .blur(function () {
    $phone = $(this);
    
    if ($phone.val() === '(') {
      $phone.val('');
    }
  });
  
$('.subscribed_users').click(function(){
  var email = $('#usersSubscribed').val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
  let error = 0;

  if(email == '') {
    $('.subscribe_msg').addClass('subscribe_err');
    $(".subscribe_msg").html(fill_in_email_err).show();
    $(".subscribe_msg").parent().addClass('jt-error');
    error = 1;
  }else if(!email_pattern.test(email)){
    $('.subscribe_msg').addClass('subscribe_err');
    $(".subscribe_msg").html(valid_email_err).show();
    $(".subscribe_msg").parent().addClass('jt-error');
    error = 1;
  } else {
    $(".subscribe_msg").parent().removeClass('jt-error');
    $(".subscribe_msg").html('');
  }

  if(error == 1)
  {
    return false;
  }
  else
  {
       
    $('.footer-loader').show();
    $.ajax({
      headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
      },

      url: siteUrl+'/users-subscription/?email='+email,
      type: 'post',

      data: { },

      success: function(response){
        $('.footer-loader').hide();
        console.log(response);
        if(response.error !=''){
          $('.subscribe_msg').removeClass('subscribe_success');
          $('.subscribe_msg').addClass('subscribe_err');
          $('.subscribe_msg').text(response.error)
        }
        
        if(response.success !=''){
           $('.subscribe_msg').removeClass('subscribe_err');
        $('.subscribe_msg').addClass('subscribe_success');
          $('.subscribe_msg').text(response.success)
        }
      }
    });
  }
});

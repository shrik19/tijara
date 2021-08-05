
//$.noConflict(); 

/*function convertToSlug by its name*/
function convertToSlug(inputtxt){
   // allLetterNumber(inputtxt);
    var slug = inputtxt.value;
    //replace space with hypen
    slug = inputtxt.value.toLowerCase();
    //replace special character with -
    slug = slug.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        //slug = slug.replace(/[^\w\s]/gi, '')
    //function to check slug name is unique or not
    slug = checkUniqueSlugName(slug);
    $('.slug-name').val(slug);

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

$(".add_new_variant_btn").click(function(){

    var $tableBody = $('#variant_table').find("tbody"),
    $trLast = $tableBody.find("tr.variant_tr:last");
    variant_id=$trLast.attr('variant_id');
    variant_id++;

    $trNew = $trLast.clone();
    $trNew.attr('variant_id',variant_id);
    $trNew.find(':text').val('');
    $trNew.find('.hidden_images').val('');
    $trNew.find('.variant_image').val('');
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
    $trNew.find('.modal').attr('variant_id',variant_id);
    $trNew.find('.plus_attribute').attr('variant_id',variant_id);
    $trNew.find('.save_attribute_group').attr('variant_id',variant_id);
    $trNew.find('.select_attribute').attr('variant_id',variant_id);
    $trNew.find('.modal').addClass('modalVariant_'+variant_id);
    $trNew.find('.close_modal').attr('variant_id',variant_id);
    $trNew.find('.attribute_tr').find('.remove_attribute_btn').remove();
    $trNew.find('.clone_tr').remove();
    $trNew.find('.select_attribute').val('');
    $trNew.find('.select_attribute_value').html('<option value="">'+select_attribute_value+'</option>');
    $trNew.find('tr.attribute_tr:gt(0)').remove();
    $trNew.find('img').remove();
    $trNew.find('.remove_image').remove();
    if($trNew.find('.add_attribute_group_td').find('.remove_variant_btn').length<=0)
    $trNew.find('.add_attribute_group_td').find('.variantButtonGroup').append("<a href='javascript:void(0);' variant_id='"+variant_id+"' class='btn btn-danger btn-xs remove_variant_btn' title='Remove Variant'><i class='fas fa-trash'></i></a>");
    //$trNew.find('.plus_attribute_tr').html("<a href='javascript:void(0);'  variant_id='"+variant_id+"'  class='fas fa-trash remove_attribute_btn' title='Remove Attribute'  ></a>");
    $trNew.find('.variant_attribute_id').attr('value','').attr('name','variant_attribute_id['+variant_id+'][0]');
    $trNew.find('.select_attribute').removeClass('preselected_attribute').attr('id','0').attr('name','attribute['+variant_id+'][0]');
    $trNew.find('.select_attribute_value').removeClass('preselected_attribute').attr('name','attribute_value['+variant_id+'][0]');

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
        var file = $(this).val().split('.').pop();
        if (validExtensions.indexOf(file) == -1) {
                alert(invalid_files_err);
                $(this).val('');
                return false;
        }

        var formData = new FormData();

        if (fileUpload.files.length > 0) {

               formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

                $.ajax({
                    headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                      url: siteUrl+'/manage-products/upload-variant-image',
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,

                      success: function(data) {
                       elm.parent('td').append('<input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images['+variant_id+'][]">'+
                          '<img src="'+siteUrl+'/uploads/ProductImages/'+data+'" width="40" height="40">'+
                                            '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>');     
                      }

                });
        }

});
$('body').on('click', '.remove_image', function () {
    $(this).prev('img').prev('input').remove();
    $(this).prev('img').remove();
    $(this).remove();
});
$('#variant_table').on('click', '.add_attribute_group_btn', function () {

    var variant_id  =   $(this).attr('variant_id');
    // jQuery.noConflict();
    //jQuery.noConflict();
    $('.modal[variant_id="'+variant_id+'"]').modal('show');
});

 $('#variant_table').on('click', '.remove_variant_btn', function () {
     if (!confirm('Are you sure you want to remove Variant?')) return false;
     $(this).parent('span').parent('td').parent('tr').remove();
 });
  $('#variant_table').on('click', '.remove_attribute_btn', function () {

     $(this).parent('td').parent('tr').remove();
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
        if(val!='') {
          var variant_id  =   $(this).attr('variant_id');
          $('.modal[variant_id="'+variant_id+'"]').find( "select.select_attribute:not(.active)" ).each(function() {

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

      if(error == 0){
        $.ajax({
              url: siteUrl+'/product-attributes/getattributevaluebyattributeid',
               data: {attribute_id: select_attribute},
               type: 'get',
               success: function(output) {
                            elm.parent('td').parent('tr').find('.select_attribute_value').html(output);
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

                          $('.select_attribute_value.'+id).html(output);

                          $('.select_attribute_value.'+id).val($('.select_attribute_value.'+id).attr('selected_attribute_value'));
                           //alert( $('.select_attribute_value.'+id).attr('selected_attribute_value'));

             }
            });

    //
});

$('#variant_table').on('click', '.close_modal', function () {

    var variant_id  =   $(this).attr('variant_id');
    $('.modal[variant_id="'+variant_id+'"]').find('.select_attribute').val('');
    $('.modal[variant_id="'+variant_id+'"]').find('.select_attribute_value').val('');
    $('.modal[variant_id="'+variant_id+'"]').find( ".clone_tr" ).each(function() {
        $(this).remove();
    });
    $('.modal[variant_id="'+variant_id+'"]').find('tr.attribute_tr:gt(0)').remove();
    $('.modal[variant_id="'+variant_id+'"]').find('.select_attribute_value').html('<option value="">'+select_attribute_value+'</option>');
   // $('.modal[variant_id="'+variant_id+'"]').modal('hide');
});
$('#variant_table').on('click', '.save_attribute_group', function () {


    var variant_id  =   $(this).attr('variant_id');


    alert(attribute_saved);
    $('.modal[variant_id="'+variant_id+'"]').modal('hide');
    var toPutHtml   =   '';
    $('.modal[variant_id="'+variant_id+'"]').find( "select.select_attribute" ).each(function() {
        if($(this).val()!='' && $(this).parent('td').next('td').find('select').val()!='') {
            select_attribute        =   $(this).find('option:selected').text();
            select_attribute_value  =   $(this).parent('td').next('td').find('option:selected').text();
            toPutHtml+='<div class="added_attributes_each_div"><b>'+select_attribute+'</b>:'+select_attribute_value+'</div>';
        }
        $('.added_attributes[variant_id="'+variant_id+'"]').html(toPutHtml);
    });
});
$('#variant_table').on('click', '.plus_attribute', function () {
    var variant_id  =   $(this).attr('variant_id');
    var $tableBody = $('.modal[variant_id="'+variant_id+'"]').find("tbody");
    $trLast = $tableBody.find("tr:last");
    attribute_number    =   $trLast.attr('attribute_number');
    attribute_number++;
   // variant_id++
    $trNew = $trLast.clone();

    $trNew.find('.select_attribute_value').html('<option value="">'+select_attribute_value+'</option>');

    //$trNew.find('option[value="'+$trLast.find('.select_attribute').val()+'"]').remove();
    $trNew.find('option:hidden').show();
    $trNew.find('select').val('');
    $trNew.attr('attribute_number',attribute_number);
    //$trNew.attr('id',"attribute_tr_"+variant_id);
    $trNew.addClass('clone_tr');
    $trNew.find('.plus_attribute_tr').html('<a href="javascript:void(0);"  variant_id="'+variant_id+'" class="btn btn-danger btn-xs remove_attribute_btn" title="Remove Attribute"><i class="fa fa-times"></i></a>');
    $trNew.find('.variant_attribute_id').val('').attr('name','variant_attribute_id['+variant_id+']['+attribute_number+']');
    $trNew.find('.select_attribute').attr('name','attribute['+variant_id+']['+attribute_number+']');
    $trNew.find('.select_attribute_value').attr('name','attribute_value['+variant_id+']['+attribute_number+']');

    $trLast.after($trNew);
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
  let title     = $("#title").val();
  let sort_order    = $("#sort_order").val();
  let error     = 0;


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
  $( ".variant_field" ).each(function() {
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
$(".frontregisterbtn").click(function(e){
  e.preventDefault();
  let fname   = $("#fname").val();
  let lname   = $("#lname").val();

  let email     = $("#email").val();
  let password  = $("#password").val();
  let cpassword = $("#cpassword").val();
  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
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
    $('#sign-up-form').submit();
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

  let email     = $("#email").val();

  let email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
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
    $("#err_email").html(fill_in_email_err).show();
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

function ConfirmDeleteFunction(url, id = false) {
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

if($('.product_listings').length>0) {
  var page = 1;
  get_product_listing(page,$('.current_category').text(),$('.current_subcategory').text(),$(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), $(".current_search_string").text());
  $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      get_product_listing(page,$('.current_category').text(),$('.current_subcategory').text(),$(".current_sellers").text(),$("#price_filter").val(), $("#city_name").val(), $(".current_search_string").text());
   });


}



function get_product_listing(page,category_slug='',subcategory_slug='',sellers ='',price='',city='', search_string='') {
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();

  $.ajax({
    url:siteUrl+"/get_product_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': page, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price,'city_filter' : city, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string },
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
                 title: txt_your_comments,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Submit',
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
                                 showSuccessMessage(product_add_success,'reload');
                               }
                               else
                               {
                                 if(responseObj.is_login_err == 0)
                                 {
                                   showErrorMessage(responseObj.msg);
                                 }
                                 else
                                 {
                                   showErrorMessage(responseObj.msg,'/front-login');
                                 }
                               }
         
                             }
                           });
                         }
                     },
                     cancel: function () {
                         //close
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
              showErrorMessage(login_buyer_required);
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



function get_service_listing(page,category_slug='',subcategory_slug='',sellers ='',price='',city='', search_string='') {
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': page, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price,'city_filter' : city, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string },
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
                 title: txt_your_comments,
                 content: '' +
                 '<form action="" class="formName">' +
                 '<div class="form-group">' +
                 '<label>'+txt_comments+'</label>' +
                 '<textarea class="name form-control" rows="3" cols="20" placeholder="'+txt_comments+'" required></textarea>' +
                 '</div>' +
                 '</form>',
                 buttons: {
                     formSubmit: {
                         text: 'Submit',
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
                                 showSuccessMessage(review_add_success,'reload');
                               }
                               else
                               {
                                 if(responseObj.is_login_err == 0)
                                 {
                                   showErrorMessage(responseObj.msg);
                                 }
                                 else
                                 {
                                   showErrorMessage(responseObj.msg,'/front-login');
                                 }
                               }
         
                             }
                           });
                         }
                     },
                     cancel: function () {
                         //close
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
              showErrorMessage(login_buyer_required);
            }
           }
          
         });
      });


    }
   });
}


function addToCart(product_variant)
{
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
          showSuccessMessage(product_add_success,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login');
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
          showSuccessMessage(wishlist_add_success,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login');
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
          showSuccessMessage(service_fav_succ,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login');
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
          showSuccessMessage(product_add_success,'reload');
      }
      else
      {
        if(responseObj.is_login_err == 0)
        {
          showErrorMessage(responseObj.msg);
        }
        else
        {
          showErrorMessage(responseObj.msg,'/front-login');
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
$( document ).ready(function() {
    hideShippingMethod();
});
/*seller profile free shipping checkbox selected then hide payment method option*/
function hideShippingMethod(){
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
      title: 'Confirm!',
      content: product_remove_confirm,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          okay: function () {
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
                    showSuccessMessage(responseObj.msg,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'/front-login');
                }
        
              }
            });
          },
          cancel: function () {
            
          },
      }
  });

}

function removeWishlistProduct(OrderDetailsId)
{
  $.confirm({
      title: 'Confirm!',
      content: wishlist_product_remove,
      type: 'orange',
      typeAnimated: true,
      columnClass: 'medium',
      icon: 'fas fa-exclamation-triangle',
      buttons: {
          okay: function () {
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
                    showSuccessMessage(responseObj.wishlist_remove,'reload');
                }
                else
                {
                    showErrorMessage(responseObj.msg,'/front-login');
                }
        
              }
            });
          },
          cancel: function () {
            
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
          showSuccessMessage(responseObj.msg,'reload');
      }
      else
      {
        if(responseObj.is_login_err)
        {
          showErrorMessage(responseObj.msg,'/front-login');
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
      title: 'Oops!',
      content: strContent,
      type: 'red',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-times-circle",
      buttons: {
        okay: function () {
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
      title: 'Success!',
      content: strContent,
      type: 'green',
      typeAnimated: true,
      columnClass: 'medium',
      icon : "fas fa-check-circle",
      buttons: {
        okay: function () {
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

$('body').on('change', '.service_image', function () {

  var fileUpload  = $(this)[0];
  var elm         =   $(this);
  
  var validExtensions = ["jpg","jpeg","gif","png"];
  var file = $(this).val().split('.').pop();
  if (validExtensions.indexOf(file) == -1) {
          alert(invalid_files_err);
          $(this).val('');
          return false;
  }

  var formData = new FormData();

  if (fileUpload.files.length > 0) {

         formData.append("fileUpload", fileUpload.files[0], fileUpload.files[0].name);

          $.ajax({
              headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
                url: siteUrl+'/manage-services/upload-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function(data) {
                 elm.next('div.images').append('<input type="hidden" class="form-control login_input hidden_images" value="'+data+'"  name="hidden_images[]">'+
                    '<img src="'+siteUrl+'/uploads/ServiceImages/'+data+'" width="70" height="70">'+
                                      '<a href="javascript:void(0);" class="remove_image"><i class="fas fa-trash"></i></a>');     
                }

          });
  }



});
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
	allLetterNumber(inputtxt);
    var slug = inputtxt.value.toLowerCase().replace(/ /g,'-').replace(/[^\w-][^,]+,\s[^,]+/g,'');
    $('.slug-name').val(slug);
    //function to check slug name is unique or not
    checkUniqueSlugName(slug);
}

/*function to validate letters for category*/
  function allLetterNumber(inputtxt){ 
    var letters = /^[0-9a-zA-ZäöåÄÖÅ ]*$/;
    if(inputtxt.value.match(letters)){
      $('.err-letter').text('');
      return true;
    }
    else {
      $('.err-letter').text(input_letter_no_err);
      return false;
    }
  }
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
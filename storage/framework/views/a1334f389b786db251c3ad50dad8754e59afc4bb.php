
<?php $__env->startSection('middlecontent'); ?>


<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>
 <!-- Carousel Default -->
<section class="product_section">
  <?php if(!empty($header_image)): ?>
      <img class="seller_banner" src="<?php echo e($header_image); ?>" alt="Header Image" style="width:100%;"/>
    <?php endif; ?>
    <div class="container-fluid">
    <div class="container-inner-section">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
       <!--  <?php echo $__env->make('Front.category_breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> -->
        <div class="col-md-3">
          <div>
             <?php if(!empty($logo)): ?> 
             <div class="seller_logo seller_details_img">
             <img class="seller_logo" src="<?php echo e($logo); ?>" alt="Logo" />&nbsp;&nbsp; </div><?php endif; ?>
           
             <div class="seller_info border-none seller_details">
              <h2><?php echo e($seller_name); ?></h2>
              <p><?php echo e($city_name); ?></p>
              <div class="star-rating">
                <select class='rating service_rating' data-rating="<?php echo e($totalRating); ?>">
                  <option value="1" >1</option>
                  <option value="2" >2</option>
                  <option value="3" >3</option>
                  <option value="4" >4</option>
                  <option value="5" >5</option>
                </select>
              </div>
          </div>
            </div>
            <div class="clearfix"></div>
             <h2> <?php echo e(__('users.butiks_info_title')); ?></h2>
             <div class="clearfix"></div>
              <h4 style="margin-top: 10px;"><?php echo e(__('lang.category_label')); ?></h4>
              <div class=" form-group search_now_input_box">
            <input type="text" name="seller_product_filter" id="seller_product_filter" class="form-control input-lg" placeholder="<?php echo e(__('users.search_item_placeholder')); ?>" />
            <button class="search_icon_btn seller_serch_icon" type="submit"><i class="fa fa-search"></i></button>
</div>
            <?php echo $__env->make('Front.services_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
           <div style="text-align: center">
            <a href="<?php echo e(route('sellerServiceListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])); ?>" title="<?php echo e(__('lang.service_label')); ?> " class="<?php if(Request::segment(4)=='services'): ?> store-active-btn  <?php else: ?> store-inactive-btn <?php endif; ?>"><?php echo e(__('lang.service_label')); ?>  </a>
            <a href="<?php echo e(route('sellerProductListingByCategory',['seller_name' => $seller_name_url, 'seller_id' => base64_encode($seller_id)])); ?>" title="<?php echo e(__('lang.products_title')); ?>" class="<?php if(Request::segment(4)=='products'): ?> store-active-btn  <?php else: ?> store-inactive-btn <?php endif; ?>" ><?php echo e(__('lang.products_title')); ?> </a>

             <!-- contact shop -->
            <a href="javascript:void(0);"  class="btn btn-black debg_color login_btn contact-store pull-right" title="<?php echo e(__('users.contact_store')); ?>" id="<?php echo e($seller_id); ?>" seller_email="<?php echo e($seller_email); ?>" seller_name="<?php echo e($seller_name); ?>"><?php echo e(__('users.contact_store')); ?> </a>

          </div>
            <span class="current_category" style="display:none;"><?php echo e($category_slug); ?></span>
            <span class="current_subcategory" style="display:none;"><?php echo e($subcategory_slug); ?></span>
            <span class="current_sellers" style="display:none;"><?php echo e($seller_id); ?></span>
            
            <div class="product_container">
            <div class="row">               
                  <div class="row"><div class="col-md-12">&nbsp;</div></div>
                  <?php if(!empty($store_information)): ?>
                  <div class="col-md-12">
                    <p class="store_info"><?php echo $store_information; ?></p>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row">
                <div class="col-md-3 pull-right">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by')); ?> : </label>
                      <select class="form-control" name="sort_by" id="sort_by" class="sort_by_name" onchange="listService()">
                        <!--   <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option> -->
                          <option value="name"><?php echo e(__('lang.sort_by_name')); ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 pull-right">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by_order')); ?> : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" class="sort_by_order" onchange="listService()">
                          <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option>
                          <option value="asc"><?php echo e(__('lang.sort_by_asc')); ?></option>
                          <option value="desc"><?php echo e(__('lang.sort_by_desc')); ?></option>
                      </select>
                    </div>
                  </div>
                  
                </div>
                <span class="service_listings"><div style="text-align:center;margin-top:50px;"><img src="<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>

        <div class="col-md-12">
          <hr>

          <div class="col-md-2">
            <h2><?php echo e(__('users.review_title')); ?></h2>
          </div>

          <div class="col-md-9">
            <?php if(!empty($serviceReviews)): ?>
              <?php $__currentLoopData = $serviceReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="row">
                <div class="col-md-1">
                  <?php if(!empty($review['profile'])): ?>
                    <img src="<?php echo e(url('/')); ?>/uploads/Buyer/buyerIcons/<?php echo e($review['profile']); ?>" class="ratingUserIcon">
                  <?php else: ?> 
                    <img src="<?php echo e(url('/')); ?>/uploads/Buyer/buyerIcons/no-image.png" class="ratingUserIcon">
                  <?php endif; ?>                   
                </div>

                <div class="col-md-5">
                  <p class="ratingUname"><?php echo $review['fname']." ".$review['lname'].", ".date('d F, Y',strtotime($review['updated_at']));?></p>

                  <div class="star-rating" style="font-size:unset;pointer-events: none;">
                    <select class='rating service_rating' data-rating="<?php echo e($review['service_rating']); ?>">
                      <option value="1" >1</option>
                      <option value="2" >2</option>
                      <option value="3" >3</option>
                      <option value="4" >4</option>
                      <option value="5" >5</option>
                    </select>
                  </div>
                  
                  <p class="ratingComment"><?php echo e($review['comments']); ?></p>
                </div>
              <div class="col-md-6"></div>
              </div>
              <hr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <?php echo $serviceReviews->links(); ?>

            <?php endif; ?>
          </div>
        </div>
        <!-- <div class="col-md-12">
          <div class="mtb-20">
          <h2><?php echo e(__('users.store_terms_title')); ?></h2>
          <?php if(!empty($getTerms)): ?>
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Store Policy : </p>
              <p style="margin-left: 10px;"><?php echo e($getTerms->store_policy); ?></p>
            </div>
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Return Policy : </p>
              <p style="margin-left: 10px;"><?php echo e($getTerms->return_policy); ?></p>
            </div>
            <div style="display: flex;">
              <p style="font-weight: bold;font-size: 16px;">Shipping Policy : </p>
              <p style="margin-left: 10px;"><?php echo e($getTerms->shipping_policy); ?></p>
            </div>             
          <?php endif; ?>
        </div>
        </div> -->
         <div class="col-md-12" style="margin-bottom: 50px;"> <hr>
          <div class="col-md-3">
            <h2><?php echo e(__('users.store_terms')); ?></h2>
          </div>
        <div class="col-md-9" style="margin-top: 25px;">
          <button class="tablink" onclick="openPage('PaymentPolicy', this, 'red')" id="defaultOpen" style=""><?php echo e(__('users.payment_btn')); ?></button>
          <button class="tablink" onclick="openPage('ShippingPolicy', this, 'blue')"><?php echo e(__('users.shipping_btn')); ?></button>
          <button class="tablink" onclick="openPage('ReturnPolicy', this, 'green')"><?php echo e(__('users.return_btn')); ?></button>
          <button class="tablink" onclick="openPage('BookingPolicy', this, 'white')"><?php echo e(__('users.booking_btn')); ?></button>
      
          <?php if(!empty($getTerms)): ?>
          <div id="PaymentPolicy" class="tabcontent">
          <!--   <h3><?php echo e(__('users.store_policy_label')); ?></h3> -->
            <p class="policies"><?php echo e(@$getTerms->payment_policy); ?></p>
            </div>

            <div id="ShippingPolicy" class="tabcontent">
            <!-- <h3><?php echo e(__('users.shipping_policy_label')); ?></h3> -->
            <p class="policies"><?php echo e(@$getTerms->shipping_policy); ?></p>
            </div>

            <div id="ReturnPolicy" class="tabcontent">
           <!--  <h3><?php echo e(__('users.return_policy_label')); ?></h3> -->
            <p class="policies"><?php echo e(@$getTerms->return_policy); ?></p> 
            </div>

            <div id="BookingPolicy" class="tabcontent">
            <!-- <h3><?php echo e(__('users.shipping_policy_label')); ?></h3> -->
            <p class="policies"><?php echo e(@$getTerms->booking_policy); ?></p>
            </div>
        <?php endif; ?>

      
   
        </div>
      </div>
       
        
    </div>
</div>

<!-- add subcategory model Form -->
 <div class="modal fade" id="contactStoremodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo e(__('users.contact_store_head')); ?></h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        <div class="loader"></div>
        <div class="modal-body">
            <div class="container">
            <form action="<?php echo e(route('FrontContactStore')); ?>"  enctype="multipart/form-data" method="post" class="storeContactform">
              <?php echo csrf_field(); ?>
                  <input type="hidden" class="seller_id"  name="hid" id="seller_id" value="">
                  <input type="hidden" name="seller_email" class="seller_email" id="seller_email" value="">
                  <input type="hidden" name="seller_name" class="seller_name" id="seller_name" value="">
                <div class="form-group">
                  <label><?php echo e(__('users.your_message_label')); ?> <span class="text-danger">*</span></label>
                  <textarea class="user_message  form-control contact-store-message" name="user_message" rows="3" cols="20" placeholder="<?php echo e(__('lang.txt_comments')); ?>"  placeholder="<?php echo e(__('users.subcategory_name_label')); ?>" id="user_message"required></textarea>
               
                </div>
            </form>
            </div>
        </div>
        
       <div class="modal-footer">
        <button type="submit" class="conact-store-save btn btn-black debg_color login_btn"><?php echo e(__('lang.save_btn')); ?></button>
        <button type="button" class="btn btn-black gray_color login_btn" data-dismiss="modal"><?php echo e(__('lang.close_btn')); ?></button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- end contact seller model Form -->


    </div> <!-- /container -->
</section>

<script type="text/javascript">

$(document).ready(function() {
  var read_more_btn = "<?php echo e(__('users.read_more_btn')); ?>";
  var read_less_btn = "<?php echo e(__('users.read_less_btn')); ?>";

	get_service_count();

	var maxLength = 200;
	$(".store_info").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = '<span class="trim-text">'+ myStr.substring(0, maxLength) + '</span>';
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append( '<span class="more-text">' + myStr.substring(0, maxLength) + removedStr + '</span>');
			$(this).append(' <a href="javascript:void(0);" class="read-more">...'+read_more_btn+'</a>');
		}
	});	
	$(".more-text").hide();
	$(".read-more").click(function(){
		var moreLess = $(".more-text").is(':visible') ? '...'+read_more_btn : '...'+read_less_btn;
		$(this).text(moreLess);
		$(this).parent('.store_info').find(".more-text").toggle();
		$(this).parent('.store_info').find(".trim-text").toggle();
	});	
});

function get_service_count(){
  $.ajax({
    url:siteUrl+"/getServiceCatSubcatList",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'sellers' : $('.current_sellers').text(),'search_seller_product':$("#seller_product_filter").val()},
    success:function(data)
    { 
      $.each(data, function(k, v) {
        $("#user_id").val();
        //$("#serviceCount_"+k).text(v.service_count);
        if(v.service_count == 0){
            $("#serviceCount_"+k).parent().css('display','none');
        }else{
          
           $("#serviceCount_"+k).text(v.service_count);
        }

      });
    }
   });
}
$( "#seller_product_filter" ).keyup(function() {

     get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),'',$(".current_search_string").text(),$("#seller_product_filter").val()) ;
     get_service_count();
});

function listService() {
   get_service_listing(page,$('.current_category').text(),$('.current_subcategory').text(),
    $('.current_sellers').text(),$('#price_filter').val(),$(".current_search_string").text(),$("#seller_product_filter").val()) ;
}


/*function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();
  var seller_product_filter = $("#seller_product_filter").val();

  $.ajax({
    url:siteUrl+"/get_service_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price_filter, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string,'search_seller_product':seller_product_filter },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.service_listings').html(responseObj.services);
     $('.seller_list_content').html(responseObj.sellers);
    }
   });
}
*/

function selectSellers()
{
    var Sellers = '';
    $(".sellerList").each(function(){
      if($(this).prop('checked'))
      {
        if(Sellers == '')
        {
            Sellers = $(this).val();
        }
        else {
          Sellers += ','+$(this).val();

        }
      }
    });
    $(".current_sellers").html(Sellers);
    listService();

}



$(document).on("click",".contact-store",function(event) {
        
        $('#contactStoremodal').find('.seller_id').val($(this).attr('id'));
        $('#contactStoremodal').find('.seller_email').val($(this).attr('seller_email')); 
        $('#contactStoremodal').find('.seller_name').val($(this).attr('seller_name'));      
        $('#contactStoremodal').modal('show'); 
});

$(document).on("click",".conact-store-save",function(event) {
       //storeContactform
      if($('#contactStoremodal').find('.user_message').val()!='') {
        let user_message   = $("#user_message").val();
        let seller_email   = $("#seller_email").val();
        let seller_id      = $("#seller_id").val();
        let seller_name      = $("#seller_name").val();
		
		$(".loader").show();
        
		setTimeout(function(){
        $.ajax({
          url:"<?php echo e(route('FrontContactStore')); ?>",
          headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
          },
          type: 'POST',
          async: false,
          data:{user_message:user_message,seller_email:seller_email,seller_id:seller_id,seller_name:seller_name},
          success: function(output){
            
			$(".loader").hide();
			$('#contactStoremodal').modal('hide'); 
			
			if(output.success !=''){
              showSuccessMessage(output.success);
              let user_message   = $("#user_message").val('');
            }else{
              showErrorMessage(output.error);
            }
          }
        });}, 300);
      } else{
          showErrorMessage(please_add_your_message);
      }    
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

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
  //   tablink-active
       var element = document.getElementById("defaultOpen");
   element.classList.add("tablink-active");

$(".page-link").click(function(){   
  var attr_val = $(this).attr('href');
  if(attr_val !='' && attr_val != undefined){
    window.location.href = attr_val; 
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/seller-services.blade.php ENDPATH**/ ?>
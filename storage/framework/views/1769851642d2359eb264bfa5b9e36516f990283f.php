
<?php $__env->startSection('middlecontent'); ?>

<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>

 <!-- Carousel Default -->
<section class="product_section p_155">
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row">
  <div class="container-inner-section">
 
        <?php if(Request::segment(1) =='services' || Request::segment(1) =='products' || Request::segment(1) =='annonser'): ?>
          <?php echo $__env->make('Front.category_breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         
        <?php endif; ?>

        <div class="cat-details">
			  <?php if(Request::segment(1) =='annonser'): ?>
				<div class="col-md-3 col-annonser-sidebar  desktop-view pl-0">
					<?php echo $__env->make('Front.annonser_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				</div>
			  <?php else: ?>
				<div class="col-md-3 col-products-sidebar desktop-view">
					<?php echo $__env->make('Front.products_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				</div>
			  <?php endif; ?>
			
			<div class="col-md-9 products-page p-0">
			
				<div class="row">
				   <?php if(Request::segment(1) =='annonser'): ?>
					<div class="col-md-1"></div>
					<div class="col-md-6">
					  <div class="annonser-image" style="text-align:center; width:100%;">
						<img src="<?php echo e(url('/')); ?>/assets/img/tijara_ann.jpeg" style="width:100%;">
            
					  </div>
					</div>
			   
              <?php if($role_id =='1' || $user_id == ''): ?>
    					   <div class="col-md-5 ">
    					     <a href="<?php echo e(route('frontProductCreate')); ?>" title="<?php echo e(__('lang.add_product')); ?>" class="btn btn-black btn-sm debg_color a_btn login_btn add_ads_btn" style="margin-bottom: 10px;"><span>+ <?php echo e(__('users.add_ads_btn')); ?></span> </a>
    					   </div>				 
					     <?php endif; ?>
				   <?php endif; ?>
				 <div class="col-md-12"> 
				  <?php if( Request::segment(1) !='annonser'): ?>
         
					  <div class="col-md-3 pr-0" style="padding-left:45px;">
						<label class="checkbox toggle candy label_width" onclick="" >
						  <input id="view" type="checkbox" />
						  <p>
							<span class="product_sorting_filter" id="productSearchFilter" style="cursor: pointer;"><?php echo e(__('lang.category_product_title')); ?></span>
							<span class="product_sorting_filter" id="serviceSearchFilter"  style="cursor: pointer;"><?php echo e(__('lang.category_service_title')); ?></span>
						  </p>                  
						  <a class="slide-button"></a>                  
						 </label>                   
					  </div>
            <div>&nbsp;</div>
					<?php else: ?>
						<div class="col-md-4"></div>
					<?php endif; ?>
						  <div class="col-md-2"></div>
						  <div style="margin-top: -3%;" class="<?php if(Request::segment(1) !='annonser'): ?> col-md-offset-1 <?php endif; ?> col-md-3 prod-service-filter" >
						  
							<div class="form-group">
							  <label class="product_sorting_filter"><?php echo e(__('lang.sort_by_order')); ?> : </label>
							  <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="getListing()">
								  <option value=""  class="product_sorting_filter_option">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option>
								  <option selected value="asc" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_asc')); ?></option>
								  <option value="desc" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_desc')); ?></option>
							  </select>
							</div>
						  </div>
						  <div style="margin-top: -3%;" class=" col-md-3 prod-service-filter pr-w-0">
							<div class="form-group">
							  <label class="product_sorting_filter"><?php echo e(__('lang.sort_by')); ?> : </label>
							  <select class="form-control" name="sort_by" id="sort_by" onchange="getListing()">
								<!--   <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option> -->
								<option value="popular" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_popular_product')); ?></option>
								<option value="price" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_price')); ?></option>
								<option value="discount" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_discount')); ?></option>
								<option value="name" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_name')); ?></option>
								<option value="rating" class="product_sorting_filter_option"><?php echo e(__('lang.sort_by_rating')); ?></option>
							  </select>
							</div>
						  </div>
						</div>
						</div>

				<span class="current_category" style="display:none;"><?php echo e($category_slug); ?></span>
				<span class="current_subcategory" style="display:none;"><?php echo e($subcategory_slug); ?></span>
				<span class="current_sellers" style="display:none;"><?php echo e($seller_id); ?></span>
				<span class="current_search_string" style="display:none;"><?php echo e($search_string); ?></span>
				<span class="current_role_id" style="display:none;"><?php echo e($current_role_id); ?></span>
				<div class="row product_container filter_product_list product_container-list-4 service_page">
				   
					<span class="product_listings"><div style="text-align:center;margin-top:50px;"><img src="<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            <span class="service_listings"><div style="text-align:center;margin-top:50px;display: none"><img src="<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
				</div>
			</div>     
		</div>
    </div>
    </div>

    </div> 
</section>
<?php if(Request::segment(1) !='annonser'): ?>
<section>
    <div class="container-fluid">
        <div class="container-inner-section">
          <div class="row">
            <div class="best_seller_container col-md-12 product_container-list-5">
                <!-- <h3><?php echo e(__('lang.popular_items_in_market_head')); ?></h3> -->
                <h2 class="other_watched_products"><?php echo e(__('users.other_watched_product')); ?></h2>
                <ul class="product_details best_seller pl-0" id="other_watched_products">
        					<?php $__currentLoopData = $PopularProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('Front.products_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        				 </ul>

                 <ul class="product_details best_seller pl-0" id="other_watched_services" style="margin-left:4px;display: none;">                 
                  <?php $__currentLoopData = $PopularServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('Front.services_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 
                 </ul>
            </div>


</div>
        </div>
    </div>
</section>
<?php endif; ?>

<script type="text/javascript">
$( document ).ready(function() {
  $("#productSearchFilter").addClass("filterActive");
  $("#serviceSearchFilter").addClass("inactiveFilter");
  $('.service_listings').hide();
  $("#productSearchFilter").click(function(){
  $("#product_service_search_type").val('products');
  $('#product_service_search_from').attr('action',siteUrl+"/products");
  $('#product_service_search_from').attr('onSubmit','');
  $('#product_service_search_from').submit();

 
});
});
   


  $("#serviceSearchFilter").click(function(){
    $("#product_service_search_type").val('products');
    $('#product_service_search_from').attr('action',siteUrl+"/services");
    $('#product_service_search_from').attr('onSubmit','');
    $('#product_service_search_from').submit();
});

function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var city_filter = $('#city_name').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();
  var current_role_id = $(".current_role_id").text();

  $.ajax({
    url:siteUrl+"/get_product_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 
      'sellers' : sellers, 'price_filter' : price_filter,'city_filter' : city_filter, 
       'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string
       , 'role_id' : current_role_id,'path':window.location.pathname },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.product_listings').html(responseObj.products);
     $('.seller_list_content').html(responseObj.sellers);

     $(".product_rating").each(function(){
        var currentRating = $(this).data('rating');
        
        $(this).barrating({
          theme: 'fontawesome-stars',
          initialRating: currentRating,
          onSelect: function(value, text, event) {

            <?php if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1): ?>
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
                                 showSuccessMessage(review_add_msg,'reload');
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
            <?php else: ?>
              window.location.href = "<?php echo e(route('frontLogin')); ?>"; 
              //showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
            <?php endif; ?>
           }
          
         });
      });

    }
   });
}
var segment = "<?php echo Request::segment(1);?>";

if(segment !='annonser'){
  var price_filter = $("#price_filter").slider({});
  price_filter.on('slideStop',function(){
      getListing();
  });
}


$("#city_name").on("input", function() {
  getListing();
});


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
    getListing();

}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/products.blade.php ENDPATH**/ ?>
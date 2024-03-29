
<?php $__env->startSection('middlecontent'); ?>

<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/fontawesome-stars.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.barrating.min.js"></script>

 <!-- Carousel Default -->
<section class="product_section">
    <div class="container-fluid">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
  <div class="container-inner-section">
 
        <?php if(Request::segment(1) =='services' || Request::segment(1) =='products' || Request::segment(1) =='annonser'): ?>
          <?php echo $__env->make('Front.category_breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         
        <?php endif; ?>
        <div class="col-md-3">
            <?php echo $__env->make('Front.products_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9 products-page">
            <span class="current_category" style="display:none;"><?php echo e($category_slug); ?></span>
            <span class="current_subcategory" style="display:none;"><?php echo e($subcategory_slug); ?></span>
            <span class="current_sellers" style="display:none;"><?php echo e($seller_id); ?></span>
            <span class="current_search_string" style="display:none;"><?php echo e($search_string); ?></span>
            <span class="current_role_id" style="display:none;"><?php echo e($current_role_id); ?></span>
            <div class="product_container">
                <div class="row">
                  <div class="col-md-6">
                   <!--  <h2><?php echo e(__('lang.trending_product_head')); ?></h2>
                    <hr class="heading_line"/> -->
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by_order')); ?> : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="getListing()">
                          <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option>
                          <option selected value="asc"><?php echo e(__('lang.sort_by_asc')); ?></option>
                          <option value="desc"><?php echo e(__('lang.sort_by_desc')); ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by')); ?> : </label>
                      <select class="form-control" name="sort_by" id="sort_by" onchange="getListing()">
                        <!--   <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option> -->
                          <option value="name"><?php echo e(__('lang.sort_by_name')); ?></option>
                          <option value="price"><?php echo e(__('lang.sort_by_price')); ?></option>
                          <option value="rating"><?php echo e(__('lang.sort_by_rating')); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <span class="product_listings"><div style="text-align:center;margin-top:50px;"><img src="<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>
    </div>
    </div>

    </div> <!-- /container -->
</section>
<?php if(Request::segment(1) !='annonser'): ?>
<section>
    <div class="container-fluid">
        <div class="row">
          <div class="container-inner-section">
            <div class="best_seller_container">
                <!-- <h3><?php echo e(__('lang.popular_items_in_market_head')); ?></h3> -->
                <h2><?php echo e(__('users.other_watched_product')); ?></h2>
                <ul class="product_details best_seller">
					<?php $__currentLoopData = $PopularProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('Front.products_widget', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				 </ul>
            </div>


</div>
        </div>
    </div>
</section>
<?php endif; ?>

<script type="text/javascript">

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
       , 'role_id' : current_role_id },
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
            <?php else: ?>
                showErrorMessage("<?php echo e(__('errors.login_buyer_required')); ?>");
            <?php endif; ?>
           }
          
         });
      });

    }
   });
}

var price_filter = $("#price_filter").slider({});
price_filter.on('slideStop',function(){
    getListing();
});

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

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/products.blade.php ENDPATH**/ ?>
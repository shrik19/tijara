
<?php $__env->startSection('middlecontent'); ?>


 <!-- Carousel Default -->
<section class="product_section">
    <div class="container">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:40px;">
        <div class="col-md-3">
            <?php echo $__env->make('Front.products_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <span class="current_category" style="display:none;"><?php echo e($category_slug); ?></span>
            <span class="current_subcategory" style="display:none;"><?php echo e($subcategory_slug); ?></span>
            <span class="current_sellers" style="display:none;"><?php echo e($seller_id); ?></span>
            
            <div class="product_container">
                <div class="row">
                  <div class="col-md-12 text-center">
                  <?php if(!empty($logo)): ?> <img src="<?php echo e($logo); ?>" alt="Logo" style="width:100px;height:100px;" />&nbsp;&nbsp;<?php endif; ?><h2><?php echo e($seller_name); ?></h2>
                  </div>
                  <?php if(!empty($header_image)): ?>
                  <div class="col-md-12">
                    <img src="<?php echo e($header_image); ?>" alt="Header Image" style="width:100%;"/>
                  </div>
                  <?php endif; ?>
                  <div class="row"><div class="col-md-12">&nbsp;</div></div>
                  <?php if(!empty($description)): ?>
                  <div class="col-md-12 text-center">
                    <?php echo $description; ?>

                  </div>
                  <?php endif; ?>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by_order')); ?> : </label>
                      <select class="form-control" name="sort_by_order" id="sort_by_order" onchange="getListing()">
                          <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option>
                          <option value="asc"><?php echo e(__('lang.sort_by_asc')); ?></option>
                          <option value="desc"><?php echo e(__('lang.sort_by_desc')); ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo e(__('lang.sort_by')); ?> : </label>
                      <select class="form-control" name="sort_by" id="sort_by" onchange="getListing()">
                          <option value="">---- <?php echo e(__('lang.sort_by_option')); ?> ----</option>
                          <option value="name"><?php echo e(__('lang.sort_by_name')); ?></option>
                          <option value="price"><?php echo e(__('lang.sort_by_price')); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <span class="product_listings"><div style="text-align:center;margin-top:50px;"><img src="<?php echo e(url('/')); ?>/assets/front/img/ajax-loader.gif" alt="loading"></div></span>
            </div>
        </div>
    </div>


    </div> <!-- /container -->
</section>

<script type="text/javascript">

function getListing()
{
  var category_slug = $('.current_category').text();
  var subcategory_slug = $('.current_subcategory').text();
  var sellers = $('.current_sellers').text();
  var price_filter = $('#price_filter').val();
  var sort_by_order = $("#sort_by_order").val();
  var sort_by = $("#sort_by").val();
  var search_string = $(".current_search_string").text();

  $.ajax({
    url:siteUrl+"/get_product_listing",
    headers: {
      'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    },
    type: 'post',
    data : {'page': 1, 'category_slug' : category_slug, 'subcategory_slug' : subcategory_slug, 'sellers' : sellers, 'price_filter' : price_filter, 'sort_order' : sort_by_order, 'sort_by' : sort_by, 'search_string' : search_string },
    success:function(data)
    {
     //$('.product_listings').html(data);
     var responseObj = $.parseJSON(data);
     $('.product_listings').html(responseObj.products);
     $('.seller_list_content').html(responseObj.sellers);
    }
   });
}

var price_filter = $("#price_filter").slider({});
price_filter.on('slideStop',function(){
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

<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/seller-products.blade.php ENDPATH**/ ?>
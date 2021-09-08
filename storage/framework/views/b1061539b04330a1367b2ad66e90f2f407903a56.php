
<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
  
</div>
<div class="container">
  <!-- Example row of columns -->
  
  <div class="row">
  <div class="col-md-2">
        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <div class="col-md-10">
		<?php if($subscribedError): ?>
	    <div class="alert alert-danger"><?php echo e($subscribedError); ?></div>
	    <?php endif; ?>
	  <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	   
	  <div class="card">
		<div class="card-header row">
		<div class="col-md-10">
		    
		  <h2><?php echo e(__('lang.your_products_label')); ?></h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-1">
		  <a href="<?php echo e(route('frontProductCreate')); ?>" title="<?php echo e(__('lang.add_product')); ?>" class="btn btn-black btn-sm debg_color login_btn" ><span><?php echo e(__('lang.add_product')); ?></span> </a>
			</div>
		</div>

		<div class="card-body">
		  <form id="" action="" method="post">
			<?php echo csrf_field(); ?>
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th data-orderable="false"><?php echo e(__('lang.image_label')); ?></th>
				  <th><?php echo e(__('lang.product_label')); ?></th>
				  <th><?php echo e(__('lang.sku_label')); ?></th>
				  <th><?php echo e(__('lang.price_label')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.category_label')); ?></th> 
				  <th><?php echo e(__('lang.sort_order_label')); ?></th>
				  <th><?php echo e(__('lang.dated_label')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.action_label')); ?></th>
				  </tr>
				</thead>
				  <tbody id="result">
				  </tbody>
			  </table>
			</div>
		  </form>  
		</div>
	  </div>
			
    </div>
  </div>
</div> <!-- /container -->
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/dataTables.bootstrap4.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.dataTables.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="<?php echo e(url('/')); ?>/assets/js/sweetalert.js"></script>
<script type="text/javascript">
  var dataTable = $('#productTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    "language": {
        "sSearch": "<?php echo __('lang.datatables.search');?>",
        "sInfo": "<?php echo __('lang.datatables.sInfo');?>",
        "sLengthMenu": "<?php echo __('lang.datatables.sLengthMenu');?>",
        "sInfoEmpty": "<?php echo __('lang.datatables.sInfoEmpty');?>",
        "sLoadingRecords": "<?php echo __('lang.datatables.sLoadingRecords');?>",
        "sProcessing": "<?php echo __('lang.datatables.sProcessing');?>",      
        "sZeroRecords": "<?php echo __('lang.datatables.sZeroRecords');?>",
        "oPaginate": {
              "sFirst":    "<?php echo __('lang.datatables.first');?>",
              "sLast":    "<?php echo __('lang.datatables.last');?>",
              "sNext":    "<?php echo __('lang.datatables.next');?>",
              "sPrevious": "<?php echo __('lang.datatables.previous');?>",
          },
        },
    columnDefs: [
          {
              targets: [1,2],
              className: "text-center",
          }
        ],
    "ajax": {
      headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
      url : '<?php echo e(route("frontProductGetRecords")); ?>',
      'data': function(data){
        data.status = $("#status").val();
		data.category = $("#selectcategory").val();
		data.subcategory = $("#selectsubcategory").val();
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value=""><?php echo e(__("lang.status_label")); ?></option>'+
  '<option value="active"><?php echo e(__("lang.active_label")); ?></option>'+
  '<option value="block"><?php echo e(__("lang.block_label")); ?></option>'+
  '</select></div>').appendTo("#productTable_filter");
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="selectsubcategory" name="subcategory">'+
  
  '<?php echo $subCategoriesHtml; ?>'+
  '</select></div>').appendTo("#productTable_length");
  
  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="selectcategory" name="category">'+
  
  '<?php echo $categoriesHtml; ?>'+
  '</select></div>').appendTo("#productTable_length");
  
  
  
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });
  $('#selectcategory').change(function(){
	  var id =	$(this).val();
	  
	  $('#selectsubcategory').val('');
	  
	  $(".subcatclass").each(function() {
		  $(this).hide();
		});
	  
	  $(".subcat"+id).each(function() {
      $(this).show();
		});
    dataTable.draw();
  });
	$('#selectsubcategory').change(function(){
		dataTable.draw();
  });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Products/index.blade.php ENDPATH**/ ?>

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
		  <h2><?php echo e(__('lang.manage_attributes_menu')); ?> </h2>
		  <hr class="heading_line"/>
		  </div>
		  <div class="col-md-1">
		  <a href="<?php echo e(route('frontAttributeCreate')); ?>" title="<?php echo e(__('lang.add_attribute')); ?>" class="btn btn-black btn-sm debg_color login_btn" ><span><?php echo e(__('lang.add_attribute')); ?></span> </a>
			</div>
		</div>

		<div class="card-body">
		  <form id="" action="" method="post">
			<?php echo csrf_field(); ?>
			<div class="table-responsive">
			   <table class="table table-striped" id="attributeTable">
                <thead>
                  <tr>
                    <th data-orderable="false">&nbsp;</th>
                    <th><?php echo e(__('lang.attribute_label')); ?></th>
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
  var dataTable = $('#attributeTable').DataTable({
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
    "ajax": {
          headers : {'X-CSRF-Token': $('input[name="_token"]').val()},
          url : '<?php echo e(route("frontAttributeGetRecords")); ?>',
          'data': function(data){
              data.status = $("#status").val();
          },
          type:'post',
        }
  });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/ProductAttributes/index.blade.php ENDPATH**/ ?>

<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section-1">
  <!-- Example row of columns -->
  
  <div class="row">
    <?php if($is_seller==1): ?>
      <div class="col-md-2 tijara-sidebar">
        <?php echo $__env->make('Front.layout.sidebar_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <div class="col-md-10 tijara-content">
        <?php else: ?>
        <div class="col-md-12 tijara-content">
      <?php endif; ?>
      
		 
	  <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="seller_info">
	  <div class="card">
		<div class="card-header row seller_header">
      <h2><?php echo e(__('lang.txt_seller_order')); ?></h2>
      <!-- <hr class="heading_line"/> -->
      </div>
    </div>
    <div class="seller_mid_cont">
    <div class="col-md-12">
      <div class="">
        <h1 ><?php echo e(__('messages.info_head')); ?></h1>
        <p  ><?php echo e(__('messages.my_order_info')); ?></p>
        <br/><br/>
      </div>
    </div>
		<div class="col-md-12">
		    
		  
		<div class="card-body">
		  <form id="" action="" method="post">
			<?php echo csrf_field(); ?>
			<div class="table-responsive">
			  <table class="table table-striped" id="productTable">
				<thead>
				  <tr>
				  <th data-orderable="false"><?php echo e(__('lang.txt_order_number')); ?></th>
          <?php if($is_seller): ?>
          <th data-orderable="false"><?php echo e(__('lang.txt_name')); ?></th>
          <?php endif; ?>
				  <th data-orderable="false"><?php echo e(__('lang.txt_subtotal')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.txt_shipping')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.txt_total')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.txt_payment_status')); ?></th> 
				  <th data-orderable="false"><?php echo e(__('lang.txt_order_status')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.txt_date')); ?></th>
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
      url : '<?php echo e(route("frontOrdersGetRecords")); ?>',
      'data': function(data){
        data.status = $("#status").val();
        data.is_seller = "<?php echo e($is_seller); ?>"
        data.user_id = "<?php echo e($user_id); ?>"
      },
       type:'post',
    },
  });

  $('<div class="form-group col-md-4" style="float:right;"><select class="form-control" id="status" name="status">'+
  '<option value=""><?php echo e(__("lang.status_label")); ?></option>'+
  '<option value="PENDING">PENDING</option>'+
  '<option value="SHIPPED">SHIPPED</option>'+
  '<option value="COMPLETE">COMPLETE</option>'+
  '<option value="CANCELLED">CANCELLED</option>'+
  '</select></div>').appendTo("#productTable_filter");  
  
  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/all_orders.blade.php ENDPATH**/ ?>
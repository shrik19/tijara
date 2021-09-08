
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section">
<div class="container-fluid">
  <div class="container-inner-section">
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
	   
	  <div class="card">
		<div class="card-header row">
		<div class="col-md-11">
		    
		  <h2><?php echo e(__('lang.all_service_request')); ?></h2>
		  <hr class="heading_line"/>
		  </div>
		</div>

		<div class="card-body">
		  <form id="" action="" method="post">
			<?php echo csrf_field(); ?>
			<div class="table-responsive">
			  <table class="table table-striped" id="serviceRequestTable">
				<thead>
				  <tr>
				  <th data-orderable="false"><?php echo e(__('lang.service_no_head')); ?></th>
          <?php if($is_seller): ?>
          <th data-orderable="false"><?php echo e(__('lang.txt_name')); ?></th>
          <?php endif; ?>
          <th data-orderable="false"><?php echo e(__('lang.sevice_name_head')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.service_time')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.service_total_cost')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.location')); ?></th>
				  <th data-orderable="false"><?php echo e(__('lang.request_date')); ?></th>
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
</div> <!-- /container -->
</div>
<!-- add subcategory model Form -->
 <div class="modal fade" id="serviceReqDetailsmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo e(__('lang.service_req_details')); ?></h4>
          <button type="button" class="close modal-cross-sign" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <table>
           <!--  <tr><td style="font-weight: 700px;"></td>:<td></td></tr> -->
           <?php if(session('role_id')==2): ?>  
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.cust_label')); ?> <?php echo e(__('lang.txt_name')); ?> :</td><td class="user_name" style="padding-left: 10px;"></td></tr>
            <?php endif; ?>
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.sevice_name_head')); ?> :</td><td class="title" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.service_label')); ?> <?php echo e(__('lang.product_description_label')); ?> :</td><td class="description" style="padding-left: 10px;"></td></tr>
            
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.service_time')); ?> :</td><td class="service_time" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.service_total_cost')); ?> :</td><td class="service_price" style="padding-left: 10px;"></td></tr>
            <tr><td style="font-weight: bold;padding: 5px;"><?php echo e(__('lang.location')); ?> :</td><td class="location" style="padding-left: 10px;"></td></tr>

          </table>
        </div>
              
      </div>
    </div>
  </div>
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/front/css/dataTables.bootstrap4.min.css">
<script src="<?php echo e(url('/')); ?>/assets/front/js/jquery.dataTables.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/front/js/dataTables.bootstrap4.min.js"></script>
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/sweetalert.css">
<!-- General JS Scripts -->
<script src="<?php echo e(url('/')); ?>/assets/js/sweetalert.js"></script>
<script type="text/javascript">
  
$( document ).ready(function() {
   jQuery.noConflict();
   $(".serviceReqDetails").css({"margin-left": ""});
});

  var serviceRequestTable = $('#serviceRequestTable').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "searching": true,
    "order": [[ 0, "desc" ]],
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
      url : '<?php echo e(route("frontServiceRequestGetRecords")); ?>',
      'data': function(data){
        data.monthYear = $("#monthYear").val();
        data.is_seller = "<?php echo e($is_seller); ?>"
        data.user_id = "<?php echo e($user_id); ?>"
      },
       type:'post',
    },
  });

  $("<div class='form-group col-md-4' style='float:right;'>"+
  
  "<?php echo $monthYearHtml; ?>"+
  "</div>").appendTo("#serviceRequestTable_length");

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#monthYear').change(function(){

    serviceRequestTable.draw();
    
  });

  $(document).on("click",".serviceReqDetails",function(event) {      
  
    jQuery.noConflict();
        $('#serviceReqDetailsmodal').find('.id').text($(this).attr('id'));
        $('#serviceReqDetailsmodal').find('.user_name').text($(this).attr('user_name'));
        $('#serviceReqDetailsmodal').find('.title').text($(this).attr('title'));
        $('#serviceReqDetailsmodal').find('.description').text($(this).attr('description'));
        $('#serviceReqDetailsmodal').find('.location').text($(this).attr('location'));
        $('#serviceReqDetailsmodal').find('.service_time').text($(this).attr('service_time'));
        $('#serviceReqDetailsmodal').find('.service_price').text($(this).attr('service_price'));

        $('#serviceReqDetailsmodal').modal('show');
        //$('.modal-backdrop').attr('style','position: relative;');
    }); 

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/all_service_request.blade.php ENDPATH**/ ?>
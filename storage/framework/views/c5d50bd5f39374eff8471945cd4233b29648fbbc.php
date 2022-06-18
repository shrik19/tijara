
<?php $__env->startSection('middlecontent'); ?>
<!-- CSS Libraries -->
<link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/dataTables.bootstrap4.min.css">
<div class="section-body">
  <div class="row">
    <div class="col-12">
      <?php echo $__env->make('Admin.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <div class="card">
        <div class="card-header">
          <h4><?php echo e(__('users.all_email_list')); ?></h4>
          <a href="<?php echo e(route('adminEmailCreate')); ?>" title="<?php echo e(__('users.add_email_btn')); ?>" class="btn btn-icon btn-success" style="margin-left:650px;"><span><?php echo e(__('users.add_email_btn')); ?></span> </a>
        </div>
        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
          <?php echo csrf_field(); ?>
          <div class="table-responsive">
            <table class="table table-striped" id="pageTable">
            <thead>
              <tr>
                <th><?php echo e(__('users.email_title')); ?></th>
                <th><?php echo e(__('users.email_subject')); ?></th>
                <th><?php echo e(__('users.email_created_title')); ?></th>
                <th data-orderable="false"><?php echo e(__('lang.status_label')); ?></th>
                <th data-orderable="false"><?php echo e(__('lang.action_thead')); ?></th>
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

<script src="<?php echo e(url('/')); ?>/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/admin/js/dataTables.buttons.min.js"></script>
<script src="<?php echo e(url('/')); ?>/assets/admin/js/buttons.html5.min.js"></script>
<script type="text/javascript">
  var dataTable = $('#pageTable').DataTable({
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
      url : '<?php echo e(route("adminEmailGetRecords")); ?>',
      'data': function(data){
        data.status = $("#status").val();
      },
      type:'post',
    },
  });

  $(".dataTables_filter label").addClass("pull-right");
  $(".dataTables_filter label").find('.form-control').removeClass('form-control-sm');

  $('#status').change(function(){
    dataTable.draw();
  });

$('.nav-link').click( function() {
    document.getElementById("pageTable").removeAttribute("style");
}); 

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Admin/Email/index.blade.php ENDPATH**/ ?>
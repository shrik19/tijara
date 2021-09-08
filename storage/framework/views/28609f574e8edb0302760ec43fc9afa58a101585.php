
<?php $__env->startSection('middlecontent'); ?>

<div class="containerfluid">
  <div class="col-md-6 hor_strip debg_color">
  </div>
  <div class="col-md-6 hor_strip gray_bg_color">
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="">
        </br>
      <?php echo $__env->make('Front.alert_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <!-- html for seller subscribe packages -->
       
    <div class="col-md-12">
  		<?php if(!empty($package_exp_msg)): ?>
  		<div class="alert alert-danger" role="alert">
		  <?php echo e($package_exp_msg); ?>

		</div>
		<?php endif; ?>
		
		<?php if(count($subscribedPackage) != 0 && !empty($subscribedPackage)): ?>
      	    <h2><?php echo e(__('users.your_active_package')); ?></h2>
        	<hr class="heading_line"/>
	      	<?php $__currentLoopData = $subscribedPackage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	      	
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading package-tbl"><?php echo e($row->title); ?></div>
				<div class="panel-body"  style="">
					<table class="table" style="border: 0px;min-height: 315px;overflow: auto;">
					  <tbody>
					  	<tr>
					  		<td class="package-tbl"><?php echo e(__('users.description_label')); ?></td>
					  		<td><?php echo $row->description; ?></td>
					    </tr>
					    <tr>
					  		<td class="package-tbl"><?php echo e(__('users.amount_label')); ?></td>
					  		<td> <?php echo e($row->amount); ?> kr</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl"><?php echo e(__('users.validity_label')); ?></td>
					  		<td><?php echo e($row->validity_days); ?> Days.</td>
					    </tr>
					    <tr>
					  		<td class="package-tbl"><?php echo e(__('users.purchased_date_label')); ?></td>
					  		<?php if($row->start_date >= date('Y-m-d H:i:s')): ?>
					  			<td><?php echo e(date('l, d F Y',strtotime($row->start_date))); ?></td>
					  			
					  		<?php else: ?>
					  			<td><?php echo e(date('l, d F Y',strtotime($row->start_date))); ?></td>
					  		<?php endif; ?>
					    </tr>
					    <tr>
					  		<td class="package-tbl"><?php echo e(__('users.expiry_date_label')); ?></td>
					  		<td><?php echo e(date('l, d F Y',strtotime($row->end_date))); ?></td>
					    </tr>
					    <tr>
					    	<td class="package-tbl"><?php echo e(__('lang.status_label')); ?></td>
					    	<?php if($row->start_date >= date('Y-m-d H:i:s') && $row->payment_status=="CAPTURED" ): ?>
					  			<td><a href="javascript:void(0)" class="btn btn-warning "> <?php echo e(__('users.not_activated_label')); ?></a></td>
					  		<?php elseif($row->payment_status=="checkout_incomplete"): ?>
					  		<td><a href="javascript:void(0)" class="btn btn-danger"> <?php echo e(__('lang.pending_label')); ?></a>
					  			<p style="font-weight: bold;margin-top: 20px;margin-left:-108px;color: green"> <?php echo e(__('messages.payment_in_process')); ?></p>
					  			<a href="" class="btn btn-info" style="margin-left: 114px;margin-top: -60px"> Reload</a>
					  		</td>
					  		<?php elseif($row->status=="active"): ?>
					  			<td><a href="javascript:void(0)" class="btn btn-success "> <?php echo e(__('lang.active_label')); ?> </a></td>
					  		<?php endif; ?>
					    </tr>
					  	
					  </tbody>
				    </table>
				</div>
				</div>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php endif; ?>
	</div>
	  

	<?php if(count($packageDetails) != 0 && !empty($packageDetails)): ?>
      <div class="col-md-12">
        <h2><?php echo e(__('users.subscribe_package_label')); ?> </h2>
        <hr class="heading_line"/>
	      	<?php $__currentLoopData = $packageDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	      	 <div class="col-md-4">
				<div class="panel panel-default subscribe-packages">
				<div class="panel-heading"><?php echo e($data['title']); ?></div>
				<div class="panel-body" style="max-height: 215px;overflow: auto;">
					<p><?php echo e(__('users.description_label')); ?> : <?php echo $data->description; ?></p>
					<p><?php echo e(__('users.amount_label')); ?> : <?php echo e($data['amount']); ?> kr</p>
					<p><?php echo e(__('users.validity_label')); ?> : <?php echo e($data['validity_days']); ?> Days</p>

					<!-- <form method="POST" action="<?php echo e(route('frontSubscribePackage')); ?>" class="needs-validation" novalidate=""> -->
					<form method="POST" action="<?php echo e(route('frontklarnaPayment')); ?>" class="needs-validation" novalidate="">
						 <?php echo e(csrf_field()); ?>

					 	<input type="hidden" name="user_id" value="<?php echo e($user_id); ?>">
					 	<input type="hidden" name="p_id" value="<?php echo e($data['id']); ?>">
					 	<input type="hidden" name="p_name" value="<?php echo e($data['title']); ?>">
					 	<input type="hidden" name="validity_days" value="<?php echo e($data['validity_days']); ?>">
					 	<input type="hidden" name="amount" value="<?php echo e($data['amount']); ?>">					 	
					 	<button type="submit" name="btnsubscribePackage" id="btnsubscribePackage" class="btn btn-black debg_color login_btn"><?php echo e(__('users.subscribe_btn')); ?></button>
					 </form>
				</div>
				</div>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	   </div>
	   <?php endif; ?>
    </div>
  </div>
</div> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\tijara\resources\views/Front/Packages/index.blade.php ENDPATH**/ ?>
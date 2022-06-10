
<?php $__env->startSection('middlecontent'); ?>

<div class="mid-section p_155">
	<div class="containerfluid">
	  <!--<div class="col-md-6 hor_strip debg_color">
	  </div>
	  <div class="col-md-6 hor_strip gray_bg_color">
	  </div>-->
	  <?php if(!empty($banner->image)): ?>
	  <img class="login_banner" src="<?php echo e(url('/')); ?>/uploads/Banner/<?php echo e($banner->image); ?>" />
	<?php endif; ?>

	<!-- <div class="container"> -->
	  <!-- Example row of columns -->
	  <div class="product_view ov-hi">
		<div class="cmspageDiv">
		 
			<h2><?php echo e($details['title']); ?></h2>
			<hr class="heading_line"/>
			<div>
				
				<?php if($details['slug']=="vanliga-fragor"): ?>
					<?php $__currentLoopData = explode("###", $details['contents']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php 
					$displayQueAns = explode('||', $line);

					 ?>
						<main>
						   <summary class="accordion-faq">
							   <?php echo e($displayQueAns[0]); ?>

						   </summary>						    	
					    	<div class="panel-faq">
					    		<div>
							   		<?php echo e($displayQueAns[1]); ?>

							    </div>
							</div>
						</main>

					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
		
				<?php else: ?>
				  <?php echo $details['contents']; ?>

				<?php endif; ?>
			 
			</div>
	 
		</div>
	  </div>
	</div>
</div>
<!-- </div> --> <!-- /container -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.layout.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tijara\resources\views/Front/pages.blade.php ENDPATH**/ ?>
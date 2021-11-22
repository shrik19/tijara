@extends('Admin.layout.template')
@section('middlecontent')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{url('/')}}/assets/admin/css/dataTables.bootstrap4.min.css">
<div class="section-body">
  <div class="row">
    <div class="col-12">
      @include('Admin.alert_messages')
      <div class="card">
        <div class="card-header">
          <h4>All Packge History <?php if(count($details) !=0){ echo "of ".@$details[0]['fname']; }?></h4>
        </div>

        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="packageHistTable">
                <thead>
                  <tr>
                  <th data-orderable="false">sr no.</th>
                  <th>Package Name</th>
                  <th>Satrt Date</th>
                  <th>End Date</th>
                  <th data-orderable="false">Status</th>
                  </tr>
                </thead>
                  <tbody id="result">
                    <?php
                    if(count($details) !=0){

                    $i=1;
                    foreach($details as $data){ ?>
                      
                      <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $data['title'];?></td>
                        <td><?php echo date("Y-m-d",strtotime($data['start_date']));?></td>
                        <td><?php echo date("Y-m-d",strtotime($data['end_date']));?></td>
                        <?php 
	                      	$curdate=strtotime(date('Y-m-d'));
			                $exp_date=strtotime($data['end_date']);

			                if($curdate > $exp_date)
			                {
			                    //echo '<span class="status expired">Expired</span>';
			                    $status = '<a href="javascript:void(0)"  class="btn btn-icon btn-danger" title="Active"><i class="fa fa-lock"></i> </a>';
			                }else{
			                	$status = '<a href="javascript:void(0)"  class="btn btn-icon btn-success" title="Block"><i class="fa fa-unlock"></i> </a>';
			                }
                        ?>

                        <td><?php echo $status;?></td>
                    </tr>
                  <?php    $i++;
                    } 
                    }else{ ?>
                    	  <td colspan="5" class="text-center">No Records Found.</td>
                   <?php  } ?>

                  </tbody>
              </table>
            </div>
          </form>  
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{url('/')}}/assets/admin/js/jquery.dataTables.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.bootstrap4.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/dataTables.buttons.min.js"></script>
<script src="{{url('/')}}/assets/admin/js/buttons.html5.min.js"></script>
<script type="text/javascript">
 

  
</script>
@endsection('middlecontent')

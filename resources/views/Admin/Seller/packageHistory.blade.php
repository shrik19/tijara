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
          <h4>{{ __('users.all_packge_history_title')}}  @if(count($details) !=0) {{ __('users.of')}} {{@$details[0]['fname']}} @endif  </h4>
        </div>

        <div class="card-body">
          <form id="vendorMultipleAction" action="" method="post">
            @csrf
            <div class="table-responsive">
              <table class="table table-striped" id="packageHistTable">
                <thead>
                  <tr>
                  <th data-orderable="false">{{ __('users.sr_no_thead')}}</th>
                  <th>{{ __('users.package_name_thead')}}</th>
                  <th>{{ __('users.start_date_thead')}}</th>
                  <th>{{ __('users.end_date_thead')}}</th>
                  <th>{{ __('lang.status_label')}}</th>
                  <th data-orderable="false">{{ __('lang.action_label')}}</th>
                  </tr>
                </thead>
                  <tbody id="result">
                    <?php
                    if(count($details) !=0){

                    $i=1;
                    foreach($details as $data){ ?>
                     <?php 
                       if($data['is_trial']==1){
                          $title='Tijara Trial';
                          $start_date=date("Y-m-d",strtotime($data['trial_start_date']));
                          $end_date=date("Y-m-d",strtotime($data['trial_end_date']));
                          $exp_date=strtotime($data['trial_end_date']);
                       }else{
                          $title=$data['title'];
                          $start_date=date("Y-m-d",strtotime($data['start_date']));
                          $end_date=date("Y-m-d",strtotime($data['end_date']));
                          $exp_date=strtotime($data['end_date']);
                       }
                     ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $title;?></td>
                        <td><?php echo $start_date;?></td>
                        <td><?php echo $end_date;?></td>
                        <?php 
	                      	$curdate=strtotime(date('Y-m-d'));
			                   

			                if($curdate > $exp_date)
			                {
			                    //echo '<span class="status expired">Expired</span>';
			                    $status = '<a href="javascript:void(0)"  class="btn btn-icon btn-danger" title="'.__('lang.active_label').'"><i class="fa fa-lock"></i> </a>';
			                }else{
			                	$status = '<a href="javascript:void(0)"  class="btn btn-icon btn-success" title="'.__('lang.block_label').'"><i class="fa fa-unlock"></i> </a>';
			                }

                      if($data['is_trial']==1){
                        $action = '<a href="'.route('adminSellerExtendPackage', base64_encode($data['id'])).'" title="'.__('users.edit_title').'" class="btn btn-icon btn-success"><i class="fas fa-edit"></i> </a>&nbsp;&nbsp;';
                      }else{
                        $action='';
                      }
                      ?>

                        <td><?php echo $status;?></td>
                        <td><?php echo $action;?></td>
                    </tr>
                  <?php    $i++;
                    } 
                    }else{ ?>
                    	  <td colspan="6" class="text-center">
                        {{ __('lang.datatables.sEmptyTable')}}
                        </td>
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
  $('.nav-link').click( function() {
    document.getElementById("packageHistTable").removeAttribute("style");
  }); 
</script>
@endsection('middlecontent')

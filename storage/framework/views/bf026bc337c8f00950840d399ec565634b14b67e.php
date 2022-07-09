<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo e(config('constants.PROJECT_NAME')); ?> | <?php echo e($pageTitle); ?></title>
  <!-- custom css -->
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/custom.css">
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/all.css" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/prism.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/daterangepicker.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/style.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/admin/css/components.css">
  <link rel="stylesheet" href="<?php echo e(url('/')); ?>/assets/css/sweetalert.css">

  <!-- General JS Scripts -->
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/popper.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/jquery.nicescroll.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/moment.min.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/daterangepicker.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/admin/js/stisla.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/js/sweetalert.js"></script>
  <script src="<?php echo e(url('/')); ?>/assets/js/jquery.mask.min.js"></script>

  <script src="<?php echo e(url('/')); ?>/assets/admin/js/admin.js"></script>
  
  <script type="text/javascript">
    function ConfirmDeleteFunction(url, id = false) {
	var message = "<?php echo e(__('messages.alert_delete_record_message')); ?>";
	swal({
      title: "<?php echo e(__('messages.are_you_sure_message')); ?>",
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo e(__('messages.yes_delete_it_message')); ?>",
        cancelButtonText: "<?php echo e(__('messages.no_cancel_message')); ?>",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        location.href=url;
        return true;
      } else {
        return false;
      }
    });
  }

  function ConfirmDeleteFunction1(url, id = false) {
    var message = 'You will not be able to recover this record again!';
   
      
  swal({
      title: "<?php echo e(__('messages.are_you_sure_message')); ?>",
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo e(__('messages.yes_delete_it_message')); ?>",
        cancelButtonText: "<?php echo e(__('messages.no_cancel_message')); ?>",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        location.href=url;
        return true;
      } else {
        return false;
      }
    });
  }
  
  function ConfirmStatusFunction(url, id = false) {
	var message = "<?php echo e(__('messages.you_want_to_change_status_message')); ?>";
	swal({
      title: "<?php echo e(__('messages.are_you_sure_message')); ?>",
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        //cancelButtonClass:"btn-danger",
        confirmButtonText: "<?php echo e(__('messages.yes_change_it_message')); ?>",
        cancelButtonText: "<?php echo e(__('messages.no_cancel_message')); ?>",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        location.href=url;
        return true;
      } else {
        return false;
      }
    });
  }
  </script>
</head>

<body>
<div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <!-- <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep"><i class="far fa-envelope"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">Messages
                <div class="float-right">
                  <a href="#">Mark All As Read</a>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-message">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="../assets/img/avatar/avatar-1.png" class="rounded-circle">
                    <div class="is-online"></div>
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Kusnaedi</b>
                    <p>Hello, Bro!</p>
                    <div class="time">10 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="../assets/img/avatar/avatar-2.png" class="rounded-circle">
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Dedik Sugiharto</b>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                    <div class="time">12 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="../assets/img/avatar/avatar-3.png" class="rounded-circle">
                    <div class="is-online"></div>
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Agung Ardiansyah</b>
                    <p>Sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    <div class="time">12 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="../assets/img/avatar/avatar-4.png" class="rounded-circle">
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Ardian Rahardiansyah</b>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit ess</p>
                    <div class="time">16 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-avatar">
                    <img alt="image" src="../assets/img/avatar/avatar-5.png" class="rounded-circle">
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Alfa Zulkarnain</b>
                    <p>Exercitation ullamco laboris nisi ut aliquip ex ea commodo</p>
                    <div class="time">Yesterday</div>
                  </div>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li> -->
          <!-- <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">Notifications
                <div class="float-right">
                  <a href="#">Mark All As Read</a>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-icons">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div class="dropdown-item-icon bg-primary text-white">
                    <i class="fas fa-code"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    Template update is available now!
                    <div class="time text-primary">2 Min Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-icon bg-info text-white">
                    <i class="far fa-user"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    <b>You</b> and <b>Dedik Sugiharto</b> are now friends
                    <div class="time">10 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-icon bg-success text-white">
                    <i class="fas fa-check"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    <b>Kusnaedi</b> has moved task <b>Fix bug header</b> to <b>Done</b>
                    <div class="time">12 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-icon bg-danger text-white">
                    <i class="fas fa-exclamation-triangle"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    Low disk space. Let's clean it!
                    <div class="time">17 Hours Ago</div>
                  </div>
                </a>
                <a href="#" class="dropdown-item">
                  <div class="dropdown-item-icon bg-info text-white">
                    <i class="fas fa-bell"></i>
                  </div>
                  <div class="dropdown-item-desc">
                    Welcome to Stisla template!
                    <div class="time">Yesterday</div>
                  </div>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li> -->
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?php echo e(url('/')); ?>/assets/admin/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block"><?php echo e(__('lang.hi')); ?>, <?php echo e(ucfirst(Auth::guard('admin')->user()->first_name)); ?> <?php echo e(ucfirst(Auth::guard('admin')->user()->last_name)); ?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <!-- <a href="#" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a> -->
              <a href="<?php echo e(route('adminChangePassword')); ?>" class="dropdown-item has-icon">
                <i class="fas fa-lock"></i> <?php echo e(__('lang.change_password_menu')); ?> 
              </a>
              <!--<a href="features-settings.html" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> Settings
              </a> -->
              <div class="dropdown-divider"></div>
              <a href="<?php echo e(route('adminLogout')); ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> <?php echo e(__('lang.logout_label')); ?> 
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <?php echo $__env->make('Admin.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
         <!-- Content Header (Page header) -->
         <?php echo $__env->make('Admin.layout.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <?php /**PATH D:\wamp64\www\tijara\resources\views/Admin/layout/header.blade.php ENDPATH**/ ?>
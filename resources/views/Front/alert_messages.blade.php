<!-- Common Alert Messages  -->
@if(Session::has('success'))
<div class="col-md-12">
<div class="alert alert-success alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong>{{ __('messages.success')}}</strong> {{Session::get('success')}}
   </div>
</div>
</div>
@endif
@if(Session::has('error'))
<div class="col-md-12">
<div class="alert alert-danger alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong>Oops!</strong> {{Session::get('error')}}
   </div>
</div>
</div>
@endif
@if(Session::has('warning'))
<div class="col-md-12">
<div class="alert alert-warning alert-dismissible show ">
   <div class="alert-body">
   <button class="close" data-dismiss="alert">
      <span>&times;</span>
   </button>
   <strong>{{ __('errors.warning')}}</strong> {{Session::get('warning')}}
   </div>
</div>
</div>
@endif
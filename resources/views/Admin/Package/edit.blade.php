@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">{{ __('users.edit_package_details_title')}}</p>
  <form method="POST" action="{{route('adminPackageUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-8 col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>{{ __('users.title_thead')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="{{ (old('title')) ?  old('title') : $PackageDetails['title']}}">
              <div class="invalid-feedback">
               {{ __('errors.fill_in_title')}}
              </div>
              <div class="text-danger">{{$errors->first('title')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.description_label')}}</label>
         
               <textarea class="form-control" name="description" id="description" spellcheck="true" style="height: 250px;"><?php if(!empty($PackageDetails['description'])){ echo $PackageDetails['description']; }?></textarea>
            </div>

            <div class="form-group">
              <label>{{ __('users.amount_thead')}} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="amount" id="amount" required tabindex="6" value="{{ (old('amount')) ?  old('amount') : $PackageDetails['amount']}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_amount')}}
              </div>
              <div class="text-danger">{{$errors->first('amount')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.validity_for_days_label')}} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="validity_days" id="validity_days" required tabindex="6" value="{{ (old('validity_days')) ?  old('validity_days') : $PackageDetails['validity_days']}}">
              <div class="invalid-feedback">
                {{ __('errors.fill_in_validity_days')}}
              </div>
              <div class="text-danger">{{$errors->first('validity_days')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('users.recurring_payment_label')}}</label>
              <select class="form-control" name="recurring_payment">
              <option value="Yes"  <?php if($PackageDetails['recurring_payment'] == 'Yes'){ echo 'selected'; } ?>>{{ __('users.yes')}}</option>
              <option value="No" <?php if($PackageDetails['recurring_payment'] == 'No'){ echo 'selected'; } ?>>{{ __('users.no')}}</option>

              </select>
              <div class="invalid-feedback">
                {{ __('errors.select_recurring_payment_err')}}
              </div>
              <div class="text-danger">{{$errors->first('recurring_payment')}}</div>
            </div>

            <div class="form-group">
              <label>{{ __('lang.status_thead')}}</label>
              <select class="form-control" name="status">
                <option value="active"  <?php if($PackageDetails['status'] == 'active'){ echo 'selected'; } ?>>{{ __('lang.active_label')}}</option>
                <option value="block" <?php if($PackageDetails['status'] == 'block'){ echo 'selected'; } ?>>{{ __('lang.inactive_label')}}</option>
              </select>
              <div class="invalid-feedback">
                {{ __('errors.select_status_err')}}
              </div>
              <div class="text-danger">{{$errors->first('status')}}</div>
            </div>

            <div class="col-12 text-right">
              <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> {{ __('lang.update_btn')}}</button>&nbsp;&nbsp;
              <a href="{{route('adminPackage')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> {{ __('lang.cancel_btn')}}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<link rel="stylesheet" href="{{url('/')}}/assets/front/css/richtext.min.css">
<script src="{{url('/')}}/assets/front/js/jquery.richtext.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.description').richText();
  }); 
</script>
@endsection('middlecontent')
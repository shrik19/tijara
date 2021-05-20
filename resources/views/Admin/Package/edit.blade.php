@extends('Admin.layout.template')
@section('middlecontent')
<div class="section-body">
  <h2 class="section-title">{{$pageTitle}}</h2>
  <p class="section-lead">Add Package Details</p>
  <form method="POST" action="{{route('adminPackageUpdate', $id)}}" class="needs-validation"  enctype="multipart/form-data" novalidate="">
    @csrf
    <div class="row">
      <div class="col-12 col-md-8 col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title" id="title" required tabindex="1" value="{{ (old('title')) ?  old('title') : $PackageDetails['title']}}">
              <div class="invalid-feedback">
                The Package field is required.
              </div>
              <div class="text-danger">{{$errors->first('title')}}</div>
            </div>

            <div class="form-group">
              <label>Description</label>
           <!--    <textarea class="form-control" id="description" name="description" rows="2" cols="30" style="height:auto" tabindex="2" required><?php if(!empty($PackageDetails['description'])){ echo $PackageDetails['description']; }?></textarea> -->
               <textarea class="form-control description" name="description" id="description" spellcheck="true" ><?php if(!empty($PackageDetails['description'])){ echo $PackageDetails['description']; }?></textarea>
              <div class="invalid-feedback">
                The Package description field is required.
              </div>
              <div class="text-danger">{{$errors->first('description')}}</div>
            </div>

            <div class="form-group">
              <label>Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="amount" id="amount" required tabindex="6" value="{{ (old('amount')) ?  old('amount') : $PackageDetails['amount']}}">
              <div class="invalid-feedback">
                The Amount field is required.
              </div>
              <div class="text-danger">{{$errors->first('amount')}}</div>
            </div>

            <div class="form-group">
              <label>Validity for days <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="validity_days" id="validity_days" required tabindex="6" value="{{ (old('validity_days')) ?  old('validity_days') : $PackageDetails['validity_days']}}">
              <div class="invalid-feedback">
                The Validity for days field is required.
              </div>
              <div class="text-danger">{{$errors->first('validity_days')}}</div>
            </div>

            <div class="form-group">
              <label>Recurring payment</label>
              <select class="form-control" name="recurring_payment">
              <option value="Yes"  <?php if($PackageDetails['recurring_payment'] == 'Yes'){ echo 'selected'; } ?>>Yes</option>
              <option value="No" <?php if($PackageDetails['recurring_payment'] == 'No'){ echo 'selected'; } ?>>No</option>

              </select>
              <div class="invalid-feedback">
                Please select Recurring payment
              </div>
              <div class="text-danger">{{$errors->first('recurring_payment')}}</div>
            </div>

            <div class="form-group">
              <label>Status</label>
              <select class="form-control" name="status">
                <option value="active"  <?php if($PackageDetails['status'] == 'active'){ echo 'selected'; } ?>>Active</option>
                <option value="block" <?php if($PackageDetails['status'] == 'block'){ echo 'selected'; } ?>>Inactive</option>
              </select>
              <div class="invalid-feedback">
                Please select Status
              </div>
              <div class="text-danger">{{$errors->first('status')}}</div>
            </div>

            <div class="col-12 text-right">
              <button type="submit" class="btn btn-icon icon-left btn-success" tabindex="15"><i class="fas fa-check"></i> Update</button>&nbsp;&nbsp;
              <a href="{{route('adminPackage')}}" class="btn btn-icon icon-left btn-danger" tabindex="16"><i class="fas fa-times"></i> Cancel</a>
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
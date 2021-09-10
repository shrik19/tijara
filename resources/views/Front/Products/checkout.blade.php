@extends('Front.layout.template')
@section('middlecontent')

<style>
  .btn span.glyphicon {
    opacity: 1;
}
.error{
    color: red;
  }
</style>

<section class="">
<div class="loader"></div>
<div class="container">
      <div class="row" style="margin-bottom:60px;">
        <div class="col-md-10 col-md-offset-1">
        <?php echo $html_snippet;?>
        </div>
      </div>
    </div>
</section>
@endsection
